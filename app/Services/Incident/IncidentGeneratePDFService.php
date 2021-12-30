<?php


namespace App\Services\Incident;


use App\Helpers\CommonHelpers;
use App\Jobs\IncidentReportNotification;
use App\Models\ShineCompliance\IncidentReport;
use App\Models\ShineCompliance\IncidentReportDocument;
use App\Models\ShineCompliance\IncidentReportInvolvedPerson;
use App\Models\ShineCompliance\IncidentReportPublished;
use App\Repositories\ShineCompliance\IncidentReportRepository;
use App\Repositories\ShineCompliance\IncidentReportPublishedRepository;
use \PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use mikehaertl\pdftk\Pdf as PDFTK;
use Prettus\Validator\Exceptions\ValidatorException;

class IncidentGeneratePDFService
{

    private $incidentReportRepository;
    private $incidentReportPublishedRepository;

    public function __construct(IncidentReportRepository $incidentReportRepository,
                                IncidentReportPublishedRepository $incidentReportPublishedRepository
                                )
    {
        $this->incidentReportRepository = $incidentReportRepository;
        $this->incidentReportPublishedRepository = $incidentReportPublishedRepository;
    }

    public function publishIncidentPDF($is_draft, $incident_data){
        try {
            DB::beginTransaction();
            $user = Auth::user();
            if($is_draft){
                $comment = $user->first_name . " " . $user->last_name . " published Incident Report draft " . $incident_data->reference . " on property " . ($incident_data->property->name ?? '');
                \CommonHelpers::logAudit(INCIDENT_REPORT_TYPE, $incident_data->id, AUDIT_ACTION_DRAFT_PUBLISH, $incident_data->reference ?? '', $incident_data->property_id, $comment, 0 , $incident_data->property_id);
                $message_response = "Incident Report Published as Draft Successfully!";
            } else {
                //update status air test
                $time_now = Carbon::now()->timestamp;
                $this->incidentReportRepository->update(['published_date' => $time_now, 'status' => INCIDENT_REPORT_AWAITING_APPROVAL, 'is_lock' => INCIDENT_REPORT_LOCKED], $incident_data->id);
                $incident_id = $incident_data->id;
                $incident_data = IncidentReport::with('property','equipment','system','documents',
                    'incidentType','involvedPersons','publishedIncidentReport','reportedUser','hsLead')->where('id',$incident_id)->first();
                //todo send mail?
                $comment = $user->first_name . " " . $user->last_name . " published Incident Report " . $incident_data->reference . " on property " . ($incident_data->property->name ?? '');
                \CommonHelpers::logAudit(INCIDENT_REPORT_TYPE, $incident_data->id, AUDIT_ACTION_PUBLISH, $incident_data->reference ?? '', $incident_data->property_id, $comment, 0 , $incident_data->property_id);
                $message_response = "Incident Report Published Successfully!";

                // send mail to asbestos lead and second asbestos lead after publish for approval
                $email_hs_lead = [
                    'subject' => 'Incident Report Ready for Approval Notification',
                    'username' => $incident_data->hsLead->full_name ?? '',
                    'incident_report_reference' => $incident_data->reference ?? '',
                    'property_uprn' => $incident_data->property->property_reference ?? '',
                    'property_name' => $incident_data->property->name ?? '',
                    'login_link' => route('login'),
                    'company_name' => env('APP_DOMAIN') ?? 'Westminster City Council',
                    'domain' => \Config::get('app.url')
                ];
                \Queue::pushOn(INCIDENT_REPORT_NOTIFICATION_QUEUE, new IncidentReportNotification($incident_data->hsLead->email, $email_hs_lead, INCIDENT_REPORT_READY_FOR_APPROVAL_EMAIL));

                $email_second_hs_lead = [
                    'subject' => 'Incident Report Ready for Approval Notification',
                    'username' => $incident_data->secondHsLead->full_name ?? '',
                    'incident_report_reference' => $incident_data->reference ?? '',
                    'property_uprn' => $incident_data->property->property_reference ?? '',
                    'property_name' => $incident_data->property->name ?? '',
                    'login_link' => route('login'),
                    'company_name' => env('APP_DOMAIN') ?? 'Westminster City Council',
                    'domain' => \Config::get('app.url')
                ];
                \Queue::pushOn(INCIDENT_REPORT_NOTIFICATION_QUEUE, new IncidentReportNotification($incident_data->secondHsLead->email, $email_second_hs_lead, INCIDENT_REPORT_READY_FOR_APPROVAL_EMAIL));
            }
            $path_publish_cert = $this->generateIncidentPDF($incident_data);
            $arr_pdf_merge = [$path_publish_cert];
            if(!$incident_data->documents->isEmpty()) {
                foreach ($incident_data->documents as $document){
                    $path = storage_path().'/app/'.$document->path;
                    if(file_exists($path)){
                        $file_info = pathinfo($path);
                        if(isset($file_info['extension']) and $file_info['extension'] == 'pdf') {
                            $arr_pdf_merge[] = $path;
                        }
                    }
                }
            }
            $pdf_merger = new PDFTK($arr_pdf_merge, [
//                'useExec' => true,
            ]);

            $pdf_merger->allow('AllFeatures')      // Change permissions
            ->flatten() ;                // Merge form data into document (doesn't work well with UTF-8!)

            if(!$pdf_merger->saveAs($path_publish_cert)){
                $error = $pdf_merger->getError();
            }

            //todo merge plans
            if($path_publish_cert){
                $response = \CommonHelpers::successResponse($message_response);
            } else {
                $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish Certificate. Please try again!');
            }
            DB::commit();
        } catch (\Exception $exception){
            dd($exception);
            Log::error($exception);
            DB::rollBack();
            $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish Certificate. Please try again!');
        }
        return $response;
    }

    public function generateIncidentPDF($incident_data){
        $incident_ref = $incident_data->reference ?? '';
        $incident_revision = count($incident_data->publishedIncidentReport);
        $data_involved_doc = IncidentReportDocument::where('incident_report_id',$incident_data->id)->get();
        $data_involved = IncidentReportInvolvedPerson::where('incident_report_id',$incident_data->id)->get();
        $header = $incident_ref . "-" . date("d/m/Y") . "-" . date("H:i") . "-UID" . Auth::user()->id;
        $file_type = 'pdf';
        $mime = 'application/pdf';
        $file_name = $incident_ref;
        if($incident_revision > 0){
            $file_name = $incident_ref . '.' . $incident_revision;
        }
        $file_name .= '.' . $file_type;


        //get data
        $data_pdf = [
            'is_pdf' => true,
            'incident_data' => $incident_data,
            'count_doc' => count($data_involved_doc),
            'data_involved' => $data_involved,
            'property' => $incident_data->property,
            'lab' => $incident_data->lab,
            'is_lab_complete' => isset($incident_data->lab) && $incident_data->lab->is_completed == 1 ? true : false,
            'revision' => $incident_revision,
            'user' => $incident_data->reportedUser,
            'current_date' => date("d/m/Y", time()),
            'current_time' => date("H:i", time()),
            'header' => $header
        ];
        //todo first gen
        $pdf = $this->generateIncidentReportDetails($incident_data, $data_pdf);

        if(!$pdf){
            $error = $pdf->getError();
        }

        $this->incidentReportPublishedRepository->create([
            'incident_id' => $incident_data->id,
            'name' => $incident_revision > 0 ? $incident_ref . "." . $incident_revision : $incident_ref,
            'revision' => $incident_revision,
            'type' => $file_type,
            'size' => filesize($pdf) ?? 0,
            'filename' => $file_name,
            'mime' => $mime,
            'path' => $pdf,
            'created_by' => Auth::user()->id,
        ]);
        return $pdf;
    }
    //common
    public function generateCoverIncidentPdf($incident_data, $data_pdf){
        $temp_path = storage_path('/app');
//        $view = '';
//        if($air_test->type_id == CRT_STAGE_4_TYPE){
//            $view = 'pdf.certificate_pdf.cover_certificate_1';
//        } else if($air_test->type_id == CRT_DECONTAMINATION_TYPE){
//            $view = 'pdf.certificate_pdf.cover_certificate_1';
//        }
        $pdf_cover = PDF::loadView('shineCompliance.pdf.incident_pdf.incident_report_cover', $data_pdf)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 0);

        $file_name1 = $incident_data->reference . '_1_' . \Str::random(10).".pdf";

        $save_path1 = $temp_path ."/" . $file_name1;
        // allow overwrite
        $pdf_cover->save($save_path1, true);
        $pdf_cover->resetOptions();

        return $save_path1;
    }

    public function generateIncidentReportDetails($incident_data, $data_pdf){
            $pdf_content_page = PDF::loadView('shineCompliance.pdf.incident_pdf.report_details', $data_pdf)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-top', MARGIN_TOP)
                ->setOption('header-font-size', 8)
                ->setOption('footer-font-size', 8)
                ->setOption('header-right', $data_pdf['header'])
                ->setOption('footer-right', "Page [page] of [toPage]")
                ->setOption('cover', view('shineCompliance.pdf.incident_pdf.incident_report_cover',[
                    'is_pdf' => true,
                    'incident_data' => $incident_data,
                    'property' => $incident_data->property]));

        $file_name = 'report_details_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path = storage_path('app/data/pdfs/incident') ."/" . $file_name;
        // allow overwrite
        $pdf_content_page->save($save_path, true);
        $pdf_content_page->resetOptions();

        return $save_path;
    }

    public function generateSummaryPdf($incident_data, $data_pdf, $offset = 0, $total_page = 0, $first_gen = true){
        $pdf_content_page = PDF::loadView('shineCompliance.pdf.incident_pdf.summary', $data_pdf)
            ->setOption('margin-bottom', MARGIN_BOTTOM)
            ->setOption('margin-top', MARGIN_TOP)
            ->setOption('header-right', $data_pdf['header'])
            ->setOption('footer-right', "Page [page] of [toPage]");

        $file_name5 = '5_' . \Str::random(10).".pdf";
        $temp_path = storage_path('/app');
        $save_path5 = $temp_path ."/" . $file_name5;
        // allow overwrite
        $pdf_content_page->save($save_path5, true);
        $pdf_content_page->resetOptions();

        return $save_path5;
    }

    public function viewPDFIncident($id){
        $publish_incident_pdf = IncidentReportPublished::where('id',$id)->first();

        //log audit
        if (!is_null(\Auth::user())) {
            $comment = (\Auth::user()->full_name ?? 'system')  . " view survey PDF "  . $publish_incident_pdf->name ?? '' . ' on '. optional($publish_incident_pdf->incidentReport)->reference;
            \CommonHelpers::logAudit(SURVEY_TYPE, $publish_incident_pdf->id, AUDIT_ACTION_VIEW, $publish_incident_pdf->name, optional($publish_incident_pdf->incidentReport)->id ,$comment, 0 ,optional($publish_incident_pdf->incidentReport)->property_id);
        }

        if($publish_incident_pdf && file_exists($publish_incident_pdf->path)){
            return response()->file($publish_incident_pdf->path);

        }
        return abort(404);
    }
    public function downloadPDFIncident($id){

        $publish_incident_pdf = IncidentReportPublished::where('id',$id)->first();
        //log audit
        $comment = \Auth::user()->full_name  . " download survey PDF "  . $publish_incident_pdf->name . ' on '. optional($publish_incident_pdf->incidentReport)->reference;
        \CommonHelpers::logAudit(SURVEY_TYPE, $publish_incident_pdf->id, AUDIT_ACTION_DOWNLOAD, $publish_incident_pdf->name, optional($publish_incident_pdf->incidentReport)->id ,$comment, 0 ,optional($publish_incident_pdf->incidentReport)->property_id);
        if($publish_incident_pdf && file_exists($publish_incident_pdf->path)){
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
            return response()->download($publish_incident_pdf->path, $publish_incident_pdf->filename, $headers);

        }
        return abort(404);
    }
}

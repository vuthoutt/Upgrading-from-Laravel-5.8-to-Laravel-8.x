<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Counter;
use App\Models\Location;
use App\Models\Property;
use App\Models\PublishedSurvey;
use App\Models\PublishedWorkRequest;
use App\Models\SampleCertificate;
use App\Models\ShineDocumentStorage;
use App\Models\SitePlanDocument;
use App\Models\SummaryPdf;
use App\Models\Survey;
use App\Models\SurveyDate;
use App\Models\WorkData;
use App\Models\WorkEmailCC;
use App\Models\WorkRequest;
use App\Models\WorkSupportingDocument;
use App\Repositories\AreaRepository;
use App\Repositories\ItemRepository;
use \App\Repositories\LocationRepository;
use App\Repositories\PropertyRepository;
use App\Repositories\SurveyRepository;
use App\User;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use \PDF;
use Illuminate\Http\Request;
use App\Repositories\ProjectRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\AuditTrailRepository;
use mikehaertl\pdftk\Pdf as PDFTK;
use ZipArchive;
use App\Jobs\SendClientEmailNotification;
use App\Jobs\SendApprovalEmail;

class GeneratePDFController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ItemRepository $itemRepository,
                                SurveyRepository $surveyRepository,
                                LocationRepository $locationRepository,
                                PropertyRepository $propertyRepository,
                                AreaRepository $areaRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->surveyRepository = $surveyRepository;
        $this->locationRepository = $locationRepository;
        $this->propertyRepository = $propertyRepository;
        $this->areaRepository = $areaRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function generatePdfFromFile(Request $request){
        $comment =  \Auth::user()->full_name  . " exported to PDF Summary Type : ". $request->summary_type . " SS" . sprintf("%03d", $request->next_number);
        \CommonHelpers::logAudit(SUMMARY_TYPE, $request->next_number, AUDIT_ACTION_EXPORT, $request->summary_type, $request->property_id ?? 0, $comment, 0 , $request->property_id ?? 0);
                //update reference will be name not null like before
        $file_path = $request->file_path;
        $file_path = storage_path().'/app/'.$file_path;



        $pdf = PDF::loadFile($file_path)
            ->setOption('header-font-size', 8)
            ->setOption('footer-font-size', 6)
            ->setOption('footer-right', "Page [page] of [toPage]")
            ->setOption('footer-left', urldecode($request->tagheader));


        if(!\File::exists(storage_path('app/data/pdfs/summary'))) {
            // path does not exist
            mkdir(storage_path('app/data/pdfs/summary'), 0755 , true);
        }
        $save_path = storage_path('app/data/pdfs/summary') ."/" . $request->file_path;
        //for overwrite
        $pdf->save($save_path, true);

        SummaryPdf::create([
            'reference'=> "SS" . sprintf("%03d", $request->next_number),
            'type'=> 3, // summary type
            'object_id'=> $request->property_id ?? 0,
            'file_name'=> $request->file_path,
            'path'=> $save_path
        ]);
        return $pdf->inline('shine_vision.pdf');
    }

    public function downloadPropertyPDF(Request $request)
    {

        $pdf = PDF::loadHTML('pdf.test_pdf');
        return $pdf->inline('hoang_ test.pdf');
        $is_download = $request->download;
        $property_id = $request->property_id;
        if(!is_numeric($property_id)){
            return abort(404);
        }
        $property = Property::where('id',$property_id)->first();
        if($property){

        }
        return abort(404);

        $type = VIEW_PDF; // default will view
        if(isset($is_download) && $is_download == 1){
            $type = DOWNLOAD_PDF;
        }
        $pdf = PDF::loadView('pdf.invoice', $data);
        return $pdf->save('invoice.pdf');
    }

    public function viewPDF(Request $request){
        $type = $request->type;
        $id = $request->id;
        if($type == VIEW_SURVEY_PDF){
            return $this->viewPDFSurvey($id);
        } else {
            return $this->viewPDFWork($id);
        }
        return false;
    }

    private function viewPDFSurvey($id){
        $publish_survey_pdf = PublishedSurvey::where('id',$id)->first();

        //log audit
        if (!is_null(\Auth::user())) {
            $comment = (\Auth::user()->full_name ?? 'system')  . " view survey PDF "  . $publish_survey_pdf->name ?? '' . ' on '. optional($publish_survey_pdf->survey)->reference;
            \CommonHelpers::logAudit(SURVEY_TYPE, $publish_survey_pdf->id, AUDIT_ACTION_VIEW, $publish_survey_pdf->name, optional($publish_survey_pdf->survey)->id ,$comment, 0 ,optional($publish_survey_pdf->survey)->property_id);
        }


        if($publish_survey_pdf && file_exists($publish_survey_pdf->path)){
            return response()->file($publish_survey_pdf->path);

        }
        return abort(404);
    }

    private function viewPDFWork($id){
        $publish_survey_pdf = PublishedWorkRequest::where('id',$id)->first();
        if(!is_null($publish_survey_pdf)){
            //log audit
            if (!is_null(\Auth::user())) {
                $comment = (\Auth::user()->full_name ?? 'system')  . " view work request PDF "  . $publish_survey_pdf->name . ' on '. optional($publish_survey_pdf->survey)->reference;
                \CommonHelpers::logAudit(WORK_REQUEST_TYPE, $publish_survey_pdf->id, AUDIT_ACTION_VIEW, $publish_survey_pdf->name, optional($publish_survey_pdf->survey)->id ,$comment, 0 ,optional($publish_survey_pdf->survey)->property_id);
            }


            if($publish_survey_pdf && file_exists($publish_survey_pdf->path)){
                return response()->file($publish_survey_pdf->path);

            }
        }

        return abort(404);
    }



    public function downloadFile($type, $id , $property_id){
        $file = ShineDocumentStorage::where('object_id', $id)->where('type', $type)->first();
        //audit log
        $auditType =  \CommonHelpers::getAuditObjectType($type);
        $comment =  \Auth::user()->full_name . ' downloaded document ' . ($file->file_name ?? '');
        \CommonHelpers::logAudit($auditType, $id , AUDIT_ACTION_DOWNLOAD, ($file->file_name ?? ''), 0 , $comment , $id , $property_id);

        if (isset($file->path)) {
            if (is_file($file->path)) {
                // return response()->file(storage_path().'/app/'.$file->path);
                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
                return response()->download(storage_path().'/app/'.$file->path, $file->file_name, $headers);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function downloadFileSummary($type, $id){

        $file = SummaryPdf::where('id', $id)->where('type', $type)->first();
        //audit log

        if (isset($file->path)) {
            if (is_file($file->path)) {
                // return response()->file(storage_path().'/app/'.$file->path);
                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
                return response()->download($file->path, $file->file_name, $headers);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function viewFileSummary($type, $id){

        $file = SummaryPdf::where('id', $id)->where('type', $type)->first();

        //audit log

        if (isset($file->path)) {
            if (is_file($file->path)) {
                $file_path = str_replace(storage_path(), '', $file->path);

                $comment =  \Auth::user()->full_name  . " searched summary : ". $file->reference . ($file->comment ?? '');
                \CommonHelpers::logAudit(SUMMARY_TYPE, $file->object_id ?? 0, AUDIT_ACTION_SEARCH, $file->reference ?? 0, 0, $comment, 0 , $request->property_id ?? 0);

                return response()->file(storage_path().$file_path);

            } else {
                return redirect()->back()->with('err', 'No summary available !');
            }
        } else {
            return redirect()->back()->with('err', 'No summary available !');
        }
    }

    public function downloadImage($type, $id, $is_view = false){

        $file = ShineDocumentStorage::where('object_id', $id)->where('type', $type)->first();
        $headers = [
            'Content-Type' => 'image/jpeg',
        ];

        if (isset($file->path)) {
            if (is_file($file->path)) {
                if($is_view){
                    return response()->file(storage_path().'/app/'.$file->path, $headers);
                }
                return response()->download(storage_path().'/app/'.$file->path, $file->file_name, $headers);
            } else {
                return redirect()->back()->with('err', 'No previewed image available !');
            }
        } else {
                return redirect()->back()->with('err', 'No previewed image available !');
        }
    }

    public function downloadPDF($type, $id){

        if($type == DOWNLOAD_SURVEY_PDF){
            return $this->downloadPDFSurvey($id);
        } else {
            return $this->downloadPDFWorkRequest($id);
        }
        return abort(404);
    }

    private function downloadPDFSurvey($id){
        $publish_survey_pdf = PublishedSurvey::where('id',$id)->first();
        //log audit
        $comment = \Auth::user()->full_name  . " download survey PDF "  . $publish_survey_pdf->name . ' on '. optional($publish_survey_pdf->survey)->reference;
        \CommonHelpers::logAudit(SURVEY_TYPE, $publish_survey_pdf->id, AUDIT_ACTION_DOWNLOAD, $publish_survey_pdf->name, optional($publish_survey_pdf->survey)->id ,$comment, 0 ,optional($publish_survey_pdf->survey)->property_id);
        if($publish_survey_pdf && file_exists($publish_survey_pdf->path)){
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
            return response()->download($publish_survey_pdf->path, $publish_survey_pdf->filename, $headers);

        }
        return abort(404);
    }

    private function downloadPDFWorkRequest($id){
        $publish_survey_pdf = PublishedWorkRequest::where('id',$id)->first();
        //log audit
        $comment = \Auth::user()->full_name  . " download work request PDF "  . $publish_survey_pdf->name . ' on '. optional($publish_survey_pdf->survey)->reference;
        \CommonHelpers::logAudit(WORK_REQUEST_TYPE, $publish_survey_pdf->id, AUDIT_ACTION_DOWNLOAD, $publish_survey_pdf->name, optional($publish_survey_pdf->survey)->id ,$comment, 0 ,optional($publish_survey_pdf->survey)->property_id);
        if($publish_survey_pdf && file_exists($publish_survey_pdf->path)){
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
            return response()->download($publish_survey_pdf->path, $publish_survey_pdf->filename, $headers);

        }
        return abort(404);
    }

    public function downloadPDFDocument($id, $type, $name) {

    }

    //generate register pdf for property/area/location
    public function createRegisterPDF(Request $request){
        $type = $request->type;
        $id = $request->id;
        if(in_array($type, [PROPERTY_REGISTER_PDF, AREA_REGISTER_PDF, LOCATION_REGISTER_PDF])){
            try {
                DB::beginTransaction();
                // miss auditrail
                // property pdf need to download as ZIP file
                $next_number = Counter::where('count_table_name','summaries')->first()->total;
                $ss_ref = "SS" . sprintf("%03d", $next_number);


                $data_comment = $this->getCommentRegisterPDF($type, $id, $ss_ref);
                $comment = $data_comment['comment'];
                $property_id = $data_comment['property_id'];
                $property = Property::with('project','propertyInfo')->where('id',$property_id)->first();

                $shine_ref = $property->reference ?? '';
                $type_check = $type == PROPERTY_REGISTER_PDF ? TYPE_ASBESTOS_REGISTER : ($type == AREA_REGISTER_PDF ? TYPE_AREA_CHECK : TYPE_ROOMCHECK ) ;
                $count_file = SummaryPdf::where(['type' => $type_check,'object_id' => $id])->get()->count(); // only property pdf need SS111.xxx

                if($type == PROPERTY_REGISTER_PDF){
                    $file_name_next_number = ($count_file) ? "." . $count_file : "";
                    $file_name = "AsbestosRegister". "_" . $shine_ref. "_"  . date("d_m_Y") . $file_name_next_number . ".pdf";
                } else {
                    $file_name = $ss_ref. ".pdf";
                }
                //log audit
                $comment = \Auth::user()->full_name  . " download property PDF "  . $ss_ref . ' on '. optional($property)->reference;
                \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_DOWNLOAD, $ss_ref, $property->id ,$comment, 0 ,$property->id);

                $items = $this->itemRepository->getRegisterItems($type, $id);
                $inaccessible_locations = $this->locationRepository->getInaccessibleLocations($type, $id);

                $data_sucess = $this->generateRegisterPDF($items, $inaccessible_locations, $property, $data_comment['footer_left'], $ss_ref, $type_check, $file_name, $id);
                Counter::where('count_table_name','summaries')->update(['total'=> $next_number +1]);
                DB::commit();
                if(count($data_sucess)){
                    //zip file for property pdf
                    if(PROPERTY_REGISTER_PDF){
                        return $this->zipFile($data_sucess['path'], $data_sucess['file_name']);
                    }
                    $headers = [
                        'Content-Type' => 'application/pdf',
                    ];
                    return response()->download($data_sucess['path'], $data_sucess['file_name'], $headers);
                }
            } catch (\Exception $e){
                DB::rollBack();

            }
        }
        return abort(404);
    }

    //view property pdf register
    public function viewRegisterPDF(Request $request){
        $type = $request->type;
        $id = $request->id;
        if($type == PROPERTY_REGISTER_PDF){
            try {
//                DB::beginTransaction();
                // miss auditrail
                $ss_ref = '';
                $property = Property::with('project','propertyInfo')->where('id',$id)->first();
                //log audit
                $comment = \Auth::user()->full_name  . " view property PDF on ". optional($property)->reference;
                \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->id ,$comment, 0 ,$property->id);

                $items = $this->itemRepository->getRegisterItems($type, $id);
                $inaccessible_locations = $this->locationRepository->getInaccessibleLocations($type, $id);
//                $property->warning_message = $this->propertyRepository->getWarningMessage($property);
                return view('pdf.register_pdf', [
                    'items' => $items,
                    'inaccessible_locations' => $inaccessible_locations,
                    'property' => $property,
                    'is_pdf' => false,
                    'ss_ref' => $ss_ref,
                    'type' => $type
                ]);
            } catch (\Exception $e){
                DB::rollBack();
//                dd($e);
            }
        }
        return abort(404);
    }
    //get data comment and property reference
    private function getCommentRegisterPDF($type, $id, $ss_ref){
        $comment = '';
        $property_id = '';
        $user = Auth::user();
        if($type == PROPERTY_REGISTER_PDF){
            $property = Property::where('id',$id)->first();
            $property_id = $property->id ?? '';
            $footer_left = "Asbestos Register " . $property->name . " " . date("d_m_Y"); // Them reference
            $comment = $user->first_name . " " . $user->last_name . " exported to PDF Summary Type : ".$ss_ref." Asbestos Register on " . $property->name ?? ''; // Them referecen + description , ,;
        } else if($type == AREA_REGISTER_PDF){
            $area = Area::with('property')->where('id',$id)->first();
            $property_id = $area->property->id ?? '';
            $footer_left = "Area Check" . " - " . $area->property->name ?? '' . " - " . $area->area_reference . " - " . $ss_ref . " - " . date("d/m/y") . "-" . date("H:i") . "-UID" . sprintf("%03d", $user->id);
            $description = implode(', ', array_filter([$area->property->name ?? '' , $area->area_reference ?? '' , $area->description ?? '']));
            $comment = $user->first_name . " " . $user->last_name . " exported to PDF Summary Type : $ss_ref Asbestos Register on " . $description;
        } else if($type == LOCATION_REGISTER_PDF){
            $location = Location::with('property','area')->where('id',$id)->first();
            $property_id = $location->property->id ?? '';
            $footer_left = "Room Check" . " - " . $location->property->name ?? '' . " - " . $location->location_reference . " - " . $ss_ref . " - " . date("d/m/y") . "-" . date("H:i") . "-UID" . sprintf("%03d", $user->id);
            $description = implode(', ', array_filter([$location->property->name ?? '', $location->area->area_reference ?? '' ,
            $location->area->description ?? '', $location->location_reference ?? '' , $location->description ?? '']));
            $comment = $user->first_name . " " . $user->last_name . " exported to PDF Summary Type : $ss_ref Asbestos Register on " . $description;
        }
        return ['comment' => $comment, 'property_id' => $property_id, 'footer_left' => $footer_left];
    }

    private function generateRegisterPDF($items, $inaccessible_locations, $property, $footer_left, $ss_ref, $type, $file_name, $object_id){
        //set warning for cover page
        $risk_type_one = $risk_type_two = NULL;
        if(isset($property->propertyType) && !$property->propertyType->isEmpty()){
            $risk_type_one = $property->propertyType->where('id',1)->first();
            $risk_type_two = $property->propertyType->where('id',2)->first();
        }
        $property->warning_message = $this->propertyRepository->getWarningMessage($property);
        $is_has_inaccessible_voids = $this->propertyRepository->isHasInaccessibleVoidLocation($property->id);
        $pdf = PDF::loadView('pdf.register_pdf', [
            'items' => $items,
            'is_pdf' => true,
            'inaccessible_locations' => $inaccessible_locations,
            'property' => $property,
            'risk_type_one'=>$risk_type_one,
            'risk_type_two'=>$risk_type_two,
            'ss_ref' => $ss_ref,
            'type' => $type
        ])
            ->setOption('header-font-size', 8)
            ->setOption('footer-font-size', 8)
            ->setOption('footer-right', "Page [page] of [toPage]")
            ->setOption('footer-left', $footer_left)
            ->setOption('cover', view('pdf.register_cover',[
                'property' => $property,
                'is_pdf' => true,
                'is_has_inaccessible_voids' => $is_has_inaccessible_voids,
                'risk_type_one'=>$risk_type_one,
                'risk_type_two'=>$risk_type_two,
                'ss_ref' => $ss_ref,
                'type' => $type]))
        ;
        $is_local = env('APP_ENV') != 'local';
        if($is_local){
            $toc_name = "publishedSurveyToc.xsl";
            $toc_path = Config::get('view.paths')[0] . "/pdf/".$toc_name;
            $pdf->setOption('toc' , true)
                ->setOption('xsl-style-sheet',$toc_path);
        }

        $file_type = 'pdf';
        $countRelatedFile = SummaryPdf::whereRaw('file_name REGEXP "'.$file_name.'"')->get()->count();
        $mime = "application/pdf";
        if($countRelatedFile){
            $fileName = $file_name . "." . $countRelatedFile;
        }

        $save_path = storage_path('app/data/pdfs/registers') ."/" . $file_name;
        //for overwrite
        $pdf->save($save_path, true);
        //update reference will be name not null like before
        SummaryPdf::create([
            'reference'=> $ss_ref,
            'type'=> $type,
            'object_id'=> $object_id,
            'file_name'=> $file_name,
            'path'=> $save_path
        ]);
        return file_exists($save_path) ? ['path' => $save_path,'file_name' => $file_name] :  [];
    }

    public function getPublishSurveyManual(){

        return view('surveys.publish_manual');
    }

    public function postPublishSurveyManual(Request $request){
        $survey_ids = $request->surveyIds;
        $array = explode(",",$survey_ids);
        if (count($array)) {
            foreach ($array as $key => $value) {
                $this->publishSurveyManual($value);
            }
        }
        return redirect()->back()->with('msg', 'Published surveys manual successfully!');
    }

    public function publishSurveyManual($survey_id) {
        try {
            //generate survey pdf for public survey
            $survey = Survey::with('property','property.propertyInfo','property.clients',
                'property.propertyInfo.propertyInfoUser','property.clients.clientAddress','property.propertySurvey',
                'client', 'client.clientInfo',
                'project','publishedSurvey',
                'surveySetting','surveyInfo','surveyDate','surveyAnswer','surveyAnswer.dropdownQuestionData','surveyAnswer.dropdownAnswerData',
                'location','locationUndecommission.area','locationUndecommission.locationInfo','locationUndecommission.locationConstruction',
                'itemUndecommission', 'itemUndecommission.AsbestosTypeValue','itemUndecommission.asbestosTypeView','itemUndecommission.area','itemUndecommission.location','itemUndecommission.sample','itemUndecommission.productDebrisView','itemUndecommission.extentView','itemUndecommission.accessibilityVulnerabilityView',
                'itemUndecommission.additionalInformationView', 'itemUndecommission.licensedNonLicensedView','itemUndecommission.actionRecommendationView',
                'itemUndecommission.ItemNoAccessValue.ItemNoAccess',
                'itemUndecommission.ActionRecommendationValue','itemUndecommission.specificLocationView',
                'itemUndecommission.pasPrimary.getData','itemUndecommission.pasSecondary.getData',
                'itemUndecommission.pasLocation.getData','itemUndecommission.pasAccessibility.getData',
                'itemUndecommission.pasExtent.getData','itemUndecommission.pasNumber.getData',
                'itemUndecommission.pasHumanFrequency.getData','itemUndecommission.pasAverageTime.getData',
                'itemUndecommission.pasType.getData','itemUndecommission.pasMaintenanceFrequency.getData',
                'itemUndecommission.masProductDebris.getData','itemUndecommission.masDamage.getData',
                'itemUndecommission.masTreatment.getData','itemUndecommission.masAsbestos.getData')->where('id',$survey_id)->first();
            if (is_null($survey)) {
               return 'survey doest not exist';
            }
            $user = Auth::user();
            $property_name = $survey->property->name ?? "";

            $comment = $user->first_name . " " . $user->last_name . " published Survey draft " . $survey->reference . " on property " . $property_name;
            \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_DRAFT_PUBLISH, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
            $message_response = "Survey Published as Draft Successfully!";

             $path_publish_survey = $this->generateSurveyPDF($survey);

            $sample_certificates_pdf = SampleCertificate::with('shineDocumentStorage')->where('survey_id',$survey_id)->get();
            $plans                   = SitePlanDocument::with('shineDocumentStorage')->where('survey_id',$survey_id)->get();

            $arr_pdf_merge = [$path_publish_survey];
            if(!$sample_certificates_pdf->isEmpty()){
                foreach ($sample_certificates_pdf as $sc){
                    if($sc->shineDocumentStorage){
                        $path = storage_path().'/app/'.$sc->shineDocumentStorage->path;
                        if(isset($sc->shineDocumentStorage) && file_exists($path)){
                            $file_info = pathinfo($path);
                            if(isset($file_info['extension']) && $file_info['extension'] == 'pdf'){
                                $arr_pdf_merge[] = storage_path().'/app/'.$sc->shineDocumentStorage->path;
                            }
                        }
                    }
                }
            }
            if(!$plans->isEmpty()) {
                foreach ($plans as $p){
                    if($p->shineDocumentStorage){
                        $path = storage_path().'/app/'.$p->shineDocumentStorage->path;
                        if(isset($p->shineDocumentStorage) && file_exists($path)){
                            $file_info = pathinfo($path);
                            if(isset($file_info['extension']) && $file_info['extension'] == 'pdf') {
                                $arr_pdf_merge[] = storage_path() . '/app/' . $p->shineDocumentStorage->path;
                            }
                        }
                    }
                }
            }

            $pdf_merger = new PDFTK($arr_pdf_merge, [

            ]);

            $pdf_merger->allow('AllFeatures')      // Change permissions
            ->flatten() ;                // Merge form data into document (doesn't work well with UTF-8!)

            if(!$pdf_merger->saveAs($path_publish_survey)){
                $error = $pdf_merger->getError();
            }

            return 'done';
        } catch (\Exception $e) {
            $publishSurvey = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish survey. Please try again!');
        }
    }

    public function publishSurveyPDF($survey_id, Request $request){

        $survey_draf = $request->has('survey_draf') ? true : false;
        try {

            //generate survey pdf for public survey
            DB::beginTransaction();
            $survey = Survey::with('property','property.propertyInfo','property.clients',
                'property.propertyInfo.propertyInfoUser','property.clients.clientAddress','property.propertySurvey',
                'client', 'client.clientInfo',
                'project','publishedSurvey',
                'surveySetting','surveyInfo','surveyDate','surveyAnswer','surveyAnswer.dropdownQuestionData','surveyAnswer.dropdownAnswerData',
                'location','locationUndecommission.area','locationUndecommission.locationInfo','locationUndecommission.locationConstruction',
                'itemUndecommission', 'itemUndecommission.AsbestosTypeValue','itemUndecommission.asbestosTypeView','itemUndecommission.area','itemUndecommission.location','itemUndecommission.sample','itemUndecommission.productDebrisView','itemUndecommission.extentView','itemUndecommission.accessibilityVulnerabilityView',
                'itemUndecommission.additionalInformationView', 'itemUndecommission.licensedNonLicensedView','itemUndecommission.actionRecommendationView',
                'itemUndecommission.ItemNoAccessValue.ItemNoAccess',
                'itemUndecommission.ActionRecommendationValue','itemUndecommission.specificLocationView',
                'itemUndecommission.pasPrimary.getData','itemUndecommission.pasSecondary.getData',
                'itemUndecommission.pasLocation.getData','itemUndecommission.pasAccessibility.getData',
                'itemUndecommission.pasExtent.getData','itemUndecommission.pasNumber.getData',
                'itemUndecommission.pasHumanFrequency.getData','itemUndecommission.pasAverageTime.getData',
                'itemUndecommission.pasType.getData','itemUndecommission.pasMaintenanceFrequency.getData',
                'itemUndecommission.masProductDebris.getData','itemUndecommission.masDamage.getData',
                'itemUndecommission.masTreatment.getData','itemUndecommission.masAsbestos.getData')->where('id',$survey_id)->first();
            if (is_null($survey)) {
                return redirect()->back()->with('err', 'Survey does not exist');
            }
            $user = Auth::user();
            $property_name = $survey->property->name ?? "";
            if ($survey_draf) {
                $comment = $user->first_name . " " . $user->last_name . " published Survey draft " . $survey->reference . " on property " . $property_name;
                \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_DRAFT_PUBLISH, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                $message_response = "Survey Published as Draft Successfully!";
            } else {
                $time_now = Carbon::now()->timestamp;
                $publish_survey = Survey::where('id', $survey_id)->update(['status' => PULISHED_SURVEY_STATUS, 'is_locked' => SURVEY_LOCKED]);
                $publish_date = SurveyDate::where('survey_id', $survey_id)->update(['published_date' =>   $time_now]);
                $survey->surveyDate->published_date = $time_now;
                // lock survey
                $this->surveyRepository->lockSurvey($survey_id);

                // missing send notification emails, update later + audit traitr
                //send mail queue
                \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                                $survey->client->name,
                                SURVEY_APPROVED_EMAILTYPE,
                                $survey->lead_by,
                                $survey->property->property_reference ?? '',
                                $survey->property->name ?? '',
                                $survey->property->propertyInfo->pblock ?? '',
                                $survey->reference,
                                \Auth::user()->clients->name
                            ));

                \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmailNotification(
                                $survey->client->name,
                                SURVEY_APPROVED_EMAILTYPE,
                                $survey->second_lead_by,
                                $survey->property->property_reference ?? '',
                                $survey->property->name ?? '',
                                $survey->property->propertyInfo->pblock ?? '',
                                $survey->reference,
                                \Auth::user()->clients->name
                            ));

                //log audit
                $comment = $user->first_name . " " . $user->last_name . "  published Survey " . $survey->reference . " on property " . $property_name;
                \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_PUBLISH, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);

                $message_response = "Survey Published Successfully!";
            }
             $path_publish_survey = $this->generateSurveyPDF($survey);

            $sample_certificates_pdf = SampleCertificate::with('shineDocumentStorage')->where('survey_id',$survey_id)->get();
            $plans                   = SitePlanDocument::with('shineDocumentStorage')->where('survey_id',$survey_id)->get();

            $arr_pdf_merge = [$path_publish_survey];
            if(!$sample_certificates_pdf->isEmpty()){
                foreach ($sample_certificates_pdf as $sc){
                    if($sc->shineDocumentStorage){
                        $path = storage_path().'/app/'.$sc->shineDocumentStorage->path;
                        if(isset($sc->shineDocumentStorage) && file_exists($path)){
                            $file_info = pathinfo($path);
                            if(isset($file_info['extension']) && $file_info['extension'] == 'pdf'){
                                $arr_pdf_merge[] = storage_path().'/app/'.$sc->shineDocumentStorage->path;
                            }
                        }
                    }
                }
            }
            if(!$plans->isEmpty()) {
                foreach ($plans as $p){
                    if($p->shineDocumentStorage){
                        $path = storage_path().'/app/'.$p->shineDocumentStorage->path;
                        if(isset($p->shineDocumentStorage) && file_exists($path)){
                            $file_info = pathinfo($path);
                            if(isset($file_info['extension']) && $file_info['extension'] == 'pdf') {
                                $arr_pdf_merge[] = storage_path() . '/app/' . $p->shineDocumentStorage->path;
                            }
                        }
                    }
                }
            }

            $pdf_merger = new PDFTK($arr_pdf_merge, [
//                'useExec' => true,
            ]);

            $pdf_merger->allow('AllFeatures')      // Change permissions
            ->flatten() ;                // Merge form data into document (doesn't work well with UTF-8!)

            if(!$pdf_merger->saveAs($path_publish_survey)){
                $error = $pdf_merger->getError();
            }

            if($path_publish_survey){
                $publishSurvey = \CommonHelpers::successResponse($message_response);
            } else {
                $publishSurvey = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish survey. Please try again!');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::debug($e);
            $publishSurvey = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish survey. Please try again!');
        }

        if (isset($publishSurvey) and !is_null($publishSurvey)) {
            if ($publishSurvey['status_code'] == 200) {
                return redirect()->back()->with('msg', $publishSurvey['msg']);
            } else {
                return redirect()->back()->with('err', $publishSurvey['msg']);
            }
        }
    }

    public function publishWorkRequestPDF($work_request_id, Request $request){

        $work_request_draf = $request->has('work_request_draf') ? true : false;

        try {
            //generate survey pdf for public survey
            DB::beginTransaction();
            //update later
            $work_request = WorkRequest::find($work_request_id);
            if (is_null($work_request)) {
                return redirect()->back()->with('err', 'Work Request does not exist');
            }
            $user = Auth::user();
            $property_name = $work_request->property->name ?? "";
            if ($work_request_draf) {
                $comment = $user->first_name . " " . $user->last_name . " published Work Request draft " . $work_request->reference . " on property " . $property_name;
                \CommonHelpers::logAudit(WORK_REQUEST_TYPE, $work_request->id, AUDIT_ACTION_DRAFT_PUBLISH, $work_request->reference, $work_request->property_id ,$comment, 0 ,$work_request->property_id);
                 WorkRequest::where('id', $work_request_id)->update(['status' => WORK_REQUEST_READY_QA]);
                $message_response = "Work Request Published as Draft Successfully!";
            } else {
                //log audit
                $comment = $user->first_name . " " . $user->last_name . "  published Work Request " . $work_request->reference . " on property " . $property_name;
                \CommonHelpers::logAudit(WORK_REQUEST_TYPE, $work_request->id, AUDIT_ACTION_PUBLISH, $work_request->reference, $work_request->property_id ,$comment, 0 ,$work_request->property_id);
                WorkRequest::where('id', $work_request_id)->update(['status' => WORK_REQUEST_AWAITING_APPROVAL, 'is_locked' => 1,'published_date' => time()]);
                $message_response = "Work Request Published Successfully!";

                // send email
                $data_user = User::where('id', PUBLISH_WORK_REQUEST_USER_ID)->first();
                // major work
                if($work_request->is_major == 1) {
                    $property_ref = 'Multiple';
                    $property_name = 'Multiple';
                } else {
                    $property_ref = $work_request->property->property_reference ?? '';
                    $property_name = $work_request->property->name ?? '';
                }
                $emailCC = WorkEmailCC::where('work_id', $work_request->id)->first();
                $additional_email = [];
                if($emailCC){
                    $additional_email =  explode(",", $emailCC->email);
                }
                $data = [
                    'subject' =>'Work Request Ready for Approval',
                    'work_requester' => $data_user->full_name ?? '',
                    'email' => $data_user->email ?? '',
                    "block_reference" => $work_request->property->pblock ?? '',
                    'work_request_reference' => $work_request->reference ?? '',
                    'work_requester_type' => $work_request->workData->description ?? '',
                    'property_reference' => $property_ref,
                    'property_name' => $property_name,
                    'company_name' => $data_user->clients->name ?? '',
                    'property_postcode' => $work_request->property->propertyInfo->postcode ?? '',
                    'domain' => \Config::get('app.url')
                ];
                // if send to work requester

                \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendApprovalEmail($data, PUBLISHED_WORK_REQUEST_EMAIL_QUEUE,$additional_email));
            }
            $path_publish_work_request = $this->generateWorkRequestPDF($work_request);
                //merge document later
            $support_docs = WorkSupportingDocument::with('shineDocumentStorage')->where('work_id',$work_request_id)->get();
            $arr_pdf_merge = [$path_publish_work_request];
            if(!$support_docs->isEmpty()){
                foreach ($support_docs as $sd){
                    if($sd->shineDocumentStorage){
                        $path = storage_path().'/app/'.$sd->shineDocumentStorage->path;
                        if(isset($sd->shineDocumentStorage) && file_exists($path)){
                            $file_info = pathinfo($path);
                            if(isset($file_info['extension']) && $file_info['extension'] == 'pdf'){
                                $arr_pdf_merge[] = storage_path().'/app/'.$sd->shineDocumentStorage->path;
                            }
                        }
                    }
                }
            }

            $pdf_merger = new PDFTK($arr_pdf_merge, [
               'useExec' => true,
            ]);

            $pdf_merger->allow('AllFeatures')      // Change permissions
            ->flatten() ;                // Merge form data into document (doesn't work well with UTF-8!)

            if(!$pdf_merger->saveAs($path_publish_work_request)){
                $error = $pdf_merger->getError();
            }

            if($path_publish_work_request){
                $message = \CommonHelpers::successResponse($message_response);
            } else {
                $message = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish work request. Please try again!');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            $message = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to publish work request. Please try again!');
        }

        if (isset($message) and !is_null($message)) {
            if ($message['status_code'] == 200) {
                return redirect()->back()->with('msg', $message['msg']);
            } else {
                return redirect()->back()->with('err', $message['msg']);
            }
        }
    }

    public function genWorkRequestAfterApprovePDF($work_request_id){

            //generate survey pdf for public survey

            //update later
            $work_request = WorkRequest::find($work_request_id);
            $user = \Auth::user();
            $property_name = $work_request->property->name ?? "";
            $path_publish_work_request = $this->generateWorkRequestPDF($work_request);
                //merge document later
            $support_docs = WorkSupportingDocument::with('shineDocumentStorage')->where('work_id',$work_request_id)->get();
            $arr_pdf_merge = [$path_publish_work_request];
            if(!$support_docs->isEmpty()){
                foreach ($support_docs as $sd){
                    if($sd->shineDocumentStorage){
                        $path = storage_path().'/app/'.$sd->shineDocumentStorage->path;
                        if(isset($sd->shineDocumentStorage) && file_exists($path)){
                            $file_info = pathinfo($path);
                            if(isset($file_info['extension']) &&$file_info['extension'] == 'pdf'){
                                $arr_pdf_merge[] = storage_path().'/app/'.$sd->shineDocumentStorage->path;
                            }
                        }
                    }
                }
            }

            $pdf_merger = new PDFTK($arr_pdf_merge, [
               'useExec' => true,
            ]);

            $pdf_merger->allow('AllFeatures')      // Change permissions
            ->flatten() ;                // Merge form data into document (doesn't work well with UTF-8!)

            if(!$pdf_merger->saveAs($path_publish_work_request)){
                $error = $pdf_merger->getError();
            }
            return true;
    }

    private function generateSurveyPDF($survey){
        if($survey){
            $survey_ref = $survey->reference;
            $survey_revision = count($survey->surveyPublishSurvey);
            //UPDATE ASBESTOS TYPE FOR INACCESSIBLE ITEMS:
            $this->itemRepository->updateAsbestosType($survey->id);
            $this->itemRepository->updateActionRecommentdationNoAcm($survey->id);
            $header = $survey_ref . "-" . date("d/m/Y") . "-" . date("H:i") . "-UID" . Auth::user()->id;

            $sample_survey = $this->surveyRepository->getActiveSamplesTable($survey->property_id, $survey->id) ;
            $item_survey = $survey->itemUndecommission ;
            // count item noacm and asbestos type != send to lab and other
            $count_item_tested = 0;
            $location_highrisk_accessible = [];
            $inaccessible_locations = $count_acm_item = $count_noacm_item = [];
            $high_risk_item = $inaccessible_items = $action_recommendation_items = $action_recommendation_removal_items = $acm_items = $item_r_and_d = $location_items = [];
            $location_inaccessible_void = $location_inaccessible_void_display = [];
            $samples = [];
            $survey_method_question_data = $survey->surveyAnswer;

            if(count($item_survey)){

                $item_survey = $this->itemRepository->sortItemSurvey($item_survey);
                foreach ($item_survey as $item){
                    // get location specific, MAS score need to convert to view later
                    $item->specific_location = $item->specificLocationView->specific_location ?? '';
                    //mas
                    $item->product_type = $item->masProductDebris->getData->score ?? 0;
                    $item->extend_damage = $item->masDamage->getData->score ?? 0;
                    $item->surface_treatment = $item->masTreatment->getData->score ?? 0;
                    $item->asbestos_fibre = $item->masAsbestos->getData->score ?? 0;
                    //pas
                    $pasPrimary = $item->pasPrimary->getData->score ?? 0;
                    $pasSecondary = $item->pasSecondary->getData->score ?? 0;
                    $pasLocation = $item->pasLocation->getData->score ?? 0;
                    $pasAccessibility = $item->pasAccessibility->getData->score ?? 0;
                    $pasExtent = $item->pasExtent->getData->score ?? 0;
                    $pasNumber = $item->pasNumber->getData->score ?? 0;
                    $pasHumanFrequency = $item->pasHumanFrequency->getData->score ?? 0;
                    $pasAverageTime = $item->pasAverageTime->getData->score ?? 0;
                    $pasType = $item->pasType->getData->score ?? 0;
                    $pasMaintenanceFrequency = $item->pasMaintenanceFrequency->getData->score ?? 0;
                    $item->primary = round(($pasPrimary + $pasSecondary)/2);
                    $item->likelihood =  round(($pasLocation + $pasAccessibility + $pasExtent)/3);
                    $item->human_exposure_potential = round(($pasNumber + $pasHumanFrequency + $pasAverageTime)/3);
                    $item->maintenance_activity = round(($pasType + $pasMaintenanceFrequency)/2);

                    $asbestos_type = $item->AsbestosTypeValue->dropdown_data_item_id ?? NULL;
                    // 394,395,396,397,398 is child of Other, 380 is send to lab
                    if($item->state != ITEM_NOACM_STATE && isset($asbestos_type) && !in_array($asbestos_type, [380, 394,395,396,397,398])){
                        $count_item_tested++;
                    }

                    if($item->state == ITEM_ACCESSIBLE_STATE){
                        if($item->total_mas_risk >= 10){
                            $high_risk_item[] = $item;
                            if (!in_array($item->location_id, $location_highrisk_accessible)){
                                $location_highrisk_accessible[] = $item->location_id;
                            }
                        }
                    }

                    if($item->state == ITEM_INACCESSIBLE_STATE){
                        $inaccessible_items[] = $item;
                    }

                    if(isset($item->ActionRecommendationValue) && isset($item->ActionRecommendationValue->dropdown_data_item_id) && $item->ActionRecommendationValue->dropdown_data_item_id > 0){
                        $action_recommendation_items[] = $item;
                        if(in_array($item->ActionRecommendationValue->dropdown_data_item_id, ACTION_RECOMMENDATION_LIST_ID)){
                            $action_recommendation_removal_items[] = $item;
                        }
                    }

                    if(isset($item->itemInfo->is_r_and_d_element) && $item->itemInfo->is_r_and_d_element > 0){
                        $item_r_and_d[] = $item;
                    }

                    if($item->sample){
                        if($item->record_id == $item->sample->original_item_id){
                            $samples[] = $item;
                        }
                    }

                    if($item->state != ITEM_NOACM_STATE){
                        $acm_items[] = $item;
                    }

                    // get total acm item and no acm item
                    if(!array_key_exists($item->location_id,$count_acm_item)){
                        $count_acm_item[$item->location_id] = 0;
                    }

                    if(!array_key_exists($item->location_id,$count_noacm_item)){
                        $count_noacm_item[$item->location_id] = 0;
                    }
                    $location_items[$item->location_id]['items'][] = $item;
                    if($item->decommissioned == ITEM_UNDECOMMISSION){
                        if($item->state == ITEM_NOACM_STATE){
                            $count_noacm_item[$item->location_id] ++;
                        } else{
                            $count_acm_item[$item->location_id] ++;
                        }
                    }

                    $location_items[$item->location_id]['total_acm_item'] = $count_acm_item[$item->location_id];
                    $location_items[$item->location_id]['total_noacm_item'] = $count_noacm_item[$item->location_id];
                }
            }

            //get location inaccess list
            $locations = $survey->locationUndecommission;
            $location_order = [];
            foreach ($locations as $key => $location) {
                //Negative Lookahead (?!^\-), keep minus char if it is at first
                $intAreaRef = preg_replace("/(?!^\-)[^a-zA-Z0-9]+/", ".", trim($location->area->area_reference));
                $intAreaRef = $intAreaRef ?? $location->area->id;
                $intLocRef = preg_replace("/(?!^\-)[^a-zA-Z0-9]+/", ".", trim($location->location_reference));
                $intLocRef = $intLocRef ?? $location->id;
                $location_order[$intAreaRef][$location->area->id][$intLocRef][$location->id][] = $location;
            }
            $sorted_location = [];
            if(count($location_order)){
                ksort($location_order);
                foreach ($location_order as $k1 => $v1) {
                    ksort($v1);
                    foreach ($v1 as $k2 => $v2) {
                        ksort($v2);
                        foreach ($v2 as $v3) {
                            ksort($v3);
                            foreach ($v3 as $v4) {
                                ksort($v4);
                                foreach ($v4 as $v5) {
                                    $sorted_location[] = $v5;
                                }
                            }
                        }
                    }
                }
            }
            $action_recommendation_items = $this->sortItemRecommendation($action_recommendation_items);
            $data_inacc_loc = $this->getLocationInaccessibleData($locations);
            $samples = $this->sortSample($samples);
            if(count($data_inacc_loc)){
                $location_inaccessible_void = $data_inacc_loc[0];
                $location_inaccessible_void_display = $data_inacc_loc[1];
                $inaccessible_locations = $data_inacc_loc[2];
            }
            $pdf = PDF::loadView('pdf.publish_survey', [
                'is_pdf' => true,
                'survey' => $survey,
                'survey_revision' => $survey_revision,
                'sample_survey' => $sample_survey,
                'count_item_tested' => $count_item_tested,
                'count_location_high_risk' => count($location_highrisk_accessible),
                'high_risk_item' => $high_risk_item,
                'inaccessible_locations' => $inaccessible_locations,
                'location_inaccessible_void' => $location_inaccessible_void,
                'location_inaccessible_void_display' => $location_inaccessible_void_display,
                'inaccessible_items' => $inaccessible_items,
                'survey_method_question_data' => $survey_method_question_data,
                'action_recommendation_items' => $action_recommendation_items,
                'samples' => $samples,
                'locations' => $sorted_location,
                'location_items' => $location_items,
                'action_recommendation_removal_items' => $action_recommendation_removal_items,
                'item_r_and_d' => $item_r_and_d,
                'acm_items' => $acm_items
            ])
                ->setOption('header-font-size', 8)
                ->setOption('footer-font-size', 8)
                ->setOption('footer-right', "Page [page] of [toPage]")
                ->setOption('header-right', $header)

                ->setOption('footer-left', $survey_ref)
                ->setOption('cover', view('pdf.survey_cover',[
                    'is_pdf' => true,
                    'survey' => $survey,
                    'property' => $survey->property]))
            ;
            $is_local = env('APP_ENV') != 'local';
            if($is_local){
                $toc_name = "publishedSurveyToc.xsl";
                $toc_path = Config::get('view.paths')[0] . "/pdf/".$toc_name;
                $pdf->setOption('toc' , true)
                    ->setOption('xsl-style-sheet',$toc_path);
            }
            $file_name = $survey_ref;
            $file_type = 'pdf';
            $mime = "application/pdf";
            if($survey_revision > 0){
                $file_name = $survey_ref . "." . $survey_revision;
            }
            $file_name .= "." . $file_type;

            $save_path = storage_path('app/data/pdfs/surveys') ."/" . $file_name;
            // allow overwrite
            $pdf->save($save_path, true);
            $data_ps = ['survey_id' => $survey->id,
                'name' => $survey_revision > 0 ? $survey_ref . "." . $survey_revision : $survey_ref,
                'revision' => $survey_revision,
                'type' => $file_type,
                'size' => filesize($save_path),
                'filename' => $file_name,
                'mime' => $mime,
                'path' => $save_path,
                'created_by' => Auth::user()->id,
                'is_large_file' => filesize($save_path) < 100000000 ? 0 : 1];
            PublishedSurvey::create($data_ps);

            return $save_path;
        }
        return false;
    }

    private function generateWorkRequestPDF($work_request){
        if($work_request){
            $work_request_ref = $work_request->reference;
            $work_request_revision = count($work_request->publishedWorkRequest);
            $header = $work_request_ref . "-" . date("d/m/Y") . "-" . date("H:i") . "-UID" . Auth::user()->id;
            $work_requester = $work_request->requester ?? NULL;
            $work_asbestos_lead = $work_request->asbestosLead ?? NULL;
            $parking_arrangements = WorkData::where('id',$work_request->workPropertyInfo->parking_arrangements ?? 0)->first();
            $ceiling_height = WorkData::where('id',$work_request->workPropertyInfo->ceiling_height ?? 0)->first();
            $air_test_type = WorkData::where('id',$work_request->workScope->air_test_type ?? 0)->first();


            $property_ids = explode(",",$work_request->property_id_major);
            $properties = Property::whereIn('id',$property_ids)->get();

            // GET PROP CONTACT
            $list_contact = $list_non_user = [];
            $arr_user_ids = $work_request->team ?? [];
            if(count($arr_user_ids)){
                $list_contact = User::whereIn('id',$arr_user_ids)->get();
            }
            $list_non_user = $work_request->workContact ?? [];


            if ($work_request->is_major == 1) {
                $pdf = PDF::loadView('pdf.publish_work_request_major', [
                    'is_pdf' => true,
                    'work_request' => $work_request,
                    'work_request_revision' => $work_request_revision,
                    'list_contact' => $list_contact,
                    'list_non_user' => $list_non_user,
                    'work_requester' => $work_requester,
                    'work_type' => $work_request->work_type,
                    'work_asbestos_lead' => $work_asbestos_lead,
                    'parking_arrangements' => $parking_arrangements,
                    'ceiling_height' => $ceiling_height,
                    'air_test_type' => $air_test_type,
                    'properties' => $properties
                ])
                ->setOption('header-font-size', 8)
                ->setOption('footer-font-size', 8)
                ->setOption('footer-right', "Page [page] of [toPage]")
                ->setOption('header-right', $header)

                ->setOption('footer-left', $work_request_ref)
                ->setOption('cover', view('pdf.work_request_cover',[
                    'is_pdf' => true,
                    'work_request' => $work_request,
                    'property' => $work_request->property]))
                ;
                # code...
            } else {
                $pdf = PDF::loadView('pdf.publish_work_request', [
                    'is_pdf' => true,
                    'work_request' => $work_request,
                    'work_request_revision' => $work_request_revision,
                    'list_contact' => $list_contact,
                    'list_non_user' => $list_non_user,
                    'work_requester' => $work_requester,
                    'work_type' => $work_request->work_type,
                    'work_asbestos_lead' => $work_asbestos_lead,
                    'parking_arrangements' => $parking_arrangements,
                    'ceiling_height' => $ceiling_height,
                    'air_test_type' => $air_test_type
                ])
                ->setOption('header-font-size', 8)
                ->setOption('footer-font-size', 8)
                ->setOption('footer-right', "Page [page] of [toPage]")
                ->setOption('header-right', $header)

                ->setOption('footer-left', $work_request_ref)
                ->setOption('cover', view('pdf.work_request_cover',[
                    'is_pdf' => true,
                    'work_request' => $work_request,
                    'property' => $work_request->property]))
                ;
            }

            $is_local = env('APP_ENV') != 'local';
            if($is_local){
                $toc_name = "publishedSurveyToc.xsl";
                $toc_path = Config::get('view.paths')[0] . "/pdf/".$toc_name;
                $pdf->setOption('toc' , true)
                    ->setOption('xsl-style-sheet',$toc_path);
            }
            $file_name = $work_request_ref;
            $file_type = 'pdf';
            $mime = "application/pdf";
            if($work_request_revision > 0){
                $file_name = $work_request_ref . "." . $work_request_revision;
            }
            $file_name .= "." . $file_type;

            $save_path = "data/work/pdfs/" . $file_name;
            // allow overwrite
            $pdf->save($save_path, true);
            $data_ps = ['work_request_id' => $work_request->id,
                'name' => $work_request_revision > 0 ? $work_request_ref . "." . $work_request_revision : $work_request_ref,
                'revision' => $work_request_revision,
                'type' => $file_type,
                'size' => filesize($save_path),
                'filename' => $file_name,
                'mime' => $mime,
                'path' => $save_path,
                'created_by' => Auth::user()->id,
                'is_large_file' => filesize($save_path) < 100000000 ? 0 : 1];
            PublishedWorkRequest::create($data_ps);
            return $save_path;
        }
        return false;
    }

    private function getLocationInaccessibleData($locations){
        $location_inaccessible_void = $location_inaccessible_void_display = $inaccessible_locations = [];
        if(count($locations)){
            foreach ($locations as $location){

                //inaccess location
                if(isset($location->state) && $location->state == LOCATION_STATE_INACCESSIBLE){
                    $inaccessible_locations[] = $location;
                }
                //inaccess reason answers in construction void
                $location_construction =  $location->locationVoid ?? NULL;
                if($location_construction){
                    $inaccess_ceiling_void = in_array(1108,explode(",",$location->locationVoid->ceiling));
                    $inaccess_floor_void = in_array(1453,explode(",",$location->locationVoid->floor));
                    $inaccess_cavities_void = in_array(1216,explode(",",$location->locationVoid->cavities));
                    $inaccess_riser_void = in_array(1280,explode(",",$location->locationVoid->risers));
                    $inaccess_ducting_void = in_array(1344,explode(",",$location->locationVoid->ducting));
                    $inaccess_boxing_void = in_array(1733,explode(",",$location->locationVoid->boxing));
                    $inaccess_pipewor_void = in_array(1606,explode(",",$location->locationVoid->pipework));
                    if($inaccess_ceiling_void || $inaccess_floor_void || $inaccess_cavities_void
                        || $inaccess_riser_void || $inaccess_ducting_void || $inaccess_boxing_void || $inaccess_pipewor_void){
                        $location_inaccessible_void[] = $location;
                        // check to display Inaccessible Void Room/locations in table
                        if($inaccess_ceiling_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1108]['void_type'] = 'Ceiling Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1108]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->ceiling, optional($location->locationVoid)->ceiling_other );
                        }

                        if($inaccess_floor_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1453]['void_type'] = 'Floor Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1453]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->floor, optional($location->locationVoid)->floor_other );
                        }

                        if($inaccess_cavities_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1216]['void_type'] = 'Cavities Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1216]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->cavities, optional($location->locationVoid)->cavities_other );
                        }

                        if($inaccess_riser_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1280]['void_type'] = 'Risers Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1280]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->risers, optional($location->locationVoid)->risers_other );
                        }

                        if($inaccess_ducting_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1344]['void_type'] = 'Ducting Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1344]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->ducting, optional($location->locationVoid)->ducting_other );
                        }

                        if($inaccess_boxing_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1733]['void_type'] = 'Boxing Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1733]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->boxing, optional($location->locationVoid)->boxing_other );
                        }

                        if($inaccess_pipewor_void){
                            $location_inaccessible_void_display[$location->id]['area_ref'] = $location->area->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['loc_ref'] = $location->title_presentation ?? '';
                            $location_inaccessible_void_display[$location->id]['reason'][1606]['void_type'] = 'Pipework Void';
                            $location_inaccessible_void_display[$location->id]['reason'][1606]['value'] = \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->pipework, optional($location->locationVoid)->pipework_other );
                        }
                    }
                }
            }
            return [$location_inaccessible_void, $location_inaccessible_void_display, $inaccessible_locations];
        }
        return [];
    }

    private function sortSample($samples){
        $sorted_samples = $temp_samples = [];
        foreach ($samples as $sample){
            $des = preg_replace("/[^a-zA-Z0-9]+/", ".", $sample->sample->description ?? 0);
            $des = $des ?? $sample->sample->id ?? 0;
            $temp_samples[$des][] = $sample;
        }
        if(count($temp_samples)){
            ksort($temp_samples);
            foreach ($temp_samples as $k1 => $v1) {
                ksort($v1);
                foreach ($v1 as $v2){
                    $sorted_samples[] = $v2;
                }
            }
        }
        return $sorted_samples;
    }


    //zip a file and delete zip after zip is done
    private function zipFile($file_path, $file_name){
        $public_dir=public_path();
        $path_parts = pathinfo($file_name);
        $zip_file_name = $path_parts['filename'] . ".zip";
        $zip_destination = $public_dir. '/zip_folder/' . $zip_file_name;
        $zip = new ZipArchive();
        if($zip->open($zip_destination, ZipArchive::CREATE) === TRUE){
            if(file_exists($file_path)){
                $new_filename = substr($file_path,strrpos($file_path,'/') + 1);
                $zip->addFile($file_path,$new_filename);
            }
        }
        $zip->close();

        return response()->download($zip_destination, $zip_file_name, array('Content-Type: application/octet-stream','Content-Length: '. filesize($zip_destination)))->deleteFileAfterSend(true);
    }

    private function sortItemRecommendation($items){
        $sorted_item = [];
        $item_order = [];
        foreach ($items as $key => $item) {
            $intAreaRef = preg_replace("/(?!^\-)[^a-zA-Z0-9]+/", ".", $item->area->area_reference);
            $intAreaRef = $intAreaRef ?? $item->area->id;
            $intLocRef = preg_replace("/(?!^\-)[^a-zA-Z0-9]+/", ".", $item->location->location_reference);
            $intLocRef = $intLocRef ?? $item->location->id;
            $item_order[$intAreaRef][$item->area->id][$intLocRef][$item->location->id][] = $item;
        }
        if(count($item_order)){
            ksort($item_order);
            foreach ($item_order as $k1 => $v1) {
                ksort($v1);
                foreach ($v1 as $k2 => $v2) {
                    ksort($v2);
                    foreach ($v2 as $k3 => $v3) {
                        ksort($v2);
                        foreach ($v3 as $k4 => $v4) {
                            ksort($v4);
                            foreach ($v4 as $v5) {
                                $sorted_item[] = $v5;
                            }
                        }
                    }
                }
            }
        }
        return $sorted_item;
    }
}

<?php


namespace App\Services\ShineCompliance;

use App\Jobs\IncidentReportNotification;
use App\Models\ShineCompliance\IncidentReport;
use App\Models\ShineCompliance\IncidentReportDocument;
use App\Models\ShineCompliance\IncidentReportInvolvedPerson;
use App\Models\ShineCompliance\IncidentReportPublished;
use App\Models\ShineCompliance\User;
use App\Repositories\ShineCompliance\IncidentReportDocumentRepository;
use App\Repositories\ShineCompliance\IncidentReportInvolvedPersonRepository;
use App\Repositories\ShineCompliance\IncidentReportRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use \PDF;
use mikehaertl\pdftk\Pdf as PDFTK;

class IncidentReportService
{
    private $incidentReportRepository;
    private $incidentReportInvolvedPersonRepository;

    public function __construct(
        IncidentReportRepository $incidentReportRepository,
        IncidentReportInvolvedPersonRepository $incidentReportInvolvedPersonRepository
    )
    {
        $this->incidentReportRepository = $incidentReportRepository;
        $this->incidentReportInvolvedPersonRepository = $incidentReportInvolvedPersonRepository;
    }

    public function getIncidentDetail($incident_id, $relations = []){
        return $this->incidentReportRepository->getIncidentReportDetail($incident_id, $relations);
    }

    public function getIncidentReportTypes($dropdown_id)
    {
        return $this->incidentReportRepository->getIncidentReportTypes($dropdown_id);
    }

    public function getAsbestosLeadIncident() {
        return User::where(['is_locked' => USER_UNLOCKED])->whereIn('id', [1, 122])->orderBy('first_name','ASC')->get();
    }

    public function createOrUpdateIncidentReporting($data = [], $id = null)
    {
        try {
            \DB::beginTransaction();
            $report_type = $data['type'] ?? null;
            $data_incident_reporting = [
                'report_recorder' => $data['report_recorder'] ?? null,
                'asbestos_lead' => HS_LEAD_USER,
                'second_asbestos_lead' => SECOND_HS_LEAD_USER,
                'call_centre_team_member_name' => $data['call_centre_team_member_name'] ?? null,
                'type' => $report_type,
                'date_of_report' => isset($data['date_of_report']) ? \CommonHelpers::toTimeStamp($data['date_of_report']) : \CommonHelpers::toTimeStamp(date('d/m/Y')),
                'time_of_report' => $data['time_of_report'] ?? date('H:i'),
                'reported_by' => $data['reported_by'] ?? null,
                'reported_by_other' => $data['reported_by_other'] ?? null,
                'property_id' =>  isset($data['is_address_in_wcc'])  ? ($data['property_id'] ?? null) : 0 ,
                'is_address_in_wcc' => isset($data['is_address_in_wcc']) ? 1 : 0,
                'address_building_name' => $data['address_building_name'] ?? null,
                'address_street_number' => $data['address_street_number'] ?? null,
                'address_street_name' => $data['address_street_name'] ?? null,
                'address_town' => $data['address_town'] ?? null,
                'address_county' => $data['address_county'] ?? null,
                'address_postcode' => $data['address_postcode'] ?? null,
                'equipment_id' => $data['equipment_id'] ?? null,
                'system_id' => $data['system_id'] ?? null,
                'details' => $data['details'] ?? null,
                'category_of_works' => $data['category_of_works'] ?? null,
                'is_risk_assessment' => (\CommonHelpers::checkArrayKey($data, 'is_risk_assessment') == 'on') ? true : false,
            ];
            $data_incident_type = [];
            $is_involved = false;
            if ($report_type == INCIDENT || $report_type == SOCIAL_CARE) {
                $is_involved = (\CommonHelpers::checkArrayKey($data, 'is_involved') == 'on') ? true : false;
                $data_incident_type = [
                    'date_of_incident' => isset($data['date_of_incident']) ? \CommonHelpers::toTimeStamp($data['date_of_incident']) : null,
                    'time_of_incident' => $data['time_of_incident'] ?? null,
                    'confidential' => (\CommonHelpers::checkArrayKey($data, 'confidential') == 'on') ? true : false,
                    'is_involved' => $is_involved,
                ];
            }

            $data_incident_summary = array_merge($data_incident_reporting, $data_incident_type);
            if (is_null($id)) {
                $data_incident_summary['status'] = INCIDENT_REPORT_CREATED_STATUS;
                $incident_report = $this->incidentReportRepository->createIncidentReporting($data_incident_summary);
                if ($incident_report) {
                    $incident_report_id = $incident_report->id;
                    $incident_reference = 'IR' . $incident_report_id;
                    $this->incidentReportRepository->updateIncidentReporting($incident_report_id, ['reference' => $incident_reference]);

                    // save incident persons and incident reasons
                    if (($report_type == INCIDENT || $report_type == SOCIAL_CARE) && $is_involved) {
                        if (count($data['involved'])) {
                            $data_incident_persons = $this->convertDataOfIncidentPersons($data['involved'], $incident_report_id);
                            IncidentReportInvolvedPerson::insert($data_incident_persons);
                        }
                    }
                }
            } else {
                $data_incident_summary['status'] = INCIDENT_REPORT_READY_QA;
                $incident_report = $this->incidentReportRepository->getIncidentReportDetail($id, ['property', 'equipment', 'system', 'documents', 'involvedPersons']);
                if ($incident_report) {
                    $this->incidentReportRepository->updateIncidentReporting($id, $data_incident_summary);

                    // save incident persons and incident reasons
                    if (($report_type == INCIDENT || $report_type == SOCIAL_CARE) && $is_involved) {
                        if (count($data['involved'])) {
                            $data_incident_persons = $this->convertDataOfIncidentPersons($data['involved'], $incident_report->id);
                            $this->incidentReportInvolvedPersonRepository->deleteIncidentPersons($incident_report->id);
                            IncidentReportInvolvedPerson::insert($data_incident_persons);
                        }
                    }
                }
            }

            // save incident reporting documents
            if(isset($data['documents'])) {
                $documents = $this->convertDataIncidentDocuments($data['documents'], $incident_report->id);
                IncidentReportDocument::insert($documents);
                \DB::update("UPDATE incident_report_documents SET reference = CONCAT('IRD', id) WHERE reference IS NULL AND incident_report_id = ". $incident_report->id);
            }

            \DB::commit();
            if (is_null($id)) {
                //log audit
                \CommonHelpers::logAudit(INCIDENT_REPORTING_TYPE, $incident_report->id, AUDIT_ACTION_ADD, $incident_report->reference, $incident_report->property_id, null, 0, $incident_report->property_id);
                return \CommonHelpers::successResponse('Created Incident Reporting Successfully!', $incident_report);
            } else {
                //log audit
                \CommonHelpers::logAudit(INCIDENT_REPORTING_TYPE, $incident_report->id, AUDIT_ACTION_EDIT, $incident_report->reference, $incident_report->property_id, null, 0, $incident_report->property_id);
                return \CommonHelpers::successResponse('Updated Incident Reporting Successfully!', $incident_report);
            }
        } catch (\Exception $e) {
            dd($e);
            \DB::rollback();
            \Log::error($e);
            return \CommonHelpers::failResponse(STATUS_FAIL_CLIENT, 'Failed to adding Incident Reproting! Please try again');
        }
    }

    public function convertDataOfIncidentPersons($data_involved = [], $incident_report_id = null) {
        $involved_persons = [];
        $data_incident_persons = [];
        foreach ($data_involved as $involved) {
            if (!empty($involved['person'])) {
                $person = $involved['person'];
                if (!in_array($person, $involved_persons) || ($person == -1)) {
                    $non_user = $involved['name'] ?? '';
                    if(!empty($involved['reasons']['injury_type'])) {
                        $injury_types = [];
                        foreach ($involved['reasons']['injury_type'] as $involved_injury) {
                            if (!in_array($involved_injury, $injury_types) && $involved_injury != INCIDENT_REPORT_INJURY_OTHER_TYPE) {
                                array_push($injury_types, $involved_injury);
                            }
                            if($involved_injury == INCIDENT_REPORT_INJURY_OTHER_TYPE) {
                                array_push($injury_types, $involved_injury);
                            }
                        }
                        $injury_type = implode(', ', $injury_types);
                    } else {
                        $injury_type = null;
                    }
                    $injury_type_other = !empty($involved['reasons']['injury_type_other']) ? implode(', ', array_unique($involved['reasons']['injury_type_other'])) : null;
                    $part_of_body_affected = !empty($involved['reasons']['part_of_body_affected']) ? implode(', ', array_unique($involved['reasons']['part_of_body_affected'])) : null;
                    if(!empty($involved['reasons']['apparent_cause'])) {
                        $apparent_causes = [];
                        foreach ($involved['reasons']['apparent_cause'] as $involved_apparent) {
                            if (!in_array($involved_apparent, $apparent_causes) && $involved_apparent != INCIDENT_REPORT_APPARENT_CAUSE_OTHER_TYPE) {
                                array_push($apparent_causes, $involved_apparent);
                            }
                            if($involved_apparent == INCIDENT_REPORT_APPARENT_CAUSE_OTHER_TYPE) {
                                array_push($apparent_causes, $involved_apparent);
                            }
                        }
                        $apparent_cause = implode(', ', $apparent_causes);
                    } else {
                        $apparent_cause = null;
                    }
                    $apparent_cause_other = !empty($involved['reasons']['apparent_cause_other']) ? implode(', ', array_unique($involved['reasons']['apparent_cause_other'])) : null;
                    $data_incident_persons[] = [
                        'incident_report_id' => $incident_report_id,
                        'user_id' => $person,
                        'non_user' => $non_user,
                        'injury_type' => $injury_type,
                        'injury_type_other' => $injury_type_other,
                        'part_of_body_affected' => $part_of_body_affected,
                        'apparent_cause' => $apparent_cause,
                        'apparent_cause_other' => $apparent_cause_other,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    array_push($involved_persons, $person);
                }
            }
        }
        return $data_incident_persons;
    }

    public function convertDataIncidentDocuments($data_documents = [], $incident_report_id = null)
    {
        $date = Carbon::now();
        $path = 'data/' . INCIDENT_REPORTING_DOCUMENT_PATH .'/'. $date->format('Y/m/d')   .'/' . $incident_report_id . '/';
        $documents = [];
        foreach ($data_documents as $file) {
            Storage::disk('local')->put($path, $file);
            $documents[] = [
                'incident_report_id' => $incident_report_id,
                'path' => $path. $file->hashName(),
                'filename' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'added_by' => \Auth::user()->id,
                'created_at' =>  Carbon::now(),
                'updated_at' =>  Carbon::now(),
            ];
        }
        return $documents;
    }

    public function updateOrCreateDocument($data = [], $id = null)
    {
        if (!empty($data) && isset($data['document'])) {
            $date = Carbon::now();
            $incident_report_id = \CommonHelpers::checkArrayKey3($data,'incident_report_id');
            $path = 'data/' . INCIDENT_REPORTING_DOCUMENT_PATH .'/'. $date->format('Y/m/d')   .'/' . $incident_report_id . '/';
            $file = $data['document'];
            $data_incident_reporting_doc = [
                'incident_report_id' => $incident_report_id,
                'path' => $path. $file->hashName(),
                'filename' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'added_by' => \Auth::user()->id,
            ];
            try {
                \DB::beginTransaction();
                if (is_null($id)) {
                    Storage::disk('local')->put($path, $file);
                    $incident_reporting_doc = IncidentReportDocument::create($data_incident_reporting_doc);
                    if ($incident_reporting_doc) {
                        $document_ref = "IRD" . $incident_reporting_doc->id;
                        IncidentReportDocument::where('id', $incident_reporting_doc->id)->update(['reference' => $document_ref]);
                    }
                    $response = \CommonHelpers::successResponse('Created Incident Reporting Document successfully !');
                    //log audit
                    \CommonHelpers::logAudit(INCIDENT_REPORTING_DOC_TYPE, $incident_reporting_doc->id, AUDIT_ACTION_ADD, $document_ref, $incident_reporting_doc->incident_report_id, null , 0, 0);
                } else {
                    $incident_reporting_doc = IncidentReportDocument::where('id', $id)->first();
                    Storage::delete($incident_reporting_doc->path);
                    IncidentReportDocument::where('id', $id)->update($data_incident_reporting_doc);
                    Storage::disk('local')->put($path, $file);
                    $response = \CommonHelpers::successResponse('Updated Incident Reporting Document successfully !');

                    //log audit
                    \CommonHelpers::logAudit(INCIDENT_REPORTING_DOC_TYPE, $incident_reporting_doc->id, AUDIT_ACTION_EDIT, $incident_reporting_doc->reference, $incident_reporting_doc->incident_report_id, null , 0, 0);
                }

                \DB::commit();
                return $response;
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::error($e);
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload Incident Reporting Document. Please try again !');
            }
        }
    }

    public function getIncidentReportByStatus($status)
    {
        return $this->incidentReportRepository->getIncidentReportByStatus($status);
    }

    public function getDecommissionedReports()
    {
        return $this->incidentReportRepository->getDecommissionedIncidentReports();
    }

    public function getApprovalIncident()
    {
        return $this->incidentReportRepository->getApprovalIncident();
//        if (\CommonHelpers::isSystemClient()) {
//            // property privilege
//            return $this->incidentReportRepository->getApprovalIncident();
//        } else {
//            return $this->incidentReportRepository->where('status', INCIDENT_REPORT_AWAITING_APPROVAL)
//                ->where('decommissioned', INCIDENT_UNDECOMMISSION)
//                ->get();
//        }
    }

    public function getUserApprovalIncident()
    {
        return $this->incidentReportRepository->getUserApprovalIncident();
    }

    public function getRejectIncident()
    {
            return $this->incidentReportRepository->getRejectIncident();
    }

    public function getUserRejectIncident()
    {
            return $this->incidentReportRepository->getUserRejectIncident();
    }

    public function getIncidentReportsProperty($property_id = null, $decommission = INCIDENT_UNDECOMMISSION)
    {
        return $this->incidentReportRepository->getIncidentReportsProperty($property_id, $decommission);
    }

    public function getIncidentReportsUser($user_id = null, $decommission = INCIDENT_UNDECOMMISSION)
    {
        return $this->incidentReportRepository->getIncidentReportsUser($user_id, $decommission);
    }

    public function approvalIncident($incident_id){
        try {
            \DB::beginTransaction();
            $data_approval = [
                'status' => INCIDENT_REPORT_COMPLETE,
                'completed_date' => Carbon::now()->timestamp
            ];
            $approved = $this->incidentReportRepository->approvalIncident($incident_id, $data_approval);
            if ($approved) {
                $incident_report = IncidentReport::where('id', $incident_id)->first();
                $file = IncidentReportPublished::where('incident_id', $incident_id)->orderBy('revision', 'desc')->first();
                $link_view_pdf = route('shineCompliance.incident_reporting.view_pdf.email', ['type'=> VIEW_SURVEY_PDF, 'id'=> $file->id ?? 0]);

                // send mail to report requester if not Call Centre Staff
                if (!$incident_report->reportRecorder->is_call_centre_staff) {
                    $email_to_reporter = [
                        'subject' => 'Incident Report Approved Notification',
                        'username' => $incident_report->reportRecorder->full_name ?? '',
                        'incident_report_reference' => $incident_report->reference ?? '',
                        'property_uprn' => $incident_report->property->property_reference ?? '',
                        'property_name' => $incident_report->property->name ?? '',
                        'link_incident_report_detail' => route('shineCompliance.incident_reporting.incident_Report', ['incident_id' => $incident_id]),
                        'link_incident_report_pdf' => $link_view_pdf,
                        'company_name' => env('APP_DOMAIN') ?? 'Westminster City Council',
                        'domain' => \Config::get('app.url')

                    ];
                    \Queue::pushOn(INCIDENT_REPORT_NOTIFICATION_QUEUE, new IncidentReportNotification($incident_report->reportRecorder->email, $email_to_reporter, INCIDENT_REPORT_APPROVED_EMAIL));
                }

                // send mail to h&s team
                if (env('HS_TEAM_EMAIL')) {
                    $hs_team_email = env('HS_TEAM_EMAIL');
                    $email_to_hs_team = [
                        'subject' => 'Incident Report Approved Notification',
                        'username' => 'H&S Team',
                        'incident_report_reference' => $incident_report->reference ?? '',
                        'property_uprn' => $incident_report->property->property_reference ?? '',
                        'property_name' => $incident_report->property->name ?? '',
                        'link_incident_report_detail' => route('shineCompliance.incident_reporting.incident_Report', ['incident_id' => $incident_id]),
                        'link_incident_report_pdf' => $link_view_pdf,
                        'company_name' => env('APP_DOMAIN') ?? 'Westminster City Council',
                        'domain' => \Config::get('app.url')
                    ];
                    \Queue::pushOn(INCIDENT_REPORT_NOTIFICATION_QUEUE, new IncidentReportNotification($hs_team_email, $email_to_hs_team, INCIDENT_REPORT_APPROVED_EMAIL));
                }

                // send mail to property contacts have Housing Officer
                $contacts = $this->incidentReportRepository->getIncidentHousingOfficerContacts($incident_report->property->id ?? 0);
                if(!$contacts->isEmpty()) {
                    $email_to_housing_officer = [
                        'subject' => 'Incident Report Approved Notification',
                        'incident_report_reference' => $incident_report->reference ?? '',
                        'property_uprn' => $incident_report->property->property_reference ?? '',
                        'property_name' => $incident_report->property->name ?? '',
                        'link_incident_report_detail' => route('shineCompliance.incident_reporting.incident_Report', ['incident_id' => $incident_id]),
                        'link_incident_report_pdf' => $link_view_pdf,
                        'company_name' => env('APP_DOMAIN') ?? 'Westminster City Council',
                        'domain' => \Config::get('app.url')
                    ];
                    \Queue::pushOn(INCIDENT_REPORT_NOTIFICATION_QUEUE, new IncidentReportNotification($contacts, $email_to_housing_officer, INCIDENT_REPORT_APPROVED_PROPERTY_CONTACT_EMAIL));
                }
            }
            \DB::commit();
            return $response = \CommonHelpers::successResponse('Incident Report updated successfully!');
        }catch (\Exception $e) {
            \DB::rollback();
            \Log::error($e);
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval Incident Report. Please try again!');
        }
    }
    public function rejectIncident($incident_id, $data){
        try {
            \DB::beginTransaction();
            $comment = $data['note'] ?? '';
            $due_date = \CommonHelpers::toTimeStamp($data['due_date']);
            $data_reject = [
                'status' => INCIDENT_REPORT_REJECT,
                'comments' => $comment,
                'due_date' => $due_date,
                'is_lock' => INCIDENT_REPORT_UNLOCK
            ];
            $this->incidentReportRepository->rejectIncident($incident_id,$data_reject);
            \DB::commit();
            return $response = \CommonHelpers::successResponse('Incident Report updated successfully!');
        }catch (\Exception $e) {
            \DB::rollback();
            \Log::error($e);
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to reject Incident Report. Please try again!');
        }
    }

    public function cancelIncident($incident) {
        try {
            \DB::beginTransaction();
            $incident->status = INCIDENT_REPORT_READY_QA;
            $incident->is_lock = INCIDENT_REPORT_UNLOCK;
            $incident->save();
            //log audit
            $comment = \Auth::user()->full_name  . " canceled Incident Report "  . $incident->reference;
            \CommonHelpers::logAudit(INCIDENT_REPORTING_TYPE, $incident->id, AUDIT_ACTION_CANCELED, $incident->reference, $incident->property_id ,$comment, 0 ,$incident->property_id);
            \DB::commit();
            return $response = \CommonHelpers::successResponse('Incident Report updated successfully!');
        } catch (\Exception $e) {
            \Log::error($e);
            \DB::rollBack();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to cancel Incident Report. Please try again!');
        }
    }

    public function decommissionIncident($incident, $reason_decommissioned)
    {
        try {
            if($incident->decommissioned == INCIDENT_UNDECOMMISSION){
                $incident->decommissioned = INCIDENT_DECOMMISSIONED;
                if ($reason_decommissioned) {
                    $incident->reason_decommissioned = $reason_decommissioned;
                }
                $incident->save();

                // log audit
                \ComplianceHelpers::logAudit(INCIDENT_REPORT_TYPE, $incident->id, AUDIT_ACTION_DECOMMISSION, $incident->reference);
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Incident Report. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Decommissioned Incident Report Successfully!', $incident);
        } catch (\Exception $e){
            Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Incident Report. Please try again!');
    }

    public function recommissionIncident($incident)
    {
        try {
            if($incident->decommissioned == INCIDENT_DECOMMISSIONED){
                $incident->decommissioned = INCIDENT_UNDECOMMISSION;
                $incident->reason_decommissioned = null;
                $incident->save();
                \ComplianceHelpers::logAudit(INCIDENT_REPORT_TYPE, $incident->id, AUDIT_ACTION_RECOMMISSION, $incident->reference);
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Incident Report. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Recommissioned Incident Report Successfully!', $incident);
        } catch (\Exception $e){
            Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Incident Report. Please try again!');
    }

    public function searchIncident($query_string) {
        return $this->incidentReportRepository->searchIncidentReport($query_string);
    }
}

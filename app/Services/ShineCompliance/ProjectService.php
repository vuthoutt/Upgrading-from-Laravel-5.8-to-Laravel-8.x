<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\ProjectRepository;
use App\Repositories\ShineCompliance\ProjectTypeRepository;
use App\Repositories\ShineCompliance\ProjectSponsorRepository;
use App\Repositories\ShineCompliance\SurveyTypeRepository;
use App\Repositories\ShineCompliance\RRConditionRepository;
use App\Repositories\ShineCompliance\WorkstreamRepository;
use App\Repositories\ShineCompliance\SurveyRepository;
use App\Repositories\ShineCompliance\DocumentRepository;
use App\Repositories\ShineCompliance\ClientRepository;
use App\Repositories\ShineCompliance\RefurbDocTypeRepository;
use App\Repositories\ShineCompliance\SitePlanDocumentRepository;
use App\Repositories\ShineCompliance\UserRepository;
use App\Repositories\ShineCompliance\NotificationRepository;
use App\Models\Project;
use Carbon\Carbon;

class ProjectService{

    private $projectRepository;
    private $projectTypeRepository;
    private $projectSponsorRepository;
    private $surveyTypeRepository;
    private $rrConditionRepository;
    private $workstreamRepository;
    private $surveyRepository;
    private $documentRepository;
    private $clientRepository;
    private $refurbDocTypeRepository;
    public function __construct(ProjectRepository $projectRepository,
                                ProjectTypeRepository $projectTypeRepository,
                                ProjectSponsorRepository $projectSponsorRepository,
                                SurveyTypeRepository $surveyTypeRepository,
                                RRConditionRepository $rrConditionRepository,
                                WorkstreamRepository $workstreamRepository,
                                SurveyRepository $surveyRepository,
                                DocumentRepository $documentRepository,
                                ClientRepository $clientRepository,
                                RefurbDocTypeRepository $refurbDocTypeRepository,
                                UserRepository $userRepository,
                                SitePlanDocumentRepository $sitePlanDocumentRepository,
                                NotificationRepository $notificationRepository
    ){
        $this->projectRepository = $projectRepository;
        $this->projectTypeRepository = $projectTypeRepository;
        $this->projectSponsorRepository = $projectSponsorRepository;
        $this->rrConditionRepository = $rrConditionRepository;
        $this->surveyTypeRepository = $surveyTypeRepository;
        $this->workstreamRepository = $workstreamRepository;
        $this->surveyRepository = $surveyRepository;
        $this->documentRepository = $documentRepository;
        $this->clientRepository = $clientRepository;
        $this->refurbDocTypeRepository = $refurbDocTypeRepository;
        $this->sitePlanDocumentRepository = $sitePlanDocumentRepository;
        $this->userRepository = $userRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function getProjectTypes(){
        return $this->projectTypeRepository->getProjectTypes();
    }

    public function getProject($project_id){
        return $this->projectRepository->getProject($project_id);
    }

    public function getSponsorList(){
        return $this->projectSponsorRepository->getSponsorList();
    }

    public function getSurveyTypes(){
        return $this->surveyTypeRepository->getSurveyTypes();
    }

    public function getRRConditions(){
        return $this->rrConditionRepository->getRRConditions();
    }

    public function getSurveyProjects($property_id) {
        return $this->projectRepository->where('property_id', $property_id)->where('status','<>',5)->get();
    }

    public function getSelectContractors($type){
        switch ($type) {
            case 1:
                $typestring = ' AND ci.type_surveying = 1 ';
                break;
            case 2:
                $typestring = ' AND ci.type_removal = 1 ';
                break;
            case 3:
                $typestring = ' AND ci.type_demolition = 1 ';
                break;
            case 4:
                $typestring = ' AND ci.type_analytical = 1 ';
                break;
            default:
                $typestring = '';
        }
        return $this->projectRepository->getSelectContractors($typestring);
    }

    public function getAsbestosLeads() {
        return $this->userRepository->getAsbestosLeads();
    }

    public function getWorkStreams(){
        return $this->workstreamRepository->getWorkStreams();
    }

    public function getProjectByType($type, $client_id) {
        if ($client_id != 1) {
            $projects = Project::with('property','client')
                                ->where('project_type' , $type)
                                ->where('status', '!=', 5)
                                ->where('decommissioned',0)
                                ->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")
                                ->orderBy('id','desc')
                                ->get();
        } else {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();

            $projects = Project::with('property','client')
                                ->where('project_type' , $type)
                                ->where('decommissioned',0)
                                ->where('status', '!=', 5)
                                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                                ->orderBy('id','desc')
                                ->get();
        }
        $clients = $this->clientRepository->all();
        if (!is_null($projects)) {
            foreach ($projects as $key => $project) {
                $project_checked_contractors = $this->getCheckedContractors($project->checked_contractors, $clients);
                $project->checked_contractor_name = implode(", ",$project_checked_contractors);
            }
        }
        return $projects;
    }

    public function getCheckedContractors($contractors, $clients) {
        $client_name = [];
        $contractors = explode(",",$contractors);
        foreach ($contractors as $project_client_id) {
            foreach ($clients as $client) {
                if ($client->id == $project_client_id) {
                    $client_name[] = $client->name;
                }
            }
        }

        return $client_name;
    }

    public function createProject($data, $id = null) {

        if (!empty($data)) {
            $contractors = null;
            if ( isset($data['contractors']) and count($data['contractors']) > 0 ) {
                // when the user selects contractors should change the project's status to Tender in progress
                $data['status'] = 2;

                $contractors = \CommonHelpers::convertArrayUnique2String($data['contractors']);
            }
            $survey_id = null;
            if (!is_null(\CommonHelpers::checkArrayKey($data,'survey_id'))) {
                $survey_id = implode(",", $data['survey_id']);
            }
            $linked_project_id = null;
            if (!is_null(\CommonHelpers::checkArrayKey($data,'linked_project_id'))) {
                $linked_project_id = implode(",", $data['linked_project_id']);
            }
            // if not required contractor

            $contractor_not_required = (\CommonHelpers::checkArrayKey($data,'contractor_not_required') == 'on') ? true : false;
            $dataProject = [
                "client_id" => \CommonHelpers::checkArrayKey($data,'client_id'),
                "property_id" => \CommonHelpers::checkArrayKey($data,'property_id'),
                "survey_id" => $survey_id,
                "survey_type" => \CommonHelpers::checkArrayKey2($data,'survey_type'),
                "title" => \CommonHelpers::checkArrayKey($data,'title'),
                "project_type" => \CommonHelpers::checkArrayKey($data,'project_type'),
                "date" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data,'date')),
                "due_date" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data,'due_date')),
                "enquiry_date" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data,'enquiry_date')),
                "lead_key" => \CommonHelpers::checkArrayKey($data,'lead_key'),
                "second_lead_key" => \CommonHelpers::checkArrayKey($data,'second_lead_key'),
                "sponsor_lead_key" => \CommonHelpers::checkArrayKey($data,'sponsor_lead_key'),
                "sponsor_id" => \CommonHelpers::checkArrayKey($data,'sponsor_id'),
                "job_no" => \CommonHelpers::checkArrayKey($data,'job_no'),
                "locked" => 0,
                "contractors" => $contractors,
                "checked_contractors" => null,
                "contractor_not_required" => $contractor_not_required ? 1 : 0 ,
                'status' => $contractor_not_required ? PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS : $data['status'],
                "comments" => \CommonHelpers::checkArrayKey($data,'comments'),
                "rr_condition" => \CommonHelpers::checkArrayKey($data,'rr_condition'),
                "work_stream" => \CommonHelpers::checkArrayKey($data,'work_stream'),
                "linked_project_type" => \CommonHelpers::checkArrayKey($data,'linked_project_type'),
                "linked_project_id" => $linked_project_id,
            ];

            try {
                if (!is_null($id)) {
                    if ( isset($data['checked_contractors']) and count($data['checked_contractors']) > 0 ) {
                        $checked_contractors = \CommonHelpers::convertArrayUnique2String($data['checked_contractors']);
                        $dataProject['checked_contractors'] = $checked_contractors;
                    }
                    $project = $this->projectRepository->getProject($id);
                    //for remove link other project
                    $this_link_project_ids = isset($project->linked_project_id) ? explode("," , $project->linked_project_id) : [];
                    $remove_ids = array_diff($this_link_project_ids, $data['linked_project_id'] ?? []);
                    $add_ids = array_diff($data['linked_project_id'] ?? [], $this_link_project_ids);
                    $this->editLinkOtherProject($id, $remove_ids, $add_ids);

                    $project->update($dataProject);
                    $project->find($id);

                    // update linked survey
                    if (!is_null($survey_id)) {
                        $survey_linked = explode(",",$survey_id);
                        $this->surveyRepository->getSurveyLink($survey_linked,$project->id);
                    }

                    if($contractor_not_required == false) {
                        //send email
                        if (isset($data['contractors']) and count($data['contractors']) > 0) {
                            $ExistPONotifications = \Notifications::getListExistingNotificationsInProject($project->id, 1);
                            // CHECKING EXISTING CONTRACTORS
                            if (!is_null($ExistPONotifications)) {
                                $ExistPOContractors = [];
                                foreach ($ExistPONotifications as $existNoti) {
                                    $ExistPOContractors[] = $existNoti->contractor_id;
                                }
//                                foreach (array_filter($data['contractors']) as  $contractor_id) {
//                                    if (!in_array($contractor_id, $ExistPOContractors)) {
//                                        \Notifications::sendMailNotification(1, $project->id, $contractor_id);
//                                    }
//                                }
                            }
                        }

                        if (isset($data['checked_contractors']) and count($data['checked_contractors']) > 0) {
                            // UPDATE PROJECT STATUS = 2
                            $this->update_status_technical($project->id, $project->status);
                            // SEND NOTIFICATIONS + EMAIL:
                            // SUCCESSFUL:
//                            $ExistSuccessNotifications = \Notifications::getListExistingNotificationsInProject($project->id, 3);
                            // CHECKING EXISTING CONTRACTORS
//                            if (count($ExistSuccessNotifications)) {
//                                $ExistSuccessContractors = [];
//                                foreach ($ExistSuccessNotifications as $existNoti) {
//                                    $ExistSuccessContractors[] = $existNoti->contractor_id;
//                                }
//                                foreach (array_filter($data['checked_contractors']) as  $contractor_id) {
//                                    if (!in_array($contractor_id, $ExistSuccessContractors)) {
//                                        \Notifications::sendMailNotification(3, $project->id, $contractor_id);
//                                    }
//                                }
//                            } else {
//                                foreach (array_filter($data['contractors']) as  $contractor_id) {
//                                    \Notifications::sendMailNotification(3, $project->id, $contractor_id);
//                                }
//                            }
//                            $unsuccessfulContractors = array_diff(array_filter($data['contractors']), array_filter($data['checked_contractors']));
//                            // recheck array_diff
//                            $send_mail = true;
//                            foreach ($unsuccessfulContractors as $key => $value) {
//                                if (!in_array($value, array_filter($data['contractors']))) {
//                                    $send_mail = false;
//                                }
//                            }
//                            if ($send_mail) {
//                                if (count($unsuccessfulContractors)) {
//                                    $ExistUnsuccessNotifications = \Notifications::getListExistingNotificationsInProject($project->id, 4);
//
//                                    if (count($ExistUnsuccessNotifications)) {
//                                        $ExistUnsuccessContractors = [];
//                                        foreach ($ExistUnsuccessNotifications as $existNoti) {
//                                            $ExistUnsuccessContractors[] = $existNoti->contractor_id;
//                                        }
//                                        foreach (array_filter($data['checked_contractors']) as  $contractor_id) {
//                                            if (!in_array($contractor_id, $ExistUnsuccessContractors)) {
//                                                \Notifications::sendMailNotification(4, $project->id, $contractor_id);
//                                            }
//                                        }
//                                    } else {
//                                        foreach ($unsuccessfulContractors as  $contractor_id) {
//                                            \Notifications::sendMailNotification(4, $project->id, $contractor_id);
//                                        }
//                                    }
//                                }
//                            }
                        } else {
                            $updateStatusTender = $this->update_status_tender($project->id, $project->status);
                            //UPDATE INVITE PRIVILEGE
                        }
                    }

                    // store comment history
                    \CommentHistory::storeCommentHistory('project', $project->id, $dataProject['comments']);
                    //log audit
                    \CommonHelpers::logAudit(PROJECT_TYPE, $id, AUDIT_ACTION_EDIT, $project->reference, $project->property_id ,null, 0 ,$project->property_id);
                    return \CommonHelpers::successResponse('Project Updated Successfully!', $id);
                } else {
                    $dataProject['created_by'] = \Auth::user()->id ;
                    $project = $this->projectRepository->createProject($dataProject);
                    if ($project) {
                        $projectRef = "PR" . $project->id;
                        $this->projectRepository->updateProject($project->id,['reference' => $projectRef]);
                        $this->addLinkOtherProject($project->id, $linked_project_id);
                        if ($contractor_not_required == false) {
                            //send email
//                            if (!is_null($data['contractors'])) {
//                                foreach (array_filter($data['contractors']) as  $contractor_id) {
//                                    \Notifications::sendMailNotification(1, $project->id, $contractor_id);
//                                }
//                            }
                        }
                        // store comment history
                        \CommentHistory::storeCommentHistory('project', $project->id, $dataProject['comments']);
                        //log audit
                        \CommonHelpers::logAudit(PROJECT_TYPE, $project->id, AUDIT_ACTION_ADD, $projectRef, $project->property_id ,null, 0 ,$project->property_id);
                        return \CommonHelpers::successResponse('Project Created Successfully!', $project);
                    }
                }

            } catch (\Exception $e) {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Create/Update Project. Please try again!');
            }
        }
    }

    private function editLinkOtherProject($project_id, $remove_link_project_ids, $add_link_project_ids){
        if($project_id and count($remove_link_project_ids)){
            $projects = $this->projectRepository->getProjectLink($remove_link_project_ids);
            if(!$projects->isEmpty()){
                foreach ($projects as $project){
                    $this_link_project_ids = explode( ",", $project->linked_project_id);
                    if(count($this_link_project_ids)){
                        if (($key = array_search($project_id, $this_link_project_ids)) !== false) {
                            unset($this_link_project_ids[$key]);
                            $project->linked_project_id = implode(",", $this_link_project_ids);
                            $project->updated_at = Carbon::now();
                            $project->save(['timestamps' => FALSE]);
                        }
                    }
                }
            }
        }

        if($project_id and count($add_link_project_ids)){
            $this->addLinkOtherProject($project_id, $add_link_project_ids);
        }
    }

    private function addLinkOtherProject($project_id, $link_project_ids){
        if($project_id and $link_project_ids){
            // array (edit project case) or explode to array (add project case)
            $other_project_ids = $link_project_ids;
            if(!is_array($link_project_ids)){
                $other_project_ids = explode( ",", $link_project_ids);
            }
            if(count($other_project_ids)){
                $projects = $this->projectRepository->getProjectLink($other_project_ids);
                if(!$projects->isEmpty()){
                    foreach ($projects as $project){
                        $this_link_project_ids = explode( ",", $project->linked_project_id);
                        if(count($this_link_project_ids)){
                            $new_ids = array_unique(array_merge($this_link_project_ids, [$project_id]));
                            $project->linked_project_id = implode(",", $new_ids);
                            $project->updated_at = Carbon::now();
                            $project->save(['timestamps' => FALSE]);
                        }
                    }
                }
            }
        }
    }

    public function update_status_tender($project_id, $status) {
        if ($status == 1) {
            $this->projectRepository->updateStatusTender($project_id);
        } else {
            return FALSE;
        }
        return TRUE;
    }

    public function getTenderBoxDocsContractor($project_id, $type, $contractor_id){
        return $this->documentRepository->getTenderBoxDocsContractor($project_id, $type, $contractor_id);
    }

    public function getTenderBoxDocs($project_id,$category){
        return $this->documentRepository->getTenderBoxDocs($project_id, $category);
    }

    public function getContractors($contractors){
        if (!is_null($contractors)) {
            $contractors = explode(",",$contractors);
            $clients = $this->clientRepository->getClientWhereIn($contractors);
        } else {
            $clients = null;
        }
        return $clients;
    }

    public function getProjectSurveys($survey_ids) {
        if (!is_null($survey_ids)) {
            $survey_ids = explode(",",$survey_ids);
            $surveys = $this->surveyRepository->getSurveyWhereIn($survey_ids);
        } else {
            $surveys = null;
        }
        return $surveys;
    }

    public function getLinkedProject($project_ids) {
        if (!is_null($project_ids)) {
            $project_ids = explode(",",$project_ids);
            $projects = $this->projectRepository->getProjectWhereIn($project_ids);
        } else {
            $projects = null;
        }
        return $projects;
    }

    public function getContractorBoxDocs($project_id, $category, $contractor_id){
        return $this->documentRepository->getContractorBoxDocs($project_id, $category, $contractor_id);
    }

    public function getSurveyDocs($survey) {
        $list = [];
        $counter = 0;
        $list[$counter]['status'] = $survey->status;
        $list[$counter]['risk'] = $this->get_risk_viewing($survey->status, $survey->surveyDate->risk_warning ?? 0);

        $list[$counter]['name'] = $survey->reference;
        $list[$counter]['type'] = "Survey";
        if ($survey->status != 5) {
            $list[$counter]['date'] = $survey->surveyDate->published_date ?? '';
        } else {
            $list[$counter]['date'] =$survey->surveyDate->completed_date ?? '';
        }
        $list[$counter]['view'] =  \CommonHelpers::getPdfViewingSurvey($survey->status,route('survey.view.pdf',['type'=> VIEW_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($survey->id)]),
            route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($survey->id)]));
        $counter++;

        //Sample certificates
        $samplesCertificates = $survey->sampleCertificate;

        if (count($samplesCertificates)) {
            foreach ($samplesCertificates as $sC) {
                $list[$counter]['name'] = $sC->sample_reference;
                $list[$counter]['type'] = $sC->description;
                $list[$counter]['date'] = $sC->updated_date ?? '';
                $list[$counter]['view'] = \CommonHelpers::getFilePdfViewing($sC->id, SAMPLE_CERTIFICATE_FILE, $survey->status ?? '',$sC->reference, $survey->property_id ?? '' );
                $list[$counter]['risk'] = '';
                $counter++;
            }
        }

        //Aritest certificates
        $airtestCertificates = $survey->airTestCertificate;

        if (count($airtestCertificates)) {
            foreach ($airtestCertificates as $aC) {
                $list[$counter]['name'] = $aC->air_test_reference;
                $list[$counter]['type'] = $aC->description;
                $list[$counter]['date'] = $aC->updated_date ?? '';
                $list[$counter]['view'] = \CommonHelpers::getFilePdfViewing($aC->id, SAMPLE_CERTIFICATE_FILE, $survey->status ?? '',$aC->reference, $survey->property_id ?? '' );
                $list[$counter]['risk'] = '';
                $counter++;
            }
        }

        //SitePlans
        $siteplans = $this->sitePlanDocumentRepository->getSitePlanbySurvey($survey->property_id,$survey->id);
        if (count($siteplans)) {
            foreach ($siteplans as $sp) {
                $list[$counter]['name'] = $sp->plan_reference;
                $list[$counter]['type'] = $sp->name;
                $list[$counter]['date'] = $sp->added ?? '';
                $list[$counter]['view'] = \CommonHelpers::getFilePdfViewing($sp->id, PLAN_FILE, $survey->status ?? '', $sp->name, $sp->property_id);
                $list[$counter]['risk'] = '';
                $counter++;
            }
        }

        return $list;
    }

    public function get_risk_viewing($status, $risk_warning){
        if($status == 5) {
            return '<span class="badge grey"> &nbsp;&nbsp;&nbsp;NA&nbsp;&nbsp;&nbsp; </span>';
        } else {
            return '<span class="badge ' . $this->get_report_risk_color($risk_warning) . '">' . $risk_warning . '</span> Days Remaining';
        }

    }

    public function listDocumentTypes($type) {
        return $this->refurbDocTypeRepository->listDocumentTypes($type);
    }

    public function is_approval_project($project_id) {
        $document = $this->documentRepository->getDocmentApprovalProject($project_id);
        return is_null($document) ? true : false;
    }

    public function get_report_risk_color($risk_warning) {

        $daysRemain = $risk_warning;
        $riskColor = "";

        if ($daysRemain <= 14) {
            $riskColor = "red";
        } elseif ($daysRemain >= 15 && $daysRemain <= 30) {
            $riskColor = "orange";
        } elseif ($daysRemain >= 31 && $daysRemain <= 60) {
            $riskColor = "yellow";
        } elseif ($daysRemain >= 61 && $daysRemain <= 120) {
            $riskColor = "blue";
        } else {
            $riskColor = "grey";
        }

        return $riskColor;
    }

    public function update_status_technical($project_id, $status) {
        if ($status < PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS) {
            $this->projectRepository->updateProject($project_id,['status' => PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS]);
        } else {
            return FALSE;
        }
        return TRUE;
    }

    public function archiveProject($project_id, $type) {
        $project = $this->projectRepository->getProject($project_id);
        try {
            //restore
            \DB::beginTransaction();
            if ($type == 'restore') {
                $this->projectRepository->updateProject($project_id,['status' => PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS]);
                //SEND NOTIFICATION + EMAILS:
                if ($project->checked_contractors) {
                    $contractors = explode(",", $project->checked_contractors);
                    foreach ($contractors as $contractor_id) {
                        \Notifications::sendMailNotification(8, $project_id, $contractor_id);
                    }
                }
                //log audit
                \CommonHelpers::logAudit(PROJECT_TYPE, $project->id, AUDIT_ACTION_RESTORE, $project->reference, $project->property_id ,null, 0 ,$project->property_id);
                $response = \CommonHelpers::successResponse('Project Restored Successfully!');
            } elseif($type='archive') {
                $time = time();
                //archive
                $this->projectRepository->updateProject($project_id,['status' => PROJECT_READY_FOR_ARCHIVE_STATUS, 'completed_date' => $time]);
                //Fixed for CSAT
                $this->complete_project($project_id);
                //SEND NOTIFICATION + EMAILS:
                if ($project->checked_contractors) {
                    $contractors = explode(",", $project->checked_contractors);
                    foreach ($contractors as $contractor_id) {
                        \Notifications::sendMailNotification(7, $project_id, $contractor_id);
                    }
                }
                //log audit
                \CommonHelpers::logAudit(PROJECT_TYPE, $project->id, AUDIT_ACTION_ARCHIVE, $project->reference, $project->property_id ,null, 0 ,$project->property_id);
                $response = \CommonHelpers::successResponse('Project Archived Successfully!');
            };
            \DB::commit();
            return $response;
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollBack();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Archive or Restoring Project. Please try again!');
        }
    }

    public function complete_project($project_id) {
        $this->projectRepository->complete_project($project_id);
        $this->notificationRepository->completeNotification($project_id);
    }

    public function getPropertyProjectType($id,$risk_classification_id) {
        $client_type = \Auth::user()->clients->client_type;
        $client_id = \Auth::user()->client_id;
        $projects = $this->projectRepository->getPropertyProjectType($id,$client_id,$client_type,$risk_classification_id);
        // missing role
        return is_null($projects) ? [] : $projects;
    }

    public function getPropertyProjects($property_id) {
        return $this->projectRepository->where('property_id', $property_id)->get();
    }

}

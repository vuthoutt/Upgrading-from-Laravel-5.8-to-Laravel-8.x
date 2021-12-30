<?php
namespace App\Repositories;
use App\Helpers\CommonHelpers;
use App\Models\PublishedWorkRequest;
use App\Models\ShineDocumentStorage;
use App\Models\WorkData;
use App\Models\WorkStream;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Project;
use App\Models\Client;
use App\Models\Document;
use App\Models\ProjectType;
use App\Models\SurveyType;
use App\Models\ProjectSponsor;
use App\Models\RRCondition;
use App\Models\RefurbDocType;
use App\Models\Survey;
use App\Models\Notification;
use App\Models\SitePlanDocument;
use App\Models\ShineCompliance\Assessment;
use App\Models\ShineCompliance\ProjectRiskClassification;
use App\User;
use Illuminate\Support\Facades\DB;

class ProjectRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Project::class;
    }

    public function getProjectByType($type, $client_id = false) {
        if ($client_id) {
            $projects = Project::with('property','client')
                                ->where('project_type' , $type)
                                ->where('status', '!=', 5)
                                ->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")
                                ->orderBy('id','desc')
                                ->get();
        } else {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();

            $projects = Project::with('property','client')
                                ->where('project_type' , $type)
                                ->where('status', '!=', 5)
                                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                                ->orderBy('id','desc')
                                ->get();
        }
        if (!is_null($projects)) {
            foreach ($projects as $key => $project) {
                $project_checked_contractors = $this->getCheckedContractors($project->checked_contractors);
                $project->checked_contractor_name = implode(",",$project_checked_contractors);
            }
        }
        return $projects;
    }

    public function getContractors($contractors) {
        if (!is_null($contractors)) {
            $contractors = explode(",",$contractors);
            $clients = Client::whereIn('id', $contractors)->get();
        } else {
            $clients = null;
        }
        return $clients;
    }

    public function getCheckedContractors($contractors) {
        if (!is_null($contractors)) {
            $contractors = explode(",",$contractors);
            $clients = Client::whereIn('id', $contractors)->pluck('name')->toArray();
        } else {
            $clients = [];
        }
        return $clients;
    }

    public function getProjectSurveys($survey_ids) {
        if (!is_null($survey_ids)) {
            $survey_ids = explode(",",$survey_ids);
            $surveys = Survey::whereIn('id', $survey_ids)->where('decommissioned', 0)->get();
        } else {
            $surveys = null;
        }
        return $surveys;
    }

    public function getProjectAssessments($assessment_ids) {
        if (!is_null($assessment_ids)) {
            $assessment_ids = explode(",",$assessment_ids);
            $assessments = Assessment::whereIn('id', $assessment_ids)->where('decommissioned', 0)->get();
        } else {
            $assessments = null;
        }
        return $assessments;
    }

    public function getLinkedProject($project_ids) {
        if (!is_null($project_ids)) {
            $project_ids = explode(",",$project_ids);
            $projects = Project::whereIn('id', $project_ids)->get();
        } else {
            $projects = null;
        }
        return $projects;
    }

    public function getCheckedProjects() {
        $condition = 'id > 0';
        $client_id = \Auth::user()->client_id;

        if (\Auth::user()->clients->client_type == 1) {
            $condition = "FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))";
        }
        return Project::with('property')->whereRaw($condition)->get();
    }

    public function getTenderBoxDocs($project_id, $category) {
        $documents = Document::where('project_id', $project_id)
                                ->where('category', $category)
                                ->orderBy('added', 'desc')
                                ->get();
        return $documents;
    }

    public function getTenderBoxDocsContractor($project_id, $category, $client_id) {
        $documents = Document::where('project_id', $project_id)
                                ->where('category', $category)
                                ->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")
                                ->orderBy('added', 'desc')
                                ->get();
        return $documents;
    }

    public function getContractorBoxDocs($project_id, $category, $contractor_id) {
        $documents = Document::where('project_id', $project_id)
                                ->where('category', $category)
                                ->where('contractor', $contractor_id)
                                ->orderBy('added', 'desc')
                                ->get();
        return $documents;
    }

    public function getProjectTypes() {
        $project_types = ProjectType::orderBy('order', 'asc')->get();
        return $project_types;
    }

    public function getProjectClassification() {
        $compliance_type = [];
        // check update permission for asbestos
        if (\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_PROJECT_ASBESTOS,JOB_ROLE_ASBESTOS )) {
            $compliance_type[] = 1;
        }
        // check update permission for fire

        if (\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_PROJECT_FIRE,JOB_ROLE_FIRE )) {
            $compliance_type[] = 2;
        }

        // check update permission for water
        if (\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_PROJECT_WATER,JOB_ROLE_WATER )) {
           $compliance_type[] = 3;
        }

        // check update permission for H&S
        if (\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_PROJECT_HS,JOB_ROLE_H_S )) {
           $compliance_type[] = 4;
        }

        return ProjectRiskClassification::whereIn('id', $compliance_type)->get();
    }

    public function getAsbestosLeads() {
        $users = User::where('joblead', 1)
                        ->where('is_locked', 0)
                        ->get();
        return $users;
    }

    public function getSponsorList() {
        return ProjectSponsor::all();
    }

    public function getSurveyTypes() {
        return SurveyType::orderBy('order', 'asc')->get();
    }

    public function getRRConditions() {
        return RRCondition::orderBy('order', 'asc')->get();
    }

    public function getSelectContractors($type) {
        switch ($type) {
            case 4:
                $typestring = ' AND ci.type_analytical = 1 ';
                break;
            case 3:
                $typestring = ' AND ci.type_demolition = 1 ';
                break;
            case 2:
                $typestring = ' AND ci.type_removal = 1 ';
                break;
            case 1:
                $typestring = ' AND ci.type_surveying = 1 ';
                break;
            default:
                $typestring = '';
        }
        // dd($typestring);
        $contractors_lists =  DB::select("
                SELECT c.id, c.name, c.reference
                FROM tbl_clients c
                LEFT JOIN tbl_client_info ci ON c.id = ci.client_id
                WHERE c.client_type = 1
                $typestring
                ORDER BY name ASC
            ");

        return is_null($contractors_lists) ? [] : $contractors_lists;
    }

    public function createProject($data, $id = null) {

        if (!empty($data)) {
            $contractors = null;
            if ( isset($data['contractors']) and count($data['contractors']) > 0 ) {
                $contractors = \CommonHelpers::convertArrayUnique2String($data['contractors']);
            }
            $data['status'] = PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS;
            $data['progress_stage'] = PROJECT_STAGE_PRE_CONSTRUCTION;
            $survey_id = null;
            $assess_ids = null;
            $document_ids = null;
            $hazard_ids = null;
            $risk_classification_id = \CommonHelpers::checkArrayKey($data,'risk_classification_id');
            if($risk_classification_id == ASBESTOS_CLASSIFICATION){
                if (!is_null(\CommonHelpers::checkArrayKey($data,'survey_id'))) {
                    $survey_id = implode(",", $data['survey_id']);
                }
            } else {
                if (!is_null(\CommonHelpers::checkArrayKey($data,'assessment_id'))) {
                    $assess_ids = implode(",", $data['assessment_id']);
                }
                if (!is_null(\CommonHelpers::checkArrayKey($data,'hazard_id'))) {
                    $hazard_ids = implode(",", $data['hazard_id']);
                }
            }
            if (!is_null(\CommonHelpers::checkArrayKey($data,'document_id'))) {
                $document_ids = implode(",", $data['document_id']);
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
                "assessment_ids" => $assess_ids,
                "document_ids" => $document_ids,
                "hazard_ids" => $hazard_ids,
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
                "checked_contractors" => $contractors,
                "contractor_not_required" => $contractor_not_required ? 1 : 0 ,
                "comments" => \CommonHelpers::checkArrayKey($data,'comments'),
                "rr_condition" => \CommonHelpers::checkArrayKey($data,'rr_condition'),
                "work_stream" => \CommonHelpers::checkArrayKey($data,'work_stream'),
                "linked_project_type" => \CommonHelpers::checkArrayKey($data,'linked_project_type'),
                "linked_project_id" => $linked_project_id,
                "risk_classification_id" => \CommonHelpers::checkArrayKey($data,'risk_classification_id'),

            ];

            try {
                if (!is_null($id)) {
                    // Remove logic Tendering functionality
//                    if ( isset($data['checked_contractors']) and count($data['checked_contractors']) > 0 ) {
//                        $checked_contractors = \CommonHelpers::convertArrayUnique2String($data['checked_contractors']);
//                        $dataProject['checked_contractors'] = $checked_contractors;
//                    }
                    $project = Project::where('id', $id)->first();
                    //for remove link other project
                    $this_link_project_ids = isset($project->linked_project_id) ? explode("," , $project->linked_project_id) : [];
                    $remove_ids = array_diff($this_link_project_ids, $data['linked_project_id'] ?? []);
                    $add_ids = array_diff($data['linked_project_id'] ?? [], $this_link_project_ids);
                    $this->editLinkOtherProject($id, $remove_ids, $add_ids);
                    $project->update($dataProject);
                    $project = Project::find($id);

//                    // update linked survey
//                    if (!is_null($survey_id)) {
//                        $survey_linked = explode(",",$survey_id);
//                        Survey::whereIn('id', $survey_linked)->update(['project_id' => $project->id]);
//                    }

                    if($contractor_not_required == false) {
                        //send email
                        if (isset($data['contractors']) and count($data['contractors']) > 0) {
                            $ExistPONotifications = \Notifications::getListExistingNotificationsInProject($project->id, PROJECT_INVITATION_EMAILTYPE);
                            // CHECKING EXISTING CONTRACTORS
                            if (!is_null($ExistPONotifications)) {
                                $ExistPOContractors = [];
                                foreach ($ExistPONotifications as $existNoti) {
                                    $ExistPOContractors[] = $existNoti->contractor_id;
                                }
                                foreach (array_filter($data['contractors']) as  $contractor_id) {
                                    if (!in_array($contractor_id, $ExistPOContractors)) {
                                        \Notifications::sendMailNotification(PROJECT_INVITATION_EMAILTYPE, $project->id, $contractor_id);
                                    }
                                }
                            }
                        }

                        // if (isset($data['checked_contractors']) and count($data['checked_contractors']) > 0) {
                        //     // UPDATE PROJECT STATUS = 2
                        //     $this->update_status_technical($project->id, $project->status);
                        //     // SEND NOTIFICATIONS + EMAIL:
                        //     // SUCCESSFUL:
                        //     $ExistSuccessNotifications = \Notifications::getListExistingNotificationsInProject($project->id, 3);
                        //     // CHECKING EXISTING CONTRACTORS
                        //     if (count($ExistSuccessNotifications)) {
                        //         $ExistSuccessContractors = [];
                        //         foreach ($ExistSuccessNotifications as $existNoti) {
                        //             $ExistSuccessContractors[] = $existNoti->contractor_id;
                        //         }
                        //         foreach (array_filter($data['checked_contractors']) as  $contractor_id) {
                        //             if (!in_array($contractor_id, $ExistSuccessContractors)) {
                        //                 \Notifications::sendMailNotification(3, $project->id, $contractor_id);
                        //             }
                        //         }
                        //     } else {
                        //         foreach (array_filter($data['contractors']) as  $contractor_id) {
                        //         \Notifications::sendMailNotification(3, $project->id, $contractor_id);
                        //         }
                        //     }
                        //     $unsuccessfulContractors = array_diff(array_filter($data['contractors']), array_filter($data['checked_contractors']));
                        //     // recheck array_diff
                        //     $send_mail = true;
                        //     foreach ($unsuccessfulContractors as $key => $value) {
                        //         if (!in_array($value, array_filter($data['contractors']))) {
                        //             $send_mail = false;
                        //         }
                        //     }
                        //     if ($send_mail) {
                        //         if (count($unsuccessfulContractors)) {
                        //             $ExistUnsuccessNotifications = \Notifications::getListExistingNotificationsInProject($project->id, 4);

                        //             if (count($ExistUnsuccessNotifications)) {
                        //                 $ExistUnsuccessContractors = [];
                        //                 foreach ($ExistUnsuccessNotifications as $existNoti) {
                        //                     $ExistUnsuccessContractors[] = $existNoti->contractor_id;
                        //                 }
                        //                 foreach (array_filter($data['checked_contractors']) as  $contractor_id) {
                        //                     if (!in_array($contractor_id, $ExistUnsuccessContractors)) {
                        //                         \Notifications::sendMailNotification(4, $project->id, $contractor_id);
                        //                     }
                        //                 }
                        //             } else {
                        //                 foreach ($unsuccessfulContractors as  $contractor_id) {
                        //                 \Notifications::sendMailNotification(4, $project->id, $contractor_id);
                        //                 }
                        //             }
                        //         }
                        //     }
                        // } else {
                        //     $updateStatusTender = $this->update_status_tender($project->id, $project->status);
                        //     //UPDATE INVITE PRIVILEGE
                        // }
                    }

                    // store comment history
                    \CommentHistory::storeCommentHistory('project', $project->id, $dataProject['comments']);
                    //log audit
                    \CommonHelpers::logAudit(PROJECT_TYPE, $id, AUDIT_ACTION_EDIT, $project->reference, $project->property_id ,null, 0 ,$project->property_id);
                    return \CommonHelpers::successResponse('Project Updated Successfully!', $id);
                } else {
                    $dataProject['status'] = $data['status'];
                    $dataProject['progress_stage'] =  $data['progress_stage'];
                    $dataProject['created_by'] = \Auth::user()->id ;
                    $project = Project::create($dataProject);
                    if ($project) {
                        $projectRef = "PR" . $project->id;
                        Project::where('id', $project->id)->update(['reference' => $projectRef]);
                        $this->addLinkOtherProject($project->id, $linked_project_id);
                        if ($contractor_not_required == false) {
                            //send email
                           if (!is_null($data['contractors'])) {
                               foreach (array_filter($data['contractors']) as  $contractor_id) {
                                 \Notifications::sendMailNotification(PROJECT_INVITATION_EMAILTYPE, $project->id, $contractor_id);
                               }
                           }
                        }
                        // store comment history
                        \CommentHistory::storeCommentHistory('project', $project->id, $dataProject['comments']);
                        //log audit
                        \CommonHelpers::logAudit(PROJECT_TYPE, $project->id, AUDIT_ACTION_ADD, $projectRef, $project->property_id ,null, 0 ,$project->property_id);
                        return \CommonHelpers::successResponse('Project Created Successfully!', $project);
                    }
                }

            } catch (\Exception $e) {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to update project. Please try again!');
            }
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
                $projects = Project::whereIn('id', $other_project_ids)->get();
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

    private function editLinkOtherProject($project_id, $remove_link_project_ids, $add_link_project_ids){
        if($project_id and count($remove_link_project_ids)){
            $projects = Project::whereIn('id', $remove_link_project_ids)->get();
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


    public function listDocumentTypes($type) {
        return RefurbDocType::where('refurb_type', $type)->where('is_active',1)->orderBy('order', 'asc')->get();
    }

    public function getMyProjects($user_id, $client_id) {
        if (\CommonHelpers::isSystemClient()) {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();

            return Project::with('property')
                                ->where('status', '<', 5)
                                ->where('decommissioned',0)
                                // ->whereRaw("(lead_key =  $user_id or second_lead_key = $user_id)" )
                                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                                ->where('lead_key', $user_id)
                                ->get();
        } else {
            return Project::with('property')
                                ->where('status', '<', 5)
                                ->where('decommissioned',0)
                                ->where(function ($query) use ($client_id){
                                    $query->whereRaw("FIND_IN_SET('$client_id', REPLACE(contractors, ' ', ''))")
                                    ->orWhereRaw("FIND_IN_SET('$client_id', REPLACE(checked_contractors, ' ', ''))");
                                })
                                ->get();
        }
    }

    public function is_approval_project($project_id) {
        $document =  Document::where('status', PROJECT_DOC_COMPLETED)
                            ->where('category',COMPLETION_DOC_CATEGORY)
                            ->whereNotNull('type')->where('project_id', $project_id)->first();
        return is_null($document) ? true : false;
    }

    public function archiveProject($project_id, $type) {
        $project = Project::find($project_id);
        try {
            //restore
            if ($type == 'restore') {
                Project::where('id', $project_id)->update(['status' => PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS]);

                //SEND NOTIFICATION + EMAILS:
                if ($project->checked_contractors) {
                    $contractors = explode(",", $project->checked_contractors);
                    foreach ($contractors as $contractor_id) {
                        \Notifications::sendMailNotification(8, $project_id, $contractor_id);
                    }
                }
                //log audit
                \CommonHelpers::logAudit(PROJECT_TYPE, $project->id, AUDIT_ACTION_RESTORE, $project->reference, $project->property_id ,null, 0 ,$project->property_id);
                return \CommonHelpers::successResponse('Project Restored Successfully!');
            } elseif($type='archive') {
                $time = time();
                //archive
                Project::where('id', $project_id)->update(['status' => PROJECT_READY_FOR_ARCHIVE_STATUS, 'completed_date' => $time]);
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
                return \CommonHelpers::successResponse('Project Archived Successfully!');
            };
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Update Project Fail!');
        }
    }

    public function complete_project($project_id) {
        Project::where('id', $project_id)->where('status', PROJECT_READY_FOR_ARCHIVE_STATUS)->update(['status' => PROJECT_COMPLETE_STATUS]);
        Notification::where('project_id', $project_id)->update(['status' => 2]);
    }

    public function update_status_technical($project_id, $status) {
        if ($status < PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS) {
            Project::where('id', $project_id)->update(['status' => PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS]);
        } else {
            return FALSE;
        }
        return TRUE;
    }
    public function update_status_tender($project_id, $status) {
        if ($status == 1) {
            Project::where('id', $project_id)->update(['status' => 2]);
        } else {
            return FALSE;
        }
        return TRUE;
    }

    public function searchProject($q){
        return $this->model->whereRaw("(title LIKE '%$q%' OR reference LIKE '%$q%')")->orderBy('reference','asc')->limit(LIMIT_SEARCH)->get();
    }

    public function createProjectWorkRequest($work_request, $complete_date, $property, $is_in_process = false){
        $data_insert = [];

        $checked_contractors = $contractors = $project_title = "";
        $contractor_major = $checked_contractor_major = '';
        $work_type = WorkData::where('id', $work_request->type)->first();
        if ($work_request->is_major == 1) {
            $contractor_major = $work_request->contractor;

            $type_surveying = $work_request->contractorRelation->clientInfo->type_surveying ?? 0;
            $type_removal = $work_request->contractorRelation->clientInfo->type_removal ?? 0;
            $type_demolition = $work_request->contractorRelation->clientInfo->type_demolition ?? 0;
            $type_analytical = $work_request->contractorRelation->clientInfo->type_analytical ?? 0;
        }

        $project_title = $work_type->description ?? '';
        if($work_type){
            // 1 Survey Only, 2 Remediation/Removal, 3 Demolition, 4 Analytical
            // plus day to caculate due date for each type if they are difference
            // contractor for each type
            $risk_classification_id = ASBESTOS_CLASSIFICATION;
            switch ($work_type->type){
                case 'air':
                     // $project_title = 'Air Monitoring';
                     $data_insert['project_type'] = PROJECT_ANALYTICAL;
                     $contractors = $checked_contractors = 13;
                     if ($work_request->is_major == 1) {
                        $contractors = $contractor_major;
                        if ($type_removal == 1) {
                            $checked_contractors = $contractor_major;
                        } else {
                            $checked_contractors = '';
                        }
                     }
                    break;
                case 'remediation':
                    // $project_title = 'Remediation';
                    $data_insert['project_type'] = PROJECT_REMEDIATION_REMOVAL;
                    if ($work_request->is_major == 1) {
                        $contractors = $contractor_major;
                        if ($type_removal == 1) {
                            $checked_contractors = $contractor_major;
                        } else {
                            $checked_contractors = '';
                        }
                    }

                    if($work_request->compliance_type == WORK_REQUEST_FIRE_TYPE){
                        $data_insert['project_type'] = FIRE_REMEDIAL_ASSESSMENT;
                        $risk_classification_id = FIRE_CLASSIFICATION;
                    }
                    break;
                case 'survey':
                    // $project_title = 'Survey';
                    $data_insert['project_type'] = PROJECT_SURVEY_ONLY;
                    $contractors = $checked_contractors = 13;
                    if ($work_request->is_major == 1) {
                        $contractors = $contractor_major;
                        if ($type_removal == 1) {
                            $checked_contractors = $contractor_major;
                        } else {
                            $checked_contractors = '';
                        }
                    }
                    if($work_request->compliance_type == WORK_REQUEST_FIRE_TYPE){
                        $data_insert['project_type'] = FIRE_ASSESSMENT;
                        $risk_classification_id = FIRE_CLASSIFICATION;
                    }
                    break;
            }
            $project_title = $project_title ? ($project_title . ' - ' . $work_request->reference) : $work_request->reference;
            $plus_day = 0;// need to confirm this one
            if($work_request->priority == 22){
                $plus_day = 0;
            } else if($work_request->priority == 23){
                $plus_day = 1;
            } else if($work_request->priority == 24){
                $plus_day = 2;
            } else if($work_request->priority == 25){
                $plus_day = 5;
            }

            $data_insert['client_id'] = $property->client_id ?? NULL;
            $data_insert['property_id'] = $property->id ?? NULL;
            $data_insert['survey_type'] = 0;
            $data_insert['rr_condition'] = 0;
            $data_insert['locked'] = 0;
            $data_insert['title'] = $project_title;
            $data_insert['date'] = $complete_date;
            $data_insert['lead_key'] = $work_request->asbestos_lead ?? NULL;
            $data_insert['second_asbestos_lead'] = 0;
            $data_insert['sponsor_id'] = $work_request->created_by ?? NULL;
            $data_insert['job_no'] = '';
            $data_insert['status'] = PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS;
            $data_insert['progress_stage'] = PROJECT_STAGE_PRE_CONSTRUCTION;
            $data_insert['comments'] = '';
            $data_insert['due_date'] = $complete_date + ($plus_day * 86400);
            $data_insert['contractors'] = $contractors;
            $data_insert['checked_contractors'] = $checked_contractors;
            $data_insert['created_by'] = \Auth::user()->id ;
            $data_insert['risk_classification_id'] = $risk_classification_id ;
            $project = Project::updateOrCreate(['id' => $work_request->project_id], $data_insert);

            if($project){
                $projectRef = "PR" . $project->id;
                Project::where('id', $project->id)->update(['reference'=> $projectRef]);
                \CommonHelpers::logAudit(PROJECT_TYPE, $project->id, AUDIT_ACTION_ADD, $projectRef, $project->property_id ,null, 0 ,$project->property_id);
                $work_request_document = PublishedWorkRequest::where('work_request_id', $work_request->id)->orderBy('id','DESC')->first();
                // only create document for WR when the API is done
                if($work_request_document && !$is_in_process){
                    $dataDocument = [
                        "client_id" => $project->client_id,
                        "name" => $work_request_document->name ?? '',
                        "project_id" => $project->id,
                        "type" => 129,
                        "re_type" => "project",
                        "contractors" => $contractors, // permission
                        "rejected" => 0,
                        "deleted" => 0,
                        "category" => PRE_CONSTRUCTION_DOC_CATEGORY,
                        "authorised" => Carbon::now()->timestamp,
                        "authorised_by" => $work_request->created_by ?? 0,
                        "value" => 0,
                        "added" => Carbon::now()->timestamp,
                        "status" => PROJECT_DOC_COMPLETED,
                        "added_by" => $work_request->created_by ?? 0
                    ];
                    $document = Document::create($dataDocument);
                    if ($document) {
                        $docRef = CommonHelpers::getDocumentReference($document->category, $document->id);
                        if($docRef){
                            Document::where('id', $document->id)->update(['reference' => $docRef]);
                        }
                        //copy work request pdf to tender
                        $copy_tender = ShineDocumentStorage::updateOrCreate([
                            "object_id" => $document->id,
                            "type" => DOCUMENT_FILE
                            ],
                            [
                            "path" => $work_request_document->path,
                            "file_name" => $work_request_document->filename,
                            "mime" => $work_request_document->mime,
                            "size" => $work_request_document->size,
                            "addedDate" => time(),
                            "addedBy" => $work_request->created_by ?? 0,
                        ]);
                        if($copy_tender){
                            Document::where('id', $document->id)->update(['document_present' => 1]);
                        }
                    }
                    //send email
                    //TODO send mail later, recheck
//                    if($contractors){
//                        foreach (explode(",", $contractors) as  $contractor_id) {
//                            \Notifications::sendMailNotification(1, $project->id, $contractor_id);
//                        }
//                    }
//                    if($checked_contractors) {
//                        foreach (explode(",", $checked_contractors) as $contractor_id) {
//                            \Notifications::sendMailNotification(3, $project->id, $contractor_id);
//                        }
//                    }

                }
                return $project->id ?? 0;
            }

        }
        return false;
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
            $list[$counter]['date_int'] = $survey->surveyDate->getOriginal('published_date') ?? '';
        } else {
            $list[$counter]['date'] =$survey->surveyDate->completed_date ?? '';
            $list[$counter]['date_int'] =$survey->surveyDate->getOriginal('completed_date') ?? '';
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
                $list[$counter]['date_int'] = $sC->getOriginal('updated_date') ?? '';
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
                $list[$counter]['date_int'] = $aC->getOriginal('updated_date') ?? '';
                $list[$counter]['view'] = \CommonHelpers::getFilePdfViewing($aC->id, SAMPLE_CERTIFICATE_FILE, $survey->status ?? '',$aC->reference, $survey->property_id ?? '' );
                $list[$counter]['risk'] = '';
                $counter++;
            }
        }

        //SitePlans
        $siteplans =  SitePlanDocument::where('property_id', $survey->property_id)->where('survey_id', $survey->id)->latest('added')->get();
        if (count($siteplans)) {
            foreach ($siteplans as $sp) {
                $list[$counter]['name'] = $sp->plan_reference;
                $list[$counter]['type'] = $sp->name;
                $list[$counter]['date'] = $sp->added ?? '';
                $list[$counter]['date_int'] = $sp->getOriginal('added') ?? '';
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

    public function getWorkStreams() {
        $users = WorkStream::where('is_deleted', '!=', 1)->get();
        return $users;
    }

    public function searchProjectAdminTool($query_string) {

        $sql = "SELECT pj.id as id, pj.reference as reference, p.`name` AS 'property_name', p.property_reference
                FROM `tbl_project` as pj
                LEFT JOIN tbl_property as p ON p.id = pj.property_id
                WHERE
                    pj.`reference` LIKE '%" . $query_string . "%'
                ORDER BY `reference` ASC
                LIMIT 0,20";
        $list = DB::select($sql);

        return $list;
    }

    public function getProjectInprogressByTpe($type, $property_id) {
        $data = \DB::select("Select count(id) count from tbl_project p
                            where property_id = $property_id
                            and status in (2,3)
                            and risk_classification_id =  $type");
        return $data[0]->count ?? false;
    }

    public function changeProgress($project_id, array $data)
    {
        try {
            $stages = [
                PROJECT_STAGE_PRE_CONSTRUCTION,
                PROJECT_STAGE_DESIGN,
                PROJECT_STAGE_COMMERCIAL,
                PROJECT_STAGE_PLANNING,
                PROJECT_STAGE_PRE_START,
                PROJECT_STAGE_SITE_RECORD,
                PROJECT_STAGE_COMPLETION,
            ];

            $project = Project::find($project_id);
            if ($data['is_completed']) {
                if ($data['progress_stage'] == PROJECT_STAGE_COMPLETION) {
                    $project->status = PROJECT_READY_FOR_ARCHIVE_STATUS;
                } else {
                    $project->progress_stage = $stages[array_search($data['progress_stage'], $stages) + 1];
                }
            } else {
                if ($project->status == PROJECT_READY_FOR_ARCHIVE_STATUS) {
                    $project->status = PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS;
                }
                $project->progress_stage = $stages[array_search($data['progress_stage'], $stages)];
            }
            $project->save();

            $commentAudit = 'Updated Status of ' . $project->reference . ' Successfully';
            \CommonHelpers::logAudit(PROJECT_TYPE, $project->id, AUDIT_ACTION_EDIT, $project->reference, $project->property_id, $commentAudit, 0, $project->property_id);

            return \CommonHelpers::successResponse('Stage Project Updated Successfully!');
        } catch (\Exception $exception) {
            Log::error($exception);
            return \CommonHelpers::failResponse(STATUS_FAIL,'Update Stage Project Fail!');
        }
    }

    public function decommissionProject($project_id, $decommission_reason) {
        $project = Project::where('id', $project_id)->first();
        try {
            if ($project->decommissioned == PROJECT_DECOMMISSION) {
                Project::where('id', $project_id)->update(['decommissioned' => PROJECT_UNDECOMMISSION]);
                $comment = \Auth::user()->full_name  . " recommission project "  . $project->reference;
                \CommonHelpers::logAudit(PROJECT_TYPE, $project->id, AUDIT_ACTION_RECOMMISSION, $project->reference, $project->property_id ,$comment, 0 ,$project->property_id);
                return \CommonHelpers::successResponse('Project Recommissioned successfully!');
            } else {
                Project::where('id', $project_id)->update(['decommissioned' => PROJECT_DECOMMISSION, 'reason_decommissioned' => $decommission_reason]);
                $comment = \Auth::user()->full_name  . " decommission project "  . $project->reference;
                \CommonHelpers::logAudit(PROJECT_TYPE, $project->id, AUDIT_ACTION_DECOMMISSION, $project->reference, $project->property_id ,$comment, 0 ,$project->property_id);
                return \CommonHelpers::successResponse('Project Decommissioned successfully!');
            }
        } catch (\Exception $e){
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Decommission Project Fail!');
        }
    }

}

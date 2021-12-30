<?php
namespace App\Repositories;
use App\Helpers\CommonHelpers;
use App\Jobs\SendApprovalEmail;
use App\Models\Document;
use App\Models\PublishedWorkRequest;
use App\Models\ShineDocumentStorage;
use App\Models\WorkRequest;
use App\Models\WorkRequestType;
use App\User;
use Carbon\Carbon;
use App\Models\Project;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Zone;
use App\Models\WorkData;
use App\Models\Property;
use App\Models\WorkScope;
use App\Models\WorkEmailCC;
use App\Models\WorkRequirement;
use App\Models\WorkPropertyInfo;
use App\Models\WorkContact;
use App\Models\SorLogic;
use App\Models\WorkSupportingDocument;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class WorkRequestRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return WorkRequest::class;
    }

    public function getDropdownValue($type, $parent_id = 0) {
        $data = WorkData::where('type', $type)->where('parent_id', $parent_id)->get();
        return $data;
    }

    public function getComplianceTypes() {
        return WorkRequestType::all();
    }

    public function getDropdownByParent($parent_id = 0) {
        $data = WorkData::where('parent_id', $parent_id)->get();
        return $data;
    }

    /**
     * GET LIVE WORKS
     * @return mixed
     */
    public function getListWorkLive($type, $user_id = null){
        $result = WorkRequest::where(['decommissioned'=> 0])
            ->where('status', '!=', WORK_REQUEST_COMPLETE)
            ->where('compliance_type',$type);

        if (!empty($user_id)) {
            $result = $result->where('asbestos_lead', $user_id);
        }

        $result = $result->orderBy('created_at','desc')->get();
        return $result;
    }

    /**
     * GET COMPLETED WORKS
     * @return mixed
     */
    public function getListWorkCompleted($type){
        return WorkRequest::where(['decommissioned'=> 0, 'status' => WORK_REQUEST_COMPLETE])->where('compliance_type',$type)->orderBy('completed_date','desc')->get();
    }

    /**
     * GET DECOMMISSIONED WORKS
     * @return mixed
     */
    public function getListWorkDecommissioned($type){
        return WorkRequest::where(['decommissioned'=> WORK_REQUEST_CREATED_STATUS])->where('compliance_type',$type)->orderBy('updated_at','desc')->get();
    }

    /**
     * GET WAITING FOR APPROVAL WORKS
     * @return mixed
     */
    public function getListWorkWaitingForApproval($user_id = null){
        $query = WorkRequest::where(['decommissioned'=> 0, 'status' => WORK_REQUEST_AWAITING_APPROVAL]);
        if (!empty($user_id)) {
            $query = $query->where(['asbestos_lead' => $user_id]);
        }
        return $query->get();
    }

    /**
     * GET REJECTED WORKS
     * @return mixed
     */
    public function getListWorkRejected($user_id = null){
        $query = WorkRequest::where(['decommissioned'=> 0, 'status' => WORK_REQUEST_REJECT]);
        if (!empty($user_id)) {
            $query = $query->where(['asbestos_lead' => $user_id]);
        }
        return $query->get();
    }

    /**
     * Decommission / Recommission Work Request
     * @param $id
     * @return mixed
     */
    public function decommission($id, $reason = 0) {
        $workRequest = WorkRequest::find($id);
        try {
            if ($workRequest->decommissioned == SURVEY_DECOMMISSION) {
                $workRequest->decommissioned = SURVEY_UNDECOMMISSION;
                $workRequest->save();

                $comment = \Auth::user()->full_name  . " recommission work request "  . $workRequest->reference;
                \CommonHelpers::logAudit(WORK_REQUEST_TYPE, $workRequest->id, AUDIT_ACTION_RECOMMISSION, $workRequest->reference, $workRequest->property_id ,$comment, 0 ,$workRequest->property_id);
                return \CommonHelpers::successResponse('Work Request recommissioned successfully!');
            } else {

                $workRequest->decommissioned = SURVEY_DECOMMISSION;
                $workRequest->decommisioned_reason = $reason;
                $workRequest->save();

                $comment = \Auth::user()->full_name  . " decommission work request "  . $workRequest->reference;
                \CommonHelpers::logAudit(WORK_REQUEST_TYPE, $workRequest->id, AUDIT_ACTION_DECOMMISSION, $workRequest->reference, $workRequest->property_id ,$comment, 0 ,$workRequest->property_id);
                return \CommonHelpers::successResponse('Work Request decommissioned successfully!');
            }
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Decommission/Recommission Work Request. Please try again!');
        }
    }

    /**
     * Get Works for homepage approve
     * @return array
     */
    public function getMyWorkWaitingForApproval() {
        // property privilege
        $property_id_privs = \CompliancePrivilege::getPermission(PROPERTY_PERMISSION,'sql');
//        $project_types = \CompliancePrivilege::getPermission(PROJECT_TYPE_PERMISSION,'sql');
        $user_id = \Auth::user()->id;

        $sqlQuery = "SELECT pj.id as project_id, pj.reference, pj.title, pr.pblock, pr.property_reference pref, pr.name pname,
                            w.id, w.property_id, w.status, w.published_date, w.completed_date, w.sor_id, w.reference as work_reference
                                FROM tbl_work_request w
                                LEFT JOIN `tbl_work_data` as wt ON `w`.type = wt.id
                                LEFT JOIN tbl_project pj ON pj.id = w.project_id
                                LEFT JOIN tbl_property pr ON pr.id = w.property_id
                                WHERE w.status = ". WORK_REQUEST_AWAITING_APPROVAL ."
                                and w.property_id IN $property_id_privs
                                AND w.asbestos_lead = $user_id
                                ";
        $works = DB::select($sqlQuery);

        // GET MAJOR WORK
        $majorWork = DB::select("SELECT 'Multiple' as reference, 'Multiple' as pref,  'Multiple' as pname, w.is_major,
                                        w.id, w.status, w.published_date, w.completed_date, w.reference as work_reference
                                FROM tbl_work_request w
                                WHERE w.status = ". WORK_REQUEST_AWAITING_APPROVAL ."
                                and w.is_major = 1
                                AND w.asbestos_lead = $user_id ");

        return array_filter(array_merge($works, $majorWork));
    }

    /**
     * Approval work request + send mail + create project
     * @param $work_request_id
     * @param  ProjectRepository $this_project_repository
     * @return mixed
     * @throws \Exception
     */
    public function approvalWorkRequest($work_request_id, $this_project_repository){
        $work_request = WorkRequest::where('id', $work_request_id)
                                ->where('status', WORK_REQUEST_AWAITING_APPROVAL)
                                ->first();
        if($work_request){
            $complete_date = time();
            $work_request->status = WORK_REQUEST_COMPLETE;
            $work_request->completed_date = $complete_date;
            $approval_work_request = $work_request->save();

            \DB::beginTransaction();
            try{
                if($approval_work_request){
                    //create a new project and make a copy pdf work request into tender document
                    if ($work_request->is_major == 1) {
                        $property_ids = explode(",",$work_request->property_id_major);
                        $properties = Property::whereIn('id',$property_ids)->get();
                        $new_project_id = [];
                        foreach ($properties as $property) {
                            $new_project_id[] = $this_project_repository->createProjectWorkRequest($work_request, $complete_date, $property);
                        }
                        $work_request->update(['project_id_major'=>implode(",",$new_project_id)]);
                    } else {
                        $regenerate_pdf = app(\App\Http\Controllers\GeneratePDFController::class)->genWorkRequestAfterApprovePDF($work_request_id);
                        $new_project_id = $work_request->project_id;
                        if(!$new_project_id){
                            // case WR approved in Shine
                            $new_pj_id = $this_project_repository->createProjectWorkRequest($work_request, $complete_date, $work_request->property);
                            if($new_pj_id){
                                $work_request->update(['project_id'=>$new_pj_id]);
                            }
                        } else {
                            // case WR approved in Orchard
                            $project = Project::find($new_project_id);
                            if($project){
                                if($project->contractors){
                                    $ExistPONotifications = \Notifications::getListExistingNotificationsInProject($project->id, 1);
                                    $ExistPOContractors = [];
                                    foreach ($ExistPONotifications as $existNoti) {
                                        $ExistPOContractors[] = $existNoti->contractor_id;
                                    }
//                                    foreach (explode(",", $project->contractors) as  $contractor_id) {
//                                        if(!in_array($contractor_id, $ExistPOContractors)){
//                                            \Notifications::sendMailNotification(1, $project->id, $contractor_id);
//                                        }
//                                    }
                                }
                                if($project->checked_contractors) {
                                    $ExistSuccessNotifications = \Notifications::getListExistingNotificationsInProject($project->id, 3);
                                    $ExistSuccessContractors = [];
                                    foreach ($ExistSuccessNotifications as $existNoti) {
                                        $ExistSuccessContractors[] = $existNoti->contractor_id;
                                    }
                                    foreach (explode(",", $project->checked_contractors) as $contractor_id) {
                                        if(!in_array($contractor_id, $ExistSuccessContractors)){
                                            \Notifications::sendMailNotification(3, $project->id, $contractor_id);
                                        }
                                    }
                                }
                            }
                        }
                        // send mail work request approval
                        if($work_request != null){
                            $dataUser = User::where('id', $work_request->created_by)->first();
                            $emailCC = WorkEmailCC::where('work_id',$work_request->id)->first();
                            $additional_email = [];
                            if(!is_null($emailCC)){
                                $additional_email =  explode(",", $emailCC->email);
                            }
                            // major work
                            if($work_request->is_major == 1) {
                                $property_ref = 'Multiple';
                                $property_name = 'Multiple';
                            } else {
                                $property_ref = $work_request->property->property_reference ?? '';
                                $property_name = $work_request->property->name ?? '';
                            }
                            $postcode = $work_request->property->propertyInfo->postcode ?? '';
                            $wr_ref = $work_request->reference ?? '';
                            $wr_type = $work_request->workData->description ?? '';
                            $data = [
                                'subject' =>'Work Request Approved',
                                'work_requester' => $dataUser->full_name ?? '',
                                'email' => $dataUser->email ?? '',
                                "block_reference" => $work_request->property->pblock ?? '',
                                'work_request_reference' => $wr_ref,
                                'work_requester_type' => $wr_type,
                                'property_reference' => $property_ref,
                                'property_name' => $property_name,
                                'company_name' => $dataUser->clients->name ?? '',
                                'property_postcode' => $postcode,
                                'domain' => \Config::get('app.url')
                            ];
                            \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendApprovalEmail($data, WORK_REQUEST_EMAIL_QUEUE,$additional_email));
                        }
                        $copy_pdf = $this->copyWorkRequestDocument($work_request, $work_request->project);
                        if ($regenerate_pdf) {
                            //update this function so when approval work request just only copy WR pdf to project tender doc
                            //add audit trail
                            $user = \Auth::user();
                            $property_name = $work_request->property->name ?? '';
                            $comment = 'PDF File removed due to GDPR Regulations for Work Request '. $work_request->reference . " on property " . $property_name;
                            \CommonHelpers::logAudit(WORK_REQUEST_TYPE, $work_request->id, AUDIT_ACTION_APPROVED, $work_request->reference, $work_request->property_id ,$comment, 0 ,$work_request->property_id);
                            # code...
                        } else {
                            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Approve Work Request. Please try again!');
                        }
                    }
                    //link project to work request

                    //log audit
                    $user = \Auth::user();
                    $property_name = $work_request->property->name ?? '';
                    $comment = $user->first_name . " " . $user->last_name . "  approved Work Request " . $work_request->reference . " on property " . $property_name;
                    \CommonHelpers::logAudit(WORK_REQUEST_TYPE, $work_request->id, AUDIT_ACTION_APPROVED, $work_request->reference, $work_request->property_id ,$comment, 0 ,$work_request->property_id);
                }
                \DB::commit();
                return \CommonHelpers::successResponse('Work Request Approved Successfully!');
            } catch (\Exception $e){
                dd($e);
                \DB::rollback();
                $work_request->status = WORK_REQUEST_AWAITING_APPROVAL;
                $work_request->completed_date = 0;
                $work_request->save();
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Approve Work Request. Please try again!');
            }
        }
        return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Approval Work Request Fail!');
    }



    public function copyWorkRequestDocument($work_request, $project, $contractors = ''){
        $work_request_document = PublishedWorkRequest::where('work_request_id', $work_request->id)->orderBy('id','DESC')->first();
        // only create document for WR when the API is done
        if($work_request_document && $project){
            $dataDocument = [
                "client_id" => $project->client_id,
                "name" => $work_request_document->name ?? '',
                "project_id" => $project->id,
                "type" => 129,
                "re_type" => "project",
                "contractors" => $project->contractors ?? '', // permission
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
                //send email
                //TODO send mail later, recheck
//                if(isset($work_request->contractors) && !empty($work_request->contractors)){
//                    foreach (explode(",", $work_request->contractors) as  $contractor_id) {
//                        \Notifications::sendMailNotification(1, $project->id, $contractor_id);
//                    }
//                }
//                if(isset($work_request->checked_contractors) && !empty($work_request->checked_contractors)) {
//                    foreach (explode(",", $work_request->checked_contractors) as $contractor_id) {
//                        \Notifications::sendMailNotification(3, $project->id, $contractor_id);
//                    }
//                }
            }
            return true;
        }
        return false;
    }

    public function rejectWorkRequest($data) {
        try {
            \DB::beginTransaction();
            $due_date = \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'due_date'));
            $work_request = WorkRequest::where('id',$data['id'])->first();
            $list_new_team = [];
            if($work_request->is_major == 0){
                $list_contact = $work_request->team ?? [];
                $list_pro_team = $work_request->property->propertyInfo->team ?? [];
                $list_new_team = array_unique(array_filter(array_merge($list_contact, $list_pro_team)));
            }

            WorkRequest::where('id',$data['id'])->update([
                'comments' => $data['note'],
                'due_date' => $due_date,'status' => WORK_REQUEST_REJECT,
                'team1' => $work_request->is_major == 0 ? (isset($list_new_team[0]) ? $list_new_team[0] : NULL) : NULL,
                'team2' => $work_request->is_major == 0 ? (isset($list_new_team[1]) ? $list_new_team[1] : NULL) : NULL,
                'team3' => $work_request->is_major == 0 ? (isset($list_new_team[2]) ? $list_new_team[2] : NULL) : NULL,
                'team4' => $work_request->is_major == 0 ? (isset($list_new_team[3]) ? $list_new_team[3] : NULL) : NULL,
                'team5' => $work_request->is_major == 0 ? (isset($list_new_team[4]) ? $list_new_team[4] : NULL) : NULL,
                'team6' => $work_request->is_major == 0 ? (isset($list_new_team[5]) ? $list_new_team[5] : NULL) : NULL,
                'team7' => $work_request->is_major == 0 ? (isset($list_new_team[6]) ? $list_new_team[6] : NULL) : NULL,
                'team8' => $work_request->is_major == 0 ? (isset($list_new_team[7]) ? $list_new_team[7] : NULL) : NULL,
                'team9' => $work_request->is_major == 0 ? (isset($list_new_team[8]) ? $list_new_team[8] : NULL) : NULL,
                'team10' =>$work_request->is_major == 0 ? (isset($list_new_team[9]) ? $list_new_team[9] : NULL) : NULL,
            ]);

            if($work_request != null){
                $dataUser = User::where('id', $work_request->created_by)->first();
                // major work
                if($work_request->is_major == 1) {
                    $property_ref = 'Multiple';
                    $property_name = 'Multiple';
                } else {
                    $property_ref = $work_request->property->property_reference ?? '';
                    $property_name = $work_request->property->name ?? '';
                }
                $postcode = $work_request->property->propertyInfo->postcode ?? '';
                $wr_ref = $work_request->reference ?? '';
                $wr_type = $work_request->workData->description ?? '';
                $data = [
                    'subject' =>'Work Request Rejected',
                    'work_requester' => $dataUser->full_name ?? '',
                    'email' => $dataUser->email ?? '',
                    "block_reference" => $work_request->property->pblock ?? '',
                    'work_request_reference' => $wr_ref,
                    'work_requester_type' => $wr_type,
                    'property_reference' => $property_ref,
                    'property_name' => $property_name,
                    'company_name' => $dataUser->clients->name ?? '',
                    'domain' => \Config::get('app.url'),
                    'property_postcode' => $postcode
                ];
                \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendApprovalEmail($data, WORK_REQUEST_EMAIL_QUEUE));
            }
            \DB::commit();
            return \CommonHelpers::successResponse('Reject Work Request Successful!');
        } catch (\Exception $e) {
            \DB::rollback();
            return \CommonHelpers::failResponse(STATUS_FAIL,'Reject Work Request Fail!');

        }
    }

    public function updateOrCreateWork($data, $id = false) {
        try {
            \DB::beginTransaction();

            $is_major =  ($data['wr_type'] == MAJOR_WORK_ID || $data['wr_type'] == FIRE_MAJOR_WORK_ID) ? true : false;


            if (isset($data['property_contact'])) {
                $data_prop_contact = $data['property_contact'];
            } else {
                $data_prop_contact = [];
            }
            $data_wr = [
                'type' => $is_major ? \CommonHelpers::checkArrayKey($data,'major_type') :\CommonHelpers::checkArrayKey($data,'type'),
                'is_major' => $is_major ? 1 : 0,
                'sor_id' => \CommonHelpers::checkArrayKey($data,'sor_id'),
                'property_id' => \CommonHelpers::checkArrayKey($data,'property_id'),
                'project_id' => \CommonHelpers::checkArrayKey($data,'project_id'),
                'asbestos_lead' => \CommonHelpers::checkArrayKey($data,'asbestos_lead'),
                'second_asbestos_lead' => \CommonHelpers::checkArrayKey($data,'second_asbestos_lead'),
                'contractor' => \CommonHelpers::checkArrayKey($data,'contractor'),
                'decommissioned' => 0,
                'published_date' => 0,
                'completed_date' => 0,
                'rejected_date' => 0,
                'due_date' => 0,
                'created_date' => time(),
                'priority' => \CommonHelpers::checkArrayKey($data,'priority'),
                'priority_reason' => \CommonHelpers::checkArrayKey($data,'priority_reason'),
                'team1' => (int)\CommonHelpers::checkArrayKey3($data['property_contact'] ?? [],0),
                'team2' => (int)\CommonHelpers::checkArrayKey3($data['property_contact'] ?? [],1),
                'team3' => (int)\CommonHelpers::checkArrayKey3($data['property_contact'] ?? [],2),
                'team4' => (int)\CommonHelpers::checkArrayKey3($data['property_contact'] ?? [],3),
                'team5' => (int)\CommonHelpers::checkArrayKey3($data['property_contact'] ?? [],4),
                'team6' => (int)\CommonHelpers::checkArrayKey3($data['property_contact'] ?? [],5),
                'team7' => (int)\CommonHelpers::checkArrayKey3($data['property_contact'] ?? [],6),
                'team8' => (int)\CommonHelpers::checkArrayKey3($data['property_contact'] ?? [],7),
                'team9' => (int)\CommonHelpers::checkArrayKey3($data['property_contact'] ?? [],8),
                'team10' => (int)\CommonHelpers::checkArrayKey3($data['property_contact'] ?? [],9),
                'non_user' => in_array('nonuser', $data['property_contact'] ?? []) ? 1 : 0,
                'duration_number_test' => \CommonHelpers::checkArrayKey($data,'duration_number_test'),
                'compliance_type' => \CommonHelpers::checkArrayKey($data,'compliance_type'),
            ];

            $data_work_contact = [
                'first_name' => \CommonHelpers::checkArrayKey($data,'first_name'),
                'last_name' => \CommonHelpers::checkArrayKey($data,'last_name'),
                'telephone' => \CommonHelpers::checkArrayKey($data,'telephone'),
                'mobile' => \CommonHelpers::checkArrayKey($data,'mobile'),
                'email' => \CommonHelpers::checkArrayKey($data,'email'),
            ];

            $data_prop_info = [
                "site_occupied" => (isset($data['site_occupied'])) ? 1 : 0,
                "site_availability" => \CommonHelpers::checkArrayKey($data,'site_availability'),
                "security_requirements" => \CommonHelpers::checkArrayKey($data,'security_requirements'),
                "parking_arrangements" => \CommonHelpers::checkArrayKey($data,'parking_arrangements'),
                "parking_arrangements_other" => \CommonHelpers::checkArrayKey($data,'parking_arrangements_other'),
                "electricity_availability" => (isset($data['electricity_availability'])) ? 1 : 0,
                "water_availability" => (isset($data['water_availability'])) ? 1 : 0,
                "ceiling_height" => \CommonHelpers::checkArrayKey($data,'ceiling_height'),
            ];

            $data_work_scope = [
                'scope_of_work' => \CommonHelpers::checkArrayKey($data,'scope_of_work'),
                'reported_by' => \CommonHelpers::checkArrayKey($data,'reported_by'),
                'access_note' => \CommonHelpers::checkArrayKey($data,'access_note'),
                'location_note' => \CommonHelpers::checkArrayKey($data,'location_note'),
                'air_test_type' => \CommonHelpers::checkArrayKey($data,'air_test_type'),
                'enclosure_size' => \CommonHelpers::checkArrayKey($data,'enclosure_size'),
                'duration_of_work' => \CommonHelpers::checkArrayKey($data,'duration_of_work'),
                'isolation_required' => (isset($data['isolation_required'])) ? 1 : 0,
                'isolation_required_comment' => \CommonHelpers::checkArrayKey($data,'isolation_required_comment'),
                'decant_required' => (isset($data['decant_required'])) ? 1 : 0,
                'decant_required_comment' => \CommonHelpers::checkArrayKey($data,'decant_required_comment'),
                'reinstatement_requirements' => \CommonHelpers::checkArrayKey($data,'reinstatement_requirements'),
                'number_of_rooms' => \CommonHelpers::checkArrayKey($data,'number_of_rooms'),
                'unusual_requirements' => \CommonHelpers::checkArrayKey($data,'unusual_requirements'),
            ];

            $data_work_requirement = [
                'site_hs' => \CommonHelpers::checkArrayKey($data,'site_hs'),
                'hight_level_access' => (isset($data['hight_level_access'])) ? 1 : 0,
                'hight_level_access_comment' => \CommonHelpers::checkArrayKey($data,'hight_level_access_comment'),
                'max_height' => (isset($data['max_height'])) ? 1 : 0,
                'max_height_comment' => \CommonHelpers::checkArrayKey($data,'max_height_comment'),
                'loft_spaces' => (isset($data['loft_spaces'])) ? 1 : 0,
                'loft_spaces_comment' => \CommonHelpers::checkArrayKey($data,'loft_spaces_comment'),
                'floor_voids' => (isset($data['floor_voids'])) ? 1 : 0,
                'floor_voids_comment' => \CommonHelpers::checkArrayKey($data,'floor_voids_comment'),
                'basements' => (isset($data['basements'])) ? 1 : 0,
                'basements_comment' => \CommonHelpers::checkArrayKey($data,'basements_comment'),
                'ducts' => (isset($data['ducts'])) ? 1 : 0,
                'ducts_comment' => \CommonHelpers::checkArrayKey($data,'ducts_comment'),
                'lift_shafts' => (isset($data['lift_shafts'])) ? 1 : 0,
                'lift_shafts_comment' => \CommonHelpers::checkArrayKey($data,'lift_shafts_comment'),
                'light_wells' => (isset($data['light_wells'])) ? 1 : 0,
                'light_wells_comment' => \CommonHelpers::checkArrayKey($data,'light_wells_comment'),
                'confined_spaces' => (isset($data['confined_spaces'])) ? 1 : 0,
                'confined_spaces_comment' => \CommonHelpers::checkArrayKey($data,'confined_spaces_comment'),
                'fumes_duct' => (isset($data['fumes_duct'])) ? 1 : 0,
                'fumes_duct_comment' => \CommonHelpers::checkArrayKey($data,'fumes_duct_comment'),
                'pm_good' => (isset($data['pm_good'])) ? 1 : 0,
                'pm_good_comment' => \CommonHelpers::checkArrayKey($data,'pm_good_comment'),
                'fragile_material' => (isset($data['fragile_material'])) ? 1 : 0,
                'fragile_material_comment' => \CommonHelpers::checkArrayKey($data,'fragile_material_comment'),
                'hot_live_services' => (isset($data['hot_live_services'])) ? 1 : 0,
                'hot_live_services_comment' => \CommonHelpers::checkArrayKey($data,'hot_live_services_comment'),
                'pieons' => (isset($data['pieons'])) ? 1 : 0,
                'pieons_comment' => \CommonHelpers::checkArrayKey($data,'pieons_comment'),
                'vermin' => (isset($data['vermin'])) ? 1 : 0,
                'vermin_comment' => \CommonHelpers::checkArrayKey($data,'vermin_comment'),
                'biological_chemical' => (isset($data['biological_chemical'])) ? 1 : 0,
                'biological_chemical_comment' => \CommonHelpers::checkArrayKey($data,'biological_chemical_comment'),
                'vulnerable_tenant' => (isset($data['vulnerable_tenant'])) ? 1 : 0,
                'vulnerable_tenant_comment' => \CommonHelpers::checkArrayKey($data,'vulnerable_tenant_comment'),
                'other' => \CommonHelpers::checkArrayKey($data,'other'),
            ];
            $data_email = [
                'email' => implode(",",$data['email_cc'] ?? [])
            ];
            if (isset($data['major_document'])) {
                $prop_reference = [];
                // Get uploaded CSV file
                $file = $this->parse_csv($data['major_document']);

                if (count($file)) {
                    foreach ($file as $key => $value) {
                        $prop_reference_key = current($value);
                        $prop_reference_key = str_replace('.', '', $prop_reference_key); // remove dots
                        $prop_reference_key = str_replace("\t", '', $prop_reference_key); // remove tabs
                        $prop_reference_key = str_replace("\n", '', $prop_reference_key); // remove new lines
                        $prop_reference_key = str_replace("\r", '', $prop_reference_key);
                        $prop_reference_key = trim(  $prop_reference_key, '"');
                        $prop_reference[] = $prop_reference_key;
                    }
                }
                $property_ids = Property::whereIn('reference',$prop_reference)->take(100)->pluck('id')->toArray();


                $property_ids = implode(",",$property_ids);
                $data_wr['property_id_major'] = $property_ids;
            }
            if ($id) {
                $data_wr['status'] = WORK_REQUEST_READY_QA;

                WorkRequest::where('id', $id)->update($data_wr);
                $work_request = WorkRequest::find($id);
                WorkEmailCC::where('work_id', $id)->update($data_email);
                WorkPropertyInfo::where('work_id', $id)->update($data_prop_info);
                WorkScope::where('work_id', $id)->update($data_work_scope);
                WorkRequirement::where('work_id', $id)->update($data_work_requirement);
                WorkContact::where('work_id', $id)->update($data_work_contact);

                $response = \CommonHelpers::successResponse('Work Request Updated successfully !',$work_request->id);
                \CommonHelpers::logAudit(WORK_REQUEST_TYPE, $work_request->id, AUDIT_ACTION_EDIT, $work_request->reference, 0, null , 0, 0);
            } else {
                $data_wr['status'] =WORK_REQUEST_CREATED_STATUS;
                $data_wr['created_by'] = \Auth::user()->id;
                $work_request = WorkRequest::create($data_wr);
                if ($work_request) {
                    if ($is_major) {
                        $refWork = 'PWR' . $work_request->id;
                    } else {
                        $refWork = 'WR' . $work_request->id;
                    }

                    WorkRequest::where('id', $work_request->id)->update(['reference' => $refWork]);
                    $data_prop_info['work_id'] = $work_request->id;
                    $data_work_scope['work_id'] = $work_request->id;
                    $data_work_requirement['work_id'] = $work_request->id;
                    $data_work_contact['work_id'] = $work_request->id;
                    $data_email['work_id'] = $work_request->id;
                    WorkEmailCC::create($data_email);
                    WorkPropertyInfo::create($data_prop_info);
                    WorkScope::create($data_work_scope);
                    WorkRequirement::create($data_work_requirement);
                    WorkContact::create($data_work_contact);

                }
                $response = \CommonHelpers::successResponse('Work Request Created successfully !',$work_request->id);
                \CommonHelpers::logAudit(WORK_REQUEST_TYPE, $work_request->id, AUDIT_ACTION_ADD, $refWork, 0, null , 0, 0);
            }
            if (isset($data['document'])) {
                $dataDoc = [
                    'work_id' => $work_request->id,
                    'name' => 'Additional Document',
                    'document' => $data['document']
                ];
                $saveLocationImage = $this->updateOrCreateDocument($dataDoc);
            }
            \DB::commit();
            return $response;
        } catch (\Exception $e) {
            \DB::rollback();
            return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Create/Update Work Request. Please try again!');
        }
    }

    public function updateOrCreateDocument($data, $id = null) {
        if (!empty($data)) {
            $dataWork = [
                "work_id" => \CommonHelpers::checkArrayKey3($data,'work_id'),
                "name" => \CommonHelpers::checkArrayKey3($data,'name'),
            ];
            try {
                if (is_null($id)) {
                    $workDoc = WorkSupportingDocument::create($dataWork);
                    if ($workDoc) {
                        $workDocRef = "WD" . $workDoc->id;
                        WorkSupportingDocument::where('id', $workDoc->id)->update(['reference' => $workDocRef]);
                    }
                    $response = \CommonHelpers::successResponse('Work Request Supporting Document Created successfully !');
                    //log audit
                    \CommonHelpers::logAudit(WORK_REQUEST_DOC_TYPE, $workDoc->id, AUDIT_ACTION_ADD, $workDocRef, $workDoc->work_id, null , 0, 0);
                } else {
                    WorkSupportingDocument::where('id', $id)->update($dataWork);
                    $workDoc = WorkSupportingDocument::where('id', $id)->first();
                    $response = \CommonHelpers::successResponse('Work Request Supporting Document Updated successfully !');

                    //log audit
                    \CommonHelpers::logAudit(WORK_REQUEST_DOC_TYPE, $workDoc->id, AUDIT_ACTION_EDIT, $workDoc->reference, $workDoc->work_id, null , 0, 0);
                }

                if (isset($data['document'])) {
                    $saveDoc = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $workDoc->id, WORK_REQUEST_FILE);
                }

                return $response;
            } catch (\Exception $e) {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload Work Request Supporting Document. Please try again !');
            }
        }
    }

   public function parse_csv($file, $options = null) {
        $delimiter = empty($options['delimiter']) ? "," : $options['delimiter'];
        $to_object = empty($options['to_object']) ? false : true;
        $str = file_get_contents($file);
        $lines = explode("\n", $str);
        // pr($lines);
        $field_names = explode($delimiter, array_shift($lines));
        foreach ($lines as $line) {
            // Skip the empty line
            if (empty($line)) continue;
            $fields = explode($delimiter, $line);
            $_res = $to_object ? new stdClass : array();
            foreach ($field_names as $key => $f) {
                if ($to_object) {
                    $_res->{$f} = $fields[$key];
                } else {
                    $_res[$f] = $fields[$key] ?? 0;
                }
            }
            $res[] = $_res;
        }
        return $res;
    }

    public function searchWork($q,$is_major = 0) {
        return $this->model->whereRaw("(reference LIKE '%$q%')")->where('is_major', $is_major)->orderBy('reference','asc')->limit(LIMIT_SEARCH)->get();
    }

    public function checkSorLogic($work_type_id, $property_type_id, $rooms,$priority,$duration,$enclosure_size,$number_positive, $duration_number_test) {
        // get compare type
        $type = SorLogic::where('work_type_id', $work_type_id)->first();
        if(!is_null($type)) {
            switch ($type->type_compare) {
                case 'property_type':
                    $sor_count =  SorLogic::where('work_type_id', $work_type_id)->where('property_type_id', $property_type_id)->count();
                    if($sor_count == 1) {
                        $sor_data =  SorLogic::where('work_type_id', $work_type_id)->where('property_type_id', $property_type_id)->first();
                    } elseif ($sor_count > 1) {
                        switch ($work_type_id) {
                            // check enclosure_size
                            case 16: // MS
                                if($property_type_id == 52){
                                    if($enclosure_size > 2000) {
                                        $sor_data =  SorLogic::find(14);
                                    } elseif($enclosure_size < 2000 and $enclosure_size > 1000) {
                                        $sor_data =  SorLogic::find(13);
                                    } elseif($enclosure_size < 1000 and  $enclosure_size > 500) {
                                        $sor_data =  SorLogic::find(12);
                                    } else {
                                        $sor_data =  SorLogic::find(11);
                                    }
                                } else if($property_type_id == 19 ){
                                    //same User Code
                                    if($rooms >= 4) {
                                        $sor_data =  SorLogic::find(10);
                                    } else {
                                        $sor_data =  SorLogic::find(9);
                                    }
                                } else if($property_type_id == 20){
                                    //same User Code
                                    if($rooms >= 4) {
                                        $sor_data =  SorLogic::find(65);
                                    } else {
                                        $sor_data =  SorLogic::find(64);
                                    }
                                }
                                break;
                            case 17: //
                                if($enclosure_size > 2000) {
                                    $sor_data =  SorLogic::find(40);
                                } elseif($enclosure_size < 2000 and $enclosure_size > 1000) {
                                    $sor_data =  SorLogic::find(39);
                                } elseif($enclosure_size < 1000 and  $enclosure_size > 500) {
                                    $sor_data =  SorLogic::find(38);
                                } else {
                                    $sor_data =  SorLogic::find(37);
                                }
                                break;
                            case 18: // MS R&D
                                if($property_type_id == 52){
                                    if($enclosure_size > 2000) {
                                        $sor_data =  SorLogic::find(82);
                                    } elseif($enclosure_size < 2000 and $enclosure_size > 1000) {
                                        $sor_data =  SorLogic::find(81);
                                    } elseif($enclosure_size < 1000 and  $enclosure_size > 500) {
                                        $sor_data =  SorLogic::find(80);
                                    } else {
                                        $sor_data =  SorLogic::find(79);
                                    }
                                } else if($property_type_id == 19 ){
                                    //same User Code
                                    if($rooms >= 4) {
                                        $sor_data =  SorLogic::find(78);
                                    } else {
                                        $sor_data =  SorLogic::find(77);
                                    }
                                } else if($property_type_id == 20){
                                    //same User Code
                                    if($rooms >= 4) {
                                        $sor_data =  SorLogic::find(84);
                                    } else {
                                        $sor_data =  SorLogic::find(83);
                                    }
                                }
                                break;
                            //check number_positive
                            case 20: //
                                if($property_type_id == 52){
                                    if($number_positive == 1) {
                                        $sor_data =  SorLogic::find(26);
                                    } else {
                                        $sor_data =  SorLogic::find(25);
                                    }
                                } else if($property_type_id == 19){
                                    //same User Code
                                    if($rooms >= 4) {
                                        $sor_data =  SorLogic::find(24);
                                    } else {
                                        $sor_data =  SorLogic::find(23);
                                    }
                                } else if($property_type_id == 20){
                                    //same User Code
                                    if($rooms >= 4) {
                                        $sor_data =  SorLogic::find(67);
                                    } else {
                                        $sor_data =  SorLogic::find(66);
                                    }
                                }
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                    break;

                case 'priority':
                    $sor_data =  SorLogic::where('work_type_id', $work_type_id)->where('priority_id', $priority)->first();
                    break;

                case 'duration':
                    $sor_count =  SorLogic::where('work_type_id', $work_type_id)->count();
                    if($sor_count == 1) {
                        $sor_data =  SorLogic::where('work_type_id', $work_type_id)->first();
                    } else if ($sor_count > 1) {
                        switch ($work_type_id) {
                            case 10: // 3.0 Asbestos Air Monitoring -> Background
                            case 11: // 3.0 Asbestos Air Monitoring -> Reassurance Test
                            case 54: // 3.0 Asbestos Air Monitoring -> SEM Testing - Standard Response
                                $sor_data = SorLogic::where('work_type_id', $work_type_id)->where('priority_id', $priority)->first();
                                break;
                            case 12: // 3.0 Asbestos Air Monitoring -> 4-Stage Air Test
                                $sor_data = SorLogic::where('work_type_id', $work_type_id)->where('duration_number_test_id', $duration_number_test)->first();
                                break;
                            default:
                                # code...
                                break;
                        }
                    }
                    break;

                default:
                    return \CommonHelpers::failResponse(STATUS_FAIL,'Not found SOR!');
                    break;
            }
        }
        if(isset($sor_data)) {
            if($sor_data->user_code == '#N/A') {
                 return \CommonHelpers::failResponse(302,'SOR User code: #N/A');
            }
            return \CommonHelpers::successResponse('Validated SOR Successfully - User Code: '.$sor_data->user_code,$sor_data->id);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Not found SOR!');

    }

    public function checkException($property_id){
        $orchard = DB::select("SELECT id FROM tbl_orchard o
                                        WHERE property_responsibility LIKE '%FALSE%'
                                        AND property_id = $property_id LIMIT 1");
        return count($orchard) > 0 ? true: false;
    }
}

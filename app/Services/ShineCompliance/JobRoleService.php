<?php

namespace App\Services\ShineCompliance;


use App\Helpers\CommonHelpers;
use App\Models\ShineCompliance\WorkData;
use App\Models\ShineCompliance\WorkFlow;
use App\Models\TemplateDocument;
use App\Repositories\ShineCompliance\ClientRepository;
use App\Repositories\ShineCompliance\ItemRepository;
use App\Repositories\ShineCompliance\JobRoleEditValueRepository;
use App\Repositories\ShineCompliance\JobRoleRepository;
use App\Repositories\ShineCompliance\JobRoleViewValueRepository;
use App\Repositories\ShineCompliance\PrivilegeCommonRepository;
use App\Repositories\ShineCompliance\PrivilegeUpdateRepository;
use App\Repositories\ShineCompliance\PrivilegeViewRepository;
use App\Repositories\ShineCompliance\PropertyRepository;
use App\Repositories\ShineCompliance\UserRepository;
use App\Repositories\ShineCompliance\ZoneRepository;
use Illuminate\Support\Facades\DB;

class JobRoleService{

    private $jobRoleRepository;
    private $privilegeViewRepository;
    private $privilegeUpdateRepository;
    private $privilegeCommonRepository;
    private $jobRoleEditValueRepository;
    private $jobRoleViewValueRepository;
    private $userRepository;
    private $propertyRepository;
    private $clientRepository;
    private $itemRepository;
    private $zoneRepository;

    public function __construct(JobRoleRepository $jobRoleRepository,
                                PrivilegeViewRepository $privilegeViewRepository,
                                PrivilegeUpdateRepository $privilegeUpdateRepository,
                                PrivilegeCommonRepository $privilegeCommonRepository,
                                JobRoleEditValueRepository $jobRoleEditValueRepository,
                                JobRoleViewValueRepository $jobRoleViewValueRepository,
                                UserRepository $userRepository,
                                PropertyRepository $propertyRepository,
                                ClientRepository $clientRepository,
                                ItemRepository $itemRepository,
                                ZoneRepository $zoneRepository){
        $this->jobRoleRepository = $jobRoleRepository;
        $this->privilegeViewRepository = $privilegeViewRepository;
        $this->privilegeUpdateRepository = $privilegeUpdateRepository;
        $this->privilegeCommonRepository = $privilegeCommonRepository;
        $this->jobRoleEditValueRepository = $jobRoleEditValueRepository;
        $this->jobRoleViewValueRepository = $jobRoleViewValueRepository;
        $this->userRepository = $userRepository;
        $this->propertyRepository = $propertyRepository;
        $this->clientRepository = $clientRepository;
        $this->itemRepository = $itemRepository;
        $this->zoneRepository = $zoneRepository;
    }

    public function getDetail($id, $request, $relations = []){
        return $this->jobRoleRepository->getDetail($id, $request, $relations);
    }

    public function getDetailByClient($id, $request, $client_id, $zone_ids){
        return $this->jobRoleRepository->getDetailByClient($id, $request, $client_id, $zone_ids);
    }

    public function getListJobRoles(){
        return $this->jobRoleRepository->getListJobRoles();
    }

    public function getPrivilegeView($type, $relations = []){
        return $this->privilegeViewRepository->getPrivilegeView($type, $relations);
    }

    public function getPrivilegeUpdate($type, $relations = []){
        return $this->privilegeUpdateRepository->getPrivilegeUpdate($type, $relations);
    }

    public function getPrivilegeCommon(){
        $data = $this->privilegeCommonRepository->getPrivilegeCommon();
        return $data->groupBy('type')->toArray();
    }

    public function getArrayValuesPrivilegeCommon($type){
        $data = $this->privilegeCommonRepository->getArrayValuesPrivilegeCommon($type);
        return $data ?? [];
    }

    public function getPrivilegeChild(){
        return $this->privilegeCommonRepository->getPrivilegeCommon();
    }

    public function updateOrCreateRole($data, $job_id = 0){
        try {
            \DB::beginTransaction();
            $role = $this->jobRoleRepository->updateOrCreateRole($data, $job_id);
            \DB::commit();
            if ($job_id > 0) {
                //log audit
                \CommonHelpers::logAudit(ROLE, $role->id, AUDIT_ACTION_EDIT, $data['name']);
                return $response = \CommonHelpers::successResponse('Updated Job Role Successfully!', $role);
            } else {
                $this->jobRoleEditValueRepository->create(['role_id' => $role->id]);
                $this->jobRoleViewValueRepository->create(['role_id' => $role->id]);
                //log audit
                \CommonHelpers::logAudit(ROLE, $role->id, AUDIT_ACTION_ADD, $data['name']);
                return $response = \CommonHelpers::successResponse('Added Job Role Successfully!', $role);
            }
        } catch (\Exception $e){
            \DB::rollback();
            \Log::error($e);
            dd($e);
            return \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to creating Job Role! Please try again');
        }
    }

    public function updateOrCreateRoleValue($data, $job_role){
        try {
            \DB::beginTransaction();
            $data_view_value = $job_role->jobRoleViewValue;
            $data_edit_value = $job_role->jobRoleEditValue;
            $key = $job_role->getKeyByType($data->role_type ?? '');
//            dd($key, $data_view_value, $data_edit_value);
            //current view values
            $common_everything_view = isset($data_view_value->common_everything) ? json_decode($data_view_value->common_everything) : new \stdClass();
            $common_static_values_view = isset($data_view_value->common_static_values_view) ? json_decode($data_view_value->common_static_values_view) : new \stdClass();
            $common_dynamic_values_view = isset($data_view_value->common_dynamic_values_view) ? json_decode($data_view_value->common_dynamic_values_view) : new \stdClass();
            //current update value
            $common_everything_update = isset($data_edit_value->common_everything) ? json_decode($data_edit_value->common_everything) : new \stdClass();
            $common_static_values_update = isset($data_edit_value->common_static_values_update) ? json_decode($data_edit_value->common_static_values_update) : new \stdClass();
            $common_dynamic_values_update = isset($data_edit_value->common_dynamic_values_update) ? json_decode($data_edit_value->common_dynamic_values_update) : new \stdClass();
            //set view values
            $common_everything_view->{$key} = $data->common_everything_view ?? 0;
            $common_static_values_view->{$key} = isset($data->common_static_values_view) ? $data->common_static_values_view : "";
            $common_dynamic_values_view->{$key} = isset($data->common_dynamic_values_view) ? $data->common_dynamic_values_view : "";

            $data_view_value->common_everything = json_encode($common_everything_view);
            $data_view_value->common_static_values_view = json_encode($common_static_values_view);
            $data_view_value->common_dynamic_values_view = json_encode($common_dynamic_values_view);
            //todo save ALl client in View/Edit
            //todo save Add Group Permission/Clients/Group
            if($data->role_type == JOB_ROLE_GENERAL){
                $data_view_value->general_is_operative = $data->general_site_operative_view ?? 0;
                if(isset($data->general_site_operative_view)){
                    //update all user in this role to site operative
                    $this->userRepository->where(['role' => $job_role->id])->update(['is_site_operative' => $data->general_site_operative_view]);
                }
//                if(in_array(JR_ALL_PROPERTIES, json_decode($data->common_static_values_view))){
//                    $job_role->propertyView()->sync($this->propertyRepository->getAllProperty());
//                }
//                if(in_array(JR_UPDATE_ALL_PROPERTIES, json_decode($data->common_static_values_update))){
//                    $job_role->propertyEdit()->sync($this->propertyRepository->getAllProperty());
//                }
                $data_view_value->general_is_tt = $data->general_view_trouble_ticket_view ?? 0;
            }
            $data_view_value->save();

            //set update values
            $common_everything_update->{$key} = $data->common_everything_update ?? 0;
            $common_static_values_update->{$key} = isset($data->common_static_values_update) ? $data->common_static_values_update : "";
            $common_dynamic_values_update->{$key} = isset($data->common_dynamic_values_update) ? $data->common_dynamic_values_update : "";
            $data_edit_value->common_everything = json_encode($common_everything_update);
            $data_edit_value->common_static_values_update = json_encode($common_static_values_update);
            $data_edit_value->common_dynamic_values_update = json_encode($common_dynamic_values_update);
            $data_edit_value->save();
            \DB::commit();

//            dd($data_edit_value, json_decode($data->common_dynamic_values_view), $data->common_dynamic_values_view, $data->common_everything_view);
            $comment = 'Updated Job Role '.$job_role->name.' tab '.$job_role->getTabRoleText($data->role_type ?? '').' Successfully!';
            //log audit
            \CommonHelpers::logAudit(ROLE, $job_role->id, AUDIT_ACTION_EDIT, $job_role->name ?? '',0,$comment);
            return \CommonHelpers::successResponse('Updated Job Role Successfully!', $job_role);
        } catch (\Exception $e){
            \DB::rollback();
            \Log::error($e);
            dd($e);
            return \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to updating Job Role! Please try again');
        }
    }

    public function getDropdownValue($type, $parent_id = 0) {
        return WorkData::where('type', $type)->where('parent_id', $parent_id)->get();
    }

    public function getWorkFlow(){
        return WorkFlow::orderBy('description')->get();
    }

//    public function showClient($request, $limit = 500, $offset = 0){
//        //sorting
//        $order_by = "c.`name` ASC";
//        if(isset($request->order[0]['column']) && isset($request->order[0]['dir'])){
//            $index = $request->order[0]['column'];
//            $column = $request->columns[$index]['data'] ?? '';
//            $dir = $request->order[0]['dir'];
//            if($column && $dir){
//                $order_by = " p.".$column. " $dir";
//            }
//        }
//        $search = "";
//        if(isset($request->search['value']) && !empty($request->search['value'])){
//            $text_search = $request->search['value'];
//            $search = " AND ( p.`name` LIKE '%$text_search%' OR p.`reference` LIKE '%$text_search%' OR p.`property_reference` LIKE '%$text_search%')";
//        }
//        return $this->clientRepository->showClient($limit, $offset, $order_by, $search);
//    }

    public function showClient($job_role, $request, $limit = 500, $offset = 0, $order_by = "tbl_clients.name",  $search = ""){
        //sorting
//        if($request->tab == JOB_ROLE_VIEW){
//            $job_role->is_add_group_arr = $job_role->getGeneralValueViewByType($request->type, 'is_add_group');
//            $job_role->is_all_group_arr = $job_role->getGeneralValueViewByType($request->type, 'is_all_group');
//        } else {
//            $job_role->is_add_group_arr = [];
//            $job_role->is_all_group_arr = $job_role->getGeneralValueEditByType($request->type, 'is_all_group');
//        }
        if(isset($request->order[0]['column']) && isset($request->order[0]['dir'])){
            $index = $request->order[0]['column'];
            $column = $request->columns[$index]['data'] ?? '';
            $dir = $request->order[0]['dir'];
//            if($column == 'is_add_group'){
//                $is_add_group_arr = implode($job_role->getGeneralValueViewByType($request->tab, 'is_add_group'));
//                if($is_add_group_arr){
//                    $order_by =  "IF(FIELD(tbl_clients.id,$is_add_group_arr)=0,1,0),FIELD(tbl_clientsid,$is_add_group_arr)";
//                }
//            } else if($column == 'is_all_group'){
//                $is_all_group_arr = implode($job_role->getGeneralValueViewByType($request->tab, 'is_all_group'));
//                if($is_all_group_arr){
//                    $order_by =  "IF(FIELD(tbl_clientsid,$is_all_group_arr)=0,1,0),FIELD(tbl_clientsid,$is_all_group_arr)";
//                }
//            } else
            if($column && $dir){
                $order_by = " tbl_clients.".$column. " $dir";
            }
        }
        if(isset($request->search['value']) && !empty($request->search['value'])){
            $text_search = $request->search['value'];
            $search = " ( tbl_clients.`name` LIKE '%$text_search%' OR tbl_clients.`reference` LIKE '%$text_search%' )";
        }
        return $this->clientRepository->showClient($job_role, $request, $limit, $offset, $order_by, $search);
    }

    public function showOrganisation($job_role, $request, $limit = 500, $offset = 0, $order_by = "tbl_clients.name",  $search = ""){
        if(isset($request->order[0]['column']) && isset($request->order[0]['dir'])){
            $index = $request->order[0]['column'];
            $column = $request->columns[$index]['data'] ?? '';
            $dir = $request->order[0]['dir'];
            if($column && $dir){
                $order_by = " tbl_clients.".$column. " $dir";
            }
        }
        if(isset($request->search['value']) && !empty($request->search['value'])){
            $text_search = $request->search['value'];
            $search = " ( tbl_clients.`name` LIKE '%$text_search%' OR tbl_clients.`reference` LIKE '%$text_search%' )";
        }

//        $privilege_common = $this->getPrivilegeCommon(JOB_ROLE_GENERAL);
//        $organisation_type = $privilege_common[JR_ORGANISATION_INFORMATION_TYPE] ?? [];
        $organisation_type = [];
        return $this->clientRepository->showOrganisation($job_role, $organisation_type, $request, $limit, $offset, $order_by, $search);
    }


    public function selectAllOrganisations($job_role, $request){
        $is_selected = $request->is_selected;
        try {
            DB::beginTransaction();
            //select all property
            $text = 'Deselecting';
            $organisation_listing = $client_ids = [];
            if($is_selected){
                $clients = $this->clientRepository->all();
                $arr_values = $this->getArrayValuesPrivilegeCommon(JR_ORGANISATION_INFORMATION_TYPE);
                $text = 'Selecting';
                foreach ($clients as $cl){
                    $client_ids[] = $cl->id;
                    $organisation_listing[$cl->id] = $arr_values;
                }
            }
            $data_view_value = $job_role->jobRoleViewValue;
            $data_edit_value = $job_role->jobRoleEditValue;
            if($request->tab == VIEW_TAB){
                //save organisation
                $data_view_value->organisation_listing = count($organisation_listing) ? json_encode($organisation_listing) : NULL;
                $data_view_value->save();
            } else {
                //save organisation
                $data_edit_value->organisation_listing = count($organisation_listing) ? json_encode($organisation_listing) : NULL;
                $data_edit_value->save();
            }
            DB::commit();
            return \CommonHelpers::successResponse("$text All Organisations Successfully!", $job_role);
        } catch (\Exception $e){
            DB::rollBack();
            dd($e);
            return \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,"Failed to $text All Organisations! Please try again");
        }
    }

    public function showListClientGroup($client_id, $relations = []){
        return $this->clientRepository->getAllClientGroupsWithRelations($client_id, $relations);
    }

    public function selectAllClients($job_role, $request){
        // select/deselect all client/group/properties/add group permission
//        ini_set('memory_limit','5120M');
        $is_selected = $request->is_selected;
        $tab = $request->tab;
        try {
            DB::beginTransaction();
            //select all property
            $text = 'Deselecting';
            $client_listing = $client_ids = $insert_data = [];
            if($is_selected){
                $properties = $this->propertyRepository->all()->pluck('id')->toArray();
                $clients = $this->clientRepository->with('zonePrivilege')->all();
                $text = 'Selecting';
                foreach ($clients as $cl){
                    $client_ids[] = $cl->id;
                    $client_listing[$cl->id] = $cl->zonePrivilege->pluck('id')->toArray() ?? [];
                }
                foreach ($properties as $pid){
                    $insert_data[] = [
                        'role_id' => $job_role->id,
                        'property_id' => $pid
                    ];
                }
            }
            $data_view_value = $job_role->jobRoleViewValue;
            $data_edit_value = $job_role->jobRoleEditValue;
            $insert_data = collect($insert_data);
            $chunks = $insert_data->chunk(500);
            if($request->tab == VIEW_TAB){
                DB::table('cp_job_role_view_property')->where('role_id', $job_role->id)->delete();
                foreach ($chunks as $chunk){
                    \DB::table('cp_job_role_view_property')->insert($chunk->toArray());
                }
                //save clients/groups
                $data_view_value->client_listing = count($client_listing) ? json_encode($client_listing) : NULL;
//                $data_view_value->add_group_permission = count($client_ids) ? implode(",", $client_ids) : NULL;
                $data_view_value->save();
            } else {
                DB::table('cp_job_role_edit_property')->where('role_id', $job_role->id)->delete();
                foreach ($chunks as $chunk){
                    \DB::table('cp_job_role_edit_property')->insert($chunk->toArray());
                }
                //save clients/groups
                $data_edit_value->client_listing = count($client_listing) ? json_encode($client_listing) : NULL;
                $data_edit_value->add_group_permission = count($client_ids) ? implode(",", $client_ids) : NULL;
                $data_edit_value->save();
            }
            DB::commit();
            return \CommonHelpers::successResponse("$text All Clients Successfully!", $job_role);
        } catch (\Exception $e){
            DB::rollBack();
            dd($e);
            return \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,"Failed to $text All Clients! Please try again");
        }
    }


    public function saveClientGroup($job_role, $request){
        try {
            DB::beginTransaction();
            //select all property
            $client_id = $request->client_id;
            $is_select = $request->client_id_check;
            $data_view_value = $job_role->jobRoleViewValue;
            $data_edit_value = $job_role->jobRoleEditValue;
            if($request->tab == VIEW_TAB){
                //save clients/groups
                $old_client_listing = $data_view_value->client_listing ? json_decode($data_view_value->client_listing, true) : [];
                if($is_select){
                    $old_client_listing[$client_id] = array_map('intval', $request->group_ids ?? []);
                } else {
                    unset($old_client_listing[$client_id]);
                }
                $data_view_value->client_listing = count($old_client_listing) ? json_encode($old_client_listing) : NULL;
                $data_view_value->save();
            } else {
                //save clients/groups
                $old_client_listing = $data_edit_value->client_listing ? json_decode($data_edit_value->client_listing, true) : [];
                if($is_select){
                    $old_client_listing[$client_id] = array_map('intval', $request->group_ids ?? []);
                } else {
                    unset($old_client_listing[$client_id]);
                }

                $old_add_group_permission = $data_edit_value->add_group_permission ? explode(",", $data_edit_value->add_group_permission) : [];
                if($request->add_group_permission){
                    $old_add_group_permission[] = $client_id;
                    $old_add_group_permission = array_unique(array_filter($old_add_group_permission));
                } else {
                    if (($key = array_search( $client_id, $old_add_group_permission)) !== false) {
                        unset($old_add_group_permission[$key]);
                    }
                }
                $data_edit_value->client_listing = count($old_client_listing) ? json_encode($old_client_listing) : NULL;
                $data_edit_value->add_group_permission = count($old_add_group_permission) ? implode(",", $old_add_group_permission) : NULL;
                $data_edit_value->save();
            }
            DB::commit();
            return \CommonHelpers::successResponse("Saving Successfully!", $job_role);
        } catch (\Exception $e){
            DB::rollBack();
            dd($e);
            return \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,"Failed to Saving! Please try again");
        }
    }


    public function saveOrganisationListing($job_role, $request){
        try {
            DB::beginTransaction();
            //select all property
            if($request->tab == VIEW_TAB){
                $data_view_value = $job_role->jobRoleViewValue;
                $old_organisation_listing = $data_view_value->organisation_listing ? json_decode($data_view_value->organisation_listing, true) : [];
                if($request->data && count($request->data)){
                    foreach ($request->data as $client_id => $value){
                        $old_organisation_listing[$client_id] = array_map('intval', $value ?? []);
                    }
                }
                if($request->uncheck && count($request->uncheck)){
                    foreach ($request->uncheck as $client_id){
                        if(array_key_exists($client_id, $old_organisation_listing)){
                            unset($old_organisation_listing[$client_id]);
                        }
                    }
                }
                $data_view_value->organisation_listing = count($old_organisation_listing) ? json_encode($old_organisation_listing) : NULL;
                $data_view_value->save();
            } else {
                $data_edit_value = $job_role->jobRoleEditValue;
                $old_organisation_listing = $data_edit_value->organisation_listing ? json_decode($data_edit_value->organisation_listing, true) : [];
                if($request->data && count($request->data)){
                    foreach ($request->data as $client_id => $value){
                        $old_organisation_listing[$client_id] = array_map('intval', $value ?? []);
                    }
                }
                if($request->uncheck && count($request->uncheck)){
                    foreach ($request->uncheck as $client_id){
                        if(array_key_exists($client_id, $old_organisation_listing)){
                            unset($old_organisation_listing[$client_id]);
                        }
                    }
                }
                $data_edit_value->organisation_listing = count($old_organisation_listing) ? json_encode($old_organisation_listing) : NULL;
                $data_edit_value->save();
            }
            DB::commit();
            return \CommonHelpers::successResponse("Saving Successfully!", $job_role);
        } catch (\Exception $e){
            DB::rollBack();
            dd($e);
            return \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,"Failed to Saving! Please try again");
        }
    }

    public function getTemplateDocument(){
        return TemplateDocument::with('template')->get();
    }
}

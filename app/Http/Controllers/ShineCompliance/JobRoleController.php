<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Helpers\ComplianceHelpers;
use App\Http\Controllers\Controller;
use App\Models\ShineCompliance\SummaryType;
use App\Models\TemplateDocument;
use App\Repositories\ShineCompliance\PropertyRepository;
use App\Services\ShineCompliance\ClientService;
use App\Services\ShineCompliance\JobRoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobRoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $route;

    public function __construct(JobRoleService $jobRoleService, ClientService $clientService, PropertyRepository $propertyRepository)
    {
        $this->jobRoleService = $jobRoleService;
        $this->clientService = $clientService;
        $this->propertyRepository = $propertyRepository;
    }

    public function editJobRole(){
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        $job_roles = $this->jobRoleService->getListJobRoles();
        return view('shineCompliance.resources.job_role.list_job_role',['job_roles' => $job_roles]);
    }

    public function updateOrCreateRole(Request $request){
        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:255|unique:cp_job_roles',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $dataRole = [
            'name' => $request->name ?? ''
        ];
        if($request->id == 0) {
            $dataRole['created_by'] = \Auth::user()->id;
        }

        $response =  $this->jobRoleService->updateOrCreateRole($dataRole, $request->id);
        if (isset($response) and !is_null($response) && $response['status_code'] == 200) {
            \Session::flash('msg', $response['msg']);
        }
        return $response;
    }
    //get detail job role General | Asbestos | Fire | Gas | M&E | RCF | Water
    public function getJobRole($id, Request $request) {
        $type = $request->type ?? JOB_ROLE_GENERAL;

        if (!\CommonHelpers::isSystemClient() || !in_array($request->type, [JOB_ROLE_GENERAL, JOB_ROLE_ASBESTOS, JOB_ROLE_FIRE, JOB_ROLE_GAS, JOB_ROLE_ME, JOB_ROLE_RCF, JOB_ROLE_WATER, JOB_ROLE_H_S])) {
            abort(404);
        }
        $job_role = $this->jobRoleService->getDetail($id, $request, ['jobRoleViewValue','jobRoleEditValue']);
        $privilege_view = $this->jobRoleService->getPrivilegeView($type, ['allChildren']);
        $privilege_update = $this->jobRoleService->getPrivilegeUpdate($type, ['allChildren']);
        $privilege_common = $this->jobRoleService->getPrivilegeCommon($type);
        $organisation = $privilege_common[JR_ORGANISATION_INFORMATION_TYPE] ?? [];
        $clients_data = $this->clientService->getAllClientsWithRelations('zonePrivilege');
        $clients_list = $clients_data['clients_list'];
        $clients = $clients_data['clients'];//co1
        $clients_contractor = $clients_data['clients_contractor'];//contractor
        $clients_other = $clients_data['clients_other'];//other_client
        $client_properties = $clients_data['client_properties'];//client with properties
//        dd(collect($job_role->propertyView)->groupBy('zone_id')->toArray(), collect($job_role->propertyView)->sortBy('zone_id')->pluck('zone_id')->unique()->toArray());
//        $job_role->list_checked_zone_view = collect($job_role->propertyView)->sortBy('zone_id')->pluck('zone_id')->unique()->toArray();
//        $job_role->list_checked_zone_edit = collect($job_role->propertyEdit)->sortBy('zone_id')->pluck('zone_id')->unique()->toArray();
//        $job_role->list_count_property_view = collect($job_role->propertyView)->groupBy('zone_id')->toArray();
//        $job_role->list_count_property_edit = collect($job_role->propertyEdit)->groupBy('zone_id')->toArray();
        $work_request_types = $this->jobRoleService->getDropdownValue('workType');
        $work_flow = $this->jobRoleService->getWorkFlow();
        $template_document = $this->jobRoleService->getTemplateDocument();
        $summary_data = SummaryType::all();
//        dd($clients_list, $clients, $clients_contractor, $clients_other, $client_properties);
        if(!$job_role){
            return abort(404);
        }

        return view('shineCompliance.resources.job_role.details',
            compact('job_role', 'type','privilege_view','privilege_update','privilege_common','clients_list','clients','clients_contractor','clients_other','client_properties','organisation',
            'work_request_types','work_flow','summary_data','template_document'));
    }

    public function postEditJobRole($id, Request $request){
        if (!\CommonHelpers::isSystemClient() || !in_array($request->role_type, [JOB_ROLE_GENERAL, JOB_ROLE_ASBESTOS, JOB_ROLE_FIRE, JOB_ROLE_GAS, JOB_ROLE_ME, JOB_ROLE_RCF, JOB_ROLE_WATER, JOB_ROLE_H_S])) {
            $response = \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to updating Job Role! Please try again');
            \Session::flash('msg', $response['msg']);
            return $response;
        }
        $job_role = $this->jobRoleService->getDetail($id, $request, ['jobRoleViewValue','jobRoleEditValue']);
        if(!$job_role){
            $response = \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'The Job Role not found');
            \Session::flash('msg', $response['msg']);
            return $response;
        }
        $response =  $this->jobRoleService->updateOrCreateRoleValue($request, $job_role);
//        if (isset($response) and !is_null($response) && $response['status_code'] == 200) {
            \Session::flash('msg', $response['msg']);
//        }
        return $response;
    }

    public function ajaxShowListProperty(Request $request){
        //role_id
        $properties = $this->propertyRepository->where('zone_id', $request->group_id)->orderBy('name')->get();
        $role = NULL;
        $role_properties = [];
        $data = [];
        $job_role = $this->jobRoleService->getDetail($request->role_id, $request, ['propertyView','propertyEdit']);
        if($request->role_id && $request->tab){
            if($request->tab == VIEW_TAB){
                //get data properties view privilege
                $role_properties = $job_role->propertyView;
            } else {
                //get data properties update privilege
                $role_properties = $job_role->propertyEdit;
            }
            if($job_role && !$role_properties->isEmpty()){
                $role_properties = $role_properties->pluck('id')->toArray() ?? [];
            }

            if(!$properties->isEmpty()){
                foreach ($properties as $k => $p){
                    $data[$k][] = $p->id;
                    $data[$k][] = $p->name;
                    $data[$k][] = count($role_properties) ? in_array($p->id, $role_properties) : false;
                }
            }
        }
        //need to check is there any property selected in this role
        return ['data'=>$data];
    }

    public function ajaxSaveListProperty(Request $request){
        //need to send tick and untick in checkboxes
        $properties = $this->propertyRepository->where('zone_id', $request->group_id)->pluck('id')->toArray();
        $post_properties = isset($request->list_property) ? explode(",", $request->list_property) : [];
        $job_role = $this->jobRoleService->getDetail($request->role_id, $request, ['propertyView','propertyEdit']);
        $role = NULL;
        if($request->tab == VIEW_TAB){
            //get data properties view privilege
            $role = $job_role->propertyView;
        } else {
            //get data properties update privilege
            $role = $job_role->propertyEdit;
        }
        if($role){
            try {
                $role_properties = $role->pluck('id')->toArray() ?? [];
                //get list remove properties to remove, compare post list vs list properties in group
                $arr_properties_remove = array_diff($properties ,$post_properties);
    //            $job_role->propertyView()->detach($arr_properties_remove);
    //            $job_role->propertyView()->sync($arr_properties_remove);

                if(count($role_properties) && count($arr_properties_remove)){
                    // Search for the array key and unset
                    foreach($arr_properties_remove as $key){
                        $keyToDelete = array_search($key, $role_properties);
                        unset($role_properties[$keyToDelete]);
                    }
                }
                $list_id =  array_filter(array_unique( array_merge($role_properties, $post_properties)));
                $insert_data = [];
                foreach ($list_id as $pid){
                    $insert_data[] = [
                        'role_id' => $job_role->id,
                        'property_id' => $pid
                    ];
                }
                $insert_data = collect($insert_data);
                $chunks = $insert_data->chunk(500);
//                dd($list_id);
                if($request->tab == VIEW_TAB){
//                    $start = microtime(true);
                    //get data properties view privilege
//                    $job_role->propertyView()->sync($list_id);
                    DB::table('cp_job_role_view_property')->where('role_id', $job_role->id)->delete();
                    foreach ($chunks as $chunk){
                        \DB::table('cp_job_role_view_property')->insert($chunk->toArray());
                    }
//                    $time = microtime(true) - $start;
                } else {
                    //get data properties update privilege
                    DB::table('cp_job_role_edit_property')->where('role_id', $job_role->id)->delete();
                    foreach ($chunks as $chunk){
                        \DB::table('cp_job_role_edit_property')->insert($chunk->toArray());
                    }
                }
            } catch (\Exception $e){
                dd($e->getMessage());
            }
        }
        return ['status'=>200, 'group_id' => $request->group_id, 'count_property' => count($properties) - count($arr_properties_remove), 'total_property' => count($properties)];
        //merge new properties and unique array to avoid duplicate properties
    }

    public function ajaxPostShowListClient(Request $request){
        $job_role = $this->jobRoleService->getDetail($request->id, $request, ['jobRoleViewValue','jobRoleEditValue']);
        return $this->jobRoleService->showClient($job_role, $request,$request->length ?? 0,$request->start ?? 0);
//        return ComplianceHelpers::responseDataTable($request->draw ?? 0, $return[1], $return[1], $return[0]);
    }

    // show tree client -> group
    public function ajaxShowListClientGroup(Request $request){
        $client_group = $this->jobRoleService->showListClientGroup($request->client_id, ['zonePrivilege.allChildrens']);
        $zone_ids = [];
        foreach ($client_group->zonePrivilege as $zone_parents){
            $ids = $zone_parents->allChildrens->pluck('id')->toArray();
            $zone_ids = array_merge($zone_ids, $ids);
        }
//        $zone_ids = $client_group->zonePrivilege->pluck('id')->toArray();
        $job_role = $this->jobRoleService->getDetailByClient($request->id, $request, $request->client_id, $zone_ids);
        if($request->tab == JOB_ROLE_VIEW){
            $job_role->list_count_property_view = collect($job_role->propertyView)->groupBy('zone_id')->map->count()->toArray();
            $checked_client_array =  isset($job_role->jobRoleViewValue->client_listing) ? json_decode($job_role->jobRoleViewValue->client_listing, true) : [] ;
        } else {
            $job_role->list_count_property_edit = collect($job_role->propertyEdit)->groupBy('zone_id')->map->count()->toArray();
            $checked_client_array =  isset($job_role->jobRoleEditValue->client_listing) ? json_decode($job_role->jobRoleEditValue->client_listing, true) : [] ;
        }
        return view('shineCompliance.resources.job_role.partial_load_client_group',
            ['type' => $request->tab,
            'prefix' => $request->tab == JOB_ROLE_VIEW ? 'client-property-view' : 'client-property-update',
            'level' => 1,
            'checked_client_array' => $checked_client_array,
            'job_role' => $job_role,
            'client_group' => $client_group])->render();
//        return ComplianceHelpers::responseDataTable($request->draw ?? 0, $return[1], $return[1], $return[0]);
    }
    // save tree client -> group
    public function ajaxPostListClientGroup(Request $request){
        $client_id = $request->client_id;
        $client_group = $this->jobRoleService->showListClientGroup($client_id, ['zonePrivilege']);
        $zone_ids = [];
        foreach ($client_group->zonePrivilege as $zone_parents){
            $ids = $zone_parents->allChildrens->pluck('id')->toArray();
            $zone_ids = array_merge($zone_ids, $ids);
        }
        $job_role = $this->jobRoleService->getDetailByClient($request->id, $request, $client_id, $zone_ids);
        $response = $this->jobRoleService->saveClientGroup($job_role, $request);
        if($request->tab == JOB_ROLE_VIEW){
            //jobRoleViewValue', 'jobRoleEditValue
            $job_role->list_count_property_view = collect($job_role->propertyView)->groupBy('zone_id')->map->count()->toArray();
            $checked_client_array =  isset($job_role->jobRoleViewValue->client_listing) ? json_decode($job_role->jobRoleViewValue->client_listing, true) : [] ;
        } else {
            $job_role->list_count_property_edit = collect($job_role->propertyEdit)->groupBy('zone_id')->map->count()->toArray();
            $checked_client_array =  isset($job_role->jobRoleEditValue->client_listing) ? json_decode($job_role->jobRoleEditValue->client_listing, true) : [] ;
        }
        return view('shineCompliance.resources.job_role.partial_load_client_group',
            ['type' => $request->tab,
                'prefix' => $request->tab == JOB_ROLE_VIEW ? 'client-property-view' : 'client-property-update',
                'level' => 1,
                'checked_client_array' => $checked_client_array,
                'job_role' => $job_role,
                'client_group' => $client_group])->render();
//        return ComplianceHelpers::responseDataTable($request->draw ?? 0, $return[1], $return[1], $return[0]);
    }

    // select/deselect all client/group/properties/add group permission
    public function ajaxCheckAllClient(Request $request){
        $job_role = $this->jobRoleService->getDetail($request->id, $request, ['jobRoleViewValue','jobRoleEditValue']);
        $response = $this->jobRoleService->selectAllClients($job_role, $request);
        \Session::reflash('msg', $response['msg']);
        return $response;
    }

    public function ajaxPostShowListOrganisation(Request $request){
        $job_role = $this->jobRoleService->getDetail($request->id, $request, ['jobRoleViewValue','jobRoleEditValue']);
        return $this->jobRoleService->showOrganisation($job_role, $request,$request->length ?? 0,$request->start ?? 0);
//        return ComplianceHelpers::responseDataTable($request->draw ?? 0, $return[1], $return[1], $return[0]);
    }

    // select/deselect all organisation listing
    public function ajaxCheckAllOrganisation(Request $request){
        $job_role = $this->jobRoleService->getDetail($request->id, $request, ['jobRoleViewValue','jobRoleEditValue']);
        $response = $this->jobRoleService->selectAllOrganisations($job_role, $request);
        \Session::reflash('msg', $response['msg']);
        return $response;
    }
    // save tree client -> group
    public function ajaxPostListOrganisation(Request $request){
        $job_role = $this->jobRoleService->getDetail($request->id, $request, ['jobRoleViewValue','jobRoleEditValue']);
        $response = $this->jobRoleService->saveOrganisationListing($job_role, $request);
        \Session::reflash('msg', $response['msg']);
        return $response;
    }
}

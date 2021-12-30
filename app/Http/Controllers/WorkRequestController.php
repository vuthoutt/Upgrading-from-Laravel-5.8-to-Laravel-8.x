<?php

namespace App\Http\Controllers;

use App\Models\DurationNumberTest;
use App\Models\Property;
use App\Models\WorkRequest;
use App\Models\DecommissionReason;
use App\Models\Zone;
use App\Models\Client;
use App\Repositories\ProjectRepository;
use App\Repositories\WorkRequestRepository;
use App\Repositories\UserRepository;
use App\Repositories\ClientRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Request\WorkRequest\WorkCreateRequest;


class WorkRequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WorkRequestRepository $workRequestRepository, UserRepository $userRepository, ClientRepository $clientRepository, ProjectRepository $projectRepository)
    {

        $this->workRequestRepository = $workRequestRepository;
        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   public function getAdd(){

        $asbestos_leads = $this->userRepository->getListAsbestosLeadWorkRequest();
        $work_request_types = $this->workRequestRepository->getDropdownValue('workType');
        $compliance_types = $this->workRequestRepository->getComplianceTypes();
        $duration_number_test = DurationNumberTest::all();
        $priorities = $this->workRequestRepository->getDropdownByParent(WR_PRIORITY_DROPDOWN);
        $parkings = $this->workRequestRepository->getDropdownByParent(WR_PARKING_ARRANGE_DROPDOWN);
        $ceilling_heights = $this->workRequestRepository->getDropdownByParent(WR_CEILLING_HEIGHT_DROPDOWN);
        $list_users = $this->clientRepository->getClientUsers(1);
        $contractors = $this->clientRepository->getContractors();

        return view('work_request.add_work_request',[
            'asbestos_leads' => $asbestos_leads,
            'work_request_types' => $work_request_types,
            'compliance_types' => $compliance_types,
            'priorities' => $priorities,
            'ceilling_heights' => $ceilling_heights,
            'parkings' => $parkings,
            'contractors' => $contractors,
            'duration_number_test' => $duration_number_test
        ]);
   }

    public function getDetail($id)
    {
        $workRequest = WorkRequest::with('workSupportingDocument')->find($id);
        $property_ids = explode(",",$workRequest->property_id_major);
        $properties = Property::whereIn('id',$property_ids)->get();
        if (is_null($workRequest)) {
            abort(404);
        }
        //check privilege
        // if (!\CommonHelpers::isSystemClient()) {
        //     abort(404);
        // }
        if ($workRequest->status == WORK_REQUEST_AWAITING_APPROVAL or $workRequest->status == WORK_REQUEST_COMPLETE) {
            $is_locked = true;
        } else {
            $is_locked = false;
        }

        $decommissioned_reason = DecommissionReason::where('type', 'work_request')->where('parent_id', 1)->get();

        // GET PROPERTY CONTACTS
        $property_contact = [];
        $arr_user_ids = $workRequest->team ?? [];
        if(count($arr_user_ids)){
            $property_contact = User::select('id', 'first_name', 'last_name')->whereIn('id',$arr_user_ids)->get()->toArray();
        }
        if ($workRequest->non_user == 1) {
            if ($workRequest->workContact) {
                $property_contact[] = $workRequest->workContact->toArray();
            }
        }

        return view('work_request.detail',[
            'workRequest' => $workRequest,
            'decommissioned_reason' => $decommissioned_reason,
            'property_contact' => $property_contact,
            'is_locked' => $is_locked,
            'properties' => $properties,
        ]);
    }

    public function decommission($id) {
        $reason = \Request::input('decommission_reason') ?? 0;
        $decommission = $this->workRequestRepository->decommission($id,$reason);
        if (isset($decommission)) {
            if ($decommission['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommission['msg']);
            } else {
                return redirect()->back()->with('err', $decommission['msg']);
            }
        }
    }
    /**
     * Create new work
     * @param WorkCreateRequest $workCreateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
   public function postAdd(WorkCreateRequest $workCreateRequest) {
        $validatedData = $workCreateRequest->validated();
        $createWork = $this->workRequestRepository->updateOrCreateWork($validatedData);

        if (isset($createWork) and !is_null($createWork)) {
            if ($createWork['status_code'] == 200) {
                return redirect()->route('wr.details', ['id' => $createWork['data']])->with('msg', $createWork['msg']);
            } else {
                return redirect()->back()->with('err', $createWork['msg']);
            }
        }
   }

    public function getEdit($id){
        $work_request = WorkRequest::find($id);
        if (is_null($work_request)) {
            abort(404);
        }
        $property = Property::find($work_request->property_id ?? 0);

        $asbestos_leads = $this->userRepository->getListAsbestosLeadWorkRequest();
        $compliance_types = $this->workRequestRepository->getComplianceTypes();
        $work_request_types = $this->workRequestRepository->getDropdownValue('workType');
        $duration_number_test = DurationNumberTest::all();
        $priorities = $this->workRequestRepository->getDropdownByParent(WR_PRIORITY_DROPDOWN);
        $parkings = $this->workRequestRepository->getDropdownByParent(WR_PARKING_ARRANGE_DROPDOWN);
        $ceilling_heights = $this->workRequestRepository->getDropdownByParent(WR_CEILLING_HEIGHT_DROPDOWN);
        $list_users = $this->clientRepository->getClientUsers(1);
        $list_users_wk = $work_request->team;
        $zone_ids = Zone::where('zone_name','NOT LIKE','%Domestic%')->where('zone_name','NOT LIKE','%Communal%')->pluck('id')->toArray();
        $zone_ids = count($zone_ids) > 0 ? implode(",", $zone_ids) : '';
        $contractors = $this->clientRepository->getContractors();

        if($work_request->non_user){
            $list_users_wk[] = 'nonuser';
        }

        return view('work_request.edit_work_request',[
            'asbestos_leads' => $asbestos_leads,
            'work_request_types' => $work_request_types,
            'compliance_types' => $compliance_types,
            'duration_number_test' => $duration_number_test,
            'priorities' => $priorities,
            'contractors' => $contractors,
            'ceilling_heights' => $ceilling_heights,
            'parkings' => $parkings,
            'work_request' => $work_request,
            'property' => $property,
            'list_users' => $list_users,
            'list_users_wk' => $list_users_wk,
            'domestic_property_ids' => $zone_ids
        ]);
   }

    public function postEdit(WorkCreateRequest $workCreateRequest) {
        $validatedData = $workCreateRequest->validated();
        $updateWork = $this->workRequestRepository->updateOrCreateWork($validatedData,$validatedData['id']);

        if (isset($updateWork) and !is_null($updateWork)) {
            if ($updateWork['status_code'] == 200) {
                return redirect()->route('wr.details', ['id' => $updateWork['data']])->with('msg', $updateWork['msg']);
            } else {
                return redirect()->back()->with('err', $updateWork['msg']);
            }
        }
   }
    /**
     * Show all work request on the dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getList(Request $request){
        $type = $request->type ?? WORK_REQUEST_ASBESTOS_TYPE;
        // TODO CHECK PERM
//        if(!\CompliancePrivilege::checkPermission(CRITICAL_VIEW_PRIV) and \CommonHelpers::isSystemClient()){
//            abort(404);
//        }
        $asbestos = true;
        $fire = true;
        $water = true;
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_ASBESTOS, JOB_ROLE_ASBESTOS)){
            $asbestos = false;
        }
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_FIRE, JOB_ROLE_FIRE)){
            $fire = false;
        }
        if((\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_WATER, JOB_ROLE_WATER)) || !env('WATER_MODULE') ) {
            $water = false;
        }
        return view('work_request.index',[
            'works_list_live' => $this->workRequestRepository->getListWorkLive($type),
            'works_list_completed' => $this->workRequestRepository->getListWorkCompleted($type),
            'works_list_decommissioned' => $this->workRequestRepository->getListWorkDecommissioned($type),
            'asbestos' => $asbestos,
            'fire' => $fire,
            'water' => $water,
            'type' => $type,
        ]);
    }

   public function getWorkDropdown(Request $request) {
        $parent_id = $request->parent_id ?? 0;

        $work_request_types = $this->workRequestRepository->getDropdownByParent($parent_id);
        return response()->json(['status_code' => 200, 'data' => $work_request_types]);
   }


    public function propertyContactWr(Request $request) {
        $validator = \Validator::make($request->all(), [
            'selected' => 'sometimes'
        ]);

        $list_users = $this->clientRepository->getClientUsers(1);
        $this_site = Property::find($request->property_id);
        $list_users_wk = $this_site->propertyInfo->team ?? [];

        return  view('forms.form_property_contact_wr', [
            'list_users'=> $list_users,
            'list_users_wk' => $list_users_wk,
            'edit' =>  true
        ])->render();
    }

    public function approvalWorkRequest($work_request_id)
    {
        $approvalWorkRequest = $this->workRequestRepository->approvalWorkRequest($work_request_id, $this->projectRepository);

        if(\Request::ajax()) {
            \Session::flash('msg', $approvalWorkRequest['msg'] ?? '');
            return response()->json(['status_code' => 200, 'success'=> $approvalWorkRequest['msg'] ?? '' ]);
        }

        if (isset($approvalWorkRequest) and !is_null($approvalWorkRequest)) {
            if ($approvalWorkRequest['status_code'] == 200) {
                return redirect()->back()->with('msg', $approvalWorkRequest['msg']);
            } else {
                return redirect()->back()->with('err', $approvalWorkRequest['msg']);
            }
        }
    }

    public function rejectWorkRequest(Request $request)
    {

        $rejectWorkRequest = $this->workRequestRepository->rejectWorkRequest($request->all());

        if (isset($rejectWorkRequest) and !is_null($rejectWorkRequest)) {
            if ($rejectWorkRequest['status_code'] == 200) {
                return redirect()->back()->with('msg', $rejectWorkRequest['msg']);
            } else {
                return redirect()->back()->with('err', $rejectWorkRequest['msg']);
            }
        }
    }

    public function updateOrCreateDocument(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'work_id' => 'required',
            'name' => 'required|max:255',
            'document' => 'required_without:id|file',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->has('id')) {
            $document = $this->workRequestRepository->updateOrCreateDocument($request->all(), $request->id);
        } else {
            $document = $this->workRequestRepository->updateOrCreateDocument($request->all());
        }

        if (isset($document) and !is_null($document)) {
            \Session::flash('msg', $document['msg']);
            return response()->json(['status_code' => $document['status_code'], 'success'=> $document['msg'], 'id' => $document['data']]);
        }
    }

    public function checkSorLogic(Request $request) {
        $validator = \Validator::make($request->all(), [
            'work_type_id' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        // todo Shine is still trying to create a job if Repair Responsibility is False

        $zone_ids = Zone::where('zone_name','NOT LIKE','%Domestic%')->where('zone_name','NOT LIKE','%Communal%')->pluck('id')->toArray();
        $is_validate = false;
        $property = Property::find($request->property_id);
        if($property && in_array($property->zone_id, $zone_ids)){
            $is_validate = true;
        }
//        $work_request = $this->workRequestRepository->checkException($request->property_id);
//        if($work_request){
//            return response()->json(['status_code' => 303, 'message'=> "Property's Repair Responsibility is False, job cannot be created!"]);
//        }

        $sor = $this->workRequestRepository->checkSorLogic($request->work_type_id ,
                                                            $request->property_type_id ?? 0,
                                                            $request->rooms && is_numeric(trim($request->rooms)) ? trim($request->rooms) : 0,
                                                            $request->priority && is_numeric(trim($request->priority)) ? trim($request->priority) : 0,
                                                            $request->duration && is_numeric(trim($request->duration)) ? trim($request->duration) : 0,
                                                            $request->enclosure_size && is_numeric(trim($request->enclosure_size)) ? trim($request->enclosure_size) : 0,
                                                            $request->number_positive && is_numeric(trim($request->number_positive)) ? trim($request->number_positive) : 0,
                                                            $request->duration_number_test && is_numeric(trim($request->duration_number_test)) ? trim($request->duration_number_test) : 0);

        if (isset($sor) and !is_null($sor)) {
            return response()->json(['status_code' => $sor['status_code'], 'message'=> $sor['msg'],'data' => $sor['data'] ?? 0, 'is_validate' => $is_validate]);
        }
    }
    public function emailCC(Request $request) {
        if ($request->has('email')){
            $email = $request->email;
            if (!is_null($email)) {
                $email = explode(",", $email);
            } else {
                $email = [];
                $checked_contractors = [];
            }
        } else {
            $email = [];
            $checked_contractors = [];
        }

        $html = view('forms.form_multiple_input', [
            'title' => 'Email Notification CC',
            'id_select' => 'contractors',
            'name' => 'email_cc',
            'value_get' => 'name',
            'email'=> $email,
            'max_option' => 10 - 1 ,
        ])->render();
        return response()->json(['status_code' =>200, 'data'=> $html, 'total' => 10]);
    }
}

<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Http\Request\ShineCompliance\Survey\SampleUpdateRequest;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\AreaService;
use App\Services\ShineCompliance\SurveyService;
use App\Services\ShineCompliance\ClientService;
use App\Services\ShineCompliance\UserService;
use App\Services\ShineCompliance\LocationService;
use App\Services\ShineCompliance\ProjectService;
use App\Services\ShineCompliance\ItemService;
use Illuminate\Http\Request;
use App\Http\Request\ShineCompliance\Survey\SurveyCreateRequest;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    private $areaService;
    private $propertyService;
    private $surveyService;
    private $clientService;
    private $userService;
    private $projectService;
    private $itemService;
    private $locationService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AreaService $areaService,
        PropertyService $propertyService,
        SurveyService $surveyService,
        ClientService $clientService,
        UserService $userService,
        ProjectService $projectService,
        ItemService $itemService,
        LocationService $locationService
    )
    {
        $this->areaService = $areaService;
        $this->propertyService = $propertyService;
        $this->surveyService = $surveyService;
        $this->clientService = $clientService;
        $this->userService = $userService;
        $this->projectService = $projectService;
        $this->itemService = $itemService;
        $this->locationService = $locationService;
    }

    /**
     * Show my organisation by id.
     *
     */

    public function index($survey_id, Request $request)
    {
        $survey = $this->surveyService->getSurvey($survey_id);
        $property_id = $this->surveyService->getPropertyFromSurvey($survey_id);

        if (empty($survey)) {
            abort(404);
        }
        //work flow update sample logic
        $can_upload_sample = false;
        // Organisation* = London Borough of Hackney
        if ($survey->client_id == 1) {
            // External Laboratory = Yes
            if (($survey->surveySetting->external_laboratory ?? '') == 1) {
                // Linked Project = Linked to a Survey Only Project created by the Work Request Feature with the Workflow Dropdown = Tersus Group.
                if (isset($survey->project) and !is_null($survey->project)) {
                    $project_type =  $survey->project->project_type ?? 0;
                    $project_status = $survey->project->status ?? 0;
                    $work_request = $survey->project->workRequest ?? null;
                    $work_flow = $work_request->work_flow ?? 0;

                    if (($project_type == PROJECT_SURVEY_ONLY) and !is_null($work_request) and ($work_flow == WORK_FLOW_HACKNEY) and ($project_status != PROJECT_COMPLETE_STATUS)  and ($survey->status != COMPLETED_SURVEY_STATUS) and (\Auth::user()->client_id == 2)) {
                        $can_upload_sample = true;
                    }
                }
            }
        }

        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $property_id);
        $is_locked = ($survey->is_locked == 1) || $survey->status == 5 ? true : false;
        if (!\CommonHelpers::isSystemClient()) {
            if(\Auth::user()->client_id == $survey->client_id || \Auth::user()->id == $survey->surveyor_id || \Auth::user()->id == $survey->consultant_id || \Auth::user()->id == $survey->created_by) {
                $canBeUpdateSurvey = true;
            } elseif ($can_upload_sample) {
                $canBeUpdateSurvey = false;
            } else {
                abort(404);
            }

        } else {
            //check privilege
            // property permission and register tab permission
            if(!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $survey->property_id) || !\CompliancePrivilege::checkPermission(SURVEYS_PROP_VIEW_PRIV)){
                abort(404);
            }
            $canBeUpdateSurvey = \CompliancePrivilege::checkUpdatePermission(SURVEYS_PROP_UPDATE_PRIV) and \CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $survey->property_id);
        }

        $plans = $this->surveyService->getSurveyPlans($property_id, $survey_id);
        $surveyorsNotes = $this->surveyService->getSurveyorsNotes($property_id, $survey_id);

        if ($request->has('section')) {
            $section = $request->section;
        } else {
            $section = SECTION_DEFAULT;
        }

        $areaData = [];
        $locationData = [];
        $pagination = [];
        $pagination_type = TYPE_REPORT;
        $active_tab = $request->active_tab ?? '';
        switch ($section) {
            case SECTION_AREA_FLOORS_SUMMARY:
                $pagination_type = TYPE_AREA;
                $area_id = $request->area ?? '';
                $data = $this->areaService->getAreaSurveyDetail($area_id,$survey,$property_id,$request);

                $items = $data['items'];
                $areaData = $data['areaData'];
                $pagination = $data['pagination'];
                $dataTab = $data['dataTab'];
                $dataDecommisstionTab = $data['dataDecommisstionTab'];
                $dataDecommisstionItems = $data['dataDecommisstionItems'];
                $dataSummary = $data['dataSummary'];
                $breadcrumb_name = $data['breadcrumb_name'];
                $breadcrumb_data = $data['breadcrumb_data'];
                break;

            case SECTION_ROOM_LOCATION_SUMMARY:

                $pagination_type = TYPE_LOCATION;
                $location_id = $request->location;
                // only pagination for some case
                $data = $this->locationService->getLocationSurveyDetail($location_id,$survey,$property_id,$request);

                $items = $data['items'];
                $pagination = $data['pagination'];
                $locationData = $data['locationData'];
                $dataTab = $data['dataTab'];
                $dataDecommisstionTab = $data['dataDecommisstionTab'];
                $dataDecommisstionItems = $data['dataDecommisstionItems'];
                $dataSummary = $data['dataSummary'];
                $breadcrumb_name = $data['breadcrumb_name'];
                $breadcrumb_data = $data['breadcrumb_data'];
                break;
            default:
                //get all areas
                //get decommissioned (1) areas
                $dataTab = $this->areaService->getSurveyArea($survey_id);
                $dataDecommisstionTab= $this->areaService->getSurveyArea($survey_id, 1);

                $items = $this->itemService->getItemSurvey($property_id,$survey_id);
                $dataSummary = $this->itemService->getRegisterSurveySummary($items,'survey', $property_id, $survey_id);
                $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
                $breadcrumb_name = 'survey_compliance';
                $breadcrumb_data = $survey;
                //log audit
                \CommonHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_VIEW, $survey->reference, $survey->property_id ,null, 0 ,$survey->property_id);
                break;
        }

        if ($request->has('type')) {
            $type = $request->type;
        } else {
            $type = false;
        }

        switch ($type) {
            case TYPE_All_ACM_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', '!=', null)->where('state', '!=', ITEM_NOACM_STATE)->all();
                if ($section == SECTION_AREA_FLOORS_SUMMARY) {
                    $title = 'All Survey Area/Floor Item Assessments';
                } elseif($section == SECTION_ROOM_LOCATION_SUMMARY) {
                    $title = 'All Survey Room/Locations Item Assessments';
                } else {
                    $title = 'All Survey Item Assessments';
                }

                $table_id = 'survey-all-items';
                break;

            case TYPE_HIGH_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[10, 99])->all();
                $title = 'High Risk ACM Item Summary';
                $table_id = 'high-risk-items';
                break;

            case TYPE_MEDIUM_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[7, 9])->all();
                $title = 'Medium Risk ACM Item Summary';
                $table_id = 'medium-risk-items';
                break;

            case TYPE_LOW_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[5, 6])->all();
                $title = 'Low Risk ACM Item Summary';
                $table_id = 'low-risk-items';
                break;

            case TYPE_VERY_LOW_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[0, 4])->all();
                $title = 'Very Low Risk ACM Item Summary';
                $table_id = 'vlow-risk-items';
                break;

            case TYPE_NO_RISK_ITEM_SUMMARY:
                $items_summary_table = $items->where('state', ITEM_NOACM_STATE)->where('decommissioned', ITEM_UNDECOMMISSION)->where('survey_id', '!=', 0)->all();
                $title = 'No Risk (NoACM) Item Summary';
                $table_id = 'no-risk-items';
                break;

            case TYPE_INACCESS_ACM_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_INACCESSIBLE_STATE)->all();
                $title = 'Inaccessible ACM Item Summary';
                $table_id = 'inaccessible-acm-items';
                break;

            case TYPE_INACCESS_ROOM_SUMMARY:
                if ($section == SECTION_AREA_FLOORS_SUMMARY) {
                    $items_summary_table = $this->itemService->countInaccessibleRooms('surveyarea',$property_id, $survey_id,$area_id );
                } else {
                    $items_summary_table = $this->itemService->countInaccessibleRooms('survey',$property_id,$survey_id);
                }

                $title = 'Inaccessible Room/locations Summary';
                $table_id = 'inaccessible-room';
                break;

            default:
                $items_summary_table = [];
                $title = '';
                $table_id = '';
                break;
        }
        if ($title) {
            //log audit
            $comment = \Auth::user()->full_name  . " viewed $title table on survey " . $survey->reference;
            \CommonHelpers::logAudit(LOCATION_TYPE, $survey->id, AUDIT_ACTION_VIEW, $survey->reference, 0 ,$comment, 0 ,$survey->property_id);
        }
        $samples = $this->itemService->getSamplesTable($property_id, $survey_id);

        $breadcrumb_data->table_title = $title;

        return view('shineCompliance.surveys.index',[
            'survey' => $survey,
            'breadcrumb_name' => $breadcrumb_name,
            'breadcrumb_data' => $breadcrumb_data,
            'dataTab' => $dataTab,
            'dataDecommisstionTab' => $dataDecommisstionTab,
            'dataDecommisstionItems' => $dataDecommisstionItems,
            'dataSummary' => $dataSummary,
            'plans' => $plans,
            'surveyorsNotes' => $surveyorsNotes,
            'section' => $section,
            'areaData' => $areaData,
            'pagination' => $pagination,
            'locationData' => $locationData,
            'can_upload_sample' => $can_upload_sample,
            'type' => $type,
            'title' => $title,
            'table_id' => $table_id,
            'items_summary_table' => $items_summary_table,
            'samples' => $samples,
            'canBeUpdateSurvey' => $canBeUpdateSurvey,
            'is_locked' => $is_locked,
            'pagination_type' => $pagination_type,
            'active_tab' => $active_tab,
            'position' => $request->position ?? 0,
            'category' => $request->category ?? 0
        ]);
    }

    public function getAddSurvey($property_id) {

        $property =  $this->propertyService->getProperty($property_id);
        $property_areas = $this->areaService->getPropertyArea($property_id);
        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $property->id);

        if (is_null($property)) {
            abort(404);
        }

        $dataProperty = $this->surveyService->getPropertySurvey($property_areas);
        $clients = $this->clientService->getAllClients();
        $surveyUsers = $this->userService->getSurveyUsers();
        // $asbestos_lead_admin = \CommonHelpers::getGetAdminAsbestosLead();
        // $surveyUsers = User::whereIn('id', $asbestos_lead_admin)->get();
        $projects = $this->projectService->getSurveyProjects($property_id);
        $work_streams = $this->projectService->getWorkStreams();
        $secondSurveyors = $this->userService->getWhereInUser(SENCOND_SURVEYORS_ID);
        $cadTechnicans = $this->userService->getWhereInUser(CAD_TECHNICAN_ID);
        return view('shineCompliance.surveys.add_survey',[
            'clients' => $clients,
            'surveyUsers' => $surveyUsers,
            'projects' => $projects ,
            'property_id' => $property_id,
            'property_areas' => $property_areas,
            'dataProperty' => $dataProperty,
            'property' => $property,
            'secondSurveyors' => $secondSurveyors,
            'cadTechnicans' => $cadTechnicans,
            'work_streams' => $work_streams,
        ]);
    }

    public function postAddSurvey($property_id,SurveyCreateRequest $surveyCreateRequest) {
        $validatedData = $surveyCreateRequest->validated();
        try {
            $surveyCreate = $this->surveyService->createSurvey($property_id, $validatedData);
            $cloneRegisterData = $this->surveyService->cloneRegisterData($validatedData['list_area'][0], $validatedData['list_location'][0], $validatedData['list_item'][0], $surveyCreate['data']->id);
            if (isset($surveyCreate) and !is_null($surveyCreate)) {
                if ($surveyCreate['status_code'] == 200) {
                    return redirect()->route('property.surveys', ['survey_id' => $surveyCreate['data']->id, 'section' => SECTION_DEFAULT])->with('msg', $surveyCreate['msg']);
                } else {
                    return redirect()->back()->with('err', $surveyCreate['msg']);
                }
            }
        } catch (\Exception $e) {
            \Log::debug($e);
            return redirect()->back()->with('err', 'Failed to create survey!');
        }

    }

    public function getEditSurvey($survey_id) {
        $survey = $this->surveyService->getSurvey($survey_id);
        if (empty($survey)) {
            abort(404);
        }
        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $survey->property_id);

        if (!\CommonHelpers::isSystemClient()) {
            if(\Auth::user()->client_id == $survey->client_id || \Auth::user()->id == $survey->surveyor_id || \Auth::user()->id == $survey->consultant_id || \Auth::user()->id == $survey->created_by) {

            }else {
                abort(404);
            }
        }

        $clients = $this->clientService->getAllClients();
        $surveyUsers = $this->userService->getSurveyUsers();
        // $asbestos_lead_admin = \CommonHelpers::getGetAdminAsbestosLead();
        // $surveyUsers = User::whereIn('id', $asbestos_lead_admin)->get();
        $projects = $this->projectService->getSurveyProjects($survey->property_id);
        $work_streams = $this->projectService->getWorkStreams();
        $secondSurveyors = $this->userService->getWhereInUser(SENCOND_SURVEYORS_ID);
        $cadTechnicans = $this->userService->getWhereInUser(CAD_TECHNICAN_ID);
        return view('shineCompliance.surveys.edit_survey',['clients' => $clients,
            'surveyUsers' => $surveyUsers,
            'projects' => $projects ,
            'secondSurveyors' => $secondSurveyors ,
            'cadTechnicans' => $cadTechnicans ,
            'survey' => $survey,
            'work_streams' => $work_streams
        ]);
    }

    public function postEditSurvey($survey_id, SurveyCreateRequest $surveyCreateRequest) {
        $validatedData = $surveyCreateRequest->validated();
        $survey = $this->surveyService->getSurvey($survey_id);
        $surveyUpdate = $this->surveyService->createSurvey($survey->property_id, $validatedData,$survey_id);

        if (isset($surveyUpdate) and !is_null($surveyUpdate)) {
            if ($surveyUpdate['status_code'] == 200) {
                return redirect()->route('property.surveys', ['survey_id' => $survey->id, 'section' => SECTION_DEFAULT ])->with('msg', $surveyUpdate['msg']);
            } else {
                return redirect()->back()->with('err', $surveyUpdate['msg']);
            }
        }
    }

    public function decommissionSurvey($survey_id) {
        $decommissionSurvey = $this->surveyService->decommissionSurvey($survey_id);
        if (isset($decommissionSurvey)) {
            if ($decommissionSurvey['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionSurvey['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionSurvey['msg']);
            }
        }
    }

    public function sendSurvey($survey_id) {
        $sendSurvey = $this->surveyService->sendSurvey($survey_id);
        if (isset($sendSurvey)) {
            if ($sendSurvey['status_code'] == 200) {
                return redirect()->back()->with('msg', $sendSurvey['msg']);
            } else {
                return redirect()->back()->with('err', $sendSurvey['msg']);
            }
        }
    }

    public function createArea(Request $request) {
        $validator = \Validator::make($request->all(), [
            'survey_id' => 'required',
            'area_reference' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        if($request->has('area_id')) {
            $id = $request->area_id;
            $dataUpdate = [
                'area_reference' => $request->area_reference,
                'description' => $request->description,
            ];
            $createArea = $this->areaService->updateArea($id, $dataUpdate);
        } else {
            $dataCreate = [
                'property_id' => $request->property_id,
                'survey_id' => $request->survey_id,
                'area_reference' => $request->area_reference,
                'description' => $request->description,
                'decommissioned' => 0
            ];
            $createArea = $this->areaService->createArea($dataCreate);
        }
        if (isset($createArea) and !is_null($createArea)) {
            \Session::flash('msg', $createArea['msg']);
            return response()->json(['status_code' => $createArea['status_code'], 'success'=> $createArea['msg'], 'id' => $createArea['data']]);
        }
    }

    public function pickSample(Request $request) {
        $checked_id = $request->has('check') ? $request->check : [];
        $unChecked_id = $request->has('uncheck') ? $request->uncheck : [];

        $updateIsReal = $this->surveyService->updateSampleIsReal($checked_id, $unChecked_id);
        if (isset($updateIsReal) and !is_null($updateIsReal)) {
            \Session::flash('msg', $updateIsReal['msg']);
            return response()->json(['status_code' => $updateIsReal['status_code'], 'msg'=> $updateIsReal['msg']]);
        }
    }

    public function sampleEmailCo1(Request $request) {
        $data_request = $request->all();
        $data = $this->surveyService->emailSample($data_request);
        if (isset($data) and !is_null($data)) {
            if ($data['status_code'] == 200) {
                return redirect()->back()->with('msg', $data['msg']);
            } else {
                return redirect()->back()->with('err', $data['msg']);
            }
        }
    }

    public function getEditSurveyInformation($survey_id, $type) {

        $surveyInfo = $this->surveyService->getSurveyInfomationData($survey_id, $type);
        $survey = $this->surveyService->getSurvey($survey_id);

        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $survey->property_id);

        if (!\CommonHelpers::isSystemClient()) {
            if(\Auth::user()->client_id == $survey->client_id || \Auth::user()->id == $survey->surveyor_id || \Auth::user()->id == $survey->consultant_id || \Auth::user()->id == $survey->created_by) {

            }else {
                abort(404);
            }
        }
        $survey->breadcrumb_title = $surveyInfo['title'];

        return view('shineCompliance.surveys.survey_information_edit',['surveyInfo' => $surveyInfo, 'survey_id' => $survey_id,'type' => $type, 'survey' => $survey]);
    }

    public function postEditSurveyInformation($survey_id, $type,Request $request) {
        $dataInfo = $request->except('_token');
        $updateSurveyInfo = $this->surveyService->updateSurveyInfo($survey_id, $dataInfo);

        if (isset($updateSurveyInfo) and !is_null($updateSurveyInfo)) {
            if ($updateSurveyInfo['status_code'] == 200) {
                return redirect()->route('property.surveys', ['survey_id' => $survey_id, 'section' => SECTION_DEFAULT ])->with('msg', $updateSurveyInfo['msg']);
            } else {
                return redirect()->back()->with('err', $updateSurveyInfo['msg']);
            }
        }
    }

    public function methodOption($survey_id,$type, Request $request) {
        $style = $request->except('_token');
        $property_id = $request->property_id ?? '';
        $data = [
            'property_id' => $property_id
        ];
        $updateMethodStyle = $this->surveyService->updateSurvey($survey_id, $data);
        if (isset($updateMethodStyle) and !is_null($updateMethodStyle)) {
            if ($updateMethodStyle['status_code'] == 200) {
                if ($style['methodStyle'] == METHOD_STYLE_QUESTION) {
                    return redirect()->route('shineCompliance.get.method_question', ['survey_id' => $survey_id]);
                } else {
                    return redirect()->route('shineCompliance.survey-information', ['type' => $type, 'survey_id' => $survey_id]);
                }
            } else {
                return redirect()->back()->with('err', $updateMethodStyle['msg']);
            }
        }
    }

    public function getMethodQuestion($survey_id) {
        $questions = \CommonHelpers::allQuestion();

        $allQuestion = [];
        $question_as = [];
        foreach ($questions as $key => $question) {
            $question_as['id'] = $question->id;
            $question_as['description'] = $question->description;
            $question_as['answers'] = \CommonHelpers::allAnswerQuestion($question->id);
            $question_as['selected'] = \CommonHelpers::getSelectedMethodAnswer($survey_id, $question->id);
            $question_as['comment'] = \CommonHelpers::getMethodAnswerComment($survey_id, $question->id);
            $question_as['other'] = ($question->other == SURVEY_ANSWER_OTHER) ? \CommonHelpers::getMethodAnswerComment($survey_id, $question->id, 'other') : false ;
            $allQuestion[$key] = $question_as;
        }
        $survey = $this->surveyService->getSurvey($survey_id);
        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $survey->property_id);

        if (!\CommonHelpers::isSystemClient()) {
            if (\Auth::user()->client_id == $survey->client_id || \Auth::user()->id == $survey->surveyor_id || \Auth::user()->id == $survey->consultant_id || \Auth::user()->id == $survey->created_by) {
            } else {
                abort(404);
            }
        }
        return view('shineCompliance.surveys.method_question', ['allQuestion' => $allQuestion, 'survey_id' => $survey_id, 'survey' => $survey]);
    }

    public function postMethodQuestion($survey_id, Request $request) {

        $validatedData = $request->validate([
            'comment' => 'nullable|max:255',
        ]);

        $data = $request->all();

        $updateMethodQuestion = $this->surveyService->updateMethodQuestion($survey_id, $data);

        if (isset($updateMethodQuestion) and !is_null($updateMethodQuestion)) {
            if ($updateMethodQuestion['status_code'] == 200) {
                return redirect()->route('property.surveys', ['survey_id' => $survey_id, 'section' => SECTION_DEFAULT ])->with('msg', $updateMethodQuestion['msg']);
            } else {
                return redirect()->back()->with('err', $updateMethodQuestion['msg']);
            }
        }
    }

    public function updateOrCreatePropertyPlan(Request $request) {

        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'property_id' => 'required',
            'assess_id' => 'required',
            'name' => 'required|max:255',
            'plan_date' => 'nullable|date_format:d/m/Y',
            'document' => 'required_without:id|file|max:100000',
            'description' => 'nullable|max:255',
            'category' => 'nullable'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        if ($request->has('id')) {
            $propertyPlan = $this->surveyService->updateOrCreatePropertyPlan($request->all(), $request->id);
        } else {
            $propertyPlan = $this->surveyService->updateOrCreatePropertyPlan($request->all());
        }

        if (isset($propertyPlan) and !is_null($propertyPlan)) {
            \Session::flash('msg', $propertyPlan['msg']);
            return response()->json(['status_code' => $propertyPlan['status_code'], 'success'=> $propertyPlan['msg']]);
        }
    }

    public function getEditPropertyInfo($survey_id)
    {
        if(!$survey = $this->surveyService->getSurvey($survey_id)){
            abort(404);
        }

        $programmeTypes = $this->propertyService->getAllProgrammeType();
        $primaryUses = $this->propertyService->loadDropdownText(PRIMARY_AND_SECONDARY_USE_ID);
        $dataDropdowns = $this->propertyService->getAllPropertyDropdownTitle($survey->property);

        return view('shineCompliance.surveys.edit_property_information',[
            'data' => $survey,
            'programmeTypes' => $programmeTypes,
            'primaryUses' => $primaryUses,
            'dataDropdowns' => $dataDropdowns
            ]
        );
    }

    public function postEditPropertyInfo($survey_id, Request $request)
    {
        $dataInput = $request->except(['_token', 'property_survey_img','sizeFloorsOther', 'sizeStaircasesOther', 'sizeLiftsOther']);
        if ($request->sizeFloors == 'Other') {
            $dataInput['sizeFloors'] = $request->sizeFloorsOther;
        }
        if ($request->sizeLifts == 'Other') {
            $dataInput['sizeLifts'] = $request->sizeLiftsOther;
        }
        if ($request->sizeStaircases == 'Other') {
            $dataInput['sizeStaircases'] = $request->sizeStaircasesOther;
        }

        $dataInfo = [
            'property_data' => json_encode($dataInput)
        ];
        if(!$survey = $this->surveyService->getSurvey($survey_id)) {
            abort(404);
        }

        $updateSurveyInfo = $this->surveyService->updateSurveyInfo($survey_id, $dataInfo, $request->property_survey_img);
        $survey =$this->surveyService->getFindSurvey($survey_id);


        if (isset($updateSurveyInfo) and !is_null($updateSurveyInfo)) {
            if ($updateSurveyInfo['status_code'] == 200) {
                // store comment history
                \CommentHistory::storeCommentHistory('property', $survey->property_id, $dataInput['sizeComments'], $survey->reference);
                return redirect()->route('property.surveys', ['survey_id' => $survey_id, 'section' => SECTION_DEFAULT ])->with('msg', $updateSurveyInfo['msg']);
            } else {
                return redirect()->back()->with('err', $updateSurveyInfo['msg']);
            }
        }
    }

    public function getEditSample($survey_id, $sample_id, Request $request) {
        $survey = $this->surveyService->getSurvey($survey_id);
        $sample = $this->surveyService->getSampleById($survey->property_id, $survey_id, $sample_id);
        $pagination = [];

        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $survey->property_id);

        if (!\CommonHelpers::isSystemClient()) {
            if (\Auth::user()->client_id == $survey->client_id || \Auth::user()->id == $survey->surveyor_id || \Auth::user()->id == $survey->consultant_id || \Auth::user()->id == $survey->created_by) {
            } else {
                abort(404);
            }
        }
        if(isset($request->position)){
            $list_samples = $samples = $this->surveyService->getActiveSamplesTable($survey->property_id, $survey->id);
            //set path
            $pagination = \CommonHelpers::setPathPagination($request, $list_samples, 'sample', $sample[0]->id);
        }

        if (empty($sample)) {
            abort(404);
        }
        $sample = $sample[0];
        $itemOS = $this->itemService->getItemOS($sample->original_item_id,$survey_id);
        if (is_null($itemOS)) {
            abort(404);
        }
        $abestosTypes = $this->itemService->loadDropdownText(ASBESTOS_TYPE_ID);
        $selectedAsbetosType  = $this->itemService->getDropdownItemValue($itemOS->id, ASBESTOS_TYPE_ID, 0, 'id');
        if ($itemOS->state == 1) {
            $selectedAsbetosType = [393,394];
        }
        $assessmentAsbestosKeys = $this->itemService->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY);
        $selectedAssessmentAsbestosKeys = $this->itemService->getDropdownItemValue($itemOS->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY, 'id');
        $breadcrumb_data = $survey;
        $breadcrumb_data->breadcrumb_title = $sample->description;

        //log audit
        $comment = \Auth::user()->full_name  . " view sample " . $sample->description . " on survey " . $survey->reference;
        \CommonHelpers::logAudit(SAMPLE_TYPE, $sample->id, AUDIT_ACTION_VIEW, $sample->reference, $survey->id ,$comment, 0 ,$survey->property_id);
        return view('shineCompliance.surveys.edit_sample',[
            'survey_id' => $survey_id,
            'sample' => $sample,
            'abestosTypes' => $abestosTypes,
            'selectedAsbetosType' => $selectedAsbetosType,
            'assessmentAsbestosKeys' => $assessmentAsbestosKeys,
            'selectedAssessmentAsbestosKeys' => $selectedAssessmentAsbestosKeys,
            'breadcrumb_data' => $breadcrumb_data,
            'pagination' => $pagination,
            'position' => $request->position
        ]);
    }

    public function postEditSample($survey_id, $sample_id, SampleUpdateRequest $sampleUpdateRequest) {

        $validatedData = $sampleUpdateRequest->validated();
        $survey = $this->surveyService->getSurvey($survey_id);
        $updateSample = $this->itemService->updateSampleSurvey($sample_id, $validatedData['reference'], \CommonHelpers::checkArrayKey($validatedData,'AsbestosTypeMore'));
        $updateItemOs = $this->itemService->updateItemOs($validatedData, $validatedData['list_item_id'],$sample_id, $survey_id);
        //log audit
        $comment = \Auth::user()->full_name  . " updated sample " . $validatedData['reference'] . " on survey " . $survey->reference;
        \CommonHelpers::logAudit(SAMPLE_TYPE, $sample_id, AUDIT_ACTION_VIEW, $validatedData['reference'], $survey->id ,$comment, 0 ,$survey->property_id);

        if (isset($updateItemOs) and !is_null($updateItemOs) and isset($updateSample) and !is_null($updateSample)) {
            if ($updateItemOs['status_code'] == 200 and $updateSample['status_code'] == 200) {
                return redirect()->back()->withInput(array('pagination' => $sampleUpdateRequest->pagination,'position' => $sampleUpdateRequest->position))->with('msg', $updateSample['msg']);
            } else {
                return redirect()->back()->withInput(array('pagination' => $sampleUpdateRequest->pagination,'position' => $sampleUpdateRequest->position))->with('err', $updateSample['msg']);
            }
        }
    }
}

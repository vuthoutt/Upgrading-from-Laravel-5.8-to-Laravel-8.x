<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Survey;
use App\Models\Sample;
use App\Repositories\SurveyRepository;
use App\Repositories\PropertyRepository;
use App\Repositories\AreaRepository;
use App\Repositories\LocationRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ItemRepository;
use App\Http\Request\Survey\SurveyInformationRequest;
use App\Http\Request\Survey\SurveyCreateRequest;
use App\Http\Request\Survey\SampleUpdateRequest;
use App\Helpers\CommonHelpers;
use App\Models\PropertyProgrammeType;

class SurveyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SurveyRepository $surveyRepository, PropertyRepository $propertyRepository, AreaRepository $areaRepository,LocationRepository $locationRepository, ClientRepository $clientRepository, ItemRepository $itemRepository)
    {
        $this->surveyRepository = $surveyRepository;
        $this->propertyRepository = $propertyRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->clientRepository = $clientRepository;
        $this->itemRepository = $itemRepository;

    }

    /**
     * Show survey detail.
     *
     */
    public function index($survey_id, Request $request)
    {

        $survey = $this->surveyRepository->getSurvey($survey_id);
        $awarded_survey = $this->surveyRepository->hasAwardedSurveyProperty(\Auth::user()->client_id, $survey->property_id);
        $property_id = $this->surveyRepository->getPropertyFromSurvey($survey_id);
        //check privilege
        // \Privilege::checkPermission(PROPERTY_PERMISSION, $property_id);

        if (empty($survey)) {
            abort(404);
        }

        $is_locked = ($survey->is_locked == 1) || $survey->status == 5 ? true : false;
        if (!\CommonHelpers::isSystemClient()) {
            $canBeUpdateSurvey = true;
            if(\Auth::user()->client_id == $survey->client_id || \Auth::user()->id == $survey->surveyor_id || \Auth::user()->id == $survey->consultant_id || \Auth::user()->id == $survey->created_by) {

            }else {
                if($awarded_survey){
                    $canBeUpdateSurvey = false;
                } else {
                    abort(404);
                }
            }
        } else {

            //check privilege
            // property permission and register tab permission
            if(!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $survey->property_id)){
                abort(403);
            }
            $canBeUpdateSurvey = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_ASSESSMENT_ASBESTOS,JOB_ROLE_ASBESTOS ) and \CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $survey->property_id);
        }


        $plans = $this->surveyRepository->getSurveyPlans($property_id, $survey_id);
        $surveyorsNotes = $this->surveyRepository->getSurveyorsNotes($property_id, $survey_id);

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
                $area_id = $request->area;
                $areaData = $this->areaRepository->getArea($area_id);
                if (!$areaData) {
                    abort(404);
                }
                //check privilege
                if (\CommonHelpers::isSystemClient()) {
                    // property permission and register tab permission
                    if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $areaData->property_id)) {
                        abort(403);
                    }
                }
                // only pagination for some case
                if(isset($request->position)){
                    $list_areas = $this->areaRepository->getAreaPaginationCustomize($survey_id, $areaData->decommissioned, $property_id);
                    //set path
                    $pagination = CommonHelpers::setPathPagination($request, $list_areas, 'area', $areaData->id);
                }

                $dataTab = $this->locationRepository->getAreaLocation($area_id, $survey_id);
                $dataDecommisstionTab = $this->locationRepository->getAreaLocation($area_id, $survey_id, 1);
                $items = Item::with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $areaData->property_id)->where('survey_id', $survey_id)->where('area_id', $area_id)->get();

                $dataSummary = $this->itemRepository->getRegisterSurveySummary($items,'surveyarea', $property_id, $areaData->survey_id , $area_id);

                $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
                $breadcrumb_name = 'survey_area';
                $breadcrumb_data = $areaData;

                //log audit
                $comment = \Auth::user()->full_name  . " viewed Survey Area/Floor "  . $areaData->area_reference .' on ' . $survey->reference . ' on ' . $survey->property->name;
                \CommonHelpers::logAudit(AREA_TYPE, $areaData->id, AUDIT_ACTION_VIEW, $areaData->area_reference, $areaData->survey_id ,$comment, 0 ,$areaData->property_id);
                break;

            case SECTION_ROOM_LOCATION_SUMMARY:
                $pagination_type = TYPE_LOCATION;
                $location_id = $request->location;
                // only pagination for some case
                $locationData = $this->locationRepository->getLocation($location_id);
                if (!$locationData) {
                    abort(404);
                }
                //check privilege
                if (\CommonHelpers::isSystemClient()) {
                    // property permission and register tab permission
                    if(!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $locationData->property_id)){
                        abort(404);
                    }
                }

                if(isset($request->position)){
                    //inaccessible will show only register data so will no need to check client_id, zone_id
                    $list_locations = $this->locationRepository->getLocationPaginationCustomize($survey_id, $locationData->decommissioned, $locationData->property_id, $locationData->area_id, NULL, NULL, $request->category, $request->pagination_type);
                    //set path
                    $pagination = CommonHelpers::setPathPagination($request, $list_locations, 'location', $locationData->id);
                }

                $dataTab = [];
                $dataDecommisstionTab = [];
                $items = Item::with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $locationData->property_id)->where('area_id', $locationData->area_id)->where('survey_id', $survey_id)->where('location_id', $location_id)->get();

                $dataSummary = $this->itemRepository->getRegisterSurveySummary($items,'survey-room', $property_id, $locationData->survey_id , $locationData->area_id, $location_id);

                $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
                $breadcrumb_name = 'survey_location';
                $breadcrumb_data = $locationData;

                //log audit
                $comment = \Auth::user()->full_name  . " viewed Survey Room/Location "  . $locationData->location_reference .' on ' . $survey->reference . ' on ' . $survey->property->name;
                \CommonHelpers::logAudit(LOCATION_TYPE, $locationData->id, AUDIT_ACTION_VIEW, $locationData->location_reference, $locationData->survey_id ,$comment, 0 ,$locationData->property_id);
                break;
            default:
                //get all areas
                //get decommissioned (1) areas
                $dataTab = $this->surveyRepository->getSurveyArea($survey_id);
                $dataDecommisstionTab= $this->surveyRepository->getSurveyArea($survey_id, 1);
                $items = Item::with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $property_id)->where('survey_id', $survey_id)->get();

                $dataSummary = $this->itemRepository->getRegisterSurveySummary($items,'survey', $property_id, $survey_id);
                $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
                $breadcrumb_name = 'survey';
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
                    $items_summary_table = $this->itemRepository->countInaccessibleRooms('surveyarea',$property_id, $survey_id,$area_id );
                } else {
                    $items_summary_table = $this->itemRepository->countInaccessibleRooms('survey',$property_id,$survey_id);
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
        $samples = $this->surveyRepository->getSamplesTable($property_id, $survey_id);

        $breadcrumb_data->table_title = $title;

        return view('surveys.index',[
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

    public function getEditSurveyInformation($survey_id, $type) {

        $surveyInfo = $this->surveyRepository->getSurveyInfomationData($survey_id, $type);
        $survey = $this->surveyRepository->getSurvey($survey_id);

        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $survey->property_id);

        if (!\CommonHelpers::isSystemClient()) {
            if(\Auth::user()->client_id == $survey->client_id || \Auth::user()->id == $survey->surveyor_id || \Auth::user()->id == $survey->consultant_id || \Auth::user()->id == $survey->created_by) {

            }else {
                abort(403);
            }
        }
        $survey->breadcrumb_title = $surveyInfo['title'];

        return view('surveys.survey_information_edit',['surveyInfo' => $surveyInfo, 'survey_id' => $survey_id,'type' => $type, 'survey' => $survey]);
    }

    // edit survey information for each type
    public function postEditSurveyInformation($survey_id, $type,Request $request) {
        $dataInfo = $request->except('_token');
        $updateSurveyInfo = $this->surveyRepository->updateSurveyInfo($survey_id, $dataInfo);

        if (isset($updateSurveyInfo) and !is_null($updateSurveyInfo)) {
            if ($updateSurveyInfo['status_code'] == 200) {
                return redirect()->route('property.surveys', ['survey_id' => $survey_id, 'section' => SECTION_DEFAULT ])->with('msg', $updateSurveyInfo['msg']);
            } else {
                return redirect()->back()->with('err', $updateSurveyInfo['msg']);
            }
        }
    }

    // change method option style
    public function methodOption($survey_id,$type, Request $request) {
        $style = $request->except('_token');
        $updateMethodStyle = $this->surveyRepository->updateSurvey($survey_id, $style);
        if (isset($updateMethodStyle) and !is_null($updateMethodStyle)) {
            if ($updateMethodStyle['status_code'] == 200) {
                if ($style['methodStyle'] == METHOD_STYLE_QUESTION) {
                    return redirect()->route('get.method_question', ['survey_id' => $survey_id]);
                } else {
                    return redirect()->route('survey-information', ['type' => $type, 'survey_id' => $survey_id]);
                }
            } else {
                return redirect()->back()->with('err', $updateMethodStyle['msg']);
            }
        }
    }

    // Get method question edit form
    public function getMethodQuestion($survey_id) {
        $questions = CommonHelpers::allQuestion();

        $allQuestion = [];
        $question_as = [];
        foreach ($questions as $key => $question) {
            $question_as['id'] = $question->id;
            $question_as['description'] = $question->description;
            $question_as['answers'] = CommonHelpers::allAnswerQuestion($question->id);
            $question_as['selected'] = CommonHelpers::getSelectedMethodAnswer($survey_id, $question->id);
            $question_as['comment'] = CommonHelpers::getMethodAnswerComment($survey_id, $question->id);
            $question_as['other'] = ($question->other == SURVEY_ANSWER_OTHER) ? CommonHelpers::getMethodAnswerComment($survey_id, $question->id, 'other') : false ;
            $allQuestion[$key] = $question_as;
        }
        $survey = $this->surveyRepository->getSurvey($survey_id);
        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $survey->property_id);

        if (!\CommonHelpers::isSystemClient()) {
            if (\Auth::user()->client_id == $survey->client_id || \Auth::user()->id == $survey->surveyor_id || \Auth::user()->id == $survey->consultant_id || \Auth::user()->id == $survey->created_by) {
            } else {
                abort(404);
            }
        }
        return view('surveys.method_question', ['allQuestion' => $allQuestion, 'survey_id' => $survey_id, 'survey' => $survey]);
    }

    // Save method qustion
    public function postMethodQuestion($survey_id, Request $request) {

        $validatedData = $request->validate([
            'comment' => 'nullable|max:255',
        ]);

        $data = $request->all();

        $updateMethodQuestion = $this->surveyRepository->updateMethodQuestion($survey_id, $data);

        if (isset($updateMethodQuestion) and !is_null($updateMethodQuestion)) {
            if ($updateMethodQuestion['status_code'] == 200) {
                return redirect()->route('property.surveys', ['survey_id' => $survey_id, 'section' => SECTION_DEFAULT ])->with('msg', $updateMethodQuestion['msg']);
            } else {
                return redirect()->back()->with('err', $updateMethodQuestion['msg']);
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
            $createArea = $this->areaRepository->updateArea($id, $dataUpdate);
        } else {
            $dataCreate = [
                'property_id' => $request->property_id,
                'survey_id' => $request->survey_id,
                'area_reference' => $request->area_reference,
                'description' => $request->description,
                'decommissioned' => 0
            ];
            $createArea = $this->areaRepository->createArea($dataCreate);
        }
        if (isset($createArea) and !is_null($createArea)) {
            \Session::flash('msg', $createArea['msg']);
            return response()->json(['status_code' => $createArea['status_code'], 'success'=> $createArea['msg'], 'id' => $createArea['data']]);
        }
    }

    public function getAddSurvey($property_id) {
        $property_areas = $this->propertyRepository->getPropertyArea($property_id);
        $property =  $this->propertyRepository->findWhere(['id' => $property_id])->first();
        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $property->id);

        if (is_null($property)) {
            abort(404);
        }
        $dataProperty = [];
        foreach ($property_areas as $key => $area) {
            $dataTmp['area'] =  $area;
            $dataTmp['locations'] = [];
            if (!is_null($area->locations)) {
                foreach ($area->locations as $key => $location) {
                    $location->title = $this->surveyRepository->getLocationTreeDes($location);
                    $dataTmp['locations'][$key]['location'] = $location;
                    $dataTmp['locations'][$key]['items'] = [];
                    if (!is_null($location->items)) {
                        foreach ($location->items as $key1 => $item) {
                            $item->productDebris = $this->surveyRepository->getItemTreeDes($item);
                            $dataTmp['locations'][$key]['items'][] = $item;
                        }
                    }
                }
            }
            $dataProperty[] = $dataTmp;
        }

        $clients = $this->clientRepository->getAllClients();
        $surveyUsers = $this->surveyRepository->getSurveyUsers();
        $projects = $this->surveyRepository->getSurveyProjects($property_id);
        $work_streams = $this->surveyRepository->getWorkStreams();

        return view('surveys.add_survey',[
            'clients' => $clients,
            'surveyUsers' => $surveyUsers,
            'projects' => $projects ,
            'property_id' => $property_id,
            'property_areas' => $property_areas,
            'dataProperty' => $dataProperty,
            'property' => $property,
            'work_streams' => $work_streams,
        ]);
    }

    public function postAddSurvey($property_id,SurveyCreateRequest $surveyCreateRequest) {

        $validatedData = $surveyCreateRequest->validated();

        $surveyCreate = $this->surveyRepository->createSurvey($property_id, $validatedData);
        $cloneRegisterData = $this->surveyRepository->cloneRegisterData($validatedData['list_area'][0], $validatedData['list_location'][0], $validatedData['list_item'][0], $surveyCreate['data']->id);
        if (isset($surveyCreate) and !is_null($surveyCreate)) {
            if ($surveyCreate['status_code'] == 200) {
                return redirect()->route('property.surveys', ['survey_id' => $surveyCreate['data']->id, 'section' => SECTION_DEFAULT])->with('msg', $surveyCreate['msg']);
            } else {
                return redirect()->back()->with('err', $surveyCreate['msg']);
            }
        }

    }

    public function getEditSurvey($survey_id) {
        $survey = $this->surveyRepository->getSurvey($survey_id);
        if (empty($survey)) {
            abort(404);
        }
        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $survey->property_id);

        if (!\CommonHelpers::isSystemClient()) {
            if(\Auth::user()->client_id == $survey->client_id || \Auth::user()->id == $survey->surveyor_id || \Auth::user()->id == $survey->consultant_id || \Auth::user()->id == $survey->created_by) {

            }else {
                abort(403);
            }
        }

        $clients = $this->clientRepository->getAllClients();
        $surveyUsers = $this->surveyRepository->getSurveyUsers();
        $projects = $this->surveyRepository->getSurveyProjects($survey->property_id);
        $work_streams = $this->surveyRepository->getWorkStreams();
        return view('surveys.edit_survey',['clients' => $clients,'surveyUsers' => $surveyUsers,'projects' => $projects , 'survey' => $survey, 'work_streams' => $work_streams]);
    }

    public function postEditSurvey($survey_id, SurveyCreateRequest $surveyCreateRequest) {
        $validatedData = $surveyCreateRequest->validated();
        $survey = $this->surveyRepository->getSurvey($survey_id);
        $surveyUpdate = $this->surveyRepository->createSurvey($survey->property_id, $validatedData,$survey_id);

        if (isset($surveyUpdate) and !is_null($surveyUpdate)) {
            if ($surveyUpdate['status_code'] == 200) {
                return redirect()->route('property.surveys', ['survey_id' => $survey->id, 'section' => SECTION_DEFAULT ])->with('msg', $surveyUpdate['msg']);
            } else {
                return redirect()->back()->with('err', $surveyUpdate['msg']);
            }
        }
    }

    public function getClientUsers($client_id) {
        $users = $this->clientRepository->getClientUsers($client_id);
        return $users;
    }

    public function getEditPropertyInfo($survey_id) {
        $survey = $this->surveyRepository->getSurvey($survey_id);

        if (empty($survey)) {
            abort(404);
        }
        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $survey->property_id);

        if (!\CommonHelpers::isSystemClient()) {
            if(\Auth::user()->client_id == $survey->client_id || \Auth::user()->id == $survey->surveyor_id || \Auth::user()->id == $survey->consultant_id || \Auth::user()->id == $survey->created_by) {

            }else {
                abort(403);
            }
        }

        $programmeTypes = PropertyProgrammeType::all();

        $propertyStatus = $this->propertyRepository->getPropertyInfoDropdown(PROPERTY_INFO_PROPERTY_STATUS_ID);
        $propertyOccupied = $this->propertyRepository->getPropertyInfoDropdown(PROPERTY_INFO_PROPERTY_OCCUPIED_ID);

        $primaryUses = $this->propertyRepository->loadDropdownText(PRIMARY_AND_SECONDARY_USE_ID);
        $allPropertyDropdowns = $this->propertyRepository->getAllPropertyDropdownTitle();
        $dataDropdowns = [];
        foreach ($allPropertyDropdowns as $key => $propertyDropdown) {
            $tmp['description'] = $propertyDropdown->name;
            $tmp['name'] = $propertyDropdown->key_name;
            $tmp['value'] = $this->propertyRepository->getPropertyDropdownData($propertyDropdown->id);
            $tmp['selected'] = [
                isset($survey->surveyInfo->property_data->electricalMeter) ? $survey->surveyInfo->property_data->electricalMeter : 0,
                isset($survey->surveyInfo->property_data->gasMeter) ? $survey->surveyInfo->property_data->gasMeter : 0,
                isset($survey->surveyInfo->property_data->loftVoid) ? $survey->surveyInfo->property_data->loftVoid : 0,
            ] ;
            $dataDropdowns[] = $tmp;
        }

        return view('surveys.edit_property_information', [
            'data' => $survey,
            'programmeTypes' => $programmeTypes,
            'primaryUses' => $primaryUses,
            'dataDropdowns' => $dataDropdowns,
            'propertyStatus' => $propertyStatus,
            'propertyOccupied' => $propertyOccupied,
        ]);
    }

    public function postEditPropertyInfo($survey_id, Request $request) {
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

        $updateSurveyInfo = $this->surveyRepository->updateSurveyInfo($survey_id, $dataInfo, $request->property_survey_img);
        $survey = Survey::find($survey_id);

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


    public function publishSurvey($survey_id, Request $request) {
        $survey_draf = $request->has('survey_draf') ? true : false;
        $publishSurvey = $this->surveyRepository->publishSurvey($survey_id, $survey_draf);

        if (isset($publishSurvey) and !is_null($publishSurvey)) {
            if ($publishSurvey['status_code'] == 200) {
                return redirect()->back()->with('msg', $publishSurvey['msg']);
            } else {
                return redirect()->back()->with('err', $publishSurvey['msg']);
            }
        }
    }

    public function approvalSurvey($survey_id) {
        $approvalSurvey = $this->surveyRepository->approvalSurvey($survey_id);

        if (isset($approvalSurvey) and !is_null($approvalSurvey)) {
            if ($approvalSurvey['status_code'] == 200) {
                return redirect()->back()->with('msg', $approvalSurvey['msg']);
            } else {
                return redirect()->back()->with('err', $approvalSurvey['msg']);
            }
        }
    }

    public function rejectSurvey($survey_id, Request $request) {
        $rejectSurvey = $this->surveyRepository->rejectSurvey($survey_id, $request->all());

        if (isset($rejectSurvey) and !is_null($rejectSurvey)) {
            if ($rejectSurvey['status_code'] == 200) {
                return redirect()->back()->with('msg', $rejectSurvey['msg']);
            } else {
                return redirect()->back()->with('err', $rejectSurvey['msg']);
            }
        }
    }

    public function cancelSurvey($survey_id) {

        $cancelSurvey = $this->surveyRepository->cancelSurvey($survey_id);

        if (isset($cancelSurvey) and !is_null($cancelSurvey)) {
            if ($cancelSurvey['status_code'] == 200) {
                return redirect()->back()->with('msg', $cancelSurvey['msg']);
            } else {
                return redirect()->back()->with('err', $cancelSurvey['msg']);
            }
        }
    }

    public function getEditSample($survey_id, $sample_id, Request $request) {
        $survey = $this->surveyRepository->getSurvey($survey_id);
        $awarded_survey = $this->surveyRepository->hasAwardedSurveyProperty(\Auth::user()->client_id, $survey->property_id);
        $sample = $this->surveyRepository->getSampleById($survey->property_id, $survey_id, $sample_id);
        $pagination = [];

        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $survey->property_id);
        $canBeUpdateSample = true;
        if (!\CommonHelpers::isSystemClient()) {
            if (\Auth::user()->client_id == $survey->client_id || \Auth::user()->id == $survey->surveyor_id || \Auth::user()->id == $survey->consultant_id || \Auth::user()->id == $survey->created_by) {
            } else {
                if($awarded_survey){
                    $canBeUpdateSample = false;
                } else {
                    abort(404);
                }
            }
        }
        if(isset($request->position)){
            $list_samples = $samples = $this->surveyRepository->getSamplesTable($survey->property_id, $survey->id);
            //set path
            $pagination = CommonHelpers::setPathPagination($request, $list_samples, 'sample', $sample[0]->id);
        }

        if (empty($sample)) {
            abort(404);
        }
        $sample = $sample[0];
        $itemOS = Item::where('record_id', $sample->original_item_id)->where('survey_id', $survey_id)->first();
        if (is_null($itemOS)) {
            abort(404);
        }
        $abestosTypes = $this->itemRepository->loadDropdownText(ASBESTOS_TYPE_ID);
        $selectedAsbetosType  = $this->itemRepository->getDropdownItemValue($itemOS->id, ASBESTOS_TYPE_ID, 0, 'id');
        if ($itemOS->state == 1) {
            $selectedAsbetosType = [393,394];
        }
        $assessmentAsbestosKeys = $this->itemRepository->loadDropdownText(MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY);
        $selectedAssessmentAsbestosKeys = $this->itemRepository->getDropdownItemValue($itemOS->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY, 'id');
        $survey = $survey = $this->surveyRepository->getSurvey($survey_id);
        $breadcrumb_data = $survey;
        $breadcrumb_data->breadcrumb_title = $sample->description;

        //log audit
        $comment = \Auth::user()->full_name  . " view sample " . $sample->description . " on survey " . $survey->reference;
        \CommonHelpers::logAudit(SAMPLE_TYPE, $sample->id, AUDIT_ACTION_VIEW, $sample->reference, $survey->id ,$comment, 0 ,$survey->property_id);
        // dd($request->position);
        return view('surveys.edit_sample',[
            'canBeUpdateSample' => $canBeUpdateSample,
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
        $survey = $this->surveyRepository->getSurvey($survey_id);

        $updateSample = $this->surveyRepository->updateSample($sample_id, $validatedData['reference'], \CommonHelpers::checkArrayKey($validatedData,'AsbestosTypeMore'));
        $updateItemOs = $this->itemRepository->updateItemOs($validatedData, $validatedData['list_item_id'],$sample_id, $survey_id);

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

    public function pickSample(Request $request) {
        $checked_id = $request->has('check') ? $request->check : [];
        $unChecked_id = $request->has('uncheck') ? $request->uncheck : [];

        $updateIsReal = $this->surveyRepository->updateSampleIsReal($checked_id, $unChecked_id);
        if (isset($updateIsReal) and !is_null($updateIsReal)) {
            \Session::flash('msg', $updateIsReal['msg']);
            return response()->json(['status_code' => $updateIsReal['status_code'], 'msg'=> $updateIsReal['msg']]);
        }
    }

    public function decommissionSurvey($survey_id,Request $request) {
        $decommission_reason = $request->decommission_reason ?? NULL;
        $decommissionSurvey = $this->surveyRepository->decommissionSurvey($survey_id,$decommission_reason);
        if (isset($decommissionSurvey)) {
            if ($decommissionSurvey['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionSurvey['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionSurvey['msg']);
            }
        }
    }

    public function decommissionArea($area_id, Request $request) {
        $reason = $request->area_decommisson_reason_add;
        $decommissionArea = $this->areaRepository->decommissionArea($area_id, $reason );
        if (isset($decommissionArea)) {
            if ($decommissionArea['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionArea['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionArea['msg']);
            }
        }
    }

    public function decommissionAreaReason($area_id, Request $request) {
        $reason = $request->area_decommisson_reason;
        $decommissionAreaReason = $this->areaRepository->decommissionAreaReason($area_id, $reason);
        if (isset($decommissionAreaReason)) {
            if ($decommissionAreaReason['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionAreaReason['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionAreaReason['msg']);
            }
        }
    }

    public function searchSurvey(Request $request) {
        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        $data = $this->surveyRepository->searchSurvey($query_string);
        return response()->json($data);
    }

    public function sendSurvey($survey_id) {
        $sendSurvey = $this->surveyRepository->sendSurvey($survey_id);
        if (isset($sendSurvey)) {
            if ($sendSurvey['status_code'] == 200) {
                return redirect()->back()->with('msg', $sendSurvey['msg']);
            } else {
                return redirect()->back()->with('err', $sendSurvey['msg']);
            }
        }
    }

    public function searchSurveyAdminTool(Request $request) {
        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        $data = $this->surveyRepository->searchSurveyAdminTool($query_string, $request->type_search);
        return response()->json($data);
    }

    public function postDecommission($assess_id, Request $request)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }
        $reason_decommissioned = $request->get('decommission_reason');
        $result = $this->assessmentService->decommissionAssessment($assessment, $reason_decommissioned);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

}

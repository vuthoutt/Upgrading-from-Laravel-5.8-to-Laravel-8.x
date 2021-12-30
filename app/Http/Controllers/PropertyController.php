<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelpers;
use App\Models\Client;
//use Illuminate\Support\Facades\Lang;
use App\Models\DropdownDataProperty;
use App\Models\Item;
use App\Models\Property;
use App\Models\DecommissionReason;
use App\Models\HistoricalDocumentType;
use App\Models\Zone;
use App\Repositories\ClientRepository;
use App\Repositories\AreaRepository;
use App\Repositories\LocationRepository;
use App\Repositories\ItemRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Repositories\PropertyRepository;
use App\Repositories\UserRepository;
use App\Repositories\ZoneRepository;
use App\Http\Request\Property\PropertyCreateRequest;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function __construct(PropertyRepository $propertyRepository,
                                ClientRepository $clientRepository,
                                AreaRepository $areaRepository,
                                LocationRepository $locationRepository,
                                ZoneRepository $zoneRepository,
                                ItemRepository $itemRepository
)
    {
        $this->propertyRepository = $propertyRepository;
        $this->clientRepository = $clientRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->itemRepository = $itemRepository;
        $this->zoneRepository = $zoneRepository;
    }

    public function index(Request $request) {
        return  view('property.index');
    }

    /**
     * get property detail
     * @return
     */
    public function detail($property_id, Request $request) {
        $propertyData = $this->propertyRepository->getProperty($property_id);

        if (is_null($propertyData)) {
            abort(404);
        }
        //check privilege
        if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $property_id) and \CommonHelpers::isSystemClient()) {
            abort(404);
        }

        $is_client_user = \CommonHelpers::isSystemClient();
        //get risk type
        $risk_type_one = $risk_type_two = NULL;
        if(isset($propertyData->propertyType) && !$propertyData->propertyType->isEmpty()){
            $risk_type_one = $propertyData->propertyType->where('ms_level',1)->first();
            $risk_type_two = $propertyData->propertyType->where('id',2)->first();//build after 2000
        }

        $contactUser = $this->propertyRepository->getPropertyContacts($property_id);
        $warning_message = $this->propertyRepository->getWarningMessagev2($propertyData);
        $parent_property = $this->propertyRepository->getParentProperty($propertyData->parent_id);
        $child_property = [];
        if(is_null($propertyData->parent_reference)){
            $child_property = $this->propertyRepository->getChildrenProperty($propertyData->id);
        }
        $historical_doc_type = HistoricalDocumentType::all();
        //get property surveys
        //get decommissioned (1) surveys
        if (\CommonHelpers::isSystemClient()) {
            $canBeUpdateThisSite = \CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id);
            $surveys = $this->propertyRepository->getPropertySurvey($property_id);
            $decommissionedSurveys = $this->propertyRepository->getPropertySurvey($property_id, 1);
        } else {
            $canBeUpdateThisSite = false;
            $surveys = $this->propertyRepository->getPropertySurvey($property_id, 0, \Auth::user()->client_id);
            $decommissionedSurveys = $this->propertyRepository->getPropertySurvey($property_id, 1, \Auth::user()->client_id);
        }

        if ($request->has('section')) {
            $section = $request->section;
        } else {
            $section = SECTION_DEFAULT;
        }
        $user = Auth::user();
        $areaData = [];
        $locationData = [];
        $survey_id = 0;
        $pagination = [];
        $areaStamping = [];
        $locationStamping = [];
        $pagination_type = TYPE_PROPERTY;
        $active_tab = $request->active_tab ?? '';//property active detail tab
        switch ($section) {
            case SECTION_AREA_FLOORS_SUMMARY:
                $pagination_type = TYPE_AREA;
                $area_id = $request->area;
                $areaData = $this->areaRepository->getArea($area_id);
                if (!$areaData) {
                    abort(404);
                }
                //check privilege
//                if (\CommonHelpers::isSystemClient()) {
//                    // property permission and register tab permission
//                    if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $areaData->property_id) || !\CompliancePrivilege::checkPermission(REGISTER_VIEW_PRIV, $areaData->property_id)) {
//                        abort(404);
//                    }
//                }
                // only pagination for some casel
                if(isset($request->position)){
                    $list_areas = $this->areaRepository->getAreaPaginationCustomize($survey_id, $areaData->decommissioned, $property_id);
                    //set path

                    $pagination = CommonHelpers::setPathPagination($request, $list_areas, 'area', $areaData->id);
                }
                $areaStamping  = $this->propertyRepository->get_data_stamping($areaData);

                if($user->is_site_operative == 1){
                    // list location operative
                    return $this->locationOperative($areaData, $propertyData, $risk_type_one, $risk_type_two);
                }

                $dataTab = $this->locationRepository->getAreaLocation($area_id, $areaData->survey_id);
                $dataDecommisstionTab = $this->locationRepository->getAreaLocation($area_id, $areaData->survey_id, 1);
                $items = Item::with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $areaData->property_id)->where('survey_id', 0)->where('area_id', $area_id)->get();

                $dataSummary = $this->itemRepository->getRegisterSurveySummary($items,'registerarea', $property_id, 0 , $area_id);

                $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
                $breadcrumb_name = 'properties_area';
                $breadcrumb_data = $areaData;
                $acm_type = 'registerarea';

                // log audit
                $comment = \Auth::user()->full_name  . " viewed Property Register Area/Floor "  . $areaData->area_reference .' on ' . $propertyData->name;
                \CommonHelpers::logAudit(AREA_TYPE, $areaData->id, AUDIT_ACTION_VIEW, $areaData->area_reference, $areaData->survey_id ,$comment, 0 ,$areaData->property_id);
                break;

            case SECTION_ROOM_LOCATION_SUMMARY:
                $location_id = $request->location;
                $pagination_type = TYPE_LOCATION;
                if($user->is_site_operative == 1){
                    // list item operative
                    return $this->itemOperative($location_id, $propertyData, $risk_type_one, $risk_type_two);
                }
                // only pagination for some case
                $locationData = $this->locationRepository->getLocation($location_id);
                $locationStamping  = $this->propertyRepository->get_data_stamping($locationData);
                if (!$locationData) {
                    abort(404);
                }
                //check privilege
                if (\CommonHelpers::isSystemClient()) {
                    // property permission and register tab permission
                    if(!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $locationData->property_id) || !\CompliancePrivilege::checkPermission(REGISTER_VIEW_PRIV)){
                        abort(404);
                    }
                }

                if(isset($request->position)){
                    // $survey_id, $decommissioned , $property_id, $area_id, $group_id ,$client_id = NULL, $type = NULL, $pagination_type = NULL
                    $list_locations = $this->locationRepository->getLocationPaginationCustomize($survey_id, $locationData->decommissioned, $locationData->property_id, $locationData->area_id, $propertyData->zone_id,$propertyData->client_id, $request->category, $request->pagination_type);
                    //set path
                    $pagination = CommonHelpers::setPathPagination($request, $list_locations, 'location',$locationData->id);
                }
                $dataTab = [];
                $dataDecommisstionTab = [];
                $items = Item::with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $locationData->property_id)->where('area_id', $locationData->area_id)->where('survey_id', 0)->where('location_id', $location_id)->get();

                $dataSummary = $this->itemRepository->getRegisterSurveySummary($items,'register-room', $property_id, 0 , $locationData->area_id, $location_id );

                $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
                $breadcrumb_name = 'properties_location';
                $breadcrumb_data = $locationData;
                $acm_type = 'register-room';

                //log audit
                $comment = \Auth::user()->full_name  . " viewed Property Register Room/Location "  . $locationData->location_reference .' on ' . $propertyData->name;
                \CommonHelpers::logAudit(LOCATION_TYPE, $locationData->id, AUDIT_ACTION_VIEW, $locationData->location_reference, $locationData->survey_id ,$comment, 0 ,$locationData->property_id);
                break;
            default:
                if($user->is_site_operative == 1){
                    // list area operative
                    return $this->areaOperative($propertyData, $risk_type_one, $risk_type_two);
                }
                //get all areas
                //get decommissioned (1) areas
                $dataTab = $this->propertyRepository->getPropertyArea($property_id);
                $dataDecommisstionTab= $this->propertyRepository->getPropertyArea($property_id, 1);
                $items = Item::with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $property_id)->where('survey_id', 0)->get();

                $dataSummary = $this->itemRepository->getRegisterSurveySummary($items,'register', $property_id);
                $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
                $breadcrumb_name = 'properties';
                $breadcrumb_data = $propertyData;
                $acm_type = 'register';
                //log audit
                 $comment = \Auth::user()->full_name . " viewed Property " . $propertyData->name;
                \CommonHelpers::logAudit(PROPERTY_TYPE, $propertyData->id, AUDIT_ACTION_VIEW, $propertyData->property_reference, $propertyData->client_id, $comment, 0 , $propertyData->id);
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
                    $title = 'Area/Floors All ACM Items Summary';
                } elseif($section == SECTION_ROOM_LOCATION_SUMMARY) {
                    $title = 'Room/Locations All ACM Items Summary';
                } else {
                    $title = 'Property All ACM Items Summary';
                }
                //log audit
                $comment = \Auth::user()->full_name  . " viewed $title table on " . $propertyData->name;

                $table_id = 'property-all-acm-items';
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
                $items_summary_table = $this->itemRepository->countNoACMItems($acm_type , 0, $property_id, isset($area_id) ? $area_id : 0,isset($location_id) ? $location_id : 0 );
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
                    $items_summary_table = $this->itemRepository->countInaccessibleRooms('registerarea',$property_id, 0,$area_id );
                } else {
                    $items_summary_table = $this->itemRepository->countInaccessibleRooms('register',$property_id);
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
            $comment = \Auth::user()->full_name  . " viewed $title table on property" . $propertyData->name;
            \CommonHelpers::logAudit(LOCATION_TYPE, $propertyData->id, AUDIT_ACTION_VIEW, $propertyData->reference, 0 ,$comment, 0 ,$propertyData->id);
        }
        $breadcrumb_data->table_title = $title;

        $projects = $this->propertyRepository->getPropertyProject($property_id);
        $decommissioned_reason_prop = DecommissionReason::where('type', 'property')->where('parent_id', 1)->get();

        if (!\CommonHelpers::isSystemClient()) {
            $canAddNewSurvey = $this->propertyRepository->isWinnerSurveyContractor($property_id, \Auth::user()->client_id);
        } else {
            $canAddNewSurvey = $canBeUpdateThisSite and \CompliancePrivilege::checkUpdatePermission(SURVEYS_PROP_UPDATE_PRIV);
        }

        return view('property.detail',['propertyData' => $propertyData,
            'contactUser' => $contactUser,
            'surveys' => $surveys,
            'decommissionedSurveys' => $decommissionedSurveys,
            'projects' => $projects,
            'breadcrumb_name' => $breadcrumb_name,
            'breadcrumb_data' => $breadcrumb_data,
            'section' => $section,
            'dataTab' => $dataTab,
            'dataDecommisstionTab' => $dataDecommisstionTab,
            'dataDecommisstionItems' => $dataDecommisstionItems,
            'dataSummary' => $dataSummary,
            'areaData' => $areaData,
            'locationData' => $locationData,
            'items_summary_table' => $items_summary_table,
            'pagination' => $pagination,
            'type' => $type,
            'title' => $title,
            'table_id' => $table_id,
            'risk_type_one' => $risk_type_one,
            'risk_type_two' => $risk_type_two,
            'warning_message' => $warning_message,
            'historical_doc_type' => $historical_doc_type,
            'canAddNewSurvey' => $canAddNewSurvey,
            'is_client_user' => $is_client_user,
            'canBeUpdateThisSite' => $canBeUpdateThisSite,
            'pagination_type' => $pagination_type,
            'locationStamping' => $locationStamping,
            'areaStamping' => $areaStamping,
            'decommissioned_reason_prop' => $decommissioned_reason_prop,
            'active_tab' => $active_tab,
            'position' => $request->position ?? 0,
            'category' => $request->category ?? 0,
            'parent_property' => $parent_property,
            'child_property' => $child_property
        ]);
    }

    public function areaOperative($propertyData, $risk_type_one, $risk_type_two)
    {
        $areaData = $this->areaRepository->getAreaOperative($propertyData, $risk_type_one, $risk_type_two);
        $breadcrumb_name = 'properties';
        $breadcrumb_data = $propertyData;
        //log audit
        $comment = \Auth::user()->full_name  . " viewed as operative Property" . $propertyData->name;
        \CommonHelpers::logAudit(PROPERTY_TYPE, $propertyData->id, AUDIT_ACTION_VIEW, $propertyData->property_reference, $propertyData->client_id, null, 0 , $propertyData->id);
        // detail_operative
        return  view('property.detail_area_operative',['propertyData' => $propertyData,
            'breadcrumb_name' => $breadcrumb_name,
            'breadcrumb_data' => $breadcrumb_data,
            'areas' => $areaData,
            'risk_type_one' => $risk_type_one,
            'risk_type_two' => $risk_type_two
        ]);
    }

    public function locationOperative($areaData, $propertyData, $risk_type_one, $risk_type_two){

        $locations = $this->locationRepository->getLocationOperative($areaData, $propertyData, $risk_type_one, $risk_type_two);
        // log audit
        $comment = \Auth::user()->full_name  . " viewed as operative Property Register Area/Floor "  . $areaData->area_reference .' on ' . $propertyData->name;
        \CommonHelpers::logAudit(AREA_TYPE, $areaData->id, AUDIT_ACTION_VIEW, $areaData->area_reference, $areaData->survey_id ,$comment, 0 ,$areaData->property_id);

        $breadcrumb_name = 'properties_area';
        $breadcrumb_data = $areaData;
        return  view('property.detail_location_operative',['areaData' => $areaData,
            'breadcrumb_name' => $breadcrumb_name,
            'breadcrumb_data' => $breadcrumb_data,
            'locations' => $locations,
            'properties' => $propertyData,
            'risk_type_one' => $risk_type_one,
            'risk_type_two' => $risk_type_two
        ]);
    }

    public function itemOperative($location_id, $propertyData, $risk_type_one, $risk_type_two){

        $locationData = $this->locationRepository->getLocationData($location_id);
        $is_inacc_void_location = $this->locationRepository->hasInaccessibleVoid($locationData);
        $items = $this->itemRepository->getItemOperative($locationData, $propertyData);

        //log audit
        $comment = \Auth::user()->full_name  . " viewed as operative Property Register Room/Location "  . $locationData->location_reference .' on ' . $propertyData->name;
        \CommonHelpers::logAudit(LOCATION_TYPE, $locationData->id, AUDIT_ACTION_VIEW, $locationData->location_reference, $locationData->survey_id ,$comment, 0 ,$locationData->property_id);
        // register now is able to have no acm items
//        $items_noacm_survey = $this->locationRepository->getItemNoAcmSurvey($locationData->record_id);
//
//        $items = $items->merge($items_noacm_survey);

        $breadcrumb_name = 'properties_location';
        $breadcrumb_data = $locationData;
        return  view('property.detail_item_operative',['location' => $locationData,
            'breadcrumb_name' => $breadcrumb_name,
            'breadcrumb_data' => $breadcrumb_data,
            'items' => $items,
            'properties' => $propertyData,
            'is_inacc_void_location' => $is_inacc_void_location,
            'risk_type_one' => $risk_type_one,
            'risk_type_two' => $risk_type_two
        ]);
    }

    public function propertyPlans($property_id) {
         $propertyData = $this->propertyRepository->getPropertyPlans($property_id);
         $data = ['data'=> ''];
         return json_encode($data);
    }
    /**
     * view/edit a property
     * @return
     */
    public function editProperty($property_id) {
        //check privilege
        // \CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $property_id);

        $property = $this->propertyRepository->findWhere(['id' => $property_id])->first();
        $client_id = $property->client_id;
        $zone_id = $property->zone_id;
        $zone =  $this->zoneRepository->findWhere(['id' => $zone_id])->first();
        $zone_parent_id = $zone->parent_id;

        $clients_list = $this->propertyRepository->getListClients();
        $client = $this->clientRepository->findWhere(['id'=> $client_id])->first();
        $service_area = $this->propertyRepository->getAllServiceArea();
        $ward = $this->propertyRepository->getAllWard();
        $asset_class = $this->propertyRepository->getAllAssetClass(true);
        $asset_type = $this->propertyRepository->getAllAssetClass(false);
        $tenure_type = $this->propertyRepository->getAllTenureType();
        $communal_area = $this->propertyRepository->getAllCommunalArea();
        $responsibility = $this->propertyRepository->getAllResponsibility();

        $parent_property = $this->propertyRepository->getParentProperty($property->parent_id);

        $list_contact = [];
        $risk_types = $this->propertyRepository->getAllRiskType();
        $programme_types = $this->propertyRepository->getAllProgrammeType();

        $list_users = $this->clientRepository->getClientUsers($client_id);
        $primaryUses = $this->propertyRepository->loadDropdownText(PRIMARY_AND_SECONDARY_USE_ID);
        $allPropertyDropdowns = $this->propertyRepository->getAllPropertyDropdownTitle();
        $dataDropdowns = [];

        foreach ($allPropertyDropdowns as $key => $propertyDropdown) {
            $tmp['description'] = $propertyDropdown->name;
            $tmp['name'] = $propertyDropdown->key_name;
            $tmp['value'] = $this->propertyRepository->getPropertyDropdownData($propertyDropdown->id);
            $tmp['selected'] = [
                isset($property->propertySurvey->electrical_meter) ? $property->propertySurvey->electrical_meter : 0,
                isset($property->propertySurvey->gas_meter) ? $property->propertySurvey->gas_meter : 0,
                isset($property->propertySurvey->loft_void) ? $property->propertySurvey->loft_void : 0,
            ] ;
            $dataDropdowns[] = $tmp;
        }

        return view('property.property_edit',[
            'list_contact' => $list_contact,
            'risk_types' => $risk_types,
            'programme_types' => $programme_types,
            'clients_list' => $clients_list,
            'client' => $client,
            'list_users' => $list_users,
            'primaryUses' => $primaryUses,
            'dataDropdowns' => $dataDropdowns,
            'client_id' => $client_id,
            'zone_id' => $zone_id,
            'zone_parent_id' => $zone_parent_id,
            'property' => $property,
            'service_area' => $service_area,
            'asset_class' => $asset_class,
            'asset_type' => $asset_type,
            'tenure_type' => $tenure_type,
            'communal_area' => $communal_area,
            'responsibility' => $responsibility,
            'parent_property' => $parent_property,
            'ward' => $ward
        ]);
    }

    /**
     * submit edit property form
     * Request $request
     */
    public function postEditProperty($property_id, PropertyCreateRequest $propertyCreateRequest){
        $validatedData = $propertyCreateRequest->validated();
        $updateProperty = $this->propertyRepository->createProperty($validatedData, $property_id);

        if (isset($updateProperty) and !is_null($updateProperty)) {
            if ($updateProperty['status_code'] == 200) {
                return redirect()->route('property_detail', ['id' => $updateProperty['data']->id, 'section' => SECTION_DEFAULT])->with('msg', $updateProperty['msg']);
            } else {
                return redirect()->back()->with('err', $updateProperty['msg']);
            }
        }
    }

    public function getAddProperty(Request $request) {
        $client_id = $request->client_id;
        $zone_id = $request->zone_id;
        $breadcrumb_data =  $this->zoneRepository->findWhere(['id' => $zone_id])->first();

        $clients_list = $this->propertyRepository->getListClients();
        $client = $this->clientRepository->findWhere(['id'=> $client_id])->first();//wcc
        $service_area = $this->propertyRepository->getAllServiceArea();
        $ward = $this->propertyRepository->getAllWard();
        $asset_class = $this->propertyRepository->getAllAssetClass(true);
        $asset_type = $this->propertyRepository->getAllAssetClass(false);
        $tenure_type = $this->propertyRepository->getAllTenureType();
        $communal_area = $this->propertyRepository->getAllCommunalArea();
        $responsibility = $this->propertyRepository->getAllResponsibility();

        $list_contact = [];
        $risk_types = $this->propertyRepository->getAllRiskType();
        $programme_types = $this->propertyRepository->getAllProgrammeType();

        $list_users = $this->clientRepository->getClientUsers($client_id);
        $primaryUses = $this->propertyRepository->loadDropdownText(PRIMARY_AND_SECONDARY_USE_ID);
        $allPropertyDropdowns = $this->propertyRepository->getAllPropertyDropdownTitle();
        $dataDropdowns = [];
        foreach ($allPropertyDropdowns as $key => $propertyDropdown) {
            $tmp['description'] = $propertyDropdown->name;
            $tmp['name'] = $propertyDropdown->key_name;
            $tmp['value'] = $this->propertyRepository->getPropertyDropdownData($propertyDropdown->id);
            $tmp['selected'] = [
            ] ;
            $dataDropdowns[] = $tmp;
        }

        return view('property.property_add',[
            'list_contact' => $list_contact,
            'risk_types' => $risk_types,
            'programme_types' => $programme_types,
            'clients_list' => $clients_list,
            'client' => $client,
            'list_users' => $list_users,
            'primaryUses' => $primaryUses,
            'dataDropdowns' => $dataDropdowns,
            'client_id' => $client_id,
            'zone_id' => $zone_id,
            'zone_parent_id' => $breadcrumb_data->parent_id,
            'breadcrumb_data' => $breadcrumb_data,
            'service_area' => $service_area,
            'ward' => $ward,
            'asset_class' => $asset_class,
            'asset_type' => $asset_type,
            'tenure_type' => $tenure_type,
            'communal_area' => $communal_area,
            'responsibility' => $responsibility
        ]);
    }

    public function postAddProperty(PropertyCreateRequest $propertyCreateRequest) {
        $validatedData = $propertyCreateRequest->validated();
        $createProperty = $this->propertyRepository->createProperty($validatedData);

        if (isset($createProperty) and !is_null($createProperty)) {
            if ($createProperty['status_code'] == 200) {
                return redirect()->route('property_detail', ['id' => $createProperty['data']->id, 'section' => SECTION_DEFAULT])->with('msg', $createProperty['msg']);
            } else {
                return redirect()->back()->with('err', $createProperty['msg']);
            }
        }
    }

    public function listGroupByClient(Request $request) {
        $client_id = $request->client_id;
        $parent_id = $request->parent_id ?? 0;
        $zoneList = $this->zoneRepository->findWhere(['client_id' => $client_id, 'parent_id' => $parent_id])->all();
        return response()->json($zoneList);
    }

    public function updateOrCreatePropertyPlan(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'property_id' => 'required',
            'survey_id' => 'required',
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
            $propertyPlan = $this->propertyRepository->updateOrCreatePropertyPlan($request->all(), $request->id);
        } else {
            $propertyPlan = $this->propertyRepository->updateOrCreatePropertyPlan($request->all());
        }

        if (isset($propertyPlan) and !is_null($propertyPlan)) {
            \Session::flash('msg', $propertyPlan['msg']);
            return response()->json(['status_code' => $propertyPlan['status_code'], 'success'=> $propertyPlan['msg'], 'id' => $propertyPlan['data']]);
        }
    }

    public function updateOrCreateHistoricalCategory(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'property_id' => 'required',
            'category_title' => 'required|string|max:255',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->id == 0) {
            $historicalCat = $this->propertyRepository->updateOrCreateHistoricalCategory($request->all());
        } else {
            $historicalCat = $this->propertyRepository->updateOrCreateHistoricalCategory($request->all(), $request->id);
        }


        if (isset($historicalCat) and !is_null($historicalCat)) {
            \Session::flash('msg', $historicalCat['msg']);
            return response()->json(['status_code' => $historicalCat['status_code'], 'success'=> $historicalCat['msg'], 'id' => $historicalCat['data']]);
        }
    }

    public function createHistoricalDoc(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'property_id' => 'required',
            'name' => 'required|max:255',
            'category' => 'required',
            'is_external_ms' => 'nullable',
            'document_type' => 'required',
            'document' => 'required_without:id|file|max:100000',
            'historic_date' => 'nullable|date_format:d/m/Y',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->has('id')) {
            $historicalDoc = $this->propertyRepository->updateOrCreateHistoricalDoc($request->all(), $request->id);
        } else {
            $historicalDoc = $this->propertyRepository->updateOrCreateHistoricalDoc($request->all());
        }

        if (isset($historicalDoc) and !is_null($historicalDoc)) {
            \Session::flash('msg', $historicalDoc['msg']);
            return response()->json(['status_code' => $historicalDoc['status_code'], 'success'=> $historicalDoc['msg'], 'id' => $historicalDoc['data']]);
        }
    }

    public function decommissionProperty($item_id, Request $request) {
        $decommissioned_reason =  $request->has('decommissioned_reason_prop') ? $request->decommissioned_reason_prop : 0;
        $decommissionProperty = $this->propertyRepository->decommissionProperty($item_id, $decommissioned_reason);
        if (isset($decommissionProperty)) {
            if ($decommissionProperty['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionProperty['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionProperty['msg']);
            }
        }
    }

    public function searchProperty(Request $request) {
        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        $data = $this->propertyRepository->searchProperty($query_string);
        return response()->json($data);
    }

    public function searchPropertyParent(Request $request) {
        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        $data = $this->propertyRepository->searchProperty($query_string, 0, 'parent');
        return response()->json($data);
    }

    public function getPropertyArea(Request $request) {
        $property_id = null;
        if ($request->has('property_id')) {
            $property_id = $request->property_id;
        }
        $data = $this->propertyRepository->getPropertyArea($property_id);
        return response()->json($data);
    }

    public function getPropertyLocation(Request $request) {
        $property_id = null;
        if ($request->has('property_id')) {
            $property_id = $request->property_id;
        }
        $data = $this->propertyRepository->getPropertyLocation($property_id);
        return response()->json($data);
    }

    public function getPropertyInformation(Request $request) {
        $property_id = $request->property_id ?? 0;
        $property = Property::find($property_id);
        $number_positive = Item::where('property_id',$property_id)->where('decommissioned',0)->where('survey_id',0)->where('state','!=',1)->first();
        $zone_ids = Zone::where('zone_name','like','%Domestic%')->orWhere('zone_name','like','%Communal%')->pluck('id')->toArray();
        $zone_ids = count($zone_ids) > 0 ? implode(",", $zone_ids) : '';
        $data = [
            'asset_type' => $property->assetType->description ?? '',
            'asset_type_id' => $property->assetType->id ?? '',
            'access_type' => $property->propertySurvey->propertyProgrammeType->description ?? null,
            'size_bedrooms' => $property->propertySurvey->size_bedrooms ?? null,
            'prop_name' => $property->name ?? '',
            'prop_ref' => $property->property_reference ?? '',
            'number_positive' => is_null($number_positive) ? 0 : 1,
            'responsibility_id' => $property->responsibility_id ?? '',
            'domestic_property_ids' => $zone_ids,
            'group_id' => $property->zone_id,
        ];
        return response()->json($data);
    }

    //Admin tool search
    public function getPropertySurveyAdminTool(Request $request) {
        $data = $this->propertyRepository->getPropertySurveyAdminTool($request->property_id);
        return response()->json($data);
    }

    public function getPropertyProjectAdminTool(Request $request) {
        $data = $this->propertyRepository->getPropertyProjectAdminTool($request->property_id);
        return response()->json($data);
    }

    public function getZoneAdminTool() {
        $data = $this->propertyRepository->getZoneAdminTool();
        return response()->json($data);
    }

    public function getPropertyAreaAdminTool(Request $request) {
        $data = $this->propertyRepository->getPropertyAreaAdminTool($request->property_id, 0, $request->survey_id);
        return response()->json($data);
    }

     public function getPropertyLocationAdminTool(Request $request) {
         // object_id = area_id
        $data = $this->propertyRepository->getPropertyLocationAdminTool($request->area_id, 0, $request->survey_id, $request->is_locked);
         return response()->json($data);
     }

    public function getPropertyItemAdminTool(Request $request) {
        // object_id = location_id
        $data = $this->propertyRepository->getPropertyItemAdminTool($request->location_id, 0, $request->survey_id);
        return response()->json($data);
    }

    public function searchDocument(Request $request) {
        $data = $this->propertyRepository->getDocumentAdminTool($request->type, $request->survey_id, $request->property_id, $request->project_id, $request->incident_id);
        return response()->json($data);
    }

    public function getPropertySurvey(Request $request) {
        $property_id = null;
        $client_id = null;
        if ($request->has('property_id')) {
            $property_id = $request->property_id;
        }
        if ($request->has('client_id')) {
            $client_id = $request->client_id;
        }
        $data = $this->propertyRepository->getPropertySurvey($property_id, 0, $client_id);
        return response()->json($data);
    }

    public function getPropertyAudit(Request $request) {
        $property_id = null;
        $client_id = null;
        if ($request->has('property_id')) {
            $property_id = $request->property_id;
        }
        if ($request->has('client_id')) {
            $client_id = $request->client_id;
        }
        $data = $this->propertyRepository->getPropertyAudit($property_id, 0, $client_id);
        return response()->json($data);
    }

    public function getPropertyProject(Request $request) {
        $property_id = null;
        if ($request->has('property_id')) {
            $property_id = $request->property_id;
        }
        $data = $this->propertyRepository->getPropertyProject2($property_id);
        return response()->json($data);
    }

    public function getPropertyDocument(Request $request) {
        $property_id = null;
        if ($request->has('property_id')) {
            $property_id = $request->property_id;
        }
        $data = $this->propertyRepository->getPropertyDocument($property_id);
        return response()->json($data);
    }
}

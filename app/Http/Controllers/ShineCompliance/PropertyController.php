<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Helpers\CommonHelpers;
use App\Http\Request\ShineCompliance\Property\HistoricalDocumentRequest;
use App\Http\Request\ShineCompliance\System\DocumentRequest;
use App\Repositories\ShineCompliance\ClientRepository;
use App\Services\ShineCompliance\ClientService;
use App\Services\ShineCompliance\IncidentReportService;
use App\Services\ShineCompliance\SystemService;
use App\Services\ShineCompliance\UserService;
use App\Services\ShineCompliance\ZoneService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Request\ShineCompliance\Property\PropertyCreateRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\ShineCompliance\PropertyService;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Null_;

class PropertyController extends Controller
{
    private $propertyService;
    private $zoneService;
    private $clientService;
    private $userService;
    private $systemService;
    private $incidentReportService;

    public function __construct(PropertyService $propertyService, ZoneService $zoneService, ClientService $clientService,
                                UserService $userService, SystemService $systemService, IncidentReportService $incidentReportService)
    {
        $this->propertyService = $propertyService;
        $this->zoneService = $zoneService;
        $this->clientService = $clientService;
        $this->userService = $userService;
        $this->systemService = $systemService;
        $this->incidentReportService = $incidentReportService;
    }

    public function index(){

    }

    public function detail(Request $request,$id){
        if(\Auth::user()->is_site_operative == 1) {
            return redirect()->route('property.operative.detail', ['id' => $id]);
        }

        $session = $request->session()->get('property_session') ?? [];
        $property_session = 0;
        if (in_array($id, $session) == false)
        {
            $request->session()->push('property_session', $id);
            $property_session = 1;
        }

        //check privilege
        if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $id) and \CommonHelpers::isSystemClient()) {
            abort(401);
        }


        if($property = $this->propertyService->getProperty($id, ['parents'])){
            //for client

            if (\CommonHelpers::isSystemClient()) {
                $canBeUpdateThisSite = false;
                if (\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $id) and \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION,JR_UPDATE_DETAILS)) {
                    $canBeUpdateThisSite = true;
                }
            } else if (\CommonHelpers::isClientUser()) {
                $canBeUpdateThisSite = true;
                if ($property->client_id != \Auth::user()->client_id) {
                     abort(403);
                }
            } else {
                $canBeUpdateThisSite = false;
            }

            $warning_message = $this->propertyService->getWarningMessagev3($property);


            $parent_property = $this->propertyService->getParentProperty($property->parent_id);
            $contactUser = $this->propertyService->getPropertyContacts($property);
            $decommissioned_reason_prop = $this->propertyService->getAllPropDecommissionReason();

            //audit
            $comment = \Auth::user()->full_name . " viewed  property detail " . $property->name;

            \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->parent_id, $comment);
            return view('shineCompliance.properties.detail', compact('canBeUpdateThisSite', 'property','warning_message','parent_property','contactUser','decommissioned_reason_prop','property_session'));

        }
        abort(404);
    }

    public function logWarning(Request $request){
        $property_id = $request->property_id ?? 0;
        $type = $request->type ?? 0;
        $warning = $request->warning ?? 0;
        $property = $this->propertyService->getProperty($property_id, ['parents']);
        if($warning == WARNING_ALERT){
            switch ($type) {
                case DANGER_BANNERS_TYPE:
                    $comment = \Auth::user()->full_name . " viewed Dynamic Red Warning dropdown " . $property->name;
                    break;
                case WARNING_BANNERS_TYPE:
                    $comment = \Auth::user()->full_name . " viewed Dynamic Amber Warning dropdown " . $property->name;
                    break;
                case SUCCESS_BANNERS_TYPE:
                    $comment = \Auth::user()->full_name . " viewed Dynamic Green Warning dropdown " . $property->name;
                    break;
                case INFO_BANNERS_TYPE:
                    $comment = \Auth::user()->full_name . " viewed Dynamic Blue Warning dropdown " . $property->name;
                    break;
            }
        }else{
            switch ($type) {
                case DANGER_BANNERS_TYPE:
                    $comment = \Auth::user()->full_name . " viewed Red Warning dropdown " . $property->name;
                    break;
                case WARNING_BANNERS_TYPE:
                    $comment = \Auth::user()->full_name . " viewed Amber Warning dropdown " . $property->name;
                    break;
                case SUCCESS_BANNERS_TYPE:
                    $comment = \Auth::user()->full_name . " viewed Green Warning dropdown " . $property->name;
                    break;
                case INFO_BANNERS_TYPE:
                    $comment = \Auth::user()->full_name . " viewed Blue Warning dropdown " . $property->name;
                    break;
            }
        }


        \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, NULL, $comment);
    }
    public function getAddProperty($zone_id, $property_id = NULL){

        if(!$zone = $this->zoneService->getZone($zone_id,['clients'])){
            abort(404);
        }
        $parent = $this->propertyService->getProperty($property_id);
        $clients_list = $this->propertyService->getListClients();
        $client = $zone->clients;
        $asset_class = $this->propertyService->getAllAssetClass(true);
        $asset_type = $this->propertyService->getAllAssetClass(false);
        $tenure_type = $this->propertyService->getAllTenureType();
        $communal_area = $this->propertyService->getAllCommunalArea();
        $responsibility = $this->propertyService->getAllResponsibility();
        $region = $this->propertyService->getAllRegion();
        $local_authority = $this->propertyService->getAllLocalAuthority();
        $building_category = $this->propertyService->getAllBuildingCategory();
        $division = $this->propertyService->getAllDivision();
        $service_area = $this->propertyService->getAllServiceArea();

        $list_contact = [];
        $risk_types = $this->propertyService->getAllRiskType();
        $programme_types = $this->propertyService->getAllProgrammeType();

        $list_users = $this->userService->getClientUsers($client->id);
        $primaryUses = $this->propertyService->loadDropdownText(PRIMARY_AND_SECONDARY_USE_ID);
        $dataDropdowns = $this->propertyService->getAllPropertyDropdownTitle();
        $propertyStatus = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PROPERTY_STATUS_ID);
        $propertyOccupied = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PROPERTY_OCCUPIED_ID);
        $listedBuilding = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_LISTED_BUILDING_ID);
        $parkingArrangements = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PARKING_ARRANGEMENTS_ID);
        $constructionMaterials = $this->propertyService->getAllConstructionMaterials();
        $evacuationStrategyDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_EVACUATION_STRATEGY_ID);
        $floorDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_FLOOR_ID);
        $stairDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_STAIR_ID);
        $wallConstructionDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_WALL_CONSTRUCTION_ID);
        $wallFinishDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_WALL_FINISH_ID);
        $vulnerableTypes = $this->propertyService->getVulnerableTypes();
        $comment = \Auth::user()->full_name . " viewed property add page on zone" . $zone->zone_name;
        \CommonHelpers::logAudit(PROPERTY_TYPE, $zone->id, AUDIT_ACTION_VIEW, $zone->reference, $zone->id, $comment);

        return view('shineCompliance.properties.property_add',[
            'list_contact' => $list_contact,
            'risk_types' => $risk_types,
            'programme_types' => $programme_types,
            'clients_list' => $clients_list,
            'client' => $client,
            'list_users' => $list_users,
            'primaryUses' => $primaryUses,
            'dataDropdowns' => $dataDropdowns,
            'client_id' => $client->id,
            'zone_id' => $zone_id,
            'zone_parent_id' => $zone->parent_id,
            'breadcrumb_data' => $zone,
            'asset_class' => $asset_class,
            'asset_type' => $asset_type,
            'region' => $region,
            'local_authority' => $local_authority,
            'building_category' => $building_category,
            'division' => $division,
            'tenure_type' => $tenure_type,
            'communal_area' => $communal_area,
            'responsibility' => $responsibility,
            'service_area' => $service_area,
            'parent' => $parent,
            'propertyStatus' => $propertyStatus,
            'propertyOccupied' => $propertyOccupied,
            'listedBuilding' => $listedBuilding,
            'parkingArrangements' => $parkingArrangements,
            'vulnerableTypes' => $vulnerableTypes,
            'constructionMaterials' => $constructionMaterials,
            'floorDropdown' => $floorDropdown,
            'stairDropdown' => $stairDropdown,
            'wallConstructionDropdown' => $wallConstructionDropdown,
            'wallFinishDropdown' => $wallFinishDropdown,
            'evacuationStrategyDropdown' => $evacuationStrategyDropdown,
        ]);
    }

    public function postAddProperty(PropertyCreateRequest $propertyCreateRequest){
        $validatedData = $propertyCreateRequest->validated();

        $createProperty = $this->propertyService->createProperty($validatedData);

        if (isset($createProperty) and !is_null($createProperty)) {
            if ($createProperty['status_code'] == 200) {
                return redirect()->route('shineCompliance.property.property_detail', ['id' => $createProperty['data']->id])->with('msg', $createProperty['msg']);
            } else {
                return redirect()->back()->with('err', $createProperty['msg']);
            }
        }
    }

    /**
     * view/edit a property
     * @return
     */
    public function getEditProperty($id) {
        if($property = $this->propertyService->getProperty($id, ['parents','clients'])){
            $clients_list = $this->propertyService->getListClients();
            $client = $property->clients;
            $asset_class = $this->propertyService->getAllAssetClass(true);
            $asset_type = $this->propertyService->getAllAssetClass(false);
            $tenure_type = $this->propertyService->getAllTenureType();
            $communal_area = $this->propertyService->getAllCommunalArea();
            $responsibility = $this->propertyService->getAllResponsibility();
            $region = $this->propertyService->getAllRegion();
            $local_authority = $this->propertyService->getAllLocalAuthority();
            $building_category = $this->propertyService->getAllBuildingCategory();
            $division = $this->propertyService->getAllDivision();
            $service_area = $this->propertyService->getAllServiceArea();
            $list_contact = [];
            $risk_types = $this->propertyService->getAllRiskType();
            $programme_types = $this->propertyService->getAllProgrammeType();

            $list_users = $this->userService->getClientUsers($client->id);
            $primaryUses = $this->propertyService->loadDropdownText(PRIMARY_AND_SECONDARY_USE_ID);
            $dataDropdowns = $this->propertyService->getAllPropertyDropdownTitle($property);
            $propertyStatus = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PROPERTY_STATUS_ID);
            $propertyOccupied = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PROPERTY_OCCUPIED_ID);
            $listedBuilding = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_LISTED_BUILDING_ID);
            $parkingArrangements = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PARKING_ARRANGEMENTS_ID);
            $vulnerableTypes = $this->propertyService->getVulnerableTypes();
            $constructionMaterials = $this->propertyService->getAllConstructionMaterials();
            $evacuationStrategyDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_EVACUATION_STRATEGY_ID);;
            $floorDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_FLOOR_ID);
            $stairDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_STAIR_ID);
            $wallConstructionDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_WALL_CONSTRUCTION_ID);
            $wallFinishDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_WALL_FINISH_ID);

            if(!is_null($property->parents)){
                $parent_id = $property->parents->id ?? '';
            }else{
                $parent_id = $property->zone->id ?? '';
            }
            $comment = \Auth::user()->full_name . " viewed property edit page " . $property->name;
            \CommonHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $parent_id, $comment);
            return view('shineCompliance.properties.property_edit',[
                'list_contact' => $list_contact,
                'risk_types' => $risk_types,
                'programme_types' => $programme_types,
                'clients_list' => $clients_list,
                'client' => $client,
                'list_users' => $list_users,
                'primaryUses' => $primaryUses,
                'dataDropdowns' => $dataDropdowns,
                'client_id' => $client->id,
                'zone_id' => $property->zone_id ?? 0,
                'zone_parent_id' => $property->zone->parent_id ?? 0,
                'breadcrumb_data' => $property->zone,
                'asset_class' => $asset_class,
                'asset_type' => $asset_type,
                'region' => $region,
                'local_authority' => $local_authority,
                'building_category' => $building_category,
                'division' => $division,
                'tenure_type' => $tenure_type,
                'communal_area' => $communal_area,
                'responsibility' => $responsibility,
                'parent_property' => $property->parents,
                'service_area' => $service_area,
                'property' => $property,
                'propertyStatus' => $propertyStatus,
                'propertyOccupied' => $propertyOccupied,
                'listedBuilding' => $listedBuilding,
                'parkingArrangements' => $parkingArrangements,
                'vulnerableTypes' => $vulnerableTypes,
                'constructionMaterials' => $constructionMaterials,
                'evacuationStrategyDropdown' => $evacuationStrategyDropdown,
                'floorDropdown' => $floorDropdown,
                'stairDropdown' => $stairDropdown,
                'wallConstructionDropdown' => $wallConstructionDropdown,
                'wallFinishDropdown' => $wallFinishDropdown,
            ]);
        }
        abort(404);
    }
    /**
     * submit edit property form
     * Request $request
     */
    public function postEditProperty($property_id, PropertyCreateRequest $propertyCreateRequest){
        if(!$property = $this->propertyService->getProperty($property_id, ['parents'])){
            abort(404);
        }
        $validatedData = $propertyCreateRequest->validated();
        $updateProperty = $this->propertyService->createProperty($validatedData, $property_id);

        if (isset($updateProperty) and !is_null($updateProperty)) {
            if ($updateProperty['status_code'] == 200) {
                return redirect()->route('shineCompliance.property.property_detail', ['id' => $updateProperty['data']->id])->with('msg', $updateProperty['msg']);
            } else {
                return redirect()->back()->with('err', $updateProperty['msg']);
            }
        }
    }

    public function getSubProperty($property_id, Request $request){
        if(!$property = $this->propertyService->getProperty($property_id, ['subProperty'])){
            abort(404);
        }

        $can_add_new = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
        } else if (\CommonHelpers::isClientUser()) {
            if ($property->client_id != \Auth::user()->client_id) {
                $can_add_new = false;
            }
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id)) {
                $can_add_new = false;
            }
        }

//        $sub_properties = $property->subProperty()->orderBy('pblock')->paginate(PAGINATION_DEFAULT);
        $sub_properties = $this->propertyService->getSubProperty($property_id, $request->q ?? '', PAGINATION_DEFAULT);
        if($request->ajax()) {
            return view('shineCompliance.properties.sub_property',compact('property','sub_properties','can_add_new'))->render();
        }
        $comment = \Auth::user()->full_name . " viewed sub property page on property" . $property->name;
        \ComplianceHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->id, $comment);
        return view('shineCompliance.properties.sub_property',compact('property','sub_properties','can_add_new'));
    }

    public function listDocument($property_id){
        if(!$property = $this->propertyService->getProperty($property_id, ['subProperty'])){
            abort(404);
        }
        $system_types = $this->systemService->getAllSystemType();
//        $documents = $this->systemService->listDocumentProperty($property_id, $system_types, ['equipment','programme','system']);
        $parent_document_types = $this->systemService->getAllParentDocumentsType();
        $categories_with_documents = $this->systemService->getAllCategoryDocument($property_id);
        $systems = $this->systemService->listRegisterSystemProperty($property_id, ['documents']);
        $document_types = $this->systemService->getAllDocumentsType();
        $document_statuses = $this->systemService->getAllDocumentStatuses();
        $documents_no_parent = $this->systemService->getAllDocumentsNoParent($property_id);
        $can_add_new = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
        } elseif(\CommonHelpers::isSystemClient()) {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_DOCUMENTS) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id)) {
                $can_add_new = false;
            }
        }

        // log audit
        \ComplianceHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference);

        return view('shineCompliance.properties.documents.index',
            compact('property','system_types','document_types','parent_document_types','can_add_new',
                    'categories_with_documents',
                    'systems', 'document_statuses', 'documents_no_parent'));
    }

    public function getComplianceDocumentType(Request $request){
        $main_document_id = $request->id ?? 0;
        $child_document_types = $this->systemService->getAllDocumentsTypeByParent($main_document_id);
        return response()->json(['status_code' => 200, 'data' => $child_document_types ?? []]);
    }

    //for system/equipment/programme
    public function listDocumentByType($id, Request $request){
        $type = $request->type ?? '';
        $data = $this->systemService->listDocumentByType($id, $type, ['equipment','programme','system']);
        $documents = $data[0];
        $view = $data[1];
        $object = $data[2];

        $can_add_new = true;
        $can_update = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
            $can_update = false;
        } elseif(\CommonHelpers::isSystemClient()) {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_DOCUMENTS) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $object->property_id)) {
                $can_add_new = false;
                $can_update = false;
            }
        }

        $categories_with_documents = $this->systemService->getAllCategoryDocument($object->property_id ?? 0);
        $document_types = $this->systemService->getAllDocumentsType();
        $parent_document_types = $this->systemService->getAllParentDocumentsType();
        $document_statuses = $this->systemService->getAllDocumentStatuses();
        return view($view,compact('can_update', 'can_add_new', 'documents','object','document_types','parent_document_types','categories_with_documents', 'document_statuses'));
    }

    public function postAddDocument(Request $request){
        $validator = Validator::make($request->all(),[
            'id' => 'nullable',
            'property_id' => 'required',
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'type' => 'required',
            'type_other' => 'nullable',
            'date' => 'nullable|date_format:d/m/Y',
            'document' => 'nullable|file|max:25600',
            'parent_type' => 'nullable',
            'property_system' => 'nullable',
            'property_programme' => 'nullable',
            'property_equipment' => 'nullable',
            'enforcement_deadline' => 'nullable|date_format:d/m/Y',
            'document_status' => 'nullable',
        ]);
        if ($validator->fails()){
            return redirect()->back()->with('err', $validator->errors()->first());
        }
        if(!$property = $this->propertyService->getProperty($request['property_id'])){
            return redirect()->back()->with('err', 'Error has occurred. Please try again later!');
        }
        $createOrUpdateDocument = $this->systemService->createOrUpdateDocument($request, $property);

        if (isset($createOrUpdateDocument) and !is_null($createOrUpdateDocument)) {
            if ($createOrUpdateDocument['status_code'] == 200) {
                return redirect()->back()->with('msg', $createOrUpdateDocument['msg']);
            } else {
                return redirect()->back()->with('err', $createOrUpdateDocument['msg']);
            }
        }
    }

    public function postEditDocument($document_id, Request $request){
        $validator = Validator::make($request->all(),[
            'id' => 'nullable',
            'property_id' => 'required',
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'type' => 'required',
            'type_other' => 'nullable',
            'date' => 'nullable|date_format:d/m/Y',
            'document' => 'nullable|file|max:25600',
            'parent_type' => 'nullable',
            'property_system' => 'nullable',
            'property_programme' => 'nullable',
            'property_equipment' => 'nullable',
            'enforcement_deadline' => 'nullable|date_format:d/m/Y',
            'document_status' => 'nullable',
        ]);
        if ($validator->fails()){
            return redirect()->back()->with('err', $validator->errors()->first());
        }
        if(!$property = $this->propertyService->getProperty($request['property_id'])){
            return redirect()->back()->with('err', 'Error has occurred. Please try again later!');
        }

        $createOrUpdateDocument = $this->systemService->createOrUpdateDocument($request, $property, $document_id);

        if (isset($createOrUpdateDocument) and !is_null($createOrUpdateDocument)) {
            if ($createOrUpdateDocument['status_code'] == 200) {
                return redirect()->back()->with('msg', $createOrUpdateDocument['msg']);
            } else {
                return redirect()->back()->with('err', $createOrUpdateDocument['msg']);
            }
        }
    }
    //historical document
    public function postAddHistoricalDocument(HistoricalDocumentRequest $documentRequest){
        if(!$property = $this->propertyService->getProperty($documentRequest['property_id'])){
            return redirect()->back()->with('err', 'Error has occurred. Please try again later!');
        }
        $createOrUpdateDocument = $this->propertyService->updateOrCreateHistoricalDocument($documentRequest, $property);

        if (isset($createOrUpdateDocument) and !is_null($createOrUpdateDocument)) {
            if ($createOrUpdateDocument['status_code'] == 200) {
                return redirect()->back()->with('msg', $createOrUpdateDocument['msg']);
            } else {
                return redirect()->back()->with('err', $createOrUpdateDocument['msg']);
            }
        }
    }

    public function postEditHistoricalDocument(){

    }
    //category
    public function updateHistoricalCategory($id, Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'property_id' => 'required',
            'category_title' => 'required|string|max:255',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $historicalCat = $this->propertyService->updateOrCreateHistoricalCategory($request->all(), $request->id);

        if ($historicalCat['status_code'] == 200) {
            return redirect()->back()->with('msg', $historicalCat['msg']);
        } else {
            return redirect()->back()->with('err', $historicalCat['msg']);
        }
    }

    public function createHistoricalCategory(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'property_id' => 'required',
            'category_title' => 'required|string|max:255',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $historicalCat = $this->propertyService->updateOrCreateHistoricalCategory($request->all(), $request->id);

        if ($historicalCat['status_code'] == 200) {
            return redirect()->back()->with('msg', $historicalCat['msg']);
        } else {
            return redirect()->back()->with('err', $historicalCat['msg']);
        }
    }

    public function getComplianceSystem(Request $request){
        $systems = $this->systemService->listSystemProperty($request->property_id);
        return response()->json($systems);
    }

    public function getComplianceProgramme(Request $request){
        $programme = $this->systemService->listProgrammeProperty($request->property_id, $request->system_id);
        return response()->json($programme);
    }

    public function getComplianceEquipment(Request $request){
        $equipment = $this->systemService->listEquimentProperty($request->property_id, $request->system_id);
        return response()->json($equipment);
    }

    public function decommissionProperty($property_id, Request $request) {
        $decommissioned_reason =  $request->has('decommissioned_reason_prop') ? $request->decommissioned_reason_prop : 0;
        $decommissionProperty = $this->propertyService->decommissionProperty($property_id, $decommissioned_reason);
        if (isset($decommissionProperty)) {
            if ($decommissionProperty['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionProperty['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionProperty['msg']);
            }
        }
    }

    public function listGroupByClient(Request $request) {
        $zoneList = $this->zoneService->listGroupByClient($request->client_id, $request->parent_id ?? 0);
        return response()->json($zoneList);
    }

    public function propertyFireExitAndAssemblyPoint($property_id, Request $request)
    {
        if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $property_id) and \CommonHelpers::isSystemClient()) {
            abort(401);
        }
        $can_add_new = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
        } elseif(\CommonHelpers::isSystemClient()) {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_FIRE_EXITS_ASSEMBLY_POINTS) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id)) {
                $can_add_new = false;
            }
        }
        $data = $this->propertyService->getPropertyFireExitAndAssemblyPoint($property_id, 9,$request);
        $property = $this->propertyService->getProperty($property_id);

        //todo search ajax
        if($request->ajax()) {
            return view('shineCompliance.properties.fireExit', compact('data', 'property_id', 'property', 'can_add_new'))->render();
        }
        return view('shineCompliance.properties.fireExit', ['data' => $data, 'property_id' => $property_id, 'property' => $property, 'can_add_new' => $can_add_new]);
    }

    public function addFireExitAndAssemblyPoint($property_id, Request $request) {
        $type = $request->methodStyle ?? '';

        if ($type == 'fire') {
            return redirect()->route('shineCompliance.assessment.get_add_fire_exit', ['property_id' => $property_id]);
        } else {
            return redirect()->route('shineCompliance.assessment.get_add_assembly_point', ['property_id' => $property_id]);
        }
    }

    public function registerOverall($property_id, Request $request) {
        if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $property_id) and \CommonHelpers::isSystemClient()) {
            abort(401);
        }
        $permission = $this->propertyService->getPropertyRegisterPermission();
        $property = $this->propertyService->getProperty($property_id);
        $type = null;

        $register_data = $this->propertyService->getRegisterOverallSummary($property_id, $permission['asbestos'], $permission['fire'], $permission['water'], $permission['health_and_safety']);

        $decommission_register_data = $this->propertyService->getDecomissionedRegisterOverallSummary($property_id);

        return view('shineCompliance.properties.register.overall', ['property_id' => $property_id,
         'property' => $property,
         'asbestos' => $permission['asbestos'],
         'overall' => $permission['overall'],
         'fire' => $permission['fire'],
         'water' => $permission['water'],
         'hs' => $permission['health_and_safety'],
         'health_and_safety' => $permission['health_and_safety'],
         'register_data' => $register_data,
         'decommission_register_data' => $decommission_register_data,
         'type' => $type
        ]);
    }

    public function registerAsbestos($property_id, Request $request) {
        $property = $this->propertyService->getProperty($property_id);
        $type = ASBESTOS;
        $register_data = $this->propertyService->getRegisterSummary($property_id);
        $decommission_register_data = $this->propertyService->getRegisterSummary($property_id, 1);

        $section = $request->section ?? false;
        $permission = $this->propertyService->getPropertyRegisterPermission();
        $section_data = $this->propertyService->getRiskItem($property_id, $section, $request->decommissioned ?? 0);
        $data_summary = $section_data['data'];
        $property->breadcrumb_title = $section_data['breadcrumb'];
        return view('shineCompliance.properties.register.asbestos', [
            'property_id' => $property_id,
            'asbestos' => $permission['asbestos'],
            'overall' => $permission['overall'],
            'fire' => $permission['fire'],
            'water' => $permission['water'],
            'health_and_safety' => $permission['health_and_safety'],
            'property' => $property,
            'register_data' => $register_data,
            'decommission_register_data' => $decommission_register_data,
            'section' => $section,
            'section_data' => $section_data,
            'data_summary' => $data_summary,
            'type' => $type
        ]);
    }

    public function registerFire($property_id, Request $request) {
        $property = $this->propertyService->getProperty($property_id);
        $type = FIRE;
        $register_data = $this->propertyService->getHazardRegisterSummary($property_id,ASSESSMENT_FIRE_TYPE);

        $decommission_register_data = $this->propertyService->getHazardRegisterSummary($property_id, 1);
        $decommission_register_data = $this->propertyService->getHazardRegisterSummary($property_id,ASSESSMENT_FIRE_TYPE, 1);
        $permission = $this->propertyService->getPropertyRegisterPermission();
        $can_add_new  = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new  = false;
        } elseif(\CommonHelpers::isSystemClient()){
            // check update permission for fire
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_REGISTER_FIRE_HAZARDS, JOB_ROLE_FIRE) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id)) {
                $can_add_new = false;
            }
        }

        $section = $request->section ?? false;

        $section_data = $this->propertyService->getRiskHazard($property_id, $section, ASSESSMENT_FIRE_TYPE, $request->decommissioned ?? 0);
        $section_hazard = $section_data['hazards'];
        $property->breadcrumb_title = $section_data['breadcrumb'];
        return view('shineCompliance.properties.register.fire', ['property_id' => $property_id,
            'asbestos' => $permission['asbestos'],
            'overall' => $permission['overall'],
            'fire' => $permission['fire'],
            'water' => $permission['water'],
            'health_and_safety' => $permission['health_and_safety'],
            'property' => $property,
            'register_data' => $register_data,
            'can_add_new' => $can_add_new,
            'decommission_register_data' => $decommission_register_data,
            'section_hazard' => $section_hazard,
            'section' => $section,
            'type' => $type
        ]);
    }

    public function registerGas($property_id, Request $request) {
        $property = $this->propertyService->getProperty($property_id);
        $type = GAS;
        return view('shineCompliance.properties.register.gas', ['property_id' => $property_id, 'property' => $property,'type' => $type]);
    }

    public function registerWater($property_id, Request $request) {
        $property = $this->propertyService->getProperty($property_id);
        $type = WATER;
        $register_data = $this->propertyService->getHazardRegisterSummary($property_id, ASSESSMENT_WATER_TYPE);
        $decommission_register_data = $this->propertyService->getHazardRegisterSummary($property_id, ASSESSMENT_WATER_TYPE, 1);
        $permission = $this->propertyService->getPropertyRegisterPermission();
        $can_add_new  = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new  = false;
        } elseif(\CommonHelpers::isSystemClient()){
            // check update permission for fire
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_REGISTER_WATER_HAZARDS, JOB_ROLE_WATER) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id)) {
                $can_add_new = false;
            }
        }

        $section = $request->section ?? false;

        $section_data = $this->propertyService->getRiskHazard($property_id, $section,ASSESSMENT_WATER_TYPE, $request->decommissioned ?? 0);
        $section_hazard = $section_data['hazards'];
        $property->breadcrumb_title = $section_data['breadcrumb'];
        return view('shineCompliance.properties.register.water', ['property_id' => $property_id,
            'property' => $property,
            'asbestos' => $permission['asbestos'],
            'overall' => $permission['overall'],
            'fire' => $permission['fire'],
            'water' => $permission['water'],
            'health_and_safety' => $permission['health_and_safety'],
            'register_data' => $register_data,
            'can_add_new' => $can_add_new,
            'decommission_register_data' => $decommission_register_data,
            'section_hazard' => $section_hazard,
            'section' => $section,
            'type' => $type
        ]);
    }

    public function registerHealthAndSafety($property_id, Request $request) {
        $section = $request->section ?? false;
        $property = $this->propertyService->getProperty($property_id);
        $permission = $this->propertyService->getPropertyRegisterPermission();
        $type = HS;
//        $incident_reports = $this->incidentReportService->getIncidentReportsProperty($property_id);
//        $decommissioned_incident_reports = $this->incidentReportService->getIncidentReportsProperty($property_id, INCIDENT_DECOMMISSIONED);
        $register_data = $this->propertyService->getHazardRegisterSummary($property_id, ASSESSMENT_HS_TYPE);
        $decommission_register_data = $this->propertyService->getHazardRegisterSummary($property_id, ASSESSMENT_HS_TYPE, 1);
        $can_add_new  = true;
        $section_data = $this->propertyService->getRiskHazard($property_id, $section,ASSESSMENT_HS_TYPE, $request->decommissioned ?? 0);
        $section_hazard = $section_data['hazards'];
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new  = false;
        } elseif(\CommonHelpers::isSystemClient()){
            // check update permission for fire
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_REGISTER_FIRE_HAZARDS, JOB_ROLE_FIRE) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id)) {
                $can_add_new = false;
            }
        }

        return view('shineCompliance.properties.register.health_and_safety', ['property_id' => $property_id,
            'property' => $property,
            'asbestos' => $permission['asbestos'],
            'overall' => $permission['overall'],
            'fire' => $permission['fire'],
            'water' => $permission['water'],
            'health_and_safety' => $permission['health_and_safety'],
//            'incident_reports' => $incident_reports,
//            'decommissioned_incident_reports' => $decommissioned_incident_reports,
            'section' => $section,
            'section_hazard' => $section_hazard,
            'register_data' => $register_data,
            'decommission_register_data' => $decommission_register_data,
            'can_add_new' => $can_add_new,
            'type' => $type
        ]);
    }

    public function getEquipmentSystem(Request $request) {
        $property_id = $request->property_id;
        $property = $this->propertyService->getProperty($property_id);
        $result = [];
        if(!$property->equipments->isEmpty()) {
            $result['equipments'] = $property->equipments;
        }
        if(!$property->systems->isEmpty()) {
            $result['systems'] = $property->equipments;
        }
        return response()->json($result);
    }
}

<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\LocationRepository;
use App\Repositories\ShineCompliance\ItemRepository;
use App\Repositories\ShineCompliance\ZoneRepository;
use App\Repositories\ShineCompliance\LocationInfoRepository;
use App\Services\ShineCompliance\ItemService;

class LocationService{

    private $locationRepository;
    private $itemRepository;
    private $zoneRepository;
    private $itemService;
    private $locationInfoRepository;

    public function __construct(LocationRepository $locationRepository,
                                ItemRepository $itemRepository,
                                ZoneRepository $zoneRepository,
                                ItemService $itemService,
                                LocationInfoRepository $locationInfoRepository
    ){
        $this->locationRepository = $locationRepository;
        $this->itemRepository = $itemRepository;
        $this->zoneRepository = $zoneRepository;
        $this->itemService = $itemService;
        $this->locationInfoRepository = $locationInfoRepository;
    }

    public function getLocation($id) {
        return $this->locationRepository->find($id);
    }

    public function getLocationInArea($area_id, $request, $limit = 9) {
        return $this->locationRepository->getLocationInArea($area_id, $request, $limit);
    }

    public function getLocationDropdown($ids) {
        return $this->locationRepository->getLocationDropdown($ids);
    }

    public function getDropdownById($ids) {
        return $this->locationRepository->getDropdownById($ids);
    }

    public function updateLocationInfo($record_id,$data) {
        return $this->locationInfoRepository->updateLocationInfo($record_id,$data);
    }

    public function getLocationVoidsData($locationVoids) {
        //push location void to array
        $locationVoidsData = [];
        foreach ($locationVoids as $locationVoid ) {
            $dataVoid['id'] = $locationVoid->id;
            $dataVoid['description'] = $locationVoid->description;
            $dataVoid['name'] = str_replace(' ', '-', $locationVoid->description);
            $parents = $this->locationRepository->getDropdownById($locationVoid->id);

            foreach ($parents as $key => $parent) {
                $dataVoid['parents'][$key]['id'] = $parent->id;
                $dataVoid['parents'][$key]['description'] = $parent->description;
                $dataVoid['parents'][$key]['name'] = str_replace(' ', '-', $parent->description);
                if (!is_null($parent->allChildrenAccounts)) {
                    $dataVoid['parents'][$key]['childs'] = $parent->allChildrenAccounts;
                }
            }

            $locationVoidsData[] = $dataVoid;
        }
        return $locationVoidsData;
    }

    public function getLocationContructionsData($locationContructions) {

        $locationContructionsDatas = [];
        //push location contruction to array
        foreach ($locationContructions as $locationContruction ) {
            $dataContruction['id'] = $locationContruction->id;
            $dataContruction['description'] = $locationContruction->description;
            $dataContruction['name'] = str_replace(' ', '-', $locationContruction->description);
            $dataContruction['childs'] = $this->locationRepository->getDropdownById($locationContruction->id);
            $locationContructionsDatas[] = $dataContruction;
        }

        return $locationContructionsDatas;
    }

    public function getLocationSelectedVoidsData($locationVoids, $location) {

        $locationVoidsData = [];
        //push location void to array
        foreach ($locationVoids as $locationVoid ) {
            $dataVoid['id'] = $locationVoid->id;
            $dataVoid['description'] = $locationVoid->description;
            $dataVoid['name'] = str_replace(' ', '-', $locationVoid->description);
            $parents = $this->locationRepository->getDropdownById($locationVoid->id);

            // get selected void
            switch ($locationVoid->id) {
                case LOCATION_CEILING_VOID:
                    $dataVoid['selected_parent'] = $this->getLocationVoidId($location->locationVoid->ceiling ?? '','parent');
                    $dataVoid['selected_child'] = $this->getLocationVoidId($location->locationVoid->ceiling ?? '');
                    $dataVoid['selected_other'] = $location->locationVoid->ceiling_other ?? '';
                    break;

                case LOCATION_CAVITIES:
                    $dataVoid['selected_parent'] = $this->getLocationVoidId($location->locationVoid->cavities ?? '','parent');
                    $dataVoid['selected_child'] = $this->getLocationVoidId($location->locationVoid->cavities ?? '');
                    $dataVoid['selected_other'] = $location->locationVoid->cavities_other ?? '';
                    break;

                case LOCATION_RISERS:
                    $dataVoid['selected_parent'] = $this->getLocationVoidId($location->locationVoid->risers ?? '','parent');
                    $dataVoid['selected_child'] = $this->getLocationVoidId($location->locationVoid->risers ?? '');
                    $dataVoid['selected_other'] = $location->locationVoid->risers_other ?? '';
                    break;

                case LOCATION_DUCTING:
                    $dataVoid['selected_parent'] = $this->getLocationVoidId($location->locationVoid->ducting ?? '','parent');
                    $dataVoid['selected_child'] = $this->getLocationVoidId($location->locationVoid->ducting ?? '');
                    $dataVoid['selected_other'] = $location->locationVoid->ducting_other ?? '';
                    break;

                case LOCATION_FLOOR_VOID:
                    $dataVoid['selected_parent'] = $this->getLocationVoidId($location->locationVoid->floor ?? '','parent');
                    $dataVoid['selected_child'] = $this->getLocationVoidId($location->locationVoid->floor ?? '');
                    $dataVoid['selected_other'] = $location->locationVoid->floor_other ?? '';
                    break;

                case LOCATION_BOXING:
                    $dataVoid['selected_parent'] = $this->getLocationVoidId($location->locationVoid->boxing ?? '','parent');
                    $dataVoid['selected_child'] = $this->getLocationVoidId($location->locationVoid->boxing ?? '');
                    $dataVoid['selected_other'] = $location->locationVoid->boxing_other ?? '';
                    break;

                case LOCATION_PIPEWORK:
                    $dataVoid['selected_parent'] = $this->getLocationVoidId($location->locationVoid->pipework ?? '','parent');
                    $dataVoid['selected_child'] = $this->getLocationVoidId($location->locationVoid->pipework ?? '');
                    $dataVoid['selected_other'] = $location->locationVoid->pipework_other ?? '';
                    break;

                default:
                    $dataVoid['selected_parent'] = [];
                    $dataVoid['selected_child'] = [];
                    $dataVoid['selected_other'] = [];
                    break;
            }

            foreach ($parents as $key => $parent) {
                $dataVoid['parents'][$key]['id'] = $parent->id;
                $dataVoid['parents'][$key]['description'] = $parent->description;
                $dataVoid['parents'][$key]['name'] = str_replace(' ', '-', $parent->description);
                if (!is_null($parent->allChildrenAccounts)) {
                    $dataVoid['parents'][$key]['childs'] = $parent->allChildrenAccounts;
                }
            }

            $locationVoidsData[] = $dataVoid;
        }

        return $locationVoidsData;
    }

    public function getLocationSelectedContructionsData($locationContructions, $location) {
        $locationContructionsDatas = [];
        //push location contruction to array
        foreach ($locationContructions as $locationContruction ) {
            $dataContruction['id'] = $locationContruction->id;
            $dataContruction['description'] = $locationContruction->description;
            $dataContruction['name'] = str_replace(' ', '-', $locationContruction->description);
            $dataContruction['childs'] = $this->locationRepository->getDropdownById($locationContruction->id);

            // get selected contruction
            switch ($locationContruction->id) {
                case LOCATION_CEILING:
                    $dataContruction['selected'] = $this->getLocationContructionId($location->locationConstruction->ceiling ?? '');
                    $dataContruction['selected_other'] = $location->locationConstruction->ceiling_other ?? '';
                    break;

                case LOCATION_WALLS:
                    $dataContruction['selected'] = $this->getLocationContructionId($location->locationConstruction->walls ?? '');
                    $dataContruction['selected_other'] = $location->locationConstruction->walls_other ?? '';
                    break;

                case LOCATION_FLOOR:
                    $dataContruction['selected'] = $this->getLocationContructionId($location->locationConstruction->floor ?? '');
                    $dataContruction['selected_other'] = $location->locationConstruction->floor_other ?? '';
                    break;

                case LOCATION_DOORS:
                    $dataContruction['selected'] = $this->getLocationContructionId($location->locationConstruction->doors ?? '');
                    $dataContruction['selected_other'] = $location->locationConstruction->doors_other ?? '';
                    break;

                case LOCATION_WINDOWS:
                    $dataContruction['selected'] = $this->getLocationContructionId($location->locationConstruction->windows ?? '');
                    $dataContruction['selected_other'] = $location->locationConstruction->windows_other ?? '';
                    break;

                default:
                    $dataContruction['selected'] = [];
                    $dataContruction['selected_other'] = [];
                    break;
            }
            $locationContructionsDatas[] = $dataContruction;
        }

        return $locationContructionsDatas;
    }

    public function updateOrCreateLocation($area_id, $data, $id = null) {
        try {

            // location void data process
            $ceilingVoid = isset($data['Ceiling-Void']) ? $data['Ceiling-Void'] : null;
            $ceilingVoidType = isset($data['Ceiling-Void-type']) ? $data['Ceiling-Void-type'] : null;
            $constructionCeilingVoid = \CommonHelpers::getMultiselectDataVoid($ceilingVoid , $ceilingVoidType);

            $cavities = isset($data['Cavities']) ? $data['Cavities'] : null;
            $cavitiesType = isset($data['Cavities-type']) ? $data['Cavities-type'] : null;
            $constructionCavities = \CommonHelpers::getMultiselectDataVoid($cavities , $cavitiesType);

            $risers = isset($data['Risers']) ? $data['Risers'] : null;
            $risersType = isset($data['Risers-type']) ? $data['Risers-type'] : null;
            $constructionRisers = \CommonHelpers::getMultiselectDataVoid($risers , $risersType);

            $ducting = isset($data['Ducting']) ? $data['Ducting'] : null;
            $ductingType = isset($data['Ducting-type']) ? $data['Ducting-type'] : null;
            $constructionDucting = \CommonHelpers::getMultiselectDataVoid($ducting , $ductingType);

            $floorVoid = isset($data['Floor-Void']) ? $data['Floor-Void'] : null;
            $floorVoidType = isset($data['Floor-Void-type']) ? $data['Floor-Void-type'] : null;
            $constructionFloorVoid = \CommonHelpers::getMultiselectDataVoid($floorVoid , $floorVoidType);

            $pipeWork = isset($data['Pipework']) ? $data['Pipework'] : null;
            $pipeWorkType = isset($data['Pipework-type']) ? $data['Pipework-type'] : null;
            $constructionPipework = \CommonHelpers::getMultiselectDataVoid($pipeWork , $pipeWorkType);

            $boxing = isset($data['Boxing']) ? $data['Boxing'] : null;
            $boxingType = isset($data['Boxing-type']) ? $data['Boxing-type'] : null;
            $constructionBoxing = \CommonHelpers::getMultiselectDataVoid($boxing , $boxingType);
            $reason_na  = \CommonHelpers::checkArrayKey($data,'reasons-na') ?? [];
            // $survey = Survey::with('surveySetting')->where('id', \CommonHelpers::checkArrayKey($data,'survey_id'))->first();
            $dataLocation = [
                'area_id'                      => \CommonHelpers::checkArrayKey($data,'area_id'),
                'survey_id'                    => \CommonHelpers::checkArrayKey($data,'survey_id'),
                'property_id'                  => \CommonHelpers::checkArrayKey($data,'property_id'),
                'is_locked'                    => 0,
                'state'                        => \CommonHelpers::checkArrayKey($data,'location-state'),
                'version'                      => 1,
                'description'                  => \CommonHelpers::checkArrayKey($data,'description'),
                'location_reference'           => \CommonHelpers::checkArrayKey($data,'reference'),
                'not_assessed'                  => \CommonHelpers::checkArrayKey($data,'not_assessed'),
                'not_assessed_reason'                  => \CommonHelpers::checkArrayKey($data,'not_assessed_reason'),
            ];

            $dataLocationInfo = [
                'reason_inaccess_key'          => end($reason_na),
                'reason_inaccess_other'        => \CommonHelpers::checkArrayKey($data,'reasons-na-other'),
                'comments'                     => \CommonHelpers::checkArrayKey($data,'location-comment'),
            ];

            $dataLocationVoid = [];

                $dataLocationVoid = [
                    'ceiling'      => $constructionCeilingVoid,
                    'ceiling_other' => \CommonHelpers::getLocationOther($data,'Ceiling-Void-type-other'),
                    'cavities'         => $constructionCavities,
                    'cavities_other'    => \CommonHelpers::getLocationOther($data,'Cavities-type-other'),
                    'risers'           => $constructionRisers,
                    'risers_other'      => \CommonHelpers::getLocationOther($data,'Risers-type-other'),
                    'ducting'          => $constructionDucting,
                    'ducting_other'     => \CommonHelpers::getLocationOther($data,'Ducting-type-other'),
                    'boxing'           => $constructionBoxing,
                    'boxing_other'      => \CommonHelpers::getLocationOther($data,'Boxing-type-other'),
                    'pipework'         => $constructionPipework,
                    'pipework_other'    => \CommonHelpers::getLocationOther($data,'Pipework-type-other'),
                    'floor'        => $constructionFloorVoid,
                    'floor_other'   => \CommonHelpers::getLocationOther($data,'Floor-Void-type-other'),
                ];

            $dataLocationContruction = [];

                $dataLocationContruction = [
                    'ceiling'          => \CommonHelpers::getMultiselectDataContruction(isset($data['Ceiling']) ? $data['Ceiling'] : null),
                    'ceiling_other'     => \CommonHelpers::checkArrayKey($data,'Ceiling-other'),
                    'walls'            => \CommonHelpers::getMultiselectDataContruction(isset($data['Walls']) ? $data['Walls'] : null),
                    'walls_other'       => \CommonHelpers::checkArrayKey($data,'Walls-other'),
                    'doors'            => \CommonHelpers::getMultiselectDataContruction(isset($data['Doors']) ? $data['Doors'] : null),
                    'doors_other'       => \CommonHelpers::checkArrayKey($data,'Doors-other'),
                    'floor'            => \CommonHelpers::getMultiselectDataContruction(isset($data['Floor']) ? $data['Floor'] : null),
                    'floor_other'       => \CommonHelpers::checkArrayKey($data,'Floor-other'),
                    'windows'          => \CommonHelpers::getMultiselectDataContruction(isset($data['Windows']) ? $data['Windows'] : null),
                    'windows_other'     => \CommonHelpers::checkArrayKey($data,'Windows-other'),
                ];

            \DB::beginTransaction();
            //create
            if (is_null($id)) {
                $dataLocation['decommissioned'] = 0;
                $dataLocation['created_by'] = \Auth::user()->id;
                $location = $this->locationRepository->create($dataLocation);
                $id = $location->id;

                $refLocation = "RL" . $id;
                $location->reference = $refLocation;
                $location->record_id =  $id;
                $location->save();

                $dataLocationInfo['location_id'] = $location->id;
                $dataLocationVoid['location_id'] = $location->id;
                $dataLocationContruction['location_id'] = $location->id;

                //log audit
                $comment_audit = \Auth::user()->full_name  . " added New Room/Location " . $refLocation .(isset($location->survey->reference) ? 'on '.$location->survey->reference : ''). ' on ' . ($location->property->name ?? '');
                \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_ADD, $refLocation, $location->survey_id ,$comment_audit, 0 ,$location->property_id);

                $message = 'Room/location Created Successfully!';
            // update
            } else {
                $dataLocation['updated_by'] = \Auth::user()->id;
                $this->locationRepository->update($dataLocation, $id);
                $location = $this->locationRepository->find($id);
                //log audit
                $comment_audit = \Auth::user()->full_name  . " edited Room/Location " . $location->reference .(isset($location->survey->reference) ? 'on '.$location->survey->reference : ''). ' on ' . ($location->property->name ?? '');
                \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_EDIT, $location->reference, $location->survey_id ,$comment_audit, 0 ,$location->property_id);

                $message  = 'Room/location Updated Successfully!';
            }
            // save image
            if (isset($data['location-photo'])) {
                $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['location-photo'], $id, LOCATION_IMAGE);
            }
            // store comment history
            \CommentHistory::storeCommentHistory('location', $location->id, $dataLocationInfo['comments']);

            // update or create relation
            $this->locationRepository->updateOrCreateLocationInfo($id, $dataLocationInfo);
            $this->locationRepository->updateOrCreateLocationVoid($id, $dataLocationVoid);
            $this->locationRepository->updateOrCreateLocationConstruction($id, $dataLocationContruction);


            \DB::commit();
            return \ComplianceHelpers::successResponse($message, $location);
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e);
            return \ComplianceHelpers::failResponse(STATUS_FAIL,'Failed to Create/Update Room/location. Please try again!');
        }
    }

    public function decommissionLocation($location_id, $reason) {
        $location = $this->locationRepository->find($location_id);
        $items = $this->itemRepository->getItembyLocation($location_id);
        try {
            \DB::beginTransaction();
            if ($location->decommissioned == LOCATION_DECOMMISSION) {

                $locations_de = $this->locationRepository->recommissionLocation($location_id, $reason);
                $items_de = $this->itemRepository->recommissionLocation($location_id, $reason);

                // store comment history
                \CommentHistory::storeDeccomissionHistory('recommission','location', $location_id, $reason, $location->survey->reference ?? null);

                //log audit
                if (!is_null($items)) {
                    foreach ($items as $item) {
                       $comment = \Auth::user()->full_name . " Recommission Item ".$item->reference." by Recommission Location " . $location->reference;
                       \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_RECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }
                \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_RECOMMISSION, $location->reference, $location->survey_id ,null, 0 ,$location->property_id);
                $response = \CommonHelpers::successResponse('Room/location Recommissioned  Successfully!');
            } else {

                $locations_de = $this->locationRepository->decommissionLocation($location_id , $reason);
                $items_de = $this->itemRepository->decommissionLocation($location_id);

                // store comment history
                \CommentHistory::storeDeccomissionHistory('decommission','location', $location_id, $reason, $location->survey->reference ?? null);
                //log audit
                //log audit
                if (!is_null($items)) {
                    foreach ($items as $item) {
                       $comment = \Auth::user()->full_name . " Decommission Item ".$item->reference." by Decommission Location " . $location->reference;
                       \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_DECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                    }
                }
                \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_DECOMMISSION, $location->reference, $location->survey_id ,null, 0 ,$location->property_id);
                $response = \CommonHelpers::successResponse('Room/location Decommissioned Successfully!');
            }
            \DB::commit();
            return $response;
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollBack();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Decommission/Recommission Room/location. Please try again!');
        }
    }

    public function getLocationVoidId($data, $type = 'child') {
        if (!is_null($data) and $data !== '') {
            $data = explode(",",$data);
            if ($type == 'parent') {
                return $data[0];
            } elseif($type == 'child') {
                if (count($data) > 1) {
                    $tmp = array_shift($data);
                    return $data;
                }
                return '';
            }
        } else {
            return '';
        }
    }

    public function getLocationContructionId($data) {
        if (!is_null($data) and $data !== '') {
            $data = explode(",",$data);
            return $data;
        } else {
            return '';
        }
    }

    public function getAssessmentLocation($assess_id, $property_id = 0, $relations = []){
        return $this->locationRepository->getAssessmentLocation($assess_id, $property_id, $relations);
    }

    public function getLocationSurveyDetail($location_id,$survey,$property_id,$request){
        $locationData = $this->locationRepository->getLocation($location_id);
        $survey_id = $survey->id ?? "";
        if (!$locationData) {
            abort(404);
        }
        //check privilege
        if (\CommonHelpers::isSystemClient()) {
            // property permission and register tab permission
            if(!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $locationData->property_id) || !\CompliancePrivilege::checkPermission(SURVEYS_PROP_VIEW_PRIV)){
                abort(404);
            }
        }

        if(isset($request->position)){
            //inaccessible will show only register data so will no need to check client_id, zone_id
            $list_locations = $this->getLocationPaginationCustomize($survey_id, $locationData->decommissioned, $locationData->property_id, $locationData->area_id, NULL, NULL, $request->category, $request->pagination_type);
            //set path
            $pagination = \CommonHelpers::setPathPagination($request, $list_locations, 'location', $locationData->id);
        }

        $dataTab = [];
        $dataDecommisstionTab = [];
        $items = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $locationData->property_id)->where('area_id', $locationData->area_id)->where('survey_id', $survey_id)->where('location_id', $location_id)->get();

        $dataSummary = $this->itemService->getRegisterSurveySummary($items,'survey-room', $property_id, $locationData->survey_id , $locationData->area_id, $location_id);

        $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
        $breadcrumb_name = 'survey_location_asbestos';
        $breadcrumb_data = $locationData;

        //log audit
        $comment = \Auth::user()->full_name  . " viewed Survey Room/Location "  . $locationData->reference .' on ' . $survey->reference . ' on ' . $survey->property->name;
        \CommonHelpers::logAudit(LOCATION_TYPE, $locationData->id, AUDIT_ACTION_VIEW, $locationData->reference, $locationData->survey_id ,$comment, 0 ,$locationData->property_id);
        $data = [
            'locationData' => $locationData,
            'items' => $items,
            'pagination' => $pagination ?? [],
            'dataTab' => $dataTab,
            'dataDecommisstionTab' => $dataDecommisstionTab,
            'dataSummary' => $dataSummary,
            'dataDecommisstionItems' => $dataDecommisstionItems,
            'breadcrumb_name' => $breadcrumb_name,
            'breadcrumb_data' => $breadcrumb_data
        ];
        return $data;
    }

    public function getLocationPaginationCustomize($survey_id, $decommissioned , $property_id, $area_id, $group_id ,$client_id = NULL, $type = NULL, $pagination_type = NULL) {
//        dd($survey_id, $decommissioned , $property_id, $area_id, $group_id ,$client_id , $type , $pagination_type );
        $list_condition = $this->getPaginationCondition($survey_id, $decommissioned , $property_id, $area_id, $group_id, $client_id, $type, $pagination_type);
//        dd($list_condition);
        $condition = count($list_condition['condition']) > 0 ? $list_condition['condition'] : TRUE;
        $other_condition = $list_condition['other_condition'] ? $list_condition['other_condition'] : TRUE ;
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        $location = $this->locationRepository->with('locationInfo', 'locationVoid', 'locationConstruction','decommissionedReason')
            ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
            ->where($condition)->whereRaw($other_condition)->get();
        return !$location->isEmpty() ? $location : [];
    }

    public function getPaginationCondition($survey_id, $decommissioned , $property_id, $area_id, $group_id ,$client_id = NULL, $type = NULL, $pagination_type = NULL){
        $condition = [];
        $other_condition = '';
        if($type && $type == TYPE_INACCESS_ROOM_SUMMARY && $pagination_type){
            //common condition
            $condition['state'] = LOCATION_STATE_INACCESSIBLE;
            $condition['decommissioned'] = LOCATION_UNDECOMMISSION;
            switch ($pagination_type){
                case TYPE_CLIENT:
                    $zone_ids = $this->zoneRepository->where(['client_id' => $client_id, 'parent_id' => 0])->get()->pluck('id')->toArray();
                    // property privilege
                    // if (\CommonHelpers::isSystemClient()) {
                    //     $property_id_privs =  \CompliancePrivilege::getPermission(PROPERTY_PERMISSION);
                    // } else {
                    //     $property_id_privs =    \CompliancePrivilege::getPropertyContractorPermission();
                    // }
                    // $client_property_ids = Property::where(['client_id' => $client_id, 'decommissioned' => 0])
                    //     ->whereIn('zone_id', $zone_ids)
                    //     ->whereIn('id', $property_id_privs)
                    //     ->pluck('id')->toArray();

                    // if (count($client_property_ids)) {
                    //     $property_ids = implode(" ,",$client_property_ids);
                    //     $other_condition = "property_id IN($property_ids)";
                    // }
                    $condition['survey_id'] =  0;
                    break;
                case TYPE_ZONE:
                    // property privilege
                    // if (\CommonHelpers::isSystemClient()) {
                    //     $property_id_privs =  \CompliancePrivilege::getPermission(PROPERTY_PERMISSION);
                    // } else {
                    //     $property_id_privs =    \CompliancePrivilege::getPropertyContractorPermission();
                    // }

                    // $client_property_group = Property::with('propertyInfo')
                    //     ->where(['decommissioned' => 0, 'zone_id'=> $group_id])
                    //     ->whereIn('id', $property_id_privs)->pluck('id')->toArray();

                    // if (count($client_property_group)) {
                    //     $property_ids = implode(" ,",$client_property_group);
                    //     $other_condition = "property_id IN($property_ids)";
                    // }
                    $condition['survey_id'] =  0;
                    break;
                case TYPE_REPORT:
                    $condition['survey_id'] =  $survey_id;
                    break;
                case TYPE_PROPERTY:
                    $condition['survey_id'] =  0;
                    $condition['property_id'] =  $property_id;
                    break;
                case TYPE_AREA:
                    $condition['survey_id'] =  $survey_id;
                    $condition['area_id'] =  $area_id;
                    break;
            }
        } else {
            //normal
            if(isset($location_id)){
                $condition['location_id'] = $location_id;
            }
            if(isset($decommissioned)){
                $condition['decommissioned'] =  $decommissioned;
            }
            if(isset($survey_id)){
                $condition['survey_id'] =  $survey_id;
            }
            if(isset($property_id)){
                $condition['property_id'] =  $property_id;
            }
            if(isset($area_id)){
                $condition['area_id'] =  $area_id;
            }
        }
//        dd($condition, $other_condition);
        return ['condition' => $condition, 'other_condition' => $other_condition];
    }
}

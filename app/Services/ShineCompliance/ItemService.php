<?php

namespace App\Services\ShineCompliance;

use App\Models\DropdownItem\ItemNoAccess;
use App\Repositories\ShineCompliance\ItemRepository;
use App\Repositories\ShineCompliance\LocationRepository;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\AsbestosTypeValueRepository;
use App\Repositories\ShineCompliance\ActionRecommendationValueRepository;

class ItemService{

    private $itemRepository;
    private $areaRepository;
    private $locationRepository;
    private $asbestosTypeValueRepository;
    private $actionRecommendationValueRepository;

    public function __construct(ItemRepository $itemRepository,
                                AreaRepository $areaRepository,
                                LocationRepository $locationRepository,
                                AsbestosTypeValueRepository $asbestosTypeValueRepository,
                                ActionRecommendationValueRepository $actionRecommendationValueRepository

    ){
        $this->itemRepository = $itemRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->asbestosTypeValueRepository = $asbestosTypeValueRepository;
        $this->actionRecommendationValueRepository = $actionRecommendationValueRepository;
    }

    public function getItem($id) {
        return $this->itemRepository->with('itemInfo', 'location', 'area', 'property', 'survey', 'client', 'zone')->where('id',$id)->first();
    }
    public function getItemSurvey($property_id,$survey_id) {
        return $this->itemRepository->with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $property_id)->where('survey_id', $survey_id)->get();
    }

    public function getItemOS($record_id,$survey_id){
        $itemOs = $this->itemRepository->getItemOs($record_id,$survey_id);
        return $itemOs ?? [];
    }

    public function getItemTableBySection($section, $id) {
        switch ($section) {
            case SECTION_AREA_FLOORS_SUMMARY:
                $area_id = $id;
                $areaData = $this->areaRepository->find($area_id);


                $dataTab = $this->locationRepository->getAreaLocation($area_id, $areaData->survey_id);
                $dataDecommisstionTab = $this->locationRepository->getAreaLocation($area_id, $areaData->survey_id, 1);
                $items = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $areaData->property_id)->where('survey_id', 0)->where('area_id', $area_id)->get();

                $dataSummary = $this->getRegisterSurveySummary($items,'registerarea', $areaData->property_id ?? 0, 0 , $area_id);

                $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
                $breadcrumb_name = 'properties_area';
                $breadcrumb_data = $areaData;
                $acm_type = 'registerarea';

                // log audit
               $comment = \Auth::user()->full_name  . " viewed Property Register Area/Floor "  . $areaData->area_reference .' on ' . $areaData->property->name ?? '';
                \ComplianceHelpers::logAudit(AREA_TYPE, $areaData->id, AUDIT_ACTION_VIEW, $areaData->area_reference, $areaData->survey_id ,$comment, 0 ,$areaData->property_id);
                break;

            case SECTION_ROOM_LOCATION_SUMMARY:
                $location_id = $id;
                $pagination_type = TYPE_LOCATION;
                $locationData = $this->locationRepository->find($location_id);


                $dataTab = [];
                $dataDecommisstionTab = [];
                $items = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $locationData->property_id)->where('area_id', $locationData->area_id)->where('survey_id', 0)->where('location_id', $location_id)->get();

                $dataSummary = $this->getRegisterSurveySummary($items,'register-room', $locationData->property_id, 0 , $locationData->area_id, $location_id );

                $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
                $breadcrumb_name = 'properties_location';
                $breadcrumb_data = $locationData;
                $acm_type = 'register-room';

                //log audit
                $comment = \Auth::user()->full_name  . " viewed Property Register Room/Location "  . $locationData->location_reference .' on ' . ($locationData->property->name ?? '');
                \ComplianceHelpers::logAudit(LOCATION_TYPE, $locationData->id, AUDIT_ACTION_VIEW, $locationData->location_reference, $locationData->survey_id ,$comment, 0 ,$locationData->property_id);
                break;
            default:
                //get all areas
                //get decommissioned (1) areas
                // $property_id = $id;
                // $dataTab = $this->propertyRepository->getPropertyArea($property_id);
                // $dataDecommisstionTab= $this->propertyRepository->getPropertyArea($property_id, 1);
                // $items = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView','decommissionedReason')->where('property_id', $property_id)->where('survey_id', 0)->get();

                // $dataSummary = $this->itemRepository->getRegisterSurveySummary($items,'register', $property_id);
                // $dataDecommisstionItems = $items->where('decommissioned', ITEM_DECOMMISSION)->all();
                // $breadcrumb_name = 'properties';
                // $breadcrumb_data = $propertyData;
                // $acm_type = 'register';

                // $comment = \Auth::user()->full_name . " viewed Property " . $propertyData->name;
                // \ComplianceHelpers::logAudit(PROPERTY_TYPE, $propertyData->id, AUDIT_ACTION_VIEW, $propertyData->property_reference, $propertyData->client_id, $comment, 0 , $propertyData->id);
                break;
        }
        return [
            'item' => $items,
            'dataSummary' => $dataSummary,
            'acm_type' => $acm_type,
            'dataDecommisstionItems' => $dataDecommisstionItems
        ];
    }

    public function getItemSummaryTable($type, $items, $acm_type, $property_id,$area_id, $location_id) {
        switch ($type) {
            case TYPE_All_ACM_ITEM_SUMMARY:
                $items_summary_table = $items->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', '!=', null)->where('state', '!=', ITEM_NOACM_STATE)->all();
                $title = 'Room/Locations All ACM Items Summary';
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

                $items_summary_table = $this->countNoACMItems($acm_type , 0, $property_id, $area_id ?? 0,$location_id ?? 0 );

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

        return [
            'items_summary_table' => $items_summary_table,
            'title' => $title,
            'table_id' => $table_id,
        ];
    }

    public function getRegisterSurveySummary ($item, $type, $property_id, $survey_id = 0, $area_id = 0, $location_id = 0, $zone_id = 0) {

        $counterAll = 0;
        $counterAll += $highRisk = $this->countRiskItem($item, 'high');
        $counterAll += $mediumRisk = $this->countRiskItem($item, 'medium');
        $counterAll += $lowRisk = $this->countRiskItem($item, 'low');
        $counterAll += $vlowRisk = $this->countRiskItem($item, 'vlow');

        $counterAll += $inaccessibleItems = $this->countInaccessibleItems($item);
        $noACMItems = count($this->countNoACMItems($type, $item,$property_id, $area_id, $location_id, $survey_id,$zone_id));
        if ($type !== 'register-room' and $type !== 'survey-room') {
            $inaccessibleRooms = count($this->countInaccessibleRooms($type, $property_id,$survey_id ,$area_id,$zone_id));
        }
        $source = url()->full();
        $dataSummary = [
            "All ACM Items" => [
                'number' => $counterAll,
                'link' => request()->fullUrlWithQuery(['type' => TYPE_All_ACM_ITEM_SUMMARY])
            ],
            "Inaccessible ACM Item Summary" => [
                'number' => $inaccessibleItems,
                'link' => request()->fullUrlWithQuery(['type' => TYPE_INACCESS_ACM_ITEM_SUMMARY])
            ],
            "High Risk ACM Item Summary" => [
                'number' => $highRisk,
                'link' => request()->fullUrlWithQuery(['type' => TYPE_HIGH_RISK_ITEM_SUMMARY])
            ],
            "Medium Risk ACM Item Summary" =>  [
                'number' => $mediumRisk,
                'link' => request()->fullUrlWithQuery(['type' => TYPE_MEDIUM_RISK_ITEM_SUMMARY])
            ],
            "Low Risk ACM Item Summary" => [
                'number' => $lowRisk,
                'link' => request()->fullUrlWithQuery(['type' => TYPE_LOW_RISK_ITEM_SUMMARY])
            ],
            "Very Low Risk ACM Item Summary" => [
                'number' => $vlowRisk,
                'link' => request()->fullUrlWithQuery(['type' => TYPE_VERY_LOW_RISK_ITEM_SUMMARY])
            ],
            "No Risk (NoACM) Item Summary" => [
                'number' => $noACMItems,
                'link' => request()->fullUrlWithQuery(['type' => TYPE_NO_RISK_ITEM_SUMMARY])
            ],
        ];
        if ($type !== 'register-room' and $type !== 'survey-room') {
            $dataSummary["Inaccessible Room/locations Summary"] = [
                    'number' => $inaccessibleRooms,
                    'link' => $source. '&type='. TYPE_INACCESS_ROOM_SUMMARY
                ];
        }

        return $dataSummary;
    }

    public function countRiskItem($item, $type){
        if (is_null($item)) {
            return [];
        }
        switch ($type) {
            case 'high':
                $items = $item->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[10, 99])->count();
                break;

            case 'medium':
                $items = $item->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[7, 9])->count();
                break;

            case 'low':
                $items = $item->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[5, 6])->count();
                break;

            case 'vlow':
                $items = $item->where('decommissioned', ITEM_UNDECOMMISSION)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[0, 4])->count();
                break;

            default:
                return 0;
                break;
        }

        return $items;
    }

    public function countInaccessibleItems($item) {
        if (is_null($item)) {
            return [];
        }
        $items = $item->where('state', ITEM_INACCESSIBLE_STATE)->where('decommissioned', ITEM_UNDECOMMISSION)->count();
        return $items;
    }

    public function countNoACMItems ($type , $item, $property_id, $area_id, $location_id, $survey_id = 0, $zone_id = 0) {
        switch ($type) {
                case 'register':
                    $data = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('property_id', $property_id)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id', 0)
                                        ->get();
                    break;
                case 'registerarea':
                    $data = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('area_id', $area_id)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id', 0)
                                        ->get();
                    break;
                case 'register-room':

                    $data = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('location_id', $location_id)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id', 0)
                                        ->get();

                    break;
                case 'zones':
                    $table_join_privs = \CompliancePrivilege::getPropertyPermission();
                    $data = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                                        ->join('tbl_property', function ($join) use ($zone_id) {
                                                $join->on('tbl_property.id', '=', 'property_id')
                                                     ->whereRaw("tbl_property.zone_id in ($zone_id)");
                                            })
                                        ->where('tbl_items.decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('tbl_items.survey_id', 0)
                                        ->get('tbl_items.*');

                    break;
                case 'survey':
                    $data = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id',$survey_id)
                                        ->get();
                    break;
                case 'surveyarea':
                    $data = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('area_id', $area_id)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id',$survey_id)
                                        ->get();
                    break;
                case 'survey-room':
                    $data = $this->itemRepository->with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('location_id', $location_id)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id',$survey_id)
                                        ->get();
                    break;
                default:
                    $data = [];
                    break;
        }

        return $data;
    }

    public function loadDropdownText($dropdown_item_id, $parent_id = 0) {
        return $this->itemRepository->loadDropdownText($dropdown_item_id, $parent_id);
    }

    public function getSamplesItem($property_id, $survey_id) {
        return $this->itemRepository->getSamplesItem($property_id, $survey_id);
    }

    public function updateOrCreateItem($data, $id =  null) {
            $total_risk = intval(\CommonHelpers::checkArrayKey2($data,'total-MAS')) + intval(\CommonHelpers::checkArrayKey2($data,'total-PAS'));
            $inspectionDate = $this->LoadInspectionFormula(time(), $total_risk);

            // No ACM item state
            if ($data['item-type'] == ITEM_NON_ASBESTOS_TYPE_ID) {
                $data['assessmentTypeKey'] = 0;
                $data['assessmentDamageKey'] = 0;
                $data['assessmentTreatmentKey'] = 0;
                $data['assessmentAsbestosKey'] = 0;
                $data['total-MAS'] = 0;
                $data['total-PAS'] = 0;

                $data['pasPrimary'] = 0;
                $data['pasSecondary'] = 0;
                $data['pasLocation'] = 0;
                $data['pasAccessibility'] = 0;
                $data['pasExtent'] = 0;
                $data['pasNumber'] = 0;
                $data['pasHumanFrequency'] = 0;
                $data['pasAverageTime'] = 0;
                $data['pasType'] = 0;
                $data['pasMaintenanceFrequency'] = 0;
                $total_risk = 0;
                $data['accessibility'] = ITEM_NOACM_STATE;
                $data['ActionsRecommendations'] = [];
            } else {
                if($data['accessibility'] == ITEM_ACCESSIBLE_STATE){
                    // assessment has only 2 value limit or full limit so need to fix it
                    $data['assessment'] = 0;
                }
                if ($data['assessment'] == ITEM_LIMIT_ASSESSMENT) {
                    $data['total-MAS'] = 12;
                    $total_risk = 12 + intval(\CommonHelpers::checkArrayKey2($data,'total-PAS'));
                }
            }
            if ($data['accessibility'] == ITEM_NOACM_STATE) {
                $data['total-MAS'] = 0;
                $data['total-PAS'] = 0;
                $total_risk = 0;
            }

            $dataItem = [
                'area_id'                       => \CommonHelpers::checkArrayKey($data,'area_id'),
                'survey_id'                     => \CommonHelpers::checkArrayKey($data,'survey_id'),
                'property_id'                   => \CommonHelpers::checkArrayKey($data,'property_id'),
                'location_id'                   => \CommonHelpers::checkArrayKey($data,'location_id'),
                'state'                         => \CommonHelpers::checkArrayKey3($data,'accessibility'),
                'version'                       => 1,
                'is_locked'                     => 0,
                'total_risk'                    => $total_risk,
                'total_mas_risk'                => \CommonHelpers::checkArrayKey3($data,'total-MAS'),
                'total_pas_risk'                => \CommonHelpers::checkArrayKey3($data,'total-PAS'),
                'name'                          => \CommonHelpers::checkArrayKey($data,'name'),
                'not_assessed'                  => \CommonHelpers::checkArrayKey($data,'not_assessed'),
                'not_assessed_reason'           => \CommonHelpers::checkArrayKey($data,'not_assessed_reason'),
            ];

            $dataItemInfo = [
                'extent' => \CommonHelpers::checkArrayKey($data,'asbestosQuantityValue'),
                'comment' => \CommonHelpers::checkArrayKey($data,'comments'),
                'assessment' => \CommonHelpers::checkArrayKey($data,'assessment'),
                'inspection_date' => $inspectionDate
            ];


            $dataItemInfo['is_r_and_d_element'] = (isset($data['rAndDElement'])) ? 1 : 0;

            try {
                \DB::beginTransaction();
                if (is_null($id)) {
                    $dataItem['decommissioned'] = 0;
                    $dataItem['created_by'] = \Auth::user()->id;
                    $item = $this->itemRepository->create($dataItem);

                    $record_id = $item->id;
                    $reference = 'IN'. $item->id;
                    //update item when create success
                    $item->update(['record_id' => $record_id, 'reference' => $reference]);

                    //log audit
                    $comment_audit = \Auth::user()->full_name  . " added new item " . $reference .(isset($item->survey->reference) ? 'on '.$item->survey->reference : ''). ' on ' . ($item->property->name ?? '');
                    \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_ADD, $reference, $item->survey_id ,$comment_audit, 0 ,$item->property_id);
                } else {
                    $item = $this->itemRepository->find($id);
                    $dataItem['updated_by'] = \Auth::user()->id;

                    $this->itemRepository->update($dataItem, $id);
                    //log audit
                    $comment_audit = \Auth::user()->full_name  . " edited item " . $item->reference .(isset($item->survey->reference) ? 'on '.$item->survey->reference : ''). ' on ' . ($item->property->name ?? '');
                    \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_EDIT, $item->reference, $item->survey_id ,$comment_audit, 0 ,$item->property_id);
                }

                if ($item) {
                    // store comment history
                    \CommentHistory::storeCommentHistory('item', $item->id, $dataItemInfo['comment']);
                    //create item Info
                    $this->itemRepository->updateOrCreateItemInfo($dataItemInfo, $item->id);
                    //create item image
                    if (isset($data['photoLocation'])) {
                        $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['photoLocation'], $item->id, ITEM_PHOTO_LOCATION);
                    }
                    if (isset($data['photoItem'])) {
                        $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['photoItem'], $item->id, ITEM_PHOTO);
                    }
                    if (isset($data['photoAdditional'])) {
                        $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['photoAdditional'], $item->id, ITEM_PHOTO_ADDITIONAL);
                    }

                    //details specific location
                    if (isset($data['specificLocations-other']) and $data['specificLocations-other'] != '') {
                        $this->itemRepository->insertDropdownValue($item->id, SPECIFIC_LOCATION_ID, 0,\CommonHelpers::checkArrayKey($data,'specificLocations1'),\CommonHelpers::checkArrayKey($data,'specificLocations-other'));
                    } else {
                        if (is_null(\CommonHelpers::checkArrayKey($data,'specificLocations3'))) {
                            $this->itemRepository->insertDropdownValue($item->id, SPECIFIC_LOCATION_ID, 0,\CommonHelpers::checkArrayKey($data,'specificLocations1'));
                        } else {
                            $this->itemRepository->insertDropdownValue($item->id, SPECIFIC_LOCATION_ID, 0,\CommonHelpers::checkArrayKey($data,'specificLocations3'));
                        }
                    }
                    //check sample
                    $dataSample = $this->handleSample($item->record_id, \CommonHelpers::checkArrayKey($data,'accessibility'), \CommonHelpers::checkArrayKey($data,'sample'), \CommonHelpers::checkArrayKey($data,'sample-other'), \CommonHelpers::checkArrayKey($data,'sample-other-comments'));
                    // insert sample link id
                    $this->itemRepository->insertDropdownValue($item->id, SAMPLE_ID, 0, $dataSample);
                    // update VRS item

                    $sampleItem = $this->itemRepository->findSample($dataSample);

                    if (!is_null($sampleItem)) {
                        if ($item->record_id == $sampleItem->original_item_id) {
                            $this->updateVRSSample($dataSample, $item->survey_id, \CommonHelpers::checkArrayKey($data,'abestosTypes'),\CommonHelpers::checkArrayKey($data,'AsbestosTypeMore'));
                        }
                    }

                    //details tab
                    $this->itemRepository->insertDropdownValue($item->id, PRODUCT_DEBRIS_TYPE_ID, 0,\CommonHelpers::checkArrayKey($data,'productDebris'),\CommonHelpers::checkArrayKey($data,'productDebris-other'));
                    $this->itemRepository->insertDropdownValue($item->id, ASBESTOS_TYPE_ID, 0,\CommonHelpers::checkArrayKey($data,'abestosTypes'),\CommonHelpers::checkArrayKey($data,'AsbestosTypeMore'));
                    $this->itemRepository->insertDropdownValue($item->id, LICENSED_NONLICENSED_ID, 0,\CommonHelpers::checkArrayKey($data,'LicensedNonLicensed'));


                    $this->itemRepository->insertDropdownValue($item->id, EXTENT_ID, 0,\CommonHelpers::checkArrayKey($data,'extent'));
                    $this->itemRepository->insertDropdownValue($item->id, ACCESSIBILITY_VULNERABILITY_ID, 0,\CommonHelpers::checkArrayKey($data,'AccessibilityVulnerability'));
                    $this->itemRepository->insertDropdownValue($item->id, ADDITIONAL_INFORMATION_ID, 0,\CommonHelpers::checkArrayKey($data,'AdditionalInformation'),\CommonHelpers::checkArrayKey($data,'AdditionalInformation-Other'));
                    $this->itemRepository->insertDropdownValue($item->id, ITEM_NO_ACCESS_ID, 0,\CommonHelpers::checkArrayKey($data,'reasons'),\CommonHelpers::checkArrayKey($data,'reasons-other'));

                    // mas tab
                    $this->itemRepository->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TYPE_KEY,\CommonHelpers::checkArrayKey($data,'assessmentTypeKey'));
                    $this->itemRepository->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_DAMAGE_KEY,\CommonHelpers::checkArrayKey($data,'assessmentDamageKey'));
                    $this->itemRepository->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TREATMENT_KEY,\CommonHelpers::checkArrayKey($data,'assessmentTreatmentKey'));
                    $this->itemRepository->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY,\CommonHelpers::checkArrayKey($data,'assessmentAsbestosKey'));

                    // pas tab with survey setting

                    $this->itemRepository->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY,\CommonHelpers::checkArrayKey($data,'pasPrimary'));
                    $this->itemRepository->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY,\CommonHelpers::checkArrayKey($data,'pasSecondary'));
                    $this->itemRepository->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_LOCATION_KEY,\CommonHelpers::checkArrayKey($data,'pasLocation'));
                    $this->itemRepository->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY,\CommonHelpers::checkArrayKey($data,'pasAccessibility'));
                    $this->itemRepository->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_EXTENT_KEY,\CommonHelpers::checkArrayKey($data,'pasExtent'));
                    $this->itemRepository->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_OCCUPANTS_KEY,\CommonHelpers::checkArrayKey($data,'pasNumber'));
                    $this->itemRepository->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY,\CommonHelpers::checkArrayKey($data,'pasHumanFrequency'));
                    $this->itemRepository->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY,\CommonHelpers::checkArrayKey($data,'pasAverageTime'));
                    $this->itemRepository->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY,\CommonHelpers::checkArrayKey($data,'pasType'));
                    $this->itemRepository->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY,\CommonHelpers::checkArrayKey($data,'pasMaintenanceFrequency'));

                    // action recommendations
                    $this->itemRepository->insertDropdownValue($item->id, ACTIONS_RECOMMENDATIONS_ID, 0,\CommonHelpers::checkArrayKey($data,'ActionsRecommendations'),\CommonHelpers::checkArrayKey($data,'ActionsRecommendations_other'));

                }
                \DB::commit();
                if (is_null($id)) {
                    return $response = \CommonHelpers::successResponse('Item Created Successfully!', $item);
                } else {
                    return $response = \CommonHelpers::successResponse('Item Updated Successfully!', $item);
                }
            } catch (\Exception $e) {
                dd($e);
                \Log::debug($e);
                \DB::rollBack();
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Create or Update Item. Please try again!');
            }
    }

    public function handleSample ($item_id,$accessibility, $sample, $sample_other, $sample_other_comments) {
        if ($accessibility != ITEM_INACCESSIBLE_STATE) {

            if ($sample == "NO_SAMPLE") {
                $dataSample = 0;
            } elseif ($sample == "OTHER") {
                // create new sample
                $dataSampleCreate = [
                    'is_real' => 0,
                    'description' => $sample_other,
                    'comment_key' => $sample_other_comments,
                    'original_item_id' => $item_id
                ];

                $sampleCreate = $this->itemRepository->createSample($dataSampleCreate);
                //update sample reference

                $this->itemRepository->updateSample(['reference' => 'SR'.$sampleCreate->id], $sampleCreate->id);
                $dataSample =  $sampleCreate->id;
                //log audit
                \ComplianceHelpers::logAudit(SAMPLE_TYPE, $sampleCreate->id, AUDIT_ACTION_ADD, 'SR'.$sampleCreate->id, $item_id);
            } else {
                // update sample comment

                $this->itemRepository->updateSample(['comment_key' => $sample_other_comments], $sample);
                $dataSample = $sample;

            }
        } else {
            $dataSample = null;
        }
        return $dataSample;
    }

    public function updateVRSSample($sample_id, $survey_id, $asbestos_id, $asbestos_other){
        $asbestos_id = end($asbestos_id) ?? 0;

        $list_vrs_item_ids = \DB::select("SELECT GROUP_CONCAT(distinct d.item_id) AS item_id  from tbl_item_sample_id_value d
                                                        LEFT JOIN tbl_items i ON d.item_id = i.id
                                                        LEFT JOIN tbl_sample s ON d.dropdown_data_item_id = s.id
                                                        WHERE d.dropdown_data_item_id = $sample_id
                                                        AND i.survey_id = $survey_id AND i.record_id != s.original_item_id
                                                        GROUP BY survey_id ");
        $os_item_id = \DB::select("SELECT item_id  from tbl_item_sample_id_value d
                                                        LEFT JOIN tbl_items i ON d.item_id = i.id
                                                        LEFT JOIN tbl_sample s ON d.dropdown_data_item_id = s.id
                                                        WHERE d.dropdown_data_item_id = $sample_id
                                                        AND i.survey_id = $survey_id AND i.record_id = s.original_item_id
                                                        GROUP BY survey_id ");

        if(in_array($asbestos_id,[382, 383, 384, 386, 387, 388, 390, 391, 392])){
            if(in_array($asbestos_id,[382,386,390])){
                $asbestos_id_new = 390;
            } else if(in_array($asbestos_id,[383,387,391])){
                $asbestos_id_new = 391;
            } else if(in_array($asbestos_id,[384,388,392])){
                $asbestos_id_new = 392;
            }

            //update VRS
            if(count($list_vrs_item_ids) > 0){
                foreach ($list_vrs_item_ids as $list_id) {
                    $list_item_vrs_ids = explode(",", $list_id->item_id);
                    if(count($list_item_vrs_ids) > 0){
                        foreach ($list_item_vrs_ids as $item_id){
                            $this->itemRepository->insertDropdownValue($item_id, ASBESTOS_TYPE_ID, 0,$asbestos_id_new, $asbestos_other);
                        }
                    }
                }
            }
            // update OS
            $this->itemRepository->insertDropdownValue($os_item_id[0]->item_id, ASBESTOS_TYPE_ID, 0,$asbestos_id, $asbestos_other);
        } else {
            //update VRS
            if(count($list_vrs_item_ids) > 0){
                foreach ($list_vrs_item_ids as $list_id) {
                    $list_item_vrs_ids = explode(",", $list_id->item_id);
                    if(count($list_item_vrs_ids) > 0){
                        foreach ($list_item_vrs_ids as $item_id){
                            $this->itemRepository->insertDropdownValue($item_id, ASBESTOS_TYPE_ID, 0,$asbestos_id, $asbestos_other);
                        }
                    }
                }
            }
            // update OS
            $this->itemRepository->insertDropdownValue($os_item_id[0]->item_id, ASBESTOS_TYPE_ID, 0,$asbestos_id, $asbestos_other);
        }
    }

    public function LoadInspectionFormula($currentDate, $totalRisk) {

        $inspectionDate = 0;
        if ($totalRisk > 0) {
            if ($totalRisk >= 17) {
                $inspectionDate = $currentDate + (90 * 86400);
            } elseif ($totalRisk >= 13 && $totalRisk <= 16) {
                $inspectionDate = $currentDate + (180 * 86400);
            } elseif ($totalRisk >= 8 && $totalRisk <= 12) {
                $inspectionDate = $currentDate + (365 * 86400);
            } elseif ($totalRisk < 8) {
                $inspectionDate = $currentDate + (540 * 86400);
            }
        }

        return $inspectionDate;
    }


    public function getDropdownItemValue($item_id, $type, $dropdown_data_item_parent_id = 0, $action = 'text') {
        return $this->itemRepository->getDropdownItemValue($item_id, $type, $dropdown_data_item_parent_id, $action);
    }

    public function getallParentsIds($data) {
        $id = [];
        if (!is_null($data)) {
            array_unshift($id, $data->id);
            if (!is_null($data->allParents)) {
                $parent1 = $data->allParents;
                array_unshift($id, $data->allParents->id);
                if (!is_null($parent1->allParents)) {
                    $parent2 = $parent1->allParents;
                    array_unshift($id, $parent1->allParents->id);
                    if (!is_null($parent2->allParents)) {
                        $parent3 = $parent2->allParents;
                        array_unshift($id, $parent2->allParents->id);
                    }
                }
            }
        }
        return $id;
    }

    public function findSample($id) {
        return $this->itemRepository->findSample($id);
    }

    public function decommissionItem($item_id, $reason) {

        $item = $this->itemRepository->find($item_id);
        try {
            \DB::beginTransaction();
            if ($item->decommissioned == ITEM_DECOMMISSION) {
                $this->itemRepository->recommissionItem($item_id, $reason);

                //update and send email
                // if ($item->survey_id == 0) {
                //     \CommonHelpers::isRegisterUpdated($item->property_id);
                // } else {
                //     \CommonHelpers::changeSurveyStatus($item->survey_id);
                // }

                // store comment history
                \CommentHistory::storeDeccomissionHistory('recommission','item', $item_id, $reason, $item->survey->reference ?? null);
                //log audit
                \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_RECOMMISSION, $item->reference, $item->survey_id ,null, 0 ,$item->property_id);
                $response = \CommonHelpers::successResponse('Item Recommissioned Successfully!');
            } else {
                $this->itemRepository->decommissionItem($item_id, $reason);

                //update and send email
                // if ($item->survey_id == 0) {
                //     \CommonHelpers::isRegisterUpdated($item->property_id);
                // } else {
                //     \CommonHelpers::changeSurveyStatus($item->survey_id);
                // }

                // store comment history
                \CommentHistory::storeDeccomissionHistory('decommission','item', $item_id, $reason, $item->survey->reference ?? null);
                //log audit
                \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_DECOMMISSION, $item->reference, $item->survey_id ,null, 0 ,$item->property_id);
                $response = \CommonHelpers::successResponse('Item Decommissioned Successfully!');
            }
            \DB::commit();
            return $response;
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollBack();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to Decommission/Recommission Item. Please try again!');
        }
    }
    public function getItemCommentbyId($comment_id){
        return $this->itemRepository->getItemCommentbyId($comment_id);
    }
    public function updateItemInfo($record_id, $comment){
        return $this->itemRepository->updateItemInfo($record_id, $comment);
    }

    public function countInaccessibleRooms($type, $property_id, $survey_id = 0, $area_id = 0, $zone_id = 0) {
        if (is_string($property_id) || is_numeric($property_id)) {
            $property_id = explode(" ",$property_id);
        }
        switch ($type) {
            case 'register':
                $locations = $this->locationRepository->with('allItems', 'items')->whereIn('property_id', $property_id)->where('survey_id', 0)->where('state', LOCATION_STATE_INACCESSIBLE)->where('decommissioned', LOCATION_UNDECOMMISSION)->get();
                break;
            case 'survey':
                $locations = $this->locationRepository->with('allItems', 'items')->whereIn('property_id', $property_id)->where('survey_id', $survey_id)->where('state', LOCATION_STATE_INACCESSIBLE)->where('decommissioned', LOCATION_UNDECOMMISSION)->get();
                break;
            case 'registerarea':
                $locations = $this->locationRepository->with('allItems', 'items')->whereIn('property_id', $property_id)->where('area_id', $area_id)->where('survey_id', 0)->where('state', LOCATION_STATE_INACCESSIBLE)->where('decommissioned', LOCATION_UNDECOMMISSION)->get();
                break;
            case 'surveyarea':
                $locations = $this->locationRepository->with('allItems', 'items')->whereIn('property_id', $property_id)->where('area_id', $area_id)->where('survey_id', $survey_id)->where('state', LOCATION_STATE_INACCESSIBLE)->where('decommissioned', LOCATION_UNDECOMMISSION)->get();
                break;
            case 'zones':
                $table_join_privs = \CompliancePrivilege::getPropertyPermission();
                $locations = $this->locationRepository->with('allItems', 'items')
                    ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                    ->join('tbl_property', function ($join) use ($zone_id) {
                        $join->on('tbl_property.id', '=', 'property_id')
                            ->whereRaw("tbl_property.zone_id in ($zone_id)");
                    })
                    ->where('tbl_location.survey_id', 0)
                    ->where('tbl_location.state', LOCATION_STATE_INACCESSIBLE)
                    ->where('tbl_location.decommissioned', LOCATION_UNDECOMMISSION)->get('tbl_location.*');
                break;
            default:
                $locations = 0;
                break;
        }

        return is_null($locations) ? 0 : $locations;
    }

    public function getSamplesTable($property_id, $survey_id){
        $survey_samples = $this->itemRepository->getSamplesTable($property_id, $survey_id);
        foreach ($survey_samples as $survey_sample) {
            $survey_sample->item_reference = (explode(",",$survey_sample->item_reference));
            $survey_sample->item_ids = (explode(",",$survey_sample->item_ids));
        }

        return $survey_samples;
    }

    public function updateAsbestosType($survey_id){
        $condition = ['survey_id'=>$survey_id, 'state'=>ITEM_INACCESSIBLE_STATE];
        $arr_item_ids = $this->itemRepository->getMutipleInaccessibleItem($condition);
        if(count($arr_item_ids)){
            // set Asbestos Type to Strongly Presumed(389) ->Chrysotile (390)
            return $this->asbestosTypeValueRepository->whereIn('item_id', $arr_item_ids)->update(['dropdown_data_item_parent_id'=>389, 'dropdown_data_item_id'=>390]);
        }
        return false;
    }

    public function updateActionRecommentdationNoAcm($survey_id){
        $condition = ['survey_id'=>$survey_id, 'state'=>ITEM_NOACM_STATE];
        $arr_item_ids = $this->itemRepository->getMutipleInaccessibleItem($condition);
        if(count($arr_item_ids)){
            // set Asbestos Type to Strongly Presumed(389) ->Chrysotile (390)
            return $this->actionRecommendationValueRepository->whereIn('item_id', $arr_item_ids)->update(['dropdown_data_item_id'=>0, 'dropdown_data_item_parent_id'=>0]);
        }
        return false;
    }

    public function sortItemSurvey($item_survey){
        return $this->itemRepository->sortItemSurvey($item_survey);
    }

    public function updateItemOs($data, $list_item_id, $sample_id, $survey_id) {
        try {
            \DB::beginTransaction();
            $state = (\CommonHelpers::checkArrayKey2($data['abestosTypes'], 0) == 393) ? ITEM_NOACM_STATE : ITEM_ACCESSIBLE_STATE;
            if (!is_null($list_item_id)) {

                if ($state == ITEM_NOACM_STATE) {
                    // update item
                    $this->itemRepository->whereIn('id', $list_item_id)->update([
                        'state' => $state,
                        'total_risk' => 0,
                        'total_mas_risk' => 0
                    ]);

                    $this->updateVRSSample($sample_id, $survey_id, \CommonHelpers::checkArrayKey($data,'abestosTypes'),\CommonHelpers::checkArrayKey($data,'AsbestosTypeMore'));

                    $this->itemRepository->updateMutiplePriorityAssessmentRiskValue($list_item_id,['dropdown_data_item_id' => 0]);
                    $this->itemRepository->updateMutipleMaterialAssessmentRiskValue($list_item_id,['dropdown_data_item_id' => 0]);
                    $this->itemRepository->updateMutipleActionRecommendationValue($list_item_id,['dropdown_data_item_id' => 0]);

                } else {

                    // update item
                    $this->itemRepository->whereIn('id', $list_item_id)->update([
                        'state' => $state
                    ]);
                    foreach ($list_item_id as $item_id) {
//                        dd(\CommonHelpers::checkArrayKey($data,'assessmentAsbestosKey'));
                        $updateAssessmentAsbestosKey = $this->itemRepository->insertDropdownValue($item_id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY,\CommonHelpers::checkArrayKey($data,'assessmentAsbestosKey'));
                        $item_current = $this->itemRepository->find($item_id);
                        $item_product_type = $item_current->masProductDebris->getData->score ?? 0;
                        $item_extend_damage = $item_current->masDamage->getData->score ?? 0;
                        $item_surface_treatment = $item_current->masTreatment->getData->score ?? 0;
                        $item_asbestos_fibre = $item_current->masAsbestos->getData->score ?? 0;
                        $item_current->update(['total_mas_risk' => $item_product_type + $item_extend_damage + $item_surface_treatment + $item_asbestos_fibre]);
                    }
                    $this->updateVRSSample($sample_id, $survey_id, \CommonHelpers::checkArrayKey($data,'abestosTypes'),\CommonHelpers::checkArrayKey($data,'AsbestosTypeMore'));
                }
            }
            \DB::commit();
            return \CommonHelpers::successResponse('Update sample successful !');
        } catch (\Exception $e) {
            dd($e);
            \Log::debug($e);
            \DB::rollBack();
            return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to  update sample. Please try again!');
        }

    }

    public function updateSampleSurvey($sample_id,$description,$comment){
        if (is_array($comment)) {
            $comment = implode(",",$comment);
        }
        $data_sample = $this->itemRepository->updateSampleSurvey($sample_id,$description,$comment);
        if ($data_sample) {
            return \CommonHelpers::successResponse('Sample Updated Successfully!');
        } else {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Sample Updated Fail!');
        }
    }

   public function getReason($item){
        $selectedReason = $item->ItemNoAccessValue->dropdown_data_item_id ?? 0;
        $selected_text = ItemNoAccess::where('id', $selectedReason)->value('description');
        if ($selectedReason == 592) {
            $selected_text = $selected_text . ' ' .($item->ItemNoAccessValue->dropdown_other ?? '');
        }
        return str_replace('Other', '', $selected_text);
    }

}

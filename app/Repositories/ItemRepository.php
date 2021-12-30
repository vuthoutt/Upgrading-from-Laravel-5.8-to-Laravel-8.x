<?php
namespace App\Repositories;
use App\Models\Property;
use App\Models\Zone;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Item;
use App\Models\ItemInfo;
use App\Models\Survey;
use App\Models\Location;
use App\Models\DropdownValue;
use App\Models\Sample;
use App\Helpers\CommonHelpers;
use App\Models\DropdownItem\ProductDebrisType;//3
use App\Models\DropdownItemValue\ProductDebrisTypeValue;//3
use App\Models\DropdownItem\Extent;//4
use App\Models\DropdownItemValue\ExtentValue;//4
use App\Models\DropdownItem\AsbestosType;//5
use App\Models\DropdownItemValue\AsbestosTypeValue;//5
use App\Models\DropdownItem\ActionRecommendation;//7
use App\Models\DropdownItemValue\ActionRecommendationValue;//7
use App\Models\DropdownItem\AdditionalInformation;//8
use App\Models\DropdownItemValue\AdditionalInformationValue;//8
use App\Models\DropdownItem\SampleComment;//9
use App\Models\DropdownItemValue\SampleCommentValue;//9
use App\Models\DropdownItem\SpecificLocation;//11
use App\Models\DropdownItemValue\SpecificLocationValue;//11
use App\Models\DropdownItem\AccessibilityVulnerability;//12
use App\Models\DropdownItemValue\AccessibilityVulnerabilityValue;//12
use App\Models\DropdownItem\LicensedNonLicensed;//13
use App\Models\DropdownItemValue\LicensedNonLicensedValue;//13
use App\Models\DropdownItem\UnableToSample;//14
use App\Models\DropdownItemValue\UnableToSampleValue;//14
use App\Models\DropdownItem\ItemNoAccess;//15
use App\Models\DropdownItemValue\ItemNoAccessValue;//15
use App\Models\DropdownItem\NoACMComments;//16
use App\Models\DropdownItemValue\NoACMCommentsValue;//16
use App\Models\DropdownItem\PriorityAssessmentRisk;//18
use App\Models\DropdownItemValue\PriorityAssessmentRiskValue;//18
use App\Models\DropdownItem\MaterialAssessmentRisk;//19
use App\Models\DropdownItemValue\MaterialAssessmentRiskValue;//19
use App\Models\DropdownItem\SampleId;//500
use App\Models\DropdownItemValue\SampleIdValue;//500
use App\Models\DropdownItem\SubSampleId;//502
use App\Models\DropdownItemValue\SubSampleIdValue;//502
use App\Jobs\SendClientEmail;
class ItemRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Item::class;
    }

    public function getItem($id) {
        $item = Item::with('itemInfo', 'location', 'area', 'property', 'survey', 'client', 'zone')->where('id',$id)->first();
        return $item;
    }

    public function getItemPagination($survey_id, $decommissioned , $property_id, $area_id,  $location_id, $group_id ,$client_id = NULL, $type = NULL, $pagination_type = NULL) {

        $list_condition = $this->getConditionPagination($survey_id, $decommissioned , $property_id, $area_id, $location_id,$group_id, $client_id, $type, $pagination_type);
        $condition = count($list_condition['condition']) > 0 ? $list_condition['condition'] : TRUE;
        $other_condition = $list_condition['other_condition'] ? $list_condition['other_condition'] : TRUE ;
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        $item = Item::with('itemInfo', 'location', 'area', 'property', 'survey')
             ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
            ->where($condition)
            ->whereRaw($other_condition)
            ->get();
        return !$item->isEmpty() ? $item : [] ;
    }

    private function getConditionPagination($survey_id, $decommissioned , $property_id, $area_id, $location_id,$group_id, $client_id, $type, $pagination_type){
        $condition = [];
        $other_condition = '';
        if($type && in_array($type, [TYPE_All_ACM_ITEM_SUMMARY, TYPE_HIGH_RISK_ITEM_SUMMARY, TYPE_MEDIUM_RISK_ITEM_SUMMARY,
                TYPE_LOW_RISK_ITEM_SUMMARY, TYPE_VERY_LOW_RISK_ITEM_SUMMARY, TYPE_NO_RISK_ITEM_SUMMARY, TYPE_INACCESS_ACM_ITEM_SUMMARY]) && $pagination_type){
            //common condition
            $condition['decommissioned'] = ITEM_UNDECOMMISSION;
            switch ($pagination_type){
                case TYPE_CLIENT:
                    // $zone_ids = Zone::where(['client_id' => $client_id, 'parent_id' => 0])->get()->pluck('id')->toArray();
                    //         // property privilege
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
                    //         ->where(['client_id' => $client_id,'decommissioned' => 0, 'zone_id'=> $group_id])
                    //         ->whereIn('id', $property_id_privs)->pluck('id')->toArray();

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
                case TYPE_SITE_OPERSTIVE:
                case TYPE_LOCATION:
                    $condition['survey_id'] =  $survey_id;
                    $condition['location_id'] =  $location_id;
                    break;
            }

            switch ($type){
                case TYPE_All_ACM_ITEM_SUMMARY:
                    $other_condition = !empty($other_condition) ? $other_condition . " AND (state IS NOT NULL AND state != ".ITEM_NOACM_STATE.")" : " (state IS NOT NULL AND state != 0 AND state != ".ITEM_NOACM_STATE.")";
                    break;
                case TYPE_HIGH_RISK_ITEM_SUMMARY:
                    $condition['state'] =  ITEM_ACCESSIBLE_STATE;
                    $other_condition = !empty($other_condition) ? $other_condition . " AND (total_mas_risk >= 10 AND total_mas_risk <= 99)" : " (total_mas_risk >= 10 AND total_mas_risk <= 99)";
                    break;
                case TYPE_MEDIUM_RISK_ITEM_SUMMARY:
                    $condition['state'] =  ITEM_ACCESSIBLE_STATE;
                    $other_condition = !empty($other_condition) ? $other_condition . " AND (total_mas_risk >= 7 AND total_mas_risk <= 9)" : " (total_mas_risk >= 7 AND total_mas_risk <= 9)";
                    break;
                case TYPE_LOW_RISK_ITEM_SUMMARY:
                    $condition['state'] =  ITEM_ACCESSIBLE_STATE;
                    $other_condition = !empty($other_condition) ? $other_condition . " AND (total_mas_risk >= 5 AND total_mas_risk <= 6)" : " (total_mas_risk >= 5 AND total_mas_risk <= 6)";
                    break;
                case TYPE_VERY_LOW_RISK_ITEM_SUMMARY:
                    $condition['state'] =  ITEM_ACCESSIBLE_STATE;
                    $other_condition = !empty($other_condition) ? $other_condition . " AND (total_mas_risk >= 0 AND total_mas_risk <= 4)" : " (total_mas_risk >= 0 AND total_mas_risk <= 4)";
                    break;
                case TYPE_NO_RISK_ITEM_SUMMARY:
                    $condition['state'] =  ITEM_NOACM_STATE;
                    break;
                case TYPE_INACCESS_ACM_ITEM_SUMMARY:
                    $condition['state'] =  ITEM_INACCESSIBLE_STATE;
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

        return ['condition' => $condition, 'other_condition' => $other_condition];
    }

    public function getItemsForSamples($property_id, $survey_id = 0) {
        if ($survey_id) {
            $item = Item::where('survey_id' , $survey_id)->where('decommissioned', 0);
        } else {
            $item = Item::where('survey_id' , 0)->where('decommissioned', 0)->where('state','!=',ITEM_NOACM_STATE)->where('property_id', $property_id);
        }
        return is_null($item) ? [] : $item->lists('id');;
    }

    public function loadDropdownText($dropdown_item_id, $parent_id = 0) {
        $data = null;
        switch ($dropdown_item_id) {
            case PRODUCT_DEBRIS_TYPE_ID:
                $data =  ProductDebrisType::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case EXTENT_ID:
                $data =  Extent::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case ASBESTOS_TYPE_ID:
                $data =  AsbestosType::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case ACTIONS_RECOMMENDATIONS_ID:
                $data =  ActionRecommendation::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case ADDITIONAL_INFORMATION_ID:
                $data =  AdditionalInformation::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case SAMPLE_COMMENTS_ID:
                $data =  SampleComment::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case SPECIFIC_LOCATION_ID:
                $data =  SpecificLocation::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case ACCESSIBILITY_VULNERABILITY_ID:
                $data =  AccessibilityVulnerability::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case LICENSED_NONLICENSED_ID:
                $data =  LicensedNonLicensed::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case UNABLE_TO_SAMPLE_ID:
                $data =  UnableToSample::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case ITEM_NO_ACCESS_ID:
                $data =  ItemNoAccess::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case PRIORITY_ASSESSMENT_RISK_ID:
                $data =  PriorityAssessmentRisk::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case NO_ACM_COMMENTS_ID:
                $data =  ItemNoAccess::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case MATERIAL_ASSESSMENT_RISK_ID:
                $data =  MaterialAssessmentRisk::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case SAMPLE_ID:
                $data =  SampleId::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
            case 502:
                $data =  SubSampleId::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
        }
        return is_null($data) ? null : $data;
    }

    public function createOrUpdateItem($data, $id = null) {
        if (!empty($data)) {
            $survey = Survey::with('surveySetting')->where('id', \CommonHelpers::checkArrayKey($data,'survey_id'))->first();

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

            // if (empty($survey) || (  isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_r_and_d_elements == ACTIVE) ) {
                $dataItemInfo['is_r_and_d_element'] = (isset($data['rAndDElement'])) ? 1 : 0;
            // }
            try {
                if (is_null($id)) {
                    $dataItem['decommissioned'] = 0;
                    $dataItem['created_by'] = \Auth::user()->id;
                    $itemCreate = Item::create($dataItem);

                    $record_id = $itemCreate->id;
                    $reference = 'IN'. $itemCreate->id;
                    //update item when create success
                    Item::where('id', $itemCreate->id)->update(['record_id' => $record_id, 'reference' => $reference]);
                    $item = Item::find($itemCreate->id);
                    //update and send email
                    if ($item->survey_id == 0) {
                        if (isset($data['ActionsRecommendations'][0]) and  in_array($data['ActionsRecommendations'][0], ACTION_RECOMMENDATION_LIST_ID)) {
                            // \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmail($item->property_id, REMEDIAL_ACTIONS_REQUIRED_EMAILTYPE));
                        }
                        \CommonHelpers::isRegisterUpdated($item->property_id);
                    } else {
                        \CommonHelpers::changeSurveyStatus($item->survey_id);
                    }
                    //log audit
                    $comment_audit = \Auth::user()->full_name  . " added new item " . $reference .(isset($item->survey->reference) ? 'on '.$item->survey->reference : ''). ' on ' . ($item->property->name ?? '');
                    \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_ADD, $reference, $item->survey_id ,$comment_audit, 0 ,$item->property_id);
                } else {
                    $item = Item::where('id', $id)->first();
                    $dataItem['updated_by'] = \Auth::user()->id;

                    Item::where('id', $id)->update($dataItem);

                    //update and send email
                    if ($item->survey_id == 0) {
                        if (isset($data['ActionsRecommendations'][0]) and  in_array($data['ActionsRecommendations'][0], ACTION_RECOMMENDATION_LIST_ID)) {
                            // \Queue::pushOn(CLIENT_EMAIL_QUEUE,new SendClientEmail($item->property_id, REMEDIAL_ACTIONS_REQUIRED_EMAILTYPE));
                        }
                        \CommonHelpers::isRegisterUpdated($item->property_id);
                    } else {
                        \CommonHelpers::changeSurveyStatus($item->survey_id);
                    }
                    //log audit
                    $comment_audit = \Auth::user()->full_name  . " edited item " . $item->reference .(isset($item->survey->reference) ? 'on '.$item->survey->reference : ''). ' on ' . ($item->property->name ?? '');
                    \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_EDIT, $item->reference, $item->survey_id ,$comment_audit, 0 ,$item->property_id);
                }
                if ($item) {
                    // store comment history
                    \CommentHistory::storeCommentHistory('item', $item->id, $dataItemInfo['comment']);
                    //create item Info
                    ItemInfo::updateOrCreate(['item_id' => $item->id], $dataItemInfo);
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
                        $this->insertDropdownValue($item->id, SPECIFIC_LOCATION_ID, 0,\CommonHelpers::checkArrayKey($data,'specificLocations1'),\CommonHelpers::checkArrayKey($data,'specificLocations-other'));
                    } else {
                        if (is_null(\CommonHelpers::checkArrayKey($data,'specificLocations3'))) {
                            $this->insertDropdownValue($item->id, SPECIFIC_LOCATION_ID, 0,\CommonHelpers::checkArrayKey($data,'specificLocations1'));
                        } else {
                            $this->insertDropdownValue($item->id, SPECIFIC_LOCATION_ID, 0,\CommonHelpers::checkArrayKey($data,'specificLocations3'));
                        }
                    }
                    //check sample
                    $dataSample = $this->handleSample($item->record_id, \CommonHelpers::checkArrayKey($data,'accessibility'), \CommonHelpers::checkArrayKey($data,'sample'), \CommonHelpers::checkArrayKey($data,'sample-other'), \CommonHelpers::checkArrayKey($data,'sample-other-comments'));
                    // insert sample link id
                    $this->insertDropdownValue($item->id, SAMPLE_ID, 0, $dataSample);
                    // update VRS item
                    $sampleItem = Sample::find($dataSample);
                    if (!is_null($sampleItem)) {
                        if ($item->record_id == $sampleItem->original_item_id) {
                            $this->updateVRSSample($dataSample, $item->survey_id, \CommonHelpers::checkArrayKey($data,'abestosTypes'),\CommonHelpers::checkArrayKey($data,'AsbestosTypeMore'));
                        }
                    }

                    //details tab
                    $this->insertDropdownValue($item->id, PRODUCT_DEBRIS_TYPE_ID, 0,\CommonHelpers::checkArrayKey($data,'productDebris'),\CommonHelpers::checkArrayKey($data,'productDebris-other'));
                    $this->insertDropdownValue($item->id, ASBESTOS_TYPE_ID, 0,\CommonHelpers::checkArrayKey($data,'abestosTypes'),\CommonHelpers::checkArrayKey($data,'AsbestosTypeMore'));
                    // if (empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_license_status == ACTIVE) ) {
                        $this->insertDropdownValue($item->id, LICENSED_NONLICENSED_ID, 0,\CommonHelpers::checkArrayKey($data,'LicensedNonLicensed'));
                    // }

                    $this->insertDropdownValue($item->id, EXTENT_ID, 0,\CommonHelpers::checkArrayKey($data,'extent'));
                    $this->insertDropdownValue($item->id, ACCESSIBILITY_VULNERABILITY_ID, 0,\CommonHelpers::checkArrayKey($data,'AccessibilityVulnerability'));
                    $this->insertDropdownValue($item->id, ADDITIONAL_INFORMATION_ID, 0,\CommonHelpers::checkArrayKey($data,'AdditionalInformation'),\CommonHelpers::checkArrayKey($data,'AdditionalInformation-Other'));
                    $this->insertDropdownValue($item->id, ITEM_NO_ACCESS_ID, 0,\CommonHelpers::checkArrayKey($data,'reasons'),\CommonHelpers::checkArrayKey($data,'reasons-other'));

                    // mas tab
                    $this->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TYPE_KEY,\CommonHelpers::checkArrayKey($data,'assessmentTypeKey'));
                    $this->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_DAMAGE_KEY,\CommonHelpers::checkArrayKey($data,'assessmentDamageKey'));
                    $this->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_TREATMENT_KEY,\CommonHelpers::checkArrayKey($data,'assessmentTreatmentKey'));
                    $this->insertDropdownValue($item->id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY,\CommonHelpers::checkArrayKey($data,'assessmentAsbestosKey'));

                    // pas tab with survey setting

                    // if (empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_priority_assessment == ACTIVE) ) {
                        $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY,\CommonHelpers::checkArrayKey($data,'pasPrimary'));
                        $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY,\CommonHelpers::checkArrayKey($data,'pasSecondary'));
                        $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_LOCATION_KEY,\CommonHelpers::checkArrayKey($data,'pasLocation'));
                        $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY,\CommonHelpers::checkArrayKey($data,'pasAccessibility'));
                        $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_EXTENT_KEY,\CommonHelpers::checkArrayKey($data,'pasExtent'));
                        $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_OCCUPANTS_KEY,\CommonHelpers::checkArrayKey($data,'pasNumber'));
                        $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY,\CommonHelpers::checkArrayKey($data,'pasHumanFrequency'));
                        $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY,\CommonHelpers::checkArrayKey($data,'pasAverageTime'));
                        $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY,\CommonHelpers::checkArrayKey($data,'pasType'));
                        $this->insertDropdownValue($item->id, PRIORITY_ASSESSMENT_RISK_ID, PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY,\CommonHelpers::checkArrayKey($data,'pasMaintenanceFrequency'));
                    // }

                    // action recommendations
                    $this->insertDropdownValue($item->id, ACTIONS_RECOMMENDATIONS_ID, 0,\CommonHelpers::checkArrayKey($data,'ActionsRecommendations'),\CommonHelpers::checkArrayKey($data,'ActionsRecommendations_other'));

                }

                if (is_null($id)) {
                    return $response = \CommonHelpers::successResponse('Item Added Successfully!', $item);
                } else {
                    return $response = \CommonHelpers::successResponse('Item Updated Successfully!', $item);
                }
            } catch (\Exception $e) {
                \Log::debug($e);
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create or update item. Please try again!');
            }
        }
    }

    public function updateItemOs($data, $list_item_id, $sample_id, $survey_id) {
        try {
            $state = (\CommonHelpers::checkArrayKey2($data['abestosTypes'], 0) == 393) ? ITEM_NOACM_STATE : ITEM_ACCESSIBLE_STATE;

            if (!is_null($list_item_id)) {

                    if ($state == ITEM_NOACM_STATE) {
                        // update item
                        Item::whereIn('id', $list_item_id)->update([
                            'state' => $state,
                            'total_risk' => 0,
                            'total_mas_risk' => 0
                        ]);

                        $this->updateVRSSample($sample_id, $survey_id, \CommonHelpers::checkArrayKey($data,'abestosTypes'),\CommonHelpers::checkArrayKey($data,'AsbestosTypeMore'));

                        PriorityAssessmentRiskValue::whereIn('item_id', $list_item_id)->update(['dropdown_data_item_id' => 0]);
                        MaterialAssessmentRiskValue::whereIn('item_id', $list_item_id)->update(['dropdown_data_item_id'=> 0]);
                        ActionRecommendationValue::whereIn('item_id', $list_item_id)->update(['dropdown_data_item_id'=> 0]);
                    } else {

                        // update item
                        Item::whereIn('id', $list_item_id)->update([
                            'state' => $state
                        ]);
                        foreach ($list_item_id as $item_id) {
                            $updateAssessmentAsbestosKey = $this->insertDropdownValue($item_id, MATERIAL_ASSESSMENT_RISK_ID, ASSESSMENT_ASBESTOS_KEY,\CommonHelpers::checkArrayKey($data,'assessmentAsbestosKey'));
                            $item_current = Item::find($item_id);
                            $item_product_type = $item_current->masProductDebris->getData->score ?? 0;
                            $item_extend_damage = $item_current->masDamage->getData->score ?? 0;
                            $item_surface_treatment = $item_current->masTreatment->getData->score ?? 0;
                            $item_asbestos_fibre = $item_current->masAsbestos->getData->score ?? 0;
                            $item_current->update(['total_mas_risk' => $item_product_type + $item_extend_damage + $item_surface_treatment + $item_asbestos_fibre]);
                        }
                        $this->updateVRSSample($sample_id, $survey_id, \CommonHelpers::checkArrayKey($data,'abestosTypes'),\CommonHelpers::checkArrayKey($data,'AsbestosTypeMore'));
                    }
            }
            return \CommonHelpers::successResponse('Update sample successful !');
        } catch (\Exception $e) {
            return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to  update sample. Please try again!');
        }

    }
    public function insertDropdownValue($item_id,$dropdown_item_id,$dropdown_data_item_parent_id, $dropdown_data_item_id, $other = null) {
        if (is_array($dropdown_data_item_id)) {
            if ($dropdown_item_id == SPECIFIC_LOCATION_ID) {
                $dropdown_data_item_id = implode(",",$dropdown_data_item_id);
            } else {
                $dropdown_data_item_id = end($dropdown_data_item_id);
            }
        }
        if (is_array($other)) {
            $other = implode(",",$other);
        }

        $dataDropdownValue = [
            'dropdown_item_id' => $dropdown_item_id,
            'dropdown_data_item_parent_id' => $dropdown_data_item_parent_id,
            'dropdown_data_item_id' => $dropdown_data_item_id,
            'dropdown_other' => $other
        ];

        switch ($dropdown_item_id) {
            case PRODUCT_DEBRIS_TYPE_ID:
                $data =  ProductDebrisTypeValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case EXTENT_ID:
                $data =  ExtentValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case ASBESTOS_TYPE_ID:
                $data =  AsbestosTypeValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case ACTIONS_RECOMMENDATIONS_ID:
                $data =  ActionRecommendationValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case ADDITIONAL_INFORMATION_ID:
                $data =  AdditionalInformationValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case SAMPLE_COMMENTS_ID:
                $data =  SampleCommentValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case SPECIFIC_LOCATION_ID:
                $data =  SpecificLocationValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case ACCESSIBILITY_VULNERABILITY_ID:
                $data =  AccessibilityVulnerabilityValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case LICENSED_NONLICENSED_ID:
                $data =  LicensedNonLicensedValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case UNABLE_TO_SAMPLE_ID:
                $data =  UnableToSampleValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case ITEM_NO_ACCESS_ID:
                $data =  ItemNoAccessValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case PRIORITY_ASSESSMENT_RISK_ID:
                $data =  PriorityAssessmentRiskValue::updateOrCreate(['item_id' => $item_id , 'dropdown_data_item_parent_id' => $dropdown_data_item_parent_id,], $dataDropdownValue);
                break;
            case NO_ACM_COMMENTS_ID:
                $data =  NoACMCommentsValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case MATERIAL_ASSESSMENT_RISK_ID:
                $data =  MaterialAssessmentRiskValue::updateOrCreate(['item_id' => $item_id, 'dropdown_data_item_parent_id' => $dropdown_data_item_parent_id,], $dataDropdownValue);
                break;
            case SAMPLE_ID:
                $data =  SampleIdValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
            case SUB_SAMPLE_ID:
                $data =  SubSampleIdValue::updateOrCreate(['item_id' => $item_id], $dataDropdownValue);
                break;
        }
    }

    public function getDropdownItemValue($item_id, $type, $dropdown_data_item_parent_id = 0, $action = 'text') {
        switch ($type) {
            case PRODUCT_DEBRIS_TYPE_ID:
                $description = [];
                $dataValue =  ProductDebrisTypeValue::where('item_id', $item_id)->first();
                if (!is_null($dataValue)) {
                    if ($action == 'text') {
                        $dataDropdown = ProductDebrisType::with('allParents')->where('id', $dataValue->dropdown_data_item_id)->first();
                        if (optional($dataDropdown)->description == 'Other' and $dataValue->dropdown_other !== '<null>') {
                            $description = [$dataValue->dropdown_other];
                        }
                        array_unshift($description, $this->getallParents($dataDropdown));
                    } elseif($action == 'id') {
                        $dataDropdown = ProductDebrisType::with('allParents')->where('id', $dataValue->dropdown_data_item_id)->first();
                        $description = $this->getallParentsIds($dataDropdown);
                        return $description;
                    } else {
                        return $dataValue->dropdown_other;
                    }
                }
                return str_replace('Other','',(implode(" ",$description)));

                break;
            case EXTENT_ID:
                $description = '';
                $dataValue =  ExtentValue::where('item_id', $item_id)->first();
                if (!is_null($dataValue)) {
                    if ($action == 'text') {
                        $dataDropdown = Extent::where('id', $dataValue->dropdown_data_item_id)->first();
                        if (!is_null($dataDropdown)) {
                            $description =  $dataDropdown->description;
                        }
                    } else {
                        return $dataValue->dropdown_data_item_id;
                    }
                }
                return $description;
                break;
            case ASBESTOS_TYPE_ID:
                $description = [];
                $dataValue =  AsbestosTypeValue::where('item_id', $item_id)->first();
                if (!is_null($dataValue)) {
                    if ($action == 'text') {
                        $dataDropdown = AsbestosType::with('allParents')->where('id', $dataValue->dropdown_data_item_id)->first();
                        if(optional($dataDropdown)->description == 'Other' and $dataValue->dropdown_other !== '<null>') {
                            $other = str_replace(',', ' and ', $dataValue->dropdown_other);
                            $description = [$other];
                        }
                        array_unshift($description, $this->getallParents($dataDropdown));
                    } else {
                        $other = [];
                        if(!is_null($dataValue->dropdown_other) and $dataValue->dropdown_other !== '<null>') {
                            $other = explode(',', $dataValue->dropdown_other);
                        }
                        $dataDropdown = AsbestosType::with('allParents')->where('id', $dataValue->dropdown_data_item_id)->first();
                        $parentIds = $this->getallParentsIds($dataDropdown);
                        array_push($parentIds, $other);

                        return $parentIds;
                    }

                } else {
                    return $action == 'text' ? '' : [];
                }

                return str_replace('Other','',(implode(" ",$description)));
                break;
            case ACTIONS_RECOMMENDATIONS_ID:
                $description = [];
                $dataValue =  ActionRecommendationValue::where('item_id', $item_id)->first();
                if (!is_null($dataValue)) {
                    if ($action == 'text') {
                        $dataDropdown = ActionRecommendation::with('allParents')->where('id', $dataValue->dropdown_data_item_id)->first();
                        if (optional($dataDropdown)->description == 'Other' and $dataValue->dropdown_other !== '<null>') {
                            $description = [$dataValue->dropdown_other];
                        }
                        array_unshift($description, $this->getallParents($dataDropdown));
                    } else {
                        $dataDropdown = ActionRecommendation::with('allParents')->where('id', $dataValue->dropdown_data_item_id)->first();
                        $description = $this->getallParentsIds($dataDropdown);
                        return $description;
                    }
                }
                return str_replace('Other','',(implode(" ",$description)));
                break;

                break;
            case ADDITIONAL_INFORMATION_ID:
                $description = [];
                $dataValue =  AdditionalInformationValue::where('item_id', $item_id)->first();
                if (!is_null($dataValue)) {
                    if ($action == 'text') {
                        $dataDropdown = AdditionalInformation::with('allParents')->where('id', $dataValue->dropdown_data_item_id)->first();
                        if (optional($dataDropdown)->description == 'Other' and $dataValue->dropdown_other !== '<null>') {
                            $description = [$dataValue->dropdown_other];
                        }
                        array_unshift($description, $this->getallParents($dataDropdown));
                    } elseif($action == 'id')  {
                        $dataDropdown = AdditionalInformation::with('allParents')->where('id', $dataValue->dropdown_data_item_id)->first();
                        $description = $this->getallParentsIds($dataDropdown);
                        return $description;
                    } else {
                        return $dataValue->dropdown_other;
                    }
                }
                return str_replace('Other','',(implode(" ",$description)));
                break;
            case SAMPLE_COMMENTS_ID:

                break;
            case SPECIFIC_LOCATION_ID:
                $description = '';
                $dataValue =  SpecificLocationValue::where('item_id', $item_id)->first();
                if (!is_null($dataValue)) {
                    $dataValue_ids = explode(",",$dataValue->dropdown_data_item_id);
                    $allSpecifics = SpecificLocation::whereIn('id', $dataValue_ids);
                    if ($action == 'text') {
                        $allSpecificsDescription = implode(" and ",$allSpecifics->pluck('description')->toArray());
                        if (is_null($allSpecifics->first())) {
                            $specificParent = '';
                        } else {
                            $specificParent = $this->getallParents($allSpecifics->first()->allParents);
                        }
                        if ($allSpecificsDescription == 'Other') {
                            $description = $specificParent. ' ' .$allSpecificsDescription. ' '. $dataValue->dropdown_other;

                        } else {
                            $description = $specificParent. ' ' .$allSpecificsDescription;
                        }
                    } elseif($action == 'id') {
                        $dataValue_ids = explode(",",$dataValue->dropdown_data_item_id);
                        if (is_null($allSpecifics->first())) {
                            $specificParentIds = [];
                        } else {
                            $specificParentIds = $this->getallParentsIds($allSpecifics->first()->allParents);
                        }
                        // if does not exist parent : other selected
                        if (empty($specificParentIds)) {
                            return $dataValue_ids;
                        }
                        array_push($specificParentIds, $dataValue_ids);
                        return $specificParentIds;
                    } else {
                        return $dataValue->dropdown_other;
                    }
                } else {
                    return $action == 'id' ? [] : '';
                }

                return str_replace('Other','',$description);
                break;
            case ACCESSIBILITY_VULNERABILITY_ID:
                $description = '';
                $dataValue =  AccessibilityVulnerabilityValue::where('item_id', $item_id)->first();
                if (!is_null($dataValue)) {
                    if ($action == 'text') {
                        $dataDropdown = AccessibilityVulnerability::where('id', $dataValue->dropdown_data_item_id)->first();
                        if (!is_null($dataDropdown)) {
                            $description =  $dataDropdown->description;
                        }
                    } else {
                        return $dataValue->dropdown_data_item_id;
                    }
                }
                return $description;
                break;
            case LICENSED_NONLICENSED_ID:
                $description = '';
                $dataValue =  LicensedNonLicensedValue::where('item_id', $item_id)->first();
                if (!is_null($dataValue)) {
                    if ($action == 'text') {
                        $dataDropdown = LicensedNonLicensed::where('id', $dataValue->dropdown_data_item_id)->first();
                        if (!is_null($dataDropdown)) {
                            $description =  $dataDropdown->description;
                        }
                    } else {
                        return $dataValue->dropdown_data_item_id;
                    }
                }
                return $description;
                break;
            case UNABLE_TO_SAMPLE_ID:

                break;
            case ITEM_NO_ACCESS_ID:
                $description = '';
                $dataValue =  ItemNoAccessValue::where('item_id', $item_id)->first();
                if (!is_null($dataValue)) {
                    if ($action == 'text') {
                        $dataDropdown = ItemNoAccess::where('id', $dataValue->dropdown_data_item_id)->first();
                        if (!is_null($dataDropdown)) {
                            $description =  $dataDropdown->description;
                        }
                    } else {
                        return $dataValue->dropdown_data_item_id;
                    }
                }
                return $description;
                break;
            case MATERIAL_ASSESSMENT_RISK_ID:
                $score = 0;
                $dataValue =  MaterialAssessmentRiskValue::where('item_id', $item_id)->where('dropdown_data_item_parent_id', $dropdown_data_item_parent_id)->first();
                if (!is_null($dataValue)) {
                    if ($action == 'text') {
                        $dataDropdown = MaterialAssessmentRisk::where('id', $dataValue->dropdown_data_item_id)->first();
                        if (!is_null($dataDropdown)) {
                            $score =  $dataDropdown->score;
                        }
                    } else {
                        return $dataValue->dropdown_data_item_id;
                    }
                }
                return $score;
                break;
            case PRIORITY_ASSESSMENT_RISK_ID:
                $score = 0;
                $dataValue =  PriorityAssessmentRiskValue::where('item_id', $item_id)->where('dropdown_data_item_parent_id', $dropdown_data_item_parent_id)->first();
                if (!is_null($dataValue)) {
                    if ($action == 'text') {
                        $dataDropdown = PriorityAssessmentRisk::where('id', $dataValue->dropdown_data_item_id)->first();
                        if (!is_null($dataDropdown)) {
                            $score =  $dataDropdown->score;
                        }
                    } else {
                        return $dataValue->dropdown_data_item_id;
                    }
                }
                return $score;
                break;
            case NO_ACM_COMMENTS_ID:

                break;
            case SAMPLE_ID:
                    $description = '';
                    $dataValue =  SampleIdValue::where('item_id', $item_id)->first();
                    if (!is_null($dataValue)) {
                        if ($action == 'text') {
                            $dataDropdown = Sample::where('id', $dataValue->dropdown_data_item_id)->first();
                            if (!is_null($dataDropdown)) {
                                $description =  $dataDropdown->description;
                            }
                        } else {
                            return $dataValue->dropdown_data_item_id;
                        }
                    }
                    return $description;

                break;
            case SUB_SAMPLE_ID:

                break;
        }
    }

    public function getallParents($data){
        $description = [];
        if (!is_null($data)) {
            array_unshift($description, $data->description);
            if (!is_null($data->allParents)) {
                $parent1 = $data->allParents;
                array_unshift($description, $data->allParents->description);
                if (!is_null($parent1->allParents)) {
                    $parent2 = $parent1->allParents;
                    array_unshift($description, $parent1->allParents->description);
                    if (!is_null($parent2->allParents)) {
                        $parent3 = $parent2->allParents;
                        array_unshift($description, $parent2->allParents->description);
                    }
                }
            }
        }
        $description = str_replace('Asbestos','',$description);
        $description = str_replace('Non-asbestos','',$description);
        return implode(" ",$description);
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
                'link' => $source. '&type='. TYPE_All_ACM_ITEM_SUMMARY
            ],
            "Inaccessible ACM Item Summary" => [
                'number' => $inaccessibleItems,
                'link' => $source. '&type='. TYPE_INACCESS_ACM_ITEM_SUMMARY
            ],
            "High Risk ACM Item Summary" => [
                'number' => $highRisk,
                'link' => $source. '&type='. TYPE_HIGH_RISK_ITEM_SUMMARY
            ],
            "Medium Risk ACM Item Summary" =>  [
                'number' => $mediumRisk,
                'link' => $source. '&type='. TYPE_MEDIUM_RISK_ITEM_SUMMARY
            ],
            "Low Risk ACM Item Summary" => [
                'number' => $lowRisk,
                'link' => $source. '&type='. TYPE_LOW_RISK_ITEM_SUMMARY
            ],
            "Very Low Risk ACM Item Summary" => [
                'number' => $vlowRisk,
                'link' => $source. '&type='. TYPE_VERY_LOW_RISK_ITEM_SUMMARY
            ],
            "No Risk (NoACM) Item Summary" => [
                'number' => $noACMItems,
                'link' => $source. '&type='. TYPE_NO_RISK_ITEM_SUMMARY
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
                    $items = Item::with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('property_id', $property_id)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id', 0)
                                        ->get();
                    break;
                case 'registerarea':
                    $items = Item::with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('area_id', $area_id)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id', 0)
                                        ->get();
                    break;
                case 'register-room':
                    $items = Item::with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('location_id', $location_id)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id', 0)
                                        ->get();
                    break;
                case 'zones':
                    $table_join_privs = \CompliancePrivilege::getPropertyPermission();
                    $items = Item::with('area', 'location','itemInfo','productDebrisView')
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
                        $items = Item::with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id',$survey_id)
                                        ->get();
                    break;
                case 'surveyarea':
                    $items = Item::with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('area_id', $area_id)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id',$survey_id)
                                        ->get();
                    break;
                case 'survey-room':
                    $items = Item::with('area', 'location','itemInfo','productDebrisView')
                                        ->where('state', ITEM_NOACM_STATE)
                                        ->where('location_id', $location_id)
                                        ->where('decommissioned', ITEM_UNDECOMMISSION)
                                        ->where('survey_id',$survey_id)
                                        ->get();
                    break;
                default:
                    $items = 0;
                    break;
            }
        return $items;
    }

    public function countInaccessibleRooms($type, $property_id, $survey_id = 0, $area_id = 0, $zone_id = 0) {
        if (is_string($property_id) || is_numeric($property_id)) {
            $property_id = explode(" ",$property_id);
        }
        switch ($type) {
            case 'register':
                $locations = Location::with('allItems', 'items')->whereIn('property_id', $property_id)->where('survey_id', 0)->where('state', LOCATION_STATE_INACCESSIBLE)->where('decommissioned', LOCATION_UNDECOMMISSION)->get();
                break;
            case 'survey':
                $locations = Location::with('allItems', 'items')->whereIn('property_id', $property_id)->where('survey_id', $survey_id)->where('state', LOCATION_STATE_INACCESSIBLE)->where('decommissioned', LOCATION_UNDECOMMISSION)->get();
                break;
            case 'registerarea':
                $locations = Location::with('allItems', 'items')->whereIn('property_id', $property_id)->where('area_id', $area_id)->where('survey_id', 0)->where('state', LOCATION_STATE_INACCESSIBLE)->where('decommissioned', LOCATION_UNDECOMMISSION)->get();
                break;
            case 'surveyarea':
                $locations = Location::with('allItems', 'items')->whereIn('property_id', $property_id)->where('area_id', $area_id)->where('survey_id', $survey_id)->where('state', LOCATION_STATE_INACCESSIBLE)->where('decommissioned', LOCATION_UNDECOMMISSION)->get();
                break;
            case 'zones':
                $table_join_privs = \CompliancePrivilege::getPropertyPermission();
                $locations = Location::with('allItems', 'items')
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

                $sampleCreate = Sample::create($dataSampleCreate);
                //update sample reference
                Sample::where('id', $sampleCreate->id)->update(['reference' => 'SR'.$sampleCreate->id]);
                $dataSample = $sampleCreate->id;
                //log audit
                \CommonHelpers::logAudit(SAMPLE_TYPE, $sampleCreate->id, AUDIT_ACTION_ADD, 'SR'.$sampleCreate->id, $item_id);
            } else {
                // update sample comment
                Sample::where('id', $sample)->update(['comment_key' => $sample_other_comments]);
                $dataSample = $sample;

            }
        } else {
            $dataSample = null;
        }
        return $dataSample;
    }

    public function getDropdownText($id, $type) {
        switch ($type) {
            case ITEM_NO_ACCESS_ID:
                return ItemNoAccess::where('id', $id)->value('description');
                break;

            case SAMPLE_COMMENTS_ID:
                return SampleComment::where('id', $id)->value('description');
                break;

            default:
                # code...
                break;
        }
    }

    public function getItemOperative($location, $property){
        $items = Item::with('area','location')->where(['location_id'=>$location->id,'decommissioned' => 0, 'survey_id' => 0])->orderBy('reference')->get();
        return $this->sortItemSurvey(!$items->isEmpty() ? $items : []);
    }

    public function decommissionItem($item_id, $reason) {
        $item = Item::find($item_id);
        try {
            if ($item->decommissioned == ITEM_DECOMMISSION) {
                Item::where('id', $item_id)->update(['decommissioned' => ITEM_UNDECOMMISSION,'decommissioned_reason' => $reason]);
                //update and send email
                if ($item->survey_id == 0) {
                    \CommonHelpers::isRegisterUpdated($item->property_id);
                } else {
                    \CommonHelpers::changeSurveyStatus($item->survey_id);
                }

                // store comment history
                \CommentHistory::storeDeccomissionHistory('recommission','item', $item_id, $reason, $item->survey->reference ?? null);
                //log audit
                \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_RECOMMISSION, $item->reference, $item->survey_id ,null, 0 ,$item->property_id);
                return \CommonHelpers::successResponse('Item Recommissioned Successfully!');
            } else {
                Item::where('id', $item_id)->update(['decommissioned' => ITEM_DECOMMISSION,'decommissioned_reason' => $reason]);
                //update and send email
                if ($item->survey_id == 0) {
                    \CommonHelpers::isRegisterUpdated($item->property_id);
                } else {
                    \CommonHelpers::changeSurveyStatus($item->survey_id);
                }

                // store comment history
                \CommentHistory::storeDeccomissionHistory('decommission','item', $item_id, $reason, $item->survey->reference ?? null);
                //log audit
                \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_DECOMMISSION, $item->reference, $item->survey_id ,null, 0 ,$item->property_id);
                return \CommonHelpers::successResponse('Item Decommissioned Successfully!');
            }
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Update Item Fail!');
        }
    }

    public function decommissionItemReason($item_id, $reason) {
        try {
            Item::where('id', $item_id)->update(['decommissioned_reason' => $reason]);
            $item = Item::find($item_id);
            \CommonHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_DECOMMISSION_REASON, $item->location_reference, $item->survey_id ,null, 0 ,$item->property_id);
            return \CommonHelpers::successResponse('Item Decommission Reason Updated Successfully!');
        } catch (\Exception $e) {
            return \CommonHelpers::failResponse(STATUS_FAIL,' Item Decommission Reason Updated Fail!');
        }
    }

    //set asbestos type for all inaccess items in survey
    public function updateAsbestosType($survey_id){
        $arr_item_ids = Item::where(['survey_id'=>$survey_id, 'state'=>ITEM_INACCESSIBLE_STATE])->pluck('id')->toArray();
        if(count($arr_item_ids)){
            // set Asbestos Type to Strongly Presumed(389) ->‘Presumed Crocidolite or other’ (2353) when asbestos type is blank
            foreach ($arr_item_ids as $item) {
                $dropdown = $item->AsbestosTypeValue->dropdown_data_item_id ?? 0;
                $item_id = $item->id ?? 0;
                if (!is_null($dropdown) || ($dropdown != 0) || ($dropdown != '')) {
                    AsbestosTypeValue::where('item_id', $item_id)->update(['dropdown_data_item_parent_id'=>385, 'dropdown_data_item_id'=>2352]);
                }
            }
        }
        return false;
    }

    // update action Recommentdation for no acm
    // old UPDATE tbldropdownvalue SET dropdownDataID = 493, dropdownOther = 'No Action Required' WHERE dropdownID = 7 AND itemID IN (" . $keyArray . ")";
    // new should update to 0 or null
    public function updateActionRecommentdationNoAcm($survey_id){
        $arr_item_ids = Item::where(['survey_id'=>$survey_id, 'state'=>ITEM_NOACM_STATE])->pluck('id')->toArray();
        if(count($arr_item_ids)){
            // set Asbestos Type to Strongly Presumed(389) ->Chrysotile (390)
            return ActionRecommendationValue::whereIn('item_id', $arr_item_ids)->update(['dropdown_data_item_id'=>0, 'dropdown_data_item_parent_id'=>0]);
        }
        return false;
    }

    // get list register item base on type and sort by area/location/risk
    public function getRegisterItems($type, $id){
        switch ($type){
            case PROPERTY_REGISTER_PDF:
                $condition = ['property_id' => $id];
                break;
            case AREA_REGISTER_PDF:
                $condition = ['area_id' => $id];
                break;
            case LOCATION_REGISTER_PDF:
                $condition = ['location_id' => $id];
                break;
            default:
                $condition = ['property_id' => $id];
                break;
        }
        $data = Item::with('property',
            'area',
            'location','specificLocationView',
            'ActionRecommendationValue','pasPrimary.getData','pasSecondary.getData',
            'pasLocation.getData','pasAccessibility.getData',
            'pasExtent.getData','pasNumber.getData',
            'pasHumanFrequency.getData','pasAverageTime.getData',
            'pasType.getData','pasMaintenanceFrequency.getData',
            'masProductDebris.getData','masDamage.getData',
            'masTreatment.getData','masAsbestos.getData',
            'itemInfo','sample','ItemNoAccessValue.ItemNoAccess',
            'productDebrisView','asbestosTypeView','extentView','actionRecommendationView')->where($condition)->where(['survey_id'=>0,'decommissioned'=>ITEM_UNDECOMMISSION])->where('state','!=',ITEM_NOACM_STATE)->get();
        return $this->sortItemRegister($data);
    }

    public function sortItemRegister($data){
        $sorted_items = [];
        if(!$data->isEmpty()){
            $arr_temp = [];
            foreach ($data as $item){
                // get location specific, MAS score need to convert to view later
                $item->specific_location = $item->specificLocationView->specific_location;
                //mas
                $item->product_type = $item->masProductDebris->getData->score ?? 0;
                $item->extend_damage = $item->masDamage->getData->score ?? 0;
                $item->surface_treatment = $item->masTreatment->getData->score ?? 0;
                $item->asbestos_fibre = $item->masAsbestos->getData->score ?? 0;
                //pas
                $pasPrimary = $item->pasPrimary->getData->score ?? 0;
                $pasSecondary = $item->pasSecondary->getData->score ?? 0;
                $pasLocation = $item->pasLocation->getData->score ?? 0;
                $pasAccessibility = $item->pasAccessibility->getData->score ?? 0;
                $pasExtent = $item->pasExtent->getData->score ?? 0;
                $pasNumber = $item->pasNumber->getData->score ?? 0;
                $pasHumanFrequency = $item->pasHumanFrequency->getData->score ?? 0;
                $pasAverageTime = $item->pasAverageTime->getData->score ?? 0;
                $pasType = $item->pasType->getData->score ?? 0;
                $pasMaintenanceFrequency = $item->pasMaintenanceFrequency->getData->score ?? 0;

                $item->primary = round(($pasPrimary + $pasSecondary)/2);
                $item->likelihood =  round(($pasLocation + $pasAccessibility + $pasExtent)/3);
                $item->human_exposure_potential = round(($pasNumber + $pasHumanFrequency + $pasAverageTime)/3);
                $item->maintenance_activity = round(($pasType + $pasMaintenanceFrequency)/2);
                // for sorting
                $intAreaRef = trim(explode(" ", $item->area->area_reference ?? '')[0]).($item->area->id ?? '');
                $intLocRef = trim(explode(" ", $item->location->location_reference ?? '')[0]).($item->location->id ?? '');
                //register pdf will sort from high risk to low risk
                if($item->total_risk >= 20 && $item->total_risk < 25) {
                    $arr_temp[$intAreaRef][$intLocRef][1][] = $item;

                } else if($item->total_risk >= 14 && $item->total_risk < 20) {
                    $arr_temp[$intAreaRef][$intLocRef][2][] = $item;

                } else if($item->total_risk >= 10 && $item->total_risk < 14) {
                    $arr_temp[$intAreaRef][$intLocRef][3][] = $item;

                } else if($item->total_risk < 10 && $item->total_risk > 0) {
                    $arr_temp[$intAreaRef][$intLocRef][4][] = $item;

                } else  {
                    $arr_temp[$intAreaRef][$intLocRef][5][] = $item;
                }
            }
            ksort($arr_temp);

            foreach ($arr_temp as $dataArea){
                ksort($dataArea);
                foreach ($dataArea as $data1){
                    ksort($data1);

                    foreach ($data1 as $value){
                        foreach ($value as $v){
                            $sorted_items[] = $v;
                        }
                    }
                }
            }
            return $sorted_items;
        }
        return [];
    }

    //sort item in publish survey pdf
    // low risk to high risk
    public function sortItemSurvey($data){
        $result = [];
        if (count($data)) {
            $arr_temp = [];
            foreach ($data as &$item) {
                $intAreaRef = trim(explode(" ", $item->area->area_reference ?? '')[0]).($item->area->id ?? 0);
                $intLocRef = trim(explode(" ", $item->location->location_reference ?? '')[0]).($item->location->id ?? 0);
                //register pdf will sort from high risk to low risk
                if($item->total_risk >= 20 && $item->total_risk < 25) {
                    $arr_temp[$intAreaRef][$intLocRef][1][] = $item;

                } else if($item->total_risk >= 14 && $item->total_risk < 20) {
                    $arr_temp[$intAreaRef][$intLocRef][2][] = $item;

                } else if($item->total_risk >= 10 && $item->total_risk < 14) {
                    $arr_temp[$intAreaRef][$intLocRef][3][] = $item;

                } else if($item->total_risk < 10 && $item->total_risk > 0) {
                    $arr_temp[$intAreaRef][$intLocRef][4][] = $item;

                } else  {
                    $arr_temp[$intAreaRef][$intLocRef][5][] = $item;
                }
            }
            ksort($arr_temp);
            foreach ($arr_temp as $dataArea){
                foreach ($dataArea as $data){
                    ksort($data);
                    foreach ($data as $value){
                        foreach ($value as $v){
                            $result[] = $v;
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function addParticularAttributeForItem($item,$count_acm_item, $count_noacm_item, $count_item_tested) {
        if (!is_null($item)) {
            $location_highrisk_accessible = [];
            // get location specific, MAS score need to convert to view later
            $item->specific_location = $item->specificLocationView->specific_location;
            //mas
            $item->product_type = $item->masProductDebris->getData->score ?? 0;
            $item->extend_damage = $item->masDamage->getData->score ?? 0;
            $item->surface_treatment = $item->masTreatment->getData->score ?? 0;
            $item->asbestos_fibre = $item->masAsbestos->getData->score ?? 0;
            //pas
            $pasPrimary = $item->pasPrimary->getData->score ?? 0;
            $pasSecondary = $item->pasSecondary->getData->score ?? 0;
            $pasLocation = $item->pasLocation->getData->score ?? 0;
            $pasAccessibility = $item->pasAccessibility->getData->score ?? 0;
            $pasExtent = $item->pasExtent->getData->score ?? 0;
            $pasNumber = $item->pasNumber->getData->score ?? 0;
            $pasHumanFrequency = $item->pasHumanFrequency->getData->score ?? 0;
            $pasAverageTime = $item->pasAverageTime->getData->score ?? 0;
            $pasType = $item->pasType->getData->score ?? 0;
            $pasMaintenanceFrequency = $item->pasMaintenanceFrequency->getData->score ?? 0;
            $item->primary = round(($pasPrimary + $pasSecondary)/2);
            $item->likelihood =  round(($pasLocation + $pasAccessibility + $pasExtent)/3);
            $item->human_exposure_potential = round(($pasNumber + $pasHumanFrequency + $pasAverageTime)/3);
            $item->maintenance_activity = round(($pasType + $pasMaintenanceFrequency)/2);
            $asbestos_type = $item->AsbestosTypeValue->dropdown_data_item_id ?? NULL;
            // 394,395,396,397,398 is child of Other, 380 is send to lab
            if($item->state != ITEM_NOACM_STATE && isset($asbestos_type) && !in_array($asbestos_type, [380, 394,395,396,397,398])){
                $count_item_tested++;
            }
            if($item->state == ITEM_ACCESSIBLE_STATE) {
                $inaccessible_items[] = $item;
                if($item->total_risk > 10){
                    $high_risk_item[] = $item;
                    if (!in_array($item->location_id, $location_highrisk_accessible)){
                        $location_highrisk_accessible[] = $item->location_id;
                    }
                }
            }
            if(isset($item->ActionRecommendationValue) && isset($item->ActionRecommendationValue->dropdown_data_item_id) && $item->ActionRecommendationValue->dropdown_data_item_id > 0){
                $action_recommendation_items[] = $item;
                if(in_array($item->ActionRecommendationValue->dropdown_data_item_id, ACTION_RECOMMENDATION_LIST_ID)){
                    $action_recommendation_removal_items[] = $item;
                }
            }
            if(isset($item->itemInfo->is_r_and_d_element) && $item->itemInfo->is_r_and_d_element > 0) {
                $item_r_and_d[] = $item;
            }
            if($item->sample){
                if($item->id == $item->sample->original_item_id){
                    $samples[] = $item;
                }
            }
            if($item->state != ITEM_NOACM_STATE){
                $acm_items[] = $item;
            }
            // get total acm item and no acm item
            if(!array_key_exists($item->location_id,$count_acm_item)){
                $count_acm_item[$item->location_id] = 0;
            }
            if(!array_key_exists($item->location_id,$count_noacm_item)){
                $count_noacm_item[$item->location_id] = 0;
            }
            $location_items[$item->location_id]['items'][] = $item;
            if($item->decommissioned == ITEM_UNDECOMMISSION){
                if($item->state == ITEM_NOACM_STATE){
                    $count_noacm_item[$item->location_id] ++;
                } else{
                    $count_acm_item[$item->location_id] ++;
                }
            }
            $location_items[$item->location_id]['total_acm_item'] = $count_acm_item[$item->location_id];
            $location_items[$item->location_id]['total_noacm_item'] = $count_noacm_item[$item->location_id];
        // return $items;
        } else {
            return null;
        }

    }

    public function searchItem($q, $survey_id = 0){
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        return $this->model->whereRaw("(name LIKE '%$q%' OR reference LIKE '%$q%')")
            ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
            ->where('survey_id','=',$survey_id)
            ->where('decommissioned','=',0)
            ->orderBy('name','asc')->limit(LIMIT_SEARCH)->get();
    }

    public function searchItem2($q, $survey_id = 0){
        // property privilege
        return \DB::select("SELECT name FROM tbl_items where name LIKE '%$q%';");
    }
    //update VRS item to strongly is parent dropdown if OS item in Chrysotile / Amphibole (exc. Crocidolite) / Crocidolite
    // parent 381 : Identified
    // child : 382 Chrysotile, 383 Amphibole (exc. Crocidolite) , 384 Crocidolite
    // parent 385 Presumed
    // child 386 Chrysotile , 387 Amphibole (exc. Crocidolite) , 388 Crocidolite
    //parent : 389 Strongly Presumed
    // child 390 Chrysotile , 391 Amphibole (exc. Crocidolite) , 392 Crocidolite
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
                            $this->insertDropdownValue($item_id, ASBESTOS_TYPE_ID, 0,$asbestos_id_new, $asbestos_other);
                        }
                    }
                }
            }
            // update OS
            $this->insertDropdownValue($os_item_id[0]->item_id, ASBESTOS_TYPE_ID, 0,$asbestos_id, $asbestos_other);
        } else {
            //update VRS
            if(count($list_vrs_item_ids) > 0){
                foreach ($list_vrs_item_ids as $list_id) {
                    $list_item_vrs_ids = explode(",", $list_id->item_id);
                    if(count($list_item_vrs_ids) > 0){
                        foreach ($list_item_vrs_ids as $item_id){
                            $this->insertDropdownValue($item_id, ASBESTOS_TYPE_ID, 0,$asbestos_id, $asbestos_other);
                        }
                    }
                }
            }
            // update OS
            $this->insertDropdownValue($os_item_id[0]->item_id, ASBESTOS_TYPE_ID, 0,$asbestos_id, $asbestos_other);
        }
    }



}

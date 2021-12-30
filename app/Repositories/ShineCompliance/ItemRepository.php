<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ItemComment;
use App\Models\ShineCompliance\Item;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\ShineCompliance\ItemInfo;
use App\Models\ShineCompliance\Survey;
use App\Models\ShineCompliance\Location;
use App\Models\ShineCompliance\DropdownValue;
use App\Models\ShineCompliance\Sample;
use App\Helper\ShineCompliances\CommonHelpers;
use App\Models\ShineCompliance\DropdownItem\ProductDebrisType;//3
use App\Models\ShineCompliance\DropdownItemValue\ProductDebrisTypeValue;//3
use App\Models\ShineCompliance\DropdownItem\Extent;//4
use App\Models\ShineCompliance\DropdownItemValue\ExtentValue;//4
use App\Models\ShineCompliance\DropdownItem\AsbestosType;//5
use App\Models\ShineCompliance\DropdownItemValue\AsbestosTypeValue;//5
use App\Models\ShineCompliance\DropdownItem\ActionRecommendation;//7
use App\Models\ShineCompliance\DropdownItemValue\ActionRecommendationValue;//7
use App\Models\ShineCompliance\DropdownItem\AdditionalInformation;//8
use App\Models\ShineCompliance\DropdownItemValue\AdditionalInformationValue;//8
use App\Models\ShineCompliance\DropdownItem\SampleComment;//9
use App\Models\ShineCompliance\DropdownItemValue\SampleCommentValue;//9
use App\Models\ShineCompliance\DropdownItem\SpecificLocation;//11
use App\Models\ShineCompliance\DropdownItemValue\SpecificLocationValue;//11
use App\Models\ShineCompliance\DropdownItem\AccessibilityVulnerability;//12
use App\Models\ShineCompliance\DropdownItemValue\AccessibilityVulnerabilityValue;//12
use App\Models\ShineCompliance\DropdownItem\LicensedNonLicensed;//13
use App\Models\ShineCompliance\DropdownItemValue\LicensedNonLicensedValue;//13
use App\Models\ShineCompliance\DropdownItem\UnableToSample;//14
use App\Models\ShineCompliance\DropdownItemValue\UnableToSampleValue;//14
use App\Models\ShineCompliance\DropdownItem\ItemNoAccess;//15
use App\Models\ShineCompliance\DropdownItemValue\ItemNoAccessValue;//15
use App\Models\ShineCompliance\DropdownItem\NoACMComments;//16
use App\Models\ShineCompliance\DropdownItemValue\NoACMCommentsValue;//16
use App\Models\ShineCompliance\DropdownItem\PriorityAssessmentRisk;//18
use App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue;//18
use App\Models\ShineCompliance\DropdownItem\MaterialAssessmentRisk;//19
use App\Models\ShineCompliance\DropdownItemValue\MaterialAssessmentRiskValue;//19
use App\Models\ShineCompliance\DropdownItem\SampleId;//500
use App\Models\ShineCompliance\DropdownItemValue\SampleIdValue;//500
use App\Models\ShineCompliance\DropdownItem\SubSampleId;//502
use App\Models\ShineCompliance\DropdownItemValue\SubSampleIdValue;//502

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

    public function getItemsByPropertyIds($property_ids,$decommissioned = 0)
    {
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        return $this->model->whereIn('property_id', $property_ids)
                ->where('survey_id', 0)
                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                ->where('decommissioned', $decommissioned)
                ->get();
    }

    public function getFirstHighRiskItem($property_id){
        return $this->model->where('state', '!=', ITEM_NOACM_STATE)->where(['decommissioned' => ITEM_UNDECOMMISSION, 'property_id' => $property_id, 'survey_id' => 0, 'total_mas_risk' => 12])->first();
    }

    public function getCountItemsByPropertyIds($client_id, $decommissioned = 0, $min, $max)
    {
        $condition = "";
        if ($client_id != 0) {
            $condition = "AND p.client_id = $client_id";
        }
        $sql = "SELECT count(i.id) as countId FROM `tbl_items` as i
                LEFT JOIN tbl_property as p ON p.id = i.property_id
                WHERE (i.survey_id = 0)
                AND (i.decommissioned = $decommissioned)
                AND (i.`state` = 3)
                $condition
                AND i.total_mas_risk BETWEEN $min AND $max";

        $results = collect(DB::select($sql))->first();
        return $results;
    }

    public function getCountItemInaccessibleState($client_id, $decommissioned = 0)
    {
        $condition = "";
        if ($client_id != 0) {
            $condition = "AND p.client_id = $client_id";
        }
        $sql = "SELECT count(i.id) as countId
                FROM `tbl_items` as i
                LEFT JOIN tbl_property as p ON p.id = i.property_id
                WHERE (i.survey_id = 0)
                AND (i.decommissioned = $decommissioned)
                $condition
                AND (i.`state` = 2)";

        $results = collect(DB::select($sql))->first();
        return $results;
    }

    public function getCountItemNoAcm($client_id, $decommissioned = 0)
    {
        $condition = "";
        if ($client_id != 0) {
            $condition = "AND p.client_id = $client_id";
        }
        $sql = "SELECT count(i.id) as countId
                FROM `tbl_items` as i
                LEFT JOIN tbl_property as p ON p.id = i.property_id
                WHERE (i.survey_id = 0)
                AND (i.decommissioned = $decommissioned)
                $condition
                AND (i.`state` = 1)";
        $results = collect(DB::select($sql))->first();
        return $results;
    }

    public function getFindItem($relation,$id){
        return $this->model->with($relation)->find($id);
    }
    public function getFirstRemedialItem($property_id){
        return $this->model->with('ActionRecommendationValue')->whereHas('ActionRecommendationValue', function ($query) use($property_id) {
            return $query->whereIn('dropdown_data_item_id', ACTION_RECOMMENDATION_LIST_ID)->where(['property_id' => $property_id, 'survey_id' => 0, 'decommissioned' => ITEM_UNDECOMMISSION])->where('state', '!=', ITEM_NOACM_STATE);
        })->first();
    }

    public function getItemArea($area_id){
        return $this->model->where('area_id', $area_id)->get();
    }

    public function getItemOs($record_id , $survey_id){
        return $this->model->where('record_id', $record_id)->where('survey_id', $survey_id)->first();
    }

    public function getItembyLocation($location_id){
        return $this->model->where('location_id', $location_id)->get();
    }

    public function decommission($property_id, $type)
    {
        return $this->model->where('property_id', $property_id)->update(['decommissioned' => $type]);
    }

    public function decommissionArea($area_id)
    {
        return $this->model->where('area_id', $area_id)->update(['decommissioned' => ITEM_DECOMMISSION]);
    }

    public function recommissionArea($area_id,$reason)
    {
        return $this->model->where('area_id', $area_id)->update(['decommissioned' => ITEM_UNDECOMMISSION,'decommissioned_reason' => $reason]);
    }

    public function decommissionLocation($location_id)
    {
        return $this->model->where('location_id', $location_id)->update(['decommissioned' => ITEM_DECOMMISSION]);
    }

    public function recommissionLocation($location_id,$reason)
    {
        return $this->model->where('location_id', $location_id)->update(['decommissioned' => ITEM_UNDECOMMISSION,'decommissioned_reason' => $reason]);
    }

    public function decommissionItem($item_id,$reason)
    {
        return $this->model->where('id', $item_id)->update(['decommissioned' => ITEM_DECOMMISSION,'decommissioned_reason' => $reason]);
    }

    public function getMutipleInaccessibleItem($condition)
    {
        $arr_item_ids =  $this->model->where($condition)->pluck('id')->toArray();
        return $arr_item_ids ?? [];
    }

    public function recommissionItem($item_id,$reason)
    {
        return $this->model->where('id', $item_id)->update(['decommissioned' => ITEM_UNDECOMMISSION,'decommissioned_reason' => $reason]);
    }

    public function getItemCommentbyId($id)
    {
        return ItemComment::find($id);;
    }

    public function updateItemInfo($record_id, $comment)
    {
        return ItemInfo::where('item_id', $record_id)->update(['comment' => $comment]);
    }

    public function getItemByAreaIds($area_ids)
    {
        return Item::with('productDebrisView','area','location')->whereIn('area_id', $area_ids)->where('decommissioned', 0)->get();
    }

    public function getItemInProperty($property_id)
    {
        return Item::with('productDebrisView','area','location')->where('property_id', $property_id)->where('decommissioned', 0)->where('survey_id', 0)->get();
    }

    public function loadDropdownText($dropdown_item_id, $parent_id = 0) {
        $data = null;
        switch ($dropdown_item_id) {
            case PRODUCT_DEBRIS_TYPE_ID:
                $data =  ProductDebrisType::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->orderBy('description')->get();
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

    public function getSamplesItem($property_id, $survey_id) {

        $survey_samples = \DB::select("SELECT
                                              sv.dropdown_data_item_id as id,
                                              sp.reference,
                                              sp.description,
                                              sp.original_item_id,
                                              sp.id as sample_id,
                                              i.reference as item_reference,
                                               i.record_id,
                                               i.reference as item_ref,
                                               i.id as item_id,
                                             i.survey_id as survey_id

                                        FROM
                                                tbl_items AS i
                                                JOIN tbl_item_sample_id_value AS sv ON i.id = sv.item_id
                                                JOIN tbl_sample sp ON sp.id = sv.dropdown_data_item_id
                                        WHERE i.survey_id = $survey_id
                                        AND  i.property_id = $property_id
                                        AND i.decommissioned = 0
                                        ");
        return $survey_samples;
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

    public function updateOrCreateItemInfo($data, $item_id) {
        return ItemInfo::updateOrCreate(['item_id' => $item_id], $data);
    }

    public function findSample($id) {
    return Sample::find($id);
    }

    public function createSample($data) {
        return Sample::create($data);
    }

    public function updateSample($data,$id) {
        return Sample::where('id', $id)->update($data);
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
    public function searchItem($q, $survey_id = 0){
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        return $this->model->whereRaw("(name LIKE '%$q%' OR reference LIKE '%$q%')")
            ->where('survey_id','=',$survey_id)
            ->where('decommissioned','=',0)
            ->orderBy('name','asc')->limit(LIMIT_SEARCH)->get();
    }
     public function getSamplesTable($property_id, $survey_id){

         $survey_samples = \DB::select("

                                SELECT
                                    tmp.id,
                                    tmp.reference,
                                    tmp.description,
                                    tmp.original_item_id,
                                    tmp.sample_id,
                                    GROUP_CONCAT( IF ( tmp.record_id = tmp.original_item_id, CONCAT( tmp.item_ref, '(OS)' ), tmp.item_ref ) ORDER BY tmp.is_os desc) AS item_reference,
                                    GROUP_CONCAT( tmp.item_id ORDER BY tmp.is_os desc) AS item_ids,
                                    tmp.product_debris ,
                                    tmp.asbestos_type,
                                    tmp.location_reference,
                                    tmp.location_id,
                                    tmp.original_state,
                                    tmp.survey_id,
                                    tmp.is_real FROM
                                        (   SELECT
                                              sv.dropdown_data_item_id as id,
                                              sp.reference,
                                              sp.description,
                                              sp.original_item_id,
                                              sv.dropdown_data_item_id as sample_id,
                                               i.record_id,
                                               i.reference as item_ref,
                                               i.id as item_id,
                                               i2.state as original_state,
                                                ipd.product_debris ,
                                                iab.asbestos_type,
                                                l.location_reference AS location_reference,
                                                l.id AS location_id,
                                                i.survey_id as survey_id,
                                                sp.is_real,
                                                IF(i.record_id = sp.original_item_id, 1, 0) as is_os
                                        FROM
                                                tbl_items AS i
                                                JOIN tbl_item_sample_id_value AS sv ON i.id = sv.item_id
                                                JOIN tbl_sample sp ON sp.id = sv.dropdown_data_item_id
                                                JOIN tbl_items i2 on i2.record_id = sp.original_item_id
                                                JOIN tbl_location l ON i2.location_id = l.id
                                                JOIN tbl_item_product_debris_view ipd ON ipd.item_id = i2.id
                                                JOIN tbl_item_asbestos_type_view iab ON iab.item_id = i2.id
                                        WHERE
                                                i.survey_id = $survey_id
                                        AND
                                                i2.survey_id = $survey_id
                                        AND
                                                i.decommissioned = 0
                                        AND
                                                i2.decommissioned = 0
                                        ORDER BY is_os DESC
                                        ) tmp

                                GROUP BY
                                    tmp.sample_id
                                 ");
         return $survey_samples ?? [];
     }

    public function sortItemSurvey($data){
        $result = [];
        if (count($data)) {
            $arr_temp = [];
            foreach ($data as &$item) {
                $intAreaRef = preg_replace("/(?!^\-)[^a-zA-Z0-9]+/", ".", trim($item->area->area_reference));
                $intAreaRef = $intAreaRef ?? $item->area->id;
                $intLocRef = preg_replace("/(?!^\-)[^a-zA-Z0-9]+/", ".", trim($item->location_reference));
                $intLocRef = $intLocRef ?? $item->id;
                $location_order[$intAreaRef][$item->area->id][$intLocRef][$item->id][] = $item;

                $intAreaRef = trim(explode(" ", $item->area->area_reference ?? '')[0]).($item->area->id ?? 0);
                $intLocRef = trim(explode(" ", $item->location->location_reference ?? '')[0]).($item->location->id ?? 0);
                //register pdf will sort from high risk to low risk
                if($item->total_risk >= 20 && $item->total_risk < 25) {
                    $arr_temp[$intAreaRef][$item->area->id][$intLocRef][$item->location->id][1][] = $item;

                } else if($item->total_risk >= 14 && $item->total_risk < 20) {
                    $arr_temp[$intAreaRef][$item->area->id][$intLocRef][$item->location->id][2][] = $item;

                } else if($item->total_risk >= 10 && $item->total_risk < 14) {
                    $arr_temp[$intAreaRef][$item->area->id][$intLocRef][$item->location->id][3][] = $item;

                } else if($item->total_risk < 10 && $item->total_risk > 0) {
                    $arr_temp[$intAreaRef][$item->area->id][$intLocRef][$item->location->id][4][] = $item;

                } else  {
                    $arr_temp[$intAreaRef][$item->area->id][$intLocRef][$item->location->id][5][] = $item;
                }
            }

            if(count($arr_temp)){
                ksort($arr_temp);
                foreach ($arr_temp as $k1 => $v1) {
                    ksort($v1);
                    foreach ($v1 as $k2 => $v2) {
                        ksort($v2);
                        foreach ($v2 as $v3) {
                            ksort($v3);
                            foreach ($v3 as $v4) {
                                ksort($v4);
                                foreach ($v4 as $v5) {
                                    ksort($v5);
                                    foreach ($v5 as $v6) {
                                        $result[] = $v6;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function updateSampleSurvey($sample_id,$description,$comment){
        $data_sample = Sample::where('id', $sample_id)->update(['description' => $description, 'comment_other' => $comment]);
        return $data_sample ?? [];
    }

    public function updateMutiplePriorityAssessmentRiskValue($list_item_id,$data){
        $data = $this->model->updateMutiplePriorityAssessmentRiskValue($list_item_id,$data);
        return $data ?? [];
    }

    public function updateMutipleMaterialAssessmentRiskValue($list_item_id,$data){
        $data = $this->model->updateMutipleMaterialAssessmentRiskValue($list_item_id,$data);
        return $data ?? [];
    }

    public function updateMutipleActionRecommendationValue($list_item_id,$data){
        $data = $this->model->updateMutipleActionRecommendationValue($list_item_id,$data);
        return $data ?? [];
    }
}

<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Item extends ModelBase
{
    protected $table = 'tbl_items';

    protected $fillable = [
        'record_id',
        'reference',
        'name',
        'area_id',
        'location_id',
        'name',
        'property_id',
        'survey_id',
        'state',
        'version',
        'is_locked',
        'total_risk',
        'total_mas_risk',
        'total_pas_risk',
        'decommissioned',
        'decommissioned_reason',
        'not_assessed',
        'not_assessed_reason',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
    protected $hidden = ['created_at','survey_id'];

    public function property() {
        return $this->belongsTo('App\Models\ShineCompliance\Property','property_id','id');
    }

    public function itemInfo() {
        return $this->hasOne('App\Models\ShineCompliance\ItemInfo','item_id');
    }

    public function location() {
        return $this->belongsTo('App\Models\ShineCompliance\Location','location_id', 'id');
    }

    public function survey() {
        return $this->belongsTo('App\Models\ShineCompliance\Survey','survey_id');
    }

    public function area() {
        return $this->belongsTo('App\Models\ShineCompliance\Area','area_id', 'id');
    }

    public function AsbestosTypeValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\AsbestosTypeValue','item_id','id');
    }

    public function ExtentValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\ExtentValue','item_id','id');
    }

    public function ProductDebrisTypeValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\ProductDebrisTypeValue','item_id','id');
    }

    public function ActionRecommendationValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\ActionRecommendationValue','item_id','id');
    }

    public function AdditionalInformationValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\AdditionalInformationValue','item_id','id');
    }

    public function SampleCommentValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\SampleCommentValue','item_id','id');
    }

    public function SpecificLocationValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\SpecificLocationValue','item_id','id');
    }

    public function AccessibilityVulnerabilityValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\AccessibilityVulnerabilityValue','item_id','id');
    }

    public function LicensedNonLicensedValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\LicensedNonLicensedValue','item_id','id');
    }

    public function UnableToSampleValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\UnableToSampleValue','item_id','id');
    }

    public function ItemNoAccessValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\ItemNoAccessValue','item_id','id');
    }

    public function NoACMCommentsValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\NoACMCommentsValue','item_id','id');
    }

    public function PriorityAssessmentRiskValue() {
        return $this->hasMany('App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue','item_id','id');
    }

    public function pasPrimary() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY);
    }

    public function pasSecondary() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY);
    }

    public function pasLocation() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', PRIORITY_ASSESSMENT_LOCATION_KEY);
    }

    public function pasAccessibility() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY);
    }

    public function pasExtent() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', PRIORITY_ASSESSMENT_EXTENT_KEY);
    }

    public function pasNumber() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', PRIORITY_ASSESSMENT_OCCUPANTS_KEY);
    }

    public function pasHumanFrequency() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY);
    }

    public function pasAverageTime() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY);
    }

    public function pasType() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY);
    }

    public function pasMaintenanceFrequency() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\PriorityAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY);
    }

    public function MaterialAssessmentRiskValue() {
        return $this->hasMany('App\Models\ShineCompliance\DropdownItemValue\MaterialAssessmentRiskValue','item_id','id');
    }

    public function masProductDebris() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\MaterialAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', ASSESSMENT_TYPE_KEY);
    }

    public function masDamage() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\MaterialAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', ASSESSMENT_DAMAGE_KEY);
    }

    public function masTreatment() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\MaterialAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', ASSESSMENT_TREATMENT_KEY);
    }

    public function masAsbestos() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\MaterialAssessmentRiskValue','item_id','id')->where('dropdown_data_item_parent_id', ASSESSMENT_ASBESTOS_KEY);
    }

    public function SampleIdValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\SampleIdValue','item_id','id');
    }

    public function SubSampleIdValue() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownItemValue\SubSampleIdValue','item_id','id')->groupBy('dropdown_data_item_id');
    }

    public function shineDocumentStorage() {
        return $this->hasMany('App\Models\ShineCompliance\ShineDocumentStorage','object_id', 'id')->whereIn('type',[ITEM_PHOTO, ITEM_PHOTO_LOCATION, ITEM_PHOTO_ADDITIONAL]);
    }

    public function productDebrisView() {
        return $this->hasOne('App\Models\ShineCompliance\View\ItemProductDebirsView', 'item_id','id');
    }

    public function asbestosTypeView() {
        return $this->hasOne('App\Models\ShineCompliance\View\ItemAsbestosTypeView', 'item_id','id');
    }

    public function accessibilityVulnerabilityView() {
        return $this->hasOne('App\Models\ShineCompliance\View\ItemAccessibilityVulnerabilityView', 'item_id','id');
    }

    public function actionRecommendationView() {
        return $this->hasOne('App\Models\ShineCompliance\View\ItemActionRecommendationView', 'item_id','id');
    }

    public function additionalInformationView() {
        return $this->hasOne('App\Models\ShineCompliance\View\ItemAdditionalInformationView', 'item_id','id');
    }

    public function extentView() {
        return $this->hasOne('App\Models\ShineCompliance\View\ItemExtentView', 'item_id','id');
    }

    public function licensedNonLicensedView() {
        return $this->hasOne('App\Models\ShineCompliance\View\ItemLicensedNonLicensedView', 'item_id','id');
    }

    public function specificLocationView() {
        return $this->hasOne('App\Models\ShineCompliance\View\ItemSpecificLocationView', 'item_id','id');
    }

    public function sample()
    {
        return $this->hasOneThrough('App\Models\ShineCompliance\Sample', 'App\Models\ShineCompliance\DropdownItemValue\SampleIdValue', 'item_id', 'id', 'id', 'dropdown_data_item_id');
    }

    public function commentHistory() {
        return $this->hasMany('App\Models\ShineCompliance\ItemComment','record_id','id')->orderBy('tbl_item_comment.created_at','desc');;
    }

    public function decommissionCommentHistory() {
        return $this->hasMany('App\Models\ShineCompliance\DecommissionComment','record_id','id')->where('category','item');
    }

    public function recommissionCommentHistory() {
        return $this->hasMany('App\Models\ShineCompliance\DecommissionComment','record_id','id')->where('type', 'recommission')->where('category','item');
    }

    public function decommissionedReason()
    {
        return $this->hasOne('App\Models\ShineCompliance\DecommissionReason','id','decommissioned_reason','id');
    }

    public function notAssessedReason()
    {
        return $this->hasOne('App\Models\ShineCompliance\DecommissionReason','id','not_assessed_reason');
    }

    public function firstAcmFullAccess() {
        return $this->hasOne('App\Models\ShineCompliance\ItemInfo','item_id')->where('tbl_items_info.assessment',ITEM_FULL_ASSESSMENT);
    }

    public function firstAcmLimitAccess() {
        return $this->hasOne('App\Models\ShineCompliance\ItemInfo','item_id')->where('tbl_items_info.assessment',ITEM_LIMIT_ASSESSMENT);
    }

    public function client()
    {
        return $this->hasOneThrough('App\Models\ShineCompliance\Client', 'App\Models\ShineCompliance\Property', 'id', 'id', 'property_id', 'client_id');
    }

    public function zone()
    {
        return $this->hasOneThrough('App\Models\ShineCompliance\Zone', 'App\Models\ShineCompliance\Property', 'id', 'id', 'property_id', 'zone_id');
    }

    public function getStateTextAttribute() {
        $state = $this->attributes['state'];
        switch ($state) {
            case ITEM_ACCESSIBLE_STATE:
                $state_text = 'Accessible';
                break;

            case ITEM_INACCESSIBLE_STATE:
                $state_text = 'Inaccessible';
                break;

            case ITEM_NOACM_STATE:
                $state_text = 'No ACM';
                break;

            default:
                $state_text = '';
                break;
        }

        return $state_text;
    }

    public function getoverallRiskAttribute() {
        if ($this->attributes['state'] == 0) {
            return 'inaccessibleItems';
        }
        $risk  = $this->attributes['total_risk'];
        switch (true) {
            case $risk == 0:
                $text = "NoRisk";
                break;
            case $risk < 10:
                $text = "veryLowRiskItems";
                break;
            case $risk < 14:
                $text = "lowRiskItems";
                break;
            case $risk < 20:
                $text = "MediumRiskItems";
                break;
            case $risk < 25:
                $text = "highRiskItems";
                break;
            default:
                $text = '';
                break;
        }
        return $text;
    }

    public function getProductDebrisTextAttribute() {
        $description = [];
        $dataValue =  $this->ProductDebrisTypeValue()->first();

        if (!is_null($dataValue)) {
            if(!is_null($dataValue->dropdown_other) and $dataValue->dropdown_other !== '<null>') {
                $description = [$dataValue->dropdown_other];
            }
            $dataDropdown = app('App\Models\ShineCompliance\DropdownItem\ProductDebrisType')::with('allParents')->where('id', $dataValue->dropdown_data_item_id)->first();
            array_unshift($description, $this->getallParents($dataDropdown));
        }
        return str_replace('Other','',(implode(" ",$description)));

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

    public function getTotalPasRiskAttribute() {
        $state = $this->attributes['state'];
        if ($state == ITEM_NOACM_STATE) {
            return 0;
        } else {
            return $this->attributes['total_pas_risk'];
        }
    }

    public function updateMutiplePriorityAssessmentRiskValue($list_item_id,$data){
        return app('App\Models\ShineCompliance\DropdownItem\PriorityAssessmentRiskValue')::whereIn('item_id', $list_item_id)->update($data);
    }

    public function updateMutipleMaterialAssessmentRiskValue($list_item_id,$data){
        return app('App\Models\ShineCompliance\DropdownItem\MaterialAssessmentRiskValue')::whereIn('item_id', $list_item_id)->update($data);
    }

    public function updateMutipleActionRecommendationValue($list_item_id,$data){
        return app('App\Models\ShineCompliance\DropdownItem\ActionRecommendationValue')::whereIn('item_id', $list_item_id)->update($data);
    }
}

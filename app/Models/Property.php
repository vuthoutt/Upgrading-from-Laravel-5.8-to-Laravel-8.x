<?php

namespace App\Models;

use App\Models\ModelBase;
use Illuminate\Support\Facades\DB;

class Property extends ModelBase
{
    protected $table = 'tbl_property';

    public static $PROGRAMME_TYPE_OTHER = 17;

    protected $fillable = [
        'reference',
        'client_id',
        'zone_id',
        'property_reference',
        'name',
        'decommissioned',
        'comments',
        'programme_phase',
        'register_updated',
        'pblock',
        'core_code',
        'service_area_id',
        'parent_reference',
        'parent_id',
        'ward_id',
        'estate_code',
        'asset_class_id',
        'asset_type_id',
        'tenure_type_id',
        'communal_area_id',
        'responsibility_id',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    public function propertyInfo() {
        return $this->hasOne('App\Models\PropertyInfo','property_id','id');
    }

    public function propertySurvey() {
        return $this->hasOne('App\Models\PropertySurvey','property_id','id');
    }

    public function zone() {
        return $this->hasOne('App\Models\Zone','id','zone_id');
    }

    public function parents() {
        return $this->hasOne('App\Models\Property', 'id', 'parent_id');
    }

    public function propertyType() {
        return $this->belongsToMany('App\Models\PropertyType', 'property_property_type','property_id','property_type_id');
    }

    public function clients() {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }

    public function sitePlanDocuments() {
        return $this->hasMany('App\Models\SitePlanDocument','property_id','id')->where('survey_id', 0)->where('category', 0);
    }

    public function historicalDocCategory() {
        return $this->hasMany('App\Models\HistoricDocCategory','property_id','id');
    }

    public function historicalDoc() {
        return $this->hasMany('App\Models\HistoricDoc','property_id','id');
    }

    public function propertyDropdownValue() {
        return $this->hasOne('App\Models\PropertyPropertyDropdownValue','property_id','dropdown_id');
    }

    public function project() {
        return $this->hasMany('App\Models\Project','property_id','id');
    }

    public function commentHistory() {
        return $this->hasMany('App\Models\PropertyComment','record_id','id')->orderBy('tbl_property_comment.created_at','desc');;
    }

    public function completeMsSurvey()
    {
        return $this->hasOne('App\Models\Survey',  'property_id', 'id')
            ->where(['tbl_survey.status' => COMPLETED_SURVEY_STATUS, 'tbl_survey.survey_type' => MANAGEMENT_SURVEY, 'decommissioned' => SURVEY_UNDECOMMISSION]);
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function completeRsSurvey()
    {
        return $this->hasOne('App\Models\Survey',  'property_id', 'id')
            ->where(['tbl_survey.status' => COMPLETED_SURVEY_STATUS, 'tbl_survey.survey_type' => REFURBISHMENT_SURVEY, 'decommissioned' => SURVEY_UNDECOMMISSION]);
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function itemACM()
    {
        return $this->hasOne('App\Models\Item',  'property_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_ACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_ACCESSIBLE_STATE .')');
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function itemInaccACM()
    {
        return $this->hasOne('App\Models\Item',  'property_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_INACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_INACCESSIBLE_STATE .')');
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function locationInaccessible(){
        return $this->hasOne('App\Models\Location', 'property_id', 'id')->where(['tbl_location.survey_id' => 0, 'tbl_location.decommissioned' => 0, 'tbl_location.state' => 0]);
    }

    public function serviceArea() {
        return $this->hasOne('App\Models\ServiceArea','id','service_area_id');
    }
    public function ward() {
        return $this->hasOne('App\Models\Ward','id','ward_id');
    }

    public function assetClass() {
        return $this->hasOne('App\Models\AssetClass','id','asset_class_id');
    }

    public function assetType() {
        return $this->hasOne('App\Models\AssetClass','id','asset_type_id');
    }

    public function tenureType() {
        return $this->hasOne('App\Models\TenureType','id','tenure_type_id');
    }

    public function communalArea() {
        return $this->hasOne('App\Models\CommunalArea','id','communal_area_id');
    }

    public function responsibility() {
        return $this->hasOne('App\Models\Responsibility','id','responsibility_id');
    }

    public function getRiskTypeTextAttribute() {
        $risk_type_text = '';
        $temp = [];
        if(isset($this->propertyType) && !$this->propertyType->isEmpty()){
            foreach ($this->propertyType as $risk_type){
                $temp[] = $risk_type->description;
            }
            $risk_type_text = implode(", ", $temp);
        }
        return $risk_type_text;
    }

    public function decommissionedReason()
    {
        return $this->belongsTo('App\Models\DecommissionReason','decommissioned_reason','id');
    }

}

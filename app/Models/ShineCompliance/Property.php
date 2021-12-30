<?php

namespace App\Models\ShineCompliance;

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
        'decommissioned_reason',
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
        'region_id',
        'local_authority',
        'cluster_reference',
        'core_reference',
        'communal_area_id',
        'responsibility_id',
        'division_id',
        'building_category',
        'division_id',
        'is_compliance',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    public function parents() {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public function subProperty() {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function propertyInfo() {
        return $this->hasOne('App\Models\ShineCompliance\PropertyInfo','property_id','id');
    }

    public function propertySurvey() {
        return $this->hasOne('App\Models\ShineCompliance\PropertySurvey','property_id','id');
    }

    public function zone() {
        return $this->hasOne('App\Models\ShineCompliance\Zone','id','zone_id');
    }

    public function propertyType() {
        return $this->belongsToMany('App\Models\ShineCompliance\PropertyType', 'property_property_type','property_id','property_type_id');
    }

    public function clients() {
        return $this->belongsTo('App\Models\ShineCompliance\Client', 'client_id', 'id');
    }

    public function sitePlanDocuments() {
        return $this->hasMany('App\Models\ShineCompliance\SitePlanDocument','property_id','id')->where('survey_id', 0)->where('category', 0);
    }

    public function historicalDocCategory() {
        return $this->hasMany('App\Models\ShineCompliance\HistoricDocCategory','property_id','id');
    }

    public function historicalDoc() {
        return $this->hasMany('App\Models\ShineCompliance\HistoricDoc','property_id','id');
    }

    public function propertyDropdownValue() {
        return $this->hasOne('App\Models\ShineCompliance\PropertyPropertyDropdownValue','property_id','dropdown_id');
    }

    public function project() {
        return $this->hasMany('App\Models\ShineCompliance\Project','property_id','id');
    }

    public function commentHistory() {
        return $this->hasMany('App\Models\ShineCompliance\PropertyComment','record_id','id')->orderBy('tbl_property_comment.created_at','desc');;
    }

    public function surveys() {
        return $this->hasMany('App\Models\ShineCompliance\Survey','property_id', 'id');
    }

    public function areas() {
        return $this->hasMany('App\Models\ShineCompliance\Area','property_id', 'id');
    }

    public function locations() {
        return $this->hasMany('App\Models\ShineCompliance\Location','property_id', 'id');
    }

    public function items() {
        return $this->hasMany('App\Models\ShineCompliance\Item','property_id', 'id');
    }

    public function completeMsSurvey()
    {
        return $this->hasOne('App\Models\ShineCompliance\Survey',  'property_id', 'id')
            ->where(['tbl_survey.status' => COMPLETED_SURVEY_STATUS, 'tbl_survey.survey_type' => MANAGEMENT_SURVEY, 'decommissioned' => SURVEY_UNDECOMMISSION]);
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function aliveSurvey()
    {
        return $this->hasMany('App\Models\ShineCompliance\Survey',  'property_id', 'id')
            ->where('tbl_survey.status', '!=', COMPLETED_SURVEY_STATUS)->where('tbl_survey.decommissioned', 0) ;
    }

    public function completeRsSurvey()
    {
        return $this->hasOne('App\Models\ShineCompliance\Survey',  'property_id', 'id')
            ->where(['tbl_survey.status' => COMPLETED_SURVEY_STATUS, 'tbl_survey.survey_type' => REFURBISHMENT_SURVEY, 'decommissioned' => SURVEY_UNDECOMMISSION]);
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function itemACM()
    {
        return $this->hasOne('App\Models\ShineCompliance\Item',  'property_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_ACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_ACCESSIBLE_STATE .')');
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function itemInaccACM()
    {
        return $this->hasOne('App\Models\ShineCompliance\Item',  'property_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_INACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_INACCESSIBLE_STATE .')');
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function locationInaccessible(){
        return $this->hasOne('App\Models\ShineCompliance\Location', 'property_id', 'id')->where(['tbl_location.survey_id' => 0, 'tbl_location.decommissioned' => 0, 'tbl_location.state' => 0]);
    }

    public function serviceArea() {
        return $this->hasOne('App\Models\ShineCompliance\ServiceArea','id','service_area_id');
    }
    public function ward() {
        return $this->hasOne('App\Models\ShineCompliance\Ward','id','ward_id');
    }

    public function assetClass() {
        return $this->hasOne('App\Models\ShineCompliance\AssetClass','id','asset_class_id');
    }

    public function assetType() {
        return $this->hasOne('App\Models\ShineCompliance\AssetClass','id','asset_type_id');
    }

    public function tenureType() {
        return $this->hasOne('App\Models\ShineCompliance\TenureType','id','tenure_type_id');
    }

    public function region() {
        return $this->hasOne('App\Models\ShineCompliance\Region','id','region_id');
    }

    public function localAuthority() {
        return $this->hasOne('App\Models\ShineCompliance\LocalAuthority','id','local_authority');
    }

    public function buildingCategory() {
        return $this->hasOne('App\Models\ShineCompliance\BuildingCategory','id','building_category');
    }

    public function division() {
        return $this->hasOne('App\Models\ShineCompliance\Division','id','division_id');
    }

    public function communalArea() {
        return $this->hasOne('App\Models\ShineCompliance\CommunalArea','id','communal_area_id');
    }

    public function responsibility() {
        return $this->hasOne('App\Models\ShineCompliance\Responsibility','id','responsibility_id');
    }

    public function vulnerableOccupant()
    {
        return $this->hasOne(PropertyVulnerableOccupant::class, 'property_id');
    }
    public function assessment()
    {
        return $this->hasMany(Assessment::class,  'property_id', 'id');
    }

    public function fireExist()
    {
        return $this->hasMany(FireExit::class,  'property_id', 'id');
    }

    public function assemblyPoint()
    {
        return $this->hasMany(AssemblyPoint::class,  'property_id', 'id');
    }

    public function vehicleParking()
    {
        return $this->hasMany(VehicleParking::class,  'property_id', 'id');
    }

    public function system()
    {
        return $this->hasMany(ComplianceSystem::class,  'property_id', 'id');
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class,  'property_id', 'id');
    }

    public function programme()
    {
        return $this->hasMany(ComplianceProgramme::class,  'property_id', 'id');
    }

    public function nonconformity()
    {
        return $this->hasMany(Nonconformity::class,  'property_id', 'id');
    }

    public function hazard()
    {
        return $this->hasMany(Hazard::class,  'property_id', 'id');
    }

    public function constructionMaterials()
    {
        return $this->belongsToMany(ConstructionMaterial::class, 'tbl_property_construction_materials', 'property_id', 'material_id')
                    ->withPivot('other');
    }

    public function equipments() {
        return $this->hasMany(Equipment::class, 'property_id', 'id')->where('cp_equipments.decommissioned', 0)->where('cp_equipments.assess_id', 0);
    }

    public function systems() {
        return $this->hasMany(ComplianceSystem::class, 'property_id', 'id')->where('compliance_systems.decommissioned', 0)->where('compliance_systems.assess_id', 0);
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
        return $this->belongsTo('App\Models\ShineCompliance\DecommissionReason','decommissioned_reason','id');
    }

}

<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Area extends ModelBase
{
    protected $table = 'tbl_area';

    protected $fillable = [
        'id',
        'reference',
        'record_id',
        'version',
        'property_id',
        'survey_id',
        'assess_id',
        'is_locked',
        'description',
        'area_reference',
        'decommissioned',
        'decommissioned_reason',
        'not_assessed',
        'not_assessed_reason',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'state',
        'reason',
        'reason_area',
    ];

    public function property() {
        return $this->belongsTo('App\Models\ShineCompliance\Property','property_id','id');
    }

    public function survey() {
        return $this->belongsTo('App\Models\ShineCompliance\Survey','survey_id','id');
    }

    public function locations() {
        return $this->hasMany('App\Models\ShineCompliance\Location','area_id','id')
            ->where('decommissioned',COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED)->where('survey_id',0)->where('assess_id',COMPLIANCE_ASSESSMENT_REGISTER);
    }

    public function naLocationRegisterEquipments() {
        return $this->hasMany('App\Models\ShineCompliance\Equipment',  'area_id', 'id')
            ->where('decommissioned',COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED)
            ->where('location_id',COMPLIANCE_ASSESSMENT_REGISTER)
            ->where('assess_id',COMPLIANCE_ASSESSMENT_REGISTER);
    }

    public function naLocationNormalHazard()
    {
        return $this->hasMany('App\Models\ShineCompliance\Hazard',  'area_id', 'id')
            ->where('decommissioned',COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED)
            ->where('assess_id',COMPLIANCE_ASSESSMENT_REGISTER)
            ->where('is_locked',COMPLIANCE_ASSESSMENT_UNLOCKED)
            ->where('is_temp',COMPLIANCE_NORMAL_HAZARD)
            ->where('location_id',COMPLIANCE_ASSESSMENT_REGISTER)
            ->where('is_deleted',COMPLIANCE_UNDELETED_HAZARD);
    }

    public function allLocations() {
        return $this->hasMany('App\Models\ShineCompliance\Location','area_id','id');
    }

    public function allItems() {
        return $this->hasMany('App\Models\ShineCompliance\Item','area_id','id');
    }

    public function reasonArea() {
        return $this->hasOne('App\Models\ShineCompliance\DropdownDataArea','id','reason');
    }

    public function decommissionedReason()
    {
        return $this->hasOne('App\Models\ShineCompliance\DecommissionReason','id','decommissioned_reason','id');
    }

    public function decommissionCommentHistory() {
        return $this->hasMany('App\Models\ShineCompliance\DecommissionComment','record_id','id')->where('category','area');
    }

    public function recommissionCommentHistory() {
        return $this->hasMany('App\Models\ShineCompliance\DecommissionComment','record_id','id')->where('type', 'recommission')->where('category','area');
    }

    public function notAssessedReason()
    {
        return $this->hasOne('App\Models\ShineCompliance\DecommissionReason','id','not_assessed_reason');
    }

    public function surveyLocations() {
        return $this->hasMany('App\Models\ShineCompliance\Location','area_id','id')->where('decommissioned',0)->where('survey_id','!=',0);
    }

    public function allAreaLocations() {
        return $this->hasMany('App\Models\ShineCompliance\Location','area_id','id')->where('survey_id','!=',0)->where('tbl_location.decommissioned', 0);
    }

    public function propertyLocations() {
        return $this->locations()->where('decommissioned',0)->where('survey_id',0);
    }

    public function getTitleAttribute($value) {
        return $this->attributes['area_reference'] . ' - ' .$this->attributes['description'];
    }

    public function getTitlePresentationAttribute($value) {
        return implode(" - ", array_filter([
            $this->attributes['area_reference'] ?? '',
            $this->attributes['description'] ?? ''
        ]));
    }

    public function itemACM()
    {
        return $this->hasOne('App\Models\ShineCompliance\Item',  'area_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_ACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_ACCESSIBLE_STATE .')');
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function itemInaccACM()
    {
        return $this->hasOne('App\Models\ShineCompliance\Item',  'area_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_INACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_ACCESSIBLE_STATE .')');
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function locationInaccessible(){
        return $this->hasOne('App\Models\ShineCompliance\Location', 'area_id', 'id')->where(['tbl_location.survey_id' => 0, 'tbl_location.decommissioned' => 0, 'tbl_location.state' => 0]);
    }
}

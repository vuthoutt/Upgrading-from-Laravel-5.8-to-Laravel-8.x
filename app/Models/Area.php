<?php

namespace App\Models;

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
    ];

    public function property() {
        return $this->belongsTo('App\Models\Property','property_id','id');
    }

    public function survey() {
        return $this->belongsTo('App\Models\Survey','survey_id','id');
    }

    public function locations() {
        return $this->hasMany('App\Models\Location','area_id','id')->where('decommissioned',0)->where('survey_id',0);
    }

    public function allLocations() {
        return $this->hasMany('App\Models\Location','area_id','id');
    }

    public function allItems() {
        return $this->hasMany('App\Models\Item','area_id','id');
    }

    public function decommissionedReason()
    {
        return $this->hasOne('App\Models\DecommissionReason','id','decommissioned_reason','id');
    }

    public function decommissionCommentHistory() {
        return $this->hasMany('App\Models\DecommissionComment','record_id','id')->where('category','area');
    }

    public function recommissionCommentHistory() {
        return $this->hasMany('App\Models\DecommissionComment','record_id','id')->where('type', 'recommission')->where('category','area');
    }

    public function notAssessedReason()
    {
        return $this->hasOne('App\Models\DecommissionReason','id','not_assessed_reason');
    }

    public function surveyLocations() {
        return $this->hasMany('App\Models\Location','area_id','id')->where('survey_id','!=',0);
    }

    public function allAreaLocations() {
        return $this->hasMany('App\Models\Location','area_id','id')->where('survey_id','!=',0)->where('tbl_location.decommissioned', 0);
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
        return $this->hasOne('App\Models\Item',  'area_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_ACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_ACCESSIBLE_STATE .')');
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function itemInaccACM()
    {
        return $this->hasOne('App\Models\Item',  'area_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_INACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_ACCESSIBLE_STATE .')');
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function locationInaccessible(){
        return $this->hasOne('App\Models\Location', 'area_id', 'id')->where(['tbl_location.survey_id' => 0, 'tbl_location.decommissioned' => 0, 'tbl_location.state' => 0]);
    }
}

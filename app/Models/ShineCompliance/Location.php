<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Location extends ModelBase
{

    //define protected variables
    public static $voidInvestigator = [
        "21" => "Ceiling Void",
        "27" => "Floor Void",
        "23" => "Cavities",
        "24" => "Risers",
        "25" => "Ducting",
        "31" => "Boxing",
        "30" => "Pipework"
    ];
    public static $voidInvestigatorTables = [
        "21" => "ceiling",
        "27" => "floor",
        "23" => "cavities",
        "24" => "risers",
        "25" => "ducting",
        "31" => "boxing",
        "30" => "pipework"
    ];
    public static $voidInvestigatorInaccessible = [
        "21" => "1108",
        "27" => "1453",
        "23" => "1216",
        "24" => "1280",
        "25" => "1344",
        "31" => "1733",
        "30" => "1606"
    ];

    protected $table = 'tbl_location';

    protected $fillable = [
        'record_id',
        'reference',
        'version',
        'area_id',
        'property_id',
        'survey_id',
        'assess_id',
        'is_locked',
        'description',
        'location_reference',
        'state',
        'decommissioned',
        'decommissioned_reason',
        'not_assessed',
        'not_assessed_reason',
        'reason_inaccess_key',
        'reason_inaccess_other',
        'photo_reference',
        'comments',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];


    public function getStateTextAttribute()
    {
        if ($this->attributes['state'] == 0) {
            return 'Inaccessible';
        }else {
            return 'Accessible';
        }
    }

    public function locationInfo() {
        return $this->hasOne('App\Models\ShineCompliance\LocationInfo','location_id');
    }

    public function locationVoid() {
        return $this->hasOne('App\Models\ShineCompliance\LocationVoid','location_id');
    }

    public function locationConstruction() {
        return $this->hasOne('App\Models\ShineCompliance\LocationConstruction','location_id');
    }

    public function items() {
        return $this->hasMany('App\Models\ShineCompliance\Item','location_id','id')->where('decommissioned',0)->where('survey_id',0);
    }

    public function surveyItems() {
        return $this->hasMany('App\Models\ShineCompliance\Item','location_id','id')->where('decommissioned',0)->where('survey_id','!=',0);
    }

    public function allSurveyItems() {
        return $this->hasMany('App\Models\ShineCompliance\Item','location_id','id')->where('survey_id','!=',0)->where('tbl_items.decommissioned', 0);
    }

    public function allItems() {
        return $this->hasMany('App\Models\ShineCompliance\Item','location_id','id')->where('decommissioned',0)->orderBy('tbl_items.total_risk','desc');
    }

    public function itemsLocation() {
        return $this->hasMany('App\Models\ShineCompliance\Item','location_id','id')->where('survey_id', 0);
    }

    public function shineDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ShineDocumentStorage','object_id', 'id')->where('type',LOCATION_IMAGE);
    }

    public function property() {
        return $this->belongsTo('App\Models\ShineCompliance\Property','property_id','id');
    }
    public function area () {
        return $this->belongsTo('App\Models\ShineCompliance\Area','area_id','id')->orderBy('area_reference');
    }

    public function survey() {
        return $this->belongsTo('App\Models\ShineCompliance\Survey','survey_id','id');
    }

    public function decommissionedReason()
    {
        return $this->hasOne('App\Models\ShineCompliance\DecommissionReason','id','decommissioned_reason','id');
    }

    public function notAssessedReason()
    {
        return $this->hasOne('App\Models\ShineCompliance\DecommissionReason','id','not_assessed_reason');
    }

    public function countItemACM(){
        return $this->hasMany('App\Models\ShineCompliance\Item',  'location_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('(tbl_items.state != '. ITEM_NOACM_STATE . ')');
    }
    // accessible ACMs
    public function allItemACM(){
        return $this->hasMany('App\Models\ShineCompliance\Item',  'location_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_ACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_ACCESSIBLE_STATE .')');
    }

    public function allInaccItemACM(){
        return $this->hasMany('App\Models\ShineCompliance\Item',  'location_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_INACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_INACCESSIBLE_STATE .')');
    }

    public function allAccessibleItemACM(){
        return $this->hasMany('App\Models\ShineCompliance\Item',  'location_id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_ACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_ACCESSIBLE_STATE .')');
    }

   public function allItemNoACM(){
       return $this->hasMany('App\Models\ShineCompliance\Item','location_id','id')
           ->where(['tbl_items.decommissioned' => ITEM_UNDECOMMISSION, 'tbl_items.state' => ITEM_NOACM_STATE]);
   }

    public function decommissionCommentHistory() {
        return $this->hasMany('App\Models\ShineCompliance\DecommissionComment','record_id','id')->where('category','location');
    }

    public function recommissionCommentHistory() {
        return $this->hasMany('App\Models\ShineCompliance\DecommissionComment','record_id','id')->where('type', 'recommission')->where('category','location');
    }

    public function commentHistory() {
        return $this->hasMany('App\Models\ShineCompliance\LocationComment','record_id','id')->orderBy('tbl_location_comment.created_at','desc');
    }
    public function client()
    {
        return $this->hasOneThrough('App\Models\ShineCompliance\Client', 'App\Models\ShineCompliance\Property', 'id', 'id', 'property_id', 'client_id');
    }

    public function zone()
    {
        return $this->hasOneThrough('App\Models\ShineCompliance\Zone', 'App\Models\ShineCompliance\Property', 'id', 'id', 'property_id', 'zone_id');
    }

    public function registerEquipments()
    {
        return $this->hasMany('App\Models\ShineCompliance\Equipment',  'location_id', 'id')
            ->where('decommissioned',COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED)->where('assess_id',COMPLIANCE_ASSESSMENT_REGISTER);
    }
    //Not auto created hazard from nonconformity
    public function normalHazard()
    {
        return $this->hasMany('App\Models\ShineCompliance\Hazard',  'location_id', 'id')
            ->where('decommissioned',COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED)
            ->where('assess_id',COMPLIANCE_ASSESSMENT_REGISTER)
            ->where('is_locked',COMPLIANCE_ASSESSMENT_UNLOCKED)
            ->where('is_temp',COMPLIANCE_NORMAL_HAZARD)
            ->where('is_deleted',COMPLIANCE_UNDELETED_HAZARD);
    }

    public function getTitlePresentationAttribute($value) {
        return implode(" - ", array_filter([
            $this->attributes['location_reference'] ?? '',
            $this->attributes['description'] ?? ''
        ]));
    }
}

<?php

namespace App\Models;

use App\Models\ModelBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends ModelBase
{
    use SoftDeletes;
    protected $table = 'tbl_zones';

    protected $fillable = [
        'id',
        'client_id',
        'reference',
        'zone_code',
        'zone_name',
        'parent_id',
        'last_revision'
    ];

    public function childrens()
    {
        return $this->hasMany('App\Models\Zone', 'parent_id', 'id');
    }

    public function allChildrens()
    {
        return $this->childrens()->with('allChildrens');
    }

    public function parents()
    {
        return $this->hasMany('App\Models\Zone', 'id', 'parent_id');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Zone', 'id', 'parent_id');
    }

    public function allParents()
    {
        return $this->parents()->with('allParents');
    }

    public function property($client_id) {
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        return $this->hasMany('App\Models\Property', 'zone_id', 'id')
                    ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'id')
                    ->where('client_id', $client_id)
                    ->get();
    }

    public function firstProperty(){
        return $this->hasOne('App\Models\Property', 'zone_id', 'id');
    }

    //https://laravel.com/docs/5.8/eloquent-relationships#has-one-through
    //not use cause property_risk type is multi select
//    public function propertyType()
//    {
//        // just one to get one PropertyType to check, not get all of them
//        return $this->hasOneThrough('App\Models\PropertyType', 'App\Models\Property', 'zone_id', 'id', 'id', 'risk_type');
//    }

    public function itemACM()
    {
        return $this->hasOneThrough('App\Models\Item', 'App\Models\Property', 'zone_id', 'property_id', 'id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_ACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_ACCESSIBLE_STATE .')');
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function itemInaccACM()
    {
        return $this->hasOneThrough('App\Models\Item', 'App\Models\Property', 'zone_id', 'property_id', 'id', 'id')
            ->where(['tbl_items.survey_id' => 0, 'tbl_items.decommissioned' => 0])->whereRaw('((tbl_items.state = '. ITEM_INACCESSIBLE_STATE .' && tbl_items.total_risk > 0) OR tbl_items.state = '.ITEM_INACCESSIBLE_STATE .')');
//        ((i.state = 'accessible' && i.totalAssessmentRisk > 0) || i.state = 'inaccessible')
    }

    public function locationInaccessible(){
        return $this->hasOneThrough('App\Models\Location', 'App\Models\Property', 'zone_id', 'property_id', 'id', 'id')->where(['tbl_location.survey_id' => 0, 'tbl_location.decommissioned' => 0, 'tbl_location.state' => 0]);
    }

    public function location(){
        return $this->hasManyThrough('App\Models\Location', 'App\Models\Property', 'zone_id', 'property_id', 'id', 'id')->where(['tbl_location.survey_id' => 0, 'tbl_location.decommissioned' => 0]);
    }

    public function bluelightService() {
        return $this->hasOne('App\Models\LastRevision','zone_id');
    }
}

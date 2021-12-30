<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Client extends ModelBase
{
    protected $table = 'tbl_clients';
    public static $USER_LOCKED = 1;
    public static $USER_UNLOCKED = 0;
    public static $USER_ADMIN = 0;
    public static $USER_OTHER = 2;

    protected $fillable = [
        'name',
        'reference',
        'client_type',
        'email',
        'email_notification',
        'key_contact',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    public function departments() {
        return $this->hasMany('App\Models\ShineCompliance\Department','client_id');
    }

    public function departments_contractor() {
        return $this->hasMany('App\Models\ShineCompliance\DepartmentContractor','client_id');
    }

    public function clientInfo() {
        return $this->hasOne('App\Models\ShineCompliance\ClientInfo','client_id');
    }

    public function clientAddress() {
        return $this->hasOne('App\Models\ShineCompliance\ClientAddress','client_id');
    }

    public function users() {
        return $this->hasMany('App\User','client_id');
    }

    public function mainUser() {
        return $this->hasOne('App\User','id', 'key_contact');
    }

    public function policy() {
        return $this->hasMany('App\Models\ShineCompliance\Policy','client_id');
    }

    public function traningRecord() {
        return $this->hasMany('App\Models\ShineCompliance\TrainingRecord','client_id');
    }

    public function getClientDescriptionAttribute(){
        return $this->attributes['name'] . ' (' . $this->attributes['reference'] . ')';
    }

    public function zones() {
        return $this->hasMany('App\Models\ShineCompliance\Zone','client_id')->where('client_id',1)->where('tbl_zones.parent_id', 0);
    }

    public function zonePrivilege() {
        return $this->hasMany('App\Models\ShineCompliance\Zone','client_id')->where('tbl_zones.parent_id', 0);
    }

    public function property($client_id) {
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        // return $this->hasMany('App\Models\Property', 'zone_id', 'id')
        //             ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'id')
        //             ->where('decommissioned', 0)
        //             ->where('client_id', $client_id)
        //             ->get();
        return $this->hasMany('App\Models\ShineCompliance\Property','client_id')
                    ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'id')
                    ->where('client_id', $client_id)
                    ->get();
    }
}

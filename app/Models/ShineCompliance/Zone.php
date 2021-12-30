<?php

namespace App\Models\ShineCompliance;

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
        'decommissioned',
        'decommissioned_reason',
        'last_revision'
    ];

    public function childrens()
    {
        return $this->hasMany('App\Models\ShineCompliance\Zone', 'parent_id', 'id');
    }

    public function allChildrens()
    {
        return $this->childrens()->with('allChildrens');
    }

    public function parents()
    {
        return $this->hasMany('App\Models\ShineCompliance\Zone', 'id', 'parent_id');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\ShineCompliance\Zone', 'id', 'parent_id');
    }

    public function allParents()
    {
        return $this->parents()->with('allParents');
    }

    public function property() {
        return $this->hasMany('App\Models\ShineCompliance\Property', 'zone_id', 'id');
    }

    public function clients() {
        return $this->belongsTo('App\Models\ShineCompliance\Client', 'client_id', 'id');
    }

    public function countAllProperty() {
        return $this->hasMany('App\Models\ShineCompliance\Property', 'zone_id','id')->count();
    }

    public function decommissionedReason()
    {
        return $this->belongsTo('App\Models\DecommissionReason','decommissioned_reason','id');
    }

}

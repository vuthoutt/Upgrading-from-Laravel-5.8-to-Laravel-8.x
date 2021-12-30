<?php

namespace App\Models;

use App\Models\ModelBase;

class Department extends ModelBase
{
    protected $table = 'tbl_departments';

    protected $fillable = [
        'id',
        'name',
        'client_id',
        'parent_id',
        'created_at',
        'updated_at',
    ];
    public function client() {
        return $this->belongsTo('App\Models\Client','client_id','id');
    }

    public function childrens()
    {
        return $this->hasMany('App\Models\Department', 'parent_id', 'id');
    }

    public function allChildrens()
    {
        return $this->childrens()->with('allChildrens');
    }

    public function parents() {
        return $this->hasOne('App\Models\Department', 'id', 'parent_id');
    }

    public function allParents() {
        return $this->parents()->with('allParents');
    }
}

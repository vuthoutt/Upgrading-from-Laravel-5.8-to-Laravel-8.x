<?php

namespace App\Models;

use App\Models\ModelBase;

class DepartmentContractor extends ModelBase
{
    protected $table = 'tbl_departments_contractor';

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
        return $this->hasMany('App\Models\DepartmentContractor', 'parent_id', 'id');
    }

    public function allChildrens()
    {
        return $this->childrens()->with('allChildrens');
    }

     public function parents() {
        return $this->hasOne('App\Models\DepartmentContractor', 'id', 'parent_id');
    }

    public function allParents() {
        return $this->parents()->with('allParents');
    }
}

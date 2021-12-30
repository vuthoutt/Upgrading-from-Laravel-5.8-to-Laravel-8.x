<?php

namespace App\Models;

use App\Models\ModelBase;

class DropdownDataLocation extends ModelBase
{
    protected $table = 'tbl_dropdown_data_location';

    protected $fillable = [
        'id',
        'description',
        'dropdown_location_id',
        'order',
        'score',
        'other',
        'decommissioned',
        'parent_id',
        'removal_cost'
    ];


    public function childrenAccounts()
    {
        return $this->hasMany('App\Models\DropdownDataLocation', 'parent_id', 'id');
    }

    public function allChildrenAccounts()
    {
        return $this->childrenAccounts()->with('allChildrenAccounts');
    }
}

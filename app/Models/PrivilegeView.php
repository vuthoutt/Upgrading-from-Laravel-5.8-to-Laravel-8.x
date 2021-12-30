<?php

namespace App\Models;

use App\Models\ModelBase;

class PrivilegeView extends ModelBase
{
    protected $table = 'tbl_privilege_view';

    protected $fillable = [
        'id',
        'route_name',
        'name',
        'type',
        'parent_id',
        'role_ids',
        'order',
        'is_deleted',
    ];

    public function childrens()
    {
        return $this->hasMany('App\Models\PrivilegeView', 'parent_id', 'id')->orderBy('order');
    }

    public function allChildrens()
    {
        return $this->childrens()->with('allChildrens');
    }
}

<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class PrivilegeView extends ModelBase
{
    protected $table = 'cp_privilege_view';

    protected $fillable = [
        'note',
        'name',
        'type',
        'parent_id',
        'order',
        'is_deleted',
    ];

    public function children()
    {
        return $this->hasMany(PrivilegeView::class, 'parent_id', 'id')->where('is_deleted', 0)->orderBy('order');
    }

    public function allChildren()
    {
        return $this->children()->with(['allChildren','privilegeChild']);
    }

    public function privilegeChild() {
        return $this->hasMany(PrivilegeChild::class,'privilege_id')->where('is_view', 1);
    }
}

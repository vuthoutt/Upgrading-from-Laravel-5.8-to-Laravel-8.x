<?php

namespace App\Models;

use App\Models\ModelBase;

class Role extends ModelBase
{
    protected $table = 'tbl_role';

    protected $fillable = [
        'name',
        'view_privilege',
        'update_privilege',
        'property',
        'project_type',
        'project_information',
        'system_owner',
        'contractor',
        'report',
        'category_box',
        'is_everything',
        'is_operative',
    ];

    public function getContractorAttribute($value)
    {
        if(isset($value)){
            return json_decode($value, true);
        }
        return [];
    }

    public function getViewPrivilegeAttribute($value)
    {
        if(isset($value)){
            return explode(",", $value);
        }
        return [];
    }

    public function getProjectTypeAttribute($value)
    {
        if(isset($value)){
            return explode(",", $value);
        }
        return [];
    }

    public function getProjectTypeRawAttribute($value)
    {
        return $this->attributes['project_type'];
    }

    public function getProjectInformationRawAttribute($value)
    {
        return $this->attributes['project_information'];
    }

    public function getProjectInformationAttribute($value)
    {
        if(isset($value)){
            return explode(",", $value);
        }
        return [];
    }

    public function getReportAttribute($value)
    {
        if(isset($value)){
            return explode(",", $value);
        }
        return [];
    }

    public function getReportRawAttribute($value)
    {
        return $this->attributes['report'];
    }

    public function getCategoryBoxAttribute($value)
    {
        if(isset($value)){
            return explode(",", $value);
        }
        return [];
    }

    public function getCategoryBoxRawAttribute($value)
    {
        return $this->attributes['category_box'];
    }

}

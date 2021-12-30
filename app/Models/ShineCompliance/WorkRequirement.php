<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class WorkRequirement extends ModelBase
{
    protected $table = 'tbl_work_requirement';

    protected $fillable = [
        'work_id',
        'site_hs',
        'hight_level_access',
        'hight_level_access_comment',
        'max_height',
        'max_height_comment',
        'loft_spaces',
        'loft_spaces_comment',
        'floor_voids',
        'floor_voids_comment',
        'basements',
        'basements_comment',
        'ducts',
        'ducts_comment',
        'lift_shafts',
        'lift_shafts_comment',
        'light_wells',
        'light_wells_comment',
        'confined_spaces',
        'confined_spaces_comment',
        'fumes_duct',
        'fumes_duct_comment',
        'pm_good',
        'pm_good_comment',
        'fragile_material',
        'fragile_material_comment',
        'hot_live_services',
        'hot_live_services_comment',
        'pieons',
        'pieons_comment',
        'vermin',
        'vermin_comment',
        'biological_chemical',
        'biological_chemical_comment',
        'vulnerable_tenant',
        'vulnerable_tenant_comment',
        'other',
        'created_at',
        'updated_at',

    ];

}

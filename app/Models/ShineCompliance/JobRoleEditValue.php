<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class JobRoleEditValue extends ModelBase
{
    protected $table = 'cp_job_roles_edit_value';

    protected $fillable = [
        'role_id',
        'common_everything',
        'common_static_values_update',
        'common_dynamic_values_update',
//        'common_property_information',
//        'common_data_centre',
//        'general_organisational',
//        'general_resources',
        'contractor',
        'client_listing',
        'organisation_listing',
        'add_group_permission',
    ];

}

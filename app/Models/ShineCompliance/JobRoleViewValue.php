<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class JobRoleViewValue extends ModelBase
{
    protected $table = 'cp_job_roles_view_value';

    protected $fillable = [
        'role_id',
        'common_everything',
        'common_static_values_view',
        'common_dynamic_values_view',
//        'common_data_centre',
//        'common_reporting',
//        'common_property_information',
//        'general_property_listing',
//        'general_email_notifications',
//        'general_organisational',
//        'general_resources',
//        'general_audit_trail',
//        'general_site_operative_view',
//        'general_view_trouble_ticket',
//        'general_work_request',
        'general_is_operative',
        'general_is_tt',
        'contractor',
        'client_listing',
        'add_group_permission',
        'organisation_listing',
    ];


}

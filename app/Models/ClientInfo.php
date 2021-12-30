<?php

namespace App\Models;

use App\Models\ModelBase;

class ClientInfo extends ModelBase
{
    protected $table = 'tbl_client_info';

    protected $fillable = [
        'client_id',
        'ukas',
        'ukas_reference',
        'ukas_testing_reference',
        'account_management_email',
        'type_surveying',
        'type_removal',
        'type_demolition',
        'type_analytical',
        'type_instructingparty',
        'contractor_setup_id',
        'type_fire_equipment',
        'type_fire_risk',
        'type_fire_remedial',
        'type_independent_survey',
        'type_legionella_risk',
        'type_water_testing',
        'type_water_remedial',
        'type_temperature',
        'type_hs_assessment',
    ];
}

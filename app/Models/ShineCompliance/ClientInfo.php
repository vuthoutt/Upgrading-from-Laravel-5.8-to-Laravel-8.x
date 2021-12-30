<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ClientInfo extends ModelBase
{
    protected $table = 'tbl_client_info';

    protected $fillable = [
        'client_id',
        'ukas',
        'ukas_reference',
        'ukas_testing_reference',
        'removal_licence_reference',
        'account_management_email',
        'type_surveying',
        'type_removal',
        'type_demolition',
        'type_analytical',
        'type_instructingparty',
        'contractor_setup_id',
    ];

    public function contractorSetup() {
        return $this->hasOne('App\Models\ShineCompliance\ContractorSetup','id', 'contractor_setup_id');
    }
}

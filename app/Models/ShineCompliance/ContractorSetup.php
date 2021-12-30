<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ContractorSetup extends ModelBase
{
    protected $table = 'tbl_contractor_setup';

    protected $fillable = [
        "description",
    ];

}

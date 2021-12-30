<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class SorLogic extends ModelBase
{
    protected $table = 'tbl_sor_logic';

    protected $fillable = [
        "id",
        "type_compare",
        "user_code",
        "sor_code",
        "sor",
        "description",
        "work_type",
        "work_type_id",
        "property_type",
        "property_type_id",
        "addition_condition",
        "priority",
        "priority_id",
        "duration",
        "price"
    ];

}

<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class RefurbDocType extends ModelBase
{
    protected $table = 'tbl_refurb_doc_types';

    protected $fillable = [

        "doc_type",
        "refurb_type",
        "order",
        "is_active",
    ];

}

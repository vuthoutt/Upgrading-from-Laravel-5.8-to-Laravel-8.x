<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class GSKDocument extends ModelBase
{
    protected $table = 'tbl_gsk_documents';

    protected $fillable = [

        'zone_id',
        'client_id',
        'name',
        'reference',
        'status',
        'type',
        'note',
        'added',
        'value',
        'created_by',


    ];
}

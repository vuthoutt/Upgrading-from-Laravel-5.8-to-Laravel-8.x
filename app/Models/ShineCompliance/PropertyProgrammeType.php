<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class PropertyProgrammeType extends ModelBase
{
    protected $table = 'tbl_property_programme_type';

    protected $fillable = [
        'id',
        'description',
        'order',
        'color',
        'other',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
        'is_deleted'
    ];


}

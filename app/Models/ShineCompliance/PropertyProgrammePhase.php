<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class PropertyProgrammePhase extends ModelBase
{
    protected $table = 'tbl_property_programme_phase';

    protected $fillable = [
        'id',
        'description',
        'order',
        'color',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];


}

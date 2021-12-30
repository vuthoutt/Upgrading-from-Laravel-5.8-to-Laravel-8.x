<?php


namespace App\Models\ShineCompliance;


use App\Models\ModelBase;

class VulnerableOccupantType extends ModelBase
{
    protected $table = 'tbl_vulnerable_occupant_types';

    protected $fillable = [
        'id',
        'description',
    ];
}

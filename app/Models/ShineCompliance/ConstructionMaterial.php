<?php


namespace App\Models\ShineCompliance;


use App\Models\ModelBase;

class ConstructionMaterial extends ModelBase
{
    protected $table = 'tbl_construction_materials';

    protected $fillable = [
        'id',
        'description',
        'order',
        'other',
    ];
}

<?php


namespace App\Models\ShineCompliance;


use App\Models\ModelBase;

class PropertyInfoDropdownData extends ModelBase
{
    protected $table = 'tbl_property_info_dropdown_data';

    protected $fillable = [
        'property_info_dropdown_id',
        'description',
        'order',
        'other',
    ];
}

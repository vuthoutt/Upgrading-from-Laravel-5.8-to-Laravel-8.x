<?php


namespace App\Models\ShineCompliance;


use App\Models\ModelBase;

class PropertyInfoDropdown extends ModelBase
{
    protected $table = 'tbl_property_info_dropdowns';

    protected $fillable = [
        'description',
        'order',
    ];

    public function propertyInfoDropdownData()
    {
        return $this->hasMany(PropertyInfoDropdownData::class, 'property_info_dropdown_id')->orderBy('order')->orderBy('id');
    }
}

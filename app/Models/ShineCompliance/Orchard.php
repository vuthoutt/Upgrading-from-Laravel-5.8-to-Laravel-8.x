<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;
use Illuminate\Support\Facades\DB;

class Orchard extends ModelBase
{
    protected $table = 'tbl_orchard';

    protected $fillable = [
        'property_reference_id',
        'property_id',
        'property_name',
        'status',
        'property_class_code',
        'property_address1',
        'property_address2',
        'property_address3',
        'property_address4',
        'property_post_code',
        'property_responsibility',
        'property_tenure_type_code',
        'property_service_area_code',
        'property_service_area_description',
        'property_tenure_type_description',
        'property_right_buy_code',
        'property_user_code',
        'property_block',
        'property_house_number',
        'property_build_date',
        'property_right_buy_description',
        'property_void',
        'date',
    ];
}

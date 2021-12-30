<?php

namespace App\Models\View;

use App\Models\ModelBase;

class ItemAdditionalInformationView extends ModelBase
{
    protected $table = 'tbl_item_additional_information_view';

    protected $fillable = [
        "item_id",
        "additional_information",
    ];

    public function getadditionalInformationAttribute () {
        $additional_information = str_replace('Other ---',' ', $this->attributes['additional_information']);
        $additional_information = str_replace('---',' ',  $additional_information);
        return $additional_information;
    }
}

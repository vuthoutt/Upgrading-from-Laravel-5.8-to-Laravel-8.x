<?php

namespace App\Models\View;

use App\Models\ModelBase;

class ItemSpecificLocationView extends ModelBase
{
    protected $table = 'tbl_item_specific_location_view';

    protected $fillable = [
        "item_id",
        "specific_location",
    ];

    public function getSpecificLocationAttribute () {
        $specific_location = str_replace('----,',' and ',  $this->attributes['specific_location']);
        $specific_location = str_replace('Other ----',' ',  $specific_location);
        $specific_location = str_replace('Other----',' ',  $specific_location);
        $specific_location = str_replace('----',' ',  $specific_location);
        $specific_location = str_replace('Wall Wall','Wall - Wall',  $specific_location);

        return $specific_location;
    }
}

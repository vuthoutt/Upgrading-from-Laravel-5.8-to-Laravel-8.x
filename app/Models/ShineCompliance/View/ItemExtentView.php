<?php

namespace App\Models\ShineCompliance\View;

use App\Models\ModelBase;

class ItemExtentView extends ModelBase
{
    protected $table = 'tbl_item_extent_view';

    protected $fillable = [
        "item_id",
        "extent",
    ];

    public function getextentAttribute () {
        $extent = str_replace('Other ---',' ',  $this->attributes['extent']);
        $extent = str_replace('---',' ',  $extent);
        return $extent;
    }
}

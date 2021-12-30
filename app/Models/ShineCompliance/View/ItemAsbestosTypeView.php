<?php

namespace App\Models\ShineCompliance\View;

use App\Models\ModelBase;

class ItemAsbestosTypeView extends ModelBase
{
    protected $table = 'tbl_item_asbestos_type_view';

    protected $fillable = [
        "item_id",
        "asbestos_type",
    ];

    public function getasbestosTypeAttribute () {
        $asbestos_type = str_replace('Other ---',' ',  $this->attributes['asbestos_type']);
        $asbestos_type = str_replace('Other---',' ',  $this->attributes['asbestos_type']);
        $asbestos_type = str_replace('---',' ',  $asbestos_type);
        $asbestos_type = str_replace(',',' and ',  $asbestos_type);
        return $asbestos_type;
    }
}

<?php

namespace App\Models;

use App\Models\ModelBase;

class HistoricDocCategory extends ModelBase
{
    protected $table = 'tbl_historicdocs_categories';

    protected $fillable = [
        "property_id",
        "category",
        "order",
    ];


    public function historicalDoc() {
        return $this->hasMany('App\Models\HistoricDoc','category','id');
    }
}

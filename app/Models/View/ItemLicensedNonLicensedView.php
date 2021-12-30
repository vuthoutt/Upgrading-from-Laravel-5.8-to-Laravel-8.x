<?php

namespace App\Models\View;

use App\Models\ModelBase;

class ItemLicensedNonLicensedView extends ModelBase
{
    protected $table = 'tbl_item_licensed_non_licensed_view';

    protected $fillable = [
        "item_id",
        "licensed_non_licensed",
    ];

    public function getlicensedNonLicensedAttribute () {
        $licensed_non_licensed = str_replace('Other ---',' ', $this->attributes['licensed_non_licensed']);
        $licensed_non_licensed = str_replace('---',' ',  $licensed_non_licensed);
        return $licensed_non_licensed;
    }
}

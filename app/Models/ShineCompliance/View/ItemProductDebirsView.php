<?php

namespace App\Models\ShineCompliance\View;

use App\Models\ModelBase;

class ItemProductDebirsView extends ModelBase
{
    protected $table = 'tbl_item_product_debris_view';

    protected $fillable = [
        "item_id",
        "product_debris",
    ];

    public function getProductDebrisAttribute () {
        $product_debris = str_replace('Non-asbestos ---','',  $this->attributes['product_debris']);
        $product_debris = str_replace('Asbestos ---','',  $product_debris);
        $product_debris = str_replace('Other ---',' ',  $product_debris);
        $product_debris = str_replace('Other---',' ',  $product_debris);
        $product_debris = str_replace('---',' ',  $product_debris);
        return $product_debris;
    }
}

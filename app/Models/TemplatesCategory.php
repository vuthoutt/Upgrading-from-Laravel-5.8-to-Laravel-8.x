<?php

namespace App\Models;

use App\Models\ModelBase;

class TemplatesCategory extends ModelBase
{
    protected $table = 'tbl_templates_categories';
    public $timestamps = false;
    protected $fillable = [
        "category",
        "order",
    ];


    public function template() {
        return $this->hasMany('App\Models\Template','category_id','id');
    }
}

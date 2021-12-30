<?php

namespace App\Models;

use App\Models\ModelBase;

class TemplateDocument extends ModelBase
{

    protected $table = 'tbl_templates_categories';

    protected $fillable = [
        'category',
        'order',
    ];
    public $timestamps = false;
    public function template() {
        return $this->hasMany('App\Models\Template','category_id','id');
    }

}

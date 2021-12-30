<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class TemplateDocument extends ModelBase
{
    protected $table = 'tbl_templates_categories';

    protected $fillable = [
        'category',
        'order',
    ];

    public function template() {
        return $this->hasMany('App\Models\Template','category_id','id');
    }

}

<?php

namespace App\Models;

use App\Models\ModelBase;

class Sample extends ModelBase
{
    protected $table = 'tbl_sample';

    protected $fillable = [
        "reference",
        "is_real",
        "description",
        "comment_key",
        "comment_other",
        "original_item_id",
        "decommissioned"

    ];

    public function items() {
        return $this->belongsTo('App\Models\Item', 'original_item_id', 'record_id');
    }

    public function sampleIdValue() {
        return $this->hasMany('App\Models\DropdownItemValue\SampleIdValue', 'dropdown_data_item_id', 'id');
    }

    public function sampleComment() {
        return $this->belongsTo('App\Models\DropdownItem\SampleComment', 'comment_key', 'id');
    }
}

<?php

namespace App\Models\DropdownItemValue;

use App\Models\ModelBase;

class MaterialAssessmentRiskValue extends ModelBase
{
    protected $table = 'tbl_item_material_assessment_risk_value';

    protected $fillable = [
        "item_id",
        "dropdown_item_id",
        "dropdown_data_item_parent_id",
        "dropdown_data_item_id",
        "dropdown_other",
        "deleted_by",
        "created_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    protected $hidden = ['item_id','id'];

    public function getData(){
        return $this->belongsTo('App\Models\DropdownItem\MaterialAssessmentRisk', 'dropdown_data_item_id', 'id');
    }
}

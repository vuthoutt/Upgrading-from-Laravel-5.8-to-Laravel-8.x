<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ProjectRiskClassification extends ModelBase
{
    protected $table = 'cp_project_risk_classification';

    protected $fillable = [
        "description",
    ];

    public function projectType(){
        return $this->hasMany('App\Models\ShineCompliance\ProjectType', 'compliance_type', 'id');
    }
}

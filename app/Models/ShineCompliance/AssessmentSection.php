<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class AssessmentSection extends ModelBase
{
    protected $table = 'cp_assessment_sections';

    protected $fillable = [
        'description',
        'order',
        'type',
        'parent_id',
    ];

    public function questions() {
        return $this->hasMany(AssessmentQuestion::class, 'section_id', 'id');
    }

    public function children() {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function resultScore() {
        return $this->hasOne(AssessmentResult::class, 'section_id', 'id');
    }
}

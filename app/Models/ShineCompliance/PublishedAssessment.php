<?php


namespace App\Models\ShineCompliance;


use App\Models\ModelBase;

class PublishedAssessment extends ModelBase
{
    protected $table = 'cp_published_assessments';

    protected $fillable = [
        'assess_id',
        'name',
        'revision',
        'size',
        'filename',
        'mime',
        'path',
        'deleted_by',
        'created_by',
        'deleted_at',
    ];

    public function assessment() {
        return $this->hasOne('App\Models\ShineCompliance\Assessment','id', 'assess_id');
    }
}

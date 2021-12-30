<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentSample extends Model
{
    protected $table = 'cp_assessment_samples';

    protected $fillable = [
        'property_id',
        'sample_reference',
        'reference',
        'assess_id',
        'document_present',
        'description',
        'date',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assess_id');
    }
    public function shineDocumentStorage() {
        return $this->hasOne('App\Models\ShineDocumentStorage','object_id', 'id')->where('type',SAMPLE_CERTIFICATE_ASSESSMENT);
    }
}

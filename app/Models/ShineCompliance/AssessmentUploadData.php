<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentUploadData extends Model
{
    protected $table = 'cp_assessment_upload_data';

    protected $fillable = [
        'manifest_id',
        'assess_id',
        'status',
        'data',
    ];

    public function manifest()
    {
        return $this->belongsTo(AssessmentUploadManifest::class, 'manifest_id');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assess_id');
    }
}

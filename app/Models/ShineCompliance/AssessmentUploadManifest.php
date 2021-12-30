<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentUploadManifest extends Model
{
    protected $table = 'cp_assessment_upload_manifests';

    protected $fillable = [
        'id',
        'assess_id',
        'assessor_id',
        'status',
    ];

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function assessment()
    {
        return $this->hasOne(Assessment::class, 'id', 'assess_id');
    }
}

<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentUploadImage extends Model
{
    protected $table = 'cp_assessment_upload_images';

    protected $fillable = [
        'assess_id',
        'manifest_id',
        'image_type',
        'file_name',
        'path',
        'mime',
        'size',
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

<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class ApiAssessmentBackupManifest extends Model
{
    protected $table = 'cp_assessment_backup_manifests';

    protected $fillable = [
        "assess_id",
        "assessor_id",
        "status",
    ];

    public function backupData()
    {
        return $this->hasOne(ApiAssessmentBackup::class, 'backup_id', 'id');
    }

    public function uploadImages() {
        return $this->hasMany(ApiAssessmentBackupImage::class, 'backup_id', 'id');
    }
}

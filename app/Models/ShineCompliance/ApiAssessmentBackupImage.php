<?php

namespace App\Models\ShineCompliance;

use Illuminate\Database\Eloquent\Model;

class ApiAssessmentBackupImage extends Model
{
    protected $table = 'cp_assessment_backup_images';

    protected $fillable = [
        'backup_id',
        'app_id',
        'type',
        'path',
        'file_name',
        'size',
    ];
}

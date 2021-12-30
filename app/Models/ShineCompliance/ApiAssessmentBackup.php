<?php

namespace App\Models\ShineCompliance;

use Illuminate\Database\Eloquent\Model;

class ApiAssessmentBackup extends Model
{
    protected $table = 'cp_assessment_backup_data';

    protected $fillable = [
        "backup_id",
        "user_id",
        "path",
        "file_name",
        "size",
    ];

}

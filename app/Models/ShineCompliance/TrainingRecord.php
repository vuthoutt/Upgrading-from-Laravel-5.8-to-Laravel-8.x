<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class TrainingRecord extends ModelBase
{
    protected $table = 'tbl_training_records';

    protected $fillable = [
        "name",
        "size",
        "file_name",
        "mime",
        "added",
        "added_by",
        "client_id",
    ];

}

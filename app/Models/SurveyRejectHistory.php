<?php

namespace App\Models;

use App\Models\ModelBase;

class SurveyRejectHistory extends ModelBase
{
    protected $table = 'tbl_survey_reject_history';

    protected $fillable = [
        "survey_id",
        "client_id",
        "user_id",
        "date",
        "note",
        "rejection_type_ids",
        "deleted_by",
        "created_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];
}

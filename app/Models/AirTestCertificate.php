<?php

namespace App\Models;

use App\Models\ModelBase;

class AirTestCertificate extends ModelBase
{
    protected $table = 'tbl_air_test_certificates';

    protected $fillable = [
        "survey_id",
        "reference",
        "air_test_reference",
        "description",
        "updated_date",
        "name",
        "mime",

    ];

    public function survey() {
        return $this->belongsTo('App\Models\Survey','survey_id','id');
    }
}

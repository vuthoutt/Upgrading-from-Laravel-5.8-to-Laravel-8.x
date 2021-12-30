<?php

namespace App\Models;

use App\Models\ModelBase;

class SampleCertificate extends ModelBase
{
    protected $table = 'tbl_sample_certificates';

    protected $fillable = [
        "survey_id",
        "reference",
        "sample_reference",
        "description",
        "updated_date",
        "name",
        "mime",
    ];

    public function survey() {
        return $this->belongsTo('App\Models\Survey','survey_id','id');
    }
    public function shineDocumentStorage() {
        return $this->hasOne('App\Models\ShineDocumentStorage','object_id', 'id')->where('type',SAMPLE_CERTIFICATE_FILE);
    }
}

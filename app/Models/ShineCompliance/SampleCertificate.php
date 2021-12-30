<?php

namespace App\Models\ShineCompliance;

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
        return $this->belongsTo('App\Models\ShineCompliance\Survey','survey_id','id');
    }
    public function shineDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ShineDocumentStorage','object_id', 'id')->where('type',SAMPLE_CERTIFICATE_FILE);
    }
}

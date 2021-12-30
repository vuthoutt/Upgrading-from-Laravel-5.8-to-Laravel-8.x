<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class SitePlanDocument extends ModelBase
{
    protected $table = 'tbl_siteplan_documents';

    protected $fillable = [
        "id",
        "reference",
        "property_id",
        "name",
        "plan_reference",
        "survey_id",
        "document_present",
        "type",
        "re_type",
        "contractor",
        "note",
        "category",
        "added",
        "rejected",
        "deleted",
        "authorised",
        "added_by",
        "deleted_by",
        "authorised_by",
        "rejected_by",
        "deadline",
        "created_at",
        "updated_at",
        "deleted_at",
    ];


    public function survey() {
        return $this->belongsTo('App\Models\ShineCompliance\Survey','survey_id','id');
    }

    public function shineDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ShineDocumentStorage','object_id', 'id')->where('type',PLAN_FILE);
    }
}

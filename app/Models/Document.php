<?php

namespace App\Models;

use App\Models\ModelBase;

class Document extends ModelBase
{
    protected $table = 'tbl_documents';

    protected $fillable = [
        "client_id",
        "name",
        "reference",
        "project_id",
        "document_present",
        "status",
        "type",
        "re_type",
        "contractor",
        "size",
        "file_name",
        "mime",
        "path",
        "note",
        "category",
        "added",
        "rejected",
        "deleted",
        "authorised",
        "approved",
        "added_by",
        "deleted_by",
        "authorised_by",
        "approved_by",
        "rejected_by",
        "deadline",
        "contractors",
        "value",
        "auto_approve"
    ];
    public function client() {
        return $this->belongsTo('App\Models\Client','client_id','id');
    }

    public function refurbDocType() {
        return $this->belongsTo('App\Models\RefurbDocType','type', 'id');
    }

    public function project() {
        return $this->belongsTo('App\Models\Project','project_id', 'id');
    }

    public function getcontractorArrayAttribute() {
        $contractors = $this->attributes['contractors'];
        if (!is_null($contractors)) {
            $contractors = explode(",",$contractors);
        } else {
            $contractors = [];
        }
        return $contractors;
    }

    public function getDeadLineDateAttribute($value) {
        $value =  $this->attributes['deadline'];
        if (is_null($value) || $value == 0) {
            return 'N/A';
        }
        if (date("d/m/Y", $value) == '01/01/1970') {
            return 'N/A';
        } else {
            return date("d/m/Y", $value);
        }
    }
}

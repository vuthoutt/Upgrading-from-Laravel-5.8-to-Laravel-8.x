<?php


namespace App\Models\ShineCompliance;


class AuditSupportingDocument extends ModelBase
{
    protected $table = 'tbl_audit_supporting_documents';

    protected $fillable = [
        "reference",
        "audit_id",
        "name",
        "added"
    ];

    public function shineDocumentStorage() {
        return $this->hasOne('App\Models\ShineDocumentStorage','object_id', 'id')->where('type',AUDIT_SUPPORTING_DOC);
    }
}

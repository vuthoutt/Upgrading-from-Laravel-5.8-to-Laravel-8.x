<?php


namespace App\Models\ShineCompliance;


class ShineAuditDocumentStorage extends ModelBase
{
    protected $table = 'tbl_shine_audit_document_storage';

    protected $fillable = [
        "manifest_id",
        "record_id",
        "type",
        "audit_id",
        "path",
        "file_name",
        "mime",
        "size"
    ];
}

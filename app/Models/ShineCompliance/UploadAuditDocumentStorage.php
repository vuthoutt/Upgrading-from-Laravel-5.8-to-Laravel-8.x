<?php


namespace App\Models\ShineCompliance;


class UploadAuditDocumentStorage extends ModelBase
{
    protected $table = 'tbl_upload_audit_document_storage';

    protected $fillable = [
        "audit_id",
        "type",
        "manifest_id",
        "correct_id",
        "data"
    ];

}

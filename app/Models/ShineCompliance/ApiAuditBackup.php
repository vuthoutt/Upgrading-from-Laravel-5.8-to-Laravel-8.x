<?php


namespace App\Models\ShineCompliance;


class ApiAuditBackup extends ModelBase
{
    protected $table = 'tbl_upload_audit_backup';

    protected $fillable = [
        "user_id",
        "audit_id",
        "path",
        "file_name",
        "size",
    ];
}

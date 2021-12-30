<?php


namespace App\Models\ShineCompliance;


class UploadAuditManifest extends ModelBase
{
    protected $table = 'tbl_upload_audit_manifest';

    protected $fillable = [
        "audit_id",
        "status",
        "user_id",
        "total_record",
        "total_photography",
        "total_image",
        "created_at",
        "updated_at",
        "deleted_at",
    ];


    public function uploadDocumentStorage() {
        return $this->hasMany('App\Models\UploadDocumentStorage','manifest_id');
    }

}

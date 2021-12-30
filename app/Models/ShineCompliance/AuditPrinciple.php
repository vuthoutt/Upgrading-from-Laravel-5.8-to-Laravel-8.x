<?php


namespace App\Models\ShineCompliance;


class AuditPrinciple extends ModelBase
{
    protected $table = 'tbl_audit_principle';

    protected $fillable = [
        "audit_id",
        "organisation",
        "site_manager",
        "telephone",
        "mobile",
        "email",
        "created_at",
        "updated_at"

    ];

}

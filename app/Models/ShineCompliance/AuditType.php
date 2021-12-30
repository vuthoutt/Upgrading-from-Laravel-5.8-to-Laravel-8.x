<?php


namespace App\Models\ShineCompliance;


class AuditType extends ModelBase
{
    protected $table = 'tbl_audit_type';

    protected $fillable = [
        "description",
        "search",
        "type_surveying",
        "type_removal",
        "type_demolition",
        "type_analytical",
        "type_instructingparty",
        "type",
        "color",
    ];

}

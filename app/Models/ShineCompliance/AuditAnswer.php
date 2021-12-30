<?php


namespace App\Models\ShineCompliance;


class AuditAnswer extends ModelBase
{
    protected $table = 'tbl_audit_answer';

    protected $fillable = [
        "type",
        "description",
        "score"
    ];
}

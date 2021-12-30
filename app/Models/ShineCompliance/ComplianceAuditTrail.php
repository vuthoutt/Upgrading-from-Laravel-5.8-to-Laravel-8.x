<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ComplianceAuditTrail extends ModelBase
{
    protected $table = 'compliance_audit_trail';

    public static $action_update = "'add', 'edit'";
    public static $audit_object_type = ['item', 'location','property','area','survey', 'user'];
    public static $audit_survey_update_type = ['item', 'location','area'];

    protected $fillable = [
        "property_id",
        "type",
        "shine_reference",
        "object_type",
        "object_id",
        "object_parent_id",
        "object_reference",
        "archive_id",
        "action_type",
        "user_id",
        "user_client_id",
        "user_name",
        "department",
        "date",
        "ip",
        "comments",
        "backup",
    ];

    public function getDateHourAttribute(){
        return date("H:i", $this->attributes['date']);
    }

    public function getDateRawAttribute(){
        return $this->attributes['date'];
    }

    public function auditUser() {
        return $this->belongsTo('App\User', 'user_id');
    }
}

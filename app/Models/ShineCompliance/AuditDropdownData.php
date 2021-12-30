<?php


namespace App\Models\ShineCompliance;


class AuditDropdownData extends ModelBase
{
    protected $table = 'tbl_audit_dropdown';

    protected $fillable = [
        "type",
        "audit_type",
        "description",
        "parent_id",
        "score",
        "other"
    ];

    public function childrens()
    {
        return $this->hasMany('App\Models\AuditDropdownData', 'parent_id', 'id');
    }

    public function questions() {
        return $this->hasMany('App\Models\AuditQuestion', 'dropdown_audit_id', 'id');
    }

    public function childQuestions()
    {
        return $this->hasManyThrough(
            'App\Models\AuditQuestion', 'App\Models\AuditDropdownData',
            'parent_id', 'dropdown_audit_id', 'id'
        );
    }

    public function auditDocumentation() {
        return $this->belongsToMany('App\Models\AuditDocumentation', 'tbl_audit_documentation_answer_value','question_id','audit_documentation_id');
    }
}

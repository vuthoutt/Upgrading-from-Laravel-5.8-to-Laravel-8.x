<?php


namespace App\Models\ShineCompliance;


class AuditDocumentation extends ModelBase
{
    protected $table = 'tbl_audit_documentation';

    protected $fillable = [
//        'id',
        'audit_id',
        'personnel_type',//remove
        'is_nonuser',
        'personnel_id',
        'result',
        'created_at',
        'updated_at'
    ];

    public function auditNonUser() {
        return $this->hasOne('App\Models\AuditNonUser', 'id','personnel_id');
    }
    public function user() {
        return $this->hasOne('App\User', 'id','personnel_id');
    }

    public function auditDocumentationAnswer() {
        return $this->belongsToMany('App\Models\AuditQuestion', 'tbl_audit_documentation_answer_value','audit_documentation_id','question_id')->withPivot('answer_id');
    }
}

<?php


namespace App\Models\ShineCompliance;


class AuditAnswerValue extends ModelBase
{
    protected $table = 'tbl_audit_answer_value';

    protected $fillable = [
        "audit_id",
        "question_id",
        "answer_id",
        "comment"
    ];

    public function answer() {
        return $this->hasOne('App\Models\AuditAnswer','id', 'answer_id');
    }
}

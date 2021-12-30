<?php


namespace App\Models\ShineCompliance;


class AuditDocumentationAnswerValue extends ModelBase
{
    protected $table = 'tbl_audit_documentation_answer_value';

    protected $fillable = [
//        'id',
        'audit_documentation_id',
        'question_id',
        'answer_id',
    ];

    public function answer() {
        return $this->belongsTo('App\Models\answers', 'answer_id', 'id');
    }
}

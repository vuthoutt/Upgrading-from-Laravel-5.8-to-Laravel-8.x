<?php


namespace App\Models\ShineCompliance;


class AuditQuestion extends ModelBase
{
    protected $table = 'tbl_audit_question';

    protected $fillable = [
        "description",
        "dropdown_audit_id",
        "answer_type",
        "unique_id",
        "other",
        "key_question"
    ];

    public function answers() {
        return $this->hasMany('App\Models\AuditAnswer', 'type', 'answer_type');
    }
}

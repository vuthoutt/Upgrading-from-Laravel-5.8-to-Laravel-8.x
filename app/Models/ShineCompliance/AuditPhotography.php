<?php


namespace App\Models\ShineCompliance;


class AuditPhotography extends ModelBase
{
    protected $table = 'tbl_audit_photography';

    protected $fillable = [
//        'id',
        'audit_id',
        'photography_name',
        'photography_type'
    ];

//    public function audit() {
//        return $this->hasMany('App\Models\AuditAnswer', 'type', 'answer_type');
//    }
}

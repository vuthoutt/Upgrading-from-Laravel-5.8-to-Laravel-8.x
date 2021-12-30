<?php


namespace App\Models\ShineCompliance;


class AuditNonUser extends ModelBase
{
    protected $table = 'tbl_audit_non_user';

    protected $fillable = [
        "first_name",
        "last_name",
        "telephone",
        "mobile",
        "email"
    ];

    public function getFullNameAttribute() {
        return $this->attributes['first_name'] . ' ' .$this->attributes['last_name'];
    }
}

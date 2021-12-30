<?php


namespace App\Models\ShineCompliance;


class PublishedAudit extends ModelBase
{
    protected $table = 'tbl_published_audit';

    protected $fillable = [
        'audit_id',
        'name',
        'revision',
        'type',
        'size',
        'filename',
        'mime',
        'path',
        'created_by'
    ];

    public function audit() {
        return $this->belongsTo('App\Models\Audit', 'audit_id', 'id');
    }
}

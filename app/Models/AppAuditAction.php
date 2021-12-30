<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppAuditAction extends Model
{
    protected $table = 'tbl_app_audit_actions';

    protected $fillable = [
        'id',
        'action_type',
    ];
}

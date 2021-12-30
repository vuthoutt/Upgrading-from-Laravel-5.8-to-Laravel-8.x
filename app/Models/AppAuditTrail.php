<?php


namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

class AppAuditTrail extends Model
{
    protected $table = 'tbl_app_audit_trail';

    protected $fillable = [
        'id',
        'reference',
        'user_id',
        'type',
        'action_type',
        'object_id',
        'comment',
        'timestamp',
        'app_version',
        'ip',
        'device_id',
        'device_soft_version',
    ];

    public function actionType()
    {
        return $this->belongsTo(AppAuditAction::class, 'action_type');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'object_id');
    }

    public function audit()
    {
        return $this->belongsTo(Audit::class, 'object_id');
    }

    public function getAppAuditRefAttribute()
    {
        return 'AAR' . $this->attributes['id'];
    }

    public function getDateAttribute()
    {
        $value = $this->attributes['timestamp'];
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getTimeAttribute()
    {
        $value = $this->attributes['timestamp'];
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("H:i", $value);
    }

}

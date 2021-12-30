<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;
    protected $table ='tbl_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'shine_reference',
        'username',
        'password',
        'remember_token',
        'is_site_operative',
        'client_id',
        'department_id',
        'first_name',
        'last_name',
        'email',
        'surveyor',
        'is_admin',
        'grant',
        'joblead',
        'is_lead_workrequest',
        'is_locked',
        'is_call_centre_staff',
        'housing_officer',
        'password_hash',
        'app_hash',
        'token',
        'role',
        'token_expired',
        'newEmail',
        'f2aToken',
        'last_asbestos_training',
        'last_shine_asbestos_training',
        'last_site_operative_training',
        'last_project_training',
        'notes',
        'tokenID',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAuthPassword() {
        return $this->password;
    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    protected $primaryKey = 'id';

    public function clients() {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }

    public function contact() {
        return $this->hasOne('App\Models\Contact','user_id','id');
    }

    public function department() {
        return $this->belongsTo('App\Models\Department','department_id');
    }

    public function departmentContractor() {
        return $this->belongsTo('App\Models\DepartmentContractor','department_id');
    }

    public function getFullNameAttribute() {
        return $this->attributes['first_name'] . ' ' .$this->attributes['last_name'];
    }

    public function getDaysRemaninngAttribute() {
        // last_asbestos_training plus 1 year
        $date = Carbon::parse($this->attributes['last_asbestos_training'])->addYear(1);
        $now = Carbon::now();
        // last_asbestos_training plus 1 year - date now
        $daysRemaning  = $now->diffInDays($date, false);
        if ($daysRemaning > 0) {
            return $daysRemaning;
        } else {
            return 0;
        }

    }

    public function resetPasswords() {
        return $this->hasOne('App\Models\PasswordReset','email','email');
    }

//    public function getlastAsbestosTrainingAttribute($value) {
//        return date("d/m/Y", $value);
//    }
//
//    public function getlastSiteOperativeTrainingAttribute($value) {
//        return date("d/m/Y", $value);
//    }
//
//    public function getlastProjectTrainingAttribute($value) {
//        return date("d/m/Y", $value);
//    }

    // public function getlastAsbestosTrainingAttribute($value) {

    //     if (is_null($value) || $value == 0) {
    //         return null;
    //     }
    //     return date("d/m/Y", $value);
    // }

    // public function getlastShineAsbestosTrainingAttribute($value) {
    //     if (is_null($value) || $value == 0) {
    //         return null;
    //     }
    //     return date("d/m/Y", $value);
    // }

    public function apiToken() {
        return $this->hasOne('App\Models\ApiToken','user_id');
    }

    public function userRole() {
        return $this->belongsTo('App\Models\ShineCompliance\JobRole', 'role', 'id');
    }

    public function userRoleUpdate() {
        return $this->belongsTo('App\Models\RoleUpdate', 'role', 'role_id');
    }


}

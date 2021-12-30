<?php

namespace App\Models\ShineCompliance;
use Carbon\Carbon;
use App\Models\ModelBase;

class ApiToken extends ModelBase
{
    protected $table = 'api_token';

    protected $fillable = [
        'user_id',
        'token',
        'expired_at',
        'created_at'
    ];
    protected $dates = ['expired_at'];

    public function user() {
        return $this->hasOne('App\User','user_id');
    }

    public function getIsTokenExpiredAttribute() {
        $expired_at =  $this->attributes['expired_at'];
        $now = Carbon::now();
        $daysRemaning  = $now->diffInMilliseconds($expired_at, false);
        return $daysRemaning < 0;
    }

}

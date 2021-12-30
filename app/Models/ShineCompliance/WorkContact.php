<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class WorkContact extends ModelBase
{
    protected $table = 'tbl_work_contact';

    protected $fillable = [
        'work_id',
        'first_name',
        'last_name',
        'telephone',
        'mobile',
        'email',
        'created_at',
        'updated_at',

    ];

    // public function getTeamAttribute() {
    //     $contact = [];
    //     for ($i=1; $i <= 10; $i++) {
    //         $name = 'team'. $i;
    //         if (!is_null($this->attributes[$name]) and $this->attributes[$name] != 0 ) {
    //             $contact[] = $this->attributes[$name];
    //         }
    //     }
    //     return $contact;
    // }

    public function getFullNameAttribute() {
        return $this->attributes['first_name'] . ' ' .$this->attributes['last_name'];
    }

}

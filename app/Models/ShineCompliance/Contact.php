<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Contact extends ModelBase
{
    protected $table = 'tbl_contact';

    protected $fillable = [
        'id',
        'user_id',
        'job_title',
        'address1',
        'address2',
        'town',
        'postcode',
        'country',
        'telephone',
        'mobile',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

}

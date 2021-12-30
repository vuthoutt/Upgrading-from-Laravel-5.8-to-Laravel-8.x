<?php

namespace App\Models;

use App\Models\ModelBase;

class PropertyEmailSent extends ModelBase
{
    protected $table = 'tbl_property_email_sent';

    protected $fillable = [
        'property_id',
        'email_type',
        'last_sent',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];


}

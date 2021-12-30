<?php

namespace App\Models;

use App\Models\ModelBase;

class RejectionType extends ModelBase
{
    protected $table = 'tbl_rejection_type';
    protected $fillable = [
        'id',
        'description',
        'type'
    ];

    public function rejectionParent() {
        return $this->belongsTo(MainRejectionType::class,'id');
    }
}

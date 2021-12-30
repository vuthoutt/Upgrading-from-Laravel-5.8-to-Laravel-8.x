<?php

namespace App\Models;

use App\Models\ModelBase;

class AssetClass extends ModelBase
{
    protected $table = 'tbl_asset_class';

    protected $fillable = [
        'id',
        'description',
        'parent_id',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
        'is_deleted',
    ];

}

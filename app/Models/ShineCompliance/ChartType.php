<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChartType extends ModelBase
{
    use SoftDeletes;
    protected $table = 'tbl_chart_type';
    protected $softDelete = true;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'description',
        'value',
        'code',
    ];
}

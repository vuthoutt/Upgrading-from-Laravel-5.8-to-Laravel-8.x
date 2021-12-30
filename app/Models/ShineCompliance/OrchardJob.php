<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;
use Illuminate\Support\Facades\DB;

class OrchardJob extends ModelBase
{
    protected $table = 'tbl_orchard_job';

    protected $fillable = [
        'id',
        'work_id',
        'step',
        'job_number',
        'description1',
        'expense_code',
        'trade_code',
        'priority_code',
        'sor_type_code',
        'volume_cde',
        'sor_num',
        'department_code',
        'contract_number',
        'timestamp',
        'date',
        'status',
    ];
    public function workRequest() {
        return $this->belongsTo('App\Models\WorkRequest','work_id','id');
    }

}

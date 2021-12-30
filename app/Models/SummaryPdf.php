<?php

namespace App\Models;

use App\Models\ModelBase;

class SummaryPdf extends ModelBase
{
    protected $table = 'tbl_summary_pdf';

    protected $fillable = [
        'id',
        'reference',
        'type',
        'object_id',
        'file_name',
        'path',
        'comment'
    ];

}

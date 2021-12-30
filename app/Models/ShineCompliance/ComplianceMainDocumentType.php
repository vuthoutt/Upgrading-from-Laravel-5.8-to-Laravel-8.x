<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ComplianceMainDocumentType extends ModelBase
{
    protected $table = 'compliance_main_document_types';

    protected $fillable = [
        'description',
    ];

    public function documentTypes(){
        return $this->hasMany(ComplianceDocumentType::class,'type','id');
    }

}

<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ComplianceDocumentCategory extends ModelBase
{
    protected $table = 'compliance_document_category';

    protected $fillable = [
        'property_id',
        'system_id',
        'equipment_id',
        'program_id',
        'name',
        'type'
    ];

    public function documents(){
        return $this->hasMany(ComplianceDocument::class, 'category_id', 'id');
    }
}

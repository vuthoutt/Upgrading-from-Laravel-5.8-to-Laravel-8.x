<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ComplianceDocument extends ModelBase
{
    protected $table = 'compliance_documents';

    protected $fillable = [
        'reference',
        'property_id',
        'equipment_id',
        'programme_id',
        'parent_type',
        'type',
        'type_other',
        'status',
        'system_id',
        'date',
        'name',
        'compliance_type',//asbestos/fire/water/gas/ME
        'is_external_ms',
        'is_reinspected',
        'is_identified_acm',
        'is_inaccess_room',
        'enforcement_deadline',
        'document_status',
        'category_id',
        'created_by',
    ];

    public function equipment(){
        return $this->belongsTo(Equipment::class,'equipment_id','id');
    }

    public function programme(){
        return $this->belongsTo(ComplianceProgramme::class,'programme_id','id');
    }

    public function system(){
        return $this->belongsTo(ComplianceSystem::class,'system_id','id');
    }

    public function property(){
        return $this->belongsTo(Property::class,'property_id','id');
    }

    public function complianceDocumentStorage(){
        return $this->hasOne(ComplianceDocumentStorage::class,'object_id','id')->where('type',COMPLIANCE_DOCUMENT_PHOTO);
    }

    public function complianceDocumentStatus() {
        return $this->belongsTo(ComplianceDocumentStatus::class, 'document_status', 'id');
    }
}

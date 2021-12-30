<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HistoricDoc extends ModelBase
{
    protected $table = 'tbl_historicdocs';

    protected $fillable = [
        'id',
        'reference',
        'name',
        'category',
        'doc_type',
        'size',
        'file_name',
        'added',
        'mime',
        'property_id',
        'is_external_ms',
        'created_by',
        'historical_type',
        'is_inaccess_room',
        'is_identified_acm',
        'is_presumed_acm',
        'is_sp_acm',
        'is_historical_action',
        'is_hazards'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:00',
    ];

    public function historicalDocType() {
        return $this->belongsTo('App\Models\ShineCompliance\HistoricalDocumentType', 'doc_type', 'id');
    }

    public function docCat() {
        return $this->belongsTo('App\Models\ShineCompliance\HistoricDocCategory','category','id');
    }

    public function property() {
        return $this->belongsTo('App\Models\ShineCompliance\Property','property_id','id');
    }

    public function complianceDocumentStorage(){
        return $this->hasOne(ComplianceDocumentStorage::class,'object_id','id')->where('type',COMPLIANCE_HISTORICAL_DOCUMENT);
    }
}

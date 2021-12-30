<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;
use Carbon\Carbon;

class ComplianceProgramme extends ModelBase
{
    protected $table = 'compliance_programmes';

    protected $fillable = [
    'reference',
    'name',
    'system_id',
    'property_id',
    'date_inspected',
    'inspection_period',
    'next_inspection',
//    'linked_document_id',
    'decommissioned',
    'created_by',
    'updated_by',

    ];
    protected $casts = [
        'reference' => 'string',
        'name' => 'string',
        'system_id' => 'integer',
        'property_id' => 'string',
        'date_inspected' => 'integer',
        'inspection_period' => 'integer',
        'next_inspection' => 'integer',
        'decommissioned' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function system() {
        return $this->belongsTo('App\Models\ShineCompliance\ComplianceSystem', 'system_id', 'id');
    }

    public function property() {
        return $this->belongsTo('App\Models\ShineCompliance\Property', 'property_id', 'id');
    }

    public function documents(){
        return $this->hasMany(ComplianceDocument::class,'programme_id','id');
    }
    //the document will reset the clock inspection
    public function documentInspection(){
        return $this->hasOne(ComplianceDocument::class, 'programme_id', 'id')->where('is_reinspected', DOCUMENT_REINSPECTED)->orderBy('date', 'desc');
    }

    public function getDateInspectedDisplayAttribute() {
        $value =  $this->attributes['date_inspected'];
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getNextInspectionDisplayAttribute() {
        $document = $this->documentInspection ?? NULL;
        $inspection_period = $this->attributes['inspection_period'];
        if($document){
            return Carbon::parse($document->getOriginal('date'))->addDays($inspection_period)->format("d/m/Y");
        }
        return '';
    }

    public function getDaysRemainingAttribute() {
        $document = $this->documentInspection ?? NULL;
        $inspection_period = $this->attributes['inspection_period'];
        if($document){
            $next_inspection_date = Carbon::parse($document->getOriginal('date'))->addDays($inspection_period)->format("d/m/Y");
            return Carbon::now()->diffInDays(Carbon::createFromFormat("d/m/Y",$next_inspection_date), false);
        }
        return 'Missing';
    }
}

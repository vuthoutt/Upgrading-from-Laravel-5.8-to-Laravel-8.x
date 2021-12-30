<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class IncidentReport extends ModelBase
{
    protected $table = 'incident_reports';

    protected $fillable = [
        'id',
        'reference',
        'type',
        'report_recorder',
        'asbestos_lead',
        'second_asbestos_lead',
        'call_centre_team_member_name',
        'status',
        'date_of_report',
        'time_of_report',
        'reported_by',
        'reported_by_other',
        'property_id',
        'equipment_id',
        'system_id',
        'details',
        'confidential',
        'is_involved',
        'is_lock',
        'published_date',
        'date_of_incident',
        'time_of_incident',
        'decommissioned',
        'reason_decommissioned',
        'is_lock',
        'category_of_works',
        'is_risk_assessment',
        'is_address_in_wcc',
        'address_building_name',
        'address_street_number',
        'address_street_name',
        'address_town',
        'address_county',
        'address_postcode',
    ];

    public function property() {
        return $this->belongsTo(Property::class,'property_id','id');
    }

    public function equipment() {
        return $this->belongsTo(Equipment::class,'equipment_id','id');
    }

    public function system() {
        return $this->belongsTo(ComplianceSystem::class,'system_id','id');
    }

    public function documents() {
        return $this->hasMany(IncidentReportDocument::class, 'incident_report_id', 'id');
    }

    public function involvedPersons() {
        return $this->hasMany(IncidentReportInvolvedPerson::class, 'incident_report_id', 'id');
    }

    public function incidentType() {
        return $this->hasOne(IncidentReportDropdownData::class, 'id', 'type');
    }

    public function categoryOfWorkType() {
        return $this->hasOne(IncidentReportDropdownData::class, 'id', 'category_of_works');
    }

    public function reportRecorder()
    {
        return $this->belongsTo(User::class, 'report_recorder');
    }

    public function reportedUser() {
        return $this->belongsTo(User::class, 'reported_by', 'id');
    }

    public function hsLead() {
        return $this->belongsTo(User::class, 'asbestos_lead', 'id');
    }

    public function secondHsLead() {
        return $this->belongsTo(User::class, 'second_asbestos_lead', 'id');
    }

    public function publishedIncidentReport()
    {
        return $this->hasMany(IncidentReportPublished::class, 'incident_id')->orderBy('revision', 'desc');
    }
    // Getter / Setter

    public function getTypeDisplayAttribute()
    {
        switch ($this->attributes['type']) {
            case EQUIPMENT_NONCONFORMITY:
                return 'Equipment Nonconformity';
            case IDENTIFIED_HAZARD:
                return 'Identified Hazard';
            case INCIDENT:
                return 'Incident';
            case SOCIAL_CARE:
                return 'Social Care';
            default:
                return '';
        }
    }

    public function getStatusDisplayAttribute()
    {
        switch ($this->attributes['status']) {
            case INCIDENT_REPORT_CREATED_STATUS:
                return 'New Incident Report';
            case INCIDENT_REPORT_READY_QA:
                return 'Ready for QA';
            case INCIDENT_REPORT_AWAITING_APPROVAL:
                return 'Published';
            case INCIDENT_REPORT_COMPLETE:
                return 'Completed';
            case INCIDENT_REPORT_REJECT:
                return 'Rejected';
            default:
                return '';
        }
    }

    public function getReportDateAttribute() {
        if (is_null($this->attributes['date_of_report']) || $this->attributes['date_of_report'] == 0) {
            return null;
        }
        return date("d/m/Y", $this->attributes['date_of_report']);
    }

    public function getReportTimeAttribute() {
        if (is_null($this->attributes['time_of_report']) || $this->attributes['time_of_report'] == 0) {
            return null;
        }
        return date("H:i:s", $this->attributes['time_of_report']);
    }

    public function getAddressOfIncidentAttribute()
    {
        return implode(", ", array_filter([
            $this->attributes['address_building_name'] ?? '',
            $this->attributes['address_street_number'] ?? '',
            $this->attributes['address_street_name'] ?? '',
            $this->attributes['address_town'] ?? '',
            $this->attributes['address_county'] ?? '',
            $this->attributes['address_postcode'] ?? '',
        ]));
    }

    public function getPublishedDatePdfAttribute() {
        $value = $this->attributes['published_date'];
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }
}

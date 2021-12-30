<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;
use Carbon\Carbon;

class Assessment extends ModelBase
{
    protected $table = 'cp_assessments';

    protected $fillable = [
        'client_id',
        'property_id',
        'classification',
        'type',
        'reference',
        'status',
        'is_locked',
        'decommissioned',
        'lead_by',
        'second_lead_by',
        'assessor_id',
        'quality_checker',
        'project_id',
        'work_request_id',
        'sent_out_date',
        'sent_back_date',
        'due_date',
        'published_date',
        'completed_date',
        'started_date',
        'assess_start_date',
        'assess_start_time',
        'assess_finish_date',
        'aborted_reason',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function clients() {
        return $this->belongsTo(Client::class,'client_id');
    }

    public function areas()
    {
        return $this->hasMany(Area::class, 'assess_id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'assess_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function abortedReason()
    {
        return $this->belongsTo(AssessmentAbortedReason::class, 'aborted_reason');
    }

    public function lead()
    {
        return $this->belongsTo(User::class, 'lead_by');
    }

    public function secondLead()
    {
        return $this->belongsTo(User::class, 'second_lead_by');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function samples()
    {
        return $this->hasMany(AssessmentSample::class, 'assess_id');
    }

    public function qualityChecker()
    {
        return $this->belongsTo(User::class, 'quality_checker');
    }

    public function assessmentInfo()
    {
        return $this->hasOne(AssessmentInfo::class, 'assess_id', 'id');
    }

    public function assessmentResult()
    {
        return $this->hasOne(AssessmentResult::class, 'assess_id', 'id');
    }

    public function unDecommissionHazard()
    {
        return $this->hasMany(Hazard::class, 'assess_id', 'id')->where('decommissioned', HAZARD_UNDECOMMISSION)->where('is_deleted', 0);
    }
    //is_temp = 0
    public function unDecommissionHazardPdf()
    {
        return $this->hasMany(Hazard::class, 'assess_id', 'id')->where('decommissioned', HAZARD_UNDECOMMISSION)->where('is_deleted', 0)->where('is_temp', 0);
    }

    public function decommissionHazard()
    {
        return $this->hasMany(Hazard::class, 'assess_id', 'id')->where('decommissioned', HAZARD_DECOMMISSION)->where('is_deleted', 0);
    }

    public function assessmentNonconformities()
    {
        return $this->hasMany(Nonconformity::class, 'assess_id', 'id')->where('is_deleted', 0);
    }

    public function assessmentActiveNonconformities()
    {
        return $this->hasManyThrough(Nonconformity::class, Equipment::class, 'assess_id', 'equipment_id')
            ->where('cp_equipments.decommissioned', 0)
            ->where('is_deleted', 0);
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class, 'assess_id', 'id')->where('decommissioned', 0);
    }

    public function decommissionEquipments()
    {
        return $this->hasMany(Equipment::class, 'assess_id', 'id')->where('decommissioned', 1);
    }

    public function systems()
    {
        return $this->hasMany(ComplianceSystem::class, 'assess_id', 'id')->where('decommissioned', 0);
    }

    public function decommissionSystems()
    {
        return $this->hasMany(ComplianceSystem::class, 'assess_id', 'id')->where('decommissioned', 1);
    }

    public function fireExits()
    {
        return $this->hasMany(FireExit::class, 'assess_id', 'id')->where('decommissioned', 0);
    }

    public function decommissionFireExits()
    {
        return $this->hasMany(FireExit::class, 'assess_id', 'id')->where('decommissioned', 1);
    }

    public function assemblyPoints()
    {
        return $this->hasMany(AssemblyPoint::class, 'assess_id', 'id')->where('decommissioned', 0);
    }

    public function decommissionAssemblyPoints()
    {
        return $this->hasMany(AssemblyPoint::class, 'assess_id', 'id')->where('decommissioned', 1);
    }

    public function vehicleParking()
    {
        return $this->hasMany(VehicleParking::class, 'assess_id', 'id')->where('decommissioned', 0);
    }

    public function decommissionVehicleParking()
    {
        return $this->hasMany(VehicleParking::class, 'assess_id', 'id')->where('decommissioned', 1);
    }

    public function publishedAssessments()
    {
        return $this->hasMany(PublishedAssessment::class, 'assess_id')->orderBy('id', 'desc');
    }

    public function plans() {
        return $this->hasMany(AssessmentPlanDocument::class,'assess_id', 'id')->orderBy('plan_date','desc');
    }

    public function assessorNotes()
    {
        return $this->hasMany(AssessmentNoteDocument::class,'assess_id', 'id')->orderBy('plan_date','desc');
    }

    public function lastPublishedAssessments()
    {
        return $this->hasOne(PublishedAssessment::class, 'assess_id')->orderBy('id', 'desc');
    }

    // Getter/Setter
    public function getStatusTextAttribute()
    {
        $statusText = "";

        if ($this->attributes['decommissioned']) {
            $statusText = "Decommissioned";
        } else {
            switch ($this->attributes['status']) {
                case ASSESSMENT_STATUS_NEW:
                    $statusText = "New Assessment";
                    break;
                case ASSESSMENT_STATUS_LOCKED:
                    $statusText = "<span class='orange_text'>Locked</span>";
                    break;
                case ASSESSMENT_STATUS_READY_FOR_QA:
                    $statusText = "Ready for QA";
                    break;
                case ASSESSMENT_STATUS_PUBLISHED:
                    $statusText = "Published";
                    break;
                case ASSESSMENT_STATUS_COMPLETED:
                    $statusText = "Completed";
                    break;
                case ASSESSMENT_STATUS_REJECTED:
                    $statusText = "Rejected";
                    break;
                case ASSESSMENT_STATUS_SENT_BACK_FROM_DEVICE:
                    $statusText = "<span class='blue_text'>Sent back</span>";
                    break;
                case ASSESSMENT_STATUS_ABORTED:
                    $statusText = "<span class='red_text'>Aborted</span>";
                    break;
                default:
                    break;
            }
        }

        return $statusText;
    }

    public function getAssessStartDateAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getAssessFinishDateAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getSentBackDayAttribute() {
        if (is_null($this->attributes['sent_back_date']) || $this->attributes['sent_back_date'] == 0) {
            return null;
        }
        return date("d/m/Y", $this->attributes['sent_back_date']);
    }

    public function getAssessClassificationAttribute() {
        $text = "";
        switch ($this->attributes['classification']) {
            case ASSESSMENT_ASBESTOS_TYPE:
                $text = ASBESTOS;
                break;
            case ASSESSMENT_FIRE_TYPE:
                $text = FIRE;
                break;
            case ASSESSMENT_GAS_TYPE:
                $text = GAS;
                break;
            case ASSESSMENT_WATER_TYPE:
                $text = WATER;
                break;
            case ASSESSMENT_HS_TYPE:
                $text = HS;
                break;
        }
        return $text;
    }

    public function getAssessTypeAttribute() {
        $text = "";

        if ($this->attributes['classification'] == ASSESSMENT_FIRE_TYPE) {
            if ($this->attributes['type'] == ASSESS_TYPE_FIRE_EQUIPMENT) {
                $text = 'Fire Equipment Assessment';
            } elseif (in_array($this->attributes['type'], ASSESS_TYPE_FIRE_RISK_ALL_TYPE) ) {
                $text = 'Fire Risk Assessment';
            }
        } elseif ($this->attributes['classification'] == ASSESSMENT_WATER_TYPE) {
            if ($this->attributes['type'] == ASSESS_TYPE_WATER_EQUIPMENT) {
                $text = 'Water Equipment Assessment';
            } elseif ($this->attributes['type'] == ASSESS_TYPE_WATER_RISK) {
                $text = 'Water Risk Assessment';
            } elseif ($this->attributes['type'] == ASSESS_TYPE_WATER_TEMP) {
                $text = 'Water Temperature Assessment';
            }
        } elseif ($this->attributes['classification'] == ASSESSMENT_HS_TYPE) {
            if ($this->attributes['type'] == ASSESS_TYPE_HS) {
                $text = 'Health & Safety Assessment';
            }
        }

        return $text;
    }

    public function getOverAllTextAttribute() {
        $text = "";
        switch ($this->attributes['type']) {
            case ASSESSMENT_ASBESTOS_TYPE:
                $text = 'oas';
                break;
            case ASSESSMENT_FIRE_TYPE:
                $text = 'ofr';
                break;
            case ASSESSMENT_GAS_TYPE:
                $text = 'ogr';
                break;
            case ASSESSMENT_WATER_TYPE:
                $text = 'owr';
                break;
        }
        return $text;
    }

    public function getNextInspectionAssessmentAttribute(){
        $assess_start_date = $this->attributes['assess_start_date'];
        if($assess_start_date > 0){
            return Carbon::parse($assess_start_date)->addYears(2)->format('d/m/Y');
        } else {
            return '00/00/0000';
        }
    }

    public function getRiskWarningAttribute() {
        if ($this->attributes['due_date']) {
            $daysRemain = intval(($this->attributes['due_date'] - time()) / 86400);
        } else {
            $daysRemain = 0;
        }

        return $daysRemain;
    }

    public function getRiskColorAttribute() {

        $daysRemain = $this->getRiskWarningAttribute();
        $riskColor = "";

        if ($daysRemain <= 14) {
            $riskColor = "red";
        } elseif ($daysRemain >= 15 && $daysRemain <= 30) {
            $riskColor = "orange";
        } elseif ($daysRemain >= 31 && $daysRemain <= 60) {
            $riskColor = "yellow";
        } elseif ($daysRemain >= 61 && $daysRemain <= 120) {
            $riskColor = "blue";
        } else {
            $riskColor = "grey";
        }

        return $riskColor;
    }
}

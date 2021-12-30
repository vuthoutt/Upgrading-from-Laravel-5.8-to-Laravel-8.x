<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Project extends ModelBase
{
    protected $table = 'tbl_project';

    protected $fillable = [

        "client_id",
        "property_id",
        "survey_id",
        "work_id",
        "survey_type",
        "title",
        "project_type",
        "reference",
        "date",
        "due_date",
        "enquiry_date",
        "completed_date",
        "lead_key",
        "second_lead_key",
        "sponsor_lead_key",
        "sponsor_id",
        "job_no",
        "locked",
        "additional_info",
        "status",
        "progress_stage",
        "contractors",
        "contractor_not_required",
        "checked_contractors",
        "comments",
        "work_stream",
        "rr_condition",
        "linked_project_type",
        "linked_project_id",
        "risk_classification_id",
        "assessment_ids",
        "created_by",

    ];

    public function property(){
        return $this->belongsTo('App\Models\ShineCompliance\Property', 'property_id', 'id');
    }

    public function client(){
        return $this->belongsTo('App\Models\ShineCompliance\Client', 'client_id', 'id');
    }

    public function survey(){
        return $this->hasMany('App\Models\ShineCompliance\Survey', 'project_id', 'id');
    }

    public function sponsor(){
        return $this->belongsTo('App\Models\ShineCompliance\ProjectSponsor', 'sponsor_id', 'id');
    }

    public function surveyType(){
        return $this->belongsTo('App\Models\ShineCompliance\SurveyType', 'survey_type', 'id');
    }

    public function rrCondition(){
        return $this->belongsTo('App\Models\ShineCompliance\RRCondition', 'rr_condition', 'id');
    }

    public function document(){
        return $this->hasMany('App\Models\ShineCompliance\Document', 'rr_condition', 'id');
    }

    public function workRequest(){
        return $this->belongsTo('App\Models\ShineCompliance\WorkRequest', 'work_id', 'id');
    }

    public function projectClassification(){
        return $this->belongsTo(ProjectRiskClassification::class, 'risk_classification_id', 'id');
    }

    public function projectType(){
        return $this->belongsTo('App\Models\ShineCompliance\ProjectType', 'project_type', 'id');
    }

    public function commentHistory() {
        return $this->hasMany('App\Models\ShineCompliance\ProjectComment','record_id','id')->orderBy('tbl_project_comment.created_at','desc');;
    }

    public function workStream() {
        return $this->hasOne('App\Models\ShineCompliance\WorkStream','id','work_stream');
    }

    public function getProjectTypeTextAttribute() {
        $projectType = $this->attributes['project_type'];
        switch ($projectType) {
            case 1:
            return 'Survey Only';
            case 2:
            return 'Remediation/Removal';
            case 3:
            return 'Demolition';
            case 4:
            return 'Analytical';
            case 5:
                return 'Fire Risk Assessment';
            case 6:
                return 'Fire Remedial Project';
            case 7:
                return 'Independent Survey';
            case 8:
                return 'Legionella Risk Assessment';
            case 9:
                return 'Water Testing Assessment';
            case 10:
                return 'Water Remedial Assessment';
            case 11:
                return 'Temperature Assessment';
            case 12:
                return 'Fire Equipment Assessment';
        }

        return '';
    }

    public function getStatusTextAttribute() {
        $statusText = "";

        switch ($this->attributes['status']) {
            case PROJECT_CREATED_STATUS:
                $statusText = "Created";
                break;
            case PROJECT_TECHNICAL_IN_PROGRESS_STATUS:
                $statusText = "Tendering";
                break;
            case PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS:
                switch ($this->attributes['progress_stage']) {
                    case PROJECT_STAGE_PRE_CONSTRUCTION:
                        $statusText = "Pre-construction in Progress";
                        break;
                    case PROJECT_STAGE_DESIGN:
                        $statusText = "Design in Progress";
                        break;
                    case PROJECT_STAGE_COMMERCIAL:
                        $statusText = "Commercial in Progress";
                        break;
                    case PROJECT_STAGE_PLANNING:
                        $statusText = "Planning in Progress";
                        break;
                    case PROJECT_STAGE_PRE_START:
                        $statusText = "Pre-Start in Progress";
                        break;
                    case PROJECT_STAGE_SITE_RECORD:
                        $statusText = "Site Records in Progress";
                        break;
                    case PROJECT_STAGE_COMPLETION:
                        $statusText = "Completion in Progress";
                        break;
                }
                break;
            case PROJECT_READY_FOR_ARCHIVE_STATUS:
                $statusText = "Ready for Archive";
                break;
            case PROJECT_COMPLETE_STATUS:
                $statusText = "Completed";
                break;
            case PROJECT_REJECTED_STATUS:
                $statusText = "Rejected";
                break;
            default:
            break;
        }

        return $statusText;
    }

    public function getriskColorAttribute() {

        $daysRemain = $this->getprojectDaysRemainAttribute();
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

    public function getprojectDaysRemainAttribute() {

        if ($this->attributes['due_date']) {
            $daysRemain = intval(($this->attributes['due_date'] - time()) / 86400);
        } else {
            $daysRemain = 0;
        }

        return $daysRemain;
    }

    public function getenquiryDateAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getselectedContractorsAttribute($value) {

        if (!is_null($this->attributes['contractors'])) {
            $selected_contractors = explode(",", $this->attributes['contractors']);
        } else {
            $selected_contractors = [];
        }

        return $selected_contractors;
    }

    public function getTitleWorkFlowAttribute($value) {
        $work_flow = $this->workRequest->work_flow ?? 0;
        if ($work_flow == 1) {
            return ($this->attributes['title'] . ' (External Laboratory)');
        }

        return $this->attributes['title'];
    }
}

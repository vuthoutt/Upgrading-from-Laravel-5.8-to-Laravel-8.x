<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Survey extends ModelBase
{
    protected $table = 'tbl_survey';

    protected $fillable = [
        'id',
        'client_id',
        'property_id',
        'project_id',
        'survey_type',
        'reference',
        'decommissioned',
        'lead_by',
        'second_lead_by',
        'surveyor_id',
        'second_surveyor_id',
        'quality_id',
        'analyst_id',
        'consultant_id',
        'is_locked',
        'reason',
        'status',
        'job_number' ,
        'instructing_party_id' ,
        'instructing_contact_id' ,
        'cost',
        'reason_decommissioned_other' ,
        'created_by',
        'cad_tech_id',
        'work_stream',
        'organisation_reference'
    ];

    public function property() {
        return $this->belongsTo('App\Models\ShineCompliance\Property','property_id');
    }

    public function clients() {
        return $this->belongsTo('App\Models\ShineCompliance\Client','client_id');
    }

    public function project() {
        return $this->belongsTo('App\Models\ShineCompliance\Project','project_id');
    }

    public function publishedSurvey() {
        return $this->hasMany('App\Models\ShineCompliance\PublishedSurvey','survey_id')->orderBy('revision','desc');
    }

    public function sampleCertificate() {
        return $this->hasMany('App\Models\ShineCompliance\SampleCertificate','survey_id');
    }

    public function sitePlanDocuments() {
        return $this->hasMany('App\Models\ShineCompliance\SitePlanDocument','survey_id')->where('category', 0)->orderBy('added','desc');
    }

    public function surveyorNote() {
        return $this->hasMany('App\Models\ShineCompliance\SitePlanDocument','category', 'id')->where('survey_id', 0)->orderBy('added','desc');
    }

    public function airTestCertificate() {
        return $this->hasMany('App\Models\ShineCompliance\AirTestCertificate','survey_id');
    }

    // public function surveyQuestionData() {
    //     return $this->belongsToMany('App\Models\ShineCompliance\DropdownDataSurvey', 'App\Models\ShineCompliance\SurveyAnswer', 'survey_id','question_id');
    // }

    // public function surveyAnswerData() {
    //     return $this->belongsToMany('App\Models\ShineCompliance\DropdownDataSurvey', 'App\Models\ShineCompliance\SurveyAnswer', 'survey_id','answer_id');
    // }

    public function surveyAnswer() {
        return $this->hasMany('App\Models\ShineCompliance\SurveyAnswer','survey_id');
    }

    public function surveyDate() {
        return $this->hasOne('App\Models\ShineCompliance\SurveyDate','survey_id');
    }

    public function surveyInfo() {
        return $this->hasOne('App\Models\ShineCompliance\SurveyInfo','survey_id');
    }

    public function surveySetting() {
        return $this->hasOne('App\Models\ShineCompliance\SurveySetting','survey_id');
    }

    public function surveyArea() {
        return $this->hasMany('App\Models\ShineCompliance\Area','survey_id', 'id')->where('tbl_area.decommissioned', 0);
    }

    public function surveyClient() {
        return $this->hasOne('App\Models\ShineCompliance\Client','client_id','id');
    }

    public function surveyPublishSurvey() {
        return $this->hasMany('App\Models\ShineCompliance\PublishedSurvey','survey_id');
    }

    public function client() {
        return $this->belongsTo('App\Models\ShineCompliance\Client', 'client_id', 'id');
    }

    public function item() {
        return $this->hasMany('App\Models\ShineCompliance\Item', 'survey_id');
    }

    public function itemUndecommission() {
        return $this->hasMany('App\Models\ShineCompliance\Item', 'survey_id')->where('tbl_items.decommissioned', 0);
    }

    public function location() {
        return $this->hasMany('App\Models\ShineCompliance\Location','survey_id');
    }
    // public function locationUndecommission() {
    //     return $this->hasMany('App\Models\ShineCompliance\Location','survey_id')->where('tbl_location.decommissioned', 0)
    //         ->join('tbl_area', 'tbl_area.id', '=', 'tbl_location.area_id')
    //         ->orderBy('tbl_area.area_reference','asc')
    //         ->orderBy('tbl_location.location_reference');

    // }
    public function locationUndecommission() {
        return $this->hasMany('App\Models\ShineCompliance\Location','survey_id')->where('tbl_location.decommissioned', 0)->orderBy('location_reference');
    }

    public function surveyReason() {
        return $this->belongsTo('App\Models\ShineCompliance\DropdownDataProperty','reason','id');
    }

    public function workStream() {
        return $this->hasOne('App\Models\ShineCompliance\WorkStream','id','work_stream');
    }

    /**
     * GET Survey Type By int
     */
    public function getSurveyTypeTextAttribute() {
        $surveyType = $this->attributes['survey_type'];
        switch ($surveyType) {
            case 1:
                return 'Management Survey';
            case 2:
                return 'Refurbishment Survey';
            case 3:
                return 'Re-Inspection Report';
            case 4:
                return 'Demolition Survey';
            case 5:
                return 'Management Survey – Partial';
            case 6:
                return 'Sample Survey';
        }

        return '';
    }

    public function getReferenceWorkFlowAttribute() {
        $external_laboratory = $this->surveySetting->external_laboratory ?? 0;
        if ($external_laboratory == 1) {
            return ($this->attributes['reference'] . ' (External Laboratory)');
        }

        return $this->attributes['reference'];
    }

    public function getStatusTextAttribute() {
        $statusText = "";

        switch ($this->attributes['status']) {
            case 1:
                $statusText = "New Survey";
                break;
            case 2:
                $statusText = "<span class='orange_text'>Locked</span>";
                break;
            case 3:
                $statusText = "Ready for QA";
                break;
            case 4:
                $statusText = "Published";
                break;
            case 5:
                $statusText = "Completed";
                break;
            case 6:
                $statusText = "Rejected";
                break;
            case 7:
                $statusText = "<span class='blue_text'>Sent back</span>";
                break;
            case 8:
                $statusText = "<span class='red_text'>Aborted</span>";
                break;
            default:
                break;
        }

        return $statusText;
    }

}

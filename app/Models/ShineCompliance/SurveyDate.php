<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class SurveyDate extends ModelBase
{
    protected $table = 'tbl_survey_date';

    protected $fillable = [
        "survey_id",
        "due_date",
        "completed_date",
        "started_date",
        "sent_out_date",
        "published_date",
        "sent_back_date",
        "surveying_start_date",
        "surveying_finish_date",
        "sample_sent_to_lab_date",
        "sample_received_from_lab_date",
        "sample_results_updated",
        "deleted_by",
        "created_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    public function getRiskWarningAttribute() {
        $due_date = $this->attributes['due_date'];
        if ($due_date) {
            $daysRemain = intval(($due_date - time()) / 86400);
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

    public function getSentOutDateRawAttribute(){
        return $this->attributes['sent_out_date'];
    }

    public function getSurveyingFinishDateSortAttribute()
    {
        $value = $this->attributes['surveying_finish_date'];
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("Y/m/d", $value);
    }
}

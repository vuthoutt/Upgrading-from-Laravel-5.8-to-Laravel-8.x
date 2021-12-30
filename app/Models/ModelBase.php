<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ModelBase extends Model
{
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    public function getAddedAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getCompletedDateAttribute($value) {

        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y H:i", $value);
    }

    public function getCompletedDateRawAttribute($value) {
        return $value;
    }

    public function getlastAsbestosTrainingAttribute($value) {

        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getlastShineAsbestosTrainingAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getdateAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getDueDateAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getStartedDateAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getSurveyingStartDateAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

     public function getSurveyingFinishDateAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getPublishedDateAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getupdatedDateAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getlastRevisionAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }
    public function getDateOfReportAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getEnforcementDeadlineAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }

    public function getDateOfIncidentAttribute($value) {

        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }
    public function getPlanDateAttribute($value) {
        if (is_null($value) || $value == 0) {
            return null;
        }
        return date("d/m/Y", $value);
    }
}

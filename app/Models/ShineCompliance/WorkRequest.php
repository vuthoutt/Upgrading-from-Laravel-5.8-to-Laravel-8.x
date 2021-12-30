<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;
use Carbon\Carbon;

class WorkRequest extends ModelBase
{
    protected $table = 'tbl_work_request';

    protected $fillable = [
        'reference',
        'type',
        'is_major',
        'property_id',
        'property_id_major',
        'project_id',
        'project_id_major',
        'status',
        'work_flow',
        'asbestos_lead',
        'second_asbestos_lead',
        'decommissioned',
        'decommisioned_reason',
        'published_date',
        'completed_date',
        'rejected_date',
        'due_date',
        'created_date',
        'document_present',
        'priority',
        'priority_reason',
        'is_locked',
        'comments',
        'sor_id',
        'team1',
        'team2',
        'team3',
        'team4',
        'team5',
        'team6',
        'team7',
        'team8',
        'team9',
        'team10',
        'non_user',
        'created_by',
        'created_at',
        'updated_at',

    ];

    public function publishedWorkRequest() {
        return $this->hasMany('App\Models\ShineCompliance\PublishedWorkRequest','work_request_id')->orderBy('revision','desc');
    }

    public function property() {
        return $this->belongsTo('App\Models\ShineCompliance\Property','property_id','id');
    }
    public function workContact() {
        return $this->hasOne('App\Models\ShineCompliance\WorkContact','work_id','id');
    }

    public function requester() {
        return $this->belongsTo('App\User','created_by');
    }

    public function asbestosLead() {
        return $this->belongsTo('App\User','asbestos_lead');
    }

    public function workData() {
        return $this->hasOne('App\Models\ShineCompliance\WorkData','id','type');
    }

    public function workFlow() {
        return $this->hasOne('App\Models\ShineCompliance\WorkFlow','id','work_flow');
    }

    public function workPropertyInfo() {
        return $this->hasOne('App\Models\ShineCompliance\WorkPropertyInfo','work_id','id');
    }

    public function workScope() {
        return $this->hasOne('App\Models\ShineCompliance\WorkScope','work_id','id');
    }

    public function sorLogic() {
        return $this->belongsTo('App\Models\ShineCompliance\SorLogic','sor_id','id');
    }

    public function decommissionedReason()
    {
        return $this->hasOne('App\Models\ShineCompliance\DecommissionReason','id','decommisioned_reason','id');
    }

    public function workRequirement() {
        return $this->hasOne('App\Models\ShineCompliance\WorkRequirement','work_id','id');
    }

    public function workSupportingDocument() {
        return $this->hasMany('App\Models\ShineCompliance\WorkSupportingDocument','work_id');
    }

    public function projects() {
        return $this->hasMany('App\Models\ShineCompliance\Project','work_id','id');
    }

    public function workPriority() {
        return $this->hasOne('App\Models\ShineCompliance\WorkData','id','priority');
    }

    public function orchardJob() {
        return $this->hasOne('App\Models\ShineCompliance\OrchardJob','work_id','id');
    }

    public function getTeamAttribute() {
        $contact = [];
        for ($i=1; $i <= 10; $i++) {
            $name = 'team'. $i;
            if (!is_null($this->attributes[$name]) and $this->attributes[$name] != 0 ) {
                $contact[] = $this->attributes[$name];
            }
        }
        return $contact;
    }

    /**
     * Return work type converted text
     * @return string
     */
    public function getWorkTypeAttribute()
    {
        $work_request_type= "";
        if ($this->workData) {
            switch ($this->workData->type) {
                case 'air':
                    $work_request_type = 'Air Monitoring';
                    break;
                case 'remediation':
                    $work_request_type = 'Remediation';
                    break;
                case 'survey':
                    $work_request_type = 'Survey';
                    break;
            }
        }
        return $work_request_type;
    }


    /**
     * Get Status Text
     * @return string
     */
    public function getStatusTextAttribute() {
        switch ($this->attributes['status']) {
            case 1:
                $text = 'New Work Request';
                break;
            case 2:
                $text = 'Ready for QA';
                break;
            case 3:
                $text = 'Published';
                break;
            case 4:
                $text = 'Completed';
                break;
            case 5:
                $text = 'Rejected';
                break;

            default:
                $text = '';
                break;
        }

        return $text;
    }

    /**
     * Return last date in the work request table
     * @return false|string|null
     */
    public function getJobNumberAttribute() {
        // TODO SET JOB NUMBER IN HERE
        return null;
    }
}

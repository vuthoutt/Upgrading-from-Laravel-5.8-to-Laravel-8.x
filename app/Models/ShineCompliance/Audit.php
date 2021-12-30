<?php


namespace App\Models\ShineCompliance;


class Audit extends ModelBase
{
    protected $table = 'tbl_audit';

    protected $fillable = [
        'reference',
        'type',
        'property_id',
        'project_id',
        'survey_id',
        'auditor_id',
        'organisation_id',
        'status',
        'is_locked',
        'asbestos_lead',
        'second_asbestos_lead',
        'decommissioned',
        'decommissioned_reason',
        'published_date',
        'completed_date',
        'started_date',
        'date',
        'finished_date',
        'rejected_date',
        'due_date',
        'created_date',
        'client_ids',
        'objective_scope',
        'created_by',
        'sent_out_date',
        'send_back_date',
        'reject_note',
        'result',
        'comment'
    ];

    public function auditType() {
        return $this->belongsTo('App\Models\AuditType', 'type', 'id');
    }

    public function auditor() {
        return $this->belongsTo('App\User', 'auditor_id', 'id');
    }

    public function asbestosLead() {
        return $this->belongsTo('App\User', 'asbestos_lead', 'id');
    }

    public function property() {
        return $this->belongsTo('App\Models\Property', 'property_id', 'id');
    }

    public function project() {
        return $this->hasOne('App\Models\Project','id','project_id');
    }

    public function survey() {
        return $this->hasOne('App\Models\Survey','id','survey_id');
    }

    public function organisationInvolved() {
        return $this->hasMany('App\Models\AuditOrganisationInvolved','audit_id','id');
    }

    public function documents() {
        return $this->hasMany('App\Models\AuditSupportingDocument','audit_id','id');
    }

    public function photoGraphy()
    {
        return $this->hasMany('App\Models\AuditPhotography', 'audit_id', 'id');
    }

    public function decommissionedReason()
    {
        return $this->hasOne('App\Models\DecommissionReason','id','decommissioned_reason');
    }

    public function publishedAudit() {
        return $this->hasMany('App\Models\PublishedAudit','audit_id')->orderBy('revision','desc');
    }

    public function auditPrinciple() {
        return $this->hasOne('App\Models\AuditPrinciple','audit_id');
    }

    public function getStatusTextAttribute() {
        $statusText = "";

        switch ($this->attributes['status']) {
            case 1:
                $statusText = "New Audit";
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

    public function getResultAttribute () {
        return json_decode($this->attributes['result']);
    }

}

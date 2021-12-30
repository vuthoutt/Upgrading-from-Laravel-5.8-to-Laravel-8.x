<?php


namespace App\Models\ShineCompliance;


class AuditOrganisationInvolved extends ModelBase
{
    protected $table = 'tbl_audit_organisations_involved';

    protected $fillable = [

        "audit_id",
        "client_id",
        "site_analyst",
        "site_analyst_non_user",
        "site_supervisor",
        "site_supervisor_non_user",
        "contracts_manager",
        "contracts_manager_non_user",
        "site_surveyor",
        "site_surveyor_non_user",
        "created_at",
        "updated_at"

    ];

    public function site_analyst_user() {
        if ($this->attributes['site_analyst_non_user'] == 1) {
            return $this->hasOne('App\Models\AuditNonUser', 'id', 'site_analyst');
        } else {
            return $this->hasOne('App\User', 'id', 'site_analyst');
        }
    }

    public function site_surveyor_user() {
        if ($this->attributes['site_surveyor_non_user'] == 1) {
            return $this->hasOne('App\Models\AuditNonUser', 'id', 'site_surveyor');
        } else {
            return $this->hasOne('App\User', 'id', 'site_surveyor');
        }
    }

    public function site_supervisor_user() {
        if ($this->attributes['site_supervisor_non_user'] == 1) {
            return $this->hasOne('App\Models\AuditNonUser', 'id','site_supervisor');
        } else {
            return $this->hasOne('App\User', 'id', 'site_supervisor');
        }
    }

    public function contracts_manager_user() {
        if ($this->attributes['contracts_manager_non_user'] == 1) {
            return $this->hasOne('App\Models\AuditNonUser', 'id','contracts_manager');
        } else {
            return $this->hasOne('App\User', 'id', 'contracts_manager');
        }
    }

    public function getSelectedSiteAnalystAttribute() {
        if ($this->attributes['site_analyst_non_user'] == 1) {
            return -1;
        }
        return $this->attributes['site_analyst'];
    }

    public function getSelectedSiteSupervisorAttribute() {
        if ($this->attributes['site_supervisor_non_user'] == 1) {
            return -1;
        }
        return $this->attributes['site_supervisor'];
    }

    public function getSelectedContractsManagerAttribute() {
        if ($this->attributes['contracts_manager_non_user'] == 1) {
            return -1;
        }
        return $this->attributes['contracts_manager'];
    }

    public function getSelectedSiteSurveyorAttribute() {
        if ($this->attributes['site_surveyor'] == 1) {
            return -1;
        }
        return $this->attributes['site_surveyor'];
    }
}

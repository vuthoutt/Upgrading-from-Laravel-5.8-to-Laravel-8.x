<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class JobRole extends ModelBase
{
    protected $table = 'cp_job_roles';

    protected $fillable = [
        'name'
    ];

    public function jobRoleViewValue() {
        return $this->hasOne(JobRoleViewValue::class,'role_id');
    }

    public function jobRoleEditValue() {
        return $this->hasOne(JobRoleEditValue::class,'role_id');
    }

    public function propertyView() {
        return $this->belongsToMany(Property::class, 'cp_job_role_view_property','role_id','property_id');
    }

    public function propertyEdit() {
        return $this->belongsToMany(Property::class, 'cp_job_role_edit_property','role_id','property_id');
    }

    public function getTabRoleText($type){
        $text = '';
        switch ($type){
            case JOB_ROLE_GENERAL:
                $text = 'General';
                break;
            case JOB_ROLE_ASBESTOS:
                $text = 'Asbestos';
                break;
            case JOB_ROLE_FIRE:
                $text = 'Fire';
                break;
            case JOB_ROLE_GAS:
                $text = 'Gas';
                break;
            case JOB_ROLE_ME:
                $text = 'M&E';
                break;
            case JOB_ROLE_RCF:
                $text = 'RCF';
                break;
            case JOB_ROLE_WATER:
                $text = 'Water';
                break;
            case JOB_ROLE_H_S:
                $text = 'H&S';
                break;
        }
        return $text;
    }

    public function getKeyByType($type){
        $key = '';
        switch ($type){
            case JOB_ROLE_GENERAL:
                $key = 'general';
                break;
            case JOB_ROLE_ASBESTOS:
                $key = 'asbestos';
                break;
            case JOB_ROLE_FIRE:
                $key = 'fire';
                break;
            case JOB_ROLE_GAS:
                $key = 'gas';
                break;
            case JOB_ROLE_ME:
                $key = 'me';
                break;
            case JOB_ROLE_RCF:
                $key = 'rcf';
                break;
            case JOB_ROLE_WATER:
                $key = 'water';
                break;
            case JOB_ROLE_H_S:
                $key = 'hs';
                break;
        }
        return $key;
    }



    public function getCommonEverythingViewByType($type){
        $key = $this->getKeyByType($type);
        return isset($this->jobRoleViewValue) && isset(json_decode($this->jobRoleViewValue->common_everything  ?? '')->{$key}) ? json_decode($this->jobRoleViewValue->common_everything)->{$key} : 0;
    }

    public function getCommonEverythingUpdateByType($type){
        $key = $this->getKeyByType($type);
        return isset($this->jobRoleEditValue) && isset(json_decode($this->jobRoleEditValue->common_everything  ?? '')->{$key}) ? json_decode($this->jobRoleEditValue->common_everything)->{$key} : 0;
    }

    public function getAsbestosReporting(){
        $key = $this->getKeyByType(JOB_ROLE_ASBESTOS);
        return isset(json_decode($this->jobRoleViewValue->common_dynamic_values_view ?? '')->{$key}) ? json_decode(json_decode($this->jobRoleViewValue->common_dynamic_values_view)->{$key})->reporting : [];
    }

    public function getGeneralEmailNotificationViewByType($type){
        $key = $this->getKeyByType($type);
        return isset($this->jobRoleViewValue) && isset(json_decode($this->jobRoleViewValue->common_dynamic_values_view ?? '')->{$key}) ? json_decode(json_decode($this->jobRoleViewValue->common_dynamic_values_view)->{$key})->email_notification : [];
    }

    public function getGeneralReportingViewByType($type){
        $key = $this->getKeyByType($type);
        return isset($this->jobRoleViewValue) && isset(json_decode($this->jobRoleViewValue->common_dynamic_values_view ?? '')->{$key}) ? json_decode(json_decode($this->jobRoleViewValue->common_dynamic_values_view)->{$key})->reporting : [];
    }

    public function getGeneralOrganisationViewByType($type, $organisation_key){
        $key = $this->getKeyByType($type);
        return isset($this->jobRoleViewValue) && isset(json_decode($this->jobRoleViewValue->common_dynamic_values_view ?? '')->{$key}) && json_decode(json_decode($this->jobRoleViewValue->common_dynamic_values_view)->{$key})->{$organisation_key}? json_decode(json_decode(json_decode($this->jobRoleViewValue->common_dynamic_values_view)->{$key})->{$organisation_key}, true) : [];
    }

    public function getGeneralOrganisationEditByType($type, $organisation_key){
        $key = $this->getKeyByType($type);
        return isset($this->jobRoleEditValue) && isset(json_decode($this->jobRoleEditValue->common_dynamic_values_update ?? '')->{$key}) && json_decode(json_decode($this->jobRoleEditValue->common_dynamic_values_update)->{$key})->{$organisation_key}? json_decode(json_decode(json_decode($this->jobRoleEditValue->common_dynamic_values_update)->{$key})->{$organisation_key}, true) : [];
    }

    public function getStaticViewValue($type){
        $key = $this->getKeyByType($type);
        return isset($this->jobRoleViewValue) && isset(json_decode($this->jobRoleViewValue->common_static_values_view ?? '')->{$key}) ? json_decode(json_decode($this->jobRoleViewValue->common_static_values_view)->{$key}) : [];
    }

    public function getStaticEditValue($type){
        $key = $this->getKeyByType($type);
        return isset($this->jobRoleEditValue) && isset(json_decode($this->jobRoleEditValue->common_static_values_update ?? '')->{$key}) ? json_decode(json_decode($this->jobRoleEditValue->common_static_values_update)->{$key}) : [];
    }

    public function getGeneralValueViewByType($type, $view_key){
        $key = $this->getKeyByType($type);
        return isset($this->jobRoleViewValue) &&
        isset(json_decode($this->jobRoleViewValue->common_dynamic_values_view)->{$key}) &&
        isset(json_decode(json_decode($this->jobRoleViewValue->common_dynamic_values_view)->{$key})->{$view_key}) &&
        json_decode(json_decode($this->jobRoleViewValue->common_dynamic_values_view)->{$key})->{$view_key} ?
        json_decode(json_decode($this->jobRoleViewValue->common_dynamic_values_view)->{$key})->{$view_key} : [];
    }

    public function getGeneralValueEditByType($type, $view_key){
        $key = $this->getKeyByType($type);
        return isset($this->jobRoleEditValue) &&
        isset(json_decode($this->jobRoleEditValue->common_dynamic_values_update)->{$key}) &&
        isset(json_decode(json_decode($this->jobRoleEditValue->common_dynamic_values_update)->{$key})->{$view_key}) &&
        json_decode(json_decode($this->jobRoleEditValue->common_dynamic_values_update)->{$key})->{$view_key}?
        json_decode(json_decode($this->jobRoleEditValue->common_dynamic_values_update)->{$key})->{$view_key} : [];
    }

    public function getCommonStaticValueByType($type) {
        $key = $this->getKeyByType($type);

        $data = json_decode($this->jobRoleViewValue->common_static_values_view ?? null);

        return $data->{$key} ?? null;
    }

    public function getCommonDynamicValueByType($type) {
        $key = $this->getKeyByType($type);
        $data = json_decode($this->jobRoleViewValue->common_dynamic_values_view ?? '')->{$key} ?? '';

        return $data;
    }

    public function getCommonStaticValueUpdateByType($type) {
        $key = $this->getKeyByType($type);
        $data = json_decode($this->jobRoleEditValue->common_static_values_update ?? null);
        return $data->{$key} ?? null;
    }

    public function getCommonDynamicValueUpdateByType($type) {
        $key = $this->getKeyByType($type);

        $data = json_decode($this->jobRoleEditValue->common_dynamic_values_update ?? null);
        return $data->{$key} ?? null;
    }

//    public function getGeneralIsOperativeByType($type){
//        $key = $this->getKeyByType($type);
//        return isset(json_decode($this->jobRoleViewValue->general_is_operative)->{$key}) ? json_decode($this->jobRoleViewValue->general_is_operative)->{$key} : 0;
//    }
//
//    public function getGeneralIsTTByType($type){
//        $key = $this->getKeyByType($type);
//        return isset(json_decode($this->jobRoleViewValue->general_is_tt)->{$key}) ? json_decode($this->jobRoleViewValue->general_is_tt)->{$key} : 0;
//    }
}

<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;
use App\Models\ShineCompliance\DropdownItem\Extent;

class Hazard extends ModelBase
{
    protected $table = 'cp_hazards';

    protected $fillable = [
        'record_id',
        'is_locked',
        'reference',
        'name',
        'property_id',
        'assess_id',
        'assess_type',
        'created_date',
        'area_id',
        'location_id',
        'decommissioned',
        'decommissioned_reason',
        'is_temp',
        'type',
        'reason',
        'reason_other',
        'likelihood_of_harm',
        'hazard_potential',
        'total_risk',
        'extent',
        'photo_override',
        'measure_id',
        'act_recommendation_noun',
        'act_recommendation_noun_other',
        'act_recommendation_verb',
        'act_recommendation_verb_other',
        'action_responsibility',
        'comment',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'linked_question_id',
        'linked_project_id',
    ];

    public function hazardType()
    {
        return $this->hasOne(HazardType::class, 'id', 'type');
    }

    public function actionResponsibility()
    {
        return $this->hasOne(HazardActionResponsibility::class, 'id', 'action_responsibility');
    }

    public function area() {
        return $this->belongsTo(Area::class,'area_id', 'id');
    }

    public function location() {
        return $this->belongsTo(Location::class,'location_id', 'id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function assessment() {
        return $this->belongsTo(Assessment::class,'assess_id', 'id');
    }

    public function hazardPotential()
    {
        return $this->hasOne(HazardPotential::class, 'id', 'hazard_potential');
    }

    public function hazardLikelihoodHarm()
    {
        return $this->hasOne(HazardLikelihoodHarm::class, 'id', 'likelihood_of_harm');
    }

    public function hazardMeasurement()
    {
        return $this->hasOne(Extent::class, 'id', 'measure_id');
    }

    public function hazardVerb()
    {
        return $this->hasOne(HazardActionRecommendationVerb::class, 'id', 'act_recommendation_verb');
    }

    public function hazardNoun()
    {
        return $this->hasOne(HazardActionRecommendationNoun::class, 'id', 'act_recommendation_noun');
    }

    public function hazardSpecificLocation()
    {
        return $this->hasOne(HazardSpecificLocationValue::class, 'hazard_id', 'id');
    }

    public function hazardSpecificLocationValue() {
        return $this->hasOne(HazardSpecificLocationValue::class,'hazard_id','id');
    }

    public function specificLocationView() {
        return $this->hasOne(HazardSpecificLocationView::class, 'hazard_id','id');
    }

    public function hazardPhotoshineDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ShineDocumentStorage','object_id', 'id')->where('type',HAZARD_PHOTO);
    }

    public function inaccessReason() {
        return $this->hasOne(HazardInAccessibleReason::class, 'id', 'reason');
    }

    public function nonconformity() {
        return $this->hasOne(Nonconformity::class,'hazard_id', 'id');
    }

    public function hazardLocationPhotoshineDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ShineDocumentStorage','object_id', 'id')->where('type',HAZARD_LOCATION_PHOTO);
    }

    public function hazardAdditionalPhotoshineDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ShineDocumentStorage','object_id', 'id')->where('type',HAZARD_ADDITION_PHOTO);
    }

    public function linkedQuestion() {
        return $this->belongsTo(AssessmentQuestion::class,'linked_question_id', 'id');
    }

    public function linkedProject() {
        return $this->belongsTo(Project::class,'linked_project_id', 'id');
    }

    public function decommissionReason()
    {
        return $this->belongsTo('App\Models\DecommissionReason','decommissioned_reason','id');
    }

    public function getActionRecommendationsAttribute(){
        $verb = $this->hazardVerb->description ?? '';
        $noun = $this->hazardNoun->description ?? '';
        return implode(" ", array_filter([$verb,$noun]));
    }

    public function getTitlePresentationAttribute($value) {
        return implode(" - ", array_filter([
            $this->attributes['reference'] ?? '',
            $this->attributes['name'] ?? ''
        ]));
    }
}

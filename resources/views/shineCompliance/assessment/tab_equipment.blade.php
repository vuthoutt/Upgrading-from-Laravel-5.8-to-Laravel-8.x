<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.assessment_system', [
            'title' => 'Assessment System Summary',
            'tableId' => 'assessment_system',
            'collapsed' => false,
            'plus_link' => ($assessment->is_locked == 0) and $canBeUpdateSurvey ? true : false,
            'link' => route('shineCompliance.system.get_add_system',['assess_id' => $data->id]),
            'data' => $data->systems ?? [],
            'order_table' => "[]"
            ])
    </div>
</div>

<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.assessment_equipment', [
            'title' => 'Assessment Equipment Summary',
            'tableId' => 'assessment_equipment',
            'collapsed' => false,
            'plus_link' => ($assessment->is_locked == 0) and $canBeUpdateSurvey ? true : false,
            'link' => route('shineCompliance.equipment.get_add_equipment',['property_id' => $data->property_id,'assess_id' => $data->id]),
            'data' => $data->equipments ?? [],
            'order_table' => "[]"
            ])
    </div>
</div>
@if($assessment->classification == ASSESSMENT_FIRE_TYPE && $assessment->assessmentInfo->setting_fire_safety)
    <div class="row">
        <div class="col-12 mb-1">
            @include('shineCompliance.tables.assessment_fire_safety', [
                'title' => 'Assessment Fire Safety Systems & Equipment',
                'tableId' => 'assessment_fire_safety',
                'collapsed' => false,
                'plus_link' => ($assessment->is_locked == 0) ? true : false,
                'modal_id' => 'fire-safety-add',
                'data' => \ComplianceHelpers::getFireSafety($assessment->assessmentInfo->fire_safety, $assessment->assessmentInfo->fire_safety_other),
                'order_table' => "[]"
                ])
        </div>
    </div>
@include('shineCompliance.modals.assessment_fire_safety_add',[
    'color' => 'orange',
    'modal_id' => 'fire-safety-add',
    'url' => route('shineCompliance.ajax.assessment_fire_safety'),
    'data' => $assessment,
])
@endif
<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.assessment_system', [
            'title' => 'Assessment Decommissioned System Summary',
            'tableId' => 'assessment_decommissioned_system',
            'collapsed' => true,
            'plus_link' => false,
            'data' => $data->decommissionSystems ?? [],
            'order_table' => "[]"
            ])
    </div>
</div>

<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.assessment_equipment', [
            'title' => 'Assessment Decommissioned Equipment Summary',
            'tableId' => 'assessment_decommissioned_equipment',
            'collapsed' => true,
            'plus_link' => false,
            'data' => $data->decommissionEquipments ?? [],
            'order_table' => "[]"
            ])
    </div>
</div>

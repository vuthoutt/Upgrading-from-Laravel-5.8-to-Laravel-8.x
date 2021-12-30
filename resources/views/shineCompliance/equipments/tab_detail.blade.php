<div class="offset-top40">
    @include('shineCompliance.forms.form_text',['title' => 'Equipment Name:', 'data' => $equipment->name ?? '' ])
    @include('shineCompliance.forms.form_text',['title' => 'Equipment Reference:', 'data' => $equipment->reference ?? '' ])
    @include('shineCompliance.forms.form_text',['title' => 'Equipment Type:', 'data' => $equipment->equipmentType->description ?? '' ])
    @include('shineCompliance.forms.form_text',['title' => 'Area/Floor:', 'data' => $equipment->area->area_reference ?? 'N/A' ])
    @include('shineCompliance.forms.form_text',['title' => 'Room/Location:', 'data' => $equipment->location->location_reference ?? 'N/A' ])
    @include('shineCompliance.forms.form_text',['title' => 'Status:', 'data' => ($equipment->decommissioned == 0) ? 'Live' : 'Decommissioned' ])
    @include('shineCompliance.forms.form_text',['title' => 'Opreational Use:', 'data' => $equipment->operationalUse->description ?? '' ])
    @include('shineCompliance.forms.form_text',['title' => 'Accessibility:', 'data' => $equipment->state_text ?? '' ])
    @if($equipment->state == 0)
        @include('shineCompliance.forms.form_text',['title' => 'Reason For Inaccessibility:', 'data' => $equipment->inaccessReason->description ?? '' ])
    @endif
    @include('shineCompliance.forms.form_text',['title' => 'Parent:', 'data' => $equipment->parent->reference ?? '' ,
    'link' => ($equipment->parent->assess_id ?? 0) > 0 ? route('shineCompliance.equipment.detail', ['id' => $equipment->parent_id ?? 0]) : route('shineCompliance.register_equipment.detail', ['id' => $equipment->parent_id ?? 0]),
                                                'class_other' => 'equipment_section', 'id' => 'parent_id'])

    @include('shineCompliance.forms.form_text',['title' => 'Hot Parent:', 'data' => $equipment->hotParent->reference ?? '' ,
    'link' => ($equipment->hotParent->assess_id ?? 0) > 0 ? route('shineCompliance.equipment.detail', ['id' => $equipment->hot_parent_id ?? 0]) : route('shineCompliance.register_equipment.detail', ['id' => $equipment->hot_parent_id ?? 0]),
                                                'class_other' => 'equipment_section', 'id' => 'hot_parent_id'])

    @include('shineCompliance.forms.form_text',['title' => 'Cold Parent:', 'data' => $equipment->coldParent->reference ?? '' ,
    'link' => ($equipment->coldParent->assess_id ?? 0) > 0 ? route('shineCompliance.equipment.detail', ['id' => $equipment->cold_parent_id ?? 0]) : route('shineCompliance.register_equipment.detail', ['id' => $equipment->cold_parent_id ?? 0]),
                                                'class_other' => 'equipment_section', 'id' => 'cold_parent_id'])
    @include('shineCompliance.forms.form_text',['title' => 'Linked:', 'data' => $equipment->system->reference ?? '' ,'link' => route('shineCompliance.assessment.system_detail', ['id' => $equipment->system_id ?? 0])])
    @include('shineCompliance.forms.form_text',['title' => 'Specific Location:', 'data' => $equipment->specificLocationView->specific_location ?? '' ])
    @include('shineCompliance.forms.form_text',['title' => 'Frequency of Use:', 'data' => $equipment->frequencyUse->description ?? '' ])
    @include('shineCompliance.forms.form_text_small',['title' => 'Extent:', 'data' => $equipment->extent ?? ''
                                                            ,'measurement' => 'Number','class_other' => 'equipment_section','id' => 'extent'])
</div>

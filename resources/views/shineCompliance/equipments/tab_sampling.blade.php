<div class="offset-top40">
    @include('shineCompliance.forms.form_text',['title' => 'Has a Water Sample been collected?', 'data' => $equipment->has_sample ? 'Yes' : 'No', 'id' => 'has_sample','class_other' => 'equipment_section' ])
    @include('shineCompliance.forms.form_text',['title' => 'Sample Reference:', 'data' => $equipment->sample_reference ?? '' , 'id' => 'sample_reference','class_other' => 'equipment_section'])

</div>

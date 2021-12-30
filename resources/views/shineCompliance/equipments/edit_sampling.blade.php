<div class="offset-top40">
    @include('shineCompliance.forms.form_dropdown',['title' =>'Has a Water Sample been collected?', 'name' => 'has_sample', 'no_first_op' => true, 'compare_value' => $equipment->has_sample,
                'data' => json_decode('[{"id": null, "description": "N/A"}, {"id": 0, "description": "No"}, {"id": 1, "description": "Yes"}]'), 'key'=>'id', 'value'=>'description'])
    @include('shineCompliance.forms.form_input',['title' => 'Sample Reference:', 'name' => 'sample_reference', 'data' => $equipment->sample_reference ,'class_other' => 'equipment_section'])
</div>
@push('javascript')
    <script>
        $(document).ready(function (){
            $('#sample_reference-form').hide();
            $('#has_sample').change(function () {
                if ($(this).val() == 1) {
                    $('#sample_reference-form').show();
                } else {
                    $('input[name=sample_reference]').val('');
                    $('#sample_reference-form').hide();
                }
            })
        });
    </script>
@endpush

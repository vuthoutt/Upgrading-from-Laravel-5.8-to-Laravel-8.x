<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.non_conformities', [
            'title' => 'Assessment Equipment Nonconformity Summary',
            'tableId' => 'non_conformities',
            'collapsed' => false,
            'plus_link' => false,
            'link' => route('shineCompliance.system.get_add_system',['assess_id' => $data->id]),
            'data' => $data->assessmentNonconformities ?? [],
            'order_table' => "[]"
            ])
    </div>
</div>


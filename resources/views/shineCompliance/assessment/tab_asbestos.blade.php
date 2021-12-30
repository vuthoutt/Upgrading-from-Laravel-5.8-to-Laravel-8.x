<div class="row">
    {{-- <div class="col-12 mb-1"> --}}
        @include('shineCompliance.tables.assessment_asbestos', [
            'title' => 'Assessment Asbestos Item Summary',
            'tableId' => 'assessment_asbestos_table',
            'over_all_text' => strtoupper($data->over_all_text),
            'collapsed' => false,
            'plus_link' => false,
            'data' => $asbestos_items ?? '',
            'assess_id' => $assessment->id,
            'order_table' => "[]"
            ])
    {{-- </div> --}}
</div>


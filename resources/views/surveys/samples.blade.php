<div class="row">
    @include('tables.samples', [
        'title' => 'Survey Samples/links',
        'tableId' => 'survey-samples',
        'collapsed' => false,
        'plus_link' => false,
        'data' => $samples,
        'add_select' => true
        ])
</div>
@if(!$is_locked and $canBeUpdateSurvey)
    <div class="row">
        <a class="btn btn_long_size light_grey_gradient mt-4" id="save-sample1"><strong>Save</strong></a>
    </div>
@endif
<div class="row">
    @include('tables.sample_certificate', [
        'title' => 'Survey Sample Certificates',
        'tableId' => 'sample-certificate-table',
        'collapsed' => true,
        'plus_link' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        'edit_permission' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        'modal_id' => 'sample-certificate-add',
        'data' => $data->sampleCertificate,
        'reference' => 'sample_reference',
        'date' => 'updated_date',
        'url' => route('ajax.sample_certificate'),
        'type' => 'sample-certificate'
        ])
</div>
@include('modals.property_plan_add',['title' => 'Sample Certificates Add' ,
                                    'color' => 'orange',
                                    'modal_id' => 'sample-certificate-add',
                                     'url' => route('ajax.sample_certificate'),
                                     'data_site' => $survey,
                                     'survey' => true,
                                     'doc_title' => 'Certificate',
                                     'reference' => 'Reference:'
                                     ])

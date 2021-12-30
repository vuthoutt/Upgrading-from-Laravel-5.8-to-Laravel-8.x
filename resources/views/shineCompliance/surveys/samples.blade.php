<div class="row">
    @include('shineCompliance.tables.samples', [
        'title' => 'Survey Samples/links',
        'tableId' => 'survey-samples',
        'collapsed' => false,
        'plus_link' => false,
        'row_col' => 'col-md-12',
        'data' => $samples,
        'add_select' => true
        ])
</div>
@if(!$is_locked and $canBeUpdateSurvey)
    <div class="row">
        <a class="btn btn_long_size light_grey_gradient_button fs-8pt mt-4" id="save-sample1"><strong>Save</strong></a>
    </div>
@endif
{{--@if(isset($survey->client_id) and ($survey->client_id) == 1)--}}
{{--    <a class="btn btn_long_size light_grey_gradient mt-4" href="#sample-email-co1" data-toggle="modal"><strong>Email</strong></a>--}}
{{--@endif--}}

<div class="row">
    @include('shineCompliance.tables.sample_certificate', [
        'title' => 'Survey Sample Certificates',
        'tableId' => 'sample-certificate-table',
        'collapsed' => true,
        'plus_link' => ((!$is_locked and $canBeUpdateSurvey) or (!$is_locked and $can_upload_sample)) ? true : false,
        'edit_permission' => ((!$is_locked and $canBeUpdateSurvey) or (!$is_locked and $can_upload_sample)) ? true : false,
        'modal_id' => 'sample-certificate-add',
        'data' => $data->sampleCertificate,
        'reference' => 'sample_reference',
        'row_col' => 'col-md-12',
        'date' => 'updated_date',
        'url' => route('shineCompliance.ajax.sample_certificate'),
        'type' => 'sample-certificate'
        ])
</div>
@include('shineCompliance.modals.property_plan_add',['title' => 'Sample Certificates Add' ,
                                    'color' => 'orange',
                                    'modal_id' => 'sample-certificate-add',
                                     'url' => route('shineCompliance.ajax.sample_certificate'),
                                     'data_site' => $survey,
                                     'survey' => true,
                                     'doc_title' => 'Certificate',
                                     'reference' => 'Reference:',
                                     'assessment' => true
                                     ])
@include('shineCompliance.modals.email_sample',['title' => 'Email Notification' ,
                                    'color' => 'orange',
                                    'modal_id' => 'sample-email-co1',
                                     'url' => route('shineCompliance.ajax.sample_email_co1'),
                                     'data_site' => $survey,
                                     'survey' => true,
                                     'doc_title' => 'Certificate',
                                     'reference' => 'Reference:',
                                     ])

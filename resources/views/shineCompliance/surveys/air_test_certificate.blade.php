<div class="row">
    @include('shineCompliance.tables.sample_certificate', [
        'title' => 'Survey Air Test Certificates',
        'tableId' => 'survey-air-test',
        'collapsed' => false,
        'plus_link' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        'edit_permission' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        'modal_id' => 'air-test-certificate-add',
        'data' => $survey->airTestCertificate,
        'reference' => 'air_test_reference',
        'date' => 'updated_date',
        'url' => route('shineCompliance.ajax.air_test_certificate'),
        'type' => 'air-test'
        ])
    @include('shineCompliance.modals.property_plan_add',['title' => 'Air Test Certificates Add' ,
                                        'color' => 'orange',
                                        'modal_id' => 'air-test-certificate-add',
                                        'url' => route('shineCompliance.ajax.air_test_certificate'),
                                        'data_site' => $survey,
                                        'survey' => true,
                                        'doc_title' => 'Certificate',
                                        'row_col' => 'col-md-12',
                                        'reference' => 'Reference:',
                                        'assessment' =>true,
                                        'doc_type' => 'air-test-certificate',
                                     ])
</div>

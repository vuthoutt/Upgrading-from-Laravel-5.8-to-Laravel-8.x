 <div class="row">
    @if($view_detail)
    <div class="col-md-12 client-image-show mt-3" >
        <div class="col-md-5">
            <img class="image-signature" src="{{ CommonHelpers::getFile($data->id, CLIENT_LOGO) }}">
        </div>
        <div class="col-md-5 offset-top20" style="display: flex;">
            <a title="Download Asbestos Register Image" href="{{ route('retrive_image',['type'=>  CLIENT_LOGO ,'id'=> $data->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
        </div>
    </div>
    @endif
    <div class="row col-md-12 mt-3">
        @if($view_detail)
            <div class="col-md-12">
                @include('forms.form_text',['title' => ($type == CONTRACTOR_TYPE) ? 'Name:' : 'Organisation Name:', 'data' => $data->name ])
                @include('forms.form_text',['title' => 'Shine Reference:', 'data' => $data->reference ])
                @include('forms.form_text',['title' => 'Address 1:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->address1 : ''])
                @include('forms.form_text',['title' => 'Address 2:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->address2 : ''])
                @include('forms.form_text',['title' => 'Town:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->address3 : ''])
                @include('forms.form_text',['title' => 'City:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->address4 : ''])
                @include('forms.form_text',['title' => 'County:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->address5 : ''])
                @include('forms.form_text',['title' => 'Postcode:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->postcode : ''])
                @include('forms.form_text',['title' => 'Country:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->country : ''])
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5 form-input-text offset-md-3" >
                        <strong>General Enquiries</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                @include('forms.form_text',['title' => ($type == CONTRACTOR_TYPE) ? 'Contractor  Contact:' : 'My Organisation Contact:', 'link' => route('profile',['user' => $data->key_contact]),'data' => !is_null($data->mainUser) ? $data->mainUser->full_name : ''])
                @include('forms.form_text',['title' => 'Telephone:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->telephone : ''])
                @include('forms.form_text',['title' => 'Mobile:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->mobile : ''])
                @include('forms.form_text',['title' => 'Email:', 'data' => !is_null($data->email) ? $data->email : ''])
                @if($data->client_type == 0 and \CommonHelpers::isSystemClient())
                    @include('forms.form_text',['title' => 'Account Management Email:', 'data' => !is_null($data->clientInfo) ? $data->clientInfo->account_management_email : ''])
                @endif
                @if($data->client_type == 1 || ($type == CONTRACTOR_TYPE))
                    @if(\CommonHelpers::isSystemClient())
                        @include('forms.form_text',['title' => 'Tender Notification Email:', 'data' => !is_null($data->email_notification) ? $data->email_notification : ''])
                        @include('forms.form_text',['title' => 'Fax:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->fax : ''])
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5 form-input-text offset-md-3" >
                                    <strong>Contractor Type</strong>
                                </div>
                            </div>
                        </div>
                        <strong style="color: black">Asbestos</strong>
                        @include('forms.form_text',['title' => 'Surveying:', 'data' => ( isset($data->clientInfo->type_surveying) and ($data->clientInfo->type_surveying == 1) ) ? 'Yes' : 'No'])
                        @include('forms.form_text',['title' => 'Removal:', 'data' => ( isset($data->clientInfo->type_removal) and ($data->clientInfo->type_removal == 1) ) ? 'Yes' : 'No'])
                        @include('forms.form_text',['title' => 'Demolition:', 'data' => ( isset($data->clientInfo->type_demolition) and ($data->clientInfo->type_demolition == 1) ) ? 'Yes' : 'No'])
                        @include('forms.form_text',['title' => 'Analytical:', 'data' => ( isset($data->clientInfo->type_analytical) and ($data->clientInfo->type_analytical == 1) ) ? 'Yes' : 'No'])

                        <strong style="color: black">Fire</strong>
                        @include('forms.form_text',['title' => 'Fire Equipment Assessment:', 'data' => ( isset($data->clientInfo->type_fire_equipment) and ($data->clientInfo->type_fire_equipment == 1) ) ? 'Yes' : 'No'])
                        @include('forms.form_text',['title' => 'Fire Risk Assessment:', 'data' => ( isset($data->clientInfo->type_fire_risk) and ($data->clientInfo->type_fire_risk == 1) ) ? 'Yes' : 'No'])
                        @include('forms.form_text',['title' => 'Fire Remedial Assessment:', 'data' => ( isset($data->clientInfo->type_fire_remedial) and ($data->clientInfo->type_fire_remedial == 1) ) ? 'Yes' : 'No'])
                        @include('forms.form_text',['title' => 'Independent Survey:', 'data' => ( isset($data->clientInfo->type_independent_survey) and ($data->clientInfo->type_independent_survey == 1) ) ? 'Yes' : 'No'])
                        @if(env('WATER_MODULE'))
                            <strong style="color: black">Water</strong>
                            @include('forms.form_text',['title' => 'Legionella Risk Assessment:', 'data' => ( isset($data->clientInfo->type_legionella_risk) and ($data->clientInfo->type_legionella_risk == 1) ) ? 'Yes' : 'No'])
                            @include('forms.form_text',['title' => 'Water Testing Assessment:', 'data' => ( isset($data->clientInfo->type_water_testing) and ($data->clientInfo->type_water_testing == 1) ) ? 'Yes' : 'No'])
                            @include('forms.form_text',['title' => 'Water Remedial Assessment:', 'data' => ( isset($data->clientInfo->type_water_remedial) and ($data->clientInfo->type_water_remedial == 1) ) ? 'Yes' : 'No'])
                            @include('forms.form_text',['title' => 'Temperature Assessment:', 'data' => ( isset($data->clientInfo->type_temperature) and ($data->clientInfo->type_temperature == 1) ) ? 'Yes' : 'No'])
                        @endif
                        <strong style="color: black">Health & Safety</strong>
                        @include('forms.form_text',['title' => 'Temperature Assessment:', 'data' => ( isset($data->clientInfo->type_hs_assessment) and ($data->clientInfo->type_hs_assessment == 1) ) ? 'Yes' : 'No'])
                    @endif
                @endif
            </div>
            <div class="col-md-4 col-form-label text-md-left">
                @if($type == CONTRACTOR_TYPE)
                    <a href="{{ route('contractor.get_edit',['client_id' => $data->id, 'type' => $type]) }}" style="text-decoration: none">
                @else
                    <a href="{{ route('my_organisation.get_edit',['client_id' => $data->id, 'type' => $type]) }}" style="text-decoration: none">
                @endif
                    {{-- system owner --}}
                    @if(!\CommonHelpers::isSystemClient() and ($type == CONTRACTOR_TYPE))
                    @else
                        @if($edit_detail)
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt ">
                            <strong>{{ __('Edit') }}</strong>
                        </button>
                        @endif
                    @endif

                </a>
            </div>
        @endif

        @if($view_policy)
            @include('tables.policies', [
                'title' => 'Policy Documents',
                'tableId' => 'policy-table',
                'collapsed' => true,
                'plus_link' => (\CommonHelpers::isSystemClient() and $edit_policy) or (!\CommonHelpers::isSystemClient() and ($type == ORGANISATION_TYPE))  ? true : false,
                'edit_permission' => (\CommonHelpers::isSystemClient() and $edit_policy) or (!\CommonHelpers::isSystemClient() and ($type == ORGANISATION_TYPE)) ? true : false,
                'modal_id' => 'add-policy',
                'data' => $data->policy
                ])
            @include('modals.training_record_add',['color' => 'red', 'modal_id' => 'add-policy','action' => 'edit', 'client_id' => $data->id, 'url' => route('ajax.training_record'), 'doc_type' => 'policy', 'title' => 'Add Policy Document'])
        @endif

        @if($view_traning_record)
            {{-- system owner --}}
            @if(!\CommonHelpers::isSystemClient() and ($type == CONTRACTOR_TYPE))
            @else
                @include('tables.trainning_records', [
                    'title' => 'Training Records',
                    'tableId' => 'training-records',
                    'collapsed' => false,
                    'plus_link' => $edit_traning_record,
                    'modal_id' => 'add-traning-record',
                    'data' => $data->traningRecord,
                    'edit_permission' => $edit_traning_record,
                    ])
                @include('modals.training_record_add',['color' => 'red', 'modal_id' => 'add-traning-record','action' => 'edit', 'client_id' => $data->id, 'url' => route('ajax.training_record'), 'doc_type' => 'training'])
            @endif
        @endif



        @if($view_department)
            @include('tables.client_departments', [
                'title' => 'Directorate',
                'tableId' => 'deparments',
                'collapsed' => false,
                'plus_link' => false,
                'data' =>  ($type == CONTRACTOR_TYPE) ? $departments_contractor : $departments,
                'type' => $type,
                'client_id' => $data->id
                ])
        @endif
    </div>
</div>

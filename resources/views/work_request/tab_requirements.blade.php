 <div class="row offset-top40">
    <div class="col-md-12">
        @include('forms.form_text',['title' => 'Work Requester:', 'data' => CommonHelpers::getUserFullname($data->created_by), 'link' => route('profile', ['id' => $data->created_by]) ])
        @include('forms.form_text',['title' => 'Asbestos Lead:', 'data' => CommonHelpers::getUserFullname($data->asbestos_lead), 'link' => route('profile', ['id' => $data->asbestos_lead]) ])
        @include('forms.form_text',['title' => 'Work Request Type:', 'data' => (optional($data->workData)->description) ])
        @include('forms.form_text',['title' => 'Priority:', 'data' => (optional($data->workPriority)->description) ])
        @if(optional($data->workPriority)->other == 1)
        @include('forms.form_text',['title' => 'Priority Reason:', 'data' => $data->priority_reason ])
        @endif
        @if($data->is_major == 0)
            @include('forms.form_text',['title' => 'Property:', 'data' => (optional($data->property)->name), 'link' => route('property_detail', ['property_id' => $data->property_id, 'section' => SECTION_DEFAULT]) ])
        @else
            @include('forms.form_text',['title' => 'Contractor:', 'data' => $data->contractorRelation->name ?? '', 'link' => route('my_organisation', ['client_id' => $data->contractor]) ])
        @endif
    </div>
     {{--        @include('forms.form_text',['title' => 'Created Date:', 'data' => $data->created_at->format('d/m/Y') ])--}}
     @if($data->is_major == 0)
     <div class="mt-4 col-md-12">
         <label class="font-weight-bold part-heading">PROPERTY INFORMATION</label>
     </div>
     <div class="col-md-12">
         @include('forms.form_text',['title' => 'Property Asset Type:', 'data' => $data->property->assetType->description ?? '' ])
         @include('forms.form_text',['title' => 'Property Access Type:', 'data' => $data->property->propertySurvey->propertyProgrammeType->description  ?? '' ])
         @include('forms.form_text',['title' => 'Property Occupied:', 'data' => ($data->workPropertyInfo->site_occupied == 1) ? 'Yes' : 'No' ])
         @include('forms.form_text',['title' => 'Property Availability:', 'data' => $data->workPropertyInfo->site_availability ?? ''  ])
     </div>
     <div class="mt-4 col-md-12">
         @include('forms.form_text',['title' => 'Security Requirements:', 'data' => $data->workPropertyInfo->security_requirements ?? '' ])
         @if(!isset($data->orchardJob) or $data->orchardJob->status != 1)
            @include('forms.form_link_contact',['title' => 'Property Contact:', 'data' => $property_contact ])
         @endif
         @include('forms.form_text',['title' => 'Parking Arrangements:',
         'data' => (($data->workPropertyInfo->parking->description ?? '') == 'Other' ? $data->workPropertyInfo->parking_arrangements_other : $data->workPropertyInfo->parking->description ?? '') ?? ''
         ])
     </div>
     <div class="mt-4 col-md-12">
         @include('forms.form_text',['title' => 'Electricity Availability:', 'data' =>  ($data->workPropertyInfo->electricity_availability == 1) ? 'Yes' : 'No' ])
         @include('forms.form_text',['title' => 'Water Availability:', 'data' =>  ($data->workPropertyInfo->water_availability == 1) ? 'Yes' : 'No' ])
         @include('forms.form_text',['title' => 'Ceiling Height:', 'data' => $data->workPropertyInfo->ceiling->description ?? '' ])
     </div>
     @endif
     <div class="mt-4 col-md-12">
         <label class="font-weight-bold part-heading">WORK REQUEST SCOPE</label>
     </div>
     <div class="col-md-12">
         @include('forms.form_text',['title' => 'Scope of Work:', 'data' => $data->workScope->scope_of_work ?? '' ])
         @include('forms.form_text',['title' => 'Reported by:', 'data' => $data->workScope->reported_by ?? '' ])
         @include('forms.form_text',['title' => 'Access Note:', 'data' => $data->workScope->access_note ?? '' ])
         @include('forms.form_text',['title' => 'Location Note:', 'data' => $data->workScope->location_note ?? '' ])
         @if(strpos($data->work_type, "Air") !== FALSE)
             @include('forms.form_text',['title' => 'Enclosure Size:', 'data' => ($data->workScope->enclosure_size ?? '') . ' m²'])
             @include('forms.form_text',['title' => 'Duration of Work:', 'data' =>( $data->workScope->duration_of_work ?? '') . ' Days'])
         @elseif(strpos($data->work_type, "Remediation") !== FALSE)
             @include('forms.form_text',['title' => 'Isolation Required:', 'data' => ($data->workScope->isolation_required == 1) ? 'Yes' : 'No'])
             @include('forms.form_text',['title' => 'Decant Required:', 'data' => ($data->workScope->decant_required == 1) ? 'Yes' : 'No'])
             @include('forms.form_text',['title' => 'Re-Instatement Requirements:', 'data' => $data->workRequirement->reinstatement_requirments ?? '' ])
         @elseif($data->is_major == 0 && strpos($data->work_type, "Survey") !== FALSE)
             @if(in_array($data->property->assetType->id ?? 0, [18,19,20]))
                 @include('forms.form_text',['title' => 'Number of Bedrooms:', 'data' => $data->workScope->number_of_rooms ?? ''])
             @endif
             @include('forms.form_text',['title' => 'Unusual Requirements:', 'data' => $data->workScope->unusual_requirements ?? ''])
         @endif
     </div>
     <div class="mt-4 col-md-12">
         <label class="font-weight-bold part-heading">
             HEALTH and SAFETY REQUIREMENTS
         </label>
     </div>
     <div class="col-md-12">
         @include('forms.form_text',['title' => 'Property Specific H&S Requirements:', 'data' => $data->workRequirement->site_hs ?? '' ])

     </div>

     @include('tables.work_request_requirements', [
         'title' => 'Health and Safety Requirements Table',
         'tableId' => 'wr-hs-table',
         'collapsed' => false,
         'plus_link' => false,
         'data' => [],
         'work_request' => $data,
         ])

     <div class="mt-5 col-md-12">
         @include('forms.form_text',['title' => 'Other:', 'data' => $data->workRequirement->other ?? '' ])
     </div>

     <div class="mt-5 ml-2 pb-5">
         @if($data->status == WORK_REQUEST_COMPLETE)
             <div class="spanWarningSurveying">
                 <strong><em>Work request is view only because technical activity is complete</em></strong>
             </div>
        @elseif($data->status == WORK_REQUEST_AWAITING_APPROVAL)
                <div class="spanWarningSurveying">
                <strong><em>Work request is view only because technical activity is in progress</em></strong>
            </div>
        @else
            @if(!\CompliancePrivilege::checkUpdatePermission(JR_RESOURCES,JR_WORK_REQUEST_EDIT) and \CommonHelpers::isSystemClient())
            @else
             <a href="{{ route('wr.get_edit',['id' => $data->id]) }}" style="text-decoration: none">
                 <button type="submit" class="btn light_grey_gradient_button fs-8pt ">
                     <strong>{{ __('Edit') }}</strong>
                 </button>
             </a>
            @endif
         @endif
     </div>
</div>

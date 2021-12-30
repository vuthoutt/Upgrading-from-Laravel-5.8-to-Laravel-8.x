<div class="offset-top40">
    @include('forms.form_text',['title' => 'Item Name:', 'data' => $item->name ])
    @include('forms.form_text',['title' => 'Item ID:', 'data' => $item->reference ])
    <div class="row">
        <label class="col-md-3 col-form-label text-md-left" >
            <span class="font-weight-bold">Reason:</span>
            <a href="" data-toggle="modal" data-target="#item_decommission_comment_history">(History)</a>
        </label>
        <div class="col-md-6 form-input-text" >
            {!! $item->decommissionedReason->description ?? null !!}
        </div>
    </div>
    <br>
    @include('forms.form_text',['title' => 'Assessed:', 'data' => $item->not_assessed == NOT_ASSESSED ? 'Not Assessed' : 'Assessed' ])
    @if($item->not_assessed == NOT_ASSESSED)
    @include('forms.form_text',['title' => 'Reason:', 'data' =>  $item->notAssessedReason->description ?? ''])
    @endif
    <br>
    @include('modals.decommission_history',[ 'color' => ($item->survey_id == 0) ? 'red' : 'orange',
                                'modal_id' => 'item_decommission_comment_history',
                                'header' => 'Historical Room/location Comments',
                                'table_id' => 'item_decommission_comment_history_table',
                                'url' => route('comment.decommission', ['category' => 'item']),
                                'data' => $item->decommissionCommentHistory,
                                'id' => $item->id
                                ])

    @include('forms.form_text',['title' => 'Type:', 'data' => $item->state_text ])
    @if($item->state == ITEM_INACCESSIBLE_STATE)
        @include('forms.form_text',['title' => 'Assessment Type:', 'data' => optional($item->itemInfo)->assessment_type_text ])
        @include('forms.form_text',['title' => 'Reason:', 'data' => $reason ])
    @else
        @include('forms.form_text',['title' => 'Sample/link ID:', 'data' => optional($selectedSample)->description ])
        @if(isset($item->sample))
        <div class="row">
            <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold" >Sample Comment</label>
            <div class="col-md-6 form-input-text" >
                @if($item->record_id == $item->sample->original_item_id and \CommonHelpers::getOsItem($item->sample->original_item_id, $item->survey_id) != 0)
                    <span>{{ $sampleComment }}</span>
                @else
                    <a href="{{ route('item.index',['id' => \CommonHelpers::getOsItem($item->sample->original_item_id, $item->survey_id)]) }}"><span>Visually Referred to Original Sample</span></a>
                @endif
            </div>
        </div>
        @endif
    @endif
    @include('forms.form_text',['title' => 'Area/floor Ref:', 'data' => optional($item->area)->area_reference ])
    @include('forms.form_text',['title' => 'Room/location Ref:', 'data' => optional($item->location)->location_reference ])


    @include('forms.form_text',['title' => 'Specific Location:', 'data' => optional($item->specificLocationView)->specific_location ])
    @include('forms.form_text',['title' => 'Product/debris Type:', 'data' => optional($item->productDebrisView)->product_debris ])
    @include('forms.form_text',['title' => 'Asbestos Type:', 'data' => $item->state == 1 ? 'No ACM Detected' : $item->asbestosTypeView->asbestos_type ])
    @include('forms.form_text',['title' => 'Extent:', 'data' => (isset($item->itemInfo->extent) and $item->itemInfo->extent != '') ?  (($item->itemInfo->extent ?? '').' '. ($item->extentView->extent ?? '')) : '' ])
   {{--  @include('forms.form_text',['title' => 'Accessibility / Vulnerability:', 'data' => optional($item->accessibilityVulnerabilityView)->accessibility_vulnerability ])
    @include('forms.form_text',['title' => 'Additional Information:', 'data' => optional($item->additionalInformationView)->additional_information ]) --}}
{{--     @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_license_status == ACTIVE) )
        @if($item->state == ITEM_ACCESSIBLE_STATE)
            @include('forms.form_text',['title' => 'Licensed/non-licensed Title:', 'data' => optional($item->licensedNonLicensedView)->licensed_non_licensed ])
        @endif
    @endif --}}
    @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_r_and_d_elements == ACTIVE) )
        {{-- @if($item->state == ITEM_ACCESSIBLE_STATE) --}}
            @include('forms.form_text',['title' => 'R&D Element:', 'data' => optional($item->itemInfo)->is_r_and_d_element_text ])
        {{-- @endif --}}
    @endif
{{--     @if($item->state == ITEM_ACCESSIBLE_STATE)
    @include('forms.form_text',['title' => 'Air Test:', 'data' => '' ])
    @include('forms.form_text',['title' => 'Air Test Comments:', 'data' => '' ])
    @endif --}}

    <div class="row">
        <label class="col-md-3 col-form-label text-md-left" >
            <span class="font-weight-bold"> Comments:</span>
            <a href="" data-toggle="modal" data-target="#item_comment_history">(History)</a>
        </label>
        <div class="col-md-6 form-input-text" >
            {!! $item->itemInfo->comment ?? null !!}
        </div>
    </div>

    @include('modals.comment_history',[ 'color' => ($item->survey_id == 0) ? 'red' : 'orange',
                                        'modal_id' => 'item_comment_history',
                                        'header' => 'Historical Item Comments',
                                        'table_id' => 'item_comment_history_table',
                                        'url' => route('comment.item'),
                                        'data' => $item->commentHistory,
                                        'id' => $item->id
                                ])
    @if($item->survey_id == 0)
        <br>
        @include('forms.form_text',['title' => 'Data Stamp:', 'data' => $itemStamping['data_stamping'] ?? 'N/A' ])
        @include('forms.form_text',['title' => 'Organisation:', 'data' => $itemStamping['organisation'] ?? 'N/A' ])
        @include('forms.form_text',['title' => 'Username:', 'data' => $itemStamping['username'] ?? 'N/A' ])
        <br>
        @include('forms.form_text',['title' => 'Creation Date:', 'data' => $itemStamping['data_stamping_create'] ?? 'N/A' ])
        @include('forms.form_text',['title' => 'Organisation:', 'data' => $itemStamping['organisation_create'] ?? 'N/A' ])
        @include('forms.form_text',['title' => 'Username:', 'data' => $itemStamping['username_create'] ?? 'N/A' ])
    @endif
</div>

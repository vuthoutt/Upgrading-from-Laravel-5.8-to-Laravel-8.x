 <div class="row offset-top40">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                @if(CommonHelpers::checkFile($data->id, PROPERTY_SURVEY_IMAGE))
                    <div class="col-md-5" style="margin-bottom: 40px;margin-left: -20px;">
                        <img src="{{ asset(\CommonHelpers::getFile($data->id, PROPERTY_SURVEY_IMAGE)) }}" class="image-signature">
                    </div>
                    <a title="Download Asbestos Register Image" href="{{ CommonHelpers::getFile($data->id, PROPERTY_SURVEY_IMAGE) }}" target="_blank" class="btn download-btn"><i class="fa fa-download"></i></a>
                @elseif(CommonHelpers::checkFile($data->property_id, PROPERTY_IMAGE))
                    <div class="col-md-5" style="margin-bottom: 40px;margin-left: -20px;">
                        <img src="{{ asset(\CommonHelpers::getFile($data->property_id, PROPERTY_IMAGE)) }}" class="image-signature">
                    </div>
                    <a title="Download Asbestos Register Image" href="{{ CommonHelpers::getFile($data->property_id, PROPERTY_IMAGE) }}" target="_blank" class="btn download-btn"><i class="fa fa-download"></i></a>
                @endif
            </div>
            <div class="col-md-8" style="min-height: 370px;">
                @if($data->surveySetting->is_require_construction_details == ACTIVE)
                    @include('forms.form_text',['title' => 'Property Access Type:', 'data' => CommonHelpers::getProgrammeType( optional($data->surveyInfo->property_data)->programmeType , optional($data->surveyInfo->property_data)->programmetypemore ) ])
                    @include('forms.form_text',['title' => 'Primary:', 'data' => CommonHelpers::getProgrammeType( optional($data->surveyInfo->property_data)->PrimaryUse,  optional($data->surveyInfo->property_data)->primaryusemore, 'primary' ) ])
                    @include('forms.form_text',['title' => 'Secondary:', 'data' => CommonHelpers::getProgrammeType( optional($data->surveyInfo->property_data)->SecondaryUse, optional($data->surveyInfo->property_data)->secondaryusemore, 'primary' ) ])
                    @include('forms.form_text',['title' => 'Property Status:', 'data'=> \CommonHelpers::getPropertyText($data->surveyInfo->property_data->property_status ?? '')])
                    @include('forms.form_text',['title' => 'Property Occupied:', 'data'=> \CommonHelpers::getPropertyText($data->surveyInfo->property_data->property_occupied ?? '')])
                    @include('forms.form_text',['title' => 'Age:', 'data' => optional($data->surveyInfo->property_data)->constructionAge ])
                    @include('forms.form_text',['title' => 'Construction Type:', 'data' => optional($data->surveyInfo->property_data)->constructionType ])
                    @include('forms.form_text',['title' => 'No. Floors:', 'data' => \CommonHelpers::getSurveyPropertyInfoText(optional($data->surveyInfo->property_data)->sizeFloors, optional($data->surveyInfo->property_data)->sizeFloorsOther ) ])
                    @include('forms.form_text',['title' => 'No. Staircases:', 'data' => \CommonHelpers::getSurveyPropertyInfoText(optional($data->surveyInfo->property_data)->sizeStaircases, optional($data->surveyInfo->property_data)->sizeStaircasesOther ) ])
                    @include('forms.form_text',['title' => 'No. Lifts:', 'data' => \CommonHelpers::getSurveyPropertyInfoText(optional($data->surveyInfo->property_data)->sizeLifts, optional($data->surveyInfo->property_data)->sizeLiftsOther ) ])
                    @include('forms.form_text',['title' => 'Electrical Meter:', 'data' => \CommonHelpers::getPropertyDropdownData($survey->surveyInfo->property_data->electricalMeter ?? '') ])
                    @include('forms.form_text',['title' => 'Gas Meter:', 'data' => \CommonHelpers::getPropertyDropdownData($survey->surveyInfo->property_data->gasMeter ?? '') ])
                    @include('forms.form_text',['title' => 'Loft Void:', 'data' => \CommonHelpers::getPropertyDropdownData($survey->surveyInfo->property_data->loftVoid ?? '') ])
                    @include('forms.form_text',['title' => 'Net Area per Floor:', 'data' => optional($data->surveyInfo->property_data)->sizeNetArea ])
                    @include('forms.form_text',['title' => 'Gross Area:', 'data' => optional($data->surveyInfo->property_data)->sizeGrossArea ])
                    @include('forms.form_text',['title' => 'Comments:', 'data' => optional($data->surveyInfo->property_data)->sizeComments ])
                @endif
            </div>
            @if(!$is_locked and $canBeUpdateSurvey)
            <a href="{{ route('get.edit_propertyInfo', [ 'survey_id ' => $data->id]) }}" style="text-decoration: none">
                 <button class="btn light_grey_gradient mt-4">
                     <strong>{{ __('Edit') }}</strong>
                 </button>
             </a>
             @endif
        </div>
    </div>
</div>

<div class="row offset-top40">
    <div class="col-md-12">
        <div class="row ">
            <div class="col-md-4">
                @if(CommonHelpers::checkFile($data->id, PROPERTY_ASSESS_IMAGE))
                    <div class="" style="margin-bottom: 40px;">
                        <img src="{{ asset(\CommonHelpers::getFile($data->id, PROPERTY_ASSESS_IMAGE)) }}" class="image-signature">
                    </div>
                    <a title="Download Asbestos Register Image" href="{{ CommonHelpers::getFile($data->id, PROPERTY_ASSESS_IMAGE) }}" target="_blank" class="btn download-btn"><i class="fa fa-download"></i></a>
                @elseif(CommonHelpers::checkFile($data->property_id, PROPERTY_IMAGE))
                    <div class="" style="margin-bottom: 40px;">
                        <img src="{{ asset(\CommonHelpers::getFile($data->property_id, PROPERTY_IMAGE)) }}" class="image-signature">
                    </div>
                    <a title="Download Asbestos Register Image" href="{{ CommonHelpers::getFile($data->property_id, PROPERTY_IMAGE) }}" target="_blank" class="btn download-btn light_grey_gradient_button" style="min-width: 40px"><i class="fa fa-download"></i></a>
                @endif
            </div>
            <div class="col-md-8" style="min-height: 370px;">
{{--                @if($assessment->classification == ASSESSMENT_WATER_TYPE)--}}
                    @include('forms.form_text',['title' => 'Property Status:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'property_status') ])
                    @include('forms.form_text',['title' => 'Property Occupied:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'property_occupied') ])
{{--                @endif--}}
                @include('forms.form_text',['title' => 'Property Access Type:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'programme_type') ])
                @if($assessment->classification == ASSESSMENT_FIRE_TYPE || $assessment->classification == ASSESSMENT_HS_TYPE)
{{--                    @include('forms.form_text',['title' => 'Property Type:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'property_type') ])--}}
                    @include('forms.form_text',['title' => 'Asset Class:', 'data'=> CommonHelpers::getPropertyInfoField($propertyInfo, 'asset_class')])
                    @include('forms.form_text',['title' => 'Asset Type:', 'data'=> CommonHelpers::getPropertyInfoField($propertyInfo, 'asset_type')])
                @endif
                @include('forms.form_text',['title' => 'Primary Use:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'asset_use_primary') ])
                @include('forms.form_text',['title' => 'Secondary Use:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'asset_use_secondary') ])
                @include('forms.form_text',['title' => 'Construction Age:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'construction_age') ])
                @if($assessment->classification == ASSESSMENT_WATER_TYPE)
                    @include('forms.form_text',['title' => 'Construction Materials:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'construction_materials') ])
                @elseif($assessment->classification == ASSESSMENT_FIRE_TYPE || $assessment->classification == ASSESSMENT_HS_TYPE)
                    @include('forms.form_text',['title' => 'Stair Construction:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'stairs') ])
                    @include('forms.form_text',['title' => 'Floor Construction:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'floors') ])
                    @include('forms.form_text',['title' => 'External Wall Construction:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'wall_construction') ])
                    @include('forms.form_text',['title' => 'External Wall Finish:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'wall_finish') ])
                @endif
                @include('forms.form_text',['title' => 'Listed Building:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'listed_building') ])
                @if($assessment->classification == ASSESSMENT_WATER_TYPE)
                    @include('forms.form_text',['title' => 'No. Floors:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'size_floors') ])
                @elseif($assessment->classification == ASSESSMENT_FIRE_TYPE || $assessment->classification == ASSESSMENT_HS_TYPE)
                    @include('forms.form_text',['title' => 'Floors Above Ground:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'floors_above') ])
                    @include('forms.form_text',['title' => 'Floors Below Ground:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'floors_below') ])
                @endif
                @include('forms.form_text',['title' => 'No. Staircases:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'size_staircases') ])
                @include('forms.form_text',['title' => 'No. Lifts:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'size_lifts') ])
                @if($assessment->classification == ASSESSMENT_WATER_TYPE)
                    @include('forms.form_text',['title' => 'Electrical Meter:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'electrical_meter') ])
                    @include('forms.form_text',['title' => 'Gas Meter:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'gas_meter') ])
                @endif
                @include('forms.form_text',['title' => 'Loft Void:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'loft_void') ])
                @include('forms.form_text',['title' => 'Net Area per Floor:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'size_net_area') ])
                @include('forms.form_text',['title' => 'Gross Area:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'size_gross_area') ])
                @include('forms.form_text',['title' => 'Parking Arrangements:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'parking_arrangements') ])
                @include('forms.form_text',['title' => 'Nearest Hospital:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'nearest_hospital') ])
                @include('forms.form_text',['title' => 'Restrictions & Limitations:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'restrictions_limitations') ])
                @include('forms.form_text',['title' => 'Unusual Features:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'unusual_features') ])
                @include('forms.form_text',['title' => 'Vulnerable Occupant Types:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'vulnerable_occupant_type') ])
                @include('forms.form_text',['title' => 'Average No. of Occupants:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'avg_vulnerable_occupants') ])
                @include('forms.form_text',['title' => 'Maximum No. of Occupants:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'max_vulnerable_occupants') ])
                @include('forms.form_text',['title' => 'No. of Occupants (Last Assessment):', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'last_vulnerable_occupants') ])
                @if($assessment->classification != ASSESSMENT_WATER_TYPE)
                    @include('forms.form_text',['title' => 'Evacuation Strategy:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'evacuation_strategy') ])
                @endif
                @if($assessment->classification == ASSESSMENT_FIRE_TYPE)
                    @include('forms.form_text',['title' => 'FRA Overall Risk Rating:', 'data' => CommonHelpers::getPropertyInfoField($propertyInfo, 'fra_overall') ])
                @endif
                @include('forms.form_text',['title' => 'Comments:', 'data' => $propertyInfo['size_comments'] ?? '' ])
            </div>
            @if($assessment->is_locked != 1)
                @if($canBeUpdateSurvey)
                <a href="{{ route('shineCompliance.assessment.get_edit_property_information', [ 'assess_id ' => $data->id]) }}" style="text-decoration: none">
                    <button class="btn light_grey_gradient_button fs-8pt mt-4">
                        <strong>{{ __('Edit') }}</strong>
                    </button>
                </a>
                @endif
            @endif
        </div>
    </div>
</div>

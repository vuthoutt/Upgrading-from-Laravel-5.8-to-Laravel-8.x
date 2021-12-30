@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_property_information_edit', 'color' => 'orange', 'data'=> $assessment])
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">Edit Property Information </h3>
        </div>
        <div class="main-content">
            <form method="POST" action="{{ route('shineCompliance.assessment.post_edit_property_information',['assess_id' => $assessment->id]) }}" enctype="multipart/form-data" class="form-shine">
                @csrf
                @include('forms.form_upload',['title' => 'Photo:', 'name' => 'property_assess_img', 'object_id' => $assessment->id, 'folder' => PROPERTY_ASSESS_IMAGE ])
                <a title="Download Asbestos Register Image" href="{{ CommonHelpers::getFile($assessment->id, PROPERTY_ASSESS_IMAGE) }}" target="_blank" class="btn download-btn" style="  margin-left: 350px;margin-bottom: 20px"><i class="fa fa-download"></i></a>
                {{--             @if(CommonHelpers::checkFile($data->client_id, PROPERTY_IMAGE))
                                @include('forms.form_display_upload',['title' => 'Photo:', 'object_id' => $data->id, 'folder' => PROPERTY_IMAGE ])
                                <a title="Download Asbestos Register Image" href="{{ CommonHelpers::getFile($data->id, PROPERTY_IMAGE) }}" target="_blank" class="btn download-btn"><i class="fa fa-download"></i></a>
                            @endif --}}
                <div class="">
{{--                    @if($assessment->classification == ASSESSMENT_WATER_TYPE)--}}
                        @include('shineCompliance.forms.form_dropdown',['title' => 'Property Status:', 'data' => $propertyStatus,
                        'name' => 'property_status', 'key'=>'id', 'value'=>'description', 'compare_value' => $propertyInfo['property_status'] ?? '' ])
                        @include('shineCompliance.forms.form_dropdown',['title' => 'Property Occupied:', 'data' => $propertyOccupied,
                                'name' => 'property_occupied', 'key'=>'id', 'value'=>'description', 'compare_value' => $propertyInfo['property_occupied'] ?? '' ])
{{--                    @endif--}}
                    @include('shineCompliance.forms.form_dropdown',['title' => 'Property Access Type:', 'data' => $programmeTypes, 'name' => 'programme_type', 'key'=> 'id', 'value'=>'description',
                            'compare_value' => $propertyInfo['programme_type'] ?? '',
                            'other' => 'programme_type_other', 'other_value' => $propertyInfo['programme_type_other'] ?? ''])
                    @if($assessment->classification == ASSESSMENT_FIRE_TYPE || $assessment->classification == ASSESSMENT_HS_TYPE)
                        @include('shineCompliance.forms.form_dropdown_asset_type',['title' =>'Asset Class:',
                        'data' => $asset_class, 'name' => 'asset_class','id' => 'asset_class_id','key'=>'id', 'value'=>'description','compare_value' => $propertyInfo['asset_class'] ?? '' ])
                        @include('shineCompliance.forms.form_dropdown_asset_type',['title' =>'Asset Type:',
                        'data' => $asset_type, 'name' => 'asset_type', 'id' => 'asset_type_id','key'=>'id', 'value'=>'description','compare_value' => $propertyInfo['asset_type'] ?? ''])
                    @endif
                    @include('shineCompliance.forms.form_dropdown',['title' => 'Primary Use:', 'data' => $primaryUses, 'name' => 'asset_use_primary', 'key'=> 'id', 'value'=>'description',
                            'compare_value' => $propertyInfo['asset_use_primary'] ?? '',
                            'other' => 'asset_use_primary_other' ,'other_value' => $propertyInfo['asset_use_primary_other'] ?? ''])
                    @include('shineCompliance.forms.form_dropdown',['title' => 'Secondary Use:', 'data' => $primaryUses, 'name' => 'asset_use_secondary', 'key'=> 'id', 'value'=>'description',
                            'compare_value' => $propertyInfo['asset_use_secondary'] ?? '', 'other' => 'asset_use_secondary_other',
                            'other_value' => $propertyInfo['asset_use_secondary_other'] ?? ''])
                    @include('shineCompliance.forms.form_text_area',['title' => 'Construction  Age:', 'data' => $propertyInfo['construction_age'] ?? '', 'name' => 'construction_age'])
{{--                    @include('shineCompliance.forms.form_text_area',['title' => 'Construction  Type:', 'data' => $propertyInfo['construction_type'], 'name' => 'construction_type'])--}}
                    @if($assessment->classification == ASSESSMENT_WATER_TYPE)
                        <div class="row register-form">
                            <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Construction Materials:</label>
                            <div class="col-md-5">
                                <div class="form-group ">
                                    @include('shineCompliance.forms.form_multi_select_contruction',[ 'name' => 'construction_materials', 'id' => 'construction_materials','data_multis' => $constructionMaterials,
                                            'selected' => $propertyInfo['construction_materials'] ?? '', 'data_other' => $propertyInfo['construction_material_other'] ?? ''])
                                </div>
                            </div>
                        </div>
                    @elseif($assessment->classification == ASSESSMENT_FIRE_TYPE || $assessment->classification == ASSESSMENT_HS_TYPE)

                        @include('shineCompliance.forms.form_dropdown',['title' => 'Stair Construction:', 'data' => $stairDropdown, 'name' => 'stairs', 'key'=> 'id', 'value'=>'description',
                            'compare_value' => $propertyInfo['stairs'] ?? '', 'other' => 'stairs_other',
                            'other_value' => $propertyInfo['stairs_other'] ?? ''])
                        @include('shineCompliance.forms.form_dropdown',['title' => 'Floor Construction:', 'data' => $floorDropdown, 'name' => 'floors', 'key'=> 'id', 'value'=>'description',
                            'compare_value' => $propertyInfo['floors'] ?? '', 'other' => 'floors_other',
                            'other_value' => $propertyInfo['floors_other'] ?? ''])
                        @include('shineCompliance.forms.form_dropdown',['title' => 'External Wall Construction:', 'data' => $wallConstructionDropdown, 'name' => 'wall_construction', 'key'=> 'id', 'value'=>'description',
                            'compare_value' => $propertyInfo['wall_construction'] ?? '', 'other' => 'wall_construction_other',
                            'other_value' => $propertyInfo['wall_construction_other'] ?? ''])
                        @include('shineCompliance.forms.form_dropdown',['title' => 'External Wall Finish:', 'data' => $wallFinishDropdown, 'name' => 'wall_finish', 'key'=> 'id', 'value'=>'description',
                            'compare_value' => $propertyInfo['wall_finish'] ?? '', 'other' => 'wall_finish_other',
                            'other_value' => $propertyInfo['wall_finish_other'] ?? ''])
                    @endif
                    @include('shineCompliance.forms.form_dropdown',['title' => 'Listed Building:', 'data' => $listedBuilding, 'name' => 'listed_building',
                             'key'=>'id', 'value'=>'description', 'other' => 'listed_building_other',
                             'compare_value' => $propertyInfo['listed_building'] ?? '',
                             'other_value' => $propertyInfo['listed_building_other'] ?? ''])

                    @if($assessment->classification == ASSESSMENT_WATER_TYPE)
                        @include('shineCompliance.forms.form_propertyInfo_range_dropdown',['title' => 'No. Floors:', 'compare_value' => $propertyInfo['size_floors'] ?? -1, 'name' => 'size_floors', 'other_name' => 'size_floors_other', 'other' => $propertyInfo['size_floors_other'] ?? '' ])
                    @elseif($assessment->classification == ASSESSMENT_FIRE_TYPE || $assessment->classification == ASSESSMENT_HS_TYPE)
                            @include('shineCompliance.forms.form_propertyInfo_range_dropdown',['title' => 'Floors Above Ground:', 'compare_value' => $propertyInfo['floors_above'] ?? -1, 'name' => 'floors_above', 'other_name' => 'floors_above_other', 'other' => $propertyInfo['floors_above_other'] ?? '' ,'required' =>true])
                            @include('shineCompliance.forms.form_propertyInfo_range_dropdown',['title' => 'Floors Below Ground:', 'compare_value' => $propertyInfo['floors_below'] ?? -1, 'name' => 'floors_below', 'other_name' => 'floors_below_other', 'other' => $propertyInfo['floors_below_other'] ?? '' ,'required' =>true])
                    @endif
                    @include('shineCompliance.forms.form_propertyInfo_range_dropdown',['title' => 'No. Staircases:', 'compare_value' => $propertyInfo['size_staircases'] ?? -1, 'name' => 'size_staircases', 'other_name' => 'size_staircases_other', 'other' => $propertyInfo['size_staircases_other'] ?? '','required' =>true])
                    @include('shineCompliance.forms.form_propertyInfo_range_dropdown',['title' => 'No. Lifts:', 'compare_value' => $propertyInfo['size_lifts'] ?? -1, 'name' => 'size_lifts', 'other_name' => 'size_lifts_other', 'other' => $propertyInfo['size_lifts_other'] ?? '','required' =>true])

                    @if(!empty($dataDropdowns))
                        @foreach($dataDropdowns as $dataDropdown)
                            @if($assessment->classification == ASSESSMENT_WATER_TYPE)
                                @include('shineCompliance.forms.form_dropdown',['title' => $dataDropdown['description'] . ':', 'data' => $dataDropdown['value'], 'name' => Str::snake($dataDropdown['name']), 'key'=> 'id', 'value'=>'description', 'compare_value' => $dataDropdown['selected'], 'type' => 'site_data'])
                            @elseif($assessment->classification == ASSESSMENT_FIRE_TYPE || $assessment->classification == ASSESSMENT_HS_TYPE)
                                @if($dataDropdown['name'] == 'loftVoid')
                                    @include('shineCompliance.forms.form_dropdown',['title' => $dataDropdown['description']. ':', 'data' => $dataDropdown['value'], 'name' => Str::snake($dataDropdown['name']), 'key'=> 'id', 'value'=>'description', 'compare_value' => $dataDropdown['selected'], 'type' => 'site_data','required' =>true])
                                @endif
                            @endif
                        @endforeach
                    @endif

                    @include('shineCompliance.forms.form_text_area',['title' => 'Net Area per Floor:', 'data' => $propertyInfo['size_net_area'] ?? '', 'name' => 'size_net_area'])
                    @include('shineCompliance.forms.form_text_area',['title' => 'Gross Area:', 'data' => $propertyInfo['size_gross_area'] ?? '', 'name' => 'size_gross_area'])
                    @include('shineCompliance.forms.form_dropdown',['title' => 'Parking Arrangements:', 'data' => $parkingArrangements, 'name' => 'parking_arrangements',
                             'key'=>'id', 'value'=>'description', 'other' => 'parking_arrangements_other',
                             'compare_value' => $propertyInfo['parking_arrangements'] ?? '',
                             'other_value' => $propertyInfo['parking_arrangements_other'] ?? ''])
                    @include('shineCompliance.forms.form_input',['title' => 'Nearest Hospital:', 'data' => $propertyInfo['nearest_hospital'] ?? '', 'name' => 'nearest_hospital'  ])
                    @include('shineCompliance.forms.form_input',['title' => 'Restrictions & Limitations:', 'data' => $propertyInfo['restrictions_limitations'] ?? '', 'name' => 'restrictions_limitations'  ])
                    @include('shineCompliance.forms.form_input',['title' => 'Unusual Features:', 'data' => $propertyInfo['unusual_features'] ?? '', 'name' => 'unusual_features'  ])
                    @include('shineCompliance.forms.form_text_area_hazard',['title' => 'Comments:', 'data' => null, 'name' => 'size_comments','required' =>true,
                    'data' => $propertyInfo['size_comments'] ?? '','css' => 'width: 554px;margin-left: 15px!important;height: 50px!important;' ])
                    @include('shineCompliance.forms.form_text_area',['title' => 'Vulnerable Occupant Type:', 'data' => (isset($propertyInfo['vulnerable_occupant_type']) && !empty($propertyInfo['vulnerable_occupant_type'])) ? $propertyInfo['vulnerable_occupant_type'] : ''
                    , 'name' => 'vulnerable_occupant_type'  ])
                    @include('shineCompliance.forms.form_input',['title' => 'Average No. of Occupants:', 'data' => $propertyInfo['avg_vulnerable_occupants'] ?? '', 'name' => 'avg_vulnerable_occupants'  ])
                    @include('shineCompliance.forms.form_input',['title' => 'Max No. of Occupants:', 'data' => $propertyInfo['max_vulnerable_occupants'] ?? '', 'name' => 'max_vulnerable_occupants'  ])
                    @include('shineCompliance.forms.form_input',['title' => 'No. of Occupants (Last Survey):', 'data' => $propertyInfo['last_vulnerable_occupants'] ?? '', 'name' => 'last_vulnerable_occupants'  ])
                    @if($assessment->classification != ASSESSMENT_WATER_TYPE)
                        @include('shineCompliance.forms.form_dropdown',['title' => 'Evacuation Strategy:', 'data' => $evacuationStrategyDropdown,
                            'name' => 'evacuation_strategy', 'key'=>'id', 'value'=>'description', 'compare_value' => $propertyInfo['evacuation_strategy'] ?? '' ])
                    @endif
                    @if($assessment->classification == ASSESSMENT_FIRE_TYPE)
                        @include('shineCompliance.forms.form_dropdown',['title' => 'FRA Overall Risk Rating:', 'data' => $FRAOverallDropdown,
                            'name' => 'fra_overall', 'key'=>'id', 'value'=>'description', 'compare_value' => $propertyInfo['fra_overall'] ?? '' ])
                    @endif

                </div>
                <div class="col-md-6 offset-md-3 mt-4">
                    <button type="submit" class="btn light_grey_gradient add-property-info">
                        <strong>{{ __('Save') }}</strong>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('javascript')
    <script type="text/javascript">

        $('body').on('change', '.form-require', function(){
            if(!$(this).val() || $(this).val() == ''){
                var label = $(this).closest('.parent-element').find('label').html();
                label = label.replace('*', '');//remove character :*
                label = label.replace(':', '');//remove character :*
                var warning_msg = 'The ' + label+ ' field is required!';
                console.log(warning_msg);
                showWarning(true, this, warning_msg);
            } else{
                showWarning(false, this, '');
            }
        });

        $('body').on('click', '.add-property-info', function(e){
            e.preventDefault();
            var is_valid = true;
            $('.form-require').each(function(k,v){
                if($(v).is(':visible') && ($(v).val() == '' || !$(v).val())){
                    is_valid = false;
                    var label = $(v).closest('.parent-element').find('label').html();
                    label = label.replace('*', '');//remove character :*
                    label = label.replace(':', '');//remove character :*
                    var warning_msg = 'The ' + label+ ' field is required!';
                    showWarning(true, v, warning_msg);
                }
            });
            if(!is_valid){
                //scroll to error
                $('html, body').animate({
                    scrollTop: $(this).closest('form').find('.is-invalid:visible:first').offset().top - 200
                }, 1000);
                $('#overlay').fadeOut();
                return;
            } else {
                $(this).closest('form').submit();
            }
        });

        //show warning
        function showWarning(is_show, that, message){
            if(is_show){
                $(that).addClass('is-invalid error-text');
                $(that).parent().find('span strong').html(message);
            } else {
                $(that).removeClass('is-invalid error-text');
                $(that).parent().find('span strong').html('');
            }
        }

        $(document).ready(function(){
            $('body').on('change','#asset_class_id',function(){
                var parent_id = $(this).val();
                $('#asset_type_id').find("option").not(':first').hide();
                //for edit case
                var old_select_parent = $('#asset_type_id').find("option:selected").data('parent');
                if(old_select_parent != parent_id){
                    $('#asset_type_id').find("option:first").prop('selected', true);
                }
                if(parent_id){
                    //show by parent id
                    $('#asset_type_id').find("option[data-parent="+parent_id+"]").show();
                    return;
                }
            });

            $('#asset_class_id').trigger('change');
        });

    </script>
@endpush

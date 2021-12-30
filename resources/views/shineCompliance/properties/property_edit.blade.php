@extends('shineCompliance.layouts.app')

@section('content')
    @if($parent_property)
        @include('shineCompliance.partials.nav',['breadCrumb' => 'sub_property_edit', 'color' => 'red', 'data' => $property ])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_edit', 'color' => 'red', 'data' => $property ])
    @endif
<div class="container-cus prism-content pad-up pl-0">
    <div class="row">
        <h3 class="col-12 title-row">Edit Property</h3>
    </div>
    <div class="main-content">
        <form id="form_edit_property" method="POST" action="{{ route('shineCompliance.property.post_edit', ['property_id' => $property->id]) }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            @include('shineCompliance.forms.form_input',['title' => 'Property Name:', 'name' => 'name', 'required' => true ,'data' => $property->name ?? ''])
            @include('shineCompliance.forms.form_input',['title' => 'Property Reference:', 'name' => 'property_reference','data' => $property->property_reference ?? ''])
            @include('shineCompliance.forms.form_input',['title' => 'Block Reference:', 'name' => 'pblock','data' => $property->pblock ?? ''])
{{--            @include('shineCompliance.forms.form_input',['title' => 'Core Reference:', 'name' => 'core_code','data' => $property->core_reference ?? '' ])--}}
{{--            @include('shineCompliance.forms.form_input',['title' => 'Cluster Reference:', 'name' => 'cluster_reference','data' => $property->cluster_reference ?? '' ])--}}
            @include('shineCompliance.forms.search_parent_property',['title' => 'Parent:', 'name' => 'parent_id', 'data' => $parent_property])
            @include('shineCompliance.forms.form_dropdown',['title' =>'Service Area:',
            'data' => $service_area , 'name' => 'service_area_id', 'key'=>'id', 'value'=>'description','compare_value' => $property->service_area_id])
            @include('shineCompliance.forms.form_input',['title' => 'Estate Code:', 'name' => 'estate_code','data' => $property->estate_code])
{{--            @include('shineCompliance.forms.form_dropdown',['title' =>'Region:','data' => $region , 'name' => 'region_id',--}}
{{--            'key'=>'id', 'value'=>'description','compare_value' => $property->region_id])--}}
{{--            @include('shineCompliance.forms.form_dropdown',['title' =>'Local Authority:','data' => $local_authority ,'name' =>'local_authority',--}}
{{--            'key'=>'id', 'value'=>'description','compare_value' => $property->local_authority])--}}
            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Property Risk Type:</label>
                <div class="col-md-5">
                    <div class="form-group ">
                        @include('forms.form_multi_select_contruction',[ 'name' => 'riskType', 'id' => 'riskType',
                        'data_multis' => $risk_types, 'selected' => $property->propertyType->pluck('id')->toArray() , 'data_other' => '' ])
                    </div>
                </div>
            </div>
            <!-- form_dropdown_asset_type -->
            @include('shineCompliance.forms.form_dropdown_asset_type',['title' =>'Asset Class:',
                        'data' => $asset_class, 'name' => 'asset_class_id', 'key'=>'id', 'value'=>'description', 'compare_value' => $property->asset_class_id])
            @include('shineCompliance.forms.form_dropdown_asset_type',['title' =>'Asset Type:',
            'data' => $asset_type, 'name' => 'asset_type_id', 'key'=>'id', 'value'=>'description', 'compare_value' => $property->asset_type_id])
            @include('shineCompliance.forms.form_dropdown',['title' =>'Property Access Type:',
                        'data' =>$programme_types, 'name' => 'programme_type', 'key'=>'id', 'value'=>'description',
                        'compare_value' => optional($property->propertySurvey)->programme_type, 'other_value' => optional($property->propertySurvey)->programme_type_other])
            @include('shineCompliance.forms.form_dropdown',['title' =>'Tenure Type:',
            'data' => $tenure_type, 'name' => 'tenure_type_id', 'key'=>'id', 'value'=>'description', 'compare_value' => $property->tenure_type_id])
            @include('shineCompliance.forms.form_dropdown',['title' =>'Communal Area:',
            'data' => $communal_area, 'name' => 'communal_area_id', 'key'=>'id', 'value'=>'description', 'compare_value' => $property->communal_area_id])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Responsibility:', 'data' => $responsibility,
            'name' => 'responsibility_id', 'key'=>'id', 'value'=>'description','compare_value' => $property->responsibility_id])
{{--            @include('shineCompliance.forms.form_dropdown',['title' => 'Building Category:', 'data' => $building_category,--}}
{{--            'name' => 'building_category', 'key'=>'id', 'value'=>'description', 'compare_value' => $property->building_category])--}}
{{--            @include('shineCompliance.forms.form_dropdown',['title' => 'Division:', 'data' => $division,'name' =>'division_id',--}}
{{--            'key'=>'id', 'value'=>'description', 'compare_value' => $property->division_id])--}}
            @include('shineCompliance.forms.form_dropdown',['title' => 'Property Client:',
                'data' => $clients_list, 'name' => 'client_id', 'key'=>'id', 'value'=>'client_description', 'required' => true,'compare_value' => $property->client_id ,'no_first_op' => true])
            @include('forms.form_select_ajax_zone',['title' => 'Property Group:', 'name' => 'zone_id', 'required' => true ])
            @include('shineCompliance.forms.form_input',['title' => 'Flat Number:', 'data' => null, 'name' => 'flat_number','data' => $property->propertyInfo->flat_number ?? ''])
            @include('shineCompliance.forms.form_input',['title' => 'Building Name:', 'data' => null, 'name' => 'building_name','data' => $property->propertyInfo->building_name ?? ''])
            @include('shineCompliance.forms.form_input',['title' => 'Street Number:', 'data' => null, 'name' => 'street_number','data' => $property->propertyInfo->street_number ?? '' ])
            @include('shineCompliance.forms.form_input',['title' => 'Street Name:', 'data' => null, 'name' => 'street_name','data' => $property->propertyInfo->street_name ?? ''])
            @include('shineCompliance.forms.form_input',['title' => 'Town:', 'data' => null, 'name' => 'town','data' => $property->propertyInfo->town ?? '' ])
            @include('shineCompliance.forms.form_input',['title' => 'County:', 'data' => null, 'name' => 'address5','data' => $property->propertyInfo->address5 ?? ''])
            @include('shineCompliance.forms.form_input',['title' => 'Postcode:', 'data' => null, 'name' => 'postcode','width' => 2,'data' => $property->propertyInfo->postcode ?? ''])
            @include('shineCompliance.forms.form_input',['title' => 'Telephone:', 'data' => null, 'name' => 'telephone','data' => $property->propertyInfo->telephone ?? ''])
            @include('shineCompliance.forms.form_input',['title' => 'Mobile:', 'data' => null, 'name' => 'mobile','data' => $property->propertyInfo->mobile ?? ''])
            @include('shineCompliance.forms.form_input',['title' => 'Email:', 'data' => null, 'name' => 'email','data' => $property->propertyInfo->email ?? ''])
            @if(count($list_users) > 0)
                @include('shineCompliance.forms.form_dropdown',['title' => 'App Contact:', 'data' => $list_users,
                'name' => 'app_contact', 'key'=>'id', 'value'=>'full_name', 'width' => 3,'compare_value' => optional($property->propertyInfo)->app_contact])
            @else
                @include('shineCompliance.forms.form_text',['title' => 'App Contact:',
                'data' => Lang::get('message.empty_data_property'), 'name' => '' ])
            @endif

            @include('shineCompliance.forms.form_upload',['title' => 'Photo:', 'name' => 'photo', 'folder' => PROPERTY_PHOTO, 'object_id' => $property->id ])
            @if(count($list_users) > 0)
                @include('shineCompliance.forms.form_multiple_option',[ 'name'=>'team','title' => 'Property Contact:',
                'dropdown_list'=> $list_users, 'value_get' => 'full_name', 'selected'=> optional($property->propertyInfo)->team ])
            @else
                @include('shineCompliance.forms.form_input',['title' => 'Property Contact:',
                'data' => Lang::get('message.empty_data_property'), 'name' => '' ])
            @endif
            @include('shineCompliance.forms.form_dropdown',['title' => 'Property Status:', 'data' => $propertyStatus,
                    'name' => 'property_status', 'key'=>'id', 'value'=>'description', 'compare_value' => optional($property->propertySurvey)->property_status ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Property Occupied:', 'data' => $propertyOccupied,
                    'name' => 'property_occupied', 'key'=>'id', 'value'=>'description', 'compare_value' => optional($property->propertySurvey)->property_occupied ])
            <!-- Primary Use -->
            @include('shineCompliance.forms.form_dropdown',['title' => 'Primary Use:', 'data' => $primaryUses,
                    'name' => 'asset_use_primary', 'key'=>'id', 'value'=>'description' ,'other' => 'primaryusemore',
                    'compare_value' => optional($property->propertySurvey)->asset_use_primary ,'other' => 'primaryusemore',
                    'other_value' => optional($property->propertySurvey)->asset_use_primary_other ])
            <!-- Secondary Use -->
            @include('shineCompliance.forms.form_dropdown',['title' => 'Secondary Use:', 'data' => $primaryUses,
                    'name' => 'asset_use_secondary', 'key'=>'id', 'value'=>'description' ,'other' => 'secondaryusemore',
                     'compare_value' => optional($property->propertySurvey)->asset_use_secondary ,'other' => 'secondaryusemore',
                     'other_value' => optional($property->propertySurvey)->asset_use_secondary_other])
            @include('shineCompliance.forms.form_input',['title' => 'Construction Age:', 'data' => $property->propertySurvey->construction_age ?? '', 'name' => 'construction_age'  ])
{{--            @include('shineCompliance.forms.form_input',['title' => 'Construction Type:', 'data' => $property->propertySurvey->construction_type ?? '', 'name' => 'construction_type'  ])--}}
{{--            <div class="row register-form">--}}
{{--                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Construction Materials:</label>--}}
{{--                <div class="col-md-5">--}}
{{--                    <div class="form-group ">--}}
{{--                        @include('shineCompliance.forms.form_multi_select_contruction',[ 'name' => 'construction_materials', 'id' => 'construction_materials','data_multis' => $constructionMaterials,--}}
{{--                                'selected' => $property->constructionMaterials->pluck('id'), 'data_other' => \CommonHelpers::getPropertyOtherMaterial($property)])--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            @include('shineCompliance.forms.form_dropdown',['title' => 'Stair Construction:', 'data' => $stairDropdown, 'name' => 'stairs', 'key'=> 'id', 'value'=>'description',
                            'compare_value' => optional($property->propertySurvey)->stairs ?? '', 'other' => 'stairs_other',
                            'other_value' => optional($property->propertySurvey)->stairs_other ?? ''])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Floor Construction:', 'data' => $floorDropdown, 'name' => 'floors', 'key'=> 'id', 'value'=>'description',
                'compare_value' => optional($property->propertySurvey)->floors ?? '', 'other' => 'floors_other',
                'other_value' => optional($property->propertySurvey)->floors_other ?? ''])
            @include('shineCompliance.forms.form_dropdown',['title' => 'External Wall Construction:', 'data' => $wallConstructionDropdown, 'name' => 'wall_construction', 'key'=> 'id', 'value'=>'description',
                'compare_value' => optional($property->propertySurvey)->wall_construction ?? '', 'other' => 'wall_construction_other',
                'other_value' => optional($property->propertySurvey)->wall_construction_other ?? ''])
            @include('shineCompliance.forms.form_dropdown',['title' => 'External Wall Finish:', 'data' => $wallFinishDropdown, 'name' => 'wall_finish', 'key'=> 'id', 'value'=>'description',
                'compare_value' =>optional($property->propertySurvey)->wall_finish ?? '', 'other' => 'wall_finish_other',
                'other_value' => optional($property->propertySurvey)->wall_finish_other ?? ''])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Listed Building:', 'data' => $listedBuilding, 'name' => 'listed_building',
                     'key'=>'id', 'value'=>'description', 'other' => 'listed_building_other',
                     'compare_value' => optional($property->propertySurvey)->listed_building,
                     'other_value' => optional($property->propertySurvey)->listed_building_other])
            @include('shineCompliance.forms.form_propertyInfo_plus',['title' => 'No. Floors:', 'name' => 'size_floors',
                    'compare_value' => optional($property->propertySurvey)->size_floors ?? -1, 'name' => 'size_floors', 'other_name' => 'size_floors_other',
                    'other' => optional($property->propertySurvey)->size_floors_other ])
            @include('shineCompliance.forms.form_propertyInfo_range_dropdown',['title' => 'Floors Above Ground:', 'compare_value' => optional($property->propertySurvey)->floors_above ?? -1, 'name' => 'floors_above',
                    'other_name' => 'floors_above_other', 'other' => optional($property->propertySurvey)->floors_above_other ?? '' ])
            @include('shineCompliance.forms.form_propertyInfo_range_dropdown',['title' => 'Floors Below Ground:', 'compare_value' => optional($property->propertySurvey)->floors_below ?? -1, 'name' => 'floors_below',
                    'other_name' => 'floors_below_other', 'other' => optional($property->propertySurvey)->floors_below_other ?? '' ])
            @include('shineCompliance.forms.form_propertyInfo_plus',['title' => 'No. Staircases:', 'name' => 'size_staircases', 'other_name' => 'size_staircases_other',
                    'compare_value' => optional($property->propertySurvey)->size_staircases ?? -1,
                    'other' => optional($property->propertySurvey)->size_staircases_other])
            @include('shineCompliance.forms.form_propertyInfo_plus',['title' => 'No. Lifts:', 'name' => 'size_lifts', 'other_name' => 'size_lifts_other',
                    'compare_value' => optional($property->propertySurvey)->size_lifts ?? -1,
                    'other' => optional($property->propertySurvey)->size_lifts_other])
            @if(!empty($dataDropdowns))
                @foreach($dataDropdowns as $dataDropdown)
                    @if($dataDropdown['name'] == 'loftVoid')
                        @include('shineCompliance.forms.form_dropdown',['title' => $dataDropdown['description']. ':',
                        'data' => $dataDropdown['value'], 'name' => $dataDropdown['name'], 'key'=> 'id', 'value'=>'description',
                         'compare_value' => $dataDropdown['selected'], 'type' => 'site_data'])
                    @endif
                @endforeach
            @endif

            @include('shineCompliance.forms.form_text_area',['title' => 'No. of Bedrooms:', 'data' => null, 'name' => 'size_bedrooms',
            'data' => optional($property->propertySurvey)->size_bedrooms])
            @include('shineCompliance.forms.form_text_area',['title' => 'Net Area per Floor:', 'data' => null, 'name' => 'size_net_area',
            'data' => optional($property->propertySurvey)->size_net_area])
            @include('shineCompliance.forms.form_text_area',['title' => 'Gross Area:', 'data' => null, 'name' => 'size_gross_area',
            'data' => optional($property->propertySurvey)->size_gross_area])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Parking Arrangements:', 'data' => $parkingArrangements, 'name' => 'parking_arrangements',
                     'key'=>'id', 'value'=>'description', 'other' => 'parking_arrangements_other',
                     'compare_value' => optional($property->propertySurvey)->parking_arrangements,
                     'other_value' => optional($property->propertySurvey)->parking_arrangements_other])
            @include('shineCompliance.forms.form_input',['title' => 'Nearest Hospital:', 'data' => optional($property->propertySurvey)->nearest_hospital, 'name' => 'nearest_hospital'  ])
            @include('shineCompliance.forms.form_input',['title' => 'Restrictions & Limitations:', 'data' => optional($property->propertySurvey)->restrictions_limitations, 'name' => 'restrictions_limitations'  ])
            @include('shineCompliance.forms.form_input',['title' => 'Unusual Features:', 'data' => optional($property->propertySurvey)->unusual_features, 'name' => 'unusual_features'  ])
            @include('shineCompliance.forms.form_text_area',['title' => 'Comments:', 'data' => null, 'name' => 'size_comments',
            'data' => optional($property->propertySurvey)->size_comments])
            {{-- @include('shineCompliance.forms.form_text_area',['title' => 'Vulnerable Occupant Type:', 'data' => optional($property->vulnerableOccupant)->vulnerable_occupant_type, 'name' => 'vulnerable_occupant_type'  ]) --}}
            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Vulnerable Occupant Type:</label>
                <div class="col-md-5">
                    <div class="form-group ">
                        @include('forms.form_multi_select_contruction',[ 'name' => 'vulnerable_occupant_types', 'id' => 'vulnerable_occupant_types',
                        'data_multis' => $vulnerableTypes, 'selected' => optional(optional(optional($property->vulnerableOccupant)->vulnerableOccupantTypes)->pluck('id'))->toArray() ?? '' , 'data_other' => '' ])
                    </div>
                </div>
            </div>
            @include('shineCompliance.forms.form_input',['title' => 'Average No. of Occupants:', 'data' => optional($property->vulnerableOccupant)->avg_vulnerable_occupants, 'name' => 'avg_vulnerable_occupants'  ])
            @include('shineCompliance.forms.form_input',['title' => 'Max No. of Occupants:', 'data' => optional($property->vulnerableOccupant)->max_vulnerable_occupants, 'name' => 'max_vulnerable_occupants'  ])
            @include('shineCompliance.forms.form_input',['title' => 'No. of Occupants (Last Survey):', 'data' => optional($property->vulnerableOccupant)->last_vulnerable_occupants, 'name' => 'last_vulnerable_occupants'  ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Evacuation Strategy:', 'data' => $evacuationStrategyDropdown,
                'name' => 'evacuation_strategy', 'key'=>'id', 'value'=>'description', 'compare_value' => optional($property->propertySurvey)->evacuation_strategy ?? '' ])
            <a href="{{url()->previous()}}">
                <div class="col-md-6 offset-md-3">
                    <button type="submit" id="submit" class="btn light_grey_gradient_button fs-8pt">
                        Save
                    </button>
                </div>
            </a>
        </form>
    </div>
</div>
@endsection
@push('javascript')
    <script type="text/javascript" src="{{ URL::asset('js/multiple_option.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/property.js') }}"></script>
    <script type="text/javascript">
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

        $('body').on('change','#client_id',function(){
            $('#zone_id_parent').find('option').remove();
            $("#zone_id_parent").html("");
            var client_id = $("#client_id").val();
            $.ajax({
                type: "GET",
                url: "{{ route('ajax.property_group') }}",
                data: {
                    client_id : client_id,
                    parent_id : 0
                },
                cache: false,
                success: function (html) {
                    $.each( html, function( key, value ) {
                        var selected = {{ $zone_parent_id ?? ''}};
                        if (selected == value.id) {
                            selected = true;
                        } else {
                            selected = false;
                        }
                        $('#zone_id_parent').append($('<option>', {
                            value: value.id,
                            text : value.zone_name,
                            selected : selected
                        }));
                        ajaxChildrenZone();
                    });
                }
            });
        });

        $('body').on('change','#zone_id_parent',function(){
            ajaxChildrenZone();
        });


        function ajaxChildrenZone(){
            $('#zone_id').find('option').remove();
            $("#zone_id").html("");
            $.ajax({
                type: "GET",
                url: "{{ route('ajax.property_group') }}",
                data: {
                    client_id : $("#client_id").val(),
                    parent_id : $("#zone_id_parent").val()
                },
                cache: false,
                async: false,
                success: function (html) {
                    $.each( html, function( key, value ) {
                        var selected = false;
                        if ({{ $zone_id ?? ''}} == value.id) {
                            selected = true;
                        }
                        $('#zone_id').append($('<option>', {
                            value: value.id,
                            text : value.zone_name,
                            selected : selected
                        }));
                    });
                }
            });
        }

        $("#client_id").trigger('change');
        });

        $('body').on('change','#riskType',function(){
            var riskType = $(this).val();
            // console.log(riskType)
            if(riskType.includes("{{DOMESTIC_PROPERTY}}")){
                $('#size_bedrooms-form').show();
            }else{
                $('#size_bedrooms-form').hide();
            }
        });
        $('#riskType').trigger('change');
    </script>
@endpush

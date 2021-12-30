@extends('shineCompliance.layouts.app')

@section('content')
    @if($parent)
        @include('shineCompliance.partials.nav',['breadCrumb' => 'sub_property_add', 'color' => 'red', 'data'=> $parent])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_add', 'color' => 'red', 'data'=> $breadcrumb_data])
    @endif
<div class="container-cus prism-content pad-up pl-0">
    <div class="row">
        @if($parent)
            <h3 class="col-12 title-row" style="margin: 0">Add Sub Property</h3>
        @else
            <h3 class="col-12 title-row" style="margin: 0">Add Property</h3>
        @endif
    </div>
    <div class="main-content">
        <form id="form_edit_property" method="POST" action="{{ route('shineCompliance.property.post_add') }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            @include('shineCompliance.forms.form_input',['title' => 'Property Name:', 'name' => 'name', 'required' => true ])
            @include('shineCompliance.forms.form_input',['title' => 'Property Reference:', 'name' => 'property_reference' ])
            @include('shineCompliance.forms.form_input',['title' => 'Block Reference:', 'name' => 'pblock' ])
            @include('shineCompliance.forms.form_input',['title' => 'Core Reference:', 'name' => 'core_code' ])
            @if($parent)
                @include('shineCompliance.forms.search_parent_property',['title' => 'Parent:', 'name' => 'parent_id', 'data' => $parent, 'is_disable' => true])
            @else
                @include('shineCompliance.forms.search_parent_property',['title' => 'Parent:', 'name' => 'parent_id'])
            @endif
            @include('shineCompliance.forms.form_dropdown',['title' =>'Service Area:',
            'data' => $service_area , 'name' => 'service_area_id', 'key'=>'id', 'value'=>'description'])
            @include('shineCompliance.forms.form_input',['title' => 'Estate Code:', 'name' => 'estate_code'])
            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold  fs-8pt" >Property Risk Type:</label>
                <div class="col-md-5">
                    <div class="form-group ">
                        @include('shineCompliance.forms.form_multi_select_contruction',[ 'name' => 'riskType', 'id' => 'riskType',
                        'data_multis' => $risk_types, 'selected' => '', 'data_other' => '' ])
                    </div>
                </div>
            </div>
            <!-- form_dropdown_asset_type -->
            @include('shineCompliance.forms.form_dropdown_asset_type',['title' =>'Asset Class:',
                        'data' => $asset_class, 'name' => 'asset_class_id', 'key'=>'id', 'value'=>'description'])
            @include('shineCompliance.forms.form_dropdown_asset_type',['title' =>'Asset Type:',
            'data' => $asset_type, 'name' => 'asset_type_id', 'key'=>'id', 'value'=>'description'])
            @include('shineCompliance.forms.form_dropdown',['title' =>'Property Access Type:',
                        'data' =>$programme_types, 'name' => 'programme_type', 'key'=>'id', 'value'=>'description'])
            @include('shineCompliance.forms.form_dropdown',['title' =>'Tenure Type:',
            'data' => $tenure_type, 'name' => 'tenure_type_id', 'key'=>'id', 'value'=>'description'])
            @include('shineCompliance.forms.form_dropdown',['title' =>'Communal Area:',
            'data' => $communal_area, 'name' => 'communal_area_id', 'key'=>'id', 'value'=>'description'])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Responsibility:', 'data' => $responsibility,
            'name' => 'responsibility_id', 'key'=>'id', 'value'=>'description'])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Property Client:',
                'data' => $clients_list, 'name' => 'client_id', 'key'=>'id', 'value'=>'client_description', 'required' => true ,'no_first_op' => true])
            @include('shineCompliance.forms.form_select_ajax_zone',['title' => 'Property Group:', 'name' => 'zone_id', 'required' => true ])
            @include('shineCompliance.forms.form_input',['title' => 'Flat Number:', 'data' => null, 'name' => 'flat_number'  ])
            @include('shineCompliance.forms.form_input',['title' => 'Building Name:', 'data' => null, 'name' => 'building_name'  ])
            @include('shineCompliance.forms.form_input',['title' => 'Street Number:', 'data' => null, 'name' => 'street_number'  ])
            @include('shineCompliance.forms.form_input',['title' => 'Street Name:', 'data' => null, 'name' => 'street_name'  ])
            @include('shineCompliance.forms.form_input',['title' => 'Town:', 'data' => null, 'name' => 'town'  ])
            @include('shineCompliance.forms.form_input',['title' => 'County:', 'data' => null, 'name' => 'address5'  ])
            @include('shineCompliance.forms.form_input',['title' => 'Postcode:', 'data' => null, 'name' => 'postcode', 'width' => 2 ])
            @include('shineCompliance.forms.form_input',['title' => 'Telephone:', 'data' => null, 'name' => 'telephone'  ])
            @include('shineCompliance.forms.form_input',['title'  => 'Mobile:', 'data' => null, 'name' => 'mobile' ])
            @include('shineCompliance.forms.form_input',['title' => 'Email:', 'data' => null, 'name' => 'email'  ])
            @if(count($list_users) > 0)
                @include('shineCompliance.forms.form_dropdown',['title' => 'App Contact:', 'data' => $list_users,
                'name' => 'app_contact', 'key'=>'id', 'value'=>'full_name', 'width' => 3 ])
            @else
                @include('shineCompliance.forms.form_text',['title' => 'App Contact:',
                'data' => Lang::get('message.empty_data_property'), 'name' => '' ])
            @endif

            @include('shineCompliance.forms.form_upload',['title' => 'Photo:', 'name' => 'photo', 'folder' => PROPERTY_PHOTO ])
            @if(count($list_users) > 0)
                @include('shineCompliance.forms.form_multiple_option',[ 'name'=>'team','title' => 'Property Contact:',
                'dropdown_list'=> $list_users, 'value_get' => 'full_name', 'selected' => [] ])
            @else
                @include('shineCompliance.forms.form_input',['title' => 'Property Contact:',
                'data' => Lang::get('message.empty_data_property'), 'name' => '' ])
            @endif

            @include('shineCompliance.forms.form_dropdown',['title' => 'Property Status:', 'data' => $propertyStatus,
                    'name' => 'property_status', 'key'=>'id', 'value'=>'description' ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Property Occupied:', 'data' => $propertyOccupied,
                    'name' => 'property_occupied', 'key'=>'id', 'value'=>'description' ])

        <!-- Primary Use -->
            @include('shineCompliance.forms.form_dropdown',['title' => 'Primary Use:', 'data' => $primaryUses,
                    'name' => 'asset_use_primary', 'key'=>'id', 'value'=>'description' ,'other' => 'primaryusemore',
                    'other_value' => '' ])
        <!-- Secondary Use -->
            @include('shineCompliance.forms.form_dropdown',['title' => 'Secondary Use:', 'data' => $primaryUses,
                    'name' => 'asset_use_secondary', 'key'=>'id', 'value'=>'description' ,'other' => 'secondaryusemore',
                     'other_value' => ''])
            @include('shineCompliance.forms.form_input',['title' => 'Construction Age:', 'data' => null, 'name' => 'construction_age'  ])


            @include('shineCompliance.forms.form_dropdown',['title' => 'Stair Construction:', 'data' => $stairDropdown, 'name' => 'stairs', 'key'=> 'id', 'value'=>'description',
                            'compare_value' => '', 'other' => 'stairs_other',
                            'other_value' => ''])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Floor Construction:', 'data' => $floorDropdown, 'name' => 'floors', 'key'=> 'id', 'value'=>'description',
                'compare_value' => '', 'other' => 'floors_other',
                'other_value' => ''])
            @include('shineCompliance.forms.form_dropdown',['title' => 'External Wall Construction:', 'data' => $wallConstructionDropdown, 'name' => 'wall_construction', 'key'=> 'id', 'value'=>'description',
                'compare_value' => '', 'other' => 'wall_construction_other',
                'other_value' => ''])
            @include('shineCompliance.forms.form_dropdown',['title' => 'External Wall Finish:', 'data' => $wallFinishDropdown, 'name' => 'wall_finish', 'key'=> 'id', 'value'=>'description',
                'compare_value' => '', 'other' => 'wall_finish_other',
                'other_value' => ''])

            @include('shineCompliance.forms.form_dropdown',['title' => 'Listed Building:', 'data' => $listedBuilding, 'name' => 'listed_building',
                     'key'=>'id', 'value'=>'description', 'other' => 'listed_building_other', 'other_value' => '' ])
            @include('shineCompliance.forms.form_propertyInfo_plus',['title' => 'No. Floors:', 'name' => 'size_floors',
                    'compare_value' => -1, 'name' => 'size_floors', 'other_name' => 'size_floors_other',
                    'other' => '' ])
            @include('shineCompliance.forms.form_propertyInfo_range_dropdown',['title' => 'Floors Above Ground:', 'compare_value' => -1, 'name' => 'floors_above', 'other_name' => 'floors_above_other', 'other' => '' ])
            @include('shineCompliance.forms.form_propertyInfo_range_dropdown',['title' => 'Floors Below Ground:', 'compare_value' => -1, 'name' => 'floors_below', 'other_name' => 'floors_below_other', 'other' => '' ])
            @include('shineCompliance.forms.form_propertyInfo_plus',['title' => 'No. Staircases:', 'name' => 'size_staircases', 'other_name' => 'size_staircases_other',
                    'compare_value' => -1,
                    'other' => ''])
            @include('shineCompliance.forms.form_propertyInfo_plus',['title' => 'No. Lifts:', 'name' => 'size_lifts', 'other_name' => 'size_lifts_other',
                    'compare_value' => -1,
                    'other' => ''])
            @if(!empty($dataDropdowns))
                @foreach($dataDropdowns as $dataDropdown)
                    @if($dataDropdown['name'] == 'loftVoid')
                        @include('shineCompliance.forms.form_dropdown',['title' => $dataDropdown['description']. ':',
                        'data' => $dataDropdown['value'], 'name' => $dataDropdown['name'], 'key'=> 'id', 'value'=>'description',
                         'compare_value' => $dataDropdown['selected'], 'type' => 'site_data'])
                    @endif
                @endforeach
            @endif

            @include('shineCompliance.forms.form_text_area',['title' => 'No. of Bedrooms:', 'data' => null, 'name' => 'size_bedrooms' ])
            @include('shineCompliance.forms.form_text_area',['title' => 'Net Area per Floor:', 'data' => null, 'name' => 'size_net_area' ])
            @include('shineCompliance.forms.form_text_area',['title' => 'Gross Area:', 'data' => null, 'name' => 'size_gross_area' ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Parking Arrangements:', 'data' => $parkingArrangements, 'name' => 'parking_arrangements',
                     'key'=>'id', 'value'=>'description', 'other' => 'parking_arrangements_other', 'other_value' => '' ])
            @include('shineCompliance.forms.form_input',['title' => 'Nearest Hospital:', 'data' => null, 'name' => 'nearest_hospital'  ])
            @include('shineCompliance.forms.form_input',['title' => 'Restrictions & Limitations:', 'data' => null, 'name' => 'restrictions_limitations'  ])
            @include('shineCompliance.forms.form_input',['title' => 'Unusual Features:', 'data' => null, 'name' => 'unusual_features'  ])
            @include('shineCompliance.forms.form_text_area',['title' => 'Comments:', 'data' => null, 'name' => 'size_comments' ])
            {{-- @include('shineCompliance.forms.form_text_area',['title' => 'Vulnerable Occupant Type:','name' => 'vulnerable_occupant_type', 'id' => 'vulnerable_occupant_type',
            'data' => 'No vulnerable residents were identified at the time of this assessment. The latest Government guidance states that it proposes to,
            “Require the responsible person to prepare a personal emergency evacuation plan (PEEP) for every resident in a high-rise residential building who self-identifies to them
            as unable to self-evacuate (subject to the resident’s voluntary self-identification) and to do so in consultation with them.”
            Currently, the responsibility for gathering this information sits with the WCC Housing Team.  See Section K.17.', 'selected' => '' , 'data_other' => '' ]) --}}
            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Vulnerable Occupant Type:</label>
                <div class="col-md-5">
                    <div class="form-group ">
                        @include('forms.form_multi_select_contruction',[ 'name' => 'vulnerable_occupant_types', 'id' => 'vulnerable_occupant_types',
                        'data_multis' => $vulnerableTypes, 'selected' => '' , 'data_other' => '' ])
                    </div>
                </div>
            </div>
            @include('shineCompliance.forms.form_input',['title' => ' Average No. of Occupants:', 'data' => null, 'name' => 'avg_vulnerable_occupants'  ])
            @include('shineCompliance.forms.form_input',['title' => 'Max No. of Occupants:', 'data' => null, 'name' => 'max_vulnerable_occupants'  ])
            @include('shineCompliance.forms.form_input',['title' => 'No. of Occupants (Last Survey):', 'data' => null, 'name' => 'last_vulnerable_occupants'  ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Evacuation Strategy:', 'data' => $evacuationStrategyDropdown,
                'name' => 'evacuation_strategy', 'key'=>'id', 'value'=>'description', 'compare_value' => $propertyInfo['evacuation_strategy'] ?? '' ])
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
            $('#asset_type_id').find("option:first").prop('selected', true);
            if(parent_id){
                //show by parent id
                $('#asset_type_id').find("option[data-parent="+parent_id+"]").show();
                return;
            }
        });

        $('#asset_class_id').trigger('change');

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
                async: false,
                cache: false,
                success: function (html) {
                    $.each( html, function( key, value ) {
                        selected = false;
                        if ({{ $zone_parent_id ?? ''}} == value.id) {
                            selected = true;
                        }
                        $('#zone_id_parent').append($('<option>', {
                            value: value.id,
                            text : value.zone_name,
                            selected : selected
                        }));
                    });
                    ajaxChildrenZone();
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
    </script>
@endpush

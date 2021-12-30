
@extends('layouts.app')

@section('content')

@include('partials.nav', ['breadCrumb' => 'edit_property','data' => $property])
<div class="container prism-content">
    <h3>Edit Property</h3>
    <div class="main-content">
        <form id="form_edit_property" method="POST" action="{{ route('property.post_edit', ['property_id' => $property->id]) }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            @include('forms.form_input',['title' => 'Property Reference:','data' => $property->property_reference , 'name' => 'property_reference' ])
            @include('forms.form_input',['title' => 'Block Reference:','data' => $property->pblock , 'name' => 'pblock'])
            @include('forms.form_input',['title' => 'Core Reference:','data' => $property->core_code , 'name' => 'core_code' ])
            @include('forms.search_parent_property',['title' => 'Parent Reference:', 'data' => $parent_property, 'name' => 'parent_id'])
            @include('forms.form_dropdown',['title' => (env('APP_DOMAIN') == 'LBHC') ? 'Housing Area:' : 'Service Area:', 'data' => $service_area, 'name' => 'service_area_id', 'key'=>'id', 'value'=>'description','compare_value' => $property->service_area_id ])
            @if(env('APP_DOMAIN') == 'LBHC')
                @include('forms.form_dropdown',['title' => 'Ward:', 'data' => $ward, 'name' => 'ward_id', 'key'=>'id', 'value'=>'description','compare_value' => $property->ward_id ])
            @endif
            @include('forms.form_input',['title' => 'Estate Code:','data' => $property->estate_code , 'name' => 'estate_code' ])
            @include('forms.form_input',['title' => 'Property Name:','data' => $property->name , 'name' => 'name', 'required' => true ])
            @include('forms.form_text',['title' => 'Shine Reference:', 'data' => $property->reference])
            @include('forms.form_select_ajax_zone',['title' => 'Property Group:', 'name' => 'zone_id', 'required' => true ])

            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Property Risk Type:</label>
                <div class="col-md-5">
                    <div class="form-group ">
                        @include('forms.form_multi_select_contruction',[ 'name' => 'riskType', 'id' => 'riskType', 'data_multis' => $risk_types, 'selected' => $property->propertyType->pluck('id')->toArray() , 'data_other' => '' ])
                    </div>
                </div>
            </div>

            @include('forms.form_dropdown_asset_type',['title' => 'Asset Class:', 'data' => $asset_class, 'name' => 'asset_class_id', 'key'=>'id', 'value'=>'description', 'compare_value' => $property->asset_class_id])
            @include('forms.form_dropdown_asset_type',['title' => 'Asset Type:', 'data' => $asset_type, 'name' => 'asset_type_id', 'key'=>'id', 'value'=>'description', 'compare_value' => $property->asset_type_id])
            @include('forms.form_dropdown',['title' => 'Tenure Type:', 'data' => $tenure_type, 'name' => 'tenure_type_id', 'key'=>'id', 'value'=>'description', 'compare_value' => $property->tenure_type_id])
            @include('forms.form_dropdown',['title' => 'Property Access Type:', 'data' => $programme_types, 'name' => 'programme_type', 'key'=>'id', 'value'=>'description','other' => 'programme_type_other', 'compare_value' => optional($property->propertySurvey)->programme_type, 'other_value' => optional($property->propertySurvey)->programme_type_other])
            @include('forms.form_dropdown',['title' => 'Communal Area:', 'data' => $communal_area, 'name' => 'communal_area_id', 'key'=>'id', 'value'=>'description', 'compare_value' => $property->communal_area_id])
            @include('forms.form_dropdown',['title' => 'Responsibility:', 'data' => $responsibility, 'name' => 'responsibility_id', 'key'=>'id', 'value'=>'description', 'compare_value' => $property->responsibility_id])

            {{--@include('forms.form_dropdown',['title' => 'Property Risk Type:', 'data' => $risk_types, 'name' => 'riskType', 'key'=>'id', 'value'=>'description', 'compare_value' => $property->risk_type])--}}

            @if(\CommonHelpers::isSystemClient())
                @include('forms.form_dropdown',['title' => 'Property Client:', 'data' => $clients_list, 'name' => 'client_id', 'key'=>'id', 'value'=>'client_description','compare_value' => $property->client_id, 'required' => true ])
            @else
                @include('forms.form_text',['title' => 'Shine Reference:', 'data' => $client->name ])
                @include('forms.form_input_hidden',['name' => 'client_id', 'data' => $client->id ])
            @endif


            @include('forms.form_input',['title' => 'Flat Number:', 'data' => $property->propertyInfo->flat_number ?? '', 'name' => 'flat_number'  ])
            @include('forms.form_input',['title' => 'Building Name:', 'data' => $property->propertyInfo->building_name ?? '', 'name' => 'building_name'  ])
            @include('forms.form_input',['title' => 'Street Number:', 'data' => $property->propertyInfo->street_number ?? '', 'name' => 'street_number'  ])
            @include('forms.form_input',['title' => 'Street Name:', 'data' => $property->propertyInfo->street_name ?? '', 'name' => 'street_name'  ])
            @include('forms.form_input',['title' => 'Town:', 'data' => optional($property->propertyInfo)->town, 'name' => 'town'  ])
            {{-- @include('forms.form_input',['title' => 'City:', 'data' => optional($property->propertyInfo)->address4, 'name' => 'address4'  ]) --}}
            @include('forms.form_input',['title' => 'County:', 'data' => optional($property->propertyInfo)->address5, 'name' => 'address5'  ])
            @include('forms.form_input',['title' => 'Postcode:', 'data' => optional($property->propertyInfo)->postcode, 'name' => 'postcode', 'width' => 2 ])
            @include('forms.form_input',['title' => 'Telephone:', 'data' => optional($property->propertyInfo)->telephone, 'name' => 'telephone'  ])
            @include('forms.form_input',['title' => 'Mobile:', 'data' => optional($property->propertyInfo)->mobile, 'name' => 'mobile'  ])
            @include('forms.form_input',['title' => 'Email:', 'data' => optional($property->propertyInfo)->email, 'name' => 'email'  ])

            @if(count($list_users) > 0)
            @include('forms.form_dropdown',['title' => 'App Contact:', 'data' => $list_users, 'name' => 'app_contact', 'key'=>'id','compare_value' => optional($property->propertyInfo)->app_contact ,'value'=>'full_name', 'width' => 3 ])
            @else
            @include('forms.form_text',['title' => 'App Contact:', 'data' => Lang::get('message.empty_data_property'), 'name' => '' ])
            @endif

            @include('forms.form_upload',['title' => 'Photo:', 'name' => 'photo', 'object_id' => $property->id , 'folder' => PROPERTY_PHOTO ])

            @if(count($list_users))
                @include('forms.form_multiple_option',[ 'name'=>'team','title' => 'Property Contact:','dropdown_list'=> $list_users, 'selected'=> optional($property->propertyInfo)->team, 'value_get' => 'full_name' ])
            @else
                @include('forms.form_input',['title' => 'Property Contact:', 'data' => Lang::get('message.empty_data_property'), 'name' => '' ])
            @endif
            <!-- Primary Use -->
            @include('forms.form_dropdown',['title' => 'Primary Use:', 'data' => $primaryUses, 'name' => 'asset_use_primary', 'key'=>'id', 'value'=>'description','compare_value' => optional($property->propertySurvey)->asset_use_primary ,'other' => 'primaryusemore', 'other_value' => optional($property->propertySurvey)->asset_use_primary_other ])
            <!-- Secondary Use -->
            @include('forms.form_dropdown',['title' => 'Secondary Use:', 'data' => $primaryUses, 'name' => 'asset_use_secondary', 'key'=>'id', 'value'=>'description','compare_value' => optional($property->propertySurvey)->asset_use_secondary ,'other' => 'secondaryusemore', 'other_value' => optional($property->propertySurvey)->asset_use_secondary_other])


            @include('forms.form_input',['title' => 'Construction Age:', 'data' => optional($property->propertySurvey)->construction_age, 'name' => 'construction_age'  ])
            @include('forms.form_input',['title' => 'Construction Type:', 'data' => optional($property->propertySurvey)->construction_type, 'name' => 'construction_type'  ])
            @include('forms.form_propertyInfo_plus',['title' => 'No. Floors:', 'compare_value' => optional($property->propertySurvey)->size_floors, 'name' => 'size_floors', 'other' => optional($property->propertySurvey)->size_floors_other ])
            @include('forms.form_propertyInfo_plus',['title' => 'No. Staircases:', 'compare_value' => optional($property->propertySurvey)->size_staircases, 'name' => 'size_staircases', 'other' => optional($property->propertySurvey)->size_staircases_other])
            @include('forms.form_propertyInfo_plus',['title' => 'No. Lifts:', 'compare_value' => optional($property->propertySurvey)->size_lifts, 'name' => 'size_lifts', 'other' => optional($property->propertySurvey)->size_lifts_other])
            @if(!empty($dataDropdowns))
                @foreach($dataDropdowns as $dataDropdown)
                    @include('forms.form_dropdown',['title' => $dataDropdown['description']. ':', 'data' => $dataDropdown['value'], 'name' => $dataDropdown['name'], 'key'=> 'id', 'value'=>'description', 'compare_value' => $dataDropdown['selected'], 'type' => 'site_data'])
                @endforeach
            @endif

            @include('forms.form_text_area',['title' => 'No. of Bedrooms:', 'data' => optional($property->propertySurvey)->size_bedrooms, 'name' => 'size_bedrooms' ])
            @include('forms.form_text_area',['title' => 'Net Area per Floor:', 'data' => optional($property->propertySurvey)->size_net_area, 'name' => 'size_net_area' ])
            @include('forms.form_text_area',['title' => 'Gross Area:', 'data' => optional($property->propertySurvey)->size_gross_area, 'name' => 'size_gross_area' ])
            @include('forms.form_text_area',['title' => 'Comments:', 'data' => optional($property->propertySurvey)->size_comments, 'name' => 'size_comments' ])
            <div class="col-md-6 offset-md-3">
                <button type="submit" id="submit" class="btn light_grey_gradient shine_submit">
                    Save
                </button>
            </div>
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
</script>
@endpush

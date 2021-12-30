
@extends('layouts.app')

@section('content')
@include('partials.nav', ['breadCrumb' => 'add_property','data' => $breadcrumb_data])
<div class="container prism-content">
    <h3>Add Property</h3>
    <div class="main-content">
        <form id="form_edit_property" method="POST" action="{{ route('property.post_add') }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            @include('forms.form_input',['title' => 'Property Name:', 'name' => 'name', 'required' => true ])
            @include('forms.form_input',['title' => 'Property Reference:', 'name' => 'property_reference' ])
            @include('forms.form_input',['title' => 'Block Reference:', 'name' => 'pblock' ])
            @include('forms.form_input',['title' => 'Core Reference:', 'name' => 'core_code' ])
            @include('forms.search_parent_property',['title' => 'Parent:', 'name' => 'parent_id'])
            @include('forms.form_dropdown',['title' => (env('APP_DOMAIN') == 'LBHC') ? 'Housing Area:' : 'Service Area:', 'data' => $service_area, 'name' => 'service_area_id', 'key'=>'id', 'value'=>'description'])
            @if(env('APP_DOMAIN') == 'LBHC')
                @include('forms.form_dropdown',['title' => 'Ward:', 'data' => $ward, 'name' => 'ward_id', 'key'=>'id', 'value'=>'description'])
            @endif
            @include('forms.form_input',['title' => 'Estate Code:', 'name' => 'estate_code'])
            {{-- @include('forms.form_text',['title' => 'Shine Reference:', 'data' => null, 'name' => 'reference' ]) --}}
            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Property Risk Type:</label>
                <div class="col-md-5">
                    <div class="form-group ">
                        @include('forms.form_multi_select_contruction',[ 'name' => 'riskType', 'id' => 'riskType', 'data_multis' => $risk_types, 'selected' => '', 'data_other' => '' ])
                    </div>
                </div>
            </div>
            <!-- form_dropdown_asset_type -->
            @include('forms.form_dropdown_asset_type',['title' => 'Asset Class:', 'data' => $asset_class, 'name' => 'asset_class_id', 'key'=>'id', 'value'=>'description'])
            @include('forms.form_dropdown_asset_type',['title' => 'Asset Type:', 'data' => $asset_type, 'name' => 'asset_type_id', 'key'=>'id', 'value'=>'description'])
            @include('forms.form_dropdown',['title' => 'Property Access Type:', 'data' => $programme_types, 'name' => 'programme_type', 'key'=>'id', 'value'=>'description','other' => 'programme_type_other', 'other_value' => ''])
            @include('forms.form_dropdown',['title' => 'Tenure Type:', 'data' => $tenure_type, 'name' => 'tenure_type_id', 'key'=>'id', 'value'=>'description'])
            @include('forms.form_dropdown',['title' => 'Communal Area:', 'data' => $communal_area, 'name' => 'communal_area_id', 'key'=>'id', 'value'=>'description'])
            @include('forms.form_dropdown',['title' => 'Responsibility:', 'data' => $responsibility, 'name' => 'responsibility_id', 'key'=>'id', 'value'=>'description'])
            @if(\CommonHelpers::isSystemClient())
                @include('forms.form_dropdown',['title' => 'Property Client:', 'data' => $clients_list, 'name' => 'client_id', 'key'=>'id', 'value'=>'client_description', 'required' => true, 'compare_value' =>$client->id ])
            @else
                @include('forms.form_text',['title' => 'Shine Reference:', 'data' => $client->name ])
                @include('forms.form_input_hidden',['name' => 'client_id', 'data' => $client->id ])
            @endif

            @include('forms.form_select_ajax_zone',['title' => 'Property Group:', 'name' => 'zone_id', 'required' => true ])

            @include('forms.form_input',['title' => 'Flat Number:', 'data' => null, 'name' => 'flat_number'  ])
            @include('forms.form_input',['title' => 'Building Name:', 'data' => null, 'name' => 'building_name'  ])
            @include('forms.form_input',['title' => 'Street Number:', 'data' => null, 'name' => 'street_number'  ])
            @include('forms.form_input',['title' => 'Street Name:', 'data' => null, 'name' => 'street_name'  ])
            @include('forms.form_input',['title' => 'Town:', 'data' => null, 'name' => 'town'  ])
            @include('forms.form_input',['title' => 'County:', 'data' => null, 'name' => 'address5'  ])
            @include('forms.form_input',['title' => 'Postcode:', 'data' => null, 'name' => 'postcode', 'width' => 2 ])
            @include('forms.form_input',['title' => 'Telephone:', 'data' => null, 'name' => 'telephone'  ])
            @include('forms.form_input',['title' => 'Mobile:', 'data' => null, 'name' => 'mobile'  ])
            @include('forms.form_input',['title' => 'Email:', 'data' => null, 'name' => 'email'  ])

            @if(count($list_users) > 0)
            @include('forms.form_dropdown',['title' => 'App Contact:', 'data' => $list_users, 'name' => 'app_contact', 'key'=>'id', 'value'=>'full_name', 'width' => 3 ])
            @else
            @include('forms.form_text',['title' => 'App Contact:', 'data' => Lang::get('message.empty_data_property'), 'name' => '' ])
            @endif

            @include('forms.form_upload',['title' => 'Photo:', 'name' => 'photo', 'folder' => PROPERTY_PHOTO ])

            @if(count($list_users))
                @include('forms.form_multiple_option',[ 'name'=>'team','title' => 'Property Contact:', 'dropdown_list'=> $list_users, 'value_get' => 'full_name', 'selected' => [] ])
            @else
                @include('forms.form_input',['title' => 'Property Contact:', 'data' => Lang::get('message.empty_data_property'), 'name' => '' ])
            @endif
            <!-- Primary Use -->
            @include('forms.form_dropdown',['title' => 'Primary Use:', 'data' => $primaryUses, 'name' => 'asset_use_primary', 'key'=>'id', 'value'=>'description' ,'other' => 'primaryusemore', 'other_value' => '' ])
            <!-- Secondary Use -->
            @include('forms.form_dropdown',['title' => 'Secondary Use:', 'data' => $primaryUses, 'name' => 'asset_use_secondary', 'key'=>'id', 'value'=>'description' ,'other' => 'secondaryusemore', 'other_value' => ''])


            @include('forms.form_input',['title' => 'Construction Age:', 'data' => null, 'name' => 'construction_age'  ])
            @include('forms.form_input',['title' => 'Construction Type:', 'data' => null, 'name' => 'construction_type'  ])
            @include('forms.form_propertyInfo_plus',['title' => 'No. Floors:', 'name' => 'size_floors', 'other' => '' ])
            @include('forms.form_propertyInfo_plus',['title' => 'No. Staircases:', 'name' => 'size_staircases', 'other' => ''])
            @include('forms.form_propertyInfo_plus',['title' => 'No. Lifts:', 'name' => 'size_lifts', 'other' => ''])
            @if(!empty($dataDropdowns))
                @foreach($dataDropdowns as $dataDropdown)
                    @include('forms.form_dropdown',['title' => $dataDropdown['description']. ':', 'data' => $dataDropdown['value'], 'name' => $dataDropdown['name'], 'key'=> 'id', 'value'=>'description', 'compare_value' => $dataDropdown['selected'], 'type' => 'site_data'])
                @endforeach
            @endif

            @include('forms.form_text_area',['title' => 'No. of Bedrooms:', 'data' => null, 'name' => 'size_bedrooms' ])
            @include('forms.form_text_area',['title' => 'Net Area per Floor:', 'data' => null, 'name' => 'size_net_area' ])
            @include('forms.form_text_area',['title' => 'Gross Area:', 'data' => null, 'name' => 'size_gross_area' ])
            @include('forms.form_text_area',['title' => 'Comments:', 'data' => null, 'name' => 'size_comments' ])
            <div class="col-md-6 offset-md-3">
                <button type="submit" id="submit" class="btn light_grey_gradient">
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
        $('#asset_type_id').find("option:first").prop('selected', true);
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

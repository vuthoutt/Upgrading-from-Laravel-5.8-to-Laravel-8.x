@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => ($location->survey_id == 0) ? 'edit_location' : 'edit_location','data' => $location,'color' => ($location->survey_id == 0) ? 'red' : 'orange'])
<div class="container-cus prism-content pad-up pl-0">
    <div class="row">
        <h3 class="col-12 title-row" style="margin: 0">Edit Location</h3>
    </div>

        <div class="main-content">
        <form method="POST" id="form-location" action="{{ route('shineCompliance.location.post_edit', ['id' => $location->id ?? 1]) }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            @include('shineCompliance.forms.form_input_hidden',['name' => 'area_id', 'data' => $location->area_id ?? 0 ])
            @include('shineCompliance.forms.form_input_hidden',['name' => 'survey_id', 'data' => $location->survey_id ?? 0 ])
            @include('shineCompliance.forms.form_input_hidden',['name' => 'property_id', 'data' => $location->property_id ?? 0 ])

            @include('shineCompliance.forms.form_text',['title' => 'Shine Reference:', 'data' => isset($location->reference) ? $location->reference : '' ])
            @include('shineCompliance.forms.form_input',['title' => 'Location Reference:', 'name' => 'reference','data' => $location->location_reference, 'required' => true ])
            @include('shineCompliance.forms.form_input',['title' => 'Location Description:', 'name' => 'description','data' => $location->description, 'required' => true ])
            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Location State:</label>
                <div class="col-md-5">
                    <div class="form-group fs-8pt">
                        <select  class="form-control " name="location-state" id="location-state">
                            <option value="{{LOCATION_STATE_ACCESSIBLE}}" {{ ($location->state == LOCATION_STATE_ACCESSIBLE ? 'selected' : '') }}>Accessible</option>
                            <option value="{{LOCATION_STATE_INACCESSIBLE}}" {{ ($location->state == LOCATION_STATE_INACCESSIBLE ? 'selected' : '') }}>Inaccessible</option>
                        </select>
                    </div>
                </div>
            </div>
            @include('shineCompliance.forms.form_dropdown_not_assessed',['assess_type' => 'location', 'compare_value' => $location->not_assessed, 'compare_reason' => $location->not_assessed_reason ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Reason NA:', 'data' => $reasons, 'name' => 'reasons-na[]','id' => 'reason1','key'=> 'id', 'value'=> 'description', 'compare_value' => $location->locationInfo->reason_inaccess_key ?? 0])
{{--             <div class="row register-form " id='form-reason-2'>
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" ></label>
                <div class="col-md-5">
                    <div class="form-group ">
                        <select  class="form-control" name="reasons-na[]" id="reason2">

                        </select>
                    </div>
                </div>
            </div> --}}
            <div class="row register-form" id="other_reason">
                <div class="col-md-5 offset-md-3">
                    <input class="form-control" type="text" name="reasons-na-other" id="reason_other" value="{{ $location->locationInfo->reason_inaccess_other ?? '' }}" />
                </div>
            </div>

            @include('shineCompliance.forms.form_upload',['title' => 'Location Photo:', 'name' => 'location-photo', 'object_id' => $location->id, 'folder' => LOCATION_IMAGE ])

            @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_location_void_investigations == ACTIVE) )
                    <div class="mt-4 mb-4">
                        <label class="font-weight-bold part-heading"> Room/location Void Investigations:</label>
                    </div>
                    @if(isset($locationVoidsData) and count($locationVoidsData) > 0)
                        @foreach($locationVoidsData as $locationVoidsData)
                            <div class="row register-form">
                                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($locationVoidsData['description']) ? $locationVoidsData['description']. ':' : '' }}</label>
                                <div class="col-md-5">
                                    <div class="form-group ">
                                        @if(isset($locationVoidsData['parents']) and count($locationVoidsData['parents']) > 0)
                                            <select  class="form-control" name="{{ $locationVoidsData['name'] }}" id="{{ $locationVoidsData['name'] }}">
                                                <option></option>
                                                @foreach($locationVoidsData['parents'] as $parents)
                                                    <option value="{{ $parents['id'] }}" {{ ($locationVoidsData['selected_parent'] == $parents['id']) ? 'selected' : '' }}>{{ $parents['description'] }}</option>
                                                @endforeach
                                            </select>

                                            @foreach($locationVoidsData['parents'] as $key => $parents)
                                                @if(count($parents['childs']) > 0)
                                                    @include('shineCompliance.forms.form_multi_select',[ 'name' => $locationVoidsData['name'].'-type', 'id' => $parents['id'], 'select' => $locationVoidsData['name'], 'data_multis' => $parents['childs'], 'selected_child' => $locationVoidsData['selected_child'], 'data_other' => $locationVoidsData['selected_other'] ])
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
            @endif

            @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_location_construction_details == ACTIVE) )
                <div class="mt-4 mb-4">
                    <label class="font-weight-bold part-heading"> Room/location Construction Details:</label>
                </div>
                @if(isset($locationContructionsDatas) and count($locationContructionsDatas) > 0)
                    @foreach($locationContructionsDatas as $locationContructionsData)
                        <div class="row register-form fs-8pt">
                            <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($locationContructionsData['description']) ? $locationContructionsData['description']. ':' : '' }}</label>
                            <div class="col-md-5">
                                <div class="form-group ">
                                    @include('shineCompliance.forms.form_multi_select_contruction',[ 'name' => $locationContructionsData['name'], 'id' => $locationContructionsData['id'], 'data_multis' => $locationContructionsData['childs'], 'selected' => $locationContructionsData['selected'], 'data_other' => $locationContructionsData['selected_other'] ])
                                </div>
                            </div>
                        </div>

                    @endforeach
                @endif
            @endif

            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Room/location Comments:</label>
                <div class="col-md-5" style="height: 150%">
                    <textarea class="text-area-form" name="location-comment" id="location-comment" style="height: 150%">{{ $location->locationInfo->comments ?? '' }}</textarea>
                </div>
            </div>
            <div class="col-md-6 offset-md-3 mt-4">
                <button type="button" id="add_location" class="btn light_grey_gradient_button fs-8pt">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    <div>
</div>
@endsection
@push('javascript')

    <script type="text/javascript">
    $('#other_reason').hide();
        $('#form-reason-2').hide();
        if ($("#location-state").val() == 1) {
            $("#reason1-form").hide();
            $('#other_reason').hide();
            $('#form-reason-2').hide();
        } else {
            $("#reason1-form").show();
        }
         $("#location-state").change(function(){
            if ($("#location-state").val() == 1) {
                $("#reason1-form").hide();
                $('#other_reason').hide();
                $('#form-reason-2').hide();
            } else {
                $("#reason1-form").show();
            }
        });

        $("#reason1").change(function(){
            $('#reason2').html("");
            var parent = $(this).val();
            if (parent == 410) {
                $('#other_reason').show();
                $('#form-reason-2').hide();
            } else if(parent == '') {
                $('#form-reason-2').hide();
                $('#other_reason').hide();
            }else {
                $('#form-reason-2').show();
                $('#other_reason').hide();
            }
            $.ajax({
                type: "GET",
                url: "{{ route('ajax.location_reason') }}",
                data: { parent_id: parent},
                cache: false,
                success: function (html) {
                        var selected_val = false;
                        $.each(html.data, function(key, value, selected ) {
                            if (value.id == {{ $location->locationInfo->reason_inaccess_key ?? 0 }}) {
                                selected_val = true;
                            }
                            $('#reason2').append($('<option>', {
                                value: value.id,
                                text : value.description,
                                selected : selected_val
                            }));
                        })
                }
            });
        });
        $("#reason1").trigger('change');
        $('body').on('click', '#add_location', function(){
            $('.other-class').each(function(k,v){
                console.log($(this), $(this).is(':visible'));
                if($(this).is(':visible') == false){
                    $(this).val('');
                }
            });
            $('#form-location').submit();

         })
</script>
@endpush

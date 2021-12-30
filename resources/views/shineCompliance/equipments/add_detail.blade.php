<div class="offset-top40">
    <input type="hidden" name="property_id" value="{{ $property_id ?? 0 }}">
    <input type="hidden" name="assess_id" id="assess_id" value="{{ $assess_id ?? 0 }}">
    @include('shineCompliance.forms.form_input',['title' => 'Equipment Name:', 'name' => 'name', 'required' => true, 'data' =>'' ,'id' => 'item'])
    {{-- @include('shineCompliance.forms.form_dropdown',['title' => 'Equipment Type:', 'data' => $types, 'name' => 'type', 'key'=> 'id', 'value'=>'description', 'required' => true ]) --}}
    @include('shineCompliance.form_search.search_equipment_type',['title' => 'Equipment Type:', 'name' => 'type', 'data' =>'' ,'id' => 'system_type'])
    @include('shineCompliance.forms.form_dropdown_area_equipment',['title' => 'Area/floor:', 'name' => 'area',
                    'required' => true, 'key'=>'id', 'value'=>'title', 'data' => $areas])
    @include('shineCompliance.forms.form_dropdown_area_equipment',['title' => 'Room/location:', 'name' => 'location',
                    'required' => true, 'key'=>'id', 'value'=>'title', 'data' => []])
    <div class="row register-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Status:<span style="color: red;"> *</span></label>
        <div class="col-md-5">
            <div class="form-group ">
                <select  class="form-control @error('decommissioned') is-invalid @enderror" name="decommissioned" id="status">
                    <option value="{{ EQUIPMENT_UNDECOMMISSION }}">Live</option>
                    <option value="{{ EQUIPMENT_DECOMMISSION }}">Decommissioned</option>
                </select>
            </div>
        </div>
        @error('decommissioned')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    @include('shineCompliance.forms.form_dropdown',['title' => 'Operational Use:', 'data' => $operationals, 'name' => 'operational_use', 'key'=> 'id', 'value'=>'description', 'required' => true, 'no_first_op' => true ])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Accessibility:', 'data' => '','compare' => 1, 'name' => 'state', 'required' => true ])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Reason for Inaccessibility:', 'data' => $reasons, 'name' => 'reason', 'key'=> 'id', 'value'=>'description'])
    <div class="row register-form mb-4 acm-child" id="reason-other-form">
        <div class="col-md-5 offset-md-3">
            <input type="text" class="form-control" name="reason_other" id="reason-other" placeholder="Please add other Reason">
        </div>
    </div>

    @include('shineCompliance.form_search.search_equipment',['title' => 'Parent:', 'property_id' => $property_id ?? 0, 'assess_id' => $assess_id ?? 0,
                                                            'name' => 'parent_id', 'data' =>'' ,'id' => 'parent_id', 'class_other' => 'equipment_section'])
    @include('shineCompliance.form_search.search_equipment',['title' => 'Hot Parent:', 'property_id' => $property_id ?? 0, 'assess_id' => $assess_id ?? 0,
                                                            'name' => 'hot_parent_id', 'data' =>'' ,'id' => 'hot_parent_id', 'class_other' => 'equipment_section',
                                                            'templates' => '10,11,12,13'])
    @include('shineCompliance.form_search.search_equipment',['title' => 'Cold Parent:', 'property_id' => $property_id ?? 0, 'assess_id' => $assess_id ?? 0,
                                                            'name' => 'cold_parent_id', 'data' =>'' ,'id' => 'cold_parent_id', 'class_other' => 'equipment_section',
                                                            'templates' => '1,3,4,5,6,7,8,9,14,15,16,17'])
    @include('shineCompliance.form_search.search_system',['title' => 'Link:', 'property_id' => $property_id ?? 0, 'assess_id' => $assess_id ?? 0,
                                                            'data' => $system->name ?? '', 'value' => $system->id ?? 0,'name' => 'system_id','id' => 'system_id'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Specific Location:', 'data' => $specificLocations, 'name' => 'specificLocations1','id' => 'specificLocations', 'key'=> 'id', 'value'=>'description' ])
    <div class="row" style="margin-top: -14px;">
        <div class="col-md-5 offset-md-3">
            <div class="form-group mt-3">
                <select class="form-control" name="specificLocations2" id="specificLocations2">
                    <option value="-1"></option>
                </select>
            </div>
        </div>
    </div>
    <div class="row" id="specific-form3">
        <div class="col-md-5 offset-md-3">
            <div class="form-group">
                <select class="form-control" name="specificLocations3[]" id="specificLocations3" multiple="multiple" style="width: 100%">
                    <option value="-1"></option>
                </select>
            </div>
        </div>
    </div>
    <div class="row" id="specific-other">
        <div class="col-md-5 offset-md-3">
            <div class="form-group">
                <input type="text" class="form-control" name="specificLocations_other" placeholder="Please add other specific locations">
            </div>
        </div>
    </div>

    @include('shineCompliance.forms.form_dropdown',['title' => 'Frequency Of Use:', 'data' => $frequencies, 'name' => 'frequency_use', 'key'=> 'id', 'value'=>'description', 'required' => true ])
    @include('shineCompliance.forms.form_input_small',['title' => 'Extent:', 'name' => 'extent', 'data' =>'' ,'measurement' => 'Number', 'class_other' => 'equipment_section'])

</div>

@push('javascript')

    <script type="text/javascript">
    $(document).ready(function(){


        $("#state").change(function(){
            var accessibility = $("#state").prop('checked');
            if (accessibility) {
                $("#reason-form").hide();
            } else {
                $("#reason-form").show();
            }
        });
        $("#state").trigger('change');

        $("#reason").change(function(){
            var text = $("#reason").find(":selected").text();
            if (text == 'Other') {
                $("#reason-other").show();
            } else {
                $("#reason-other").hide();
            }
        });
        $("#reason").trigger('change');

        // Specific location
        $("#specific-other").hide();
        $('#specificLocations3').select2({
            placeholder: "Please select an option"
        }).on('select2:select', function(e){
          var id = e.params.data.id;
          var option = $(e.target).children('[value='+id+']');
          option.detach();
          $(e.target).append(option).change();
        });

        $("#specific-form3").hide();
        $("#specificLocations2").hide();

        // on change specific location 1
        $("#specificLocations").change(function(){

            $('#specificLocations3').find('option').remove();
            // show other option if
            var text = $("#specificLocations").find(":selected").text();
            if (text.includes("Other")) {
                $("#specific-other").show();
                $("#specific-form3").hide();
                $("#specificLocations2").hide();
            } else {
                $("#specific-other").hide();
                $('#specificLocations2').find('option').remove();
                var parentId = $("#specificLocations").val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('shineCompliance.equipment.ajax_specific') }}",
                    data: {parent_id: parentId},
                    cache: false,
                    success: function (html) {
                        if (html.data.length > 0) {
                            if (html.have_child == true) {

                                $.each(html.data, function(key, value ) {
                                    $('#specificLocations2').append($('<option>', {
                                        value: value.id,
                                        text : value.description
                                    }));
                                });
                                $("#specificLocations2").show().trigger('change');
                                $("#specific-form3").show();
                                // onchangeSpecific2();
                            } else {
                                $('#specificLocations3').find('option').remove();
                                $.each(html.data, function(key, value ) {
                                    $('#specificLocations3').append($('<option>', {
                                        value: value.id,
                                        text : value.description
                                    }));
                                });
                                $("#specific-form3").show();
                                $("#specificLocations2").hide();
                            }
                        } else {
                            $("#specific-form3").hide();
                            $("#specificLocations2").hide();
                        }
                    }
                });
            }
        });
        //on change specific location 2
        $("#specificLocations2").change(function(){
            getAjaxItemData('specificLocations2','specificLocations3')
        });
        // end of specific location


        function getAjaxItemData(parent_select_id, child_select_id, child_select_id2 , child_select_id3 ) {
            if (child_select_id2 === undefined) {
                child_select_id2 = 'nan';
            }
            if (child_select_id3 === undefined) {
                child_select_id3 = 'nan';
            }
            $('#' + child_select_id).find('option').remove();
            var parent_id = $('#' + parent_select_id).val();
            $.ajax({
                type: "GET",
                url: "{{ route('shineCompliance.equipment.ajax_specific') }}",
                data: {parent_id: parent_id},
                cache: false,
                success: function (html) {
                    if (html.data.length > 0) {
                        $.each(html.data, function(key, value ) {
                            $('#' + child_select_id).append($('<option>', {
                                value: value.id,
                                text : value.description
                            }));
                        })
                        $('#' + child_select_id).show().trigger('change');
                    } else {
                        $('#' + child_select_id).hide();
                        $('#' + child_select_id2).hide();
                        $('#' + child_select_id3).hide();
                        $("#productDebris1").show();
                    }
                },
            });
        }

        $('#area').change(function () {
            $('#overlay').fadeIn();
                if ($(this).val() > 0) {
                    $.ajax
                    ({
                        type: "GET",
                        url: "/compliance/assessment/" + $('#assess_id').val() + "/area/" + $(this).val() + "/locations",
                        cache: false,
                        success: function (data) {
                            if (data) {
                                if (data != undefined) {
                                    $('#location').html('');
                                    $('#location').append($('<option>', {
                                        value: '',
                                        text : '------ Please select an option -------'
                                    }));
                                    $.each( data, function( key, value ) {
                                        $('#location').append($('<option>', {
                                            value: value.id,
                                            text : value.location_reference + ' - ' + value.description
                                        }))
                                    });
                                    $('#location').append($('<option>', {
                                        value: -1,
                                        text : 'Other'
                                    }));
                                }
                            }
                        }
                    });
                } else {
                    $('#location').html('');
                    $('#location').append($('<option>', {
                        value: '',
                        text : '------ Please select an option -------'
                    })).append($('<option>', {
                        value: -1,
                        text : 'Other'
                    }));
                }
            $('#overlay').fadeOut();
        })

    });

</script>
@endpush

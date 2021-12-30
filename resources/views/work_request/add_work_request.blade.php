@extends('shineCompliance.layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'work_requests_add','color' => 'red', 'data' => ''])
<style>
    #overlay {
        background: #ffffff;
        color: #666666;
        position: fixed;
        height: 100%;
        width: 100%;
        z-index: 5000;
        top: 0;
        left: 0;
        float: left;
        text-align: center;
        padding-top: 25%;
        opacity: .80;
    }
    .spinner {
        margin: 0 auto;
        height: 64px;
        width: 64px;
        animation: rotate 0.8s infinite linear;
        border: 5px solid firebrick;
        border-right-color: transparent;
        border-radius: 50%;
    }
    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    Loading...
</div>
<div class="container prism-content">
    <h3>Add Work Request</h3>
        <div class="main-content">
        <form method="POST" action="{{ route('wr.post_add') }}" id="form_add_survey" enctype="multipart/form-data" class="form-shine">
            @csrf
            <input type="hidden" name="number_positive" id="number_positive" value=""/>
            <input type="hidden" name="sor_id" id="sor_id" value=""/>
            <input type="hidden" name="property_resposibility" id="responsibility_id" />
            <input type="hidden" name="domestic_property" id="domestic_property_ids" />
            <input type="hidden" name="group_id" id="group_id"/>
            @include('forms.form_text',['title' => 'Work Requester:', 'data' => \Auth::user()->full_name ])
            @include('forms.form_dropdown',['title' => 'Work Request Type:', 'data' => $compliance_types, 'name' => 'compliance_type', 'key'=> 'id', 'value'=>'description', 'required' => true])
            @include('forms.form_dropdown',['title' => '', 'data' => $work_request_types, 'name' => 'wr_type', 'key'=> 'id', 'value'=>'description', 'option_data' => 'compliance_type'])
            @include('forms.form_select_ajax',['title' => '', 'name' => 'type' ])
            @include('forms.form_select_ajax',['title' => '', 'name' => 'major_type' ])
            @include('forms.form_dropdown',['title' => 'Work Request Lead:', 'data' => $asbestos_leads, 'name' => 'asbestos_lead', 'key'=> 'id', 'value'=>'full_name', 'compare_value'=> '', 'required' => true ])
            <div id="load_contractor">
            </div>
            @include('forms.form_dropdown',['title' => 'Contractor:', 'data' => $contractors, 'name' => 'contractor', 'key'=> 'id','class_other' => 'major_work' ,'value'=>'name', 'required' => true])
            @include('forms.form_dropdown',['title' => 'Duration / Number Of Tests:', 'data' => $duration_number_test, 'name' => 'duration_number_test', 'key'=> 'id', 'value'=>'description','class_other' => 'duration_number_test'])
            @include('forms.form_dropdown',['title' => 'Priority:', 'data' => $priorities, 'name' => 'priority', 'key'=> 'id', 'value'=>'description','option_data' => 'other', 'required' => true])
            @include('forms.form_text_area',['title' => 'Priority Reason:','name' => 'priority_reason', 'class_other' => 'priority_reason_form'])

            <div class="row register-form major_work">
                <label  class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold">
                Additional Documents:
                </label>
                <div class="col-md-{{ isset($width) ? $width : 5 }}">
                    <div class="form-group">
                        <input class="col-md-8" type="file" class="form-control-file @error('major_document') is-invalid @enderror" name="major_document">
                        @error('major_document')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <a href="{{ route('summary.property_info_template') }}" >Programmed Works CSV Template Download</a>
                </div>
            </div>
            @include('forms.form_search_property', ['title' => 'Property','name' => 'property_id', 'data' => NULL, 'required' => true])
            @include('forms.form_input_with_number',['title' => 'Enclosure Size:', 'data' => '' ,'name' => 'enclosure_size', 'amount' => 'm²' ,'class_other' => 'air_test_only'])
            @include('forms.form_input_with_number',['title' => 'Duration of Work:', 'data' => '' ,'name' => 'duration_of_work', 'amount' => 'Days' ,'class_other' => 'air_test_only'])
            @include('forms.form_input_with_number',['title' => 'Number of Bedrooms:', 'data' => '' ,'name' => 'number_of_rooms', 'amount' => 'Rooms' ,'class_other' => 'survey_only'])
            <div class="mt-4 mb-4 property_info">
                <label class="font-weight-bold part-heading"> PROPERTY INFORMATION</label>
            </div>

            <div class="row mb-2 property_info">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Property Asset Type:</label>
                <div class="col-md-6 form-input-text" >
                    <input type="hidden" id="asset_type_id" value="" />
                        <span id="asset_type"></span>
                </div>
            </div>
            <div class="row mb-1 property_info">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Property Access Type:</label>
                <div class="col-md-6 form-input-text" >
                        <span id="access_type"></span>
                </div>
            </div>

            <div class="row mb-1 property_info">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Property Reference:</label>
                <div class="col-md-6 form-input-text" >
                    <span id="property_ref"></span>
                </div>
            </div>

            <div class="mb-1 mb-1 property_info" id="property_contact_parent">

            </div>

            @include('forms.form_checkbox',['title' => 'Property Occupied:', 'data' => '','compare' => 1, 'name' => 'site_occupied' ,'class_other' => 'property_info_input'])
            @include('forms.form_text_area',['title' => 'Property Availability:','name' => 'site_availability','class_other' => 'property_info_input'])
            @include('forms.form_text_area',['title' => 'Security Requirements:','name' => 'security_requirements','class_other' => 'property_info_input'])
            @include('forms.form_dropdown',['title' => 'Parking Arrangements:', 'data' => $parkings, 'name' => 'parking_arrangements', 'key'=> 'id', 'value'=>'description','class_other' => 'property_info_input','other' => 'parking_arrangements_other','other_value' =>''])
            @include('forms.form_checkbox',['title' => 'Electricity Availability:', 'data' => '','compare' => 1, 'name' => 'electricity_availability' ,'class_other' => 'property_info_input'])
            @include('forms.form_checkbox',['title' => 'Water Availability:', 'data' => '','compare' => 1, 'name' => 'water_availability' ,'class_other' => 'property_info_input'])
            @include('forms.form_dropdown',['title' => 'Ceiling Height:', 'data' => $ceilling_heights, 'name' => 'ceiling_height', 'key'=> 'id', 'value'=>'description','class_other' => 'property_info_input'])

            <div class="mt-4 mb-4 general_form">
                <label class="font-weight-bold part-heading"> WORK REQUEST SCOPE</label>
            </div>
            @include('forms.form_text_area',['title' => 'Scope of Work:','name' => 'scope_of_work','class_other' => 'general_form', 'max_length' => 700, 'required' => true, 'class' => 'form-control'])
            @include('forms.form_input',['title' => 'Reported by:', 'name' => 'reported_by','id' => 'reported_by', 'data' => \Auth::user()->full_name ?? '', 'required' => true, 'maxlength' => 47])
            @include('forms.form_text_area',['title' => 'Access Note:','name' => 'access_note','class_other' => 'general_form', 'max_length' => 50])
            @include('forms.form_text_area',['title' => 'Location Note:','name' => 'location_note','class_other' => 'general_form', 'max_length' => 50])
            @include('forms.form_checkbox',['title' => 'Isolation Required:', 'data' => '','compare' => 1, 'name' => 'isolation_required' ,'class_other' => 'remediation_only'])
            @include('forms.form_checkbox',['title' => 'Decant Required:', 'data' => '','compare' => 1, 'name' => 'decant_required' ,'class_other' => 'remediation_only'])
            @include('forms.form_text_area',['title' => 'Re-Instatement Requirements:','name' => 'reinstatement_requirements','class_other' => 'remediation_only'])
            @include('forms.form_text_area',['title' => 'Unusual Features:','name' => 'unusual_requirements','class_other' => 'survey_only'])

            <div class="mt-4 mb-4 general_form">
                <label class="font-weight-bold part-heading"> HEALTH AND SAFETY REQUIREMENTS</label>
            </div>
            @include('forms.form_text_area',['title' => 'Property Specific Health and Safety Requirements:','name' => 'site_hs','class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'High Level Access:', 'data' => '','compare' => 1, 'name' => 'hight_level_access' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Max Height if Over 3m:', 'data' => '','compare' => 1, 'name' => 'max_height' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Loft Spaces:', 'data' => '','compare' => 1, 'name' => 'loft_spaces' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Floor Voids:', 'data' => '','compare' => 1, 'name' => 'floor_voids' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Basements:', 'data' => '','compare' => 1, 'name' => 'basements' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Ducts:', 'data' => '','compare' => 1, 'name' => 'ducts' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Lift Shafts:', 'data' => '','compare' => 1, 'name' => 'lift_shafts' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Light Wells:', 'data' => '','compare' => 1, 'name' => 'light_wells' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Confined Spaces:', 'data' => '','compare' => 1, 'name' => 'confined_spaces' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Fumes/Dust:', 'data' => '','compare' => 1, 'name' => 'fumes_duct' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Patching/Making Good:', 'data' => '','compare' => 1, 'name' => 'pm_good' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Fragile Material:', 'data' => '','compare' => 1, 'name' => 'fragile_material' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Hot/Live Services:', 'data' => '','compare' => 1, 'name' => 'hot_live_services' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Pigeons:', 'data' => '','compare' => 1, 'name' => 'pieons' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Vermin:', 'data' => '','compare' => 1, 'name' => 'vermin' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Biological/chemical:', 'data' => '','compare' => 1, 'name' => 'biological_chemical' ,'class_other' => 'general_form'])
            @include('forms.form_checkbox_with_other',['title' => 'Vulnerable Tenant:', 'data' => '','compare' => 1, 'name' => 'vulnerable_tenant' ,'class_other' => 'general_form'])
            @include('forms.form_text_area',['title' => 'Other:','name' => 'other','class_other' => 'general_form'])

            <div class="mt-4 mb-4 general_form">
                <label class="font-weight-bold part-heading"> SUPPORTING DOCUMENTS</label>
            </div>

            <div class="row register-form general_form">
                <label  class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold">
                Additional Documents:
                </label>
                <div class="col-md-{{ isset($width) ? $width : 5 }}">
                    <div class="form-group">
                        <input class="col-md-8" type="file" class="form-control-file @error('document') is-invalid @enderror" name="document">
                        @error('document')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-6 offset-md-3 mt-3">
                <button type="submit" id="add_wr" class="btn light_grey_gradient">
                    <strong>{{ __('Add') }}</strong>
                </button>
            </div>
        </form>
        <div class="mb-5"></div>
    </div>
</div>
@endsection
@push('javascript')
    <script type="text/javascript" src="{{ URL::asset('js/multiple_input.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        //refresh token after a specified period of time
        var csrfToken = $('[name="csrf_token"]').attr('content');
        var period_time = 14*60*1000; // 14min
        setInterval(refreshToken, period_time); // 1 hour

        function refreshToken(){
            $.ajax
            ({
                type: "GET",
                url: "/refresh-csrf",
                data: {},
                cache: false,
                success: function (html) {
                    $('[name="csrf_token"]').attr('content', html);
                }
            });
        }

        hideForm();

        function hideForm(){
            // $("#check_udc").hide();
            $("#add_wr").show();
            $('.general_form').hide();
            $('.air_test_only').hide();
            $('.remediation_only').hide();
            $('.survey_only').hide();
            $('.priority_reason_form').hide();
            $('.major_work').hide();
            $('#major_type').hide();
            $('.property_info_input').hide();
            $('.property_info').hide();
            $('.property_search').show();
            $('.property_search_info').hide();
            $('.warning_text').hide();
            $('.duration_number_test').hide();
        }
        $("#type").hide();
        $("#wr_type").hide();
        // $("#add_wr").hide();
        $("#compliance_type").change(function () {
            var val = $(this).val();
            $("#type").hide();
            $("#wr_type").hide();
            $("#wr_type option[data-option='"+val+"']").show();
            $("#wr_type option[data-option!='"+val+"']").hide();
            $("#wr_type").val(0);
            $("#wr_type").show();

            // change asbestos lead based on work request type
            if (val == {{ WORK_REQUEST_ASBESTOS_TYPE }}) {
                $("#asbestos_lead").val(15);
            } else if (val == {{ WORK_REQUEST_FIRE_TYPE }}) {
                $("#asbestos_lead").val(116);
            } else {
                $("#asbestos_lead").val('');
            }
        });
        $("#wr_type").change(function () {
            hideForm();
            var parent_id =  $(this).val();
            $("#type").show();
            $("#type").html("");
            $.ajax
            ({
                type: "GET",
                url: "{{ route('ajax.wr_dropdown') }}",
                data: { parent_id : parent_id},
                cache: false,
                success: function (html) {
                    if (html.data) {
                        $('#type').append($('<option>', {
                            value: 0,
                            text : ''
                        }));
                        $.each( html.data, function( key, value ) {
                            $('#type').append($('<option>', {
                                'data-type': value.type,
                                value: value.id,
                                text : value.description
                            }));
                        });
                        checkSor();
                    }
                }
            });
        });


        $("#type").change(function () {
            var type =  $( "#type option:selected" ).data('type');
            if (type == 'air') {
                $('.general_form').show();
                $('.air_test_only').show();
                $('.remediation_only').hide();
                $('.survey_only').hide();
                $('.property_info').show();
                $('.property_info_input').show();
                $('.duration_number_test').show();
                // $("#check_udc").show();
                // $("#add_wr").hide();
                //if sucesswarning is visible then show add
                checkSor();
            } else if (type == 'remediation') {
                $('.general_form').show();
                $('.air_test_only').hide();
                $('.remediation_only').show();
                $('.survey_only').hide();
                $('.property_info').show();
                $('.property_info_input').show();
                $('.duration_number_test').hide();
                // $("#check_udc").show();
                // $("#add_wr").hide();
                //if sucesswarning is visible then show add
                checkSor();
            } else if (type == 'survey') {
                $('.general_form').show();
                $('.air_test_only').hide();
                $('.remediation_only').hide();
                $('.survey_only').show();
                $('.property_info').show();
                $('.property_info_input').show();
                $('.duration_number_test').hide();
                // $("#check_udc").show();
                // $("#add_wr").hide();
                //if sucesswarning is visible then show add
                checkSor();
            } else if(type == 'workMajor'){
                //hide all warning
                //show Add btn
                var parent_id =  $(this).val();
                $("#major_type").show();
                // $("#check_udc").hide();
                $("#add_wr").show();
                $('.major_work').show();
                $('.property_info').hide();
                $('.general_form').hide();
                $('.property_search').hide();
                $('.duration_number_test').hide();
                $("#major_type").html("");
                $.ajax
                ({
                    type: "GET",
                    url: "{{ route('ajax.wr_dropdown') }}",
                    data: { parent_id : parent_id},
                    cache: false,
                    success: function (html) {
                        if (html.data) {
                            $('#major_type').append($('<option>', {
                                value: 0,
                                text : ''
                            }));
                            $.each( html.data, function( key, value ) {
                                $('#major_type').append($('<option>', {
                                    'data-type': value.type,
                                    value: value.id,
                                    text : value.description
                                }));
                            });
                            majorType();
                        }
                    }
                });

            }else{
                hideForm();
                checkSor();
            }
        });

        $("#priority").change(function () {
            var other =  $( "#priority option:selected" ).data('option');
            if (other == 1) {
                $('.priority_reason_form').show();
            } else {
                $('.priority_reason_form').hide();
            }
        });

        function majorType() {
            $("#major_type").change(function () {
                var major_type_selected =  $( "#major_type option:selected" ).data('type');
                if (major_type_selected == 'air') {
                    $('.general_form').show();
                    $('.air_test_only').show();
                    $('.remediation_only').hide();
                    $('.survey_only').hide();
                } else if (major_type_selected == 'remediation') {
                    $('.general_form').show();
                    $('.air_test_only').hide();
                    $('.remediation_only').show();
                    $('.survey_only').hide();
                } else if (major_type_selected == 'survey') {
                    $('.general_form').show();
                    $('.air_test_only').hide();
                    $('.remediation_only').hide();
                    $('.survey_only').hide();
                }

            });
        }

        $('body').on('focusout', '#enclosure_size, #duration_of_work, #number_of_rooms', function(e){
            checkSor();
        });

        $('body').on('change', '#priority, #duration_number_test', function(e){
            checkSor();
        })
        // property search check sor
        //type

        $('body').on('click', '#add_wr', function(e){
            // scope_of_work, reported_by
            e.preventDefault()
            var list_email_val = $("[name='email_cc[]']")
                .map(function(){
                    return $(this).val();
                })
            if(list_email_val.length > 0 && !$.trim(list_email_val[0]) == ''){
                var is_error_email = scrollToErrorMultiple(['.email_cc']);
            }
            var is_error = scrollToError(['#scope_of_work', '#reported_by']);
            if(is_error || is_error_email){
                return
            } else {
                $('#form_add_survey').submit();
            }

        });
    });
    function checkSor(){
        //if major return
        var wr_type = $('#wr_type').val();
        if(wr_type == 35 || wr_type == 57){
            $('.warning_text').hide();
            $("#add_wr").show();
            //hide all warning
            //show add button
            return;
        }
        // $('#add_wr').hide();
        $('#warning_insufficient').hide();
        if($('#type').val() == 0 || !$('#property-id-search').val()){
            //show yellow banner
            $('#add_wr').hide();
            $('#warning_insufficient').show();
            $('#sor_id').val('MANUAL');
            return;
        }

        //check property ref
        var pro_ref = $.trim($('#property_ref').text());
        $('.warning_text').hide();
        if(pro_ref.length < 5){
            $('#warning_not_found_property').show();
            $('#sor_id').val('MANUAL');
            $('#add_wr').show();
            return true;
        }

        if($('#warning_repair_responsibility').is(":visible") || $('#responsibility_id').val() == 1919){
            $('#warning_repair_responsibility').show();
            $('#sor_id').val('MANUAL');
            // var str = $('#domestic_property_ids').val();
            // var strArray = str.split(',');
            // console.log(strArray, $('#group_id').val());
            // if($.inArray($('#group_id').val(), strArray) !== -1){
            //     $('#add_wr').hide();
            // } else {
            //     $('#add_wr').show();
            // }
            //check domestic property
            $('#add_wr').show();
            return;
        }
        var work_type_id =  $("#type").val();
        var property_type_id =  $("#asset_type_id").val();
        var rooms =  $.trim($('#number_of_rooms').val());
        var priority =  $.trim($('#priority').val());
        var duration_of_work =  $.trim($('#duration_of_work').val());
        var enclosure_size =  $.trim($('#enclosure_size').val());
        var number_positive =  $.trim($('#number_positive').val());
        var duration_number_test =  $.trim($('#duration_number_test').val());
        $('#overlay').fadeIn();
        $.ajax
        ({
            type: "GET",
            url: "{{ route('ajax.check_sor_logic') }}",
            data: {
                work_type_id : work_type_id,
                property_type_id : property_type_id,
                property_id : $('#property-id-search').val(),
                rooms :rooms,
                priority :priority,
                duration_of_work :duration_of_work,
                enclosure_size :enclosure_size,
                number_positive :number_positive,
                duration_number_test :duration_number_test
            },
            cache: false,
            async: true,
            success: function (html) {
                console.log(html);
                $('#add_wr').show();
                $('#overlay').fadeOut();
                if (html.status_code == 200) {
                    $('#warning_us').show();
                    $('#warning_us').html(html.message);
                    $('#sor_id').val(html.data);
                    checkProperty(html.is_validate);
                } else if(html.status_code == 302) {
                    $('#warning_na').show();
                    $('#sor_id').val('MANUAL');
                    // checkProperty(html.is_validate);
                } else {
                    $('#warning_not_found').show();
                    $('#sor_id').val('MANUAL');
                    // checkProperty(html.is_validate);
                }
            }
        });
    }

    function checkProperty(is_validate){
        $('#warning_not_found_property').hide();
        if(is_validate){
            $('#warning_na').hide();
            $('#warning_not_found').hide();
            $('#warning_us').hide();
            $('#warning_not_found_property').show();
            $('#sor_id').val('MANUAL');
            // $('#add_wr').hide();
        } else {
            // $('#add_wr').show();
        }
    };

    function scrollToErrorMultiple(arr_ele){
        var is_error = false;
        if(arr_ele.length > 0){
            $.each(arr_ele, function(k1,ele){
                $.each($(ele), function (k2,v2) {
                    var value = $.trim($(v2).val());
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    if(value == '' || !emailReg.test(value)){
                        console.log(1);
                        is_error = true;
                        $(v2).addClass('is-invalid');
                        $(v2).parent().find('span strong').html('This field can not be empty or incorrect email format');
                        $('html, body').animate({
                            scrollTop: $(v2).offset().top - 200
                        }, 1000);
                        return false;
                    } else {
                        $(v2).removeClass('is-invalid');
                        $(v2).parent().find('span strong').html('');
                    }
                });
            });
        }
        return is_error;
    }

    function scrollToError(arr_ele){
        var is_error = false;
        if(arr_ele.length > 0){
            $.each(arr_ele, function(k,v){
                if($(v).is(":visible") && $.trim($(v).val()) == '' ){
                    is_error = true;
                    $(v).addClass('is-invalid');
                    $(v).parent().find('span strong').html('This field can not be empty.');
                    $('html, body').animate({
                        scrollTop: $(v).offset().top - 200
                    }, 1000);
                    return false;
                } else {
                    $(v).removeClass('is-invalid');
                    $(v).parent().find('span strong').html('');
                }
            });
        }
        return is_error;
    }

        $.ajax
        ({
            type: "GET",
            url: "{{ route('ajax.email_cc' )}}",
            cache: false,
            success: function (data) {
                if (data) {
                    $('#load_contractor').html(data.data)

                    $('#max_option').val(data.total - 1);
                }
                else {

                }
            }
        });
</script>
@endpush

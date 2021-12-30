@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav', ['breadCrumb' => 'add_incident_report','color' => 'red', 'data' => ''])
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
    <h3>Add Incident Reporting</h3>
        <div class="main-content">
        <form method="POST" action="{{ route('shineCompliance.incident_reporting.post_add') }}" id="form_add_incident_reporting" enctype="multipart/form-data" class="form-shine">
            @csrf
            @include('shineCompliance.forms.form_input_hidden',['name' => 'report_recorder', 'data' => \Auth::user()->id ])
            @include('shineCompliance.forms.form_text',['title' => 'Report Recorder:', 'data' => \Auth::user()->full_name ])
            @if (\Auth::user()->is_call_centre_staff)
                @include('shineCompliance.forms.form_input',['title' => 'Call Centre Team Member Name:', 'data' => '', 'name' => 'call_centre_team_member_name', 'required' =>true  ])
            @endif
{{--            @include('shineCompliance.forms.form_dropdown',['title' => 'H&S Lead:', 'data' => $hs_leads, 'name' => 'asbestos_lead', 'key'=> 'id', 'value'=>'full_name', 'compare_value'=> '', 'required' => true])--}}
            @include('shineCompliance.forms.form_dropdown',['title' => 'Report Form Type:', 'data' => $report_types, 'key' => 'id', 'value' => 'description', 'name' => 'type', 'required' =>true  ])

            <div class="incident-reporting-form">
                @include('shineCompliance.forms.form_text',['title' => 'Date of Report:', 'data' => date('d/m/Y') ])
                @include('shineCompliance.forms.form_text',['title' => 'Time of Report:', 'data' => date('H:i') ])
            </div>

            <div class="incident-type-form">
                @include('shineCompliance.forms.form_datepicker',['title' => 'Date of Incident:', 'data' => date('d/m/Y'), 'name' => 'date_of_incident', 'required' => true ])
                @include('shineCompliance.forms.form_timepicker',['title' => 'Time of Incident:', 'data' => date('H:i'), 'name' => 'time_of_incident', 'required' => true ])
            </div>

            <div class="incident-reporting-form">
                @include('shineCompliance.forms.form_dropdown_non_user',['title' => 'Reported By:', 'data' => $users, 'name' => 'reported_by', 'key'=> 'id', 'value'=>'full_name',
                            'compare_value'=> \Auth::user()->id, 'required' => true, "non_user" =>true])
                @include('shineCompliance.forms.search_parent_property_incident',['title' => 'Address of Incident:', 'name' => 'property_id', 'required' => true, 'width' => 8])
                @include('shineCompliance.forms.search_equipment',['title' => 'Equipment:', 'name' => 'equipment_id', 'data' =>'' ,'id' => 'equipment_id'])
                @include('shineCompliance.forms.search_system',['title' => 'System:', 'data' => '','name' => 'system_id','id' => 'system_id'])

                <div class="row register-form parent-element" style="margin-bottom: 30px">
                    <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt"><span class="incident-details">Details</span>: <span style="color:red">*</span></label>
                    <div class="col-md-5" style="height: 150%">
                        <textarea class="text-area-form form-require @error('details') is-invalid @enderror" name="details" id="details" style="height: 150%"></textarea>
                        <span class="invalid-feedback" role="alert" style="display: block">
                            <strong>@error('details'){{ $message }}@enderror</strong>
                        </span>
                    </div>
                </div>
                @include('shineCompliance.forms.form_dropdown',['title' => 'Category of Works (Contractor Work Only):', 'data' => $category_of_works, 'name' => 'category_of_works', 'key'=> 'id', 'value'=>'description'])
                @include('shineCompliance.forms.form_checkbox',['title' => 'Was there a Risk Assessment for this Work?', 'data' => '','compare' => 1, 'name' => 'is_risk_assessment'])
            </div>

            <div class="incident-type-form">
                @include('shineCompliance.forms.form_checkbox',['title' => 'Was there any person(s) involved?', 'data' => '','compare' => 1, 'name' => 'is_involved'])
                <div class="involved-form">
                    @include('shineCompliance.forms.form_multiple_in_multiple_option',[
                        'title' => 'Who was involved?',
                        'data' => '',
                        'name' => 'involved_persons',
                        'id' => 'involved',
                        'dropdown_list' => $users,
                        'value_get' => 'fullname',
                        'child_dropdown' => $involved_select,
                        'non_user' => true
                        ])
                </div>
            </div>

            <div class="incident-reporting-form">
                @include('shineCompliance.forms.form_multiple_file_upload',['title' => 'Supporting Document(s) & Photography', 'data' => '','name' => 'documents','id' => 'documents'])
            </div>

            <div class="incident-type-form">
                @include('shineCompliance.forms.form_checkbox',['title' => 'Confidential:', 'data' => '','compare' => 1, 'name' => 'confidential'])
            </div>

            <div class="col-md-6 offset-md-3 mt-3">
                <button type="submit" id="add_ir" class="btn light_grey_gradient">
                    <strong>{{ __('Add') }}</strong>
                </button>
            </div>
        </form>
        <div class="mb-5"></div>
    </div>
</div>
@endsection
@push('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $('.incident-reporting-form').hide();
        $('.incident-type-form').hide();
        $('.involved-form').hide();

        $('#type').on('change', function () {
            $('#overlay').fadeIn();
            var type = $(this).val();
            if (type == {!! INCIDENT !!}) {
                $('.incident-details').html("{!! INCIDENT_DETAILS_TITLE !!}");
                $('.incident-reporting-form').show();
                $('.incident-type-form').show();
            } else if (type == {!! SOCIAL_CARE !!}) {
                $('.incident-details').html("{!! SOCIAL_CARE_TITLE !!}");
                $('.incident-reporting-form').show();
                $('.incident-type-form').show();
            } else if (type == {!! EQUIPMENT_NONCONFORMITY !!}) {
                $('.incident-details').html("{!! EQUIPMENT_NONCONFORMITY_DETAILS_TITLE !!}");
                $('.incident-reporting-form').show();
                $('.incident-type-form').hide();
            } else if (type == {!! IDENTIFIED_HAZARD !!}) {
                $('.incident-details').html("{!! IDENTIFIED_HAZARD_DETAILS_TITLE !!}");
                $('.incident-reporting-form').show();
                $('.incident-type-form').hide();
            } else {
                $('.incident-reporting-form').hide();
                $('.incident-type-form').hide();
                $('.involved-form').hide();
            }
            $('#overlay').fadeOut();
        });

        $('#is_involved').change('click', function () {
            $('#overlay').fadeIn();
            if($(this).prop('checked')) {
                $('.involved-form').show();
            } else {
                $('.involved-form').hide();
            }
            $('#overlay').fadeOut();
        });

        $('body').on('change', '.form-require', function(){
            if(!$(this).val() || $(this).val() == ''){
                var label = $(this).closest('.parent-element').find('label').html();
                label = label.replace('*', '');//remove character :*
                label = label.replace(':', '');//remove character :*
                var warning_msg = 'The ' + label+ ' field is required!';
                showWarning(true, this, warning_msg);
            } else{
                showWarning(false, this, '');
            }
        });

        //show warning
        function showWarning(is_show, that, message){
            if(is_show){
                $(that).addClass('is-invalid');
                $(that).parent().find('span strong').html(message);
            } else {
                $(that).removeClass('is-invalid');
                $(that).parent().find('span strong').html('');
            }
        }

        $('#property_id-property-old').show()
        $('#property_id-property-insert').hide()
        $('body').on('click', '#is_address_in_wcc_checkbox', function(e){
            var check = $("#is_address_in_wcc").is(":checked");
            if(check) {
                $('#property_id-property-old').hide()
                $('#property_id-property-insert').show()
                $('#form-equipment_id').hide()
                $('#form-system_id').hide()
            } else {
                $('#property_id-property-old').show()
                $('#property_id-property-insert').hide()
                $('#form-equipment_id').show()
                $('#form-system_id').show()
            }
        });

        $('body').on('click', '#add_ir', function(e){
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

        $('body').on('change', '.select-incident-report-person' ,function(e) {
            var id = $(this).val();
            if(id == -1){
                $(this).closest('.involved-persons-select').find('.involved_insert_form').show()
            }else{
                $(this).closest('.involved-persons-select').find('.involved_insert_form').hide()
            }
        })
        $('.select-incident-report-person').trigger('change');
    });
</script>
@endpush

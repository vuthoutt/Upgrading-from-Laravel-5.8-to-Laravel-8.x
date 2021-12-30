@extends('shineCompliance.layouts.app')
@section('content')
@if($client->client_type == 0)
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'my-organisation-add-user', 'color' => 'red', 'data' => $client])
@elseif($client->client_type == 1)
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'contractor-add-user', 'color' => 'red', 'data' => $client])
@else
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'client-add-user', 'color' => 'red', 'data' => $client])
@endif
@include('partials.nav', ['breadCrumb' => 'home'])
<div class="container prism-content">
    <h3> Add User </h3>
    <div class="main-content">
        <form method="POST" action="{{ route('contractor.post_add_user') }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" name="client_type" id="client_type" value="{{ $client->client_type }}">
            @include('forms.form_input',['title' => 'Forename:', 'data' => '', 'name' => 'first_name','required' => true ])
            @include('forms.form_input',['title' => 'Last Name:', 'data' => '', 'name' => 'last_name','required' => true ])
            @include('forms.form_text',['title' => 'Organisation:', 'data' => $client->name ])
            <div class="row register-form">
                <label for="email" class="col-md-3 col-form-label text-md-left font-weight-bold">{{ __('Department:') }} <span style="color: red;">*</span></label>
                <div class="col-md-5">
                    <div class="">
                        <div class="form-group">
                            @if(isset($department_id) && count($departments) > 0)
                                @include('forms.form_dropdown_department_edit',['title' => 'Department:', 'data' => $departments, 'name' => '', 'key'=> 'id', 'value'=>'name' ])
                            @else
                                @include('forms.form_dropdown_department',['title' => 'Department:', 'data' => $departments, 'name' => '', 'key'=> 'id', 'value'=>'name' ])
                            @endif
                            @error('departments')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group" id="departmentOther">
                        <input type="text" class="form-control @error('department-other') is-invalid @enderror" name="department-other" placeholder="Please add new department">
                        @error('role')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>


            @include('forms.form_input',['title' => 'Job Title:', 'data' => '', 'name' => 'job-title' ])
            @include('forms.form_input',['title' => 'Username:', 'data' => '', 'name' => 'username','required' => true ])
            <div class="form-group row">
                <label for="password" class="col-md-3 col-form-label text-md-left font-weight-bold">{{ __('Password:') }}<span style="color: red;">*</span></label>
                <div class="col-md-5">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert" style="">
                            <strong style="font-size: 105%;">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            @include('forms.form_input',['title' => 'Mobile:', 'data' => '', 'name' => 'mobile' ])
            @include('forms.form_input',['title' => 'Telephone:', 'data' => '', 'name' => 'telephone' ])
            @include('forms.form_input',['title' => 'Email:', 'data' => '', 'name' => 'email','required' => true ])

            @include('forms.form_upload',['title' => 'Signature:', 'name' => 'signature' ])
            @if($client->client_type == 0)
                @include('forms.form_dropdown',['title' => 'Role:', 'data' => $roleSelect, 'name' => 'role', 'key'=> 'id', 'value'=>'name', 'required' => true ])
            @endif
            @include('forms.form_datepicker',['title' => 'Last shineAsbestos Awareness Training:', 'data' => date('d/m/Y'), 'name' => 'asbestos-awareness' ])
            @include('forms.form_datepicker',['title' => 'Last shineAsbestos Training:', 'data' => date('d/m/Y'), 'name' => 'shine-asbestos' ])

            @include('forms.form_input',['title' => 'Notes:', 'data' => '', 'name' => 'notes' ])

            <div class="row register-form {{ isset($class_other) ? $class_other : '' }}">
                <label for="first-name" class="col-md-{{ isset($width_label) ? $width_label : 3 }}  col-form-label text-md-left font-weight-bold">
                    Housing Officer:
                </label>

                <div class="col-md-5 mt-1">
                    <label class="switch">
                        <input type="checkbox" name="housing_officer"
                               class="primary {{isset($class) ? $class : ''}}" value="1" {{ isset($userData->housing_officer) ? (($userData->housing_officer == 1) ? 'checked' : '') : ''}}>
                        <span class="slider round" ></span>
                    </label>
                </div>
            </div>

            <div class="col-md-6 offset-md-3">
                <button type="button" id="add_user" class="btn light_grey_gradient">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    <div>
</div>
@endsection
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('click', '#add_user', function(e){
                e.preventDefault();
                var is_valid = true;
                $('.form-require').each(function(k,v){
                    console.log(v);
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

            function showWarning(is_show, that, message){
                if(is_show){
                    $(that).addClass('is-invalid');
                    $(that).parent().find('span strong').html(message);
                } else {
                    $(that).removeClass('is-invalid');
                    $(that).parent().find('span strong').html('');
                }
            }

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
        })
    </script>
@endpush

@extends('shineCompliance.layouts.app')
@section('content')
@if($client->client_type == 0)
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'my-organisation-add-user', 'color' => 'red', 'data' => $client])
@elseif($client->client_type == 1)
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'contractor-add-user', 'color' => 'red', 'data' => $client])
@else
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'client-add-user', 'color' => 'red', 'data' => $client])
@endif
<div class="container-cus prism-content">
    <div class="row">
        <h3 class="title-row"> Add User </h3>
    </div>
    <div class="main-content">
        <form method="POST" action="{{ route('shineCompliance.contractor.post_add_user') }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" name="client_type" id="client_type" value="{{ $client->client_type }}">
            @include('shineCompliance.forms.form_input',['title' => 'Forename:', 'data' => '', 'name' => 'first_name','required' => true ])
            @include('shineCompliance.forms.form_input',['title' => 'Surname:', 'data' => '', 'name' => 'last_name','required' => true ])
            @include('shineCompliance.forms.form_upload',['title' => 'Avatar:', 'name' => 'avatar', 'object_id' => NULL, 'folder' => AVATAR ])
            @include('shineCompliance.forms.form_text',['title' => 'Organisation:', 'data' => $client->name ])
            <div class="row register-form">
                <label for="email" class="col-md-3 col-form-label text-md-left font-weight-bold">{{ __('Department:') }} <span style="color: red;">*</span></label>
                <div class="col-md-5">
                    <div class="">
                        <div class="form-group">
                            @if(isset($department_id) && count($departments) > 0)
                                @include('shineCompliance.forms.form_dropdown_department_edit',['title' => 'Department:', 'data' => $departments, 'name' => '', 'key'=> 'id', 'value'=>'name' ])
                            @else
                                @include('shineCompliance.forms.form_dropdown_department',['title' => 'Department:', 'data' => $departments, 'name' => '', 'key'=> 'id', 'value'=>'name' ])
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


            @include('shineCompliance.forms.form_input',['title' => 'Username:', 'data' => '', 'name' => 'username','required' => true ])
            @if($client->client_type == 0)
                @include('shineCompliance.forms.form_dropdown',['title' => 'Role:', 'data' => $roleSelect, 'name' => 'role', 'key'=> 'id', 'value'=>'name', 'required' => true ])
            @endif
            @include('shineCompliance.forms.form_input',['title' => 'Job Title:', 'data' => '', 'name' => 'job-title' ])
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
            @include('shineCompliance.forms.form_input',['title' => 'Mobile:', 'data' => '', 'name' => 'mobile' ])
            @include('shineCompliance.forms.form_input',['title' => 'Telephone:', 'data' => '', 'name' => 'telephone' ])
            @include('shineCompliance.forms.form_input',['title' => 'Email:', 'data' => '', 'name' => 'email','required' => true ])

            @include('shineCompliance.forms.form_upload',['title' => 'Signature:', 'name' => 'signature' ])
            <div id="load_equipment_inventory">

            </div>
{{--            @include('shineCompliance.forms.form_datepicker',['title' => 'Last shineAsbestos Awareness Training:', 'data' => date('d/m/Y'), 'name' => 'asbestos-awareness' ])--}}
{{--            @include('shineCompliance.forms.form_datepicker',['title' => 'Last shineAsbestos Training:', 'data' => date('d/m/Y'), 'name' => 'shine-asbestos' ])--}}

            @include('shineCompliance.forms.form_input',['title' => 'Notes:', 'data' => '', 'name' => 'notes' ])

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
                <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('javascript')
    <script type="text/javascript" src="{{ URL::asset('js/shineCompliance/multiple_option.js') }}"></script>
    <script type="text/javascript">
        $.ajax
        ({
            type: "GET",
            url: "{{ route('shineCompliance.ajax.equipment_inventory_select' )}}",
            data: {
                selected : "{{ $equipment_inventories ?? '' }}",
            },
            cache: false,
            success: function (data) {
                if (data) {
                    $('#load_equipment_inventory').html(data.data)

                    $('#max_option').val(data.total - 1);
                }
                else {

                }
            }
        });
    </script>
@endpush


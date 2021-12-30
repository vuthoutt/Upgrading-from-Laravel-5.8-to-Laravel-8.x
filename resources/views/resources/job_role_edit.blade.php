@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'job_role','data' => ''])

<div class="container prism-content">
    <h3 class="ml-2">
        Job Roles Details
    </h3>
    <div class="main-content">
        <div class="row">
            <div class="col-9">
                <form method="GET" id="edit-role-form" action="{{route('get_job_role')}}">
                    @csrf
                    @include('forms.form_job_role',['id' => 'job_role', 'data' => $job_roles])
                    <button type="submit" name="submit" id="submit" class="btn light_grey_gradient">
                        <strong>Save</strong>
                    </button>
                </form>
            </div>
            </div>
            <div class="col-3">
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script type="text/javascript">
    $(document).ready(function(){
    });
</script>
@endpush

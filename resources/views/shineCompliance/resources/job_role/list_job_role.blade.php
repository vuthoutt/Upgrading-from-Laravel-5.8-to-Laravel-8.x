@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'job_role','data' => ''])

<div class="container prism-content">
    <h3 class="ml-2">
        Job Roles Details
    </h3>
    <div class="main-content">
        <div class="tab-content">
            <div id="role" class="container">
                <div class="row">
                    @include('shineCompliance.tables.role', [
                   'title' => 'Job Roles',
                   'tableId' => 'roles-table',
                   'collapsed' => false,
                   'plus_link' => true,
                   'modal_id' => 'job-modal',
                   'data' => $job_roles
                   ])
                </div>
                @include('shineCompliance.modals.job_role_add_update',['color' => 'red', 'modal_id' => 'job-modal', 'title' => 'Job Role', 'url' => route('shineCompliance.ajax.resource_role')])
            </div>
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

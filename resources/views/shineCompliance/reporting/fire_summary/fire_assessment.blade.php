@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'summary_com', 'color' => 'red'])
<div class="container-cus prism-content pad-up">
    @include('shineCompliance.summary.partials._summary_button')

    <div class="d-flex" id="asdasd">
        <!-- Sidebar -->
        <div class="bg-light border-summary" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <h3>Fire Assessment Summary</h3>
            </div>
            <div class="nav list-group list-group-flush">
                @include('shineCompliance.reporting.partials.fire_summary')
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div>
                    @if(\CompliancePrivilege::checkPermission(JR_REPORTING, JR_PREPLAN_SUMMARY_FIRE,JOB_ROLE_FIRE) and \CommonHelpers::isSystemClient())
                        <h3 class="text-center mt-4">Fire Hazard AR Summary</h3>
                        <div class="mt-5">
                            <div class="form-summary">
                                <a href="{{ route('shineCompliance.export.fire_summary.fire_assessment_hazard') }}">
                                <button type="submit" class="btn shine-compliance-button ml-3 light_grey_gradient_button font-weight-bold fs-8pt">
                                    Export CSV File
                                </button>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

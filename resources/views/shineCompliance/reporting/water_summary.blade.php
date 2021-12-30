@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'summary_com', 'color' => 'red'])
<div class="container-cus prism-content pad-up">
    @include('shineCompliance.summary.partials._summary_button')

    <div class="d-flex" id="asdasd">
        <!-- Sidebar -->
        <div class="bg-light border-summary" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <h3>Water Summary</h3>
            </div>
            <div class="nav list-group list-group-flush">
                @if(\CompliancePrivilege::checkPermission(JR_REPORTING, JR_PREPLAN_SUMMARY_WATER,JOB_ROLE_WATER) and \CommonHelpers::isSystemClient())
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link active">
                    Pre-Planned Maintenance Summary
                </a>
                @endif
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div>
                    @if(\CompliancePrivilege::checkPermission(JR_REPORTING, JR_PREPLAN_SUMMARY_WATER,JOB_ROLE_WATER) and \CommonHelpers::isSystemClient())
                    <h3 class="text-center mt-4">Pre-Planned Maintenance Summary</h3>
                    {{-- <form method="GET" action="{{ route('shineCompliance.export.summary') }}"> --}}
                        <div class="mt-5">
                            <div class="form-summary">
                                <a  href="{{ route('shineCompliance.export.summary') }}">
                                <button type="submit" class="btn shine-compliance-button ml-3 light_grey_gradient_button font-weight-bold fs-8pt">
                                    Export CSV Flie
                                </button>
                                </a>
                            </div>
                        </div>
                    {{-- </form> --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

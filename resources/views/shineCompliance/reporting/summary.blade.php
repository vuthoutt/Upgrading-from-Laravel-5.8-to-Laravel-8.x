@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'summary_com', 'color' => 'red'])
<div class="container-cus prism-content pad-up">
    <div class="d-flex" id="asdasd">
        <!-- Sidebar -->
        <div class="bg-light border-summary" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <h3>Summary</h3>
            </div>
            <div class="nav list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link active">
                    Pre-Planned Maintenance Summary
                </a>
                {{-- <a href="#" class="list-group-item list-group-item-action bg-light nav-link">
                    Risk Assessment Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Inaccessible Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Action/recommendation Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Technical Manager Survey Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Project Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Register Item Change
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    HD Document Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Survey Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Project Document Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    User Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Area/floor Check
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Room/location Check
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Re-Inspection Programme
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Directors Overview - Asbestos
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Managers Overview - Asbestos
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Contractor KPI Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Property Information
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Asbestos Remedial Action Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Priority for Action
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Decommissioned Item Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    User Community Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Sample Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Duplication Checker
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Photography Size Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Survey Document Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Santia Sample Summary
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link ">
                    Project Metadata
                </a> --}}
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

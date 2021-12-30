@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'summary_com', 'color' => 'red'])
<div class="container-cus prism-content pad-up">
    @include('shineCompliance.summary.partials._summary_button')

    <div class="d-flex" id="asdasd">
        <!-- Sidebar -->
        <div class="bg-light border-summary" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <h3>General Summaries</h3>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
    </div>
</div>
@endsection

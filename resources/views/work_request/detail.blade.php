@extends('shineCompliance.layouts.app')
@section('content')
    @include('partials.nav', ['breadCrumb' => 'work_requests_detail','data' => $workRequest])

    <div class="container prism-content">
        <h3>Work Request</h3>
        <div class="main-content">
            <!-- Nav tabs -->
            <ul class="nav nav-pills red_gradient_nav" id="myTab">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#details"><strong>Details</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#requirements"><strong>Requirements</strong></a>
                </li>
                @if($workRequest->is_major == 1)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Properties"><strong>Properties</strong></a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#documents"><strong>Documents</strong></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="details" class="container tab-pane active">
                    @include('work_request.tab_details',['data' => $workRequest])
                </div>
                <div id="requirements" class="container tab-pane fade">
                    @include('work_request.tab_requirements',['data' => $workRequest])
                </div>
                <div id="Properties" class="container tab-pane fade">
                    @include('work_request.tab_properties',['data' => $properties])
                </div>
                <div id="documents" class="container tab-pane fade">
                     @include('work_request.tab_documents',['workRequest' => $workRequest])
                </div>
            </div>

        </div>
    </div>
@endsection

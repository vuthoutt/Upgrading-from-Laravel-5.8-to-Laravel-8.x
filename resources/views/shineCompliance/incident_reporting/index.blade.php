@extends('shineCompliance.layouts.app')

@section('content')

    @include('shineCompliance.partials.nav', ['breadCrumb' => 'detail_incident_report','color' => 'red', 'data' => $incident ?? ''])

<div class="container-cus prism-content pad-up">
    <div class="row" style="margin-top: 45px;padding-left: 15px">
        <h3 class="title-row" >Incident Report</h3>
    </div>
    <div class="main-content mar-up">
        <div class="main-content">
            <!-- Nav tabs -->
            <input type="hidden" id="active_tab" value="">
            <ul class="nav nav-pills red_gradient_nav" id="myTab">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#Details" title="Details"><strong>Details</strong></a>
                </li>
                <li class="nav-item {{ $incident->confidential && (Auth::user()->id == $incident->asbestos_lead || Auth::user()->id == $incident->second_asbestos_lead || Auth::user()->id == $incident->report_recorder) || !$incident->confidential ? '' : 'ui-state-disabled' }}">
                    <a class="nav-link" data-toggle="tab" href="#summary" title="Summary"><strong>Summary</strong></a>
                </li>
                <li class="nav-item {{ $incident->confidential && (Auth::user()->id == $incident->asbestos_lead || Auth::user()->id == $incident->second_asbestos_lead || Auth::user()->id == $incident->report_recorder) || !$incident->confidential ? '' : 'ui-state-disabled' }}    ">
                    <a class="nav-link" data-toggle="tab" href="#documents" title="Documents"><strong>Documents</strong></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="Details" class="container tab-pane active">
                    @include('shineCompliance.incident_reporting.detail')
                </div>

                <div id="summary" class="container tab-pane fade">
                    @if($incident->confidential && (Auth::user()->id == $incident->asbestos_lead || Auth::user()->id == $incident->second_asbestos_lead || Auth::user()->id == $incident->report_recorder) || !$incident->confidential)
                        @include('shineCompliance.incident_reporting.summary')
                    @endif
                </div>

                <div id="documents" class="container tab-pane fade">
                    @if($incident->confidential && (Auth::user()->id == $incident->asbestos_lead || Auth::user()->id == $incident->second_asbestos_lead || Auth::user()->id == $incident->report_recorder) || !$incident->confidential)
                        @include('shineCompliance.incident_reporting.documents')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('javascript')

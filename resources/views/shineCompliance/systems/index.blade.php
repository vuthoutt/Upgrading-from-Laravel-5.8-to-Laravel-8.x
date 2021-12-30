@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_system_detail', 'color' => 'orange', 'data'=> $system])
<div class="container prism-content pad-up pl-0">
    <div class="row">
        <h3 class="col-12">{{ $system->name ?? '' }}</h3>
    </div>
    <div class="main-content">
        <ul class="nav nav-pills {{ ($system->assess_id == 0) ? \CommonHelpers::getNavItemColor('red') : \CommonHelpers::getNavItemColor('orange') }}" id="myTab">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#details"><strong>Details</strong></a>
            </li>
        </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="details" class="container tab-pane active pl-0">
                    <div class="offset-top40">
                        <div class="col-md-12 client-image-show mb-3">
                            <img class="image-signature" src="{{ ComplianceHelpers::getSystemFile($system->id, COMPLIANCE_SYSTEM_PHOTO) }}" style="max-height: 320px">
                        </div>
                        <div class="col-md-12 client-image-show mb-3">
                            <a title="Download Asbestos Room/Location Image" href="{{ route('shineCompliance.retrive_image',['type'=>  COMPLIANCE_SYSTEM_PHOTO ,'id'=> $system->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
                        </div>
                    </div>
                    <div class="offset-top40">
                        @include('shineCompliance.forms.form_text',['title' => 'System Name:', 'data' => $system->name ?? '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Reference:', 'data' => $system->reference ?? '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'System Type:', 'data' => $system->systemType->description ?? ''  ])
                        @include('shineCompliance.forms.form_text',['title' => 'Classification:', 'data' => $system->systemClassification->description ?? ''  ])
                        @include('shineCompliance.forms.form_text',['title' => 'Comment:', 'data' => $system->comment ?? ''  ])
                    </div>
                </div>
                <div class="col-md-6 mt-4 pl-0">
                    @if(($system->assess_id != 0) and isset($system->assessment->is_locked) and ($system->assessment->is_locked == 1))
                        <div class="spanWarningSurveying">
                            <strong><em>System is view only because technical activity is complete</em></strong>
                        </div>
                    @else
                    @if($system->decommissioned == SURVEY_UNDECOMMISSION && $system->is_locked == SURVEY_UNLOCKED)
                        <a href="{{ route('shineCompliance.system.get_edit_system', ['id' => $system->id ?? 1]) }}">
                            <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                                <strong>{{ __('Edit') }}</strong>
                            </button>
                        </a>
                    @endif
                    <a href="{{  route('shineCompliance.systems.decommission',['id' => $system->id ?? 0]) }}" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                            @if($system->decommissioned == SURVEY_UNDECOMMISSION)
                                <strong>{{ __('Decommission') }}</strong>
                            @else
                                <strong>{{ __('Recommission') }}</strong>
                            @endif
                        </button>
                    </a>
                    @endif
                </div>
            </div>
    </div>
</div>
@endsection

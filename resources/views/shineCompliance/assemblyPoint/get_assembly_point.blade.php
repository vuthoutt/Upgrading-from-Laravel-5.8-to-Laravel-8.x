@extends('shineCompliance.layouts.app')
@section('content')
    @if($assemblyPoint->assess_id == 0)
        @include('shineCompliance.partials.nav',['breadCrumb' => 'register_assembly_point_detail', 'color' => 'red', 'data'=> $assemblyPoint])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_assembly_point_detail', 'color' =>'orange', 'data'=> $assemblyPoint])
    @endif
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">{{ $assemblyPoint->reference }} - Assembly Point Details</h3>
        </div>
        @if($assemblyPoint->assess_id == 0)
            @include('shineCompliance.assemblyPoint._assembly_button', [
                'backRoute' =>  route('shineCompliance.property.fireExit',['property_id' => $assemblyPoint->property_id]) ])
        @else
            @include('shineCompliance.assemblyPoint._assembly_button', [
                        'backRoute' =>  route('shineCompliance.assessment.show',['assessment_id' => $assemblyPoint->assess_id]) ])
        @endif
        <div class="main-content">
            @include('shineCompliance.forms.form_text',['title' => 'Reference:', 'data' => $assemblyPoint->reference ])
            @include('shineCompliance.forms.form_text',['title' => 'Assembly Point Name:', 'data' => $assemblyPoint->name ])
            @include('shineCompliance.forms.form_text',['title' => 'Area/floor:', 'data' => $assemblyPoint->area->area_reference ?? 'N/A' ])
            @include('shineCompliance.forms.form_text',['title' => 'Room/location:', 'data' => $assemblyPoint->location->location_reference ?? 'N/A' ])
            @include('shineCompliance.forms.form_text',['title' => 'Accessibility:', 'data' => ($assemblyPoint->accessibility == 1) ? 'Yes' : 'No' ])
            @if($assemblyPoint->accessibility == 0)
                @include('shineCompliance.forms.form_text',['title' => 'Reason NA:', 'data' => $assemblyPoint->reasonNotAccessible->description ?? '' ])
            @endif
            @if($assemblyPoint->decommissioned == 1)
                @include('shineCompliance.forms.form_text',['title' => 'Reason:', 'data' => $assemblyPoint->reasonDecommission->description ?? ''])
            @endif
            <div class="row">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Assembly Point Photo</label>
                <div class="col-md-6 form-input-text" >
                    <img class="image-item" src="{{ CommonHelpers::getFile($assemblyPoint->id, ASSEMBLY_POINT_PHOTO) }}">
                </div>
            </div>
            @include('shineCompliance.forms.form_text',['title' => 'Assembly Point Comments:', 'data' => $assemblyPoint->comment ])
        </div>
        <div class="mt-3 pb-2">
            @if($assemblyPoint->is_locked && ($assemblyPoint->assess_id == 0 || $assemblyPoint->assess_id != 0 && $assemblyPoint->assessment->status == ASSESSMENT_STATUS_LOCKED))
                <div class="spanWarningSurveying">
                    <strong><em>Assembly Point is view only while assessment is in progress</em></strong>
                </div>
            @else
                @if($assemblyPoint->assess_id != 0 && $assemblyPoint->assessment->status == ASSESSMENT_STATUS_COMPLETED)
                    <div class="spanWarningSurveying">
                        <strong><em>Assembly Point is view only because technical activity is complete</em></strong>
                    </div>
                @else
                    @if($can_update)
                        @if($assemblyPoint->decommissioned == 0)
                            @if($assemblyPoint->is_locked == SURVEY_UNLOCKED)
                                <a href="{{ route('shineCompliance.assessment.get_edit_assembly_point',['assembly_point_id' => $assemblyPoint->id]) }}" style="text-decoration: none">
                                    <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                                        <strong>{{ __('Edit') }}</strong>
                                    </button>
                                </a>
                            @endif
                            <a href="" data-toggle="modal" data-target="#decommission_assembly_point" style="text-decoration: none">
                                <button type="button" class="btn light_grey_gradient_button fs-8pt">
                                    <strong>{{ __('Decommission') }}</strong>
                                </button>
                            </a>
                            @php
                                $decommissionModalOption = [
                                        'modal_id' => 'decommission_assembly_point',
                                        'header' => 'Decommission Assembly Point',
                                        'name' => 'decommission_reason',
                                        'decommission_type' => 'work_request',
                                        'reference' => $assemblyPoint->reference ?? '',
                                        'url' => route('shineCompliance.assessment.decommission.assembly_point', ['id' => $assemblyPoint->id]),
                                    ];
                                if ($assemblyPoint->assess_id) {
                                    $decommissionModalOption['color'] = 'orange';
                                }
                            @endphp
                            @include('shineCompliance.modals.decommission_assessment', $decommissionModalOption)
                        @else
                            <a href="" data-toggle="modal" data-target="#recommission_assembly_point" style="text-decoration: none">
                                <button type="button" class="btn light_grey_gradient_button fs-8pt">
                                    <strong>{{ __('Recommission') }}</strong>
                                </button>
                            </a>
                            @php
                                $recommissionModalOption = [
                                        'modal_id' => 'recommission_assembly_point',
                                        'header' => 'Recommission Assembly Point',
                                        'reference' => $assemblyPoint->reference ?? '',
                                        'url' => route('shineCompliance.assessment.recommission.assembly_point', ['id' => $assemblyPoint->id]),
                                    ];
                                if ($assemblyPoint->assess_id) {
                                    $recommissionModalOption['color'] = 'orange';
                                }
                            @endphp
                            @include('shineCompliance.modals.common_recommission_assessment', $recommissionModalOption)
                        @endif
                    @endif
                @endif
            @endif
        </div>
    </div>
@endsection

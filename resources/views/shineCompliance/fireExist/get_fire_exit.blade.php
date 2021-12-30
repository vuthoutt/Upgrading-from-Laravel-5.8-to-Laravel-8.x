@extends('shineCompliance.layouts.app')
@section('content')
    @if($fireExit->assess_id == 0)
        @include('shineCompliance.partials.nav',['breadCrumb' => 'register_fire_exit_detail', 'color' => 'red', 'data'=> $fireExit])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_fire_exit_detail', 'color' => 'orange', 'data'=> $fireExit])
    @endif

    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">{{ $fireExit->reference }} - Fire Exit Details</h3>
        </div>
        @if($fireExit->assess_id == 0)
            @include('shineCompliance.assemblyPoint._assembly_button', [
                'backRoute' =>  route('shineCompliance.property.fireExit',['property_id' => $fireExit->property_id]) ])
        @else
            @include('shineCompliance.assemblyPoint._assembly_button', [
                        'backRoute' =>  route('shineCompliance.assessment.show',['assessment_id' => $fireExit->assess_id]) ])
        @endif
        <div class="main-content">
            @include('shineCompliance.forms.form_text',['title' => 'Reference:', 'data' => $fireExit->reference ])
            @include('shineCompliance.forms.form_text',['title' => 'Fire Exit Point Name:', 'data' => $fireExit->name ])
            @include('shineCompliance.forms.form_text',['title' => 'Fire Exit Type:', 'data' => $fireExit->type_disp ])
            @include('shineCompliance.forms.form_text',['title' => 'Area/floor:', 'data' => $fireExit->area->area_reference ?? 'N/A'])
            @include('shineCompliance.forms.form_text',['title' => 'Room/location:', 'data' => $fireExit->location->location_reference ?? 'N/A'])
            @include('shineCompliance.forms.form_text',['title' => 'Accessibility:', 'data' => ($fireExit->accessibility == 1) ? 'Yes' : 'No' ])
            @if($fireExit->accessibility == 0)
                @include('shineCompliance.forms.form_text',['title' => 'Reason NA:', 'data' => $fireExit->reasonNotAccessible->description ?? ''])
            @endif
            @if($fireExit->decommissioned == 1)
                @include('shineCompliance.forms.form_text',['title' => 'Reason:', 'data' => $fireExit->reasonDecommission->description ?? ''])
            @endif
            <div class="row">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Fire Exit Photo</label>
                <div class="col-md-6 form-input-text" >
                    <img class="image-item" src="{{ CommonHelpers::getFile($fireExit->id, FIRE_EXIT_PHOTO) }}">
                </div>
            </div>
            @include('shineCompliance.forms.form_text',['title' => 'Fire Exit Comments:', 'data' => $fireExit->comment ])
        </div>
        <div class="mt-3 pb-2">
            @if($fireExit->is_locked && ($fireExit->assess_id == 0 || $fireExit->assess_id != 0 && $fireExit->assessment->status == ASSESSMENT_STATUS_LOCKED))
                <div class="spanWarningSurveying">
                    <strong><em>Fire Exit is view only while assessment is in progress</em></strong>
                </div>
            @else
                @if($fireExit->assess_id != 0 && $fireExit->assessment->status == ASSESSMENT_STATUS_COMPLETED)
                    <div class="spanWarningSurveying">
                        <strong><em>Fire Exit is view only because technical activity is complete</em></strong>
                    </div>
                @else
                    @if($can_update)
                        @if($fireExit->decommissioned == 0)
                            @if($fireExit->is_locked == SURVEY_UNLOCKED)
                                <a href="{{ route('shineCompliance.assessment.get_edit_fire_exit',['fire_exit_id' => $fireExit->id]) }}" style="text-decoration: none">
                                    <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                                        <strong>{{ __('Edit') }}</strong>
                                    </button>
                                </a>
                            @endif
                            <a href="" data-toggle="modal" data-target="#decommission_fire_exit" style="text-decoration: none">
                                <button type="button" class="btn light_grey_gradient_button fs-8pt">
                                    <strong>{{ __('Decommission') }}</strong>
                                </button>
                            </a>
                            @php
                                $decommissionModalOption = [
                                        'modal_id' => 'decommission_fire_exit',
                                        'header' => 'Decommission Fire Exit',
                                        'name' => 'decommission_reason',
                                        'decommission_type' => (isset($fireExit->assessment->classification) and ($fireExit->assessment->classification == ASSESSMENT_FIRE_TYPE)) ? 'assessment_fire' : 'assessment_water',
                                        'reference' => $fireExit->reference ?? '',
                                        'url' => route('shineCompliance.assessment.decommission.fire_exit', ['id' => $fireExit->id]),
                                    ];
                                if ($fireExit->assess_id) {
                                    $decommissionModalOption['color'] = 'orange';
                                }
                            @endphp
                            @include('shineCompliance.modals.decommission_assessment', $decommissionModalOption)
                        @else
                            <a href="" data-toggle="modal" data-target="#recommission_fire_exit" style="text-decoration: none">
                                <button type="button" class="btn light_grey_gradient_button fs-8pt">
                                    <strong>{{ __('Recommission') }}</strong>
                                </button>
                            </a>
                            @php
                                $recommissionModalOption = [
                                        'modal_id' => 'recommission_fire_exit',
                                        'header' => 'Recommission Fire Exit',
                                        'reference' => $fireExit->reference ?? '',
                                        'url' => route('shineCompliance.assessment.recommission.fire_exit', ['id' => $fireExit->id]),
                                    ];
                                if ($fireExit->assess_id) {
                                    $recommissionModalOption['color'] = 'orange';
                                }
                            @endphp
                            @include('shineCompliance.modals.common_recommission_assessment',$recommissionModalOption)
                        @endif
                    @endif
                @endif
            @endif
        </div>
    </div>
@endsection

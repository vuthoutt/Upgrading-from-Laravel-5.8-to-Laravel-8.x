@extends('shineCompliance.layouts.app')
@section('content')
    @if($vehicleParking->assess_id == 0)
        @include('shineCompliance.partials.nav',['breadCrumb' => 'register_vehicle_parking_detail', 'color' => 'red', 'data'=> $vehicleParking])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_vehicle_parking_detail', 'color' => 'orange', 'data'=> $vehicleParking])
    @endif
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">{{ $vehicleParking->reference }} - Vehicle Parking Details</h3>
        </div>
        @if($vehicleParking->assess_id == 0)
            @include('shineCompliance.assemblyPoint._assembly_button', [
                'backRoute' =>  route('shineCompliance.property.parking',['property_id' => $vehicleParking->property_id]) ])
        @else
            @include('shineCompliance.assemblyPoint._assembly_button', [
                        'backRoute' =>  route('shineCompliance.assessment.show',['assessment_id' => $vehicleParking->assess_id]) ])
        @endif
        <div class="main-content">
            @include('shineCompliance.forms.form_text',['title' => 'Reference:', 'data' => $vehicleParking->reference ])
            @include('shineCompliance.forms.form_text',['title' => 'Vehicle Parking Name:', 'data' => $vehicleParking->name ])
            @include('shineCompliance.forms.form_text',['title' => 'Area/floor:', 'data' => $vehicleParking->area->area_reference ?? 'N/A'])
            @include('shineCompliance.forms.form_text',['title' => 'Room/location:', 'data' => $vehicleParking->location->location_reference ?? 'N/A'])
            @include('shineCompliance.forms.form_text',['title' => 'Accessibility:', 'data' => !empty($vehicleParking->accessibility) ? 'Yes' : 'No' ])
            @if($vehicleParking->accessibility == 0)
                @include('shineCompliance.forms.form_text',['title' => 'Reason NA:', 'data' => $vehicleParking->reasonNotAccessible->description ?? '' ])
            @endif
            @if($vehicleParking->decommissioned == 1)
                @include('shineCompliance.forms.form_text',['title' => 'Reason:', 'data' => $vehicleParking->reasonDecommission->description ?? ''])
            @endif
            <div class="row">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Vehicle Parking Photo</label>
                <div class="col-md-6 form-input-text" >
                    <img class="image-item" src="{{ CommonHelpers::getFile($vehicleParking->id, VEHICLE_PARKING_PHOTO) }}">
                </div>
            </div>
            @include('shineCompliance.forms.form_text',['title' => 'Vehicle Parking Comments:', 'data' => $vehicleParking->comment ])
        </div>
        <div class="mt-3 pb-2">
            @if($vehicleParking->is_locked && ($vehicleParking->assess_id == 0 || $vehicleParking->assess_id != 0 && $vehicleParking->assessment->status == ASSESSMENT_STATUS_LOCKED))
                <div class="spanWarningSurveying">
                    <strong><em>Vehicle Parking is view only while assessment is in progress</em></strong>
                </div>
            @else
                @if($vehicleParking->assess_id != 0 && $vehicleParking->assessment->status == ASSESSMENT_STATUS_COMPLETED)
                    <div class="spanWarningSurveying">
                        <strong><em>Vehicle Parking is view only because technical activity is complete</em></strong>
                    </div>
                @else
                    @if($can_update)
                        @if($vehicleParking->decommissioned == 0)
                            @if($vehicleParking->is_locked == SURVEY_UNLOCKED)
                                <a href="{{ route('shineCompliance.assessment.get_edit_vehicle_parking',['vehicle_parking_id' => $vehicleParking->id]) }}" style="text-decoration: none">
                                    <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                                        <strong>{{ __('Edit') }}</strong>
                                    </button>
                                </a>
                            @endif
                            <a href="" data-toggle="modal" data-target="#decommission_vehicle_parking" style="text-decoration: none">
                                <button type="button" class="btn light_grey_gradient_button fs-8pt">
                                    <strong>{{ __('Decommission') }}</strong>
                                </button>
                            </a>
                            @php
                                $decommissionModalOption = [
                                        'modal_id' => 'decommission_vehicle_parking',
                                        'header' => 'Decommission Vehicle Parking',
                                        'name' => 'decommission_reason',
                                        'decommission_type' => (isset($vehicleParking->assessment->classification) and ($vehicleParking->assessment->classification == ASSESSMENT_FIRE_TYPE)) ? 'assessment_fire' : 'assessment_water',
                                        'reference' => $vehicleParking->reference ?? '',
                                        'url' => route('shineCompliance.assessment.decommission.vehicle_parking', ['id' => $vehicleParking->id]),
                                    ];
                                if ($vehicleParking->assess_id) {
                                    $decommissionModalOption['color'] = 'orange';
                                }
                            @endphp
                            @include('shineCompliance.modals.decommission_assessment', $decommissionModalOption)
                        @else
                            <a href="" data-toggle="modal" data-target="#recommission_vehicle_parking" style="text-decoration: none">
                                <button type="button" class="btn light_grey_gradient_button fs-8pt">
                                    <strong>{{ __('Recommission') }}</strong>
                                </button>
                            </a>
                            @php
                                $recommissionModalOption = [
                                    'modal_id' => 'recommission_vehicle_parking',
                                    'header' => 'Recommission Vehicle Parking',
                                    'reference' => $vehicleParking->reference ?? '',
                                    'url' => route('shineCompliance.assessment.recommission.vehicle_parking', ['id' => $vehicleParking->id]),
                                ];
                                if ($vehicleParking->assess_id) {
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

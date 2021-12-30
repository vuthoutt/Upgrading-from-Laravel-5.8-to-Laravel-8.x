@extends('shineCompliance.layouts.app')
@section('content')
    @if($hazard->assess_id == 0)
        @include('shineCompliance.partials.nav',['breadCrumb' => 'register_hazard_detail', 'color' => 'red', 'data'=> $hazard])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_hazard_detail', 'color' => 'orange', 'data'=> $hazard])
    @endif

    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="mb-4 title-row">{{$hazard->name ?? ''}}</h3>
        </div>
        @include('shineCompliance.forms.form_text',['title' => 'Hazard Reference:', 'data' => $hazard->name ?? '' ])
        @include('shineCompliance.forms.form_text',['title' => 'Hazard Type:', 'data' => $hazard->hazardType->description ?? '' ])
        @if($hazard->decommissioned == HAZARD_DECOMMISSION)
            @include('shineCompliance.forms.form_text',['title' => 'Decommissioned Reason:', 'data' => $hazard->decommissionReason->description ?? '' ])
            @include('shineCompliance.forms.form_text',['title' => 'Linked Project:', 'data' => $hazard->linkedProject->reference ?? '' ])
        @endif
        @if($hazard->assess_type == ASSESSMENT_FIRE_TYPE)
            @include('shineCompliance.forms.form_text',['title' => 'Date Created:', 'data' => $hazard->created_date ?? '' ])
        @endif
        @if($hazard->type == HAZARD_TYPE_INACCESSIBLE_ROOM)
            @include('shineCompliance.forms.form_text',['title' => 'Reason For Inaccessibility:', 'data' => ($hazard->inaccessReason->description ?? '') . ($hazard->reason == HAZARD_TYPE_INACCESSIBLE_ROOM ? ", ". ($hazard->reason_other ?? '') : '')])
        @endif
        @include('shineCompliance.forms.form_text',['title' => 'Floor:', 'data' => $hazard->area->title_presentation ?? 'N/A' ])
        @include('shineCompliance.forms.form_text',['title' => 'Room:', 'data' => $hazard->location->title_presentation ?? 'N/A' ])
        @if($hazard->assess_type != ASSESSMENT_FIRE_TYPE)
            @include('shineCompliance.forms.form_text',['title' => 'Specific Location:', 'data' => $hazard->specificLocationView->specific_location ?? '' ])
        @endif
        @include('shineCompliance.forms.form_text',['title' => 'Hazard Potential:', 'data' => $hazard->hazardPotential->description ?? '' ])
        @include('shineCompliance.forms.form_text',['title' => 'Likelihood of Harm:', 'data' => $hazard->hazardLikelihoodHarm->description ?? '' ])
        <div class="row">
            <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >{{ 'Overall '.ucfirst($assessment->assess_type ?? '').' Risk Assessment:' }}</label>
            <div class="col-md-6 form-input-text" >
                <span  class="badge {{ \CommonHelpers::getTotalHazardText($hazard->total_risk)['color'] }}" id="risk-color" style="width: 30px; color: {{\CommonHelpers::getTotalRiskHazardText($hazard->total_risk)['color']}} !important;">
                    {{ $hazard->total_risk ?? '' }}
                </span>
                <span>{{ \CommonHelpers::getTotalHazardText($hazard->total_risk)['risk'] }}</span>
            </div>
        </div>
{{--        @if((request()->has('assess_type') && request()->assess_type != ASSESSMENT_FIRE_TYPE)--}}
{{--                || ($assessment && $assessment->classification != ASSESSMENT_FIRE_TYPE))--}}
            @include('shineCompliance.forms.form_text',['title' => 'Extent:', 'data' => $hazard->extent . ' ' . ($hazard->hazardMeasurement->description ?? '') ])

{{--        <div class="row {{ $class_other ?? '' }}" id="{{ $id ?? '' }}-form">--}}
{{--            <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >Photography Override</label>--}}
{{--            <div class="col-md-6 form-input-text" >--}}
{{--                @if(isset($hazard->photo_override) && ($hazard->photo_override != 0) )--}}
{{--                    <span>Yes</span>--}}
{{--                @else--}}
{{--                    <span>No</span>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}
        @include('shineCompliance.forms.form_yes_no',['title' => 'Photography Override:', 'data' => $hazard->photo_override ?? ''])
{{--        @endif--}}
        @if(!$hazard->photo_override)
            <div class="row offset-top40" id="img-hazard-on">
                <div class="col-md-3 mr-4">
                    <div style="max-height: 450px;">
                        <div class="col-md-12 client-image-show mb-3">
                            <label class="col-form-label text-md-left font-weight-bold" >Location:</label>
                        </div>
                        <div class="col-md-12 client-image-show mb-3">
                            <img class="image-item" src="{{ ComplianceHelpers::getFileImage($hazard->id, HAZARD_LOCATION_PHOTO) }}">
                        </div>
                    </div>
                    <div class="col-md-12 client-image-show mb-3">
                        <a title="Download Hazard Location Image" href="{{ route('retrive_image',['type'=>  HAZARD_LOCATION_PHOTO ,'id'=> $hazard->id ]) }}" class="btn download-btn light_grey_gradient_button" style="min-width: 40px"><i class="fa fa-download"></i></a>
                    </div>
                </div>
                <div class="col-md-3 mr-4">
                    <div style="max-height: 450px;">
                        <div class="col-md-12 client-image-show mb-3">
                            <label class="col-form-label text-md-left font-weight-bold" >Hazard:</label>
                        </div>
                        <div class="col-md-12 client-image-show mb-3">
                            <img class="image-item" src="{{ ComplianceHelpers::getFileImage($hazard->id, HAZARD_PHOTO) }}">
                        </div>
                    </div>
                    <div class="col-md-12 client-image-show mb-3">
                        <a title="Download Asbestos Hazard Image" href="{{ route('retrive_image',['type'=>  HAZARD_PHOTO ,'id'=> $hazard->id ]) }}" class="btn download-btn light_grey_gradient_button" style="min-width: 40px"><i class="fa fa-download"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    {{--                @if(\CommonHelpers::checkFile($hazard->id, ITEM_PHOTO_ADDITIONAL))--}}
                    <div style="max-height: 450px;">
                        <div class="col-md-12 client-image-show mb-3">
                            <label class="col-form-label text-md-left font-weight-bold" >Additional:</label>
                        </div>
                        <div class="col-md-12 client-image-show mb-3">
                            <img class="image-item" src="{{ ComplianceHelpers::getFileImage($hazard->id, HAZARD_ADDITION_PHOTO) }}">
                        </div>
                    </div>
                    <div class="col-md-12 client-image-show mb-3">
                        <a title="Download Hazard Additional Image" href="{{ route('retrive_image',['type'=>  HAZARD_ADDITION_PHOTO ,'id'=> $hazard->id ]) }}" class="btn download-btn light_grey_gradient_button" style="min-width: 40px"><i class="fa fa-download"></i></a>
                    </div>
                    {{--                @endif--}}
                </div>
            </div>
        @else
            <div class="row offset-top40" id="img-hazard-off">
                <div class="col-md-3 mr-4">
                    <div style="max-height: 450px;">
                        <div class="col-md-12 client-image-show mb-3">
                            <label class="col-form-label text-md-left font-weight-bold" >Location:</label>
                        </div>
                        <div class="col-md-12 client-image-show mb-3">
                            <img class="image-item" src="{{ ComplianceHelpers::getFileImage(0, HAZARD_LOCATION_PHOTO) }}">
                        </div>
                    </div>
                    <div class="col-md-12 client-image-show mb-3">
                        <a title="Download Hazard Location Image" href="{{ route('retrive_image',['type'=>  HAZARD_LOCATION_PHOTO ,'id'=> $hazard->id ]) }}" class="btn download-btn light_grey_gradient_button" style="min-width: 40px"><i class="fa fa-download"></i></a>
                    </div>
                </div>
                <div class="col-md-3 mr-4">
                    <div style="max-height: 450px;">
                        <div class="col-md-12 client-image-show mb-3">
                            <label class="col-form-label text-md-left font-weight-bold" >Hazard:</label>
                        </div>
                        <div class="col-md-12 client-image-show mb-3">
                            <img class="image-item" src="{{ ComplianceHelpers::getFileImage(0, HAZARD_PHOTO) }}">
                        </div>
                    </div>
                    <div class="col-md-12 client-image-show mb-3">
                        <a title="Download Asbestos Hazard Image" href="{{ route('retrive_image',['type'=>  HAZARD_PHOTO ,'id'=> $hazard->id ]) }}" class="btn download-btn light_grey_gradient_button" style="min-width: 40px"><i class="fa fa-download"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    {{--                @if(\CommonHelpers::checkFile($hazard->id, ITEM_PHOTO_ADDITIONAL))--}}
                    <div style="max-height: 450px;">
                        <div class="col-md-12 client-image-show mb-3">
                            <label class="col-form-label text-md-left font-weight-bold" >Additional:</label>
                        </div>
                        <div class="col-md-12 client-image-show mb-3">
                            <img class="image-item" src="{{ ComplianceHelpers::getFileImage(0, HAZARD_ADDITION_PHOTO) }}">
                        </div>
                    </div>
                    <div class="col-md-12 client-image-show mb-3">
                        <a title="Download Hazard Additional Image" href="{{ route('retrive_image',['type'=>  HAZARD_ADDITION_PHOTO ,'id'=> $hazard->id ]) }}" class="btn download-btn light_grey_gradient_button" style="min-width: 40px"><i class="fa fa-download"></i></a>
                    </div>
                    {{--                @endif--}}
                </div>
            </div>
        @endif
        @include('shineCompliance.forms.form_text',['title' => 'Actions/recommendations:', 'data' => $hazard->action_recommendations ?? '' ])

        @if($hazard->assess_type == ASSESSMENT_FIRE_TYPE)
            @include('shineCompliance.forms.form_text',['title' => 'Action Responsibility:', 'data' =>
            (isset($hazard->actionResponsibility->parents) ?
            ($hazard->actionResponsibility->parents->description ?? '') . ', ' : '') . ($hazard->actionResponsibility->description ?? '')

            ])
        @endif
        <div class="row">
            <label class="col-md-3 col-form-label text-md-left">
                <span class="font-weight-bold"> Comments:</span>
{{--                <a href="" data-toggle="modal" data-target="#item_comment_history">(History)</a>--}}
            </label>
            <div class="col-md-6 form-input-text" >
                {!! $hazard->comment ?? null !!}
            </div>
        </div>
        <div class="mt-4">
            @if($can_update)
                @if($hazard->decommissioned == 0)
                    <a href="{{ route('shineCompliance.assessment.get_edit_hazard', ['hazard_id' => $hazard->id]) }}" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                            <strong>{{ __('Edit') }}</strong>
                        </button>
                    </a>
                    <a href="" data-toggle="modal" data-target="#decommission_hazard" style="text-decoration: none">
                        <button type="button" class="btn light_grey_gradient_button fs-8pt">
                            <strong>{{ __('Decommission') }}</strong>
                        </button>
                    </a>
                    @include('shineCompliance.modals.decommission_hazard',[
                                'color' => $hazard->assess_id > 0 ? 'orange' : 'red',
                                'modal_id' => 'decommission_hazard',
                                'header' => 'Decommission Hazard',
                                'decommission_type' => 'hazard',
                                'name' => 'decommission_reason',
                                'color' => 'light_blue_gradient',
                                'reference' => $hazard->reference ?? '',
                                'projects' => $projects ?? [],
                                'linked_project_id' => $hazard->linked_project_id ?? '',
                                'url' => route('shineCompliance.assessment.decommission.hazard', ['id' => $hazard->id]),
                            ])
                @else
                    <a href="" data-toggle="modal" data-target="#recommission_hazard" style="text-decoration: none">
                        <button type="button" class="btn light_grey_gradient_button fs-8pt">
                            <strong>{{ __('Recommission') }}</strong>
                        </button>
                    </a>
                    @include('shineCompliance.modals.common_recommission_assessment',[
                                'color' => $hazard->assess_id > 0 ? 'orange' : 'red',
                                'modal_id' => 'recommission_hazard',
                                'header' => 'Recommission Hazard',
                                'reference' => $hazard->reference ?? '',
                                'url' => route('shineCompliance.assessment.recommission.hazard', ['id' => $hazard->id]),
                            ])
                @endif
            @endif
        </div>
    </div>
@endsection

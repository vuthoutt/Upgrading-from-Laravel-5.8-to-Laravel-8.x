@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' =>'detail_location', 'color' => 'red', 'data' =>$location ?? ''])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $location->reference ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button', [
            'backRoute' =>  route('shineCompliance.location.list',['area_id' => $location->area_id ?? 0]),
            'route_decommission' =>  route('shineCompliance.location.decommission',['id' => $location->id ?? 0]),
            'decommission' => $location->decommissioned ?? 0,
            'editRoute'  => $can_update ? route('shineCompliance.location.get_edit',['id' => $location->id ?? 0]) : false,
            'recommission_target'  => '#recommission_location',
            'route_decommission'  => '#',
            'data_target'  => '#decommission_location',
            'is_locked' =>  (($location->is_locked == LOCATION_LOCKED)? TRUE : FALSE)
            ])

        <div class="row">
            @include('shineCompliance.properties.partials._location_sidebar',[
                'location_id' => $location->id,
                'image' => \ComplianceHelpers::getFileImage($location->id ?? 0, LOCATION_IMAGE)
                ])

                <div class="col-md-9 pl-0 pr-0" style="padding: 0" >

                    <div class="card-data mar-up">
                        <div class="col-md-6">
                            @if($location->is_locked == LOCATION_LOCKED)
                            <div class="btn-toolbar mb-3" role="toolbar">
                                <button id="danger-button" class="btn btn-danger dropdown-toggle box-shadow--2dp f13"
                                    type="button" data-toggle="dropdown" style="margin-right: 10px">Red Warnings
                                    <span class="caret" style="padding-right: 20px;"></span>
                                </button>
                                <div class="dropdown-menu" style="background-color: #ffe3e6;max-height: 600px;overflow: auto;padding: 0!important;" >
                                        <span class="dropdown-item f13 f13" style="color: #bd2130!important;" >
                                            <strong>Room/location is view only while surveying or assessing is in progress</strong>
                                        </span>
                                        <div class="dropdown-divider" style="border-color: white;padding: 0!important;"></div>
                                </div>
                            </div>
                            @endif
                            <div class="card discard-border-radius">
                                <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                                <div class="card-body" style="padding: 15px;">
                                @include('shineCompliance.forms.form_text_compliance',['title' => 'Shine Reference',
                                        'data' => $location->reference ?? ''
                                        ])
                                @include('shineCompliance.forms.form_text_compliance',['title' => 'Room/Location Reference',
                                        'data' => $location->location_reference ?? ''
                                        ])
                                @include('shineCompliance.forms.form_text_compliance',['title' => 'Room/Location Description',
                                        'data' => $location->description ?? ''
                                        ])
                                <div class="row">
                                    <label class="col-md-6 col-form-label text-md-left" >
                                        <span class="font-weight-bold">Reason:</span>
                                        <a href="" data-toggle="modal" data-target="#location_decommission_comment_history">(History)</a>
                                    </label>
                                    <div class="col-md-6 form-input-text" >
                                        {!! $location->decommissionedReason->description ?? null !!}
                                    </div>
                                </div>

                                @include('shineCompliance.forms.form_text_compliance',['title' => 'Assessed',
                                    'data' => $location->not_assessed == NOT_ASSESSED ? 'Not Assessed' : 'Assessed'
                                    ])
                                @include('shineCompliance.forms.form_text_compliance',['title' => 'Status',
                                        'data' => ($location->decommissioned == 1) ? 'Decommissioned' : 'Live'
                                        ])
                                @include('shineCompliance.forms.form_text_compliance',['title' => 'Accessibility',
                                        'data' => $location->state_text ?? ''  ])
                                @if($location->state != AREA_ACCESSIBLE_STATE)
                                @include('shineCompliance.forms.form_text_compliance',['title' => 'Reason NA',
                                        'data' => \CommonHelpers::getLocationVoidDetails($location->locationInfo->reason_inaccess_key ?? 0, $location->locationInfo->reason_inaccess_other ?? 0 )
                                        ])
                                @endif
                                @include('shineCompliance.forms.form_text_compliance',['title' => 'Comment',
                                        'data' => $location->locationInfo->comments ?? null
                                        ])
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-data mar-up">
                        <div class="col-md-6">
                            <div class="card h-100 discard-border-radius">
                                <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Room/location Void Investigations</strong></div>
                                <div class="card-body" style="padding: 15px;">
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Ceiling Void:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationVoid->ceiling ?? 0, $location->locationVoid->ceiling_other ?? '' ) ])
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Floor Void:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationVoid->floor ?? 0, $location->locationVoid->floor_other ?? '' ) ])
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Cavities:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationVoid->cavities ?? 0, $location->locationVoid->cavities_other ?? '' ) ])
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Risers:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationVoid->risers ?? 0, $location->locationVoid->risers_other ?? '' ) ])
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Ducting:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationVoid->ducting ?? 0, $location->locationVoid->ducting_other ?? '' ) ])
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Boxing:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationVoid->boxing ?? 0, $location->locationVoid->boxing_other ?? '' ) ])
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Pipework:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationVoid->pipework ?? 0, $location->locationVoid->pipework_other ?? '' ) ])
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 discard-border-radius">
                                <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Room/location Construction Details</strong></div>
                                <div class="card-body" style="padding: 15px;">
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Ceiling:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationConstruction->ceiling ?? 0, $location->locationConstruction->ceiling_other ?? '' ) ])
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Walls:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationConstruction->walls ?? 0, $location->locationConstruction->walls_other ?? '' ) ])
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Floor:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationConstruction->floor ?? 0, $location->locationConstruction->floor_other ?? '' ) ])
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Doors:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationConstruction->doors ?? 0, $location->locationConstruction->doors_other ?? '' ) ])
                                    @include('shineCompliance.forms.form_text_compliance',['title' => 'Windows:', 'data' => \CommonHelpers::getLocationVoidDetails($location->locationConstruction->windows ?? 0, $location->locationConstruction->windows_other ?? '' ) ])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
    @include('shineCompliance.modals.decommission_history',[ 'color' => 'red',
                                'modal_id' => 'location_decommission_comment_history',
                                'header' => 'Historical Room/location Comments',
                                'table_id' => 'location_decommission_comment_history_table',
                                'url' => route('comment.decommission', ['category' => 'location']),
                                'data' => $location->decommissionCommentHistory,
                                'id' => $location->id
                                ])

    @if($location->decommissioned == 1)
        @include('shineCompliance.modals.recommission',[ 'color' => 'red',
                                        'modal_id' => 'recommission_location',
                                        'header' => 'Recommission Room/Location',
                                        'decommission_type' => 'location',
                                        'name' => 'location_decommisson_reason_add',
                                        'url' => route('shineCompliance.location.decommission', ['location_id' => $location->id]),
                                        ])
    @else
        @include('shineCompliance.modals.decommission',[ 'color' => 'red',
                                            'modal_id' => 'decommission_location',
                                            'header' => 'Decommission Room/Location',
                                            'decommission_type' => 'location',
                                            'name' => 'location_decommisson_reason_add',
                                            'url' => route('shineCompliance.location.decommission', ['location_id' => $location->id]),
                                            ])
    @endif

@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            $('#filter').click( function(e) {
                $('.add').toggleClass('add-aimation');
            });
            $('#close-form').click( function() {
                $('.add').removeClass('add-aimation');
            });
        });
    </script>
@endpush

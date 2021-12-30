@extends('shineCompliance.layouts.app')

@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'area_detail', 'color' => 'red', 'data' => $area ?? ''])
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">{{ $area->area_reference ?? '' }}</h3>
        </div>
        <div class="main-content mar-up">
            @include('shineCompliance.properties.partials._property_button', [ 'backRoute' => route('shineCompliance.property.list_area', ['property_id' =>$property_id]),
                        'editRoute' => $can_update ? "#edit-area-register-$area->id" : false ,'data_target' =>"#decommission_area",'decommission' => $area->decommissioned,
                        'recommission_target' =>'#recommission_area','route_decommission' => '#','is_locked' => (($area->is_locked == AREA_LOCKED)? TRUE : FALSE) ]
                        )

            <div class="row">
                @include('shineCompliance.properties.area.partials._area_sidebar',['property_id' => $property_id,'area_id' => $area])
                <div class="col-md-9 pl-0 pr-0">
                    <div class="card-data mar-up">
                        <div class="col-md-6">
                            @if($area->is_locked == AREA_LOCKED)
                            <div class="btn-toolbar mb-3" role="toolbar">
                                <button id="danger-button" class="btn btn-danger dropdown-toggle box-shadow--2dp f13"
                                    type="button" data-toggle="dropdown" style="margin-right: 10px">Red Warnings
                                    <span class="caret" style="padding-right: 20px;"></span>
                                </button>
                                <div class="dropdown-menu" style="background-color: #ffe3e6;max-height: 600px;overflow: auto;padding: 0!important;" >
                                        <span class="dropdown-item f13 f13" style="color: #bd2130!important;" ><strong>Area/Floor is view only while surveying or assessing is in progress</strong></span>
                                        <div class="dropdown-divider" style="border-color: white;padding: 0!important;"></div>
                                </div>
                            </div>
                            @endif
                            <div class="card h-100 discard-border-radius">
                                <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                                <div class="card-body " style="padding: 15px;">
                                    @include('shineCompliance.forms.form_property_details',['title' => 'Shine Reference:', 'data'=> $area->reference ?? ''])
                                    @include('shineCompliance.forms.form_property_details',['title' => 'Area/floor Reference:', 'data'=> $area->area_reference ?? ''])
                                    @include('shineCompliance.forms.form_property_details',['title' => 'Area/floor Description:', 'data'=> $area->description ?? ''])
                                    @include('shineCompliance.forms.form_property_details',['title' => 'Accessibility:', 'data'=> $area->state == AREA_ACCESSIBLE_STATE ? 'Accessible' : 'inaccessible' ])
                                    @if($area->state != AREA_ACCESSIBLE_STATE)
                                        @include('shineCompliance.forms.form_property_details',['title' => 'Inaccessible Reason:', 'data'=> $area->reasonArea->description ?? '' ])
                                    @endif
                                    @if($area->reason == OTHER_AREA)
                                        @include('shineCompliance.forms.form_property_details',['title' => 'Comment:', 'data'=> $area->reason_area ?? '' ])
                                    @endif
                                    <div class="row">
                                        <label class="col-md-6 col-form-label text-md-left" >
                                            <span class="font-weight-bold">Reason:</span>
                                            <a href="" data-toggle="modal" data-target="#decommission_reason_history_area">(History)</a>
                                        </label>
                                        <div class="col-md-6 form-input-text" >
                                            {!! $area->decommissionedReason->description ?? null !!}
                                        </div>
                                    </div>
                                    @include('shineCompliance.forms.form_property_details',['title' => 'Data Stamp:', 'data'=> $area_stamping['data_stamping'] ?? 'N/A'])
                                    @include('shineCompliance.forms.form_property_details',['title' => 'Organisation:', 'data'=> $area_stamping['organisation'] ?? 'N/A'])
                                    @include('shineCompliance.forms.form_property_details',['title' => 'Username:', 'data'=> $area_stamping['username'] ?? 'N/A'])
                                    <br>
                                    @include('shineCompliance.forms.form_property_details',['title' => 'Creation Date:', 'data'=>  $area_stamping['data_stamping_create'] ?? 'N/A'])
                                    @include('shineCompliance.forms.form_property_details',['title' => 'Organisation:', 'data'=> $area_stamping['organisation_create'] ?? 'N/A'])
                                    @include('shineCompliance.forms.form_property_details',['title' => 'Username:', 'data'=>  $area_stamping['username_create'] ?? 'N/A'])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('shineCompliance.modals.decommission_history',[ 'color' => 'red',
                                'modal_id' => 'decommission_reason_history_area',
                                'header' => 'Decommission Area/floor Reason',
                                'table_id' => 'area_decommission_history_table',
                                'url' => '',
                                'data' => $area->decommissionCommentHistory,
                                'id' => $area->id
                             ])

    @if($area->decommissioned == 0)
        @include('shineCompliance.modals.decommission',[ 'color' => 'red',
                                            'modal_id' => 'decommission_area',
                                            'header' => 'Decommission Area/floor',
                                            'decommission_type' => 'area',
                                            'name' => 'area_decommisson_reason_add',
                                            'url' => route('shineCompliance.decommission_area', ['area_id' => $area->id]),
                                            ])
    @else
        @include('shineCompliance.modals.recommission',[ 'color' => 'red',
                                        'modal_id' => 'recommission_area',
                                        'header' => 'Recommission Area/floor',
                                        'decommission_type' => 'area',
                                        'name' => 'area_decommisson_reason_add',
                                        'url' => route('shineCompliance.decommission_area', ['area_id' => $area->id]),
                                        ])
    @endif
    @include('shineCompliance.modals.edit_area',['property_id' =>$property_id, 'modal_id' => "edit-area-register-".$area->id ,'area_id'=>$area->id,'action' => 'edit' ,
            'url' => route('shineCompliance.ajax.create_area'), 'data' => $area ,'data_dropdown' => $data_reason ,'unique_value' =>$area->id])
@endsection
@push('javascript')

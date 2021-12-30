@extends('shineCompliance.layouts.app')

@section('content')
@if($assess_view)
    @include('shineCompliance.partials.nav',['breadCrumb' =>'property_assessment_asbestos_view','data' => $assessment, 'color' => 'orange'])
@else
    @include('shineCompliance.partials.nav',['breadCrumb' => ($item->survey_id == 0) ? 'items_detail_compliance' : 'survey_item_asbestos','data' => $item,'color' => ($item->survey_id == 0) ? 'red' : 'orange'])
@endif
<style>
    .card-data-item{
        margin-right: 20px;
    }
    .row p strong{
        color: black;
    }
    .detail-item{
        padding-left: 0px;
        padding-right: 10px;
        margin-top: 10px;
    }
    .overall{
        padding-left: 10px;
        padding-right: 0px;
        margin-top: 10px;
    }
    .risk{
        margin-top: 20px;
    }
    img{
        object-fit:cover !important;
    }
</style>
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $item->name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @if(!$assess_view)
            @if($item->survey_id == 0)
                @include('shineCompliance.properties.partials._property_button',[
                    'backRoute' => route('shineCompliance.item.list',['location_id' => $item->location_id ?? 0]),
                    'recommission_target'  => '#recommission_item',
                    'route_decommission'  => '#',
                    'data_target'  => '#decommission_item',
                    'editRoute' => $can_update ? route('shineCompliance.item.get_edit',['id' => $item->id ?? 0]) : false,
                    'decommission' => $item->decommissioned ?? 0,
                    'is_locked' => (($item->is_locked === ITEM_LOCKED)? TRUE : FALSE),'asbestos' => TRUE
                    ])
             @else
                @include('shineCompliance.properties.partials._property_button',[
                     'backRoute' => route('property.surveys',['id'=> $item->survey_id,'location'=> $item->location_id, 'section' => SECTION_ROOM_LOCATION_SUMMARY ]),
                     'recommission_target'  => '#recommission_item',
                     'route_decommission'  => '#',
                     'data_target'  => '#decommission_item',
                     'editRoute' => $can_update ? route('shineCompliance.item.get_edit',['id' => $item->id ?? 0]) : false,
                     'decommission' => $item->decommissioned ?? 0,
                     'is_locked' => (($item->is_locked === ITEM_LOCKED)? TRUE : FALSE)
                     ])
                @endif
        @endif
        <div class="row ">
            <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                <div style="width:250px; " >
                    <ul class="list-group">
                        <p><strong>Location</strong></p>
                        <div class="list-group-img">
                            <img src="{{ \ComplianceHelpers::getFileImage($item->id, ITEM_PHOTO_LOCATION) }}"  width="100%" height="200px" alt="">
                        </div>
                        <div class="list-group-button">
                            <a title="Download Item Location Image" href="{{ route('retrive_image',['type'=>  ITEM_PHOTO_LOCATION ,'id'=> $item->id ]) }}">
                                <button class="list-group-btn" style="margin-left:0px" title="Download"><i class="fa fa-image fa-2x"></i></button>
                            </a>
                        </div>
                    </ul>
                </div>
            </div>
            <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                <div style="width:250px; " >
                    <ul class="list-group">
                        <p><strong>ACM</strong></p>
                        <div class="list-group-img">
                            <img src="{{ \ComplianceHelpers::getFileImage($item->id, ITEM_PHOTO) }}"  width="100%" height="200px" alt="">
                        </div>
                        <div class="list-group-button">
                            <a title="Download Asbestos Item Image" href="{{ route('retrive_image',['type'=>  ITEM_PHOTO ,'id'=> $item->id ]) }}">
                                <button class="list-group-btn" style="margin-left:0px"  title="Download"><i class="fa fa-image fa-2x"></i></button>
                            </a>
                        </div>
                    </ul>
                </div>
            </div>
            <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                <div style="width:250px; " >
                    <ul class="list-group">
                        <p><strong>Additional</strong></p>
                        <div class="list-group-img">
                            <img src="{{ \ComplianceHelpers::getFileImage($item->id, ITEM_PHOTO_ADDITIONAL) }}"  width="100%" height="200px" alt="">
                        </div>
                        <div class="list-group-button">
                            <a title="Download Asbestos Item Image" href="{{ route('retrive_image',['type'=>  ITEM_PHOTO_ADDITIONAL ,'id'=> $item->id ]) }}">
                                <button class="list-group-btn" style="margin-left:0px" title="Download"><i class="fa fa-image fa-2x"></i></button>
                            </a>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
        @if( $item->state == ITEM_NOACM_STATE)
            <div class="col-md-7 detail-item" >
        @else
            <div class="col-md-6 detail-item" >
        @endif
                <div class="card discard-border-radius">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4;"><strong>Details</strong></div>
                    <div class="card-body">
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Item Name:', 'data' =>  $item->name ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Item ID:', 'data' => $item->reference ?? '' ])
                        <div class="row">
                            <label class="col-md-4 col-form-label text-md-left" >
                                <span class="font-weight-bold">Reason:</span>
                                <a href="" data-toggle="modal" data-target="#item_decommission_comment_history">(History)</a>
                            </label>
                            <div class="col-md-6 form-input-text" >
                                {!! $item->decommissionedReason->description ?? null !!}
                            </div>
                        </div>
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Assessed:', 'data' => $item->not_assessed == NOT_ASSESSED ? 'Not Assessed' : 'Assessed' ])
                        @if($item->not_assessed == NOT_ASSESSED)
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Reason:', 'data' => $item->notAssessedReason->description ?? '' ])
                        @endif
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Item Type:', 'data' => $item->state != ITEM_NOACM_STATE ? 'ACM' : 'No ACM' ])
                        @if( $item->state != ITEM_NOACM_STATE)
                            @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Accessibility:', 'data' => $item->state_text ?? '' ])
                            @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Assessment Type:', 'data' => optional($item->itemInfo)->assessment_type_text ])
                            @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Reason:', 'data' => $reason ])
                        @endif
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Sample/link ID:', 'data' => $item->sample->description ?? "N/A" ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Sample Comment:', 'data' => $item->sample->sampleComment->description ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Area/floor Reference:', 'data' => $item->area->area_reference ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Room/floor Reference:', 'data' => $item->location->location_reference ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Specific Location:', 'data' => $item->specificLocationView->specific_location ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Product/debirs Type:', 'data' => $item->productDebrisView->product_debris ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Asbestos Type:', 'data' => $item->asbestosTypeView->asbestos_type ?? ''])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Extent:', 'data' => (isset($item->itemInfo->extent) and $item->itemInfo->extent) != '' ? (($item->itemInfo->extent ?? '').' '. ($item->extentView->extent ?? '')) : '' ])
                        @include('shineCompliance.forms.form_text_property_detail',['width_label' => 4,'title' => 'Action/recommendation:', 'data' => $item->actionRecommendationView->action_recommendation ?? '' ])
{{--                        @include('forms.form_text_property_detail',['title' => 'Division:','width_label' => 5, 'data' => $property->division->description ?? null ])--}}
                        <div class="row">
                            <label class="col-md-4 col-form-label text-md-left" >
                                <span class="font-weight-bold"> Comments:</span>
                                <a href="" data-toggle="modal" data-target="#item_comment_history">(History)</a>
                            </label>
                            <div class="col-md-6 form-input-text" >
                                {!! $item->itemInfo->comment ?? null !!}
                            </div>
                        </div>
                        <br>
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Data Stamp:', 'data' => $itemStamping['data_stamping'] ?? 'N/A' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Organisation:', 'data' => $itemStamping['organisation'] ?? 'N/A' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Username:', 'data' => $itemStamping['username'] ?? 'N/A' ])
                        <br>
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Creation Date:', 'data' => $itemStamping['data_stamping_create'] ?? 'N/A' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Organisation:', 'data' => $itemStamping['organisation_create'] ?? 'N/A' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Username:', 'data' => $itemStamping['username_create'] ?? 'N/A' ])
                    </div>
                </div>
            </div>
            @if( $item->state != ITEM_NOACM_STATE)
                <div class="col-md-6 overall" >
                <div class="card discard-border-radius ">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Overall Risk Assessment</strong></div>
                    <div class="card-body" >
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold fs-8pt" >Material Risk Assessment:</label>
                            <div class="col-md-5" style="padding-top: 5px">
                                <span class="badge {{ \CommonHelpers::getMasRiskColor($item->total_mas_risk) }}" id="risk-color" style="width: 30px;margin-right: 10px">
                                    {{sprintf("%02d", $item->total_mas_risk)}}
                                </span>
                                <span>{{ \CommonHelpers::getMasRiskText($item->total_mas_risk) }}</span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold fs-8pt" >Priority Risk Assessment:</label>
                            <div class="col-md-5" style="padding-top: 5px">
                                <span class="badge {{ \CommonHelpers::getMasRiskColor($item->total_pas_risk) }}" id="risk-color" style="width: 30px;margin-right: 10px">
                                    {{sprintf("%02d", $item->total_pas_risk)}}
                                </span>
                                <span>{{ \CommonHelpers::getMasRiskText($item->total_pas_risk) }}</span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold fs-8pt" >Overall Risk Assessment:</label>
                            <div class="col-md-5" style="padding-top: 5px">
                          <span class="badge {{ \CommonHelpers::getTotalText($item->total_risk)['color'] ?? '' }}" id="risk-color" style="width: 30px;margin-right: 10px">
                                {{sprintf("%02d", $item->total_risk)}}
                          </span>
                                <span>{{ \CommonHelpers::getTotalText($item->total_risk)['risk'] ?? 0}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card discard-border-radius risk">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Material Risk Assessment</strong></div>
                    <div class="card-body">
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Product Type (a):', 'data' => $item->masProductDebris->getData->score ?? 0 ])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Extent of Damage (b):', 'data' => $item->masDamage->getData->score ?? 0 ])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Surface Treatment (c):', 'data' => $item->masTreatment->getData->score ?? 0 ])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Asbestos Fibre (d):', 'data' => $item->masAsbestos->getData->score ?? 0 ])
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold fs-8pt" >Total:</label>
                            <div class="col-md-5">
                            <span id="total-MRA">
                                {{sprintf("%02d", $item->total_mas_risk)}}
                            </span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold fs-8pt" >Material Risk Assessment:</label>
                            <div class="col-md-5">
                              <span class="badge {{ \CommonHelpers::getMasRiskColor($item->total_mas_risk) }}" style="width: 30px;margin-right: 10px;">
                                {{sprintf("%02d", $item->total_mas_risk)}}
                              </span>
                               <span>{{ \CommonHelpers::getMasRiskText($item->total_mas_risk) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card discard-border-radius risk" >
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4;" ><strong>Priority Risk Assessment</strong></div>
                    <div class="card-body">
                        @include('shineCompliance.forms.form_text_right',['title' => 'Normal Occupancy Activity'])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Primary:', 'data' => $item->pasPrimary->getData->score ?? 0 ])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Secondary:', 'data' => $item->pasSecondary->getData->score ?? 0])

                        @include('shineCompliance.forms.form_text_right',['title' => 'Likelihood of Disturbance'])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Location:', 'data' => $item->pasLocation->getData->score ?? 0 ])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Accessibility:', 'data' => $item->pasAccessibility->getData->score ?? 0 ])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Extent/Amount:', 'data' => $item->pasExtent->getData->score ?? 0 ])

                        @include('shineCompliance.forms.form_text_right',['title' => '  Human Exposure Potential'])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Number:', 'data' => $item->pasNumber->getData->score ?? 0 ])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Frequency:', 'data' => $item->pasHumanFrequency->getData->score ?? 0 ])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Average Time:', 'data' => $item->pasAverageTime->getData->score ?? 0 ])

                        @include('shineCompliance.forms.form_text_right',['title' => '  Maintenance Activity'])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Type:', 'data' => $item->pasType->getData->score ?? 0 ])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Frequency:', 'data' => $item->pasMaintenanceFrequency->getData->score ?? 0 ])
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold fs-8pt" >Total:</label>
                            <div class="col-md-5">
                            <span id="total-PRAs">
                                {{sprintf("%02d", $item->total_pas_risk)}}
                            </span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold fs-8pt" >Priority Risk Assessment:</label>
                            <div class="col-md-5">
                                  <span class="badge {{ \CommonHelpers::getMasRiskColor($item->total_pas_risk) }}" id="risk-color" style="width: 30px;margin-right: 10px">
                                        {{sprintf("%02d", $item->total_pas_risk)}}
                                  </span>
                                <span>{{ \CommonHelpers::getMasRiskText($item->total_pas_risk) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
{{--decomission--}}
    @if($item->decommissioned == 0)
        @include('shineCompliance.modals.decommission',[ 'color' => ($item->survey_id == 0) ? 'red' : 'orange',
                                            'modal_id' => 'decommission_item',
                                            'header' => 'Decommission Item',
                                            'decommission_type' => 'item',
                                            'name' => 'item_decommisson_reason_add',
                                            'url' => route('shineCompliance.item.decommission', ['item_id' => $item->id]),
                                            ])
    @else
        @include('shineCompliance.modals.recommission',[ 'color' => ($item->survey_id == 0) ? 'red' : 'orange',
                                        'modal_id' => 'recommission_item',
                                        'header' => 'Recommission Item',
                                        'decommission_type' => 'item',
                                        'name' => 'item_decommisson_reason_add',
                                        'url' => route('shineCompliance.item.decommission', ['item_id' => $item->id]),
                                        ])
    @endif
{{--reason--}}

    @include('shineCompliance.modals.decommission_history',[ 'color' => ($item->survey_id == 0) ? 'red' : 'orange',
                            'modal_id' => 'item_decommission_comment_history',
                            'header' => 'Historical Room/location Comments',
                            'table_id' => 'item_decommission_comment_history_table',
                            'url' => route('comment.decommission', ['category' => 'item']),
                            'data' => $item->decommissionCommentHistory,
                            'id' => $item->id
                            ])
{{--comment--}}

    @include('shineCompliance.modals.comment_history',[ 'color' => ($item->survey_id == 0) ? 'red' : 'orange',
                                    'modal_id' => 'item_comment_history',
                                    'header' => 'Historical Item Comments',
                                    'table_id' => 'item_comment_history_table',
                                    'url' => route('shineCompliance.comment.item'),
                                    'data' => $item->commentHistory,
                                    'id' => $item->id
                            ])
@endsection
@push('javascript')

@endpush

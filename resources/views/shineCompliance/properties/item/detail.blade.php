@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav', ['color' => 'red'])
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
<div class="container prism-content pad-up">
    <div class="row">
        <h3 style="margin: 0">2474-124</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button',['backRoute' => url()->previous()])

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
                                <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
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
                            <img src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO) }}"  width="100%" height="200px" alt="">
                        </div>
                        <div class="list-group-button">
                            <a title="Download Asbestos Item Image" href="{{ route('retrive_image',['type'=>  ITEM_PHOTO ,'id'=> $item->id ]) }}">
                                <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
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
                            <img src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_ADDITIONAL) }}"  width="100%" height="200px" alt="">
                        </div>
                        <div class="list-group-button">
                            <a title="Download Asbestos Item Image" href="{{ route('retrive_image',['type'=>  ITEM_PHOTO_ADDITIONAL ,'id'=> $item->id ]) }}">
                                <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
                            </a>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 detail-item" >
                <div class="card discard-border-radius">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4;"><strong>Details</strong></div>
                    <div class="card-body">
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Item Name:', 'data' =>  $item->name ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Item ID:', 'data' => $item->reference ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Assessed:', 'data' => $item->not_assessed == NOT_ASSESSED ? 'Not Assessed' : 'Assessed' ])
                        @if($item->not_assessed == NOT_ASSESSED)
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Reason:', 'data' => $item->notAssessedReason->description ?? '' ])
                        @endif
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Type:', 'data' => $item->state_text ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Sample/link ID:', 'data' => $item->sample->description ?? "N/A" ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Sample Comment:', 'data' => $item->sample->sampleComment->description ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Area/floor Reference:', 'data' => $item->area->area_reference ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Room/floor Reference:', 'data' => $item->location->location_reference ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Specific Location:', 'data' => $item->specificLocationView->specific_location ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Product/debirs Type:', 'data' => $item->productDebrisView->product_debris ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Asbestos Type:', 'data' => $item->asbestosTypeView->asbestos_type ?? ''])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Extent:', 'data' => (isset($item->itemInfo->extent) and $item->itemInfo->extent) != '' ? (($item->itemInfo->extent ?? '').' '. ($item->extentView->extent ?? '')) : '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Action/recommendation:', 'data' => $item->actionRecommendationView->action_recommendation ?? '' ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Comments:', 'data' => $item->itemInfo->comment ?? null ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Data Stamp:', 'data' => null ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Organisation:', 'data' => null ])
                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Username:', 'data' => null ])
                    </div>
                </div>
            </div>
            <div class="col-md-6 overall" >
                <div class="card discard-border-radius ">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Overall Risk Assessment</strong></div>
                    <div class="card-body" >
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Material Risk Assessment:</label>
                            <div class="col-md-5" style="padding-top: 5px">
                                <span class="badge {{ \CommonHelpers::getMasRiskColor($item->total_mas_risk) }}" id="risk-color" style="width: 30px;margin-right: 10px">
                                    {{sprintf("%02d", $item->total_mas_risk)}}
                                </span>
                                <span>{{ \CommonHelpers::getMasRiskText($item->total_mas_risk) }}</span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Priority Risk Assessment:</label>
                            <div class="col-md-5" style="padding-top: 5px">
                                <span class="badge {{ \CommonHelpers::getMasRiskColor($item->total_pas_risk) }}" id="risk-color" style="width: 30px;margin-right: 10px">
                                    {{sprintf("%02d", $item->total_pas_risk)}}
                                </span>
                                <span>{{ \CommonHelpers::getMasRiskText($item->total_pas_risk) }}</span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Overall Risk Assessment:</label>
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
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Total:</label>
                            <div class="col-md-5">
                            <span id="total-MRA">
                                {{sprintf("%02d", $item->total_mas_risk)}}
                            </span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Material Risk Assessment:</label>
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
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Frequency:', 'data' => $item->pasNumber->getData->score ?? 0 ])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Average Time:', 'data' => $item->pasAverageTime->getData->score ?? 0 ])

                        @include('shineCompliance.forms.form_text_right',['title' => '  Maintenance Activity'])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Type:', 'data' => $item->pasType->getData->score ?? 0 ])
                        @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Frequency:', 'data' => $item->pasMaintenanceFrequency->getData->score ?? 0 ])
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Total:</label>
                            <div class="col-md-5">
                            <span id="total-PRAs">
                                {{sprintf("%02d", $item->total_pas_risk)}}
                            </span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Priority Risk Assessment:</label>
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
        </div>
    </div>
</div>

@endsection
@push('javascript')

@endpush

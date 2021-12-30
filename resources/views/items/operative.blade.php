@extends('layouts.app')
@section('content')
    @include('partials.nav', ['breadCrumb' => ($item->survey_id == 0) ? 'properties_item' : 'survey_item','data' => $item,'color' => ($item->survey_id == 0) ? 'red' : 'orange'])

    <div class="container prism-content">



        @php
        $display = CommonHelpers::getTotalRiskText($item->total_risk);
        @endphp
        <div style="border-bottom:1px #dddddd solid;text-align: center;">
            <img width="150px" src="{{ CommonHelpers::getFile(1, CLIENT_LOGO) }}" alt="{{ env('APP_DOMAIN') ?? 'GSK' }}" style="margin-bottom: 30px;"/>
        </div>
        @if($item)
            <h3 class="offset-top20 offset-bottom40">{!! $item->name !!}</h3>

            @if($asbestos_type_warning)
            <div class="offset-top20 notification-lsop-warning">
                <strong><em>Representative Sample Only – please seek Asbestos Team Advise</em></strong>
            </div>
            @endif
            <!-- list property operative -->
            <div class="container">
                    @php
                        // merger item from survey
                        $count = 0;
                    @endphp
                    <div class="row ">
                        <div class="property-opt row"  style="width: 100%; margin-left:0px!important;padding-bottom:10px">
                            <!-- update link item normal -->
                            {{-- <a href="javascript:void(0)" style="text-decoration: none;"> --}}
                                <div class="col-md-7">
                                    <div class="unit-operative">
                                        <div class="row item-operative" style="margin-left: 0px">
                                            <div class="col-4 client-image-show">
                                                <img src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO) }}" class="image-item" alt="...">
                                            </div>
                                            <div class="col-4 client-image-show" >
                                                <img src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_LOCATION) }}" class="image-item" alt="...">
                                            </div>
                                             @if(\CommonHelpers::checkFile($item->id, ITEM_PHOTO_ADDITIONAL))
                                            <div class="col-4 client-image-show">
                                                <img src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_ADDITIONAL) }}" class="image-item" alt="...">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="property-opt-des" style="background: {{$display['bg_color']}}; color: {{$display['color']}};">
                                                <strong class="">{{$display['risk']}}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="unit-operative offset-top20 h-auto">
                                        <div class="row item-operative">
                                            <label  class="col-4 col-form-label font-weight-bold text-body">Item ID :</label>
                                            <div class="col-8">
                                                <p class="form-control-plaintext">
                                                    @if(isset($item->sample))
                                                        @if($item->record_id == $item->sample->original_item_id)
                                                            {!! $item->name . "(OS)" !!}
                                                        @else
                                                            {!! $item->name . "(VRS)" !!}
                                                        @endif
                                                    @else
                                                        {!! $item->name !!}
                                                    @endif</p>
                                            </div>
                                        </div>
                                        @if(isset($item->sample))
                                        <div class="row item-operative">
                                            <label  class="col-4 col-form-label font-weight-bold text-body">Sample/link ID:</label>
                                            <div class="col-8">
                                            <p class="form-control-plaintext">
                                                {{$item->sample->description ?? ''}}
                                            </p>
                                            </div>
                                        </div>
                                         @endif
                                        <div class="row item-operative">
                                            <label  class="col-4 col-form-label font-weight-bold text-body">Property Name:</label>
                                            <div class="col-8">
                                                <p class="form-control-plaintext">{!! $item->property->name ?? 'N/A' !!}</p>
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label  class="col-4 col-form-label font-weight-bold text-body">Area/Floor:</label>
                                            <div class="col-8">
                                                <p class="form-control-plaintext">{!! $item->area->area_reference ?? 'N/A' !!}</p>
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label  class="col-4 col-form-label font-weight-bold text-body">Room/Location:</label>
                                            <div class="col-8">
                                                <p class="form-control-plaintext">{!! $item->location->location_reference ?? 'N/A' !!}</p>
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label  class="col-4 col-form-label font-weight-bold text-body">Room/Location Description:</label>
                                            <div class="col-8">
                                                <p class="form-control-plaintext">{!! $item->location->description ?? 'N/A' !!}</p>
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label  class="col-4 col-form-label font-weight-bold text-body">Specific Location:</label>
                                            <div class="col-8">
                                                <p class="form-control-plaintext">{{trim($specificLocation) ? $specificLocation : 'N/A'}}</p>
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label class="col-4 col-form-label font-weight-bold text-body">Product/debris Type:</label>
                                            <div class="col-8">
                                                <p class="form-control-plaintext">{{$item->productDebrisView->product_debris ?? 'N/A'}}</p>
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label  class="col-4 col-form-label font-weight-bold text-body">Asbestos Type:</label>
                                            <div class="col-8">
                                                <p class="form-control-plaintext">{{$item->asbestosTypeView->asbestos_type ?? 'N/A'}}
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label class="col-4 col-form-label font-weight-bold text-body">Extent:</label>
                                            <div class="col-8">
                                                <p class="form-control-plaintext">{{ (isset($item->itemInfo->extent) and $item->itemInfo->extent) != '' ? (($item->itemInfo->extent ?? '').' '. ($item->extentView->extent ?? '')) : ''}}</p>
                                            </div>
                                        </div>

                                        @if ($item->state != ITEM_NOACM_STATE)
                                        <div class="row item-operative">
                                            <label  class="col-4 col-form-label font-weight-bold text-body">Action/recommendations:</label>
                                            <div class="col-8">
                                                <p class="form-control-plaintext">{{  $item->actionRecommendationView->action_recommendation ?? ''}}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <?php
                                    $item_data_stamping = CommonHelpers::get_data_stamping($item);
                                    ?>
                                    <div style="margin-top: 15px">
                                        <div class="row item-operative">
                                            <label class="col-4 col-form-label font-weight-bold text-body">Data Stamp:</label>
                                            <div class="col-8">
                                            <p class="form-control-plaintext"><?= $item_data_stamping['data_stamping']; ?></p>
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label class="col-4 col-form-label font-weight-bold text-body">Organisation:</label>
                                            <div class="col-8">
                                            <p class="form-control-plaintext"><?= $item_data_stamping['organisation']; ?></p>
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label class="col-4 col-form-label font-weight-bold text-body">Username:</label>
                                            <div class="col-8">
                                            <p class="form-control-plaintext"><?= $item_data_stamping['username']; ?></p>
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label class="col-4 col-form-label font-weight-bold text-body">Creation Date:</label>
                                            <div class="col-8">
                                            <p class="form-control-plaintext"><?= $item_data_stamping['data_stamping_create']; ?></p>
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label class="col-4 col-form-label font-weight-bold text-body">Organisation:</label>
                                            <div class="col-8">
                                            <p class="form-control-plaintext"><?= $item_data_stamping['organisation_create']; ?></p>
                                            </div>
                                        </div>
                                        <div class="row item-operative">
                                            <label class="col-4 col-form-label font-weight-bold text-body">Username:</label>
                                            <div class="col-8">
                                            <p class="form-control-plaintext"><?= $item_data_stamping['username_create']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="unit-operative offset-top20 h-auto">
                                        <div >
                                            <div style="margin-top:0; padding-top:0;"><strong>Material Assessment</strong></div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Product Type (a) </p>
                                                <p style="display:inline-block;width:23%;">
                                                    {{isset($item->product_type) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ? 'N/A' : $item->product_type ) : 0}}
                                                </p>
                                            </div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Extent of Damage (b) </p>
                                                <p style="display:inline-block;width:23%;">
                                                    {{isset($item->extend_damage) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ? 'N/A' : $item->extend_damage ) : 0}}
                                                </p>
                                            </div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Surface Treatment (c) </p>
                                                <p style="display:inline-block;width:23%;">
                                                    {{isset($item->surface_treatment) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ? 'N/A' : $item->surface_treatment ) : 0}}
                                                </p>
                                            </div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Asbestos Fibre (d) </p>
                                                <p style="display:inline-block;width:23%;">
                                                    {{isset($item->asbestos_fibre) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ? 'N/A' : $item->asbestos_fibre ) : 0}}
                                                </p>
                                            </div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Total (a+b+c+d) </p>
                                                <p style="display:inline-block;width:23%;">
                                                    {{sprintf("%02d",$item->total_mas_risk)}}
                                                </p>
                                            </div>

                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Material Risk Assessment </p>
                                                <p style="display:inline-block;width:23%;">
                                                    <img src="{{CommonHelpers::getAssetFile('img/'.CommonHelpers::getMasRiskColor($item->total_mas_risk).'-block.png', false)}}"
                                                         width="10" alt=""/> {{CommonHelpers::getMasRiskText($item->total_mas_risk)}}
                                                </p>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="item-operative-score">
                                            <div ><strong>Priority Assessment</strong></div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Normal Occupant Activity (e) </p>
                                                <p style="display:inline-block;width:23%;">
                                                    {{$item->primary ?? 0}}
                                                </p>
                                            </div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Likelihood of Disturbance (f) </p>
                                                <p style="display:inline-block;width:23%;">
                                                    {{$item->likelihood ?? 0}}
                                                </p>
                                            </div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Human Exposure Potential (g) </p>
                                                <p style="display:inline-block;width:23%;">
                                                    {{$item->human_exposure_potential ?? 0}}
                                                </p>
                                            </div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Maintenance Activity (h) </p>
                                                <p style="display:inline-block;width:23%;">
                                                    {{$item->maintenance_activity ?? 0}}
                                                </p>
                                            </div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Total (e+f+g+h) </p>
                                                <p style="display:inline-block;width:23%;">
                                                    {{sprintf("%02d", $item->total_pas_risk)}}
                                                </p>
                                            </div>

                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Priority Risk Assessment </p>
                                                <p style="display:inline-block;width:23%;">
                                                    <img src="{{CommonHelpers::getAssetFile('img/'.CommonHelpers::getMasRiskColor($item->total_pas_risk).'-block.png', false)}}"
                                                         width="10" alt=""/> {{CommonHelpers::getMasRiskText($item->total_pas_risk)}}
                                                </p>
                                            </div>
                                        </div>
                                        <br>
                                        <div >
                                            <div><strong>Overall Assessment</strong></div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Total (a+b+c+d+e+f+g+h) </p>
                                                <p style="display:inline-block;width:23%;">
                                                    {{sprintf("%02d", $item->total_risk)}}
                                                </p>
                                            </div>
                                            <div class="item-operative-score">
                                                <p style="display:inline-block;width:58%;">
                                                    Overall Risk Assessment </p>
                                                <p style="display:inline-block;width:23%;">
                                                    <img src="{{CommonHelpers::getAssetFile('img/'.CommonHelpers::getTotalText($item->total_risk)['color'].'-block.png', false)}}" width="10"
                                                         alt=""/> {{CommonHelpers::getTotalText($item->total_risk)['risk']}}
                                                </p>
                                            </div>
                                        </div>

                                        <br>
                                        <div class="item-operative-score">
                                            <div style="display:inline-block;width:58%;"><strong>Comments</strong></div>
                                            <div style="display:inline-block;width:58%;">
                                                <p >
                                                    {!! isset($item->itemInfo->comment) ? nl2br($item->itemInfo->comment) : 'No Comments' !!}
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {{-- </a> --}}
                        </div>
                    </div>



            </div>
        @endif
        @include('vendor.pagination.simple-bootstrap-4-customize')
    </div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            $(".name").each(function(){
                var showChar = 37;
                var text = $(this).text();

                if(text.length > 37){
                    text = text.substring(0, 36) + " ...";
                }
                $(this).text(text);
            });
        });
    </script>
@endpush

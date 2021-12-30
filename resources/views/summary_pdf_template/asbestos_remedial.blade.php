<!DOCTYPE HTML>
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $property->property_reference ?? ''}}</title>
    <style type="text/css">
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 10pt;
            /*padding: 10px 30px 10px 20px;*/
            color: #666;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #333;
        }

        h2 {
            font-size: 1.5em;
        }

        .topTitle {
            font-size: 1.17em;
            font-weight: bold;
            color: #333;
        }

        .topTitleSmall {
            font-size: 1.11em;
            color: #333;
            font-weight: bold;
        }

        .topTitleBig {
            font-size: 1.5em;
            color: #333;
            font-weight: bold;
        }

        p {
            margin: 1px 0;
            text-align: justify;
        }

        .row {
            padding: 1px 0;
            margin: 0 0 0 0;
        }

        .row p {
            padding: 1px 0;
        }

        table {
            width: 99%;
            margin: 30px 0 20px 0;
        }

        table thead th {
            vertical-align: bottom;
            text-align: center;
            background: #e2e2e2;
        }

        table th, table td {
            padding: 4px;
            line-height: 15px;
            text-align: left;
            vertical-align: top;
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-row-group
        }

        tr {
            page-break-inside: avoid
        }

        .page {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        @media print {
            .element-that-contains-table {
                overflow: visible !important;
            }
        }
    </style>
</head>
<body>
<div id="container" style="width: 859px">
    <div style="page-break-before: always;"></div>
    <div id="excutive-summary" style="margin-top: 30px;">
        <h2>Executive Summary</h2>

        <h3 style="margin-top:20px;margin-bottom:0;">Introduction</h3>
        {!! $reason ?? '' !!}

        <h3 style="margin-top:20px;margin-bottom:0;">Summary of Remedial or Removal Works</h3>
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:20px;margin-bottom:0;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size: 10pt;">
            <thead>
            <tr>
                <th style="width:100px;">Item</th>
                <th style="width:80px;">Sample</th>
                <th style="width:140px;">Product/debris Type</th>
                <th style="width:65px;">Area/floor</th>
                <th style="width:100px;">Room/location</th>
                <th style="width:200px;">Action/recommendations</th>
            </tr>
            </thead>
            <tbody>

            @if (count($items) > 0)
                @php
                // Sort Array
                $itemsRemoval = $items;
                usort($itemsRemoval, function ($a, $b) {
                    return strcmp($a->id, $b->id);
                });
                @endphp
                @foreach ($itemsRemoval as $item)
                    <tr>
                        <td style="width:100px;">{{ $item->id }}</td>
                        <td style="width:80px;">{{ $item->sample->reference ?? '' }}</td>
                        <td style="width:140px;">{{ $item->productDebrisView->product_debris ?? '' }}</td>
                        <td style="width:65px;">{{ $item->area->area_reference ?? '' }}</td>
                        <td style="width:100px;">{{ $item->location->location_reference ?? '' }}</td>
                        <td style="width:200px;">{{ $item->actionRecommendationView->action_recommendation ?? '' }}</td>
                    </tr>

                @endforeach
            @else
                <tr><td colspan='6'>No Remediation & Removal Works Required</td></tr>
            @endif
            </tbody>
        </table>

    </div><!--Executive Summary page 03 -->


    <div style="page-break-before: always;"></div>
    <div id="property-details" style="margin-top: 30px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size: 10pt;">
        <h2>Property Details</h2>

        <div style="width:99%;">
            <h3 style="margin-top:30px;">Property Construction Details</h3>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Primary Use: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{ \CommonHelpers::getProgrammeType( $property->propertySurvey->asset_use_primary  ?? null,  $property->propertySurvey->asset_use_primary_other ?? null, 'primary' ) }}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Secondary Use: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{ \CommonHelpers::getProgrammeType( $property->propertySurvey->asset_use_secondary ?? null,  $property->propertySurvey->asset_use_secondary_other ?? null, 'primary' )}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Date of Construction: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$property->propertySurvey->construction_age ?? ''}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Construction Type: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$property->propertySurvey->construction_type ?? ''}}
                </p>
            </div>

            <div class="row" style="margin-top:10px">
                <p style="display:inline-block;width:49%;text-align:left;">
                    No. Floors: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{ \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_floors ?? '')}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    No. Staircases: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{\CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_staircases ?? null)}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    No. Lifts: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{\CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_lifts ?? null)}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Net Area per Floor: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{\CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_net_area ?? null)}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Gross Area: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{\CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_gross_area ?? null)}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;vertical-align: top;">
                    Comments: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {!! nl2br($property->comments ?? null)  !!}
                </p>
            </div>
        </div>

    </div><!--Property Details PAGE 06-->
     @php $location_temp = []; @endphp
    @foreach ($items as $item)
    @php
        $location = $item->location;
    @endphp
        <div style="padding-top:40px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size: 10pt;">
            @if(!in_array($location->id, $location_temp))
            <div style="display:inline-block;width:36%">
                <div class="row">
                    <div style="display:inline-block;"><img height="200" src="{{ CommonHelpers::getFile($location->id, LOCATION_IMAGE, $is_pdf) }}"/>
                    </div>
                </div>
                <div class="row">
                    <div style="display:inline-block;width:99%; {{ isset($locationDetailMargin) ? "margin-top:20px;" : "" }}">
                        <div class="row">
                            <div class="topTitleSmall">Room/location Details</div>
                            <div class="row">
                                <p style="display:inline-block;width:49%;text-align:left;">Room/location Reference:</p>

                                <p style="display:inline-block;width:49%;text-align:left;">{{ $location->location_reference ?? '' }}</p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:49%;text-align:left;">Room/location Description:</p>

                                <p style="display:inline-block;width:49%;text-align:left;">{{ $location->description ?? '' }}</p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:49%;text-align:left;">Area/floor Reference:</p>

                                <p style="display:inline-block;width:49%;text-align:left;">{{ $location->area->area_reference ?? '' }}</p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:49%;text-align:left;">Area/floor Description:</p>

                                <p style="display:inline-block;width:49%;text-align:left;">{{ $location->area->description ?? '' }}</p>
                            </div>
                            <div class="row">
                                @switch ($location->state)
                                    @case(LOCATION_STATE_ACCESSIBLE)
                                        <p style="display:inline-block;width:49%;text-align:left;">Accessibility:</p><p style="display:inline-block;width:49%;text-align:left;">&nbsp;Accessible</p>
                                        @break
                                    @case(LOCATION_STATE_INACCESSIBLE)
                                        <p style="display:inline-block;width:49%;text-align:left;">Accessibility:</p>
                                        <p style="display:inline-block;width:49%;text-align:left;">&nbsp;Inaccessible</p>
                                        <br/>
                                        <p style="display:inline-block;width:49%;text-align:left;">Reason for No Access:</p>
                                        <p style="display:inline-block;width:49%;text-align:left;">&nbsp;{{\CommonHelpers::getLocationVoidDetails(optional($location->locationInfo)->reason_inaccess_key, optional($location->locationInfo)->reason_inaccess_other )}}</p>
                                        @break
                                    @case(LOCATION_STATE_NO_ACM)
                                        <p style="display:inline-block;width:49%;text-align:left;">Accessibility:</p><p style="display:inline-block;width:49%;text-align:left;">&nbsp;No ACM</p>
                                        @break
                                @endswitch
                            </div>
                        </div>
                        <div class="row">
                            <p>&nbsp;</p>

                            <p style="display:inline-block;width:49%;text-align:left;">Total ACMs:</p>

                            <p style="display:inline-block;width:49%;text-align:left;">{{ $location->countItemACM()->count() }}</p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;width:49%;text-align:left;">Total NoACMs:</p>

                            <p style="display:inline-block;width:49%;text-align:left;">{{ $location->allItemNoACM()->count() }}</p>

                            <p>&nbsp;</p>
                        </div>
                    </div>
                </div>

                <div style="width:99%;">
                    <div class="row">
                        <div class="topTitleSmall" style="margin-bottom: 20px;">Room/location Void Investigations</div>
                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span style="width:70px; margin-right:15px;"><strong>Ceiling Void:</strong></span>
                        {{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->ceiling,
                                                                    optional($location->locationVoid)->ceiling_other ) }}</p>

                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span style="width:70px; margin-right:15px;"><strong>Floor
                                    Void:</strong></span>{{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->floor, optional($location->locationVoid)->floor_other ) }}</p>

                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span
                                    style="width:70px; margin-right:15px;"><strong>Cavities:</strong></span>
                                    {{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->cavities, optional($location->locationVoid)->cavities_other ) }}
                        </p>

                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span
                                    style="width:70px; margin-right:15px;"><strong>Risers:</strong></span>{{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->risers, optional($location->locationVoid)->risers_other ) }}
                        </p>

                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span
                                    style="width:70px; margin-right:15px;"><strong>Ducting:</strong></span>{{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->ducting, optional($location->locationVoid)->ducting_other ) }}
                        </p>

                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span
                                    style="width:70px; margin-right:15px;"><strong>Boxing:</strong></span>{{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->boxing, optional($location->locationVoid)->boxing_other ) }}
                        </p>

                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span
                                    style="width:70px; margin-right:15px;"><strong>Pipework:</strong></span>{{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->pipework, optional($location->locationVoid)->pipework_other ) }}
                        </p>

                    </div>
                </div>

                <div style="width:99%;">
                    <div class="row">
                        <div class="topTitleSmall" style="margin-top: 20px; margin-bottom: 20px;">Room/location Construction
                                                                                                  Details
                        </div>
                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span
                                    style="width:70px; margin-right:15px;"><strong>Ceiling:</strong></span>{{\CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->ceiling, optional($location->locationConstruction)->ceiling_other ) }}
                        </p>
                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span
                                    style="width:70px; margin-right:15px;"><strong>Walls:</strong></span>{{\CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->walls, optional($location->locationConstruction)->walls_other ) }}
                        </p>
                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span
                                    style="width:70px; margin-right:15px;"><strong>Floor:</strong></span>{{\CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->floor, optional($location->locationConstruction)->floor_other ) }}
                        </p>
                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span
                                    style="width:70px; margin-right:15px;"><strong>Doors:</strong></span>{{\CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->doors, optional($location->locationConstruction)->doors_other ) }}
                        </p>
                    </div>
                    <div class="row">
                        <p style="display:inline-block;"><span
                                    style="width:70px; margin-right:15px;"><strong>Windows:</strong></span>{{\CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->windows, optional($location->locationConstruction)->windows_other ) }}
                        </p>
                    </div>
                </div>
                <div style="width:99%;" class="row">
                    <p style="display:inline-block;"><span style="width:70px; margin-right:15px;"><strong>Comments:</strong></span>
                        {!! $location->locationInfo->comments ?? '' !!}
                    </p>
                </div>
            </div>
            @php
            $location_temp[] =  $location->id;
            @endphp
            @endif
            <!-- Location details -->
            <div style="width:99%;">
                <div style="margin-top: 20px; page-break-before: always">
                            <div style="position: relative">
                                <div style="width: 32%; display: inline-block;">
                                        <img width="200" src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_LOCATION, $is_pdf) }}" />
                                        <p style="margin-top:10px;">Location Photography</p>
                                </div>
                                <div style="width: 32%; display: inline-block;">
                                        <img width="200" src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO, $is_pdf) }}" />
                                        <p style="margin-top:10px;">Item Photography</p>
                                </div>
                                <div style="width: 32%; display: inline-block;">
                                        <img width="200" src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_ADDITIONAL, $is_pdf) }}" />
                                        <p style="margin-top:10px;">Additional Photography</p>
                                </div>
                            </div>

                            <div class="topTitle" style="margin:0;padding:0; margin-top: 10px;">Item Detail</div>
                            <div class="row" style="margin:0;padding:0">
                                <p style="display:inline-block;width:15%;text-align:left;">
                                    Item ID </p>
                                <p style="display:inline-block;width:59%;">
                                    @if(isset($item->sample))
                                        @if($item->record_id == $item->sample->original_item_id)
                                            {{$item->name . "(OS)"}}
                                        @else
                                            {{$item->name . "(VRS)"}}
                                        @endif
                                    @else
                                        {{$item->name}}
                                    @endif
                                </p>
                            </div>
                            @if(isset($item->sample))
                                <div>
                                    <p style="display:inline-block;width:20%;">
                                        Sample/link ID </p>
                                    <p style="display:inline-block;width:59%;">
                                        {{$item->sample->description ?? ''}}
                                    </p>
                                </div>
                            @endif
                            <div class="row" style="margin-top:10px;">
                                <p style="display:inline-block;width:20%;text-align:left;">
                                    Property Name </p>

                                <p style="display:inline-block;width:49%;text-align:left;">
                                    {{ $property->name }}
                                </p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:20%;text-align:left;">
                                    Area/floor </p>

                                <p style="display:inline-block;width:49%;text-align:left;">
                                    {{ $item->area->area_reference ?? '' }}
                                </p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:20%;text-align:left;">
                                    Room/location </p>

                                <p style="display:inline-block;width:49%;text-align:left;">
                                    {{ $item->location->location_reference ?? '' }}
                                </p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:20%;text-align:left;">
                                    Specific location </p>

                                <p style="display:inline-block;width:49%;text-align:left;">
                                    {{ $item->specificLocationView->specific_location ?? '' }}
                                </p>
                            </div>

                            <div class="row" style="margin-top: 10px;">
                                <p style="display:inline-block;width:20%;text-align:left;">
                                    Product/debris type </p>

                                <p style="display:inline-block;width:49%;text-align:left;">
                                    {{$item->productDebrisView->product_debris ?? '' }}
                                </p>
                            </div>
                            @if($item->state == ITEM_INACCESSIBLE_STATE)
                                <div class="row">
                                    <p style="display:inline-block;width:20%;text-align:left;">
                                        Asbestos type </p>

                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        {{$item->asbestosTypeView->asbestos_type ?? ''}}
                                    </p>
                                </div>
                            @endif
                            <div class="row" style="margin-top: 10px;">
                                <p style="display:inline-block;width:20%;text-align:left;">
                                    Extent </p>

                                <p style="display:inline-block;width:49%;text-align:left;">
                                    {{isset($item->extentView->extent )and $item->extentView->extent != '' ? ($item->itemInfo->extent ?? '').' '. ($item->extentView->extent ?? '') : ''}}
                                </p>
                            </div>

                            <div class="row" style="padding-top:10px;clear:both;position: relative;">
                                <div style="display:inline-block;width:49%;">
                                    <div class="topTitle">Material Assessment</div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Product Type (a) </p>
                                        <p style="display:inline-block;width:29%">
                                            {{$item->product_type ?? 0}}
                                        </p>
                                    </div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Extent of Damage (b) </p>

                                        <p style="display:inline-block;width:29%">
                                            {{$item->extend_damage ?? 0}}
                                        </p>
                                    </div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Surface Treatment (c) </p>

                                        <p style="display:inline-block;width:29%">
                                            {{$item->surface_treatment ?? 0}}
                                        </p>
                                    </div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Asbestos Fibre (d) </p>

                                        <p style="display:inline-block;width:29%">
                                            {{$item->asbestos_fibre ?? 0}}
                                        </p>
                                    </div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Total (a+b+c+d) </p>

                                        <p style="display:inline-block;width:29%">
                                            {{sprintf("%02d",$item->total_mas_risk)}}
                                        </p>
                                    </div>
                                    <div class="row" style="padding-top:5px;clear:both">
                                        <p style="display:inline-block;width:69%">
                                            Material Risk Assessment </p>

                                    <p style="display:inline-block;width:23%;">
                                        <img src="{{CommonHelpers::getAssetFile('img/'.CommonHelpers::getMasRiskColor($item->total_mas_risk).'-block.png', $is_pdf)}}"
                                             width="10" alt=""/> {{CommonHelpers::getMasRiskText($item->total_mas_risk)}}
                                    </p>
                                    </div>

                                    <div class="topTitle" style="margin-top: 20px;">Overall Assessment</div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Total (a+b+c+d+e+f+g+h) </p>

                                        <p style="display:inline-block;width:29%">
                                            {{sprintf("%02d", $item->total_risk)}}
                                        </p>
                                    </div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Overall Risk Assessment </p>

                                        <p style="display:inline-block;width:29%">
                                    <img src="{{CommonHelpers::getAssetFile('img/'.CommonHelpers::getTotalText($item->total_risk)['color'].'-block.png', $is_pdf)}}" width="10"
                                         alt=""/> {{CommonHelpers::getTotalText($item->total_risk)['risk']}}
                                        </p>
                                    </div>
                                </div>

                                <div style="display:inline-block;width:49%;    position: absolute;">
                                    <div class="topTitle">Priority Assessment</div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Normal Occupant Activity (e) </p>

                                        <p style="display:inline-block;width:29%">
                                            {{$item->primary ?? 0}}
                                        </p>
                                    </div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Likelihood of Disturbance (f) </p>

                                        <p style="display:inline-block;width:29%">
                                           {{$item->likelihood ?? 0}}
                                        </p>
                                    </div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Human Exposure Potential (g) </p>

                                        <p style="display:inline-block;width:29%">
                                            {{$item->human_exposure_potential ?? 0}}
                                        </p>
                                    </div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Maintenance Activity (h) </p>

                                        <p style="display:inline-block;width:29%">
                                           {{$item->maintenance_activity ?? 0}}
                                        </p>
                                    </div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            Total (e+f+g+h) </p>

                                        <p style="display:inline-block;width:29%">
                                            {{sprintf("%02d", $item->total_pas_risk)}}
                                        </p>
                                    </div>
                                    <div class="row" style="padding-top:5px;clear:both">
                                        <p style="display:inline-block;width:69%">
                                            Priority Risk Assessment </p>

                                        <p style="display:inline-block;width:29%">
                                        <img src="{{CommonHelpers::getAssetFile('img/'.CommonHelpers::getMasRiskColor($item->total_pas_risk).'-block.png', $is_pdf)}}"
                                             width="10" alt=""/> {{CommonHelpers::getMasRiskText($item->total_pas_risk)}}
                                        </p>
                                    </div>
                                    <div class="topTitle" style="margin-top:20px;">Comments</div>
                                    <div class="row">
                                        <p style="display:inline-block;width:69%">
                                            {{isset($item->itemInfo->comment) ? nl2br($item->itemInfo->comment) : 'No Comments'}}
                                        </p>

                                        <p style="display:inline-block;width:29%">
                                            <span>&nbsp;</span>
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <div class="row" style="width:99%;margin-top:20px;">
                                <div class="topTitle">Actions/recommendations</div>
                                <p style="display:inline-block;width:49%;text-align:left;">
                                    Action/recommendations </p>
                                <p style="display:inline-block;width:49%;text-align:left;">
                                    {{ optional($item->actionRecommendationView)->action_recommendation }}
                                </p>
                            </div>
                </div><!-- Survey Results Inspection Records PAGE 15 -->
            </div>
        </div>
    @endforeach

    <div id="assessment-information" style="page-break-before: always;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size: 10pt;">
        <div>
            <h2>Survey Appendices</h2>
            <h3 style="margin-top:20px">Material Risk Assessment Algorithm</h3>
            <p>Material assessments consider the type and condition of the ACM and the ease with which it will release
               fibres when subject to disturbance. The main parameters are:</p>

            <ol type="a" style="margin:20px 0 20px 40px;">
                <li>Product Type</li>
                <li>Extent of Damage & Deterioration</li>
                <li>Surface Treatments</li>
                <li>Asbestos Types</li>
            </ol>

            <p style="margin-top:20px">The material assessment will give a good initial guide to the priority for
                                       management as it will identify the materials which will most readily release
                                       airborne fibres if disturbed. It does not automatically follow that those
                                       materials assigned the highest score will be the priority for remedial action,
                                       such priorities must be determined by conducting and subsequently considering the
                                       results of a priority assessment.</p>

            <p style="margin-top:20px">To achieve some form of standardisation of the risk rating and action level, the
                                       assessment algorithm contained within HSG264 has been adopted, which is based
                                       upon a numerical rating given to each of the parameters considered above. The
                                       addition of each number results in a score that falls into one of four possible
                                       risk categories, which can assist the duty holder to prioritise the need for
                                       action as part of the plan for managing asbestos.</p>

            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX>
                <thead>
                <tr>
                    <th>Assessment Factor</th>
                    <th>Score</th>
                    <th>Score Variables</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td rowspan="3">Product Type (a)</td>
                    <td>1</td>
                    <td>Asbestos Reinforced Composites (Plastics, Resins, Mastics, Roofing Felts, Vinyl Floor Tiles,
                        Semi-Rigid Paints, Decorative Finishes, Asbestos Cement)
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Asbestos Insulating Board (AIB), Millboards, Other Low-Density Insulation Boards, Asbestos
                        Textiles, Gaskets, Ropes, Woven Textiles and Asbestos Paper or Felt
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Thermal Insulating (e.g. Pipe and Boiler Lagging) Sprayed Asbestos, Loose Asbestos, Asbestos
                        Mattresses and Packing
                    </td>
                </tr>

                <tr>
                    <td rowspan="4">Extent of Damage (b)</td>
                    <td>0</td>
                    <td>Good Condition: No Visible Damage</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Low Damage: A Few Scratches or Surface Marks, Broken Edges on Boards or Tiles</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Medium Damage: Significant Breakage of Material or Several Small Areas where Material has been
                        Damaged Revealing Loose Asbestos Fibre
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>High Damage: Delaminating of Materials, Sprays and Thermal Insulation, Visible Asbestos Debris
                    </td>
                </tr>

                <tr>
                    <td rowspan="4">Surface Treatment (c)</td>
                    <td>0</td>
                    <td>Composite Materials Containing Asbestos: Reinforced Plastics, Resins, Vinyl Tiles</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Enclosed Sprays and lagging, AIB with Exposed Face Painted or Encapsulated, Asbestos Cement Sheets etc
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Unsealed AIB or Encapsulated Lagging and Sprays</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Unsealed Lagging and Sprays</td>
                </tr>

                <tr>
                    <td rowspan="3">Asbestos Type (d)</td>
                    <td>1</td>
                    <td>Chrysotile (White)</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Amphibole Asbestos, Amosite (Brown), Actinolite, Anthophyllite and Tremolite</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Crocidolite (Blue)</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div><!-- Material Risk Assessment Algorithm PAGE 09-->

    <div id="Material-Classifcations" style="page-break-before: always;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size: 10pt;">
        <div>
            <div class="topTitleBig">Assessment Information</div>
            <h3 style="margin-top:20px">Material Classifications</h3>
            <p>The following material assessment categories are used within this survey and indicate the level of hazard
               each material presents.</p>

            <div class="topTitleSmall"
                 style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                <span style="width:16px;height:16px;background:red;display:inline-block;margin-right:3px"></span>(10<img
                        src="{{ CommonHelpers::getAssetFile('/img/ge.png', $is_pdf) }}" height="15" alt="GE">) High
            </div>
            <p>ACMs in this category are regarded as having a significant potential to release fibres if disturbed. Such
               ACMs require urgent consideration to ensure people are not exposed to the hazard. In most circumstances
               plans for removal should be implemented and in the interim, the affected area should be sealed off.</p>

            <div class="topTitleSmall"
                 style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                <span style="width:16px;height:16px;background:#ad8049;display:inline-block;margin-right:3px"></span>(7-9)
                                                                                                                     Medium
            </div>
            <p>ACMs within this category do not always pose an imminent threat and the likelihood of fibre release is
               moderate under existing conditions. A decision regarding how these ACMs are to be managed should be made
               promptly and most likely as part of an overall management plan. Such situations should be regularly
               inspected to ascertain any change to circumstances unless serious damage is present or debris is visible,
               then this will require action which could involve removal or encapsulation.</p>

            <div class="topTitleSmall"
                 style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                <span style="width:16px;height:16px;background:orange;display:inline-block;margin-right:3px"></span>(5-6)
                                                                                                                    Low
            </div>
            <p>ACMs within this category should be regarded as providing a low risk to people exposed to them but
               precautions should be followed and the situation should be monitored through regular
                <span>re-inspections</span> to ascertain any deterioration in condition which may occur with the passage
               of time. These ACMs generally have no or very little sign of historic damage.</p>

            <div class="topTitleSmall"
                 style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                <span style="width:16px;height:16px;background:yellow;display:inline-block;margin-right:3px"></span>(<img
                        src="{{CommonHelpers::getAssetFile('/img/le.png', $is_pdf)}}" height="15" alt="GE">4) Very Low
            </div>
            <p>ACMs within this category do not generally present a significant risk. They should be managed and only
               considered to be removed if the item falls within a refurbishment and demolition area and the works are
               likely to disturb the material.</p>

            <div class="topTitleSmall"
                 style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                <span style="width:16px;height:16px;background:green;display:inline-block;margin-right:3px"></span>(0)
                                                                                                                   No
                                                                                                                   Risk
            </div>
            <p>No ACM present.</p>
        </div>
    </div><!-- Material Classifications PAGE 10 -->

    <div id="survey-results-continue" style="page-break-before: always;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size: 10pt;">
        <div>
            <h2>Survey Appendices</h2>
            <h3 style="margin-top:20px">Remedial Options</h3>
            <p>There are a variety of remedial options available. In many cases the ACMs can be protected or enclosed,
               sealed or encapsulated, or repaired and these options should be considered first. Where such actions are
               not practical, ACMs should be removed. Recommended action in the Management Survey will normally involve
               one or more of the following:</p>

            <div class="topTitleSmall" style="margin-top:20px">Removal</div>
            <p>ACMs vulnerable to damage should often be removed. Where they are in such poor condition, removal is
               often the only practical option. Removal is required where refurbishment or demolition works are planned
               that will impinge on the ACMs present.</p>

            <div class="topTitleSmall" style="margin-top:20px">Management</div>
            <p>Management of the ACMs present (where these are not in poor condition or vulnerable to damage) is
               achieved by labelling, registering and monitoring as necessary. Such management should be undertaken in
               compliance with CAR 2012.</p>

            <div class="topTitleSmall" style="margin-top:20px">Monitor</div>
            <p>Re-inspection of ACMs should be undertaken at regular intervals determined by the risk priority and by a
               trained, suitably experienced and competent person. This may be accompanied by air testing where relevant
               to determine whether any asbestos fibres are present.</p>

            <div class="topTitleSmall" style="margin-top:20px">Label</div>
            <p>Where an ACM is detected, regardless of its risk categorisation, it is recommended that approved industry
               specific warning labels are positioned to prevent accidental damage to the material.</p>

            <div class="topTitleSmall" style="margin-top:20px">Protection/enclosure</div>
            <p>Undertake enclosure where the ACM is in poor condition or vulnerable to damage. This involves protection
               by a physical barrier, such as a timber casing. The casing is sealed and as airtight as possible to
               prevent the migration of fibres.</p>

            <div class="topTitleSmall" style="margin-top:20px">Sealed/encapsulate</div>
            <p>There are two methods of encapsulation: applying a durable layer adhered to the surface of the ACM, or
               applying a material that penetrates the ACM before hardening which locks the material together.</p>

            <div class="topTitleSmall" style="margin-top:20px">Repair</div>
            <p>All repairs should be undertaken by a competent person with the relevant training and equipment. Repair
               should only be undertaken if the damage is slight. There are a number of methods including filling,
               wrapping and isolated encapsulation. All repairs will be carried out using non-asbestos containing
               materials and appropriate precautions undertaken to prevent the release of any asbestos fibres.</p>

            <div class="topTitleSmall" style="margin-top:20px">Remove</div>
            <p>The HSE recommend against removal of asbestos if the removal is undertaken without due consideration of
               the potential to increase the risk of harm. ACMs should be removed where found to be in poor condition,
               if it is not possible to undertake maintenance works without disturbance, or refurbishment works are due
               to be undertaken. Only HSE licensed contractors may be appointed to deal with work that contains 'high
               risk' ACMs.</p>

            <div class="topTitleSmall" style="margin-top:20px">Periodic Air Test</div>
            <p>Where there is a large amount of ACMs in a confined space with a history of unauthorised disturbance,
               periodic air tests may be undertaken to monitor asbestos fibre levels to confirm that it is safe to
               access the area.</p>
        </div>
    </div><!--Survey Results Continue PAGE 18-->

    <div id="survey-appendices" class="page" style="page-break-before: always;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size: 10pt;">
        <div class="topTitleBig">Survey Appendices</div>
        <h3 style="margin-top:20px">Regulations and Guidance</h3>
        <div class="topTitleSmall" style="margin-top:20px">Legislation</div>
        <p>The Health & Safety at Work Act (1974) and The Management of Health and Safety at Work Regulations (1999)
           collectively require employers to provide a safe workplace for all their employees and those affected by
           their activities.</p>

        <p style="margin-top:20px">Asbestos specifically and work with asbestos is covered by specialist regulations
                                   known as The Control of Asbestos Regulations 2012 (CAR 2012). The duty to manage
                                   requires those in control of the premises to:</p>

        <ol style="margin:20px 0;">
            <li>Take reasonable steps to determine the location and condition of ACMs.</li>
            <li>Presume materials contain asbestos unless there is strong evidence that they do not.</li>
            <li>Set up and maintain a record of the location and condition of the ACMs or presumed ACMs in premises.
            </li>
            <li>Assess the risk of the likelihood of anyone being exposed to fibres from these ACMs.</li>
            <li>Prepare a plan setting out how the risks from the ACMs are to be managed.</li>
            <li>Take the necessary steps to put the plan into action.</li>
            <li>Review and monitor the plan periodically.</li>
            <li>Provide information on the location and condition of the materials to anyone who is liable to work on or
                disturb them.
            </li>
        </ol>

        <div class="topTitleSmall" style="margin-top:20px">Approved Codes of Practice and Guidance Documents</div>
        <p>There is a raft of publications that disseminate advice and information relating to asbestos which should be
           consulted by those who work with or have an obligation to manage ACMs (please note this list is not
           exhaustive).</p>

        <ol style="margin:20px 0;">
            <li>L127 'The management of asbestos in non-domestic premises'</li>
            <li>L143 'Work with materials containing asbestos'</li>
            <li>HSG 189/2 'Working with asbestos cement'</li>
            <li>HSG210 'Asbestos essentials task manual'</li>
            <li>HSG213 'Introduction to asbestos essentials'</li>
            <li>HSG227 'A comprehensive guide to managing asbestos in premises'</li>
            <li>HSG247 'Asbestos: The licensed contractors' guide'</li>
            <li>HSG248 'Asbestos: The analysts' guide for sampling, analysis and clearance procedures'</li>
            <li>HSG264 'Asbestos: The survey guide'</li>
            <li>INDG223 'A short guide to managing asbestos in premises'</li>
        </ol>

        <p style="margin-top:20px">The HSE has also published 38 'Asbestos essentials task sheets' and 10 'Equipment and
                                   Method sheets' which can help ensure compliance with CAR 2012 and illustrate 'good
                                   practice'.</p>

        <h3 style="margin-top:30px">Bulk Analysis Results</h3>
        <p>Documents Enclosed.</p>

        <h3 style="margin-top:30px">Site Plans</h3>
        <p>Documents Enclosed.</p>

    </div><!--Survey Appendices PAGE 19-->

</div><!--Container - set width -->
</body>
</html>

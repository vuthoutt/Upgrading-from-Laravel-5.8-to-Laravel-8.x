<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $reference ?? ''}}</title>
    <style type="text/css">
        body {
            padding: 10px 30px 10px 20px;
            color: #666;
            font-family: Arial, sans-serif;
            font-size: 10pt;
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
            font-size: 1.12em;
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

        @page :first {
            @bottom-center {
                content: element(watermark)
            }
        }

        #survey-report {
            height: 1100px;
        }

        #watermark {
            height: 70px;
        }
    </style>
</head>
<body>


<div id="container">
    <div id="survey-report" class="page">
        <div id="client-thumb" style="margin-bottom:30px">
            <img height="200" src="{{ CommonHelpers::getFile(1, CLIENT_LOGO, $is_pdf) }}"/>
        </div>
        <div id="property-thumb" style="margin-bottom:20px">
            <img height="250" src="{{ CommonHelpers::getFile($property->id ?? 0, PROPERTY_IMAGE, $is_pdf) }}"/>
        </div>
        <div id="asbestos-survey-report">
            <h2>Asbestos Register <span><?= date("d/m/Y") ?></span></h2>
            <h2>{{ $summary_name ?? '' }}</h2>
            <div style="margin-bottom:30px;" id="property-details1">
                <p><strong>{{ $property->property_reference ?? ''}}</strong></p>

                <p><strong>{{ $property->pblock ?? ''}}</strong></p>

                <p><strong>{{ $property->name ?? ''}}</strong></p>

                <p>{!! $property->propertyInfo->address1 ?? '' !!}</p>

                <p>{!! $property->propertyInfo->address2 ?? '' !!}</p>

                <p>{!! $property->propertyInfo->address3 ?? '' !!}</p>

                <p>{!! $property->propertyInfo->address4 ?? '' !!}</p>

                <p>{!! $property->propertyInfo->address5 ?? '' !!}</p>

                <p>{!! $property->propertyInfo->postcode ?? '' !!}</p>
            </div>

            <p style="margin-top:20px"><strong>IMPORTANT:</strong> The asbestos management information is live, meaning
                                                                   that the system is constantly being updated and
                                                                   amended. Summaries created from information in either
                                                                   the asbestos register or management system will
                                                                   therefore only be valid for the time and date they
                                                                   are created. </p>

            <p style="margin-top:20px">Summaries provide a limited overview of the data available, they are not designed
                                       to be used by those carrying out major refurbishment or works involving
                                       alterations to the fabric of a building or its services.</p>

            <p style="margin-top:20px">Contact the Asbestos Management Team for more detail.</p>


        </div>
    </div>
    <!-- PAGE 01 -->
    <div id="watermark">
        <p style="font-style:italic;">
            Powered by </p>

        <div id="powered-by-thumb">
            <img src="{{CommonHelpers::getAssetFile('img/shineAsbestosLogo.png', $is_pdf)}}" height="50" alt="ShineAsbestos Logo">
        </div>
    </div>

    <div style="page-break-before: always;"></div>

    <h2>Room/locations Checked:</h2>
    @if(!is_null($locationChecked))
        @php $locationPst = 0; @endphp
    @foreach($locationChecked as $location)
    @if ($locationPst > 0)
        <div style="page-break-before: always;" ></div>
    @endif
    @php $locationPst++; @endphp
    <div style="padding-top:40px;">
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

                        <p style="display:inline-block;width:49%;text-align:left;">{{ $location->countItemACM->count() }}</p>
                    </div>
                    <div class="row">
                        <p style="display:inline-block;width:49%;text-align:left;">Total NoACMs:</p>

                        <p style="display:inline-block;width:49%;text-align:left;">{{ $location->allItemNoACM()->count() }}</p>

                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: inline-block;width: 99%">
            <div style="width:99%;">
                <div class="row">
                    <div class="topTitleSmall" style="margin-bottom: 20px;">Room/location Void Investigations</div>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:15%;text-align:left;">Ceiling Void:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                    {{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->ceiling,
                                                                optional($location->locationVoid)->ceiling_other ) }}</p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:15%;text-align:left;">Floor Void:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                    {{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->floor, optional($location->locationVoid)->floor_other ) }}
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:15%;text-align:left;">Cavities:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                                {{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->cavities, optional($location->locationVoid)->cavities_other ) }}
                    </p>

                </div>
                <div class="row">
                    <p style="display:inline-block;width:15%;text-align:left;">Risers:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                        {{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->risers, optional($location->locationVoid)->risers_other ) }}
                    </p>

                </div>
                <div class="row">
                    <p style="display:inline-block;width:15%;text-align:left;">Ducting:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                        {{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->ducting, optional($location->locationVoid)->ducting_other ) }}
                    </p>

                </div>
                <div class="row">
                    <p style="display:inline-block;width:15%;text-align:left;">Boxing:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                        {{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->boxing, optional($location->locationVoid)->boxing_other ) }}
                    </p>

                </div>
                <div class="row">
                    <p style="display:inline-block;width:15%;text-align:left;">Pipework:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                        {{\CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->pipework, optional($location->locationVoid)->pipework_other ) }}
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
                    <p style="display:inline-block;width:15%;text-align:left;">Ceiling:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                        {{\CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->ceiling, optional($location->locationConstruction)->ceiling_other ) }}
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:15%;text-align:left;">Walls:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                        {{\CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->walls, optional($location->locationConstruction)->walls_other ) }}
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:15%;text-align:left;">Floor:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                        {{\CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->floor, optional($location->locationConstruction)->floor_other ) }}
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:15%;text-align:left;">Doors:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                        {{\CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->doors, optional($location->locationConstruction)->doors_other ) }}
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:15%;text-align:left;">Windows:</p>
                    <p style="display:inline-block;width:69%;text-align:left;">
                        {{\CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->windows, optional($location->locationConstruction)->windows_other ) }}
                    </p>
                    <p>&nbsp;</p>
                </div>
            </div>
            <div style="width:99%;" class="row">
                <p style="display:inline-block;width:15%;text-align:left;">Comments:</p>
                <p style="display:inline-block;width:69%;text-align:left;">
                    {!! $location->locationInfo->comments ?? '' !!}
                </p>
                <p>&nbsp;</p>
            </div>
            <!-- Add data time stamping -->
            <?php $location_data_stamping = CommonHelpers::get_data_stamping($location); ?>
            <div>
                <p style="display:inline-block;width:15%">
                    Data Stamp: </p>
                <p style="display:inline-block;width:69%">
                    <?= $location_data_stamping['data_stamping']; ?>
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:15%">
                    Organisation: </p>
                <p style="display:inline-block;width:69%">
                    <?= $location_data_stamping['organisation']; ?>
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:15%">
                    Username: </p>
                <p style="display:inline-block;width:69%">
                    <?= $location_data_stamping['username']; ?>
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:15%">
                    Creation Date: </p>
                <p style="display:inline-block;width:69%">
                    <?= $location_data_stamping['data_stamping_create']; ?>
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:15%">
                    Organisation: </p>
                <p style="display:inline-block;width:69%">
                    <?= $location_data_stamping['organisation_create']; ?>
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:15%">
                    Username: </p>
                <p style="display:inline-block;width:69%">
                    <?= $location_data_stamping['username_create']; ?>
                </p>
            </div>
        </div>
        <!-- Location details -->
        <div style="width:99%; font-size: 8pt;">
            @if ( count($location->allItems) == 0)
                <p style="margin-top:20px;margin-bottom:10px">There was no asbestos items identified or presumed to be present within the Room/location(s) requested at the time the Room/location Check was generated. NoACM Item(s) which have been sampled and returned a negative result are presented.</p>
            @else
                <div style="page-break-before: always;"></div><p style="margin-top:60px;">&nbsp;</p>
                @php $asbestosRegisterCount = 0; @endphp
                @foreach($location->allItems as $item)
                    @if ($asbestosRegisterCount % 2 == 0 && $asbestosRegisterCount != 0)
                    <div style="page-break-before: always;">
                    </div>
                    <p style="margin-top:60px;">&nbsp;</p>
                    @elseif ($asbestosRegisterCount == 0)
                    @else
                    <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: 5px; margin-bottom: 20px;"/>

                    @endif
                    <div style="display:inline-block;width:64%;margin-bottom:5px; margin-right: 5px">
                        <div style="margin-top:3px;">
                        <div style="display:inline-block;width:30%; margin-right: 4%" >
                            <img style="max-height: 80px;" width="160" height="80" src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_LOCATION, $is_pdf) }}" />
                            <p style='margin-top:5px'>Location</p>
                        </div>
                        <div style="display:inline-block;width:30%; margin-right: 4%">
                            <img style="max-height: 80px;" width="160" height="80" src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO, $is_pdf) }}" />
                            <p style='margin-top:5px'>Item</p>
                        </div>
                        @if(\CommonHelpers::checkFile($item->id, ITEM_PHOTO_ADDITIONAL))
                        <div style="display:inline-block;width:30%;">
                            <img style="max-height: 80px;" width="160" height="80" src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_ADDITIONAL, $is_pdf) }}" />
                            <p style='margin-top:5px'>Additional</p>
                        </div>
                        @endif
                        </div>

                        <div style="margin-top:5px;">
                            <div>
                                <p style="display:inline-block;width:39%;">
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
                            <?php if($item->state == ITEM_INACCESSIBLE_STATE) { ?>
                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Item Assessment</p>
                                <p style="display:inline-block;width:59%;">
                                    @if(isset($item->itemInfo->assessment))
                                        @if($item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT)
                                            {{'Limited Assessment Inaccessible Item'}}
                                        @elseif($item->itemInfo->assessment == ITEM_FULL_ASSESSMENT)
                                            {{'Full Assessment Inaccessible Item'}}
                                        @endif
                                    @endif
                                </p>
                            </div>
                            <!-- for Reason Assessment Type -->
                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Reason for No Access </p>
                                <p style="display:inline-block;width:59%;">
                                    {{  \Str::replaceFirst('Other', '',($item->ItemNoAccessValue->ItemNoAccess->description ?? '') . ' ' . ($item->ItemNoAccessValue->dropdown_other ?? '')) }}
                                </p>
                            </div>
                            <?php } ?>
                            @if(isset($item->sample))
                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Sample/link ID </p>
                                <p style="display:inline-block;width:59%;">
                                    {{$item->sample->description ?? ''}}
                                </p>
                            </div>
                             @endif
                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Property Name </p>
                                <p style="display:inline-block;width:59%;">
                                    <?= $property->name ?? ''; ?>
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Area/floor </p>
                                <p style="display:inline-block;width:59%;">
                                    <?= $item->area->area_reference ?? ''; ?>
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Room/location </p>
                                <p style="display:inline-block;width:59%;">
                                    <?= $item->location->location_reference ?? ''; ?>
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Room/location Description</p>
                                <p style="display:inline-block;width:59%;">
                                    <?= $item->location->description ?? ''; ?>
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Specific location </p>
                                <p style="display:inline-block;width:59%;">
                                    {{ $item->specificLocationView->specific_location ?? '' }}
                                </p>
                            </div>

                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Product/debris type </p>
                                <p style="display:inline-block;width:59%;">
                                    <?= $item->productDebrisView->product_debris ?? ''; ?>
                                </p>
                            </div>
                            @if ($item->state == ITEM_ACCESSIBLE_STATE || $item->state == ITEM_INACCESSIBLE_STATE)
                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Asbestos type </p>
                                <p style="display:inline-block;width:59%;">
                                    <?= $item->asbestosTypeView->asbestos_type ?? ''; ?>
                                </p>
                            </div>
                            @else
                                <div>
                                    <p style="display:inline-block;width:39%;">
                                        Asbestos type </p>
                                    <p style="display:inline-block;width:59%;">
                                         No asbestos detected
                                    </p>
                                </div>
                            @endif
                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Extent </p>
                                <p style="display:inline-block;width:59%;">
                                    {{(isset($item->itemInfo->extent) and $item->itemInfo->extent) != '' ? (($item->itemInfo->extent ?? '').' '. ($item->extentView->extent ?? '')) : ''}}
                                </p>
                            </div>
                            @if ($item->state != ITEM_NOACM_STATE)
                            <div>
                                <p style="display:inline-block;width:39%;text-align:left;">
                                    Action/recommendations </p>
                                <p style="display:inline-block;width:49%;text-align:left;">
                                    {{  $item->actionRecommendationView->action_recommendation ?? ''}}
                                </p>
                            </div>
                            @endif
                            <div>
                                <p style="display:inline-block;width:39%;">
                                    Data Stamp </p>
                                <?php $item_data_stamping = CommonHelpers::get_data_stamping($item); ?>
                                <p style="display:inline-block;width:59%;">
                                    <?= $item_data_stamping['data_stamping'] ;?>
                                </p>
                                <p style="display:inline-block;width:39%;">
                                    Organisation </p>
                                <p style="display:inline-block;width:59%;">
                                    <?= $item_data_stamping['organisation'] ;?>
                                </p>
                                <p style="display:inline-block;width:39%;">
                                    Username </p>
                                <p style="display:inline-block;width:59%;">
                                    <?= $item_data_stamping['username'] ;?>
                                </p>
                                <p style="display:inline-block;width:39%;">
                                    Creation Date </p>
                                <p style="display:inline-block;width:59%;">
                                    <?= $item_data_stamping['data_stamping_create'] ;?>
                                </p>
                                <p style="display:inline-block;width:39%;">
                                    Organisation </p>
                                <p style="display:inline-block;width:59%;">
                                    <?= $item_data_stamping['organisation_create'] ;?>
                                </p>
                                <p style="display:inline-block;width:39%;">
                                    Username </p>
                                <p style="display:inline-block;width:59%;">
                                    <?= $item_data_stamping['username_create'] ;?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="display:inline-block;width:30%;margin-bottom:5px;">
                        <div>
                            <div style="margin-top:0; padding-top:0;"><strong>Material Assessment</strong></div>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Product Type (a) </p>
                                <p style="display:inline-block;width:23%;">
                                    {{isset($item->product_type) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ? 'N/A' : $item->product_type ) : 0}}
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Extent of Damage (b) </p>
                                <p style="display:inline-block;width:23%;">
                                    {{isset($item->extend_damage) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ? 'N/A' : $item->extend_damage ) : 0}}
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Surface Treatment (c) </p>
                                <p style="display:inline-block;width:23%;">
                                    {{isset($item->surface_treatment) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ? 'N/A' : $item->surface_treatment ) : 0}}
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Asbestos Fibre (d) </p>
                                <p style="display:inline-block;width:23%;">
                                    {{isset($item->asbestos_fibre) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ? 'N/A' : $item->asbestos_fibre ) : 0}}
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Total (a+b+c+d) </p>
                                <p style="display:inline-block;width:23%;">
                                    {{sprintf("%02d",$item->total_mas_risk)}}
                                </p>
                            </div>

                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Material Risk Assessment </p>
                                <p style="display:inline-block;width:23%;">
                                    <img src="{{CommonHelpers::getAssetFile('img/'.CommonHelpers::getMasRiskColor($item->total_mas_risk).'-block.png', $is_pdf)}}"
                                         width="10" alt=""/> {{CommonHelpers::getMasRiskText($item->total_mas_risk)}}
                                </p>
                            </div>
                        </div>
                        <br>
                        <div>
                            <div><strong>Priority Assessment</strong></div>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Normal Occupant Activity (e) </p>
                                <p style="display:inline-block;width:23%;">
                                    {{$item->primary ?? 0}}
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Likelihood of Disturbance (f) </p>
                                <p style="display:inline-block;width:23%;">
                                    {{$item->likelihood ?? 0}}
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Human Exposure Potential (g) </p>
                                <p style="display:inline-block;width:23%;">
                                    {{$item->human_exposure_potential ?? 0}}
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Maintenance Activity (h) </p>
                                <p style="display:inline-block;width:23%;">
                                    {{$item->maintenance_activity ?? 0}}
                                </p>
                            </div>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Total (e+f+g+h) </p>
                                <p style="display:inline-block;width:23%;">
                                    {{sprintf("%02d", $item->total_pas_risk)}}
                                </p>
                            </div>

                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Priority Risk Assessment </p>
                                <p style="display:inline-block;width:23%;">
                                    <img src="{{CommonHelpers::getAssetFile('img/'.CommonHelpers::getMasRiskColor($item->total_pas_risk).'-block.png', $is_pdf)}}"
                                         width="10" alt=""/> {{CommonHelpers::getMasRiskText($item->total_pas_risk)}}
                                </p>
                            </div>
                        </div>
                        <br>
                        <div>
                            <div><strong>Overall Assessment</strong></div>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Total (a+b+c+d+e+f+g+h) </p>
                                <p style="display:inline-block;width:23%;">
                                    {{sprintf("%02d", $item->total_risk)}}
                                </p>
                            </div>
                            <br>
                            <div>
                                <p style="display:inline-block;width:64%;">
                                    Overall Risk Assessment </p>
                                <p style="display:inline-block;width:23%;">
                                    <img src="{{CommonHelpers::getAssetFile('img/'.CommonHelpers::getTotalText($item->total_risk)['color'].'-block.png', $is_pdf)}}" width="10"
                                         alt=""/> {{CommonHelpers::getTotalText($item->total_risk)['risk']}}
                                </p>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div style="width: 99%">
                        <div><strong>Comments</strong></div>
                        <div>
                            <p style="display:inline-block;width:99%;">
                                {{ $item->itemInfo->comment ?? 'No Comments' }}
                            </p>
                            <p style="display:inline-block;width:23%;">
                                &nbsp; </p>
                        </div>
                    </div>
                    @php $asbestosRegisterCount++; @endphp
                @endforeach
            @endif
        </div>
    </div>
    @endforeach
    @else
        <p style="margin-top:20px;margin-bottom:10px">There was no asbestos items identified or presumed to be present within the Room/location(s) requested at the time the Room/location Check was generated.</p>
    @endif
</div>
    <!--Container - set width -->
</body>
</html>

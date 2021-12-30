<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" >
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{$ss_ref}}</title>
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
    </style>
</head>
<body>

<div id="container" style="width: 859px">
    <h2>Asbestos Register</h2>
    <?php
    if (count($items)) {
    $asbestosRegisterCount = 0;
    foreach ($items as $item) {
    if ($asbestosRegisterCount % 2 == 0 && $asbestosRegisterCount != 0) {
    ?>
    <div style="page-break-before: always;"></div><p style="margin-top:60px;">&nbsp;</p>
    <?php } elseif ($asbestosRegisterCount == 0) { ?><?php } else { ?>
    <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: 5px; margin-bottom: 0;"/>
    <?php } ?>
    <div style="margin-top:30px; font-size: 8pt;">
        <div style="display:inline-block;width:64%;margin-bottom:5px;">
            <div style="margin-top:3px;">

                <div style="display:inline-block;width:30%;">
                    <img style="max-height: 80px;" width="160" height="80" src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_LOCATION, $is_pdf) }}" />
                    <p style='margin-top:5px'>Location</p>
                </div>
                <div style="display:inline-block;width:30%;">
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
                <?php if ($item->state == ITEM_ACCESSIBLE_STATE || $item->state == ITEM_INACCESSIBLE_STATE) { ?>
                <div>
                    <p style="display:inline-block;width:39%;">
                        Asbestos type </p>
                    <p style="display:inline-block;width:59%;">
                        <?= $item->asbestosTypeView->asbestos_type ?? ''; ?>
                    </p>
                </div>
                <?php } ?>
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

            <!-- Misssing Data time stamp here -->
        </div>

        <div style="display:inline-block;width:35%;margin-bottom:5px;">
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


    </div><!-- PAGE 16 -->
    <?php
    $asbestosRegisterCount++;
    }
    } else {
        echo '<p style="margin-top:20px;margin-bottom:10px">There was no asbestos items identified or presumed to be present within the Room/location(s) requested at the time the Room/location Check was generated.</p>';
    }
    ?>
    <div style="page-break-before: always;"></div>
    <h2>Inaccessible Room/locations</h2>

    <?php
    if (count($inaccessible_locations)) {
    $locationPst = 0;
    foreach ($inaccessible_locations as $key => $location) {
    if ($locationPst > 0) {
        echo '<div style="page-break-before: always;" ></div>';
    }
    $locationPst++;
    ?>
    <div style="padding-top:40px;">
        <div style="display:inline-block;width:36%">
            <img height="300" width="250" style="max-height: 300px;"
                 src="{{ \CommonHelpers::getFile($location->id, LOCATION_IMAGE, $is_pdf) }}"/>
        </div>
        <div style="display:inline-block;width:62%;padding-top:5px;">
            <div>
                <p style="display:inline-block;width:54%;">
                    Room/location Reference - Description: </p>
                <p style="display:inline-block;width:44%;">
                    {{ $location->location_reference . ' - '. $location->description}}
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:54%;">
                    Area/floor Reference - Description: </p>
                <p style="display:inline-block;width:44%;">
                    {{$location->area->area_reference ?? '' . ' - '. $location->area->description ?? ''}}
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:54%;">
                    Accessibility: </p>
                <p style="display:inline-block;width:44%;">
                    <?php
                    switch ($location->state) {
                        case LOCATION_STATE_ACCESSIBLE:
                            echo "Accessible";
                            break;
                        case LOCATION_STATE_INACCESSIBLE:
                            echo "Inaccessible";
                            break;
                    }
                    ?>
                </p>
            </div>
            @if($location->state == LOCATION_STATE_INACCESSIBLE)
             <div>
                <p style="display:inline-block;width:54%;">
                    Inaccessibility Reason: </p>
                <p style="display:inline-block;width:44%;">
                    {{ \CommonHelpers::getLocationVoidDetails(optional($location->locationInfo)->reason_inaccess_key, optional($location->locationInfo)->reason_inaccess_other ) }}
                </p>
            </div>
            @endif
            <div>
                <p style="display:inline-block;width:54%">
                    Comments: </p>
                <p style="display:inline-block;width:44%">
                    {!!  $location->locationInfo->comments ?? '' !!}
                </p>
            </div>

            <!-- Add DataStamping Missing -->
            <?php $location_data_stamping = CommonHelpers::get_data_stamping($location) ?>
            <div>
                <p style="display:inline-block;width:54%">
                    Data Stamp: </p>
                <p style="display:inline-block;width:44%">
                    <?= $location_data_stamping['data_stamping']; ?>
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:54%">
                    Organisation: </p>
                <p style="display:inline-block;width:44%">
                    <?= $location_data_stamping['organisation']; ?>
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:54%">
                    Username: </p>
                <p style="display:inline-block;width:44%">
                    <?= $location_data_stamping['username']; ?>
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:54%">
                    Creation Date: </p>
                <p style="display:inline-block;width:44%">
                    <?= $location_data_stamping['data_stamping_create']; ?>
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:54%">
                    Organisation: </p>
                <p style="display:inline-block;width:44%">
                    <?= $location_data_stamping['organisation_create']; ?>
                </p>
            </div>
            <div>
                <p style="display:inline-block;width:54%">
                    Username: </p>
                <p style="display:inline-block;width:44%">
                    <?= $location_data_stamping['username_create']; ?>
                </p>
            </div>

        </div>
        <?php if ($risk_type_one ?? '') { ?>
        <div class="des-field"
             style="width: 480px;background: #f2dede;border: 1px solid #eed3d7;color: #b94a48;padding: 10px;float: left;margin-top: 30px;">
            <strong><em>Inaccessible Room/locations; Asbestos must be Presumed to be Present;<br> An
                    Inspection to be Undertaken Prior to any Routine Occupation,<br> Please contact the
                    Asbestos Team for Further Advice </em></strong>
        </div>
        <?php } ?>
    </div>
    <?php
    }
    } else {
        if($type != LOCATION_REGISTER_PDF){
            echo '<p style="margin-top:20px;margin-bottom:10px">No Inaccessible rooms present.</p>';
        }
    }
    ?>


</div><!--Container - set width -->
</body>
</html>

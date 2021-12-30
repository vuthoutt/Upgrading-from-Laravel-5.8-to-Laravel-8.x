<?php
if ($survey) {

    $surveyorName = CommonHelpers::getUserFullname($survey->surveyor_id);
    $second_surveyor_idName =  CommonHelpers::getUserFullname($survey->second_surveyor_id);
    $technicalName = CommonHelpers::getUserFullname($survey->consultant_id);
    $qualityName = CommonHelpers::getUserFullname($survey->quality_id);
    $analystName = CommonHelpers::getUserFullname($survey->analyst_id);
    $commissionedName = CommonHelpers::getUserFullname($survey->created_by);

    switch ($survey->survey_type) {
        case 1:
            $surveyTitle = ($survey->surveySetting->is_require_r_and_d_elements) ? "Management and Refurbishment Survey"
                : "Management Survey";

            break;
        case 2:
            $surveyTitle = "Refurbishment Survey";
            break;
        case 3:
            $surveyTitle = "Re-Inspection Report";
            break;
        case 4:
            $surveyTitle = "Demolition Survey";
            break;
        case 5:
            $surveyTitle = "Management Survey Partial";
            break;
        default:
            break;
    }


}
?>


    <!DOCTYPE HTML>
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= $survey->reference; ?></title>
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

        #survey-report {
            height: 1100px;
        }

        #watermark {
            height: 70px;
        }
    </style>
</head>
<body>
<div id="container" style="width: 859px">
    <div id="survey-details" style="margin-top:30px;">
        <h2>Survey Details</h2>

        <div style="width:99%;">
            <h3 style="margin-top:30px;">Property Information</h3>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Property Name - Property Reference Number: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    <?php
                    echo implode(" - ", array_filter([$survey->property->reference ?? '', $survey->property->pblock ?? '', $survey->property->name ?? '']));
                    ?>
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Address & Postcode: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {!! implode(", ", array_filter([
                        $survey->property->propertyInfo->address1 ?? '',
                        $survey->property->propertyInfo->address2 ?? '',
                        $survey->property->propertyInfo->address3 ?? '',
                        $survey->property->propertyInfo->address4 ?? '',
                        $survey->property->propertyInfo->address5 ?? ''
                    ])) !!}
                </p>
            </div>

            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Property Coordinator: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$survey->property->propertyInfo->propertyInfoUser->full_name ?? ''}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Telephone / Mobile: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    <?php
                    echo implode(" / ", array_filter([$survey->property->propertyInfo->propertyInfoUser->first_name ?? '',
                                                    $survey->property->propertyInfo->propertyInfoUser->last_name ?? '']));
                    ?>
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Email: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$survey->property->propertyInfo->propertyInfoUser->email ?? ''}}
                </p>
            </div>
        </div>

        <div style="width:99%;">
            <h3 style="margin-top:30px;">Client Information</h3>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Client Name - Client Reference Number: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    <?php
                    echo implode(" - ", array_filter([$survey->property->clients->email ?? '',
                        $survey->property->clients->reference ?? '']));
                    ?>
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Address & Postcode: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                     {!! implode(", ", array_filter([
                            $survey->property->clients->clientAddress->address1 ?? '',
                            $survey->property->clients->clientAddress->address2 ?? '',
                            $survey->property->clients->clientAddress->address3 ?? '',
                            $survey->property->clients->clientAddress->address4 ?? '',
                            $survey->property->clients->clientAddress->country ?? '',
                            $survey->property->clients->clientAddress->postcode ?? ''
                    ])) !!}
                </p>
            </div>

            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Telephone / Mobile: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    <?php
                    echo implode(" / ", array_filter([
                            $survey->property->clients->clientAddress->telephone ?? '',
                            $survey->property->clients->clientAddress->mobile ?? ''
                        ])) . ".";
                    ?>
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Email: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$survey->property->clients->email ?? ''}}
                </p>
            </div>
        </div>

        <div style="width:99%;">
            @if($survey->client_id == TERSUS_SURVEYING )
                <h3 style="margin-top:30px;">Inspection Body</h3>
            @else
                <h3 style="margin-top:30px;">Contractor Information</h3>
            @endif
            <div class="row">
                @if($survey->client_id == TERSUS_SURVEYING )
                    <p style="display:inline-block;width:49%;text-align:left;">
                        Inspection Body Name - Inspection Body Reference Number: </p>
                @else
                    <p style="display:inline-block;width:49%;text-align:left;">
                        Contractor Name - Contractor Reference Number: </p>
                @endif
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?php
                        echo implode(" - ", array_filter([
                            $survey->client->name ?? '',
                            $survey->client->reference ?? ''
                        ]));
                        ?>
                    </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Address & Postcode: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {!! implode(", ", array_filter([
                            $survey->client->clientAddress->address1 ?? '',
                            $survey->client->clientAddress->address2 ?? '',
                            $survey->client->clientAddress->address3 ?? '',
                            $survey->client->clientAddress->address4 ?? '',
                            $survey->client->clientAddress->country ?? '',
                            $survey->client->clientAddress->postcode ?? ''
                     ])) !!}
                </p>
            </div>

            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Telephone / Mobile: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    <?php
                    echo implode(" / ", array_filter([
                        $survey->client->clientAddress->telephone ?? '',
                        $survey->client->clientAddress->mobile ?? ''
                    ]));
                    ?>
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Email: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$survey->client->email}}
                </p>
            </div>

            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    UKAS Reference Number: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$survey->client->clientInfo->ukas_reference}}
                </p>
            </div>
        </div>

        <div style="width:99%;">
            <h3 style="margin-top:30px;">{{$survey->survey_type_text}} Information</h3>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Survey Reference: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    <?php if ($survey_revision > 0) {
                        echo $survey->reference . "." . $survey_revision;
                    } else {
                        echo $survey->reference;
                    } ?>
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Surveying Start Date: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$survey->surveyDate->surveying_start_date ?? ''}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Surveying Finish Date: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$survey->surveyDate->surveying_finish_date ?? $survey->surveyDate->sent_back_date}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Publish Date: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$survey->surveyDate->published_date ?? ''}}
                </p>
            </div>

        </div>
        <div style="width:99%;">
            <h3 style="margin-top:30px; margin-bottom:10px;">Document Authorisation</h3>
            <?php if(strpos($surveyorName, 'Laboratory') == FALSE) { ?>
            <div id="signature-lead" style="display:inline-block;width:32%;text-align:center;">
                <img style="max-height: 100px;"
                     src="{{ \CommonHelpers::getFile($survey->surveyor_id, USER_SIGNATURE, $is_pdf) }}">
                <p style="height: 7px;">&nbsp;</p>
                {!! $surveyorName !!}
                <p style="height: 5px;">&nbsp;</p>
                Lead Surveyor
            </div>
            <?php } ?>
            <?php if($survey->second_surveyor_id && strpos($second_surveyor_idName, 'Laboratory') == FALSE){ ?>
            <div id="second-ignature-lead" style="display:inline-block;width:32%;text-align:center;">
                <img style="max-height: 100px;"
                     src="{{ \CommonHelpers::getFile($survey->second_surveyor_id, USER_SIGNATURE, $is_pdf) }}">
                <p style="height: 7px;">&nbsp;</p>
                {!! $second_surveyor_idName !!}
                <p style="height: 5px;">&nbsp;</p>
                Second Surveyor
            </div>
            <?php } ?>
            <?php if($survey->consultant_id && strpos($technicalName, 'Laboratory') == FALSE){ ?>
            <div id="project-manager" style="display:inline-block;width:32%;text-align:center;">
                <img style="max-height: 100px;"
                     src="{{ \CommonHelpers::getFile($survey->consultant_id, USER_SIGNATURE, $is_pdf) }}">
                <p style="height: 7px;">&nbsp;</p>
                {!! $technicalName !!}
                <p style="height: 5px;">&nbsp;</p>
                Project Manager
            </div>
            <?php } ?>
            <?php if($survey->quality_id && strpos($qualityName, 'Laboratory') == FALSE){ ?>
            <div id="quality-key" style="display:inline-block;width:32%;text-align:center;margin-top:120px;">
                <img style="max-height: 100px;"
                     src="{{ \CommonHelpers::getFile($survey->quality_id, USER_SIGNATURE, $is_pdf) }}">
                <p style="height: 7px;">&nbsp;</p>
                {!! $qualityName !!}
                <p style="height: 5px;">&nbsp;</p>
                Quality Checked By
            </div>
            <?php } ?>
            <?php if($survey->analyst_id && strpos($analystName, 'Laboratory') == FALSE){ ?>
            <div id="analyst" style="display:inline-block;width:32%;text-align:center;margin-top:120px;">
                <img style="max-height: 100px;"
                     src="{{ \CommonHelpers::getFile($survey->analyst_id, USER_SIGNATURE, $is_pdf) }}">
                <p style="height: 7px;">&nbsp;</p>
                {!! $analystName !!}
                <p style="height: 5px;">&nbsp;</p>
                Analyst
            </div>
            <?php } ?>
        </div>
    </div><!--Survey Detail PAGE 05-->


    <div style="page-break-before: always;"></div>
    <div id="excutive-summary" style="margin-top: 30px;">
        <h2>Executive Summary</h2>
        <p style="margin-top:20px;">A <?= $surveyTitle ?> was carried out at <?= $survey->property->name ?> on the {{ $survey->surveyDate->surveying_start_date ?? '' }}
            @if(isset($survey->surveyDate->surveying_start_date) && $survey->surveyDate->surveying_start_date > 0 )
                {{ $survey->surveyDate->surveying_start_date }}
                @if(isset($survey->surveyDate->surveying_finish_date) && !empty($survey->surveyDate->surveying_finish_date))
                    {{ (' - ' . $survey->surveyDate->surveying_finish_date) ?? ' '}}
                @endif
            @else
                00/00/0000
            @endif
        @if ($survey->survey_type == 1)
            @if ($survey->client_id == TERSUS_SURVEYING )
                <p style="margin-top:20px;">A summary of all identified or presumed asbestos can be found in the asbestos assessments/register within this survey report.</p>
                <p style="margin-top:20px;">Non accessible areas are noted on the no access register/assessments, any areas or items not accessed must be presumed to contain asbestos until such a time as full access and inspection can be undertaken.</p>
            @else
                @if ($survey->surveySetting->is_require_r_and_d_elements == 0)
                    <p style="margin-top:20px;">The purpose of the survey was to identify, as far as reasonably
                        practicable,
                        the presence and extent of any suspect Asbestos Containing Materials (ACMs)
                        in the areas inspected and assess their condition.</p>

                    <p style="margin-top:20px;">Management survey information was requested for this building. This type of
                        survey is designed to be used for assessing risks during normal work
                        activities and simple or routine maintenance tasks.</p>

                    <p style="margin-top:20px;">It is not designed to be used by those carrying out major refurbishments or
                        for work involving alterations to the fabric of the building.</p>

                    <p style="margin-top:20px;">If any refurbishment or demolition works are to be undertaken, A
                        Refurbishment or Demolition survey will be required prior to the start of
                        any work. This is a fully intrusive survey intended to find any hidden ACMs
                        contained within the main structure of the building.</p>

                @else
                    <p style="margin-top:20px;">The purpose of the survey was to identify, as far as reasonably practicable,
                        the presence and extent of any suspect asbestos containing materials (ACMs)
                        in the areas inspected and assess their condition.</p>

                    <p style="margin-top:20px;">Management and Refurbishment Survey information was requested for this
                        building. </p>

                    <p style="margin-top:20px;">The management part of the survey is designed to be used for assessing risks
                        during normal work activities and simple or routine maintenance tasks.</p>

                    <p style="margin-top:20px;">The refurbishment part of the survey is intrusive and may involve
                        destructive inspection, as necessary, to gain access to potentially hidden
                        asbestos within the building fabric. The level of intrusion necessary was
                        defined in the scope of works for this project. </p>

                    <p style="margin-top:20px;">Changes to the scope of work identified in this report may necessitate
                        further inspection and sampling. Destructive inspection was only carried out
                        in areas which would be disturbed for this project. ACMs may still be hidden
                        within the building fabric. </p>

                    <p style="margin-top:20px;">Construction/down taking plans appended to this report indicate the areas
                        surveyed within this building.</p>
                 @endif
            @endif
        @elseif ($survey->survey_type == 2)
            @if ($survey->client_id == TERSUS_SURVEYING )
                <p style="margin-top:20px;">A summary of all identified or presumed asbestos can be found in the asbestos assessments/register within this survey report.</p>
                <p style="margin-top:20px;">Non accessible areas are noted on the no access register/assessments, any areas or items not accessed must be presumed to contain asbestos until such a time as full access and inspection can be undertaken.</p>
            @else
                <p style="margin-top:20px;">The purpose of the survey was to identify, as far as reasonably practicable, the
                    presence and extent of any suspect asbestos containing materials (ACMs) in the
                    areas inspected and assess their condition.</p>

                <p style="margin-top:20px;">Refurbishment survey information was requested for this building. </p>

                <p style="margin-top:20px;">This type of survey is intrusive and may involve destructive inspection, as
                    necessary, to gain access to potentially hidden asbestos within the building
                    fabric. The level of intrusion necessary was defined in the scope of works for
                    this project.</p>

                <p style="margin-top:20px;">Changes to the scope of work identified in this report may necessitate further
                    inspection and sampling. Destructive inspection was only carried out in areas
                    which would be disturbed for this project. ACMs may still be hidden within the
                    building fabric. </p>

                <p style="margin-top:20px;">Construction/down taking plans appended to this report indicate the areas
                    surveyed within this building.</p>
            @endif
        @elseif ($survey->survey_type == 3)
            <p style="margin-top:20px;">The purpose of this survey was to inspect asbestos containing materials (ACMs)
                identified in the asbestos register to determine any changes in condition,
                location or accessibility. </p>

            <p style="margin-top:20px;">This report can be used to update existing survey information by documenting
                where ACMs have been removed prior to refurbishment or demolition. </p>

            <p style="margin-top:20px;">This report does not constitute a re-survey and should not be used as an initial
                resource for any work involving alterations to the fabric of the building. </p>

        @elseif ($survey->survey_type == 4)
            <p style="margin-top:20px;">The purpose of the survey was to identify, as far as reasonably practicable, the
                presence and extent of any suspect asbestos containing materials (ACMs) in the
                areas inspected and assess their condition.</p>

            <p style="margin-top:20px;">Demolition survey information was requested for this building. </p>

            <p style="margin-top:20px;">This type of survey is intrusive and may involve destructive inspection, as
                necessary, to gain access to potentially hidden asbestos within the building
                fabric.</p>

            <p style="margin-top:20px;">Changes to the scope of work identified in this report may necessitate further
                inspection and sampling. Destructive inspection was only carried out in areas
                which would be disturbed for this project. ACMs may still be hidden within the
                building fabric. </p>

            <p style="margin-top:20px;">Construction/down taking plans appended to this report indicate the areas
                surveyed within this building.</p>

            <p style="margin-top:20px;">This survey was as intrusive as possible with all reasonably practicable
                measures undertaken to determine the possible location of ACMs</p>
        @endif
        <?php if ($survey->surveyInfo->executive_summary) echo '<p style="margin-top:20px;">' . $survey->surveyInfo->executive_summary . '</p>' ?>

        <p style="margin-top:20px;">This report was published on {{$survey->surveyDate->published_date ?? ''}}.
            Updated information may be present on the asbestos management system which should be
            checked on a regular basis.</p>

        <p style="margin-top:20px;">During this Survey {{count($sample_survey)}} sample(s) were
            taken for analysis. There were {{$count_item_tested}} asbestos items identified or
            presumed to contain asbestos within the property.</p>

        <!-- Surveyor’s Notes -->
        @if ($survey->client_id == TERSUS_SURVEYING  || $survey->client_id == NOTTING_HILL_GENESIS )
            @if(count($data_siteplan)){
                @foreach ($data_siteplan as $document)
                <p style="margin-top:20px;">{{ $document->name }}. </p>
                @endforeach
            @endif
        @endif
        <div id="room-location-high-risk" style="margin-top:30px;">
            <h3>Room/locations containing High Risk Material</h3>
            <p>
                <?php
                if (count($high_risk_item)) {
                    echo "There were " . count($high_risk_item) . " room/locations with identified (or presumed) ACMs, containing a total of " . count($high_risk_item) . " Items with High Risk Material Scores. ";
                } else {
                    echo "Of the areas inspected, there were no locations identified (or presumed) to contain High Risk ACMs.";
                }
                ?>
            </p>
            <?php if (count($high_risk_item)) { ?>
            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX>
                <thead>
                <tr>
                    <th>Area/floor</th>
                    <th>Room/location</th>
                    <th>Item</th>
                    <th>Sample</th>
                    <th>Product/debris Type</th>
                    <th>Material Score</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($high_risk_item as $item) {
                ?>
                <tr>
                    <td><?= $item->area->title_presentation ?? ''; ?></td>
                    <td><?= $item->location->title_presentation ?? ''; ?></td>
                    <td><?= $item->name ?? ''; ?></td>
                    <td><?= $item->sample->description ?? "N/A"; ?></td>
                    <td><?= $item->productDebrisView->product_debris ?? ''; ?></td>
                    <td style="text-align: center;">
                        <img src="{{ public_path('img/red-block.png') }}" width="14" alt=""/> High Risk
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
            <div id="inaccessible-room-locations" style="margin-top: 20px;">
                <h3>Inaccessible Room/locations</h3>
                <p>
                    <?php
                    if (count($inaccessible_locations)) {
                        echo "There were " . count($inaccessible_locations) . " inaccessible room/locations all of which attract a High Material Score.";
                    } else {
                        echo "All areas were accessed as agreed at the pre-survey stage.";
                    }
                    ?>
                </p>
                <?php if (count($inaccessible_locations)) { ?>
                <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX>
                    <thead>
                    <tr>
                        <th width="25%">Area/floor</th>
                        <th width="25%">Room/location</th>
                        <th width="50%">Reason</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($inaccessible_locations as $location) {
                    ?>
                    <tr>
                        <td><?= $location->area->title_presentation ?></td>
                        <td><?= $location->title_presentation; ?></td>
                        <td><?= \CommonHelpers::getLocationVoidDetails(optional($location->locationInfo)->reason_inaccess_key, optional($location->locationInfo)->reason_inaccess_other ) ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
            <?php
            $is_require_location_void = $survey->surveySetting->is_require_location_void_investigations ?? NULL;
            if($is_require_location_void){ ?>
            <div id="inaccessible-void-room-locations" style="margin-top: 20px;">
                <h3>Inaccessible Void Room/locations</h3>
                <p>
                    <?php
                    if (count($location_inaccessible_void) ) {
                        $count = 0;
                        foreach ($location_inaccessible_void as $countCol){
                            $count ++;
                        }
                        echo "There were " . $count . " inaccessible areas all of which are presumed to contain asbestos until proven otherwise.";
                    }else{
                        echo "All room/locations voids were accessed as agreed at the pre-survey stage";
                    }
                    ?>
                </p>
                <?php if (count($location_inaccessible_void)) {?>
                <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX>
                    <thead>
                    <tr>
                        <th width="25%">Area/floor</th>
                        <th width="25%">Room/location</th>
                        <th width="25%">Void Type</th>
                        <th width="25%">Reason</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($location_inaccessible_void_display as $loc_inacc_type) { ?>
                    @if(isset($loc_inacc_type['reason']) && count($loc_inacc_type['reason']))
                        @foreach($loc_inacc_type['reason'] as $reason)
                            <tr>
                                <td><?= $loc_inacc_type['area_ref'] ?></td>
                                <td><?= $loc_inacc_type['loc_ref'] ?></td>
                                <td><?= $reason['void_type'] ; ?></td>
                                <td><?= str_replace("Inaccessible, ", "", $reason['value']) ;?></td>
                            </tr>
                        @endforeach
                    @endif
                    <?php }?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
            <?php } ?>

            <div id="inaccessible-items" style="margin-top: 20px;">
                <h3>Inaccessible Items</h3>
                <p>
                    <?php
                    if (count($inaccessible_items)) {
                        echo "There were " . count($inaccessible_items) . " inaccessible items all of which attract a High Material Score.";
                    } else {
                        echo "All items were accessed during the survey.";
                    }
                    ?>
                </p>
                <?php if (count($inaccessible_items)) { ?>
                <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX>
                    <thead>
                    <tr>
                        <th width="25%">Item</th>
                        <th width="20%">Area/floor</th>
                        <th width="20%">Room/location</th>
                        <th width="35%">Reason</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($inaccessible_items as $item) {
                    ?>
                    <tr>
                        <td><?= $item->name ?? '' ?></td>
                        <td><?= $item->area->title_presentation ?? '' ?></td>
                        <td><?= $item->location->title_presentation ?? '' ?></td>
                        <td>{{  \Str::replaceFirst('Other', '',($item->ItemNoAccessValue->ItemNoAccess->description ?? '') . ' ' . ($item->ItemNoAccessValue->dropdown_other ?? '')) }}</td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
    </div><!--Executive Summary page 03 -->

        <?php
        $is_survey_require_construction_detail = $survey->surveySetting->is_require_construction_details > 0 ?? NULL;
        if ($is_survey_require_construction_detail) {
        ?>
        <div style="page-break-before: always;"></div>
        <div id="property-details" style="margin-top: 30px;">
            <h2>Property Details</h2>


            <div style="width:99%;">
                <h3 style="margin-top:30px;">Property Construction Details</h3>
                <div class="row">
                    <p style="display:inline-block;width:49%;text-align:left;">
                        Primary Use: </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?= CommonHelpers::getProgrammeType( optional($survey->surveyInfo->property_data)->PrimaryUse,  optional($survey->surveyInfo->property_data)->primaryusemore, 'primary' ) ?>
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:49%;text-align:left;">
                        Secondary Use: </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?= CommonHelpers::getProgrammeType( optional($survey->surveyInfo->property_data)->SecondaryUse, optional($survey->surveyInfo->property_data)->secondaryusemore, 'primary' ) ?>
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:49%;text-align:left;">
                        Date of Construction: </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?= nl2br(optional($survey->surveyInfo->property_data)->constructionAge) ?>
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:49%;text-align:left;">
                        Construction Type: </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?= nl2br(optional($survey->surveyInfo->property_data)->constructionType); ?>
                    </p>
                </div>

                <div class="row" style="margin-top:10px">
                    <p style="display:inline-block;width:49%;text-align:left;">
                        No. Floors: </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?= nl2br(\CommonHelpers::getSurveyPropertyInfoText(optional($survey->surveyInfo->property_data)->sizeFloors, optional($survey->surveyInfo->property_data)->sizeFloorsOther )); ?>
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:49%;text-align:left;">
                        No. Staircases: </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?= nl2br(\CommonHelpers::getSurveyPropertyInfoText(optional($survey->surveyInfo->property_data)->sizeStaircases, optional($survey->surveyInfo->property_data)->sizeStaircasesOther )); ?>
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:49%;text-align:left;">
                        No. Lifts: </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?= nl2br(\CommonHelpers::getSurveyPropertyInfoText(optional($survey->surveyInfo->property_data)->sizeLifts, optional($survey->surveyInfo->property_data)->sizeLiftsOther )); ?>
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:49%;text-align:left;">
                        Gas Metel: </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?= nl2br(\CommonHelpers::getSurveyPropertyInfoText(optional($survey->property->propertySurvey)->gas_meter)); ?>
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:49%;text-align:left;">
                        Loft Void(s): </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?= nl2br(\CommonHelpers::getSurveyPropertyInfoText(optional($survey->property->propertySurvey)->loft_void)); ?>
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:49%;text-align:left;">
                        Net Area per Floor: </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?= nl2br(optional($survey->surveyInfo->property_data)->sizeNetArea ); ?>
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:49%;text-align:left;">
                        Gross Area: </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        <?= nl2br(optional($survey->surveyInfo->property_data)->sizeGrossArea); ?>
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:49%;text-align:left;vertical-align: top;">
                        Comments: </p>
                    <p style="display:inline-block;width:49%;text-align:left;">
                        {!! nl2br(optional($survey->surveyInfo->property_data)->sizeComments) !!}
                    </p>
                </div>
            </div>

        </div><!--Property Details PAGE 06-->
        <?php } ?>


        <div style="page-break-before: always;"></div>
        <h2 style="margin-top:30px;">Survey Information</h2>
        <h3 style="margin-top:20px;">Objective & Scope</h3>

        @if ($survey->surveyInfo->objectives_scope)
        <p style="margin-top:20px;">{!! $survey->surveyInfo->objectives_scope ?? '' !!}</p>
        @elseif($survey->client_id == LIFE_ENVIRONMENTAL)
                <p style="margin-top:20px;">Life Environmental Services Ltd received an order of confirmation to undertake
                    a {{ $surveyTitle }} from {{ $survey->client->name }} and Capita. This order has been
                    accepted on the basis of the original Quotation and Survey Plan and our terms and
                    conditions of business.
                </p>
                <p style="margin-top:20px;">All subsequent information
                    provided by the client or
                    ascertained otherwise was
                    assessed during planning
                    stage of the project and a
                    suitable Plan of Work
                    produced. Where information
                    was provided regarding the
                    presence of known or
                    presumed asbestos materials
                    then this has been validated
                    during the course of the
                    survey, and recorded within
                    this report. This survey was
                    carried out in accordance
                    with documented in house
                    procedures and HSE Guidance
                    document HSG 264. </p>
                <p style="margin-top:20px;">
                    <strong>Scope of Works:</strong>
                </p>
                @if($survey->property->reference)
                    <p>Asbestos management survey to include all rooms, spaces and external areas applicable to the
                        dwelling.</p>
                    <ul type="disc" style="margin-top:20px;">
                        <li>Floors are to be accessed beneath floor covering unless there is fixed laminate or tiles.</li>
                        <li>Voids are to be accessed where a hatch is present</li>
                        <li>Lofts are to be accessed where a hatch is present</li>
                    </ul>
                @else
                    <p>Asbestos management survey to whole site, to include all buildings, spaces and external areas. All
                        Blocks are to be included.</p>
                @endif
            <p style="margin-top:20px;">Instructions were received from (client contact name) on behalf of (client name)
                to undertake a management survey on the site known as (site name/address).</p>
        @elseif($survey->client_id == TERSUS_SURVEYING)
            @if($survey->survey_type == 1)
                @if($survey->surveySetting->is_require_r_and_d_elements == 1)
                    <p style="margin-top:20px;">The extent of the survey was to undertake a management asbestos survey with refurbishment elements. The scope of this inspection has been defined as follows:</p>
                    <p style="margin-top:20px;">[SCOPEOFWORK]</p>
                    <p style="margin-top:20px;">The purpose of this report is to enable compliance with CAR2012; The duty to manage asbestos in non-domestic premises.</p>
                    <p style="margin-top:20px;">The aim of this management asbestos survey is to locate identify and assess the condition of asbestos containing materials.</p>
                    <p style="margin-top:20px;">The aim of the refurbishment elements of this survey is to enable works to specific areas as required.</p>
                    <p style="margin-top:20px;">Refurbishment element surveys have been undertaken in the following locations: </p>
                    <p style="margin-top:20px;">[RANDDROOMS]</p>
                    <p style="margin-top:20px;">Information on the results of these inspections is detailed in this report, appendices and on annotated drawings.</p>
                    <p style="margin-top:20px;">The report and asbestos register must be maintained as one document, as all sections record information on the surveyor’s opinions, findings and limitations. Plans of the premises have been drafted and annotated accordingly in the Appendix.</p>
                @else
                    <p style="margin-top:20px;">The extent of the survey was to undertake an Asbestos Management. The scope of
                        this inspection has been defined as follows:</p>
                    <p style="margin-top:20px;">[SCOPEOFWORK]</p>
                    <p style="margin-top:20px;">The purpose of this report is to enable compliance with CAR2012; The duty to
                        manage asbestos in non-domestic premises.</p>
                    <p style="margin-top:20px;">The aim of this management asbestos survey is to locate identify and assess the
                        condition of asbestos containing materials.</p>
                    <p style="margin-top:20px;">Information on the results of these inspections is detailed in this report,
                        appendices and on annotated drawings.</p>
                    <p style="margin-top:20px;">The report and asbestos register must be maintained as one document, as all
                        sections record information on the surveyor’s opinions, findings and limitations. Plans of the premises
                        have been drafted and annotated accordingly in the Appendix.</p>
                @endif
            @endif
        @elseif($survey->survey_type == 2)
            <p style="margin-top:20px;">The extent of the survey was to undertake a Refurbishment asbestos survey. The scope of this inspection has been defined as follows:</p>
            <p style="margin-top:20px;">[SCOPEOFWORK]</p>
            <p style="margin-top:20px;">The purpose of this report is to enable compliance with CAR2012; The duty to manage asbestos in non-domestic premises. The aim of this refurbishment asbestos survey is to locate identify and assess the condition of asbestos containing materials. The aim of the refurbishment elements of this survey is to enable works to specific areas as required.</p>
            <p style="margin-top:20px;">Information on the results of these inspections is detailed in this report, appendices and on annotated drawings. The report and asbestos register must be maintained as one document, as all sections record information on the surveyor’s opinions, findings and limitations. Plans of the premises have been drafted and annotated accordingly in the Appendix.</p>
        @endif

        <div style="page-break-before: always;"></div>
        <div class="topTitleBig" style="margin-top:30px;">Survey Information</div>
        <h3 style="margin-top:20px;">Method</h3>
        <?php
        if($survey_method_question_data->isEmpty()) {
        if (optional($survey->surveyInfo)->method) {
            echo $survey->surveyInfo->method;
        }elseif ($survey->client_id == 2) {
        ?>

        <p style="margin-top:20px;"><?= $surveyTitle ?> - Access Allowances – The following access
            requirements
            have
            been agreed at Quotation Stage. Intrusive access and other
            access provision - Based on agreed Scope:</p><p
            style="margin-top:20px;"><strong>Height Access Provision - Standard (3m)</strong></p><p>
            Please
            record
            all areas where
            high level
            access equipment
            is required.
            These areas will
            be surveyed at a
            later date.</p>
        <p style="margin-top:20px;"><strong>Loft spaces - Access where a hatch is available within common
                areas</strong>
        </p><p>Please note: Where possible access loft hatch and carry out visual survey. Full access for
            management
            surveys will only be made where safe and sufficient walkways are available. Any restrictions on
            access
            are to be recorded with a comprehensive reason for limited access. </p><p
            style="margin-top:20px;">
            <strong>Electrical Switchgear - No</strong></p><p>Please record all locations where switchgear
            could
            potentially contain asbestos as these will be surveyed at
            a later date. </p><p style="margin-top:20px;"><strong>Plant/equipment
                -
                Yes</strong>
        </p><p>As far as reasonably practicable to do so whilst still live.</p><p style="margin-top:20px;">
            <strong>Lift
                Shafts
                -
                No</strong>
        </p><p>Lift shafts are to be recorded as no access and surveyed at a later date.</p><p
            style="margin-top:20px;">
            <strong>Escalator - No</strong></p><p style="margin-top:20px;"><strong>Confined Spaces -
                No</strong>
        </p><p>
            Confined spaces are to be recorded as areas of no access.</p><p style="margin-top:20px;">
            <strong></strong>External
            Soffits
            &
            Fascias
            -
            Yes
        </p><p>Where practicable and safe to do so.</p><p style="margin-top:20px;"><strong>Roof -
                Yes</strong>
        </p><p>
            Where requiring specialist equipment, single storey roofs are to be surveyed as part of the
            works
            where
            practical and safe to do so. Roofs above this height are only to be surveyed where regular
            access is
            required for maintenance activities. Please record all such instance and these will be surveyed
            at a
            later
            date.</p><p style="margin-top:20px;"><strong>Boxing - Yes</strong></p><p>Readily accessible by
            removable
            panels, all boxing and false work
            to be accessed where practicable.
            If access hatches can be removed
            easily and replaced securely the
            boxing must be included in the
            survey.</p><p
            style="margin-top:20px;"><strong>Solid Wall Cavities - No</strong></p><p
            style="margin-top:20px;">
            <strong>Partition Wall Cavities - No</strong>
        </p><p style="margin-top:20px;"><strong></strong>Wall Cladding & Coverings - No</p><p
            style="margin-top:20px;">
            <strong></strong>Fixed Suspended Ceilings - No</p><p>Where ceilings are interlocking MMMF tiles
            the
            void
            above cannot be surveyed without destructive access.
            All locations with this style of ceiling are to be
            recorded as limited access. </p><p
            style="margin-top:20px;"><strong>Glazing - No</strong></p><p style="margin-top:20px;">
            <strong>Window
                Frames -
                No</strong>
        </p><p style="margin-top:20px;"><strong>Window Sills - No</strong></p><p style="margin-top:20px;">
            <strong>Door
                Frames
                -
                No</strong>
        </p><p style="margin-top:20px;"><strong>Doors Internally - No</strong></p><p
            style="margin-top:20px;">
            <strong>Concealed
                Risers
                or
                Voids
                -
                No</strong>
        </p><p>Known or identified during survey</p><p style="margin-top:20px;"><strong>Ventilation Trunking
                -
                No</strong></p><p
            style="margin-top:20px;"><strong>Skirting - No</strong></p><p style="margin-top:20px;">
            <strong>Fixed
                Flooring
                -
                No</strong>
        </p><p style="margin-top:20px;"><strong>Floor Voids - No</strong></p><p>Where practicable and safe
            to do
            so</p>
        <p style="margin-top:20px;"><strong>Floor Ducts - No</strong></p><p>Where practicable and safe to do
            so
            and
            access is required as part of ongoing
            maintenance activities.</p><p
            style="margin-top:20px;"><strong>Below Ground Drainage Systems - No</strong></p><p
            style="margin-top:20px;">
            <strong>Slab - No</strong></p><p>Specify depth/diameter</p><p style="margin-top:20px;"><strong>Locked
                Locations -
                Client to
                provide
                access</strong>
        </p><p style="margin-top:20px;"><strong>Beyond suspected or known asbestos installations -
                No</strong>
        </p><p
            style="margin-top:20px;"><strong>Other Variations to Scope - N/A</strong></p><p
            style="margin-top:20px;">
            Note: If any activities are to be undertaken within areas that have not been accessed as part of
            this survey
            then a further survey and assessment should be carried out prior to these works</p>

        <?php
        } elseif ($survey->client_id == LUCION_ENVIRONMENTAL) {
                ?>
                <p style="margin-top:20px;"><strong>Survey Methodology</strong></p>

                <p style="margin-top:20px;">The asbestos survey findings detailed in this report were gathered using documented in house inspection (TOP01.01) and sampling procedures (TOP01.02) that implement the requirements of the Health and Safety Executive Publications HSG 264 (Asbestos: The survey guide) and HSG 248 (Asbestos: The analysts' guide for sampling, analysis and clearance procedures). All asbestos surveys aim to locate as far as is reasonably practicable, the presence and extent of any ACMs in the building within the defined scope of the survey (refer HSG 264). This method complies with section 3 of Regulation 4 (CAR, 2012).</p>

                <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: 20px; margin-bottom: 0;"/>

                <p style="margin-top:20px;"><h4>HSG 264 Asbestos: The Survey Guide</h4></p>

                <p style="margin-top:20px;">Publication HSG 264 sub-divides asbestos surveys into 2 principal types, termed: Management and  Refurbishment & Demolition surveys respectively. These survey types may be summarised as follows  (both have been shown to allow visualisation of the scope of the present management survey relative to the refurbishment & demolition survey specification and their suggested application/s).</p>

                <p style="margin-top:20px;"><strong>Management Survey - Standard Sampling, Identification and Assessment</strong></p>

                <p style="margin-top:20px;">The underlying purpose and inspection methodology of the management survey is to locate the presence, extent and condition by way of sampling and inspection of suspect asbestos containing materials as they are encountered. Where possible, representative samples of materials suspected by  the surveyor to contain asbestos are taken and analysed for the presence and type of asbestos fibre present. This survey is intended for integration into a plan for the management of asbestos containing materials under Regulation 4 of CAR (2012). The management surveys offers information allowing  routine and simple maintenance works to be carried and this reflects the surveyor's level of intrusion at the time of the inspection. More extensive maintenance or repair work may require additional  investigations to be undertaken; the findings of this survey should be checked with this in mind to confirm whether or not they of adequate scope.</p>

                <p style="margin-top:20px;"><strong>Refurbishment Survey - Full Access, Sampling and Identification</strong></p>

                <p style="margin-top:20px;">The refurbishment and demolition survey is fully intrusive (as far as is reasonably practicable) and is aimed at locating all asbestos containing materials within a survey area. Normally, unless otherwise specified, it involves fully invasive and possibly destructive investigation of all survey areas, in order to locate and assess all materials suspected as containing asbestos. The survey records only the location and estimated extent of asbestos containing materials. A priority rating has been assigned to  asbestos containing materials encountered during the course of the inspection to aid ongoing  management of these materials prior to refurbishment (HSG 264 states a duration in excess of 3 months as requiring ongoing management of ACMs). Recommendations made for material management are based solely on isolated item inspection and material assessment and may be subject to change in the context of the overall refurbishment works. However the surveyor will default to "remove" as a recommendation within a refurbishment survey. This type of survey is normally
                    recommended prior to refurbishment work commencing in the survey area. There is a specific requirement in CAR (2012) (Regulation 7) for all asbestos containing materials to be removed as far reasonably practicable before refurbishment.</p>

                <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: 20px; margin-bottom: 0;"/>

                <p style="margin-top:20px;"><h4>Survey Methodology - Important Notes</h4></p>

                <p style="margin-top:20px;"><strong>Reasonable Skill and Care</strong></p>

                <p style="margin-top:20px;">Although all survey areas that have been examined are reported in accordance with HSG 264 and documented in house procedures (for the specified survey type) and all reasonable skill and care has been exercised by the surveyor in doing so, it must be realised that no survey can reasonably guarantee beyond doubt that all asbestos containing materials have been located. Reasons for this limitation may include health and safety issues, reasons of practicality, non-access to live equipment and dangerous or contaminated environments or risk of unsafe levels of damage being inflicted on the survey area amongst others, or the location of the material being outside the investigative scope of the survey type undertaken.</p>

                <p style="margin-top:20px;">Please note, refurbishment and demolition asbestos surveys are used to locate and describe, as far  as reasonably practicable, all asbestos containing materials in the area where the refurbishment work will take place or in the whole building if demolition or refurbishment is planned. To do this, investigations will need to be of an intrusive nature and involve destructive inspection techniques. Notwithstanding the purpose of refurbishment and demolition surveys, the practical reality is that no survey should ever be used as an absolute guarantee to have identified all asbestos containing  materials. Destructive inspection points will be in locations intended to represent the structure as a  whole. Therefore, there remains the potential for hidden, obscured and discrete areas within the building fabric and/or structure that may contain unlocatable asbestos containing materials that may  only become apparent during demolition or refurbishment activities.</p>

                <p style="margin-top:20px;"><strong>Non-asbestos Materials - A Reasoned Argument</strong></p>

                <p style="margin-top:20px;">All items examined by the surveyor at the time of the survey are listed in the inspection detail of this  report. This detail includes those items believed by the surveyor not to contain asbestos and an appropriate categorisation of their material composition is given. Employing this rationale the surveyor  can use experience and judgement to form a reasoned argument that there is evidence to suggest that the material may not contain asbestos. Periodically "non-asbestos" building materials may be  sampled by way of a method control to further support the surveyor's argument. These materials do not bear any risk assessment detail.</p>

                <p style="margin-top:20px;"><strong>Materials Presumed to Contain Asbestos</strong></p>

                <p style="margin-top:20px;">If the surveyor feels that a reasoned argument against a material containing asbestos cannot be  formed, the item in question may be presumed to contain asbestos. This may include, but is not restricted to, areas where access cannot be gained. This scenario attracts the designation "P" in the “Item Detail” section within this report.</p>

                <p style="margin-top:20px;"><strong>Materials Strongly Presumed to Contain Asbestos</strong></p>

                <p style="margin-top:20px;">In the case of a material or materials being encountered that the surveyor suspects, following visual assessment, as containing asbestos but cannot be sampled for reasons of practicality, that material is  strongly presumed to contain asbestos. An assessment (where possible) of the material's extent and
                    condition is made. Nota bene: as no definitive assessment of asbestos fibre type contained in the material may be made, this portion of the priority score is based on a strongly presumed worst-case scenario of fibre type commonly contained in the material concerned. This scenario attracts the designation "P" in the “Item Detail” section within this report.</p>

                <p style="margin-top:20px;"><strong>Sampling of Materials</strong></p>

                <p style="margin-top:20px;">If access to the material permits, a representative sample of the material is taken according to the "sampling strategy". An assessment (where necessary or possible) of the material's extent and  condition is made. As no practical sampling strategy can be assured as being entirely representative  of the circumstances encountered during surveying, care should be exercised when interpreting results. That is to say that if works are planned that may cause disturbance or require the removal of asbestos containing materials, implementation of a more intense sampling regime may be desirable.</p>

                <p style="margin-top:20px;"><strong>Material Cross Referencing</strong></p>

                <p style="margin-top:20px;">In the event of a suspect material being encountered with a frequency that does not permit repeated re-sampling on the grounds of practicality, the surveyor may cross reference this item with one that has already been sampled. To do this the surveyor will ensure that the material is identical in nature  (through examining visual appearance e.g. colour) to that of the material to which it is referenced. Nota bene: as no definitive assessment of asbestos fibre type contained in the material may be made, this portion of the priority score is strongly presumed as being the same as that of the material from which it is cross referenced.</p>

                <p style="margin-top:20px;"><strong>Asbestos Removal</strong></p>

                <p style="margin-top:20px;">It should be noted that this report is not intended to be used as a bill of quantities for the removal of asbestos containing materials; it purely provides support. Extents and quantities recorded during the  survey have been estimated to the best of the surveyor’s ability, however, these shall require  verification and accurate measurement prior to removal of the asbestos containing materials through  production of an appropriate Technical Specification and Scope of Works. These documents can be prepared by Lucion upon request.</p>

                <p style="margin-top:20px;"><strong>Operational Buildings</strong></p>

                <p style="margin-top:20px;">The inspection and testing will be conducted to minimise any disruption to the occupiers as far as practical. To this end, the building or area undergoing survey should be unoccupied in order to minimise risk to employees or members of the public on the premises. Ideally, the building or area will  not be in use and all furnishings will have been removed. It should be noted that occupied or  operational buildings may place certain restrictions on the scope of the survey in respect of intrusive access and sampling strategy, for example it will not be possible to inspect behind a ceiling which is a known or suspected ACM, and that it may prove impossible to adequately investigate all areas of the property at the time of the initial survey. Where this is the case it may be required to undertake additional surveys or inspections immediately prior to the proposed refurbishment or demolition works  at a time and cost agreed with the client. Aspects of these additional inspections, e.g. penetration of known ACMs, may also require the services of a Licensed Asbestos Removal Contractor and notification of the work to the Enforcing Authority. It is the client’s responsibility to ensure that the information provided in the survey is adequate and relates to their requirements.</p>

                <p style="margin-top:20px;"><strong>Dust Sampling</strong></p>

                <p style="margin-top:20px;">The survey may include taking dust samples from areas where contamination is suspected to be  present due to visible signs of damage to asbestos containing materials or signs of previous
                    unsatisfactory asbestos removal works but does not include random dust sampling where there is no  apparent source of asbestos.</p>

                <p style="margin-top:20px;"><strong>Stored, portable and random use</strong></p>

                <p style="margin-top:20px;">Unless specifically identified within the report, no responsibility can be accepted by Lucion for non-systematic or random use of asbestos within the property or in contrast to the products and uses as detailed in HSG264 Appendix 2, for example adhoc use of packers in cavities (wall, floor, ceiling). Not only are these items small, but their occurrence is sporadic and they may only become visible once complete sections of wall, floor or ceiling are removed during demolition or refurbishment. In  addition, unless specifically identified within the report, no responsibility can be accepted by Lucion, for stored or portable items of asbestos.</p>

                <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: 20px; margin-bottom: 0;"/>

                <p style="margin-top:20px;"><h4>Method of Sample analysis</h4></p>

                <p style="margin-top:20px;">The bulk asbestos fibre identification results detailed in this report and the appended certificate of bulk analysis were obtained using a documented in house testing procedure (TOP01.03) that implements  the requirements of Health and Safety Executive Publication HSG 248, Appendix 2 (Asbestos: The analysts' guide for sampling, analysis and clearance procedures). All samples collected during the  course of this survey are tested in accordance with this method.</p>

                <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: 20px; margin-bottom: 0;"/>

                <p style="margin-top:20px;"><h4>HSG248 Asbestos: The Analysts' Guide for Sampling, Analysis and Clearance Procedures</h4></p>

                <p style="margin-top:20px;">Publication HSG 248 describes a two stage approach to the detection and subsequent identification of  asbestos fibre in bulk (i.e. suspect sample) materials. Initially the microscopist will examine the material under a low power stereo light microscope. The microscopist then performs extensive optical tests using polarised light microscopy in order to confirm or refute that the material contains an asbestos mineral. This technique allows for the detection of the six common forms of asbestos fibre as follows:</p>

                <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top: 20px;margin-bottom: 20px;">
                    <thead>
                    <tr>
                        <th>Asbestos Fibre Type</th>
                        <th>Common Nomenclature</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr><td>Chrysotile</td><td>White Asbestos</td></tr>
                    <tr><td>Amosite</td><td>Brown Asbestos</td></tr>
                    <tr><td>Crocidolite</td><td>Blue Asbestos</td></tr>
                    <tr><td>Asbestos Actinolite</td><td>N/A</td></tr>
                    <tr><td>Asbestos Anthophyllite</td><td>N/A</td></tr>
                    <tr><td>Asbestos Tremolite</td><td>N/A</td></tr>
                    </tbody>
                </table>

                <p style="margin-top:20px;">The results of this test are given, where appropriate, in the inspection detail report for each sample  taken and are summarised in the management recommendation report. They are also separately     detailed in the bulk-analysis report appended to this report. The homogeneity of asbestos containing materials can differ depending on their type. Typically, homogeneous materials include sprayed    coatings, insulating board and asbestos cement products. Other materials are typically less    homogeneous including pipe lagging (due to patch repairs, hand mixing at time of application), textured coatings (due to low concentration of asbestos fibre and hand application), composites (due to low concentration of asbestos fibre and material matrix) and debris samples (due to the potentially inconsistent occurrences that have led to their presence). Whilst sampling frequencies / techniques     and analysis methods attempt to address the issue of non-homogeneity it should be realised that     sampling in accordance with HSG 264 and analysis in accordance with HSG 248 cannot always     obviate the problems of determining asbestos fibre content in non-homogeneous materials. The    results of sample analysis presented in this report therefore pertain to the samples analysed and so relate only to the time at which sampling took place and to the conditions prevailing during that time.</p>
                <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: 20px; margin-bottom: 0;"/>

        <?php  } elseif ($survey->client_id == TERSUS_SURVEYING) { ?>
            <?php if($survey->survey_type == 1){ ?>
                @if($survey->is_require_r_and_d_elements == 1)
                        <p style="margin-top:20px;">The Management asbestos survey with refurbishment elements (HSG264) is used to locate and describe, as far as reasonable practical Asbestos containing materials (ACMs) in the building and may involve destructive inspection, as necessary, to gain access to areas, including those that may be difficult to reach in predefined areas. The survey is designed to be used as a risk assessment for intrusive works.</p>
                        <p style="margin-top:20px;">Every effort has been made to identify all asbestos materials so far as was reasonably practical to do so within the scope of the survey and the attached report. Methods used to carry out the survey were agreed with the client prior to any works being commenced. The aim of these inspections was to produce a Refurbishment / Demolition survey of the aforementioned building.</p>
                        <p style="margin-top:20px;">All reasonable attempts were made to access all areas within the scope of the survey. Areas not accessed are reported in the executive summary, non-accessible areas register.</p>
                        <p style="margin-top:20px;">Possible asbestos containing materials or areas of the buildings that were inaccessible must be presumed to contain asbestos until proven otherwise.</p>
                        <p style="margin-top:20px;">This Management Survey is based on a visual inspection of materials on site, confirmed by sample analysis. The purpose of this survey type was to locate, as far as reasonably practical, any asbestos containing materials in the building and assess them for risk. This report is based on the results of the visual inspections, sampling and analysis of suspected asbestos materials. The surveyor has taken all reasonable steps in order to conclude that asbestos containing materials are not present</p>
                        <p style="margin-top:20px;">Although every effort was made to access all areas of the building it is possible that concealed cavities, floor voids etc will only be accessible during demolition, and therefore contingencies must be made to include the potential risks that asbestos containing materials may remain unidentified in the property or area covered by that survey.</p>
                        <p style="margin-top:20px;">All sampling was undertaken so as to cause the minimum possible nuisance, disruption or risk to health. These factors may have limited the sampling strategy and as such, materials may have been presumed.</p>
                        <p style="margin-top:20px;">The extent and assessment of asbestos materials was determined by visible evidence on site with bulk sampling and analysis to confirm the surveyor’s judgement. Management Surveys only involve minor intrusive works therefore it is always possible that after a survey, asbestos containing materials may remain unidentified in the property or area covered by the scope of the survey.</p>
                        <p style="margin-top:20px;">Samples were collected with due diligence and in line with our survey and sampling in house procedures, accredited by UKAS to ISO 17020 and ISO 17025:2005. Unless requested otherwise a label bearing the sample reference number is then adhered to the area sampled</p>
                        <p style="margin-top:20px;">The surveyor shall take all reasonable steps in order to conclude that ACM are not present. There are obvious materials that are not asbestos. The surveyor will record basic inspection notes and conclude that no asbestos is presumed or identified for that room or area. Look-a-like materials will be sampled to support the surveyor’s judgement.</p>
                        <p style="margin-top:20px;">In general terms it is the policy of this company to take samples where appropriate in order to prove the existence or otherwise of asbestos containing materials. On occasions where the report states 'presumed', 'strongly presumed' or 'no asbestos presumed', the surveyor will already have made his or her judgement, on the basis of 'reasoned argument' and with regard to their experience of similar materials. Where items have sample numbers reported “As sample NW00067” these results are strongly presumed to have the same asbestos content as identical homogenous materials that have been sampled and are related to the result of the sample to which they refer. Conclusions for "As samples" are also appended with an Asterix "*".</p>
                        <p style="margin-top:20px;">Materials are reported as 'strongly presumed' where the material appears to contain asbestos, but analysis has not been undertaken. Materials will be strongly presumed in the following scenarios;</p>
                        <ul>
                            <li>or based on a sample of homogenous material,</li>
                            <li>based on the knowledge and experience of the surveyor,</li>
                            <li>where materials have the appearance of asbestos or fibres are clearly visible.</li>
                            <li>where the materials might contain asbestos</li>
                        </ul>
                        <p style="margin-top:20px;">Materials are reported as 'presumed' where asbestos materials may be present but are not accessible to inspect, assess nor sample ie there is insufficient evidence that is it asbestos free.</p>
                        <div style="page-break-before: always;"></div>
                        <h3>RERURBISHMENT ELEMENT</h3>
                        <p style="margin-top:20px;">Refurbishment / Demolition survey (HSG264). This type of survey is used to locate and describe, as far as reasonable practical Asbestos containing materials (ACMs) in the building and may involve destructive inspection, as necessary, to gain access to areas, including those that may be difficult to reach. The survey is designed to be used as a basis for tendering the removal of ACMs from the building prior to demolition or major refurbishment.</p>
                        <p style="margin-top:20px;">Due to the inherent risk to health, Refurbishment / Demolition surveys are only conducted in un-occupied buildings or sites which will remain un-occupied until any remedial or removal measures have been undertaken. If a site is to be re-occupied the requirement for testing for reoccupation will have been discussed with the client and will be dependent on the finding within this report and condition of any asbestos materials found.</p>
                        <p style="margin-top:20px;">Pre demolition surveys require substantial disruption to the building, i.e. partial demolition of risers, ducts, opening up of access hatches locked or blocked doors, etc. This cannot be accomplished without safeguards being in place and the building being empty otherwise limitations will have be employed.</p>
                        <p style="margin-top:20px;">Although every effort was made to access all areas of the building it is possible that concealed cavities, floor voids etc will only be accessible during demolition, and therefore contingencies must be made to include the potential risks that asbestos containing materials may remain unidentified in the property or area covered by that survey,</p>
                        <p style="margin-top:20px;"><strong>Inspection Procedure</strong></p>
                        <p style="margin-top:20px;">Each room or designated area is inspected individually noting any building materials, which may contain asbestos. All heating, ventilation, services, riser, voids etc, will be accessed where possible and safe to do so.</p>
                        <p style="margin-top:20px;">Occupied areas during surveys can impose restrictions on sampling and investigation.</p>
                        <p style="margin-top:20px;">All reasonable efforts are made to access and find any concealed asbestos, e.g. below floor ducts, in ceiling voids and inside convector heaters. However, because of the way that asbestos is used in composite structures and inaccessible places it cannot be guaranteed that all asbestos materials have been located during the surveys.</p>
                        <p style="margin-top:20px;"><strong>MATERIAL ASSESSMENT ALGORITHM SCORES (MAS) - HSG264</strong></p>
                        <p style="margin-top:20px;">Number scores are allocated to ACM depending on product type, extent of damage/ deterioration, surface treatment and asbestos type (which shall be scored as Crocidolite (blue) asbestos unless similar samples show otherwise, or it is likely that another type of asbestos is almost always used).</p>
                        <p style="margin-top:20px;">ACM with scores of 10 or more are regarded as a high potential to release fibres if disturbed, 7- 9 medium potential, 5-6 low potential and 4 or less very low potential.</p>
                        <p style="margin-top:20px;">These scores and other recorded observations, which are perceived as being likely to affect the release of asbestos fibres, are then used to allocate a risk code, which provides some basic advice on how the ACM should be treated in our opinion.</p>
                        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top: 20px;margin-bottom: 20px;">
                            <tbody>
                            <tr>
                                <strong>MATERIAL ALGORITHM ASSESSMENT SCORE (MAS)</strong>
                            </tr>
                            <tr>
                                <td><strong>Sample Variable</strong></td>
                                <td><strong>Score</strong></td>
                                <td><strong>EXAMPLE</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Product type</strong></td>
                                <td>1</td>
                                <td>Asbestos reinforced composites (plastics, resins, mastics, roofing felts, vinyl floor tiles, semi-rigid paints or decorative finishes, asbestos cement products etc).</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>2</td>
                                <td>Asbestos insulation board, mill board, other low density boards, asbestos ropes and woven textiles, gaskets, asbestos paper and felt</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>3</td>
                                <td>Insulation (pipe and boiler lagging, spray coating, loose asbestos.</td>
                            </tr>
                            <tr>
                                <td><strong>Extent of damage / deterioration</strong></td>
                                <td>0</td>
                                <td>Good condition; no visible damage.</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>1</td>
                                <td>Low damage; scratches or surface marks; broken edges to boards, tiles etc</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>2</td>
                                <td>Medium damage; significant breakage of materials or several small areas where material has been damaged revealing loose fibres.</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>3</td>
                                <td>High damage or delamination of materials, sprays and thermal insulation. Visible asbestos debris</td>
                            </tr>
                            <tr>
                                <td><strong>Surface treatment</strong></td>
                                <td>0</td>
                                <td>Composite materials containing asbestos; reinforced plastics, resins, vinyl tiles</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>1</td>
                                <td>Enclosed sprays and lagging, AIB (with exposed face painted or encapsulated) cement sheets etc.</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>2</td>
                                <td>Unsealed AIB, or encapsulated lagging and sprays</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>3</td>
                                <td>Unsealed lagging and sprays.</td>
                            </tr>
                            <tr>
                                <td><strong>Asbestos type</strong></td>
                                <td>1</td>
                                <td>Chrysotile</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>2</td>
                                <td>Amphibole asbestos excluding Crocidolite</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>3</td>
                                <td>Crocidolite</td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="page-break-before: always;"></div>
                        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top: 20px;margin-bottom: 20px;">
                            <tbody>
                            <tr><strong>RISK CODE TABLE</strong></tr>
                            <tr>
                                <td><strong>RISK CODE</strong></td>
                                <td><strong>MANAGEMENT RECOMMENDATIONS</strong></td>
                            </tr>
                            <tr>
                                <td><strong>A</strong></td>
                                <td>Restrict access to area immediately. Remove by licence asbestos contractors under controlled conditions in accordance with CAR2012.</td>
                            </tr>
                            <tr>
                                <td><strong>B</strong></td>
                                <td>Remove or repair by licensed contractors in accordance with CAR2012.</td>
                            </tr>
                            <tr>
                                <td><strong>C</strong></td>
                                <td>Encapsulate by licensed contractor in accordance with CAR 2012. Where appropriate label with warning signs on completion. Undertake routine re-inspections.</td>
                            </tr>
                            <tr>
                                <td><strong>D</strong></td>
                                <td>High Risk ACM in good condition, encapsulation intact. Where appropriate label with warning signs. Undertake routine re-inspections for damage or deterioration in accordance with asbestos management plan and CAR 2012.</td>
                            </tr>
                            <tr>
                                <td><strong>E</strong></td>
                                <td>Low risk ACM (Bound in matrix). Where appropriate label with warning signs. Undertake routine inspections for damage and deterioration. Where damaged, remove or repair in accordance with CAR2012. Reg 3(2).</td>
                            </tr>
                            <tr>
                                <td><strong>F</strong></td>
                                <td>Inaccessible room or item, maintain presumption of asbestos until accessed.</td>
                            </tr>
                            </tbody>
                        </table>
                        <p style="margin-top:20px;">Tersus always recommends the use of licensed asbestos removal contractors undertaking all works in accordance with the Control of Asbestos Regulations (CAR2012).</p>
                        <p style="margin-top:20px;">Asbestos management survey (HSG264) investigations only were undertaken; limitations of the survey should be noted and areas not fully accessed must be presumed to contain asbestos products unless further investigation proves otherwise.</p>
                        <p style="margin-top:20px;">Should you require any further assistance please do not hesitate to contact Tersus.</p>
                    @else
                        <p style="margin-top:20px;">The purpose of the inspection is to produce a report on asbestos bearing materials using methods stated within HSG264 ‘Asbestos: The survey guide', for a Management Asbestos Survey.</p>
                        <p style="margin-top:20px;">Every effort has been made to identify all asbestos materials so far as reasonably practical to do so within the scope of the survey. Methods used to carry out the survey were agreed with the client prior to any works being carried out.</p>
                        <p style="margin-top:20px;">All reasonable attempts were made to access all areas within the scope of the survey. Areas not accessed are reported in the executive summary, non-accessible areas register</p>
                        <p style="margin-top:20px;">Possible asbestos containing materials or areas of the buildings that were inaccessible must be presumed to contain asbestos until proven otherwise.</p>
                        <p style="margin-top:20px;">This management survey is based on a visual inspection of materials on site, confirmed by sampling and analysis. The purpose of this survey is to locate, as far as reasonable practicable, the presence and extent of any suspect ACMs in the building which could be damaged  or disturbed during normal occupancy, including foreseeable maintenance, installation and to assess their condition.</p>
                        <p style="margin-top:20px;">All sampling was undertaken so as to cause the minimum possible nuisance, disruption or risk to health. These factors may have limited the sampling strategy and as such, materials may have been presumed.</p>
                        <p style="margin-top:20px;">The extent and assessment of asbestos materials was determined by visible evidence on site with bulk sampling and analysis to confirm the surveyor’s judgement. Management Surveys only involve minor intrusive works therefore it is always possible that after a survey, asbestos containing materials may remain unidentified in the property or area covered by the scope of the survey.</p>
                        <p style="margin-top:20px;">Samples were collected with due diligence and in line with our survey and sampling in house procedures. We hold accreditation to ISO 17020:2012 and ISO 17025:2017. Unless requested otherwise a label bearing the sample reference number is then adhered to the area sampled where practicable.</p>
                        <p style="margin-top:20px;">The surveyor will record basic inspection notes and conclude that no asbestos is presumed or identified for that room or area. Look-a-like materials will be sampled to support the surveyor’s judgement.</p>
                        <p style="margin-top:20px;">In general terms it is the policy of this company to take samples where appropriate in order to prove the existence or otherwise of asbestos containing materials. On occasions where the report states 'presumed', 'strongly presumed' or 'no asbestos presumed', the surveyor will already have made his or her judgement, on the basis of 'reasoned argument' and with regard to their experience of similar materials. Where items have sample numbers reported “As sample NW00067” these results are strongly presumed to have the same asbestos content as identical homogenous materials that have been sampled and are related to the result of the sample to which they refer. Conclusions for "As samples" are also appended with an Asterix "*".</p>
                        <p style="margin-top:20px;">Materials are reported as 'strongly presumed' where the material appears to contain asbestos, but analysis has not been undertaken. Materials will be strongly presumed in the following scenarios:</p>
                        <p style="margin-top:20px;">
                            <ul>
                                <li>or based on a sample of homogenous material,</li>
                                <li>based on the knowledge and experience of the surveyor,</li>
                                <li>where materials have the appearance of asbestos or fibres are clearly visible.</li>
                                <li>where the materials might contain asbestos</li>
                            </ul>
                        </p>
                        <p style="margin-top:20px;">Materials are reported as 'presumed' where asbestos materials may be present but are not accessible to inspect, assess nor sample ie there is insufficient evidence that is it asbestos free</p>
                        <p style="margin-top:20px;"><strong>Inspection Procedure</strong></p>
                        <p style="margin-top:20px;">Each room or designated area is inspected individually noting any building materials, which may contain asbestos. All heating, ventilation, services, riser, voids etc, will be accessed where possible and safe to do so.</p>
                        <p style="margin-top:20px;">Occupied areas during surveys can impose restrictions on sampling and investigation.</p>
                        <p style="margin-top:20px;">All reasonable efforts are made to access and find any concealed asbestos, e.g. below floor ducts, in ceiling voids and inside convector heaters. However, because of the way that asbestos is used in composite structures and inaccessible places it cannot be guaranteed that all asbestos materials have been located during the surveys.</p>
                        <p style="margin-top:20px;"><strong>MATERIAL ASSESSMENT ALGORITHM SCORES (MAS) - HSG264</strong></p>
                        <p style="margin-top:20px;">Number scores are allocated to ACM depending on product type, extent of damage/ deterioration, surface treatment and asbestos type (which shall be scored as Crocidolite (blue) asbestos unless similar samples show otherwise, or it is likely that another type of asbestos is almost always used).</p>
                        <p style="margin-top:20px;">ACM with scores of 10 or more are regarded as a high potential to release fibres if disturbed, 7- 9 medium potential, 5-6 low potential and 4 or less very low potential.</p>
                        <p style="margin-top:20px;">These scores and other recorded observations, which are perceived as being likely to affect the release of asbestos fibres, are then used to allocate a risk code, which provides some basic advice on how the ACM should be treated in our opinion.</p>
                        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top: 20px;margin-bottom: 20px;">
                            <tbody>
                            <tr>
                                <strong>MATERIAL ALGORITHM ASSESSMENT SCORE (MAS)</strong>
                            </tr>
                            <tr>
                                <td><strong>Sample Variable</strong></td>
                                <td><strong>Score</strong></td>
                                <td><strong>EXAMPLE</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Product type</strong></td>
                                <td>1</td>
                                <td>Asbestos reinforced composites (plastics, resins, mastics, roofing felts, vinyl floor tiles, semi-rigid paints or decorative finishes, asbestos cement products etc).</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>2</td>
                                <td>Asbestos insulation board, mill board, other low density boards, asbestos ropes and woven textiles, gaskets, asbestos paper and felt</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>3</td>
                                <td>Insulation (pipe and boiler lagging, spray coating, loose asbestos.</td>
                            </tr>
                            <tr>
                                <td><strong>Extent of damage / deterioration</strong></td>
                                <td>0</td>
                                <td>Good condition; no visible damage.</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>1</td>
                                <td>Low damage; scratches or surface marks; broken edges to boards, tiles etc</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>2</td>
                                <td>Medium damage; significant breakage of materials or several small areas where material has been damaged revealing loose fibres.</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>3</td>
                                <td>High damage or delamination of materials, sprays and thermal insulation. Visible asbestos debris</td>
                            </tr>
                            <tr>
                                <td><strong>Surface treatment</strong></td>
                                <td>0</td>
                                <td>Composite materials containing asbestos; reinforced plastics, resins, vinyl tiles</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>1</td>
                                <td>Enclosed sprays and lagging, AIB (with exposed face painted or encapsulated) cement sheets etc.</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>2</td>
                                <td>Unsealed AIB, or encapsulated lagging and sprays</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>3</td>
                                <td>Unsealed lagging and sprays.</td>
                            </tr>
                            <tr>
                                <td><strong>Asbestos type</strong></td>
                                <td>1</td>
                                <td>Chrysotile</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>2</td>
                                <td>Amphibole asbestos excluding Crocidolite</td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td>3</td>
                                <td>Crocidolite</td>
                            </tr>
                            </tbody>
                        </table>
                        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top: 20px;margin-bottom: 20px;">
                            <tbody>
                            <tr><strong>RISK CODE TABLE</strong></tr>
                            <tr>
                                <td><strong>RISK CODE</strong></td>
                                <td><strong>MANAGEMENT RECOMMENDATIONS</strong></td>
                            </tr>
                            <tr>
                                <td><strong>A</strong></td>
                                <td>Restrict access to area immediately. Remove by licence asbestos contractors under controlled conditions in accordance with CAR2012.</td>
                            </tr>
                            <tr>
                                <td><strong>B</strong></td>
                                <td>Remove or repair by licensed contractors in accordance with CAR2012.</td>
                            </tr>
                            <tr>
                                <td><strong>C</strong></td>
                                <td>Encapsulate by licensed contractor in accordance with CAR 2012. Where appropriate label with warning signs on completion. Undertake routine re-inspections.</td>
                            </tr>
                            <tr>
                                <td><strong>D</strong></td>
                                <td>High Risk ACM in good condition, encapsulation intact. Where appropriate label with warning signs. Undertake routine re-inspections for damage or deterioration in accordance with asbestos management plan and CAR 2012.</td>
                            </tr>
                            <tr>
                                <td><strong>E</strong></td>
                                <td>Low risk ACM (Bound in matrix). Where appropriate label with warning signs. Undertake routine inspections for damage and deterioration. Where damaged, remove or repair in accordance with CAR2012. Reg 3(2).</td>
                            </tr>
                            <tr>
                                <td><strong>F</strong></td>
                                <td>Inaccessible room or item, maintain presumption of asbestos until accessed.</td>
                            </tr>
                            </tbody>
                        </table>
                        <p style="margin-top:20px;">Tersus always recommends the use of licensed asbestos removal contractors undertaking all works in accordance with the Control of Asbestos Regulations (CAR2012).</p>
                        <p style="margin-top:20px;">Asbestos management survey (HSG264) investigations only were undertaken; limitations of the survey should be noted and areas not fully accessed must be presumed to contain asbestos products unless further investigation proves otherwise.</p>
                        <p style="margin-top:20px;">Should you require any further assistance please do not hesitate to contact Tersus.</p>
                @endif
        <?php } elseif ($survey->survey_type == 2) { ?>
                        <p style="margin-top:20px;">Refurbishment asbestos survey (HSG264). This type of survey is used to locate and describe, as far as is reasonably practical Asbestos containing materials (ACMs) in the building and may involve destructive inspection, as necessary, to gain access to areas, including those that may be difficult to reach. The survey is designed to be used as a basis for the removal of ACMs or the planning of routes around ACMs within a project specification prior to the refurbishment works occurring</p>
                        <p style="margin-top:20px;">Every effort has been made to identify all asbestos materials so far as was reasonably practical to do so within the scope of the survey and the attached report. Methods used to carry out the survey were agreed with the client prior to any works being commenced. The aim of these inspections was to produce a refurbishment survey of the aforementioned building.</p>
                        <p style="margin-top:20px;">All reasonable attempts were made to access all areas within the scope of the survey. Areas not accessed are reported in the executive summary, non accessible areas register.</p>
                        <p style="margin-top:20px;">Due to the inherent risk to health, Refurbishment asbestos surveys are only conducted in un-occupied buildings or sites which will remain un-occupied until any remedial or removal measures have been undertaken. If a site is to be re-occupied the requirement for testing for reoccupation will have been discussed with the client and will be dependent on the finding within this report and condition of any asbestos materials found.</p>
                        <p style="margin-top:20px;">Refurbishment asbestos surveys require substantial disruption to the building; the level of this destructive element will be agreed with the client in contract review prior to the site survey taking place. The level of intrusive inspection will be tailored to the customers’ requirements as detailed in the refurbishment project plan. This may involve partial demolition of risers, ducts, opening up of access hatches locked or blocked doors, etc. or be a little as testing for the purpose of redecoration. However intrusive inspections cannot be accomplished without safeguards being in place and the building being empty otherwise limitations will have to be employed.</p>
                        <p style="margin-top:20px;">Although every effort was made to access all areas of the building it is possible that concealed cavities, floor voids etc will only be accessible during demolition, and therefore contingencies must be made to include the potential risks that asbestos containing materials may remain unidentified in the property or area covered by that survey.</p>
                        <p style="margin-top:20px;">The extent and assessment of asbestos materials was determined by visible evidence on site with bulk sampling and analysis to confirm the surveyors judgement. The investigation includes an evaluation of its deterioration and homogeneity.</p>
                        <p style="margin-top:20px;">Samples were collected with due diligence and in line with our survey and sampling in house procedures, we are accredited to ISO 17020:2012 and ISO 17025:2005. Unless requested otherwise a label bearing the sample reference number is then adhered to the area sampled where practicable.</p>
                        <p style="margin-top:20px;">The surveyor will record basic inspection notes and conclude that no asbestos is presumed or identified for that room or area. Look-a-like materials will be sampled to support the surveyors judgement.</p>
                        <p style="margin-top:20px;">Any Intrusive elements of the survey will normally be completed by at least two surveyors in full RPE and PPE unless a site specific risk assessment deems otherwise.</p>
                        <p style="margin-top:20px;">In general terms it is the policy of this company to take samples where appropriate in order to prove the existence or otherwise of asbestos containing materials. On occasions where the report states 'presumed', 'strongly presumed' or 'no asbestos presumed', the surveyor will already have made his or her judgement, on the basis of 'reasoned argument' and with regard to their experience of similar materials. Where items have sample numbers reported “As sample NW00067” these results are strongly presumed to have the same asbestos content as identical homogenous materials that have been sampled and are related to the result of the sample to which they refer</p>
                        <p style="margin-top:20px;">Materials are reported as 'strongly presumed' where the material appears to contain asbestos, but analysis has not been undertaken. Materials will be strongly presumed in the following scenarios</p>
                        <p style="margin-top:20px;">
                        <ul>
                            <li>or based on a sample of homogenous material,</li>
                            <li>based on the knowledge and experience of the surveyor,</li>
                            <li>where materials have the appearance of asbestos or fibres are clearly visible.</li>
                            <li>where the materials might contain asbestos</li>
                        </ul>
                    </p>
                    <p style="margin-top:20px;">Materials are reported as 'presumed' where asbestos materials may be present but are not accessible to inspect, assess nor sample ie there is insufficient evidence that is it asbestos free.</p>
                    <p style="margin-top:20px;"><strong>Inspection Procedure</strong></p>
                    <p style="margin-top:20px;">Each room or designated area is inspected individually noting any building materials, which may contain asbestos. All heating, ventilation, services, riser, voids etc, will be accessed where possible and safe to do so.</p>
                    <p style="margin-top:20px;">Occupied areas during surveys can impose restrictions on sampling and investigation.</p>
                    <p style="margin-top:20px;">All reasonable efforts are made to access and find any concealed asbestos, e.g. below floor ducts, in ceiling voids and inside convector heaters. However, because of the way that asbestos is used in composite structures and inaccessible places it cannot be guaranteed that all asbestos materials have been located during the surveys.</p>
                    <p style="margin-top:20px;"><strong>MATERIAL ASSESSMENT ALGORITHM SCORES (MAS) - HSG264</strong></p>
                    <p style="margin-top:20px;">Number scores are allocated to ACM depending on product type, extent of damage/ deterioration, surface treatment and asbestos type (which shall be scored as Crocidolite (blue) asbestos unless similar samples show otherwise, or it is likely that another type of asbestos is almost always used).</p>
                    <p style="margin-top:20px;">ACM with scores of 10 or more are regarded as a high potential to release fibres if disturbed, 7- 9 medium potential, 5-6 low potential and 4 or less very low potential.</p>
                    <p style="margin-top:20px;">These scores and other recorded observations, which are perceived as being likely to affect the release of asbestos fibres, are then used to allocate a risk code, which provides some basic advice on how the ACM should be treated in our opinion.</p>
                    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top: 20px;margin-bottom: 20px;">
                        <tbody>
                            <tr><strong>MATERIAL ALGORITHM ASSESSMENT SCORE (MAS)</strong></tr>
                            <tr>
                                <th><strong>Sample Variable</strong></th>
                                <th><strong>Score</strong></th>
                                <th><strong>EXAMPLE</strong></th>
                            </tr>
                            <tr>
                                <th><strong>Product type</strong></th>
                                <th>1</th>
                                <th>Asbestos reinforced composites (plastics, resins, mastics, roofing felts, vinyl floor tiles, semi-rigid paints or decorative finishes, asbestos cement products etc).</th>
                            </tr>
                            <tr>
                                <th><strong></strong></th>
                                <th>2</th>
                                <th>Asbestos insulation board, mill board, other low density boards, asbestos ropes and woven textiles, gaskets, asbestos paper and felt</th>
                            </tr>
                            <tr>
                                <th><strong></strong></th>
                                <th>3</th>
                                <th>Insulation (pipe and boiler lagging, spray coating, loose asbestos.</th>
                            </tr>
                            <tr>
                                <th><strong>Extent of damage / deterioration</strong></th>
                                <th>0</th>
                                <th>Good condition; no visible damage.</th>
                            </tr>
                            <tr>
                                <th><strong></strong></th>
                                <th>1</th>
                                <th>Low damage; scratches or surface marks; broken edges to boards, tiles etc</th>
                            </tr>
                            <tr>
                                <th><strong></strong></th>
                                <th>2</th>
                                <th>Medium damage; significant breakage of materials or several small areas where material has been damaged revealing loose fibres.</th>
                            </tr>
                            <tr>
                                <th><strong></strong></th>
                                <th>3</th>
                                <th>High damage or delamination of materials, sprays and thermal insulation. Visible asbestos debris</th>
                            </tr>
                            <tr>
                                <th><strong>Surface treatment</strong></th>
                                <th>0</th>
                                <th>Composite materials containing asbestos; reinforced plastics, resins, vinyl tiles</th>
                            </tr>
                            <tr>
                                <th><strong></strong></th>
                                <th>1</th>
                                <th>Enclosed sprays and lagging, AIB (with exposed face painted or encapsulated) cement sheets etc.</th>
                            </tr>
                            <tr>
                                <th><strong></strong></th>
                                <th>2</th>
                                <th>Unsealed AIB, or encapsulated lagging and sprays</th>
                            </tr>
                            <tr>
                                <th><strong></strong></th>
                                <th>3</th>
                                <th>Unsealed lagging and sprays.</th>
                            </tr>
                            <tr>
                                <th><strong>Asbestos type</strong></th>
                                <th>1</th>
                                <th>Chrysotile</th>
                            </tr>
                            <tr>
                                <th><strong></strong></th>
                                <th>2</th>
                                <th>Amphibole asbestos excluding Crocidolite</th>
                            </tr>
                            <tr>
                                <th><strong></strong></th>
                                <th>3</th>
                                <th>Crocidolite</th>
                            </tr>
                        </tbody>
                    </table>
                    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top: 20px;margin-bottom: 20px;">
                        <tbody>
                            <tr>RISK CODE TABLE</tr>
                            <tr>
                                <th><strong>RISK CODE</strong></th>
                                <th><strong>MANAGEMENT RECOMMENDATIONS</strong></th>
                            </tr>
                            <tr>
                                <th>A</th>
                                <th>Restrict access to area immediately. Remove by licence asbestos contractors under controlled conditions in accordance with CAR2012.</th>
                            </tr>
                            <tr>
                                <th>B</th>
                                <th>Remove or repair by licensed contractors in accordance with CAR2012.</th>
                            </tr>
                            <tr>
                                <th>C</th>
                                <th>Encapsulate by licensed contractor in accordance with CAR 2012. Where appropriate label with warning signs on completion. Undertake routine re-inspections.</th>
                            </tr>
                            <tr>
                                <th>D</th>
                                <th>High Risk ACM in good condition, encapsulation intact. Where appropriate label with warning signs. Undertake routine re-inspections for damage or deterioration in accordance with asbestos management plan and CAR 2012.</th>
                            </tr>
                            <tr>
                                <th>E</th>
                                <th>Low risk ACM (Bound in matrix). Where appropriate label with warning signs. Undertake routine inspections for damage and deterioration. Where damaged, remove or repair in accordance with CAR2012. Reg 3(2).</th>
                            </tr>
                            <tr>
                                <th>F</th>
                                <th>Inaccessible room or item, maintain presumption of asbestos until accessed.</th>
                            </tr>
                        </tbody>
                    </table>
                    <p style="margin-top:20px;">Tersus always recommends the use of licensed asbestos removal contractors undertaking all works in accordance with the Control of Asbestos Regulations (CAR2012).</p>
                    <p style="margin-top:20px;">Refurbishment (HSG264) investigations only were undertaken; limitations of the survey should be noted and areas not fully accessed must be presumed to contain asbestos products unless further investigation proves otherwise.</p>
                    <p style="margin-top:20px;">Should you require any further assistance please do not hesitate to contact Tersus.</p>
        <?php }
            }
        } else { ?>
            <p style="margin-top:20px;">The following Access Requirements have been agreed at the Quotation Stage for
                the <?= $survey->survey_type_text ?>
                with <?= $survey->client->name ?>. Intrusive access and other access provision
                are documented below:</p>
            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
                   style="margin-top: 20px;margin-bottom: 20px;">
                <thead>
                <tr>
                    <th width="35%">Intrusive Access Requirements</th>
                    <th width="10%">Scope</th>
                    <th width="55%">Specific Allowance Comments</th>
                </tr>
                </thead>
                <tbody>
                @if(!$survey_method_question_data->isEmpty())
                    @foreach($survey_method_question_data as $dataRow)
                        @php
                            //dd($dataRow);
                        @endphp
                        @if(isset($dataRow->question_id))
                            @if(in_array($dataRow->question_id, [1885, 1886]))
                                @if($dataRow->question_id == 1885)
                                    <thead>
                                    <tr>
                                        <th colspan="3">The use of forced access to locked locations identified on the day of the survey
                                            would be individually assessed and may be subject to an additional site visit which would be
                                            subject to an additional cost.
                                        </th>
                                    </tr>
                                    </thead>
                                    <tr>
                                        <td>{{ isset($dataRow->dropdownQuestionData->description) ? $dataRow->dropdownQuestionData->description : '' }}</td>
                                        <td>{{ isset($dataRow->dropdownAnswerData->description) ? $dataRow->dropdownAnswerData->description : '' }}</td>
                                        <td>{!! $dataRow->comment !!}</td>
                                    </tr>
                                @else
                                    <thead>
                                    <tr>
                                        <th colspan="3">{{ isset($dataRow->dropdownQuestionData->description) ? $dataRow->dropdownQuestionData->description : '' }}</th>
                                    </tr>
                                    </thead>
                                    <tr>
                                        <td colspan="3">{!! $dataRow->comment !!}</td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td>{{ isset($dataRow->dropdownQuestionData->description) ? $dataRow->dropdownQuestionData->description : '' }}</td>
                                    <td>{{ isset($dataRow->dropdownAnswerData->description) ? $dataRow->dropdownAnswerData->description : '' }}</td>
                                    <td>{!! $dataRow->comment !!}</td>
                                </tr>
                                @endif
                                @endif
                                @endforeach
                                @endif
                                </tbody>
            </table>
        <?php
            }
        ?>

        <div style="page-break-before: always;"></div>
        <div class="topTitleBig" style="margin-top:30px;">Survey Information</div>
        <h3 style="margin-top:20px;">Limitations</h3>
        <?php
        if ($survey->surveyInfo->limitations) {
            echo $survey->surveyInfo->limitations;
        } elseif ($survey->client_id == LIFE_ENVIRONMENTAL){ ?>
            <p style="margin-top:20px;">Due to <?= $surveyTitle ?> being non-intrusive in their nature, asbestos may remain
                unidentified within common locations where non-intrusive inspection would not
                normally be possible, for example: </p>
            <ul type="disc" style="margin-top:20px;">
                <li>As internal linings to fire doors and hatches</li>
                <li>As packing around door and window frames</li>
                <li>Within the fabric of the building including cavity walls, floor voids and foundations, etc.</li>
                <li>Behind or within fixed wall linings, fixed boxings, fixed ceilings, etc.</li>
                <li>Below fixed floor coverings</li>
                <li>Below existing felt and bitumen roof coverings</li>
                <li>Within drainage systems and below ground services</li>
                <li>Within chimneys and chimney breasts</li>
                <li>Residual asbestos material may be present beneath re-insulated services and cannot be detected unless
                    the insulation is systematically removed. Caution should therefore be taken when working on such
                    materials for the potential presence of asbestos residue
                </li>

            </ul>

        <?php
        } elseif ($survey->client_id == LUCION_ENVIRONMENTAL) {
        ?>
            <p style="margin-top:20px;">Every effort has been made to identify all asbestos materials so far as was reasonably practical to do so within the scope of the survey and the attached report. Methods used to carry out the survey were agreed with the client prior to any works being commenced by way of acceptance of our contract / quotation.</p>

            <p style="margin-top:20px;">This survey was conducted in accordance with Health and Safety Guidance - Publication "Asbestos: The Survey Guide (HSG 264)". Lucion Environmental Ltd cannot accept any liability for loss, injury, damage or penalty issues that arise for reasons of survey scope limitations. </p>

            <p style="margin-top:20px;">Lucion Environmental Ltd cannot be held responsible for any damage caused as part of this survey carried out on your behalf. Due to the nature and necessity of sampling for asbestos some damage is unavoidable and will be limited to that necessary for taking of the samples. </p>

            <p style="margin-top:20px;">The "Inaccessible Room/Locations” and “Inaccessible Items" section of this report gives details of             those buildings, locations and items not accessed at the time of the survey (where appropriate, and if                 all areas were fully accessed, no items are listed). The "Property Information" above gives details of                those buildings included in the survey scope. The inspection log should be referenced for details of                specific locations inspected within these buildings. </p>

            <p style="margin-top:20px;">The scope of this survey relates only to building or area(s) inspected and does not include any form of                   investigation of the land on which the building is situated. </p>

            <p style="margin-top:20px;">Where investigation of an intrusive nature (within the scope of the survey being performed) is needed                to discern the presence of a material and the property is occupied during the inspection the level of                  intrusion may be restricted. As far as is reasonably practicable such restrictions will be indicated               within the "areas excluded & not fully accessed during survey" section of this report. Scenarios               leading to intrusion restriction may [by way of example] include (but are not limited to) security                integrity of the building envelope, significant damage to decorative finishes, risk to the structural              integrity of the building, occupation within adjacent areas. Investigations undertaken in such situations             may, through circumstantial restrictions, be incomplete. Further investigation works may be required            once unrestricted access can be offered. </p>

            <p style="margin-top:20px;">A report is provided electronically via the Shine web-portal. Prior to commencing any works or review                of the report, the most current version should be obtained via Shine; any local hard copies should not                  be relied on as containing the most current information. </p>

            <p style="margin-top:20px;"><strong>Items or areas not covered by this survey that are scheduled to undergo works that may result in the release of asbestos fibres should be investigated prior to commencement of such activities.</strong></p>

        <?php }elseif ($survey->client_id == TERSUS_SURVEYING) {
        ?>
                @if($survey->survey_type == 1)
                    @if($survey->surveySetting->is_require_r_and_d_elements == 1)
                        <table border="1" cellpadding="1" cellspacing="1" rules="ALL" frame="BOX"
                               style="margin-top: 20px;margin-bottom: 20px;">
                            <tr>
                                <td><b>Potential Access restrictions</b></td>
                                <td><p style="margin-bottom:20px;">Access to the following items can be potentially restricted. This is normally due to the items being live or in confined or sealed environments. Where known and identifiable these are documented within the survey report, but where concealed and subsequently discovered that were previously unknown to the surveyor at the time of the survey these should be presumed to conceal some form of asbestos. Full access to these areas may require specialist contractors or extensive demolition.</p>
                                    <p style="margin-bottom:20px;">Service ducts, risers, voids and cavities (concealed under floors, in voids etc.)</p>
                                    <p style="margin-bottom:20px;">Live mechanical and electrical services (presumptions as to asbestos content will be made) Lift shafts</p>
                                    <p style="margin-bottom:20px;">Un-boarded or unsafe roof / loft spaces and any area at excessive height (presumptions will be made and attempts to make safe /access at height will be discussed with the client)</p>
                                    <p style="margin-bottom:20px;">Internal fire proofing materials (i.e. fire doors etc.)</p>
                                </td>
                            </tr>

                            <tr>
                                <td><b>Furniture, Fixtures and Fittings</b></td>
                                <td><p style="margin-bottom:20px;">Furniture, fixtures or fittings shall be moved where possible during the survey. Access to areas obstructed by these items where known will be restricted and have been recorded within the survey report. </p>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Reasonable access</b></td>
                                <td><p style="margin-bottom:20px;">Access to voids, risers, ducts etc. was made through existing removable access hatches, panels, ceiling tiles etc. which can be replaced in the same condition. Where excessive damage is required especially in occupied areas this will be recorded as no access. </p></br>
                                    No access was made through known or presumed asbestos containing materials as part of this management survey.</p>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Materials at height</b></td>
                                <td><p style="margin-bottom:20px;">Where materials exist at a height beyond which it was reasonably practical to access the materials have been visually determined and presumed.</p>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Plans</b></td>
                                <td><p style="margin-bottom:20px;">If plans of the premises to be inspected are not made available, it cannot be ascertained if all areas have been identified or accessed. </p>
                                    <p style="margin-bottom:20px;">All premises will be hand sketched in order to avoid misinterpretation, however in complex premises Tersus cannot guarantee that all areas have been identified.It is the client’s responsibility to check the supplied drawing and to highlight any concealed or obstructed areas not shown on sketches.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    @else
                        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
                                   style="margin-top: 20px;margin-bottom: 20px;">
                                <tbody>
                                <tr>
                                    <td><strong>Potential Access restrictions</strong></td>
                                    <td>
                                        <p style="margin-bottom:20px;">Access to the following items can be potentially restricted. This is
                                            normally due to the items being live or in confined or sealed environments. Where known and
                                            identifiable these are documented within the survey report, but where concealed and
                                            subsequently discovered that were previously unknown to the surveyor at the time of the
                                            survey these should be presumed to conceal some form of asbestos. Full access to these areas
                                            may require specialist contractors or extensive demolition.</p>
                                        <p style="margin-bottom:20px;">Service ducts, risers, voids and cavities (concealed under floors,
                                            in voids etc.)</p>
                                        <p style="margin-bottom:20px;">Live mechanical and electrical services (presumptions as to asbestos
                                            content will be made) Lift shafts</p>
                                        <p style="margin-bottom:20px;">Un-boarded or unsafe roof / loft spaces and any area at excessive
                                            height (presumptions will be made and attempts to make safe /access at height will be
                                            discussed with the client)</p>
                                        <p style="margin-bottom:20px;">Internal fire proofing materials (i.e. fire doors etc.)</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Furniture, Fixtures and Fittings</strong></td>
                                    <td>
                                        <p style="margin-bottom:20px;">Furniture, fixtures or fittings shall be moved where possible during
                                            the survey. Access to areas obstructed by these items where known will be restricted and
                                            have been recorded within the survey report.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Reasonable access</strong></td>
                                    <td>
                                        <p style="margin-bottom:20px;">Access to voids, risers, ducts etc. was made through existing
                                            removable access hatches, panels, ceiling tiles etc. which can be replaced in the same
                                            condition. Where excessive damage is required especially in occupied areas this will be
                                            recorded as no access.</p>
                                        <p style="margin-bottom:20px;">No access was made through known or presumed asbestos containing
                                            materials as part of this management survey.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Materials at height</strong></td>
                                    <td>
                                        <p style="margin-bo:20px;">Where materials exist at a height beyond which it was reasonably
                                            practical to access the materials have been visually determined and presumed.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Plans</strong></td>
                                    <td>
                                        <p style="margin-bottom:20px;">If plans of the premises to be inspected are not made available, it
                                            cannot be ascertained if all areas have been identified or accessed.</p>
                                        <p style="margin-bottom:20px;">All premises will be hand sketched in order to avoid
                                            misinterpretation, however in complex premises Tersus cannot guarantee that all areas have
                                            been identified.</p>
                                        <p style="margin-bottom:20px;">It is the client’s responsibility to check the supplied drawing and
                                            to highlight any concealed or obstructed areas not shown on sketches.</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                    @endif
                @elseif($survey->survey_type == 2)
                    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top: 20px;margin-bottom: 20px;">
                            <tbody>
                            <tr>
                                <td><strong>Mechanical & Electrical installations</strong></td>
                                <td>Where these are live and cannot be isolated presumptions as to typical asbestos in electrical plant has been made within the reports</td>
                            </tr>
                            <tr>
                                <td><strong>Reasonable access</strong></td>
                                <td>Access limitations and requirements will be pre-determined in accordance with the client’s requirements.</td>
                            </tr>
                            <tr>
                                <td><strong>Fire Doors</strong></td>
                                <td>
                                    <p style="margin-top:20px">Doors were only destructively inspected where doing so did not adversely affect the security or safety of the premises. These have been recorded in the report</p>
                                    <p style="margin-top:20px">No access was made through known or presumed asbestos containing materials as part of this Refurbishment survey.</p>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Plans</strong></td>
                                <td>
                                    <p style="margin-top:20px">If plans of the premises to be inspected are not made available, it cannot be ascertained if all areas have been identified or accessed.</p>
                                    <p style="margin-top:20px">All premises will be hand sketched in order to avoid misinterpretation, however in complex premises Tersus cannot guarantee that all areas have been identified.</p>
                                    <p style="margin-top:20px">It is the client’s responsibility to check the supplied drawing and to highlight any concealed or obstructed areas not shown on sketches.</p>
                                </td>
                            </tr>
                            </tbody>
                </table>
                @endif
        <?php }?>


        <div style="page-break-before: always;"></div>
        <div id="Specifc-Exclusions" style="margin-top:30px;">
            <div class="topTitleBig">Survey Information</div>
            <h3 style="margin-top:20px;">Specific Exclusions</h3>
            <p style="margin-top:20px;">Where detailed, it was agreed at the pre-survey stage that the following
                room/locations would be excluded from the scope of Survey. The room/locations do not
                include more general exclusions (i.e. inaccessible room/locations/items) detailed
                elsewhere.</p>
            <?php if ($survey->survey_type != 2 && $survey->surveySetting->is_require_r_and_d_elements == 0) { ?>
            <p style="margin-top:20px">The survey was limited to those areas accessible at the time of the survey
                (and
                as agreed at the pre-survey stage). Flues, ducts, voids or any similarly enclosed
                areas, have not been inspected (unless an appropriate access hatch or inspection
                panel was present), as gaining such access would necessitate the use of
                specialist equipment/tools or require overly destructive work.</p>
            <?php } ?>
            <p style="margin-top:20px">No responsibility is accepted for the presence of asbestos in voids (under floor,
                floor, wall or ceiling) other than those opened up during the investigation (unless
                agreed at the pre-survey stage).</p>

            <p style="margin-top:20px">Areas requiring specialist access arrangements or equipment (other than
                stepladders)
                will not be assessed unless otherwise stated and agreed at the pre-survey stage. Fire
                doors were not inspected internally to ascertain if they are manufactured using ACMs
                as to do so would entail overly destructive testing procedures.</p>

            <?php if ($survey->survey_type != 2 && $survey->surveySetting->is_require_r_and_d_elements == 0) { ?>
            <p style="margin-top:20px">Whilst every effort will have been made to identify the true nature and
                extent of
                the asbestos material present in the building surveyed, no responsibility has
                been accepted for the presence of asbestos in materials other than those sampled
                at the requisite density. Inspection of pipe work has been restricted primarily
                to the insulation visible (sampled in accordance with HSG264 guidelines),
                therefore only a limited inspection has been carried out of pipework concealed by
                overlaying non-asbestos insulation.</p>
            <?php
            } ?>
        </div><!--Survey Information PAGE 08-->

    <div style="page-break-before: always;"></div>
    <div>
        <h2>Survey Results</h2>
        <h3 style="margin-top:20px;">Recommendations</h3>
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top: 10px;margin-bottom: 30px;">
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
            <?php
            if (count($action_recommendation_items)) {
            // item was Sorted in controller
            foreach ($action_recommendation_items as $item) {
            ?>
            <tr>
                <td style="width:100px;"><?= $item->name ?? ''; ?></td>
                <td style="width:80px;"><?= $item->sample->description ?? "N/A"; ?></td>
                <td style="width:140px;"><?= $item->productDebrisView->product_debris ?? ''; ?></td>
                <td style="width:65px;"><?= $item->area->title_presentation ?? ''; ?></td>
                <td style="width:100px;"><?= $item->location->title_presentation ?? ''; ?></td>
                <td style="width:200px;"><?= $item->actionRecommendationView->action_recommendation ?? ''; ?></td>
            </tr>
            <?php
            }
            } else {
                echo "<tr><td colspan='6'>No Action/recommendations Found</td></tr>";
            }
            ?>
            </tbody>
        </table>

        <h3 style="margin-bottom: 0;">Sample Summary</h3>

        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:20px;margin-bottom: 0;">
            <thead>
            <tr>
                <th width="10%">Sample</th>
                <th width="20%">Product/debris Type</th>
                <th width="10%">Area/floor</th>
                <th width="10%">Room/location</th>
                <th width="30%">Asbestos Type</th>
            </tr>
            </thead>
            <tbody>
            @if(count($samples))
                @foreach($samples as $sample)
                    <tr>
                        <td>{{$sample->sample->description ?? ''}}</td>
                        <td>{{$sample->productDebrisView->product_debris ?? ''}}</td>
                        <td>{{$sample->area->title_presentation ?? ''}}</td>
                        <td>{{$sample->location->title_presentation ?? ''}}</td>
                        <td>{{ (isset($sample->state) and ($sample->state == 1)) ? 'No ACM Detected' : ($sample->asbestosTypeView->asbestos_type ?? '')}}</td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan='6'>No Sample Found</td></tr>
            @endif
            </tbody>
        </table>
    </div><!--Survey Results Recommendations PAGE 14 -->

    @if(count($locations))
        @foreach($locations as $key => $location)
            <div style="page-break-before: always;">&nbsp;</div>
            @if($key == 0)
                <h3 style="margin-bottom: 20px;">Room/location Details
                    {{isset($survey->surveySetting->is_require_location_construction_details)
                     && $survey->surveySetting->is_require_location_construction_details > 0 ? "including Construction Details" : ""}}</h3>
            @endif
            <div style="width:99%;">
                <div class="row">
                    <div style="display:inline-block;">
                        <img width="200" src="{{ \CommonHelpers::getFile($location->id, LOCATION_IMAGE, $is_pdf) }}" alt="Location Image"/>
                    </div>
                </div>
                <div class="row">
                    <div style="display:inline-block;width:99%;">
                        <div class="row">
                            <div class="topTitleSmall">Room/location Details</div>
                            <div class="row">
                                <p style="display:inline-block;width:49%;text-align:left;">Room/location Reference:</p>
                                <p style="display:inline-block;width:49%;text-align:left;">{{$location->location_reference ?? ''}}</p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:49%;text-align:left;">Room/location Description:</p>
                                <p style="display:inline-block;width:49%;text-align:left;">{{$location->description ?? ''}}</p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:49%;text-align:left;">Area/floor Reference:</p>
                                <p style="display:inline-block;width:49%;text-align:left;">{{$location->area->area_reference ?? ''}}</p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:49%;text-align:left;">Area/floor Description:</p>
                                <p style="display:inline-block;width:49%;text-align:left;">{{$location->area->description ?? ''}}</p>
                            </div>
                            <div class="row">
                                @if($location->state == LOCATION_STATE_ACCESSIBLE)
                                    <p style="display:inline-block;width:49%;text-align:left;">Accessibility:</p><p style="display:inline-block;width:49%;text-align:left;">&nbsp;Accessible</p>
                                @elseif($location->state == LOCATION_STATE_INACCESSIBLE)
                                    <p style="display:inline-block;width:49%;text-align:left;">Accessibility:</p><p style="display:inline-block;width:49%;text-align:left;">&nbsp;Inaccessible</p><br />
                                    <p style="display:inline-block;width:49%;text-align:left;">Reason for No Access: </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">{{" ".\CommonHelpers::getLocationVoidDetails(optional($location->locationInfo)->reason_inaccess_key, optional($location->locationInfo)->reason_inaccess_other )}}</p>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <p>&nbsp;</p>
                            <p style="display:inline-block;width:49%;text-align:left;">Total ACMs:</p>
                            <p style="display:inline-block;width:49%;text-align:left;">
                                {{isset($location_items[$location->id]['total_acm_item']) ? $location_items[$location->id]['total_acm_item'] : 0}}
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;width:49%;text-align:left;">Total NoACMs:</p>
                            <p style="display:inline-block;width:49%;text-align:left;">
                                {{isset($location_items[$location->id]['total_noacm_item']) ? $location_items[$location->id]['total_noacm_item'] : 0}}
                            </p>
                            <p>&nbsp;</p>
                        </div>
                    </div>
                </div>

                @if(optional($survey->surveySetting)->is_require_location_void_investigations)
                    <div style="width:99%;">
                        <div class="row">
                            <div class="topTitleSmall" style="margin-bottom: 20px;">Room/location Void Investigations</div>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;"><span style="width:70px; margin-right:15px;"><strong>Ceiling Void:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->ceiling, optional($location->locationVoid)->ceiling_other ) ?>
                            </p>

                        </div>
                        <div class="row">
                            <p style="display:inline-block;"><span style="width:70px; margin-right:15px;"><strong>Floor Void:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->floor, optional($location->locationVoid)->floor_other ) ?>
                            </p>

                        </div>
                        <div class="row">
                            <p style="display:inline-block;">
                                <span style="width:70px; margin-right:15px;"><strong>Cavities:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->cavities, optional($location->locationVoid)->cavities_other ) ?>
                            </p>

                        </div>
                        <div class="row">
                            <p style="display:inline-block;">
                                <span style="width:70px; margin-right:15px;"><strong>Risers:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->risers, optional($location->locationVoid)->risers_other ) ?>
                            </p>

                        </div>
                        <div class="row">
                            <p style="display:inline-block;">
                                <span style="width:70px; margin-right:15px;"><strong>Ducting:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->ducting, optional($location->locationVoid)->ducting_other ) ?>
                            </p>

                        </div>
                        <div class="row">
                            <p style="display:inline-block;">
                                <span style="width:70px; margin-right:15px;"><strong>Boxing:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->boxing, optional($location->locationVoid)->boxing_other ) ?>
                            </p>

                        </div>
                        <div class="row">
                            <p style="display:inline-block;">
                                <span style="width:70px; margin-right:15px;"><strong>Pipework:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationVoid)->pipework, optional($location->locationVoid)->pipework_other ) ?>
                            </p>

                        </div>
                    </div>
                @endif

                @if(optional($survey->surveySetting)->is_require_location_construction_details)
                    <div style="width:99%;">
                        <div class="row">
                            <div class="topTitleSmall" style="margin-top: 20px; margin-bottom: 20px;">Room/location
                                Construction Details
                            </div>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;">
                                <span style="width:70px; margin-right:15px;"><strong>Ceiling:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->ceiling, optional($location->locationConstruction)->ceiling_other ) ?>
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;">
                                <span style="width:70px; margin-right:15px;"><strong>Walls:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->walls, optional($location->locationConstruction)->walls_other ) ?>
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;">
                                <span style="width:70px; margin-right:15px;"><strong>Floor:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->floor, optional($location->locationConstruction)->floor_other ) ?>
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;">
                                <span style="width:70px; margin-right:15px;"><strong>Doors:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->doors, optional($location->locationConstruction)->doors_other ) ?>
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;">
                                <span style="width:70px; margin-right:15px;"><strong>Windows:</strong></span>
                                <?= \CommonHelpers::getLocationVoidDetails(optional($location->locationConstruction)->windows, optional($location->locationConstruction)->windows_other ) ?>
                            </p>
                        </div>
                    </div>
                @endif
                <div style="width:99%;" class="row">
                    <p style="display:inline-block;"><span style="width:70px; margin-right:15px;"><strong>Comments:</strong></span>{!! $location->locationInfo->comments ?? ''  !!}
                    </p>
                </div>
            </div><!-- Location details -->
            <div style="width:99%;">
                    @if(isset($location_items[$location->id]['items']) && count($location_items[$location->id]['items']))
                        @foreach($location_items[$location->id]['items'] as $item)
                            <div style="margin-top: 20px; page-break-before: always">
                                <?php if ($survey->surveySetting->is_require_photos != 0) { ?>
                                <div style="width:99%;">
                                    <table BORDER=0 CELLPADDING=1 CELLSPACING=1>
                                        <tr>
                                            <!--<div style="display:inline-block;width:33.33%;">-->
                                            <td width="33.33%">
                                                @if(CommonHelpers::checkFile($item->id, ITEM_PHOTO_LOCATION))
                                                    <img width="200"   src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_LOCATION, $is_pdf) }}" />
                                                @endif
                                            </td>
                                            <!--<div style="display:inline-block;width:33.33%;">-->
                                            <td width="33.33%">
                                                @if(CommonHelpers::checkFile($item->id, ITEM_PHOTO))
                                                    <img width="200"   src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO, $is_pdf) }}" />
                                                @endif
                                            </td>
                                            <!--<div style="display:inline-block;width:33.33%;">-->
                                            <td width="33.33%">
                                                @if(CommonHelpers::checkFile($item->id, ITEM_PHOTO_ADDITIONAL))
                                                    <img width="200"   src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_ADDITIONAL, $is_pdf) }}" />
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <?php } ?>

                                <div class="topTitle" style="margin:0;padding:0; margin-top: 10px;">Item Detail</div>
                                <div class="row" style="margin:0;padding:0">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Item ID </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
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
                                <div class="row" style="margin:0;padding:0">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Item Assessment </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
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
                                <div class="row" style="margin:0;padding:0">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Reason for No Access </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        {{  \Str::replaceFirst('Other', '',($item->ItemNoAccessValue->ItemNoAccess->description ?? '') . ' ' . ($item->ItemNoAccessValue->dropdown_other ?? '')) }}
                                    </p>
                                </div>
                                <?php } ?>

                                @if(isset($item->sample->description))
                                    <div class="row">
                                        <p style="display:inline-block;width:49%;text-align:left;">
                                            <?php if($survey->client_id != 5) {
                                                if ($item->record_id == $item->sample->original_item_id) {
                                                    echo "Referenced to";
                                                } else {
                                                    echo "Sample Linked/ID";
                                                }
                                            }else{
                                                echo "Sample Linked/ID";
                                            } ?> </p>
                                        <p style="display:inline-block;width:49%;text-align:left;">
                                            {{$item->sample->description}}
                                        </p>
                                    </div>
                                @endif

                                <?php if (!empty($item->sample->sampleReference)) { ?>
                                <?php } ?>
                                <div class="row" style="margin-top:10px;">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Property Name </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        <?= $survey->property->name ?? ''; ?>
                                    </p>
                                </div>
                                <div class="row">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Area/floor </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        <?= $item->area->title_presentation ?? ''; ?>
                                    </p>
                                </div>
                                <div class="row">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Room/location </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        <?= $item->location->title_presentation ?? ''; ?>
                                    </p>
                                </div>
                                <div class="row">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Specific location </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        <?= $item->specific_location ?? ''; ?>
                                    </p>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Product/debris type </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        <?= $item->productDebrisView->product_debris ?? ''; ?>
                                    </p>
                                </div>
                                <?php if ($item->state == ITEM_ACCESSIBLE_STATE || $item->state == ITEM_INACCESSIBLE_STATE) { ?>
                                <div class="row">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Asbestos type </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        <?= $item->asbestosTypeView->asbestos_type ?? ''; ?>
                                    </p>
                                </div>
                                <?php }elseif ($item->state == ITEM_NOACM_STATE){ ?>
                                <div>
                                    <p style="display:inline-block;width:49%;">
                                        Asbestos type </p>
                                    <p style="display:inline-block;width:49%;">
                                        No ACM Detected
                                    </p>
                                </div>
                                <?php } ?>
                                <div class="row" style="margin-top: 10px;">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Extent </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        {{(isset($item->itemInfo->extent) and $item->itemInfo->extent) != '' ? (($item->itemInfo->extent ?? '').' '. ($item->extentView->extent ?? '')) : ''}}
                                    </p>
                                </div>
                                <?php if ($item->state != ITEM_NOACM_STATE) { ?>
                                <?php } else { ?>
                                <div class="row">
                                    <p style="display:inline-block;width:49%;text-align:left;">

                                    </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        <img src="{{ public_path('img/green-block.png') }}" width="16" alt=""/> No Risk
                                    </p>
                                </div>
                                <?php } ?>
                                <?php if ($item->state == ITEM_ACCESSIBLE_STATE) { ?><?php if (optional($survey->surveySetting)->is_require_license_status != 0) { ?>
                                <div class="row" style="margin-top: 10px;">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Licensed/non-licensed </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        {{$item->licensedNonLicensedView->licensed_non_licensed ?? 'N/A'}}
                                    </p>
                                </div>
                                <?php } ?><?php if (optional($survey->surveySetting)->is_require_r_and_d_elements != 0) { ?>
                                <div class="row">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        R&D Element: </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        {{optional($item->itemInfo)->is_r_and_d_element_text}}
                                    </p>
                                </div>
                                <?php } ?>
                                <div class="row">
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Air Test </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        {{''}}
                                    </p>
                                </div>
                                <?php } ?>

                                <div class="row" style="padding-top:10px;clear:both;">
                                    <div style="display:inline-block;width:49%">
                                        <?php if ($item->state != ITEM_NOACM_STATE) { ?>
                                        <div class="topTitle">Material Assessment</div>
                                        <div class="row">
                                            <p style="display:inline-block;width:69%">
                                                Product Type (a) </p>
                                            <p style="display:inline-block;width:29%">
                                                {{isset($item->product_type) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ? 'N/A' : $item->product_type) : 0}}
                                            </p>
                                        </div>
                                        <div class="row">
                                            <p style="display:inline-block;width:69%">
                                                Extent of Damage (b) </p>
                                            <p style="display:inline-block;width:29%">
                                                {{isset($item->extend_damage) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ?  'N/A' : $item->extend_damage) : 0}}
                                            </p>
                                        </div>
                                        <div class="row">
                                            <p style="display:inline-block;width:69%">
                                                Surface Treatment (c) </p>
                                            <p style="display:inline-block;width:29%">
                                                {{isset($item->surface_treatment) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ?  'N/A' : $item->surface_treatment) : 0}}
                                            </p>
                                        </div>
                                        <div class="row">
                                            <p style="display:inline-block;width:69%">
                                                Asbestos Fibre (d) </p>
                                            <p style="display:inline-block;width:29%">
                                                {{isset($item->asbestos_fibre) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ?  'N/A' : $item->asbestos_fibre) : 0}}
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
                                            <p style="display:inline-block;width:29%">
                                                <img src="{{public_path('img/'.CommonHelpers::getMasRiskColor($item->total_mas_risk).'-block.png')}}"
                                                     width="16" alt=""/> {{CommonHelpers::getMasRiskText($item->total_mas_risk)}}
                                            </p>
                                        </div>

                                        <?php if ($survey->surveySetting->is_require_priority_assessment != 0) { ?>
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
                                                <img src="{{public_path('img/'.CommonHelpers::getTotalText($item->total_risk)['color'].'-block.png')}}"
                                                     width="16" alt=""/> {{CommonHelpers::getTotalText($item->total_risk)['risk']}}
                                            </p>
                                        </div>
                                        <?php } ?><?php } ?>
                                    </div>

                                    <div style="display:inline-block;width:49%">
                                        <?php if ($item->state != ITEM_NOACM_STATE && $survey->surveySetting->is_require_priority_assessment != 0) { ?>
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
                                                <img src="{{public_path('img/'.CommonHelpers::getMasRiskColor($item->total_pas_risk).'-block.png')}}"
                                                     width="16" alt=""/> {{CommonHelpers::getMasRiskText($item->total_pas_risk)}}
                                            </p>
                                        </div>
                                        <?php } ?>
                                        <div class="topTitle" style="margin-top:20px;">Comments</div>
                                        <div class="row">
                                            <p style="display:inline-block;width:69%">
                                                {!! nl2br(optional($item->itemInfo)->comment) !!}
                                            </p>
                                            <p style="display:inline-block;width:29%">
                                                <span>&nbsp;</span>
                                            </p>
                                        </div>
                                    </div>

                                </div>


                                <?php if ($item->state != ITEM_NOACM_STATE) { ?>
                                <div class="row" style="width:99%;margin-top:20px;">
                                    <div class="topTitle">Actions/recommendations</div>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        Action/recommendations </p>
                                    <p style="display:inline-block;width:49%;text-align:left;">
                                        {{optional($item->actionRecommendationView)->action_recommendation}}
                                    </p>
                                </div>
                                <?php } ?>
                            </div>
                        @endforeach
                    @endif

            </div>
        @endforeach
    @endif

    <?php if (count($acm_items)) { ?>
    <div id="asbestos-register" style="margin-top:10px;page-break-before: always;">
        <h3 style="margin-top:10px;">Asbestos Register</h3>
    </div>

    <?php
    $asbestosRegisterCount = 0;
    foreach ($acm_items as $item) {
    if ($asbestosRegisterCount % 3 == 0 && $asbestosRegisterCount != 0) {
    ?>
    <div style="page-break-before: always;"></div>
    <div style="margin-top:60px;"></div>
    <?php } ?>
    <div style="margin-top:10px; font-size: 7pt;">
        <div style="display:inline-block;width:64%;margin-bottom:5px;">
            <div style="margin-top:3px;">
                <?php if ($survey->surveySetting->is_require_photos != 0) { ?>
                <div style="display:inline-block;width:30%;">
                    @if(CommonHelpers::checkFile($item->id, ITEM_PHOTO_LOCATION))
                        <img width="160"   src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_LOCATION, $is_pdf) }}" />
                        <p style='margin-top:5px'>Location</p>
                    @endif
                </div>
                <div style="display:inline-block;width:30%;">
                    @if(CommonHelpers::checkFile($item->id, ITEM_PHOTO))
                        <img width="160"   src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO, $is_pdf) }}" />
                        <p style='margin-top:5px'>Item</p>
                    @endif
                </div>
                <div style="display:inline-block;width:30%;">
                    @if(CommonHelpers::checkFile($item->id, ITEM_PHOTO_ADDITIONAL))
                        <img width="160"   src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_ADDITIONAL, $is_pdf) }}" />
                        <p style='margin-top:5px'>Additional</p>
                    @endif
                </div>
                <?php } ?>
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
                <?php if (isset($item->sample->description) && !empty($item->sample->description)) { ?>
                <div class="row">
                    <p style="display:inline-block;width:39%;text-align:left;">
                        <?php if($survey->client_id != 5) {
                            if ($item->record_id == $item->sample->original_item_id) {
                                echo "Referenced to";
                            } else {
                                echo "Sample Linked/ID";
                            }
                        }else{
                            echo "Sample Linked/ID";
                        } ?> </p>
                    <p style="display:inline-block;width:59%;text-align:left;">
                        <?= $item->sample->description ?? ''; ?>
                    </p>
                </div>
                <?php } ?>
                <div>
                    <p style="display:inline-block;width:39%;">
                        Property Name </p>
                    <p style="display:inline-block;width:59%;">
                        <?= $survey->property->name ?? '' ?>
                    </p>
                </div>
                <div>
                    <p style="display:inline-block;width:39%;">
                        Area/floor </p>
                    <p style="display:inline-block;width:59%;">
                        <?= $item->area->title_presentation ?? ''; ?>
                    </p>
                </div>
                <div>
                    <p style="display:inline-block;width:39%;">
                        Room/location </p>
                    <p style="display:inline-block;width:59%;">
                        <?= $item->location->title_presentation ?? ''; ?>
                    </p>
                </div>
                <div>
                    <p style="display:inline-block;width:39%;">
                        Specific location </p>
                    <p style="display:inline-block;width:59%;">
                        <?= $item->specific_location ?? ''; ?>
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
                <?php }else{ ?>
                <div>
                    <p style="display:inline-block;width:39%;">
                        Asbestos type </p>
                    <p style="display:inline-block;width:59%;">
                        No ACM Detected
                    </p>
                </div>
                <?php } ?>
                <div>
                    <p style="display:inline-block;width:39%;">
                        Extent </p>
                    <p style="display:inline-block;width:59%;">
                        {{optional($item->itemInfo)->extent.' '.optional($item->extentView)->extent}}
                    </p>
                </div>
                <?php if ($item->state != ITEM_NOACM_STATE) { ?>
                <?php if ($item->state == ITEM_ACCESSIBLE_STATE) { ?><?php if ($survey->surveySetting->is_require_license_status != 0) { ?>
                <div>
                    <p style="display:inline-block;width:39%;">
                        Licensed/non-licensed </p>
                    <p style="display:inline-block;width:59%;">
                        {{$item->licensedNonLicensedView->licensed_non_licensed ?? 'N/A'}}
                    </p>
                </div>
                <?php } ?><?php if ($survey->surveySetting->is_require_r_and_d_elements != 0) { ?>
                <div>
                    <p style="display:inline-block;width:39%;">
                        R&D Element: </p>
                    <p style="display:inline-block;width:59%;">
                        {{optional($item->itemInfo)->is_r_and_d_element_text}}
                    </p>
                </div>
                <?php } ?>
                <div>
                    <p style="display:inline-block;width:39%;">
                        Air Test </p>
                    <p style="display:inline-block;width:59%;">
                        {{''}}
                    </p>
                </div>
                <?php
                }
                }
                ?>
            </div>
            <?php if ($item->state != ITEM_NOACM_STATE) { ?>
            <div>
                <div class="topTitleSmall">Actions/recommendations</div>
                <p style="display:inline-block;width:39%;">
                    Action/recommendations </p>
                <p style="display:inline-block;width:59%;">
                    <?= $item->actionRecommendationView->action_recommendation ?? ''; ?>
                </p>
            </div>
            <?php } ?>
        </div>

        <div style="display:inline-block;width:35%;margin-bottom:5px;">
            <?php if ($item->state != ITEM_NOACM_STATE) { ?>
            <div>
                <div class="topTitleSmall" style="margin-top:0; padding-top:0;">Material Assessment</div>
                <div>
                    <p style="display:inline-block;width:64%;">
                        Product Type (a) </p>
                    <p style="display:inline-block;width:23%;">
                        {{isset($item->product_type) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ? 'N/A' : $item->product_type) : 0}}
                    </p>
                </div>
                <div>
                    <p style="display:inline-block;width:64%;">
                        Extent of Damage (b) </p>
                    <p style="display:inline-block;width:23%;">
                        {{isset($item->extend_damage) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ?  'N/A' : $item->extend_damage) : 0}}
                    </p>
                </div>
                <div>
                    <p style="display:inline-block;width:64%;">
                        Surface Treatment (c) </p>
                    <p style="display:inline-block;width:23%;">
                        {{isset($item->surface_treatment) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ?  'N/A' : $item->surface_treatment ) : 0}}
                    </p>
                </div>
                <div>
                    <p style="display:inline-block;width:64%;">
                        Asbestos Fibre (d) </p>
                    <p style="display:inline-block;width:23%;">
                        {{isset($item->asbestos_fibre) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ?  'N/A' : $item->asbestos_fibre ) : 0}}
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
                        <img src="{{public_path('img/'.CommonHelpers::getMasRiskColor($item->total_mas_risk).'-block.png')}}"
                             width="16" alt=""/> {{CommonHelpers::getMasRiskText($item->total_mas_risk)}}
                    </p>
                </div>
            </div>
            <?php if (isset($survey->surveySetting->is_require_priority_assessment) && $survey->surveySetting->is_require_priority_assessment != 0) { ?>
            <div>
                <div class="topTitleSmall">Priority Assessment</div>
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
                        <img src="{{public_path('img/'.CommonHelpers::getMasRiskColor($item->total_pas_risk).'-block.png')}}"
                             width="16" alt=""/> {{CommonHelpers::getMasRiskText($item->total_pas_risk)}}
                    </p>
                </div>
            </div>

            <div>
                <div class="topTitleSmall">Overall Assessment</div>
                <div>
                    <p style="display:inline-block;width:64%;">
                        Total (a+b+c+d+e+f+g+h) </p>
                    <p style="display:inline-block;width:23%;">
                        {{sprintf("%02d", $item->total_risk)}}
                    </p>
                </div>
                <div>
                    <p style="display:inline-block;width:64%;">
                        Overall Risk Assessment </p>
                    <p style="display:inline-block;width:23%;">
                        <img src="{{public_path('img/'.CommonHelpers::getTotalText($item->total_risk)['color'].'-block.png')}}"
                             width="16" alt=""/> {{CommonHelpers::getTotalText($item->total_risk)['risk']}}
                    </p>
                </div>
            </div>
            <?php } ?><?php } ?>
            <div>
                <div class="topTitleSmall">Comments</div>
                <div>
                    <p style="display:inline-block;width:64%;">
                        {!! $item->itemInfo->comment ?? 'No Comments' !!}
                    </p>
                    <p style="display:inline-block;width:23%;">
                        &nbsp; </p>
                </div>
            </div>
        </div>
        <?php if ($asbestosRegisterCount % 3 != 2) { ?>
        <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: 5px; margin-bottom: 0;"/>
        <?php } ?>


    </div><!-- PAGE 16 -->
    <?php
    $asbestosRegisterCount++;
    }
    }
    ?>

    <?php if ($survey->surveySetting->is_require_priority_assessment != 0) { ?>
    <div style="page-break-before: always;"></div>
    <div id="overall-risk-assessment-table" style="margin-top:30px;">
        <div class="topTitleBig">Survey Results</div>
        <h3 style="margin-top:20px;margin-bottom:0;">Overall Risk Assessment Table</h3>
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:20px;">
            <thead>
            <tr>
                <th width="21%"></th>
                <th width="27%" colspan="5">Material Risk Assessment</th>
                <th width="27%" colspan="5">Priority Risk Assessment</th>
                <th width="26%">Overall Risk Assessment</th>
            </tr>
            <tr>
                <th style="border-top: 1px solid">Item</th>
                <th style="border-top: 1px solid">a</th>
                <th style="border-top: 1px solid">b</th>
                <th style="border-top: 1px solid">c</th>
                <th style="border-top: 1px solid">d</th>
                <th style="border-top: 1px solid">Total</th>
                <th style="border-top: 1px solid">e</th>
                <th style="border-top: 1px solid">f</th>
                <th style="border-top: 1px solid">g</th>
                <th style="border-top: 1px solid">h</th>
                <th style="border-top: 1px solid">Total</th>
                <th style="border-top: 1px solid">Total</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (count($acm_items)) {
            foreach ($acm_items as $item) {
            ?>
            <tr>
                <td style="text-align:center"><?= $item->name ?? ''; ?></td>
                <td style="text-align:center"><?= isset($item->product_type) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ? 'N/A' : $item->product_type ) : 0 ?></td>
                <td style="text-align:center"><?= isset($item->extend_damage) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ?  'N/A' : $item->extend_damage ) : 0; ?></td>
                <td style="text-align:center"><?= isset($item->surface_treatment) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ?  'N/A' : $item->surface_treatment ) : 0; ?></td>
                <td style="text-align:center"><?= isset($item->asbestos_fibre) ? ($item->state == ITEM_INACCESSIBLE_STATE && $item->itemInfo->assessment == ITEM_LIMIT_ASSESSMENT ?  'N/A' : $item->asbestos_fibre ) : 0;  ?></td>
                <td style="text-align:center"><?= sprintf("%02d",$item->total_mas_risk); ?></td>
                <td style="text-align:center"><?= $item->primary ?? 0; ?></td>
                <td style="text-align:center"><?= $item->likelihood ?? 0; ?></td>
                <td style="text-align:center"><?= $item->human_exposure_potential ?? 0; ?></td>
                <td style="text-align:center"><?= $item->maintenance_activity ?? 0; ?></td>
                <td style="text-align:center"><?= sprintf("%02d", $item->total_pas_risk); ?></td>
                <td style="text-align:center"><?= sprintf("%02d", $item->total_risk); ?></td>
            </tr>
            <?php
            }
            } else
                echo "<tr><td colspan='12'>No ACM Item Found</td></tr>";
            ?>
            </tbody>
        </table>
        <div style="margin-top: 20px;display: block;">
                <span style="color:#000000;font-weight:bold;line-height: 16px;font-size: 15px;height: 16px;vertical-align: top;display:inline-block;width:19%"><span
                        style="width:16px;height:16px;background:red;display:inline-block;margin-right:3px"></span>(20<img
                        src="{{ public_path('img/ge.png') }}" height="16" alt="GE">) High</span> <span
                style="color:#000000;font-weight:bold;line-height: 16px;font-size: 15px;height: 16px;vertical-align: top;display:inline-block;width:19%"><span
                    style="width:16px;height:16px;background:#ad8049;display:inline-block;margin-right:3px"></span>(14-19) Medium</span>
            <span style="color:#000000;font-weight:bold;line-height: 16px;font-size: 15px;height: 16px;vertical-align: top;display:inline-block;width:19%"><span
                    style="width:16px;height:16px;background:orange;display:inline-block;margin-right:3px"></span>(10-13) Low</span>
            <span style="color:#000000;font-weight:bold;line-height: 16px;font-size: 15px;height: 16px;vertical-align: top;display:inline-block;width:19%"><span
                    style="width:16px;height:16px;background:yellow;display:inline-block;margin-right:3px"></span>(<img
                    src="{{ public_path('img/le.png') }}" height="16" alt="GE">9) Very Low</span> <span
                style="color:#000000;font-weight:bold;line-height: 16px;font-size: 15px;height: 16px;vertical-align: top;display:inline-block;width:19%"><span
                    style="width:16px;height:16px;background:green;display:inline-block;margin-right:3px"></span>(0) No Risk</span>
        </div>

    </div><!-- Overall Risk Table PAGE 17 -->
    <?php } ?>

    <div style="page-break-before: always;"></div>
    <div id="overall-survey-summaries" style="margin-top:30px;">
        <div class="topTitleBig">Survey Results</div>
        <?php if ($survey->survey_type != 2 && $survey->surveySetting->is_require_r_and_d_elements != 0) { ?>
        <h3 style="margin-top: 20px;">Refurbishments &amp; Demolition Elements</h3><p
            style="margin-top: 20px; margin-bottom: 20px;">Whilst this survey is predominantly a <?php
            if ($survey->survey_type == 1)
                echo "Management Survey";
            elseif ($survey->survey_type == 3)
                echo "Re-Inspection Survey";
            ?> and therefore non-intrusive in nature, there have been refurbishment and demolition elements
            conducted as required and/or requested to ensure that
            the extent and type of ACMs can be established. The
            table below is a summary of all such items.</p>
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:0px;margin-bottom:30px;">
            <thead>
            <tr>
                <th>Item</th>
                <th>Floor/area</th>
                <th>Room/location</th>
                <th>Product/debris Type</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (count($item_r_and_d)) {
            foreach ($item_r_and_d as $item) {
            ?>

            <tr>
                <td>{{ $item->name ?? ''}}</td>
                <td><?= $item->area->title_presentation ?? ''; ?></td>
                <td><?= $item->location->title_presentation ?? ''; ?></td>
                <td><?= $item->productDebrisView->product_debris ?? ''; ?></td>
            </tr>
            <?php
            }
            } else
                echo "<tr><td colspan='4'>No Refurbishments &amp; Demolition Elements</td></tr>";
            ?>
            </tbody>
        </table>

        <?php } ?>
        <h3 style="margin-top:20px;margin-bottom:0;">Summary of Remedial or Removal Works</h3>
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:20px;margin-bottom:0;">
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
            <?php
            if (count($action_recommendation_removal_items)) {
            // Sort Array
            foreach ($action_recommendation_removal_items as $item) {
            ?>

            <tr>
                <td style="width:100px;"><?= $item->name ?? ''; ?></td>
                <td style="width:80px;"><?= $item->sample->description ?? ''; ?></td>
                <td style="width:140px;"><?= $item->productDebrisView->product_debris ?? ''; ?></td>
                <td style="width:65px;"><?= $item->area->title_presentation ?? ''; ?></td>
                <td style="width:100px;"><?= $item->location->title_presentation ?? ''; ?></td>
                <td style="width:200px;"><?= $item->actionRecommendationView->action_recommendation ?? ''; ?></td>
            </tr>
            <?php
            }
            } else
                echo "<tr><td colspan='6'>No Remediation & Removal Works Required</td></tr>";
            ?>
            </tbody>
        </table>

    </div><!--Overall Survey Summaries PAGE 17-->


    <div id="assessment-information" style="page-break-before: always;">
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

    <div id="Material-Classifcations" style="page-break-before: always;">
        <div>
            <div class="topTitleBig">Assessment Information</div>
            <h3 style="margin-top:20px">Material Classifications</h3>
            <p>The following material assessment categories are used within this survey and indicate the level of hazard
                each material presents.</p>

            <div class="topTitleSmall"
                 style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                <span style="width:16px;height:16px;background:red;display:inline-block;margin-right:3px"></span>(10<img
                    src="{{ public_path('img/ge.png') }}" height="15" alt="GE">) High
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
                    src="{{ public_path('img/le.png') }}" height="15" alt="GE">4) Very Low
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

    <?php if ($survey->surveySetting->is_require_priority_assessment != 0) { ?>
        <div id="Assessment-Information" style="page-break-before: always;">
            <div>
                <div class="topTitleBig">Assessment Information</div>
                <h3 style="margin-top:10px">Priority Classifications</h3>
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
                        <td>Normal Occupant Activity (e)</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td rowspan="4" style="border-top: 0;">Main Type of Activity in Area</td>
                        <td>0</td>
                        <td>Rare Disturbance Activity (e.g. Little used Store Room)</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Low Disturbance Activities (e.g. Office Type Activity)</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Periodic Disturbance (e.g. Industrial or Vehicular Activity which may contact ACMs</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>High Levels of Disturbance (e.g. Door with AIB Sheeting in Constant Use)</td>
                    </tr>
                    <tr>
                        <td style="border-top: 0;">Secondary Activity in Area</td>
                        <td>As Above</td>
                        <td>As Above</td>
                    </tr>

                    <tr>
                        <td>Likelihood of Disturbance (f)</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td rowspan="4" style="border-top: 0;">Location</td>
                        <td>0</td>
                        <td>Outdoors</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Large Rooms or Well Ventilated Areas</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Rooms up to 100m²</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Confined Spaces</td>
                    </tr>
                    <tr>
                        <td rowspan="4" style="border-top: 0;">Accessibility</td>
                        <td>0</td>
                        <td>Usually Inaccessible or Unlikely to be Disturbed</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Occasionally Likely to be Disturbed</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Easily Disturbed</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Routinely Disturbed</td>
                    </tr>
                    <tr>
                        <td rowspan="4" style="border-top: 0;">Extent / Amount</td>
                        <td>0</td>
                        <td>Small Amounts or Items (e.g. Gaskets or Strings)</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td><img src="{{ public_path('img/le_grey.png') }}" height="12" alt="GE">10m² or <img
                                src="{{ public_path('img/le_grey.png') }}" height="12" alt="GE"> 10m Pipe Run
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>&gt;10m² to 50m² or &gt;10m to 50m Pipe Run</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>&gt;50m² or &gt;50m Pipe Run</td>
                    </tr>

                    <tr>
                        <td>Human Exposure Potential (g)</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td rowspan="4" style="border-top: 0;">Number of Occupants</td>
                        <td>0</td>
                        <td>None</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>1 to 3</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>4 to 10</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>>10</td>
                    </tr>
                    <tr>
                        <td rowspan="4" style="border-top: 0;">Frequency of Use in Area</td>
                        <td>0</td>
                        <td>Infrequent</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Monthly</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Weekly</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Daily</td>
                    </tr>
                    <tr>
                        <td rowspan="4" style="border-top: 0;">Average Time Area is in Use</td>
                        <td>0</td>
                        <td>&lt;1 Hour</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>&gt;1 to &lt;3 Hours</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>&gt;3 to &lt;6 Hours</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>>6 Hours</td>
                    </tr>

                    <tr>
                        <td>Maintenance Activity (h)</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td rowspan="4" style="border-top: 0;">Type of Maintenance Activity</td>
                        <td>0</td>
                        <td>Minor Disturbance (e.g. Possibility of Contact when Gaining Access)</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Low Disturbance (e.g. Changing Light Bulbs in AIB Ceiling)</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Medium Disturbance (e.g. Lifting One or Two AIB Ceiling Tiles to access valves)</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>High Level of Disturbance (e.g. Removing a Number of AIB Ceiling Tiles to Replace a Valve or
                            Re-cabling Works)
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="4" style="border-top: 0;">Frequency of Maintenance Activity</td>
                        <td>0</td>
                        <td>ACM Unlikely to be Disturbed for Maintenance</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td><img src="{{ public_path('img/le_grey.png') }}" height="12" alt="GE">1 per Year</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>&gt;1 per Year</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>&gt;1 per Month</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div><!-- PAGE 11 -->

        <div id="priority-risk-assessment-algorithm" style="page-break-before: always;">
            <div>
                <div class="topTitleBig">Assessment Information</div>
                <h3 style="margin-top:20px">Priority Risk Assessment Algorithm</h3>
                <p>Priority assessments consider the likelihood of someone disturbing the identified/presumed ACM during
                    normal occupancy and should be considered alongside the material assessment to determine the priority
                    for remedial action. The main assessment factors are:</p>

                <ol type="a" start="5" style="margin:20px 0 20px 40px;">
                    <li>Maintenance Activity</li>
                    <li>Occupant Activity</li>
                    <li>Likelihood of Disturbance</li>
                    <li>Human Exposure Potential</li>
                </ol>

                <p style="margin-top:20px">Similar to a material assessment, a material algorithm based upon a numerical
                    rating given to each of the parameters considered above has been employed in
                    line with HSG227. The number against each assessment factor is averaged and
                    then totalled to give a score that falls into one of four possible risk
                    categories, aimed at calculating the level of risk those in the vicinity of
                    the ACM are exposed to.</p>

                <div class="topTitleSmall"
                     style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                    <span style="width:16px;height:16px;background:red;display:inline-block;margin-right:3px"></span>(10<img
                        src="{{ public_path('img/ge.png') }}" height="16" alt="GE">) High
                </div>
                <p>An ACM that due to its location presents an unacceptable risk to individuals.</p>

                <div class="topTitleSmall"
                     style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                    <span style="width:16px;height:16px;background:#ad8049;display:inline-block;margin-right:3px"></span>(7-9)
                    Medium
                </div>
                <p>An ACM situated in a high use, readily accessible position which may also be in an area routinely
                    accessed for maintenance.</p>

                <div class="topTitleSmall"
                     style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                    <span style="width:16px;height:16px;background:orange;display:inline-block;margin-right:3px"></span>(5-6)
                    Low
                </div>
                <p>An ACM that will rarely be disturbed through normal occupation or maintenance activities.</p>

                <div class="topTitleSmall"
                     style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                    <span style="width:16px;height:16px;background:yellow;display:inline-block;margin-right:3px"></span>(<img
                        src="{{ public_path('img/le.png') }}" height="16" alt="GE">4) Very Low
                </div>
                <p>An ACM that is not readily accessible and unlikely to be disturbed.</p>

                <div class="topTitleSmall"
                     style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                    <span style="width:16px;height:16px;background:green;display:inline-block;margin-right:3px"></span>(0)
                    No
                    Risk
                </div>
                <p>No ACM present.</p>

                <div>
                    <div style="margin-top:30px;width:99%;">
                        <div style="width:40%;display:inline-block">
                            <p>Disturbance Primary (e)</p>
                            <p>Disturbance Secondary (e)</p>
                        </div>
                        <div style="width:59%;display:inline-block">
                            <div style="width:10%;display:inline-block;vertical-align: middle;">
                                <p>0</p>
                                <p>0</p>
                            </div>
                            <div style="width:10%;display:inline-block;vertical-align: middle;">
                                <div style="width:15px;height:45px;border:1px solid #999;border-left:none;"></div>
                            </div>
                            <div style="width:79%;display:inline-block;">
                                <p style="margin-top: 15px;">Average Score 0</p>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:20px;width:99%;">
                        <div style="width:40%;display:inline-block">
                            <p>Location (f)</p>
                            <p>Accessibility (f)</p>
                            <p>Extent / Amount (f)</p>
                        </div>
                        <div style="width:59%;display:inline-block">
                            <div style="width:10%;display:inline-block;vertical-align: middle;">
                                <p>0</p>
                                <p>0</p>
                                <p>0</p>
                            </div>
                            <div style="width:10%;display:inline-block;vertical-align: middle;">
                                <div style="width:15px;height:60px;border:1px solid #999;border-left:none;"></div>
                            </div>
                            <div style="width:79%;display:inline-block">
                                <p>&nbsp;</p>
                                <p>Average Score 0</p>
                                <p>&nbsp;</p>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:20px;width:99%;">
                        <div style="width:40%;display:inline-block">
                            <p>Number of Occupants (g)</p>
                            <p>Frequency of Use (g)</p>
                            <p>Average Time in Use (g)</p>
                        </div>
                        <div style="width:59%;display:inline-block">
                            <div style="width:10%;display:inline-block;vertical-align: middle;">
                                <p>0</p>
                                <p>0</p>
                                <p>0</p>
                            </div>
                            <div style="width:10%;display:inline-block;vertical-align: middle;">
                                <div style="width:15px;height:60px;border:1px solid #999;border-left:none;"></div>
                            </div>
                            <div style="width:79%;display:inline-block">
                                <p>&nbsp;</p>
                                <p>Average Score 0</p>
                                <p>&nbsp;</p>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:20px;width:99%;">
                        <div style="width:40%;display:inline-block">
                            <p>Type of Maintenance (h)</p>
                            <p>Frequency of Maintenance (h)</p>
                        </div>
                        <div style="width:59%;display:inline-block">
                            <div style="width:10%;display:inline-block;vertical-align: middle;">
                                <p>0</p>
                                <p>0</p>
                            </div>
                            <div style="width:10%;display:inline-block;vertical-align: middle;">
                                <div style="width:15px;height:45px;border:1px solid #999;border-left:none;"></div>
                            </div>
                            <div style="width:79%;display:inline-block;">
                                <p style="margin-top: 15px;">Average Score 0</p>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:20px;width:99%;">
                        <div style="width:60%;display:inline-block">
                            <p>Total of Averages (e+f+g+h)</p>
                        </div>
                        <div style="width:39%;display:inline-block;">
                            <p style="margin-left: 34px">00</p>
                        </div>
                    </div>

                    <div style="margin-top:20px;width:99%;">
                        <div style="width:58%;display:inline-block;">
                            <p>Priority Risk Assessment</p>
                        </div>
                        <div style="width:41%;display:inline-block;text-align: right;">
                            <p style="margin-left: 15px">
                                <span style="width:16px;height:16px;background:white;border:1px solid #999;display:inline-block;margin-right:3px"></span>
                                Risk</p>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- priority algorithm PAGE 12 -->

        <div id="Overall-Risk-Assessment-Algorithm" style="page-break-before: always;">
            <div>
                <div class="topTitleBig">Assessment Information</div>
                <h3 style="margin-top:20px;clear:both;">Overall Risk Assessment Algorithm</h3>
                <p>The overall assessment is a combination of the material and priority assessment scores. It is this
                    total score that may be used to establish the priority of those ACMs requiring remedial action and
                    also, the type of action that will be taken. Where an ACM is detected, regardless of its risk
                    categorisation, it is recommended that Approved Warning Labels are positioned to prevent accidental
                    damage to the material.</p>

                <p style="margin-top:20px;">Although actions and recommendations may vary according to the individual
                    circumstances of an ACM, it is desirable to have some form of
                    standardisation therefore the following categories are used within this
                    survey to identify areas that require immediate attention and allow the duty
                    holder to instigate planned preventative maintenance and management of the
                    ACMs.</p>

                <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX>
                    <thead>
                    <tr>
                        <th width="20%"></th>
                        <th width="27%" colspan="5">Material Risk Assessment</th>
                        <th width="27%" colspan="5">Priority Risk Assessment</th>
                        <th width="26%">Overall Risk Assessment</th>
                    </tr>
                    <tr>
                        <th style="border-top: 1px solid">Item</th>
                        <th style="border-top: 1px solid">a</th>
                        <th style="border-top: 1px solid">b</th>
                        <th style="border-top: 1px solid">c</th>
                        <th style="border-top: 1px solid">d</th>
                        <th style="border-top: 1px solid">Total</th>
                        <th style="border-top: 1px solid">e</th>
                        <th style="border-top: 1px solid">f</th>
                        <th style="border-top: 1px solid">g</th>
                        <th style="border-top: 1px solid">h</th>
                        <th style="border-top: 1px solid">Total</th>
                        <th style="border-top: 1px solid">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="text-align:center">I000</td>
                        <td style="text-align:center">0</td>
                        <td style="text-align:center">0</td>
                        <td style="text-align:center">0</td>
                        <td style="text-align:center">0</td>
                        <td style="text-align:center">00</td>
                        <td style="text-align:center">0</td>
                        <td style="text-align:center">0</td>
                        <td style="text-align:center">0</td>
                        <td style="text-align:center">0</td>
                        <td style="text-align:center">00</td>
                        <td style="text-align:center">00</td>
                    </tr>
                    </tbody>
                </table>

                <h3 style="margin-top:20px;">Overall Classifications</h3>
                <div class="topTitleSmall"
                     style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                    <span style="width:16px;height:16px;background:red;display:inline-block;margin-right:3px"></span>(20<img
                        src="{{ public_path('img/ge.png') }}" height="16" alt="GE">) High
                </div>
                <p>The potential hazard arising from this category warrants urgent action to reduce the associated risk
                    as disturbance of the materials is liable to expose personnel to elevated levels of airborne
                    respirable asbestos fibres. ACMs in this category are usually not suited to any form of containment
                    programme and therefore immediate plans should be made for removal or environmental cleaning. Where
                    this is delayed, the ACM should be sealed/encapsulated and appropriately managed in accordance with
                    the asbestos management policy, until such time that removal can be facilitated.</p>

                <div class="topTitleSmall"
                     style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                    <span style="width:16px;height:16px;background:#ad8049;display:inline-block;margin-right:3px"></span>(14-19)
                    Medium
                </div>
                <p>This category indicates that deterioration in any of the contributory factors may result in fibre
                    release and therefore all ACMs should be removed or other appropriate remedial action undertaken on a
                    programmed basis within a specified time scale (usually 6-12 months). The condition of the ACMs
                    should be regularly monitored and, where necessary sealed/encapsulated until removal takes place.</p>

                <div class="topTitleSmall"
                     style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                    <span style="width:16px;height:16px;background:orange;display:inline-block;margin-right:3px"></span>(10-13)
                    Low
                </div>
                <p>This category indicates the need for regular monitoring and inspection as whilst the current risk of
                    fibre release may be low, such ACMs may suffer deterioration through age and/or accidental damage. It
                    is recommended that ACMs in this category are visually inspected on a six month cycle (minimum) to
                    ascertain any change in condition. Where such a change occurs, <span>re-prioritisation</span> may be
                    necessary.</p>

                <div class="topTitleSmall"
                     style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                    <span style="width:16px;height:16px;background:yellow;display:inline-block;margin-right:3px"></span>(<img
                        src="{{ public_path('img/le.png') }}" height="16" alt="GE">9) Very Low
                </div>
                <p>ACMs within this category are predominantly not readily accessible, unlikely to be disturbed and due
                    to their nature, condition, location or extent, would lead to minimal fibre release if they were
                    disturbed. Visual inspections should be made on an annual basis to ascertain any change in condition
                    and where such a change occurs, should be appropriately assessed, scored and
                    <span>re-prioritised</span>. Such ACMs should be suitably managed and considered for removal if they
                    falls within a demolition or refurbishment area and works are likely to disturb the material.</p>

                <div class="topTitleSmall"
                     style="margin-top: 20px;line-height: 16px;font-size: 16px;height: 16px;vertical-align: top;">
                    <span style="width:16px;height:16px;background:green;display:inline-block;margin-right:3px"></span>(0)
                    No
                    Risk
                </div>
                <p>No ACM present.</p>
            </div>
        </div><!--Assessment Information PAGE 13-->
        <?php } ?>
    <div id="survey-results-continue" style="page-break-before: always">
        <div>
            <h2>Survey Appendices</h2>
            <h3 style="margin-top:20px">Remedial Options</h3>
            <p>There are a variety of remedial options available. In many cases the ACMs can be protected or enclosed,
                sealed or encapsulated, or repaired and these options should be considered first. Where such actions are
                not practical, ACMs should be removed. Recommended action in the <?= $surveyTitle ?> will normally
                involve one or more of the following:</p>

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

    <div id="survey-appendices" class="page" style="page-break-before: always">
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
            <li>L143 'Managing and Work with asbestos'</li>
            <li>HSG210 'Asbestos essentials task manual'</li>
            <li>HSG227 'A comprehensive guide to managing asbestos in premises'</li>
            <li>HSG247 'Asbestos: The licensed contractors' guide'</li>
            <li>HSG248 'Asbestos: The analysts' guide for sampling, analysis and clearance procedures'</li>
            <li>HSG264 'Asbestos: The survey guide'</li>
            <li>INDG223 'Managing asbestos in building: A brief guide'</li>
        </ol>

        <p style="margin-top:20px">The HSE has also published 38 'Asbestos essentials task sheets' and 10 'Equipment and Method sheets' which can help ensure compliance with CAR 2012 and illustrate 'good practice'.</p>


        <h3 style="margin-top:30px">Site Plans</h3>
        <p>Documents Enclosed.</p>
    </div><!--Survey Appendices PAGE 19-->
</div><!--Container - set width -->
</body>
</html>

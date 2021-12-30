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
        case 6:
            $surveyTitle = "Sample Survey";
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
                        optional($survey->property->propertyInfo)->flat_number,
                        optional($survey->property->propertyInfo)->building_name,
                        optional($survey->property->propertyInfo)->street_number,
                        optional($survey->property->propertyInfo)->street_name,
                        optional($survey->property->propertyInfo)->town,
                        optional($survey->property->propertyInfo)->address5,
                        optional($survey->property->propertyInfo)->postcode
                    ]))!!}
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
            <h3 style="margin-top:30px;">Inspection Body Information</h3>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Inspection Body Name - Inspection Body Reference Number: </p>
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
        <?php if ($survey->client_id == 3 && $survey->property->zone_id == 9){ ?>
        <p style="margin-top:20px;">The Executive Summary contains details of the scope and extent of the works. The reader must ensure that the scope covers the required areas and that any variations do not impact on any proposed works or management of the site. All areas of no access should be considered as containing asbestos until proven otherwise.</p>
        <p style="margin-top:20px;">Recommended Actions provides a summary of all identified and presumed asbestos containing materials (ACMs). ACMs are listed by recommendation with those requiring urgent attention listed first.</p>
        <p style="margin-top:20px;">The Asbestos Register presents ACMs by building, floor & location. It provides a detailed list of all locations included within the survey where positive samples have been taken or items are presumed to contain asbestos. Items physically sampled will show the asbestos type within the analysis column.</p>
        <p style="margin-top:20px;">Items cross referenced (strong presumption) have their asbestos type determined by the sample result of materials of similar appearance and use that have been sampled elsewhere on site.</p>
        <p style="margin-top:20px;">Strongly Presumed samples are items that the surveyor was unable to sample but the materials are similar in appearance and use to known asbestos-containing materials and hence they are confirmed as containing asbestos.</p>
        <p style="margin-top:20px;">Presumed items are those that the surveyor was unable to sample or inspect adequately to confirm the presence of asbestos, as such there is a potential for asbestos being present and the item is presumed to contain asbestos.</p>
        <p style="margin-top:20px;">A Material Assessment algorithm has been completed for all positive samples. It should be noted that to enable an accurate Priority Assessment to be undertaken this requires a detailed knowledge of the property. The responsibility for this lies with the duty holder, although Life Environmental can assist with the provision of information or generic assessments where agreed.</p>
        <p style="margin-top:20px;">Recommendations within this report are based on the condition of the asbestos and the Material Assessment. Prior to carrying out these recommendations consideration should be given to the Priority Assessment Algorithm</p>
        <?php } ?>
        <p style="margin-top:20px;">A <?= $surveyTitle ?> was carried out at <?= $survey->property->name ?> on the
        {{ $survey->surveyDate->surveying_start_date ?? ''}}

        <?php if ($survey->survey_type == 1) { ?>

        <?php if ($survey->surveySetting->is_require_r_and_d_elements == 0) { ?>
        <p style="margin-top:20px;">The purpose of the survey was to identify, as far as reasonably practicable,
            the presence and extent of any suspect asbestos containing materials (ACMs)
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

        <?php } else { ?>
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
        <?php } ?>

        <?php } elseif ($survey->survey_type == 2) { ?>
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

        <?php } elseif ($survey->survey_type == 3) { ?>
        <p style="margin-top:20px;">The purpose of this survey was to inspect asbestos containing materials (ACMs)
            identified in the asbestos register to determine any changes in condition,
            location or accessibility. </p>

        <p style="margin-top:20px;">This report can be used to update existing survey information by documenting
            where ACMs have been removed prior to refurbishment or demolition. </p>

        <p style="margin-top:20px;">This report does not constitute a re-survey and should not be used as an initial
            resource for any work involving alterations to the fabric of the building. </p>

        <?php } elseif ($survey->survey_type == 4) { ?>
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

        <?php }elseif ($survey->survey_type == 6){ ?>
        <p style="margin-top:20px;">The purpose of the survey was to identify, as far as reasonably practicable, the presence and
            extent of any suspect asbestos containing materials (ACMs) in the areas inspected and assess their condition.
        </p>

        <p style="margin-top:20px;">An assessment of specific construction materials were requested for this building. </p>

        <p style="margin-top:20px;">This type of survey is not designed to be used for assessing risks during normal work activities
            and simple or routine maintenance tasks.
        </p>

        <p style="margin-top:20px;">It is also not to be used by those carrying out major refurbishments or for work involving
            alterations to the fabric of the building. If any refurbishment or demolition works are to be undertaken,
            a Refurbishment or Demolition survey will be required prior to the start of any work. This is a fully
            intrusive survey intended to find any hidden ACMs contained within the main structure of the building.
        </p>
        <?php } ?>

        @if ($survey->surveyInfo->executive_summary)
            <?php echo '<p style="margin-top:20px;">' . $survey->surveyInfo->executive_summary . '</p>' ?>
        @else
            @if($survey->survey_type != 6)
                <p>The client should note that if demolition or refurbishment works are to be undertaken in any part of this property which was not included in the scope of this survey, or was physically and visually impossible to access, further investigations should be carried out before any works commence.</p>
            @endif
        @endif

        <?php if($survey->client_id == 5) { ?>
        <p style="margin-top:20px;">A list of all of the asbestos materials found during the inspection can be found in the survey register included in this report. This Executive Summary is not a conclusive report on the extent and nature of ACMs on site and should be read in conjunction with the survey register.</p>
        <p style="margin-top:20px;">The register has details of all instances of asbestos materials whether sampled or presumed and includes a material assessment for each instance. Please note that the register only lists those materials found within the scope of works and further asbestos materials may be found if any limitations can later be lifted and further inspections carried out subject to extending the scope.</p>
        <?php } ?>

        <p style="margin-top:20px;">This report was published on {{$survey->surveyDate->published_date ?? ''}}.
            Updated information may be present on the asbestos management system which should be
            checked on a regular basis</p>

        <p style="margin-top:20px;">During this Survey {{count($sample_survey)}} sample(s) were
            taken for analysis. There were {{$count_item_tested}} asbestos items identified or
            presumed to contain asbestos within the property.</p>

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
        @if($survey->survey_type != 6)
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
        @endif
    </div><!--Executive Summary page 03 -->
@if($survey->survey_type != 6)
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
    <?php if($survey->client_id == 4){ ?>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:20px;margin-bottom: 0;">
        <thead>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">Scope of works (including proposed refurbishment where applicable)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th style="width: 100%;padding-bottom: 15px;">Survey of site, to enable the client to manage the risks posed from any ACM's identified.   Survey to include out buildings and a walk through of external areas, to check for any obvious miscellaneous materials</th>
        </tr>
        </tbody>
    </table>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:20px;margin-bottom: 0;">
        <thead>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">Elements to be inspected</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">All surveys</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;"><stong>Element</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Usual inspection method (if applicable)</stong></th>
            <th style="width: 10%;padding-bottom: 15px;"><stong>Included?</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Comments</stong></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">All areas within site boundary</th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection of all areas within the site boundaries, including exterior elements and surrounding gardens / land.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;">Survey to be concentrated on building structure, externals and immediate vicinity</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Beneath carpet or floor coverings (except laminate floors)</th>
            <th style="width: 35%;padding-bottom: 15px;">Management surveys - to be lifted in discrete locations where to do so will not cause damage and where the flooring can be safely replaced<br><br>Refurbishment / demolition surveys – full inspection to all relevant areas.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Check in unobtrusive locations and replace on completion</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Beneath or behind fixtures and fittings – e.g. bath panels / kitchen units</th>
            <th style="width: 35%;padding-bottom: 15px;">Management surveys – inspection only where the item is screw fixed or can be easily removed and replaced without causing damage.<br><br>Refurbishment / demolition surveys - removal of cladding panels / units or inspection hole created with appropriate tools.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Remove and replace liftable panels (within WC’s etc.)</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Within boxings or risers (not constructed of a suspect ACM).</th>
            <th style="width: 35%;padding-bottom: 15px;">Management surveys – inspection only where the boxing / access panel is screw fixed or can be easily removed and replaced without causing damage.<br><br>Management surveys – inspection only where the boxing / access panel is screw fixed or can be easily removed and replaced without causing damage.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Obtain keys from the estates department.  Make reasonable attempt to unscrew fixed panels and replace.</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Roof voids</th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection from the access hatch if no flooring or crawl boards available</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">All roof spaces to be accessed  (Cat ladder within electrical cupboard)</th>
        </tr>
        </tbody>
        <thead>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">Refurbishment surveys / demolition surveys (elements to be included in areas affected by the scope of the proposed works)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;"><stong>Element</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Usual inspection method (if applicable)</stong></th>
            <th style="width: 10%;padding-bottom: 15px;"><stong>Included?</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Comments</stong></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Solid wall cavities</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of vent covers/window sills. May involve removal of individual bricks in some cases.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;">N/A</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Partition wall cavities</th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection holes created with appropriate tools and of suitable size to allow for adequate inspection.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Where existing openings allow.</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Above fied ceilings / ceiling voids</th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection holes created with appropriate tools. Inspection may also be possible via the removal of light fittings or removal of flooring within areas above.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Remove/replace ceiling tiles as necessary.</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Floor voids</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of floorboards or use of existing access points.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">As can be safely accessed without specialist equipment and covers and left safe on completion</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Beneath window sills</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of window sills or inspection holes created with appropriate tools.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Within fire doors</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of door furniture or inspection hole created with appropriate tools.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;">Check for obvious fillets and panels only</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Beneath fixed flooring materials (e.g. laminate / ceramic tiles)</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of flooring material.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Check under kitchen cupboards etc for locations, which may not be laminated</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Behind fixed wall cladding / coverings / tiles</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of cladding / coverings / tiles.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Behind skirting and door frames</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of skirting and door frames.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Beneath non-asbestos insulation to pipework / calorifiers etc.</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of non-asbestos insulation materials.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;">Sample to full depth of material</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Behind non asbestos external soffits / fascias</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of soffit / fascia. Inspection hole created with appropriate tools. Suitable inspection may be possible form within roof voids</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Sealed off or locked areas where no key is available</th>
            <th style="width: 35%;padding-bottom: 15px;">Access gained using intrusive methods.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Liaise with client/site contact for access</th>
        </tr>
        </tbody>
    </table>

    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:20px;margin-bottom: 0;">
        <thead>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">NB The following elements are standard exclusions from the survey unless they are indicated as specifically being included and suitable arrangements have been made.</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;"><stong>Element</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Usual inspection method (if applicable)</stong></th>
            <th style="width: 10%;padding-bottom: 15px;"><stong>Included?</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Comments</stong></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Work at height requiring specialist access equipment </th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection via MEWP or scaffold as appropriate.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">3m folding ladders to be provided</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Within electric switchgear, fuse boxes, plant and other associated services</th>
            <th style="width: 35%;padding-bottom: 15px;">Only accessed if a suitably qualified electrician is provided otherwise these items will not be inspected and are specifically excluded from the scope of the survey.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Providing electrical isolation can be provided by client.  In the absence of isolation, comment on age of installation and provide as much detail as possible</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Within operational plant and machinery including boilers / calorifiers etc.</th>
            <th style="width: 35%;padding-bottom: 15px;">Only accessed if a suitably qualified engineer is provided otherwise these items will not be inspected and are specifically excluded from the scope of the survey.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Providing electrician provided by client</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Access behind / above existing ACMs which would require the use of an asbestos removal contractor</th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection will only be carried out if a licensed asbestos removal is provided otherwise these areas are specifically excluded from the scope of the survey.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Lifts, lift shafts and lift machinery</th>
            <th style="width: 35%;padding-bottom: 15px;">Unless agreed with the client and a lift engineer is provided, lifts and shafts are specifically excluded from the scope of the survey. Lift machinery will be inspected externally where possible</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Providing lift engineer provided by client</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Internal elements of safes</th>
            <th style="width: 35%;padding-bottom: 15px;">Where not included and if there are no client specific requirements, all safes will be recorded as a no access item which should be therefore presumed to contain asbestos.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Intrusion through solid ceiling slab, floor slabs or solid walls.</th>
            <th style="width: 35%;padding-bottom: 15px;">Only accessed if suitable specialist support services are provided otherwise these items will not be inspected and are specifically excluded from the scope of the survey.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Below external ground level</th>
            <th style="width: 35%;padding-bottom: 15px;">Only accessed if suitable specialist support services are provided otherwise these items will not be inspected and are specifically excluded from the scope of the survey.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        </tbody>
        <thead>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">Additional information</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th colspan="4" style="padding-bottom: 15px;"><strong>Site specific inclusions</strong> - None</th>
        </tr>
        <tr>
            <th colspan="4" style="padding-bottom: 15px;"><strong>Site Specific exclusions</strong> - None</th>
        </tr>
        </tbody>
    </table>
    <?php } ?>
    <h3 style="margin-top:20px;">Objective & Scope</h3>
    <?php
    if ($survey->surveyInfo->objectives_scope) {
        echo $survey->surveyInfo->objectives_scope;
    } else{
    if($survey->survey_type == 3) { ?>
    <p style="margin-top:20px;">Life Environmental Services Ltd received an order of confirmation to undertake a reinspection Survey from GlaxoSmithKline.  This order has been accepted on the basis of the original Quotation and our terms and conditions of business.  This reinspection is subject to copyright and protected by copyright law.</p>
    <p style="margin-top:20px;">The brief for these works was to carry out a re-inspection of the Asbestos Containing Materials (ACMs) within the aforementioned property.  The re-inspection is based upon the previous survey data held on {{ env('APP_DOMAIN') ?? 'GSK' }} electronic Shine Database.</p>
    <p style="margin-top:20px;">This reinspection considered any damage and disturbance to items identified in the above survey.  Every effort has been made to access any areas that were previously not accessible in the original survey.  Where such locations have still not been accessed, asbestos should be presumed to be present within these areas.</p>
    <p style="margin-top:20px;">Each section of this report focuses on one or two aspects; no section should be taken and read as a stand-alone document.  It is imperative that each section is read in conjunction with each other.</p>
    <p style="margin-top:20px;">This reinspection report forms an addendum to the original survey. The original survey report should be referred to for the following items of information:</p>
    <p style="margin-top:20px;">
    <ul>
        <li>Information on the original survey methodology/caveats</li>
        <li>Original laboratory bulk analysis certificate of analysed samples</li>
        <li>Original drawings</li>
    </ul>
    </p>
    <p style="margin-top:20px;">It should be noted that this report is not intended as a Scope of Works for asbestos removal and that a detailed technical document could be provided upon request.</p>

    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
           style="margin-top:20px;margin-bottom: 0;">
        <tbody>
        <thead>
        <tr>
            <th colspan="4" style="width:100%;padding-bottom: 15px;"><strong>Scope of Works:</strong>
            </th>
        </tr>
        </thead>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Purpose of Survey</strong></th>
            <th colspan="3" style="padding-bottom: 15px;"><strong>Develop their Management Policy / Plan
                    or Acquisition / Sale</strong></th>
        </tr>
        <tr>
            <th style="width: 30%;background: #e2e2e2;padding-bottom: 15px;">Property Type</th>
            <th style="width: 20%;padding-bottom: 15px;">Commercial</th>
            <th style="width: 20%;background: #e2e2e2;padding-bottom: 15px;">Property Status</th>
            <th style="width: 20%;padding-bottom: 15px;">Operational</th>
        </tr>
        <tr style="font-weight: bold;">
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Survey Type Required
                    ?</strong></th>
            <th colspan="3" style="padding-bottom: 15px;"><strong>Reinspection Survey</strong></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong style="font-weight: bold;">Scope / Extent of Survey </strong>
            </th>
            <th colspan="3">
                Re-Inspection of existing asbestos register.
                <p style="margin-top:20px;">The surveyor is to verify the following using the Shine App;</p>
                -	Room Location Ref & Description (As per {{ env('APP_DOMAIN') ?? 'GSK' }} allocation)<br>
                -	Room Location Void Investigations<br>
                -	Room Location Construction Details<br>
                -	Confirmed or Cross Referenced ACM items<br>
                -	Presumed ACM items.<br>

                <p style="margin-top:20px;">Attempt to gain access to any areas previously 'No accessed' if safe to do so. </p>
                <p style="margin-top:20px;">Samples of any suspect items previously not listed are to be taken.</p>
            </th>
        </tr>
        </tbody>
    </table>

    <?php }else{
    if ($survey->client_id == 5) { ?>
    <p style="margin-top:20px;"><strong>Scope of Works:</strong></p>
    <?php if ($survey->client_id == 5) { ?>
    <p style="margin-top:20px;">The purpose of the survey was to carry out a management survey to the
        site listed on the front of this report. All areas to be included unless specified within the
        report.</p><br>

    <p style="margin-top:20px;">Asbestos inspections and surveys should be carried out following a
        detailed review, site scoping process and consideration of the guidance document HSG264
        ”Asbestos the survey guide”. This is available as a PDF download from the HSE books website</p>
    <br>

    <p style="margin-top:20px;">This document further clarifies specific considerations with respect to
        agreeing the scope of works between the Client or duty holder and the asbestos inspection body.
        An asbestos survey is only part of the process through which to effectively manage the risks
        from asbestos containing materials (ACMs), but the survey report itself is not an asbestos
        management plan. A summary of the actions that may need to be taken can be found in the Survey
        Appendices section of this report, referenced from HSG227 “A comprehensive guide to Managing
        ASBESTOS in premises”.</p><br>

    <?php } ?>
    <?php if ($survey->property->reference) { ?>
    <p>Asbestos management survey to include all rooms, spaces and external areas applicable to the
        dwelling.</p>
    <ul type="disc" style="margin-top:20px;">
        <li>Floors are to be accessed beneath floor covering unless there is fixed laminate or tiles.
        </li>
        <li>Voids are to be accessed where a hatch is present</li>
        <li>Lofts are to be accessed where a hatch is present</li>
    </ul>
    <?php } else { ?>
    <p>Asbestos management survey to whole site, to include all buildings, spaces and external areas.
        All Blocks are to be included.</p>
    <?php } ?>


    <?php } elseif ($survey->client_id == 3) {
    if ($survey->property->zone_id == 9) { ?>
    <p style="margin-top:20px;">Life Environmental Services Ltd received an order of confirmation to
        undertake a Management Survey in accordance with the Scope of Services document from
        GlaxoSmithKline for a full management survey of the Weybridge site. This order has been accepted
        on the basis of the original Quotation and our terms and conditions of business. </p>
    <p style="margin-top:20px;">All subsequent information provided by the client or ascertained
        otherwise was assessed during planning stage of the project and a suitable Plan of Work
        produced. Where information was provided regarding the presence of known or presumed asbestos
        materials then this has been validated during the course of the survey, and recorded within this
        report. This survey was carried out in accordance with documented in house procedures and HSE
        Guidance document HSG 264. </p>
    <p style="margin-top:20px;"><strong>Scope of Works:</strong></p>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
           style="margin-top:20px;margin-bottom: 0;">
        <tbody>
        <thead>
        <tr>
            <th colspan="4" style="width:100%;padding-bottom: 15px;"><strong>Scope of Works:</strong>
            </th>
        </tr>
        </thead>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Purpose of Survey</strong></th>
            <th colspan="3" style="padding-bottom: 15px;"><strong>Develop their Management Policy / Plan
                    or Acquisition / Sale</strong></th>
        </tr>
        <tr>
            <th style="width: 30%;background: #e2e2e2;padding-bottom: 15px;">Property Type</th>
            <th style="width: 20%;padding-bottom: 15px;">Commercial</th>
            <th style="width: 20%;background: #e2e2e2;padding-bottom: 15px;">Property Status</th>
            <th style="width: 20%;padding-bottom: 15px;">Operational</th>
        </tr>
        <tr style="font-weight: bold;">
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Survey Type Required
                    ?</strong></th>
            <th colspan="3" style="padding-bottom: 15px;"><strong>Management</strong></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong style="font-weight: bold;">Scope / Extent of Survey </strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(including any areas of the building /
                    site to be excluded)</p>
            </th>
            <th colspan="3">
                <strong style="font-weight: bold;">Asbestos management survey including priority
                    assessments to all buildings at the Weybridge {{ env('APP_DOMAIN') ?? 'GSK' }} site.</strong>
                <p style="margin-top:20px;">Access must be made within ducts, trenches, risers and voids
                    etc. that have access panels or hatches and which are not classified as Confined
                    Spaces. This includes external service trenches where purpose made access covers are
                    available (Survey Team to liaise with site host prior to any access into ducts or
                    trenches).</p>
                <p style="margin-top:20px;">Survey team need to establish the substrate and potential
                    presence of any suspect items below fixed floorings (carpets/carpet tiles & vinyl’s)
                    in discreet locations. And where it can be lifted and replaced in a manner where no
                    damage is caused or trip hazard is left.</p>
                <p style="margin-top:20px;">If the survey team suspect the presence of asbestos
                    containing material within live electrical services then they must contact the site
                    host and CBRE to isolate and provide access for inspection. If isolation is not
                    possible due to critical services being affected</p>
                <p style="margin-top:20px;">The survey team need to inform site host when they require
                    access into lift shaft and inspect lift services. CBRE will isolate the system and
                    provide safe access.</p>
                <p style="margin-top:20px;padding-bottom: 15px;">Where possible the survey team must
                    record the findings and extents of suspected gaskets in relation to individual pipe
                    runs.</p>
            </th>
        </tr>
        </tbody>
    </table>
    <?php } elseif ($survey->property->zone_id == 2) { ?>
    <p style="margin-top:20px;">Life Environmental Services Ltd received an order of confirmation to
        undertake a Management Survey in accordance with the Scope of Services document from
        GlaxoSmithKline for the Stevenage Research and Development site. This order has been accepted on
        the basis of the original Quotation and our terms and conditions of business.</p>
    <p style="margin-top:20px;">All subsequent information provided by the client or ascertained
        otherwise was assessed during planning stage of the project and a suitable Plan of Work
        produced. Where information was provided regarding the presence of known or presumed asbestos
        materials then this has been validated during the course of the survey, and recorded within this
        report. This survey was carried out in accordance with documented in house procedures and HSE
        Guidance document HSG 264.</p>
    <p style="margin-top:20px;"><strong>Scope of Works:</strong></p>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
           style="margin-top:20px;margin-bottom: 0;">
        <tbody>
        <thead>
        <tr>
            <th colspan="4" style="width:100%;font-weight: bold;padding-bottom: 15px;">Section 2: Scope
                of Works
            </th>
        </tr>
        </thead>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">Purpose of Survey</th>
            <th colspan="3" style="padding-bottom: 15px;">Develop their Management Policy / Plan or
                Acquisition / Sale
            </th>
        </tr>
        <tr>
            <th style="width: 30%;background: #e2e2e2;padding-bottom: 15px;">Property Type</th>
            <th style="width: 20%;padding-bottom: 15px;">Industrial</th>
            <th style="width: 20%;background: #e2e2e2;padding-bottom: 15px;">Property Status</th>
            <th style="width: 20%;padding-bottom: 15px;">Operational</th>
        </tr>
        <tr style="font-weight: bold;">
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Survey Type Required
                    ?</strong></th>
            <th colspan="3" style="padding-bottom: 15px;"><strong>Management</strong></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong style="font-weight: bold;">Scope / Extent of Survey </strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(including any areas of the building /
                    site to be excluded)</p>
            </th>
            <th colspan="3">
                <strong style="font-weight: bold;">Asbestos management survey including priority
                    assessments in accordance to {{ env('APP_DOMAIN') ?? 'GSK' }} ' Scope of Services for Asbestos Management
                    Surveys'</strong>
                <p style="margin-top:20px;">Access must be made within ducts, trenches, risers and voids
                    etc that have access panels or hatches. This includes external service trenches
                    where purpose made access covers are available. Survey Team to liaise with site host
                    prior to any access into ducts or trenches.</p>
                <p style="margin-top:20px;">Survey team need to establish the substrate and potential
                    presence of any suspect items below fixed floorings (carpets/carpet tiles/vinyls) in
                    discreet locations. And where it can be lifted and replaced in a manner where no
                    damage is caused or trip hazard is left.</p>
                <p style="margin-top:20px;">If the survey team suspect the presence of asbestos
                    containing material within live electrical services then they must contact the site
                    host to isolate and provide access for inspection.</p>
                <p style="margin-top:20px;padding-bottom: 15px;">Where possible the survey team must
                    record the findings and extents of suspected gaskets in relation to individual pipe
                    runs.</p>
            </th>
        </tr>
        </tbody>
    </table>
    <?php } elseif ($survey->property->zone_id == 6) { ?>
    <p style="margin-top:20px;">Life Environmental Services Ltd received an order of confirmation to
        undertake a Management Survey in accordance with the Scope of Services document from
        GlaxoSmithKline for the Maidenhead site. This order has been accepted on the basis of the
        original Quotation and our terms and conditions of business.</p>
    <p style="margin-top:20px;">All subsequent information provided by the client or ascertained
        otherwise was assessed during planning stage of the project and a suitable Plan of Work
        produced. Where information was provided regarding the presence of known or presumed asbestos
        materials then this has been validated during the course of the survey, and recorded within this
        report. This survey was carried out in accordance with documented in house procedures and HSE
        Guidance document HSG 264.</p>
    <p style="margin-top:20px;"><strong>Scope of Works:</strong></p>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
           style="margin-top:20px;margin-bottom: 0;">
        <tbody>
        <thead>
        <tr>
            <th colspan="4" style="width:100%;font-weight: bold;padding-bottom: 15px;">Section 2: Scope
                of Works
            </th>
        </tr>
        </thead>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">Purpose of Survey</th>
            <th colspan="3" style="padding-bottom: 15px;">Develop their Management Policy / Plan or
                Acquisition / Sale
            </th>
        </tr>
        <tr>
            <th style="width: 30%;background: #e2e2e2;padding-bottom: 15px;">Property Type</th>
            <th style="width: 20%;padding-bottom: 15px;">Industrial</th>
            <th style="width: 20%;background: #e2e2e2;padding-bottom: 15px;">Property Status</th>
            <th style="width: 20%;padding-bottom: 15px;">Operational</th>
        </tr>
        <tr style="font-weight: bold;">
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Survey Type Required?</strong>
            </th>
            <th colspan="3" style="padding-bottom: 15px;"><strong>Management</strong></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong style="font-weight: bold;">Scope / Extent of Survey </strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(including any areas of the building /
                    site to be excluded)</p>
            </th>
            <th colspan="3">
                <strong style="font-weight: bold;">Asbestos management survey including priority
                    assessments in accordance to {{ env('APP_DOMAIN') ?? 'GSK' }} ' Scope of Services for Asbestos Management
                    Surveys'</strong>
                <p style="margin-top:20px;">Access must be made within ducts, trenches, risers and voids
                    etc. that have access panels or hatches. This includes external service trenches
                    where purpose made access covers are available. Survey Team to liaise with site host
                    prior to any access into ducts or trenches.</p>
                <p style="margin-top:20px;">Survey team need to establish the substrate and potential
                    presence of any suspect items below fixed floorings (carpets/carpet tiles/vinyls) in
                    discreet locations. And where it can be lifted and replaced in a manner where no
                    damage is caused or trip hazard is left.</p>
                <p style="margin-top:20px;">If the survey team suspect the presence of asbestos
                    containing material within live electrical services then they must contact the site
                    host to isolate and provide access for inspection.</p>
                <p style="margin-top:20px;padding-bottom: 15px;">Where possible the survey team must
                    record the findings and extents of suspected gaskets in relation to individual pipe
                    runs.</p>
            </th>
        </tr>
        </tbody>
    </table>
    <?php } elseif ($survey->property->zone_id == 4) {
    if ($survey->property_id == 98) { ?>
    <p style="margin-top:20px;">Life Environmental Services Ltd received an order of confirmation to
        undertake a Management Survey in accordance with the Scope of Services document from
        GlaxoSmithKline for the 34 Berkeley Square site. This order has been accepted on the basis
        of the original Quotation and our terms and conditions of business.</p>
    <p style="margin-top:20px;">All subsequent information provided by the client or ascertained
        otherwise was assessed during planning stage of the project and a suitable Plan of Work
        produced. Where information was provided regarding the presence of known or presumed
        asbestos materials then this has been validated during the course of the survey, and
        recorded within this report. This survey was carried out in accordance with documented in
        house procedures and HSE Guidance document HSG 264.</p>
    <p style="margin-top:20px;"><strong>Scope of Works:</strong></p>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
           style="margin-top:20px;margin-bottom: 0;">
        <tbody>
        <thead>
        <tr>
            <th colspan="4" style="width:100%;font-weight: bold;padding-bottom: 15px;">Section 2:
                Scope of Works
            </th>
        </tr>
        </thead>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">Purpose of Survey</th>
            <th colspan="3" style="padding-bottom: 15px;">Develop their Management Policy / Plan or
                Acquisition / Sale
            </th>
        </tr>
        <tr>
            <th style="width: 30%;background: #e2e2e2;padding-bottom: 15px;">Property Type</th>
            <th style="width: 20%;padding-bottom: 15px;">Industrial</th>
            <th style="width: 20%;background: #e2e2e2;padding-bottom: 15px;">Property Status</th>
            <th style="width: 20%;padding-bottom: 15px;">Operational</th>
        </tr>
        <tr style="font-weight: bold;">
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Survey Type
                    Required?</strong></th>
            <th colspan="3" style="padding-bottom: 15px;"><strong>Management</strong></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong style="font-weight: bold;">Scope / Extent of Survey </strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(including any areas of the
                    building / site to be excluded)</p>
            </th>
            <th colspan="3">
                <strong style="font-weight: bold;">Asbestos management survey including priority
                    assessments in accordance to {{ env('APP_DOMAIN') ?? 'GSK' }} ' Scope of Services for Asbestos Management
                    Surveys'</strong>
                <p style="margin-top:20px;">Access must be made within ducts, trenches, risers and
                    voids etc. that have access panels or hatches.</p>
                <p style="margin-top:20px;">Survey team need to establish the substrate and
                    potential presence of any suspect items below fixed floorings (carpets/carpet
                    tiles/vinyls) in discreet locations. And where it can be lifted and replaced in
                    a manner where no damage is caused or trip hazard is left.</p>
                <p style="margin-top:20px;">If the survey team suspect the presence of asbestos
                    containing material within live electrical services then they must contact the
                    site host to isolate and provide access for inspection.</p>
                <p style="margin-top:20px;padding-bottom: 15px;">Where possible the survey team must
                    record the findings and extents of suspected gaskets in relation to individual
                    pipe runs.</p>
            </th>
        </tr>
        </tbody>
    </table>
    <?php }
    }
    }
    }
    } ?>
    <div style="page-break-before: always;"></div>
    <div class="topTitleBig" style="margin-top:30px;">Survey Information</div>
    <h3 style="margin-top:20px;">Method</h3>
    <?php
    if($survey_method_question_data->isEmpty()) {
    if (optional($survey->surveyInfo)->method) {
        echo $survey->surveyInfo->method;
    } else {
    if($survey->survey_type == 3){ ?>
    <p style="margin-top:20px;">Management element of reinspection Survey as and when required - The following access requirements have been agreed at Quotation Stage. Intrusive access and other access provision - Based on agreed Scope:</p>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
           style="margin-top:20px;margin-bottom: 0;">
        <tbody>
        <thead>
        <tr>
            <th colspan="3" style="width:100%;padding-bottom: 15px;">Scoping table</th>
        </tr>
        <tr>
            <th colspan="3" style="width:100%;padding-bottom: 15px;"><strong>Access Allowances – The
                    following access requirements have been agreed at Quotation Stage</strong></th>
        </tr>
        <tr>
            <th style="width: 32%;background: #e2e2e2;padding-bottom: 15px;"><strong>Intrusive
                    access
                    and other access provision - Based on agreed Scope</strong></th>
            <th style="width: 26%;background: #e2e2e2;padding-bottom: 15px;"><strong>Areas included
                    within Scope of survey</strong></th>
            <th style="width: 32%;background: #e2e2e2;padding-bottom: 15px;"><strong>Specific
                    Allowances</strong></th>
        </tr>
        </thead>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Height</strong> access
                provision
            </th>
            <th style="padding-bottom: 15px;">Standard (3m)</th>
            <th style="padding-bottom: 15px;">Standard survey step ladders are to be used access up to 3m in accordance with SOP`s
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>loof Spaces</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(Note: access will only be made where safe and sufficient walkways are available)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Electrical
                    switchgear</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Liaise with site contact to discuss the possibility of accessing electrics if surveyor deems the potential for ACM’s to be present.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Plant / equipment</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Liaise with site contact to discuss the possibility of accessing plant if surveyor deems the potential for ACM’s to be present.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Lift shafts </strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Escalator Pits</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Confined spaces</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>External soffits &
                    Fascias</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Where required and safe to do so. </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Roof</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(requiring specialist equipment) </p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Where required and fixed edge protection is in place.</th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Boxing</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(readily accessible by removable panels)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">All boxing and falsework to be accessed where access hatches or panels to be utilised. If accessed the surveyor is to record this as a non-asbestos item.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Solid Wall
                    cavities</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Partition Wall
                    cavities</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Wall Cladding &
                    Coverings</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;"><strong>Fixed suspended ceilings</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Fixed suspended ceilings to be accessed where access hatches or panels can be utilised. The surveyor is to record this item as a void investigation. </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Glazing</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Window Frames</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Window sills</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Door Frames</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Doors internally</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Surveyor to establish the presence of internal door linings by visible open edges only. NO intrusions to be made within any fire door. The surveyor is to record this item in the Construction details when an open edge cannot be utilised without causing damage.</th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">
                <strong>Concealed Risers or Voids</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(Known or identified during survey)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Risers and voids to be accessed where access hatches or panels can be utilised. The surveyor is to record this item as a void investigation.</th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Ventilation trunking</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(fume trunking should be specifically identified and assessed)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;"><strong>Skirting</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Fixed Flooring</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Survey team need to establish the substrate and potential presence of any suspect items below fixed flooring in discreet locations. And where it can be lifted and replaced in a manner where no damage is caused or a trip hazard is left. If no suspect items are found the surveyor is to record this item in the Construction Details. </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Floor voids</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;"> Floor voids to be accessed where access hatches or panels can be utilised. The surveyor is to record this item as a void investigation.</th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">
                <strong>Floor ducts</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(specific details / layout required; specialist lifting equipment; covered or known)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Below Ground Drainage
                    Systems</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">
                <strong>Slab</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(specify depth / diameter)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        </tbody>
    </table>
    <?php }else {
    if ($survey->client_id == 2) {
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
    } elseif ($survey->client_id == 3) {
    if ($survey->property->zone_id == 9) { ?>
    <p style="margin-top:20px;">Management Survey - Access Allowances – The following access
        requirements have been agreed at Quotation Stage. Intrusive access and other access
        provision -
        Based on agreed Scope:</p>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
           style="margin-top:20px;margin-bottom: 0;">
        <tbody>
        <thead>
        <tr>
            <th colspan="3" style="width:100%;padding-bottom: 15px;">Scoping table</th>
        </tr>
        <tr>
            <th colspan="3" style="width:100%;padding-bottom: 15px;"><strong>Access Allowances – The
                    following access requirements have been agreed at Quotation Stage</strong></th>
        </tr>
        <tr>
            <th style="width: 32%;background: #e2e2e2;padding-bottom: 15px;"><strong>Intrusive
                    access
                    and other access provision - Based on agreed Scope</strong></th>
            <th style="width: 26%;background: #e2e2e2;padding-bottom: 15px;"><strong>Areas included
                    within Scope of survey</strong></th>
            <th style="width: 32%;background: #e2e2e2;padding-bottom: 15px;"><strong>Specific
                    Allowances</strong></th>
        </tr>
        </thead>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Height</strong> access
                provision
            </th>
            <th style="padding-bottom: 15px;">Standard (3m)</th>
            <th style="padding-bottom: 15px;">High level equipment can be arrange at surveyors
                request
                via PM.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>loof Spaces</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(Note: access for management
                    surveys
                    will only be made where safe and sufficient walkways are available)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Electrical
                    switchgear</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">If isolation has been confirmed by CBRE on surveyors
                suspected item requests.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Plant / equipment</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">If isolation has been confirmed by CBRE on surveyors
                suspected item requests.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Lift shafts </strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">CBRE to organise.</th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Escalator Pits</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Confined spaces</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>External soffits &
                    Fascias</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Where practicable</th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Roof</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(requiring specialist
                    equipment)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Where practicable</th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Boxing</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(readily accessible by removable
                    panels)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">All boxing and falsework to be accessed where access
                panels are present.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Solid Wall
                    cavities</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Partition Wall
                    cavities</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Wall Cladding &
                    Coverings</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;"><strong>Fixed suspended ceilings</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Glazing</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Window Frames</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Window sills</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Door Frames</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Doors internally</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">
                <strong>Concealed Risers or Voids</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(Known or identified during
                    survey)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Ventilation trunking</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(fume trunking should be
                    specifically
                    identified and assessed)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;"><strong>Skirting</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Fixed Flooring</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Floor voids</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">
                <strong>Floor ducts</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(specific details / layout
                    required;
                    specialist lifting equipment; covered or known)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">If reasonable access is available and not a Confined
                Space.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Below Ground Drainage
                    Systems</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">
                <strong>Slab</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(specify depth / diameter)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        </tbody>
    </table>
    <?php } elseif ($survey->property->zone_id == 2) { ?>
    <p style="margin-top:20px;">Management Survey - Access Allowances – The following access
        requirements have been agreed at Quotation Stage. Intrusive access and other access
        provision -
        Based on agreed Scope:</p>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
           style="margin-top:20px;margin-bottom: 0;">
        <tbody>
        <thead>
        <tr>
            <th colspan="3" style="width:100%;padding-bottom: 15px;">Scoping table</th>
        </tr>
        <tr>
            <th colspan="3" style="width:100%;padding-bottom: 15px;"><strong>Access Allowances – The
                    following access requirements have been agreed at Quotation Stage</strong></th>
        </tr>
        <tr>
            <th style="width: 32%;background: #e2e2e2;padding-bottom: 15px;"><strong>Intrusive
                    access
                    and other access provision - Based on agreed Scope</strong></th>
            <th style="width: 26%;background: #e2e2e2;padding-bottom: 15px;"><strong>Areas included
                    within Scope of survey</strong></th>
            <th style="width: 32%;background: #e2e2e2;padding-bottom: 15px;"><strong>Specific
                    Allowances</strong></th>
        </tr>

        </thead>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Height </strong>access
                provision
            </th>
            <th style="padding-bottom: 15px;">Power (10m+)</th>
            <th style="padding-bottom: 15px;">Survey team to operate MEWP to access high level areas
                Externally. Standard survey step ladders are to be used access up to 3m in
                accordance
                with SOP`s.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Loft Spaces</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(Note: access for management
                    surveys
                    will only be made where safe and sufficient walkways are available)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Electrical
                    switchgear</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">CBRE to isolate and provide safe access.</th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Plant / equipment</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Where possible CBRE to isolate and provide safe access
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Lift shafts </strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">External contractor provided by the client to isolate
                and
                provide safe access
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Escalator Pits</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Confined spaces</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>External soffits &
                    Fascias</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Trained personnel to operate MEWP to access high level
                areas.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Roof</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(requiring specialist
                    equipment) </p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Only if safe access is achievable and edge protection
                is
                in place.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Boxing</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(readily accessible by removable
                    panels)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">All boxing and falsework to be accessed where access
                hatches or panels can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Solid Wall
                    cavities</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Partition Wall
                    cavities</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Wall Cladding &
                    Coverings</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Fixed suspended
                    ceilings</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;">Fixed suspended ceilings to be accessed where access
                hatches or panels can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Glazing</strong></th>
            <th style="padding-bottom: 15px;">no</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Window Frames</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Window sills</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Door Frames</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Doors internally</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Survey team to establish the presence of internal door
                linings by visible open edges only. NO intrusions to be made within any fire door.
                <door class=""></door>
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Concealed Risers or Voids</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(Known or identified during
                    survey)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Risers and voids to be accessed where access hatches
                or
                panels can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Ventilation trunking</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(fume trunking should be
                    specifically
                    identified and assessed)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Skirting</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Fixed Flooring</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Survey team need to establish the substrate and
                potential
                presence of any suspect items below fixed carpets/carpet tiles in discreet
                locations.
                And where it can be lifted and replaced in a manner where no damage is caused or
                trip
                hazard is left.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Floor voids</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Floor voids to be accessed where access hatches or
                panels
                can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">
                <strong>Floor ducts</strong>
                <p style="margin-top:20px;">(specific details / layout required; specialist lifting
                    equipment; covered or known)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Floor ducts to be accessed where access hatches or
                purpose
                made access covers can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Below Ground Drainage
                    Systems</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Slab</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(specify depth / diameter)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        </tbody>
    </table>
    <?php } elseif ($survey->property->zone_id == 6) { ?>
    <p style="margin-top:20px;">Management Survey - Access Allowances – The following access
        requirements have been agreed at Quotation Stage. Intrusive access and other access
        provision -
        Based on agreed Scope:</p>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
           style="margin-top:20px;margin-bottom: 0;">
        <tbody>
        <thead>
        <tr>
            <th colspan="3" style="width:100%;padding-bottom: 15px;">Scoping table</th>
        </tr>
        <tr>
            <th colspan="3" style="width:100%;padding-bottom: 15px;"><strong>Access Allowances – The
                    following access requirements have been agreed at Quotation Stage</strong></th>
        </tr>
        <tr>
            <th style="width: 32%;background: #e2e2e2;padding-bottom: 15px;"><strong>Intrusive
                    access
                    and other access provision - Based on agreed Scope</strong></th>
            <th style="width: 26%;background: #e2e2e2;padding-bottom: 15px;"><strong>Areas included
                    within Scope of survey</strong></th>
            <th style="width: 32%;background: #e2e2e2;padding-bottom: 15px;"><strong>Specific
                    Allowances</strong></th>
        </tr>

        </thead>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Height </strong>access
                provision
            </th>
            <th style="padding-bottom: 15px;">Power (10m+)</th>
            <th style="padding-bottom: 15px;">Survey team to operate MEWP to access high level areas
                Externally. Standard survey step ladders are to be used access up to 3m in
                accordance
                with SOP`s.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Loft Spaces</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(Note: access for management
                    surveys
                    will only be made where safe and sufficient walkways are available)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Electrical
                    switchgear</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">CBRE to isolate and provide safe access.</th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Plant / equipment</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Where possible CBRE to isolate and provide safe access
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Lift shafts </strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">External contractor provided by the client to isolate
                and
                provide safe access
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Escalator Pits</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Confined spaces</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>External soffits &
                    Fascias</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Trained personnel to operate MEWP to access high level
                areas.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Roof</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(requiring specialist
                    equipment) </p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Only if safe access is achievable and edge protection
                is
                in place.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Boxing</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(readily accessible by removable
                    panels)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">All boxing and falsework to be accessed where access
                hatches or panels can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Solid Wall
                    cavities</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Partition Wall
                    cavities</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Wall Cladding &
                    Coverings</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Fixed suspended
                    ceilings</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Fixed suspended ceilings to be accessed where access
                hatches or panels can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Glazing</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Window Frames</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Window sills</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Door Frames</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Doors internally</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Survey team to establish the presence of internal door
                linings by visible open edges only. NO intrusions to be made within any fire door.
                <door class=""></door>
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Concealed Risers or Voids</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(Known or identified during
                    survey)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Risers and voids to be accessed where access hatches
                or
                panels can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Ventilation trunking</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(fume trunking should be
                    specifically
                    identified and assessed)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Skirting</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Fixed Flooring</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Survey team need to establish the substrate and
                potential
                presence of any suspect items below fixed carpets/carpet tiles in discreet
                locations.
                And where it can be lifted and replaced in a manner where no damage is caused or
                trip
                hazard is left.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Floor voids</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Floor voids to be accessed where access hatches or
                panels
                can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">
                <strong>Floor ducts</strong>
                <p style="margin-top:20px;">(specific details / layout required; specialist lifting
                    equipment; covered or known)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Floor ducts to be accessed where access hatches or
                purpose
                made access covers can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Below Ground Drainage
                    Systems</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Slab</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(specify depth / diameter)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        </tbody>
    </table>
    <?php } elseif ($survey->property->zone_id == 4) {
    if ($survey->property_id == 98) { ?>
    <p style="margin-top:20px;">Management Survey - Access Allowances – The following access
        requirements have been agreed at Quotation Stage. Intrusive access and other access
        provision - Based on agreed Scope:Management Survey - Access Allowances – The following
        access requirements have been agreed at Quotation Stage. Intrusive access and other
        access
        provision - Based on agreed Scope:</p>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
           style="margin-top:20px;margin-bottom: 0;">
        <tbody>
        <thead>
        <tr>
            <th colspan="3" style="width:100%;padding-bottom: 15px;">Scoping table</th>
        </tr>
        <tr>
            <th colspan="3" style="width:100%;padding-bottom: 15px;"><strong>Access Allowances –
                    The
                    following access requirements have been agreed at Quotation Stage</strong>
            </th>
        </tr>
        <tr>
            <th style="width: 32%;background: #e2e2e2;padding-bottom: 15px;"><strong>Intrusive
                    access and other access provision - Based on agreed Scope</strong></th>
            <th style="width: 26%;background: #e2e2e2;padding-bottom: 15px;"><strong>Areas
                    included
                    within Scope of survey</strong></th>
            <th style="width: 32%;background: #e2e2e2;padding-bottom: 15px;"><strong>Specific
                    Allowances</strong></th>
        </tr>

        </thead>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Height </strong>access
                provision
            </th>
            <th style="padding-bottom: 15px;">Standard 3m</th>
            <th style="padding-bottom: 15px;">Standard survey step ladders are to be used access
                up
                to 3m in accordance with SOP`s
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Loft Spaces</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(Note: access for management
                    surveys will only be made where safe and sufficient walkways are
                    available)(Note: access for management surveys will only be made where safe
                    and
                    sufficient walkways are available)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Electrical
                    switchgear</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Contractor to isolate and provide safe access.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Plant /
                    equipment</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">External contractor provided by the client to
                isolate
                and provide safe access
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Lift shafts </strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">External contractor provided by the client to
                isolate
                and provide safe access
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Escalator
                    Pits</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Confined
                    spaces</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>External soffits &
                    Fascias</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Roof</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(requiring specialist
                    equipment) </p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Only if safe access is achievable and edge
                protection
                is in place.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Boxing</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(readily accessible by
                    removable
                    panels)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">All boxing and falsework to be accessed where
                access
                hatches or panels can be utilised.All boxing and falsework to be accessed where
                access hatches or panels can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Solid Wall
                    cavities</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Partition Wall
                    cavities</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Wall Cladding &
                    Coverings</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Fixed suspended
                    ceilings</strong></th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Fixed suspended ceilings to be accessed where
                access
                hatches or panels can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Glazing</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Window Frames</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Window sills</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Door Frames</strong>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Doors
                    internally</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Survey team to establish the presence of internal
                door
                linings by visible open edges only.
                <door class=""></door>
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Concealed Risers or Voids</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(Known or identified during
                    survey)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Risers and voids to be accessed where access
                hatches
                or panels can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Ventilation trunking</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(fume trunking should be
                    specifically identified and assessed)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Skirting</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Fixed
                    Flooring</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Survey team need to establish the substrate and
                potential presence of any suspect items below fixed carpets/carpet tiles in
                discreet
                locations. And where it can be lifted and replaced in a manner where no damage
                is
                caused or trip hazard is left.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Floor voids</strong>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Floor voids to be accessed where access hatches or
                panels can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;">
                <strong>Floor ducts</strong>
                <p style="margin-top:20px;">(specific details / layout required; specialist
                    lifting
                    equipment; covered or known)</p>
            </th>
            <th style="padding-bottom: 15px;">Yes</th>
            <th style="padding-bottom: 15px;">Floor ducts to be accessed where access hatches or
                purpose made access covers can be utilised.Floor ducts to be accessed where
                access
                hatches or purpose made access covers can be utilised.
            </th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Below Ground Drainage
                    Systems</strong></th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="background: #e2e2e2;">
                <strong>Slab</strong>
                <p style="margin-top:20px;padding-bottom: 15px;">(specify depth / diameter)</p>
            </th>
            <th style="padding-bottom: 15px;">No</th>
            <th style="padding-bottom: 15px;"></th>
        </tr>
        </tbody>
    </table>
    <?php }
    }
    } elseif ($survey->client_id == 5) { ?>
    <p style="margin-top:20px;"><strong><u>Survey Methodology</u></strong></p>

    <p style="margin-top:20px;">Ayerst Environmental surveys are carried out in accordance with the
        requirements of HSG264 “Asbestos: The survey guide”, and in house surveying methods. Ayerst
        Environmental is a UKAS accredited inspection body (No. 246) and UKAS accredited Testing
        Laboratory
        (No. 0612).</p>

    <p style="margin-top:20px;">The types of survey and requirements of these inspections can be found
        in
        HSG264 Paragraphs 40 to 54. This document is currently available as a free download from the HSE
        books website.</p>

    <p style="margin-top:20px;">An asbestos survey is only part of the process through which to
        effectively
        manage the risks from asbestos containing materials (ACMs), but the survey report itself is not
        an
        asbestos management plan. A summary of the actions that may need to be taken can be found in
        Survey
        Appendices section of this report, referenced from HSG227 “A comprehensive guide to Managing
        asbestos in premises”.</p>

    <p style="margin-top:20px;">During a survey bulk samples of suspect asbestos containing materials
        may be
        extracted to determine the nature and extent of the material, and the results of their
        laboratory
        analysis are given in the Asbestos Identification Report included in this report. Bulk sampling
        was
        carried out in accordance with documented in-house methods and HSE guidance note HSG264
        “Asbestos:
        The survey guide’ 2012” available to download from the HSE books website.</p>

    <p style="margin-top:20px;">At the discretion of the surveyor, where instances of asbestos
        containing
        material appeared to be extensive, representative samples were taken for analysis whilst other
        occurrences of the apparently same materials were referred to those sampled.</p>

    <p style="margin-top:20px;">Bulk sample analysis was carried out in accordance with HSE Guidance
        Note
        HSG248 “Asbestos: The analysts’ guide for sampling, analysis and clearance procedures” and
        documented in-house methods under our UKAS accreditation No. 0612.</p>

    <p style="margin-top:20px;">The three most commonly used types of asbestos were:</p><br>
    <strong style="margin-top:20px;">• CRYSOTILE ASBESTOS</strong><br>
    <strong>• AMOSITE ASBESTOS</strong><br>
    <strong>• CROCIDOLITE ASBESTOS</strong><br>
    <?php }
    }
    }
    }else{ ?>
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
    } else{
    if($survey->survey_type == 3){ ?>
    <p style="margin-top:20px;">Life Environmental Services Ltd, make every effort to locate and identify all Asbestos Containing Materials (ACMs), within the scope of the agreed inspection brief, supplied by the client. Due to the nature of Asbestos distribution and uncontrolled usage within buildings built prior to 1999, Life Environmental Services Ltd will not accept any liability for claims arising from post survey, hidden unidentified ACMs, or contamination arising from their subsequent disturbance.</p>
    <p style="margin-top:20px;">Due to Management Surveys being non-intrusive in their nature, asbestos may remain unidentified within common locations where non-intrusive inspection would not normally be possible, for example:</p>
    <p style="margin-top:20px;">
    <ul>
        <li>As internal linings to fire doors and hatches</li>
        <li>As packing around door and window frames within the fabric of the building including cavity walls, floor voids and foundations, etc.</li>
        <li>Behind or within fixed wall linings, fixed boxings, fixed ceilings, etc.</li>
        <li>Below fixed floor coverings</li>
        <li>Below existing felt and bitumen roof coverings Within drainage systems and below ground services Within chimneys and chimney breasts</li>
        <li>Residual asbestos material may be present beneath re-insulated services and cannot be detected unless the insulation is systematically removed. Caution should therefore be taken when working on such materials for the potential presence of asbestos residue</li>
    </ul>
    </p>
    <?php }else {
    if ($survey->client_id == 2) {
    ?>
    <p style="margin-top:20px;">Due to <?= $surveyTitle ?> being non-intrusive in their nature, asbestos may
        remain
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
        <li>Residual asbestos material may be present beneath re-insulated services and cannot be detected
            unless
            the insulation is systematically removed. Caution should therefore be taken when working on such
            materials for the potential presence of asbestos residue
        </li>

    </ul>

    <?php
    } elseif ($survey->client_id == 5) { ?>
    <p style="margin-top:20px;">Paragraphs 55 to 58 of the HSG264 give specific guidance with regards to
        agreeing site specific limitations for asbestos surveys.</p>
    <p>Where possible, a site based and desktop scoping document would have been completed as part of the
        survey planning process and specific limitations agreed. The following items are listed as general
        limitations due to the sometimes difficult nature of carrying out asbestos inspections.</p>

    <p style="margin-top:20px;">Specific limitations, descriptions and locations will also be listed in the
        Executive Summary. These items may require further inspection if and when required, depending on the
        needs of the client or duty holder. In all cases, each item, where applicable, will be agreed by the
        lead surveyor and client, or their agent, during the course of the survey, and noted in the
        register.</p>
    <p style="margin-top:20px;">Ducts, risers and loft spaces are only entered for inspection where this can
        be done safely, although every effort will be made to access these areas, including revisits where
        practicable. This should be agreed in the scope of works.</p><br>
    <p style="margin-top:20px;">Safety limitations to inspections can apply to all types of surveys. This
        includes occupation of the premises at the time of the survey. It also includes inspections behind
        suspected asbestos containing materials (for example, riser cover panels that may contain asbestos
        will not be disturbed. Therefore inspections within the riser will not be possible). If laboratory
        analysis shows that the material does not contain asbestos, a further inspection should be arranged
        by the duty holder/client to inspect behind this material. If asbestos is identified, the material
        will need to be removed prior to any further inspections. Any further inspections will be subject to
        additional costs.</p><br>
    <p style="margin-top:20px;">Asbestos may well be hidden as part of the structure of a building and not
        visible until the structure is dismantled at a later date. We cannot undertake intrusive
        investigations where, in our view, doing so might possibly lead to structural defects or damage to
        the property unless agreed in the survey scope. Also if a client has requested that a building is
        fit to be occupied immediately after a survey, this might also limit the extent of any intrusive
        inspections.</p><br>
    <p style="margin-top:20px;">Extent (amount) of materials are estimated and are only an approximate
        measure.</p><br>
    <p style="margin-top:20px;">Drawings should not be used for scaling purposes. They serve only to assist
        with locations of findings.</p><br>
    <p style="margin-top:20px;">Where no access or limited access has been gained, the client should assume
        asbestos is present in those areas. Further investigation will be required before allowing any work
        to be carried out in these areas. The following will apply to all management surveys:</p><br>
    <li style="list-style:none;">
        <ul style="list-style:none;">
            <li>Unless specifically documented no access has been gained below fitted floor coverings as to
                do so would cause damage and is outside of the scope of a management survey.
            </li>
            <br>
            <li>Unless specifically documented no access has been gained below metal clad pipework or
                ducting, or below sealed insulations as to do so would cause damage and is outside of the
                scope of the management survey.
            </li>
            <br>
            <li>Throughout, boxings will only be accessed if possible without causing damage. Sealed boxing
                is out of the scope of the survey and should be inspected as part of a refurbishment survey
                prior to works.
            </li>
            <br>
        </ul>
    </li>
    <p style="margin-top:20px;">Ayerst Environmental Ltd is not accredited for carrying out priority risk
        assessments and accepts no liability for priority assessments made based on data provided by our
        surveyors. The accuracy of the information recorded for priority assessments cannot be guaranteed as
        it is only a snapshot of what was viewed on the day by the surveyor. This will not always be an
        accurate reflection of the regular activities that might occur in an area.</p>

    <?php } elseif ($survey->client_id == 3) {
    if ($survey->property->zone_id == 9) { ?>
    <p style="margin-top:20px;">Life Environmental Services Ltd, make every effort to locate and
        identify all asbestos containing materials (ACMs), within the scope of the agreed inspection
        brief, supplied by the client. Due to the nature of Asbestos distribution and uncontrolled usage
        within buildings built prior to 1999, Life Environmental Services Ltd will not accept any
        liability for claims arising from post survey, hidden unidentified ACMs, or contamination
        arising from their subsequent disturbance.</p>
    <p style="margin-top:20px;">Due to Management Surveys being non-intrusive in their nature, asbestos
        may remain unidentified within common locations where non-intrusive inspection would not
        normally be possible, for example:</p>
    <p style="margin-top:20px;">
    <ul type="disc" style="margin-top:20px;">
        <li>As internal linings to fire doors and hatches</li>
        <li>As packing around door and window frames</li>
        <li>Within the fabric of the building including cavity walls, floor voids and foundations,
            etc.
        </li>
        <li>Behind or within fixed wall linings, fixed boxings, fixed ceilings, etc.</li>
        <li>Below fixed floor coverings</li>
        <li>Below existing felt and bitumen roof coverings</li>
        <li>Within drainage systems and below ground services</li>
        <li>Within chimneys and chimney breasts</li>
        <li>Residual asbestos material may be present beneath re-insulated services and cannot be
            detected unless the insulation is systematically removed. Caution should therefore be taken
            when working on such materials for the potential presence of asbestos residue
        </li>
    </ul>
    </p>
    <?php } elseif ($survey->property->zone_id == 2) { ?>
    <p style="margin-top:20px;">Life Environmental Services Ltd, make every effort to locate and
        identify all Asbestos Containing Materials (ACMs), within the scope of the agreed inspection
        brief, supplied by the client. Due to the nature of Asbestos distribution and uncontrolled usage
        within buildings built prior to 1999, Life Environmental Services Ltd will not accept any
        liability for claims arising from post survey, hidden unidentified ACMs, or contamination
        arising from their subsequent disturbance.</p>
    <p style="margin-top:20px;">Due to Management Surveys being non-intrusive in their nature, asbestos
        may remain unidentified within common locations where non-intrusive inspection would not
        normally be possible, for example:</p>
    <p style="margin-top:20px;">
    <ul type="disc" style="margin-top:20px;">
        <li>As internal linings to fire doors and hatches</li>
        <li>As packing around door and window frames</li>
        <li>Within the fabric of the building including cavity walls, floor voids and foundations,
            etc.
        </li>
        <li>Behind or within fixed wall linings, fixed boxings, fixed ceilings, etc.</li>
        <li>Below fixed floor coverings</li>
        <li>Below existing felt and bitumen roof coverings</li>
        <li>Within drainage systems and below ground services</li>
        <li>Within chimneys and chimney breasts</li>
        <li>Residual asbestos material may be present beneath re-insulated services and cannot be
            detected unless the insulation is systematically removed. Caution should therefore be taken
            when working on such materials for the potential presence of asbestos residue
        </li>
    </ul>
    </p>
    <?php } elseif ($survey->property->zone_id == 6) { ?>
    <p style="margin-top:20px;">Life Environmental Services Ltd, make every effort to locate and
        identify all asbestos containing materials (ACMs), within the scope of the agreed inspection
        brief, supplied by the client. Due to the nature of Asbestos distribution and uncontrolled usage
        within buildings built prior to 1999, Life Environmental Services Ltd will not accept any
        liability for claims arising from post survey, hidden unidentified ACMs, or contamination
        arising from their subsequent disturbance.</p>
    <p style="margin-top:20px;">Due to Management Surveys being non-intrusive in their nature, asbestos
        may remain unidentified within common locations where non-intrusive inspection would not
        normally be possible, for example:</p>
    <p style="margin-top:20px;">
    <ul type="disc" style="margin-top:20px;">
        <li>As internal linings to fire doors and hatches</li>
        <li>As packing around door and window frames</li>
        <li>Within the fabric of the building including cavity walls, floor voids and foundations,
            etc.
        </li>
        <li>Behind or within fixed wall linings, fixed boxings, fixed ceilings, etc.</li>
        <li>Below fixed floor coverings</li>
        <li>Below existing felt and bitumen roof coverings</li>
        <li>Within drainage systems and below ground services</li>
        <li>Within chimneys and chimney breasts</li>
        <li>Residual asbestos material may be present beneath re-insulated services and cannot be
            detected unless the insulation is systematically removed. Caution should therefore be taken
            when working on such materials for the potential presence of asbestos residue
        </li>
    </ul>
    </p>
    <?php } elseif ($survey->property->zone_id == 4) {
    if ($survey->property_id == 98) { ?>
    <p style="margin-top:20px;">Life Environmental Services Ltd, make every effort to locate and
        identify all Asbestos Containing Materials (ACMs), within the scope of the agreed inspection
        brief, supplied by the client. Due to the nature of Asbestos distribution and uncontrolled
        usage within buildings built prior to 1999, Life Environmental Services Ltd will not accept
        any liability for claims arising from post survey, hidden unidentified ACMs, or
        contamination arising from their subsequent disturbance.</p>
    <p style="margin-top:20px;">Due to Management Surveys being non-intrusive in their nature,
        asbestos may remain unidentified within common locations where non-intrusive inspection
        would not normally be possible, for example: </p>
    <p style="margin-top:20px;">
    <ul type="disc" style="margin-top:20px;">
        <li>As internal linings to fire doors and hatches</li>
        <li>As packing around door and window frames</li>
        <li>Within the fabric of the building including cavity walls, floor voids and foundations,
            etc.
        </li>
        <li>Behind or within fixed wall linings, fixed boxings, fixed ceilings, etc.</li>
        <li>Below fixed floor coverings</li>
        <li>Below existing felt and bitumen roof coverings</li>
        <li>Within drainage systems and below ground services</li>
        <li>WWithin chimneys and chimney breasts</li>
        <li>Residual asbestos material may be present beneath re-insulated services and cannot be
            detected unless the insulation is systematically removed. Caution should therefore be
            taken when working on such materials for the potential presence of asbestos residue
        </li>
    </ul>
    </p>
    <?php }
    }
    }
    }
    }
    ?>


    <div style="page-break-before: always;"></div>
    <div id="Specifc-Exclusions" style="margin-top:30px;">
        <div class="topTitleBig">Survey Information</div>
        <h3 style="margin-top:20px;">Specific Exclusions</h3>
        <?php if($survey->survey_type == 3){ ?>
        <p style="margin-top:20px;">Where detailed, it was agreed at the pre-survey stage that the following room/locations would be excluded from the scope of Survey. The room/locations do not include more general exclusions (i.e. inaccessible room/locations/items) detailed elsewhere.</p>
        <p style="margin-top:20px;">The survey was limited to those areas accessible at the time of the survey (and as agreed at the pre-survey stage). Flues, ducts, voids or any similarly enclosed areas, have not been inspected (unless an appropriate access hatch or inspection panel was present), as gaining such access would necessitate the use of specialist equipment/tools or require overly destructive work.</p>
        <p style="margin-top:20px;">No responsibility is accepted for the presence of asbestos in voids (under floor, floor, wall or ceiling) other than those opened up during the investigation (unless agreed at the pre-survey stage).</p>
        <p style="margin-top:20px;">Areas requiring specialist access arrangements or equipment (other than stepladders) will not be assessed unless otherwise stated and agreed at the pre-survey stage. Fire doors were not inspected internally to ascertain if they are manufactured using ACMs as to do so would entail overly destructive testing procedures.</p>
        <p style="margin-top:20px;">Whilst every effort will have been made to identify the true nature and extent of the asbestos material present in the building surveyed, no responsibility has been accepted for the presence of asbestos in materials other than those sampled at the requisite density. Inspection of pipe work has been restricted primarily to the insulation visible (sampled in accordance with HSG264 guidelines), therefore only a limited inspection has been carried out of pipework concealed by overlaying non-asbestos insulation.</p>
        <?php }else { ?>
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
        <?php }
        } ?>
    </div><!--Survey Information PAGE 08-->

@else
    <div style="page-break-before: always;"></div>
    <h2 style="margin-top:30px;">Survey Information</h2>
    <?php if($survey->client_id == 4){ ?>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:20px;margin-bottom: 0;">
        <thead>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">Scope of works (including proposed refurbishment where applicable)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th style="width: 100%;padding-bottom: 15px;">Survey of site, to enable the client to manage the risks posed from any ACM's identified.   Survey to include out buildings and a walk through of external areas, to check for any obvious miscellaneous materials</th>
        </tr>
        </tbody>
    </table>
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:20px;margin-bottom: 0;">
        <thead>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">Elements to be inspected</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">All surveys</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;"><stong>Element</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Usual inspection method (if applicable)</stong></th>
            <th style="width: 10%;padding-bottom: 15px;"><stong>Included?</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Comments</stong></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">All areas within site boundary</th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection of all areas within the site boundaries, including exterior elements and surrounding gardens / land.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;">Survey to be concentrated on building structure, externals and immediate vicinity</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Beneath carpet or floor coverings (except laminate floors)</th>
            <th style="width: 35%;padding-bottom: 15px;">Management surveys - to be lifted in discrete locations where to do so will not cause damage and where the flooring can be safely replaced<br><br>Refurbishment / demolition surveys – full inspection to all relevant areas.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Check in unobtrusive locations and replace on completion</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Beneath or behind fixtures and fittings – e.g. bath panels / kitchen units</th>
            <th style="width: 35%;padding-bottom: 15px;">Management surveys – inspection only where the item is screw fixed or can be easily removed and replaced without causing damage.<br><br>Refurbishment / demolition surveys - removal of cladding panels / units or inspection hole created with appropriate tools.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Remove and replace liftable panels (within WC’s etc.)</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Within boxings or risers (not constructed of a suspect ACM).</th>
            <th style="width: 35%;padding-bottom: 15px;">Management surveys – inspection only where the boxing / access panel is screw fixed or can be easily removed and replaced without causing damage.<br><br>Management surveys – inspection only where the boxing / access panel is screw fixed or can be easily removed and replaced without causing damage.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Obtain keys from the estates department.  Make reasonable attempt to unscrew fixed panels and replace.</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Roof voids</th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection from the access hatch if no flooring or crawl boards available</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">All roof spaces to be accessed  (Cat ladder within electrical cupboard)</th>
        </tr>
        </tbody>
        <thead>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">Refurbishment surveys / demolition surveys (elements to be included in areas affected by the scope of the proposed works)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;"><stong>Element</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Usual inspection method (if applicable)</stong></th>
            <th style="width: 10%;padding-bottom: 15px;"><stong>Included?</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Comments</stong></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Solid wall cavities</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of vent covers/window sills. May involve removal of individual bricks in some cases.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;">N/A</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Partition wall cavities</th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection holes created with appropriate tools and of suitable size to allow for adequate inspection.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Where existing openings allow.</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Above fied ceilings / ceiling voids</th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection holes created with appropriate tools. Inspection may also be possible via the removal of light fittings or removal of flooring within areas above.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Remove/replace ceiling tiles as necessary.</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Floor voids</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of floorboards or use of existing access points.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">As can be safely accessed without specialist equipment and covers and left safe on completion</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Beneath window sills</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of window sills or inspection holes created with appropriate tools.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Within fire doors</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of door furniture or inspection hole created with appropriate tools.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;">Check for obvious fillets and panels only</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Beneath fixed flooring materials (e.g. laminate / ceramic tiles)</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of flooring material.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Check under kitchen cupboards etc for locations, which may not be laminated</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Behind fixed wall cladding / coverings / tiles</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of cladding / coverings / tiles.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Behind skirting and door frames</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of skirting and door frames.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Beneath non-asbestos insulation to pipework / calorifiers etc.</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of non-asbestos insulation materials.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;">Sample to full depth of material</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Behind non asbestos external soffits / fascias</th>
            <th style="width: 35%;padding-bottom: 15px;">Removal of soffit / fascia. Inspection hole created with appropriate tools. Suitable inspection may be possible form within roof voids</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Sealed off or locked areas where no key is available</th>
            <th style="width: 35%;padding-bottom: 15px;">Access gained using intrusive methods.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Liaise with client/site contact for access</th>
        </tr>
        </tbody>
    </table>

    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-top:20px;margin-bottom: 0;">
        <thead>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">NB The following elements are standard exclusions from the survey unless they are indicated as specifically being included and suitable arrangements have been made.</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;"><stong>Element</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Usual inspection method (if applicable)</stong></th>
            <th style="width: 10%;padding-bottom: 15px;"><stong>Included?</stong></th>
            <th style="width: 35%;padding-bottom: 15px;"><stong>Comments</stong></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Work at height requiring specialist access equipment </th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection via MEWP or scaffold as appropriate.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">3m folding ladders to be provided</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Within electric switchgear, fuse boxes, plant and other associated services</th>
            <th style="width: 35%;padding-bottom: 15px;">Only accessed if a suitably qualified electrician is provided otherwise these items will not be inspected and are specifically excluded from the scope of the survey.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Providing electrical isolation can be provided by client.  In the absence of isolation, comment on age of installation and provide as much detail as possible</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Within operational plant and machinery including boilers / calorifiers etc.</th>
            <th style="width: 35%;padding-bottom: 15px;">Only accessed if a suitably qualified engineer is provided otherwise these items will not be inspected and are specifically excluded from the scope of the survey.</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Providing electrician provided by client</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Access behind / above existing ACMs which would require the use of an asbestos removal contractor</th>
            <th style="width: 35%;padding-bottom: 15px;">Inspection will only be carried out if a licensed asbestos removal is provided otherwise these areas are specifically excluded from the scope of the survey.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Lifts, lift shafts and lift machinery</th>
            <th style="width: 35%;padding-bottom: 15px;">Unless agreed with the client and a lift engineer is provided, lifts and shafts are specifically excluded from the scope of the survey. Lift machinery will be inspected externally where possible</th>
            <th style="width: 10%;padding-bottom: 15px;">Yes</th>
            <th style="width: 35%;padding-bottom: 15px;">Providing lift engineer provided by client</th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Internal elements of safes</th>
            <th style="width: 35%;padding-bottom: 15px;">Where not included and if there are no client specific requirements, all safes will be recorded as a no access item which should be therefore presumed to contain asbestos.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Intrusion through solid ceiling slab, floor slabs or solid walls.</th>
            <th style="width: 35%;padding-bottom: 15px;">Only accessed if suitable specialist support services are provided otherwise these items will not be inspected and are specifically excluded from the scope of the survey.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        <tr>
            <th style="width: 20%;padding-bottom: 15px;">Below external ground level</th>
            <th style="width: 35%;padding-bottom: 15px;">Only accessed if suitable specialist support services are provided otherwise these items will not be inspected and are specifically excluded from the scope of the survey.</th>
            <th style="width: 10%;padding-bottom: 15px;">No</th>
            <th style="width: 35%;padding-bottom: 15px;"></th>
        </tr>
        </tbody>
        <thead>
        <tr>
            <th colspan="4" style="text-align:left;background: #e2e2e2;padding-bottom: 15px;">Additional information</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th colspan="4" style="padding-bottom: 15px;"><strong>Site specific inclusions</strong> - None</th>
        </tr>
        <tr>
            <th colspan="4" style="padding-bottom: 15px;"><strong>Site Specific exclusions</strong> - None</th>
        </tr>
        </tbody>
    </table>
    <?php } ?>
    <h3 style="margin-top:20px;">Objective & Scope</h3>
    <?php
    if ($survey->surveyInfo->objectives_scope) {
        echo $survey->surveyInfo->objectives_scope;
    } else{
        if($survey->survey_type == 3) { ?>
        <p style="margin-top:20px;">Life Environmental Services Ltd received an order of confirmation to undertake a reinspection Survey from GlaxoSmithKline.  This order has been accepted on the basis of the original Quotation and our terms and conditions of business.  This reinspection is subject to copyright and protected by copyright law.</p>
        <p style="margin-top:20px;">The brief for these works was to carry out a re-inspection of the Asbestos Containing Materials (ACMs) within the aforementioned property.  The re-inspection is based upon the previous survey data held on {{ env('APP_DOMAIN') ?? 'GSK' }} electronic Shine Database.</p>
        <p style="margin-top:20px;">This reinspection considered any damage and disturbance to items identified in the above survey.  Every effort has been made to access any areas that were previously not accessible in the original survey.  Where such locations have still not been accessed, asbestos should be presumed to be present within these areas.</p>
        <p style="margin-top:20px;">Each section of this report focuses on one or two aspects; no section should be taken and read as a stand-alone document.  It is imperative that each section is read in conjunction with each other.</p>
        <p style="margin-top:20px;">This reinspection report forms an addendum to the original survey. The original survey report should be referred to for the following items of information:</p>
        <p style="margin-top:20px;">
        <ul>
            <li>Information on the original survey methodology/caveats</li>
            <li>Original laboratory bulk analysis certificate of analysed samples</li>
            <li>Original drawings</li>
        </ul>
        </p>
        <p style="margin-top:20px;">It should be noted that this report is not intended as a Scope of Works for asbestos removal and that a detailed technical document could be provided upon request.</p>

        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
               style="margin-top:20px;margin-bottom: 0;">
            <tbody>
            <thead>
            <tr>
                <th colspan="4" style="width:100%;padding-bottom: 15px;"><strong>Scope of Works:</strong>
                </th>
            </tr>
            </thead>
            <tr>
                <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Purpose of Survey</strong></th>
                <th colspan="3" style="padding-bottom: 15px;"><strong>Develop their Management Policy / Plan
                        or Acquisition / Sale</strong></th>
            </tr>
            <tr>
                <th style="width: 30%;background: #e2e2e2;padding-bottom: 15px;">Property Type</th>
                <th style="width: 20%;padding-bottom: 15px;">Commercial</th>
                <th style="width: 20%;background: #e2e2e2;padding-bottom: 15px;">Property Status</th>
                <th style="width: 20%;padding-bottom: 15px;">Operational</th>
            </tr>
            <tr style="font-weight: bold;">
                <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Survey Type Required
                        ?</strong></th>
                <th colspan="3" style="padding-bottom: 15px;"><strong>Reinspection Survey</strong></th>
            </tr>
            <tr>
                <th style="background: #e2e2e2;">
                    <strong style="font-weight: bold;">Scope / Extent of Survey </strong>
                </th>
                <th colspan="3">
                    Re-Inspection of existing asbestos register.
                    <p style="margin-top:20px;">The surveyor is to verify the following using the Shine App;</p>
                    -	Room Location Ref & Description (As per {{ env('APP_DOMAIN') ?? 'GSK' }} allocation)<br>
                    -	Room Location Void Investigations<br>
                    -	Room Location Construction Details<br>
                    -	Confirmed or Cross Referenced ACM items<br>
                    -	Presumed ACM items.<br>

                    <p style="margin-top:20px;">Attempt to gain access to any areas previously 'No accessed' if safe to do so. </p>
                    <p style="margin-top:20px;">Samples of any suspect items previously not listed are to be taken.</p>
                </th>
            </tr>
            </tbody>
        </table>

        <?php }else{
        if ($survey->client_id == 5) { ?>
        <p style="margin-top:20px;"><strong>Scope of Works:</strong></p>
        <?php if ($survey->client_id == 5) { ?>
        <p style="margin-top:20px;">The purpose of the survey was to carry out a management survey to the
            site listed on the front of this report. All areas to be included unless specified within the
            report.</p><br>

        <p style="margin-top:20px;">Asbestos inspections and surveys should be carried out following a
            detailed review, site scoping process and consideration of the guidance document HSG264
            ”Asbestos the survey guide”. This is available as a PDF download from the HSE books website</p>
        <br>

        <p style="margin-top:20px;">This document further clarifies specific considerations with respect to
            agreeing the scope of works between the Client or duty holder and the asbestos inspection body.
            An asbestos survey is only part of the process through which to effectively manage the risks
            from asbestos containing materials (ACMs), but the survey report itself is not an asbestos
            management plan. A summary of the actions that may need to be taken can be found in the Survey
            Appendices section of this report, referenced from HSG227 “A comprehensive guide to Managing
            ASBESTOS in premises”.</p><br>

        <?php } ?>
        <?php if ($survey->property->reference) { ?>
        <p>Asbestos management survey to include all rooms, spaces and external areas applicable to the
            dwelling.</p>
        <ul type="disc" style="margin-top:20px;">
            <li>Floors are to be accessed beneath floor covering unless there is fixed laminate or tiles.
            </li>
            <li>Voids are to be accessed where a hatch is present</li>
            <li>Lofts are to be accessed where a hatch is present</li>
        </ul>
        <?php } else { ?>
        <p>Asbestos management survey to whole site, to include all buildings, spaces and external areas.
            All Blocks are to be included.</p>
        <?php } ?>


        <?php } elseif ($survey->client_id == 3) {
        if ($survey->property->zone_id == 9) { ?>
        <p style="margin-top:20px;">Life Environmental Services Ltd received an order of confirmation to
            undertake a Management Survey in accordance with the Scope of Services document from
            GlaxoSmithKline for a full management survey of the Weybridge site. This order has been accepted
            on the basis of the original Quotation and our terms and conditions of business. </p>
        <p style="margin-top:20px;">All subsequent information provided by the client or ascertained
            otherwise was assessed during planning stage of the project and a suitable Plan of Work
            produced. Where information was provided regarding the presence of known or presumed asbestos
            materials then this has been validated during the course of the survey, and recorded within this
            report. This survey was carried out in accordance with documented in house procedures and HSE
            Guidance document HSG 264. </p>
        <p style="margin-top:20px;"><strong>Scope of Works:</strong></p>
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
               style="margin-top:20px;margin-bottom: 0;">
            <tbody>
            <thead>
            <tr>
                <th colspan="4" style="width:100%;padding-bottom: 15px;"><strong>Scope of Works:</strong>
                </th>
            </tr>
            </thead>
            <tr>
                <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Purpose of Survey</strong></th>
                <th colspan="3" style="padding-bottom: 15px;"><strong>Develop their Management Policy / Plan
                        or Acquisition / Sale</strong></th>
            </tr>
            <tr>
                <th style="width: 30%;background: #e2e2e2;padding-bottom: 15px;">Property Type</th>
                <th style="width: 20%;padding-bottom: 15px;">Commercial</th>
                <th style="width: 20%;background: #e2e2e2;padding-bottom: 15px;">Property Status</th>
                <th style="width: 20%;padding-bottom: 15px;">Operational</th>
            </tr>
            <tr style="font-weight: bold;">
                <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Survey Type Required
                        ?</strong></th>
                <th colspan="3" style="padding-bottom: 15px;"><strong>Management</strong></th>
            </tr>
            <tr>
                <th style="background: #e2e2e2;">
                    <strong style="font-weight: bold;">Scope / Extent of Survey </strong>
                    <p style="margin-top:20px;padding-bottom: 15px;">(including any areas of the building /
                        site to be excluded)</p>
                </th>
                <th colspan="3">
                    <strong style="font-weight: bold;">Asbestos management survey including priority
                        assessments to all buildings at the Weybridge {{ env('APP_DOMAIN') ?? 'GSK' }} site.</strong>
                    <p style="margin-top:20px;">Access must be made within ducts, trenches, risers and voids
                        etc. that have access panels or hatches and which are not classified as Confined
                        Spaces. This includes external service trenches where purpose made access covers are
                        available (Survey Team to liaise with site host prior to any access into ducts or
                        trenches).</p>
                    <p style="margin-top:20px;">Survey team need to establish the substrate and potential
                        presence of any suspect items below fixed floorings (carpets/carpet tiles & vinyl’s)
                        in discreet locations. And where it can be lifted and replaced in a manner where no
                        damage is caused or trip hazard is left.</p>
                    <p style="margin-top:20px;">If the survey team suspect the presence of asbestos
                        containing material within live electrical services then they must contact the site
                        host and CBRE to isolate and provide access for inspection. If isolation is not
                        possible due to critical services being affected</p>
                    <p style="margin-top:20px;">The survey team need to inform site host when they require
                        access into lift shaft and inspect lift services. CBRE will isolate the system and
                        provide safe access.</p>
                    <p style="margin-top:20px;padding-bottom: 15px;">Where possible the survey team must
                        record the findings and extents of suspected gaskets in relation to individual pipe
                        runs.</p>
                </th>
            </tr>
            </tbody>
        </table>
        <?php } elseif ($survey->property->zone_id == 2) { ?>
        <p style="margin-top:20px;">Life Environmental Services Ltd received an order of confirmation to
            undertake a Management Survey in accordance with the Scope of Services document from
            GlaxoSmithKline for the Stevenage Research and Development site. This order has been accepted on
            the basis of the original Quotation and our terms and conditions of business.</p>
        <p style="margin-top:20px;">All subsequent information provided by the client or ascertained
            otherwise was assessed during planning stage of the project and a suitable Plan of Work
            produced. Where information was provided regarding the presence of known or presumed asbestos
            materials then this has been validated during the course of the survey, and recorded within this
            report. This survey was carried out in accordance with documented in house procedures and HSE
            Guidance document HSG 264.</p>
        <p style="margin-top:20px;"><strong>Scope of Works:</strong></p>
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
               style="margin-top:20px;margin-bottom: 0;">
            <tbody>
            <thead>
            <tr>
                <th colspan="4" style="width:100%;font-weight: bold;padding-bottom: 15px;">Section 2: Scope
                    of Works
                </th>
            </tr>
            </thead>
            <tr>
                <th style="background: #e2e2e2;padding-bottom: 15px;">Purpose of Survey</th>
                <th colspan="3" style="padding-bottom: 15px;">Develop their Management Policy / Plan or
                    Acquisition / Sale
                </th>
            </tr>
            <tr>
                <th style="width: 30%;background: #e2e2e2;padding-bottom: 15px;">Property Type</th>
                <th style="width: 20%;padding-bottom: 15px;">Industrial</th>
                <th style="width: 20%;background: #e2e2e2;padding-bottom: 15px;">Property Status</th>
                <th style="width: 20%;padding-bottom: 15px;">Operational</th>
            </tr>
            <tr style="font-weight: bold;">
                <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Survey Type Required
                        ?</strong></th>
                <th colspan="3" style="padding-bottom: 15px;"><strong>Management</strong></th>
            </tr>
            <tr>
                <th style="background: #e2e2e2;">
                    <strong style="font-weight: bold;">Scope / Extent of Survey </strong>
                    <p style="margin-top:20px;padding-bottom: 15px;">(including any areas of the building /
                        site to be excluded)</p>
                </th>
                <th colspan="3">
                    <strong style="font-weight: bold;">Asbestos management survey including priority
                        assessments in accordance to {{ env('APP_DOMAIN') ?? 'GSK' }} ' Scope of Services for Asbestos Management
                        Surveys'</strong>
                    <p style="margin-top:20px;">Access must be made within ducts, trenches, risers and voids
                        etc that have access panels or hatches. This includes external service trenches
                        where purpose made access covers are available. Survey Team to liaise with site host
                        prior to any access into ducts or trenches.</p>
                    <p style="margin-top:20px;">Survey team need to establish the substrate and potential
                        presence of any suspect items below fixed floorings (carpets/carpet tiles/vinyls) in
                        discreet locations. And where it can be lifted and replaced in a manner where no
                        damage is caused or trip hazard is left.</p>
                    <p style="margin-top:20px;">If the survey team suspect the presence of asbestos
                        containing material within live electrical services then they must contact the site
                        host to isolate and provide access for inspection.</p>
                    <p style="margin-top:20px;padding-bottom: 15px;">Where possible the survey team must
                        record the findings and extents of suspected gaskets in relation to individual pipe
                        runs.</p>
                </th>
            </tr>
            </tbody>
        </table>
        <?php } elseif ($survey->property->zone_id == 6) { ?>
        <p style="margin-top:20px;">Life Environmental Services Ltd received an order of confirmation to
            undertake a Management Survey in accordance with the Scope of Services document from
            GlaxoSmithKline for the Maidenhead site. This order has been accepted on the basis of the
            original Quotation and our terms and conditions of business.</p>
        <p style="margin-top:20px;">All subsequent information provided by the client or ascertained
            otherwise was assessed during planning stage of the project and a suitable Plan of Work
            produced. Where information was provided regarding the presence of known or presumed asbestos
            materials then this has been validated during the course of the survey, and recorded within this
            report. This survey was carried out in accordance with documented in house procedures and HSE
            Guidance document HSG 264.</p>
        <p style="margin-top:20px;"><strong>Scope of Works:</strong></p>
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
               style="margin-top:20px;margin-bottom: 0;">
            <tbody>
            <thead>
            <tr>
                <th colspan="4" style="width:100%;font-weight: bold;padding-bottom: 15px;">Section 2: Scope
                    of Works
                </th>
            </tr>
            </thead>
            <tr>
                <th style="background: #e2e2e2;padding-bottom: 15px;">Purpose of Survey</th>
                <th colspan="3" style="padding-bottom: 15px;">Develop their Management Policy / Plan or
                    Acquisition / Sale
                </th>
            </tr>
            <tr>
                <th style="width: 30%;background: #e2e2e2;padding-bottom: 15px;">Property Type</th>
                <th style="width: 20%;padding-bottom: 15px;">Industrial</th>
                <th style="width: 20%;background: #e2e2e2;padding-bottom: 15px;">Property Status</th>
                <th style="width: 20%;padding-bottom: 15px;">Operational</th>
            </tr>
            <tr style="font-weight: bold;">
                <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Survey Type Required?</strong>
                </th>
                <th colspan="3" style="padding-bottom: 15px;"><strong>Management</strong></th>
            </tr>
            <tr>
                <th style="background: #e2e2e2;">
                    <strong style="font-weight: bold;">Scope / Extent of Survey </strong>
                    <p style="margin-top:20px;padding-bottom: 15px;">(including any areas of the building /
                        site to be excluded)</p>
                </th>
                <th colspan="3">
                    <strong style="font-weight: bold;">Asbestos management survey including priority
                        assessments in accordance to {{ env('APP_DOMAIN') ?? 'GSK' }} ' Scope of Services for Asbestos Management
                        Surveys'</strong>
                    <p style="margin-top:20px;">Access must be made within ducts, trenches, risers and voids
                        etc. that have access panels or hatches. This includes external service trenches
                        where purpose made access covers are available. Survey Team to liaise with site host
                        prior to any access into ducts or trenches.</p>
                    <p style="margin-top:20px;">Survey team need to establish the substrate and potential
                        presence of any suspect items below fixed floorings (carpets/carpet tiles/vinyls) in
                        discreet locations. And where it can be lifted and replaced in a manner where no
                        damage is caused or trip hazard is left.</p>
                    <p style="margin-top:20px;">If the survey team suspect the presence of asbestos
                        containing material within live electrical services then they must contact the site
                        host to isolate and provide access for inspection.</p>
                    <p style="margin-top:20px;padding-bottom: 15px;">Where possible the survey team must
                        record the findings and extents of suspected gaskets in relation to individual pipe
                        runs.</p>
                </th>
            </tr>
            </tbody>
        </table>
        <?php } elseif ($survey->property->zone_id == 4) {
        if ($survey->property_id == 98) { ?>
        <p style="margin-top:20px;">Life Environmental Services Ltd received an order of confirmation to
            undertake a Management Survey in accordance with the Scope of Services document from
            GlaxoSmithKline for the 34 Berkeley Square site. This order has been accepted on the basis
            of the original Quotation and our terms and conditions of business.</p>
        <p style="margin-top:20px;">All subsequent information provided by the client or ascertained
            otherwise was assessed during planning stage of the project and a suitable Plan of Work
            produced. Where information was provided regarding the presence of known or presumed
            asbestos materials then this has been validated during the course of the survey, and
            recorded within this report. This survey was carried out in accordance with documented in
            house procedures and HSE Guidance document HSG 264.</p>
        <p style="margin-top:20px;"><strong>Scope of Works:</strong></p>
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX
               style="margin-top:20px;margin-bottom: 0;">
            <tbody>
            <thead>
            <tr>
                <th colspan="4" style="width:100%;font-weight: bold;padding-bottom: 15px;">Section 2:
                    Scope of Works
                </th>
            </tr>
            </thead>
            <tr>
                <th style="background: #e2e2e2;padding-bottom: 15px;">Purpose of Survey</th>
                <th colspan="3" style="padding-bottom: 15px;">Develop their Management Policy / Plan or
                    Acquisition / Sale
                </th>
            </tr>
            <tr>
                <th style="width: 30%;background: #e2e2e2;padding-bottom: 15px;">Property Type</th>
                <th style="width: 20%;padding-bottom: 15px;">Industrial</th>
                <th style="width: 20%;background: #e2e2e2;padding-bottom: 15px;">Property Status</th>
                <th style="width: 20%;padding-bottom: 15px;">Operational</th>
            </tr>
            <tr style="font-weight: bold;">
                <th style="background: #e2e2e2;padding-bottom: 15px;"><strong>Survey Type
                        Required?</strong></th>
                <th colspan="3" style="padding-bottom: 15px;"><strong>Management</strong></th>
            </tr>
            <tr>
                <th style="background: #e2e2e2;">
                    <strong style="font-weight: bold;">Scope / Extent of Survey </strong>
                    <p style="margin-top:20px;padding-bottom: 15px;">(including any areas of the
                        building / site to be excluded)</p>
                </th>
                <th colspan="3">
                    <strong style="font-weight: bold;">Asbestos management survey including priority
                        assessments in accordance to {{ env('APP_DOMAIN') ?? 'GSK' }} ' Scope of Services for Asbestos Management
                        Surveys'</strong>
                    <p style="margin-top:20px;">Access must be made within ducts, trenches, risers and
                        voids etc. that have access panels or hatches.</p>
                    <p style="margin-top:20px;">Survey team need to establish the substrate and
                        potential presence of any suspect items below fixed floorings (carpets/carpet
                        tiles/vinyls) in discreet locations. And where it can be lifted and replaced in
                        a manner where no damage is caused or trip hazard is left.</p>
                    <p style="margin-top:20px;">If the survey team suspect the presence of asbestos
                        containing material within live electrical services then they must contact the
                        site host to isolate and provide access for inspection.</p>
                    <p style="margin-top:20px;padding-bottom: 15px;">Where possible the survey team must
                        record the findings and extents of suspected gaskets in relation to individual
                        pipe runs.</p>
                </th>
            </tr>
            </tbody>
        </table>
        <?php }
        }
        }
        }
    } ?>
@endif

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
                                        @if(isset($item->ItemNoAccessValue->ItemNoAccess->other) and ($item->ItemNoAccessValue->ItemNoAccess->other == -1))
                                            {{  \Str::replaceFirst('Other', '',($item->ItemNoAccessValue->ItemNoAccess->description ?? '') . ' ' . ($item->ItemNoAccessValue->dropdown_other ?? '')) }}
                                        @else
                                            {{  $item->ItemNoAccessValue->ItemNoAccess->description ?? '' }}
                                        @endif
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
                        @if(isset($item->ItemNoAccessValue->ItemNoAccess->other) and ($item->ItemNoAccessValue->ItemNoAccess->other == -1))
                            {{  \Str::replaceFirst('Other', '',($item->ItemNoAccessValue->ItemNoAccess->description ?? '') . ' ' . ($item->ItemNoAccessValue->dropdown_other ?? '')) }}
                        @else
                            {{  $item->ItemNoAccessValue->ItemNoAccess->description ?? '' }}
                        @endif
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
@if($survey->survey_type != 6)
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
@endif
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

        <?php if ($survey->client_id == 5 || $survey->isGenerateBC != 1) { ?>
        <h3 style="margin-top:30px">Bulk Analysis Results</h3>
        <p>Documents Enclosed.</p>
        <?php } ?>

        <h3 style="margin-top:30px">Site Plans</h3>
        <p>Documents Enclosed.</p>

    </div><!--Survey Appendices PAGE 19-->
</div><!--Container - set width -->
</body>
</html>

<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $assessment->reference ?? ''}}</title>
    <style type="text/css">
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 10pt;
            color: #575756;
        }

        .mt30{
            margin-left: 20px;
            margin-top: 5px!important;
            color: #575756;
            font-size: 10pt;
            line-height: 1.6;
            text-align: left;
            word-spacing: 1.0px;
        }
        .blue-text{color: #092591}
        .page {
            margin-top: 30px;
        }
        .content{
            margin:20px 0px;
        }
        .content-title{
            margin-bottom: 20px;
        }
        /*<tablecover>*/
        .titletable{
            color:black;
            margin: 30px 0px;
        }
        .tableCover table, td, th {
            border: 1px solid black;
        }
        .tableCover table {
            color: black;
            border-collapse: collapse;
            width: 100%;
        }

        .tableCover th {
            background-color: #e6fbbb;
            height: 50px;
        }
        .tableCover tr {
            height: 30px;
        }
        .tableCover td {
            padding-left: 20px;
            padding-right: 20px;
        }
        .textES{
            /*font-size: 15px;*/
            padding-left: 20px;
        }
        /*EndES*/
        /*tableItems*/
        .titletableItems{
            color: black;
            margin: 20px 50px;
        }
        .tableItems table{
            margin-top: 0px;
            width: 98%;
            margin-left:20px;
        }
        .tableItems th {
            text-align: left;
            padding-left: 10px;
            font-size: 14px;
            color: #ffffff;
            height: 10px;
        }
        #red tr th{
            background-color: #ff0000;
        }
        .tableItems tr {
            height: 10px;
        }
        .tableItems td {
            padding-left: 20px;
            padding-right: 20px;
        }
        #tableYellow tr th{
            background-color: #ffc000;
        }
        #tableGray tr th {
            background-color: #92d050;
        }
        .tableBlue th {
            background-color: #0070c0;
        }
        #tableYesNo th {
            text-align: left;
            padding-left: 10px;
            color: black;
            background-color: #e6fbbb;
            height: 50px;
        }
        #tableYesNo td span{
            background-color: #ffff00;
        }
        .colspan{
            height: 120px;
            position: relative;
        }
        #tableYesNo tr th{
            height: 150px;
        }
        .tableAR tr td{
            word-break: break-word;
        }
        .tableAR tr th {
            font-size: 14px;
            text-align: left;
            padding-left: 10px;
            color: black;
            background-color: #e6fbbb;
            height: 50px;
        }
        /*EndtableItems*/

        .titleAppendix{
            color: black;
            font-size: 18px;
            padding-left: 20px;
        }
        .titleAppendix1{
            color: black;
            font-size: 18px;
            padding-left: 45px;
        }
        .textdecoration{
            margin-left: 20px;
            margin-top: 80px;
        }
        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }
        .tableItems tr td{
            word-break: break-word;
        }

        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }
        .page {
            margin-top:80px;
            padding: 10px 0px 10px 0px;
            font-size: 12pt;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
        }
        .paragraph{
            margin-left: 30px;
            margin-top: 50px;
            color: black;
            /*font-size: 12pt;*/
            line-height: 1.8;
            text-align: left;
            word-spacing: 1.5px;
        }
        .text-indent{
            margin-top: 50px;
            margin-bottom: 50px;
            margin-left: 30px;
            word-spacing: 1.5px;
        }
        .paragraphdown{
            margin-top: 50px;
            color: black;
            /*font-size: 12pt;*/
            line-height: 1.8;
            text-align: left;
            word-spacing: 1.5px;
        }
        .text-indentdown{
            margin-top: 10px;
            margin-bottom: 50px;
            text-indent: 50px;
        }
        .space{
            margin-right: 50px;
        }
        .italic{
            margin-top: 50px;
            color: black;
            font-style: italic;
            margin-left: 60px;
            /*font-size: 10pt;*/
            line-height: 1.8;
            text-align: left;
            word-spacing: 1px;
        }

        table, td, th {
            border: 1px solid black;
        }

        table {
            color: black;
            margin-bottom: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #e6fbbb;
        }
        table tr td{
            font-size: 12px;
            padding-left: 15px;
            padding-right: 15px;
            line-height: 1.5;
            word-spacing: 1px;
        }
        .contentAp{
            margin-left: 30px;
        }

        .tableAp3 tr td {
            text-align: left;
            padding-left: 10px;
        }
        .tableAp3 tr th {
            font-size: 14px;
            text-align: left;
            padding-left: 10px;
        }
        .text-content{
            margin-top: 30px;
            margin-bottom: 30px;
            margin-left: 60px;
        }
        .text-title{
            margin-bottom: 10px;
            margin-left: 30px;
        }
        .contents-section{
            margin-left: 50px;
            margin-bottom: 25px;
        }

        .title{
            padding:0px 30px;
            text-align: center;
            /*border: 3px solid green;*/
            background-color: #e6fbbb;
            line-height: 1.8;
            font-size: 17px;
            margin-bottom: 30px;
        }
        .contents{
            margin: 50px 0px;
        }
        .appendix{
            margin-bottom: 30px;
        }
        .titleAppendixTitle{
            padding-left: 50px;
        }
        h2{
            font-size: 20px;
        }
        h3{
            font-size: 15px;
        }
        th{
            font-size: 12px!important;
            font-weight: normal;
        }
        .tableGray tr th {
            background-color: #dadada!important;
            color: #575756;
        }
        .tableGray tr td {
            color: #575756;
            padding-left: 10px;
        }
        .unset-border td {
            border: 0!important;
        }
        table.unset-border{
            color: #575756;
            border: 0!important;
            margin-left: 5px
        }
        p{
            font-size: 10pt!important;
            text-align: justify!important;
        }
        .alignment-title{
            background-color: #706f6f;
            color: white;
            width: 100%;
            height: 40px;
        }
        .alignment-text{
            padding-top: 13px;
            padding-left: 30px;
            font-size: 16px!important;
        }
        .system th,td{
                     text-align: left;
                     padding-left: 10px;
                 }
    </style>
</head>
<body>
<div style="page-break-before: always;"></div>
@php
    $propertyInfo = json_decode($assessment->assessmentInfo->property_information ?? '', true);
@endphp
<div id="executisve-summary" style="margin-top:30px;">
    <div class="textdecoration alignment-title">
        <p class="alignment-text">1.0 Property Information and Assessment Signatory</p>
    </div>
    <div class="content">
        <div class="mt30">
            <p>  This Health and Safety Assessment report has been technically reviewed and authorised for issue to {{$assessment->property->clients->name ?? ''}} by the following authorised
                members of the {{$assessment->assessor->clients->name ?? ''}} Technical Team. This document holds legal status under the Health & Safety at Work Act (1974) and
                The Management of Health and Safety at Work Regulations (1999), and therefore should be used and made available accordingly.
            </p>
        </div>
    </div>
    <h3  class="mt30">Property Construction</h3>
    <table cellspacing="0" class="unset-border">
{{--        <tr>--}}
{{--            <td width="25%">--}}
{{--                <p>Property Status:</p>--}}
{{--            </td>--}}
{{--            <td width="60%">--}}
{{--                <p>{{ $assessment->property->propertySurvey->property_status_disp ?? '' }}</p>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        <tr style="border-top: unset!important;border-bottom: unset!important;">--}}
{{--            <td>--}}
{{--                <p>Property Occupied:</p>--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <p>{{ $assessment->property->propertySurvey->property_occupied_disp ?? '' }}</p>--}}
{{--            </td>--}}
{{--        </tr>--}}
        <tr>
            <td>
                <p>Primary Use:</p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->use_primary_disp ?? ''}}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Secondary Use: </p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->use_secondary_disp ?? '' }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Construction Age:</p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->construction_age ?? '' }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Construction Materials:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyMaterials($assessment->property)  }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Listed Building: </p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->listed_building_disp ?? '' }}</p>
            </td>
        </tr>
{{--        <tr>--}}
{{--            <td>--}}
{{--                <p>No. of Floors:</p>--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <p>{{ \CommonHelpers::getSurveyPropertyInfoText($assessment->property->propertySurvey->size_floors ?? null, $assessment->property->propertySurvey->size_floors_other ?? null) }}</p>--}}
{{--            </td>--}}
{{--        </tr>--}}
        <tr>
            <td>
                <p>No. of Staircases: </p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getSurveyPropertyInfoText($assessment->property->propertySurvey->size_staircases ?? null, $assessment->property->propertySurvey->size_staircases_other ?? null) }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>No. of Lift:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getSurveyPropertyInfoText($assessment->property->propertySurvey->size_lifts ?? null, $assessment->property->propertySurvey->size_lifts_other ?? null) }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Electric Meter:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyDropdownData($assessment->property->propertySurvey->electrical_meter ?? null) }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Gas Meter:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyDropdownData($assessment->property->propertySurvey->gas_meter ?? null) }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Loft Void:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyDropdownData($assessment->property->propertySurvey->loft_void ?? null) }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>No of Bedrooms:</p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->size_bedrooms ?? null }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Net Area per Floor:</p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->size_net_area ?? null }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Gross Area:</p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->size_gross_area ?? null }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Parking Arrangements:</p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->parking_arrangements_disp ?? ''}}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Nearest Hospital:</p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->nearest_hospital ?? '' }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Restrictions & Limitations:</p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->restrictions_limitations ?? '' }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Unusual Features: </p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->unusual_features ?? ''}}</p>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:top;">
                <p>Comments:</p>
            </td>
            <td>
                <p> {!! nl2br($assessment->property->propertySurvey->size_comments ?? '') ?? null !!}</p>
            </td>
        </tr>
    </table>

    <h3 class="mt30">Vulnerable Occupant Group Types</h3>
        @if(isset($propertyInfo['vulnerable_occupant_type']))
        <div class="mt30">
            <p>The property is occupied by the following high risk groups of individuals which
                are summerised below:</p>
        </div>
            <div class="tableItems tableGray" style="margin-top: 10px">
                <table>
                    <thead>
                    <tr>
                        <th width="45%">High Risk Groups</th>
                        <th width="10%">Average</th>
                        <th width="10%">Maximum</th>
                        <th width="20%">Time of Assessment</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                {{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'vulnerable_occupant_type') }}
                            </td>
                            <td>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'avg_vulnerable_occupants') }}</td>
                            <td>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'max_vulnerable_occupants') }}</td>
                            <td>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'last_vulnerable_occupants') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <p style="padding-left: 20px ">No high risk groups of individuals occupy this property.</p>
        @endif

    <div style="clear: left;">
        <h3 class="mt30" style="margin-top: 30px;">Assessment Signatory</h3>
        <div  class="tableItems tableGray">
            <table style="margin-top:0px">
                <thead>
                <tr>
                    <th colspan="3">Assessment Authorisation</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="text-align: center!important;" width="33%">
                        <img style="max-width: 200px; margin-top: 7px" src="{{ \CommonHelpers::getFile($assessment->lead_by ?? 0, USER_SIGNATURE, $is_pdf) }}">
                        <p style="text-align: center!important;">Project Manager</p>
                        <p style="margin-top: 2px;text-align: center!important;">{{ \CommonHelpers::getUserFullname($assessment->lead_by) ?? '' }}</p>
                        <p style="margin-top: 2px;text-align: center!important;">{{ now()->format('d/m/Y') }} </p>
                        <p style="height: 5px;">&nbsp;</p>
                    </td>
                    <td style="text-align: center!important;" width="33%">
                        <img style="max-width: 200px;margin-top: 5px" src="{{ \CommonHelpers::getFile($assessment->assessor_id, USER_SIGNATURE, $is_pdf) }}">
                        <p style="text-align: center!important;">{{ucfirst($assessment->assess_type)}} Risk Assessor</p>
                        <p style="margin-top: 2px;text-align: center!important;">{{ \CommonHelpers::getUserFullname($assessment->assessor_id) ?? '' }}</p>
                        <p style="margin-top: 2px;text-align: center!important;">{{ now()->format('d/m/Y') }}</p>
                        <p style="height: 5px;">&nbsp;</p>
                    </td>
                    <td style="text-align: center!important;" width="33%">
                        <img style="max-width: 200px;margin-top: 5px" src="{{ \ComplianceHelpers::getFileImage($assessment->quality_checker, USER_SIGNATURE, $is_pdf) }}">
                        <p style="text-align: center!important;">Quality Checked By</p>
                        <p style="margin-top: 2px;text-align: center!important;">{{ \CommonHelpers::getUserFullname($assessment->quality_checker ?? '') }}</p>
                        <p style="margin-top: 2px;text-align: center!important;">{{ now()->format('d/m/Y') }}</p>
                        <p style="height: 5px;">&nbsp;</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>

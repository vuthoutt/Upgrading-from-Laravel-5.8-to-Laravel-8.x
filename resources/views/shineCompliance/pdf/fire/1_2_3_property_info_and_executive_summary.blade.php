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
            <p>  This Fire Risk Assessment report has been technically reviewed and authorised for issue to {{$assessment->property->clients->name ?? ''}} by the following authorised
                members of the {{$assessment->assessor->clients->name ?? ''}} Technical Team. This document holds legal status under the Health & Safety at Work Act (1974) and
                The Management of Health and Safety at Work Regulations (1999), and therefore should be used and made available accordingly.
            </p>
        </div>
    </div>
    <h3  class="mt30">Property Construction</h3>
    <table cellspacing="0" class="unset-border">
        <tr>
            <td width="25%">
                <p>Property Status:</p>
            </td>
            <td width="60%">
                <p>{{ CommonHelpers::getPropertyInfoField($propertyInfo, 'property_status') ?? '' }}</p>
            </td>
        </tr>
        <tr style="border-top: unset!important;border-bottom: unset!important;">
            <td>
                <p>Property Occupied:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'property_occupied')}}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Primary Use:</p>
            </td>
            <td>
                <p>{{ CommonHelpers::getPropertyInfoField($propertyInfo, 'asset_use_primary') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Secondary Use: </p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'asset_use_secondary') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Construction Age:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'construction_age') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Construction Materials:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'construction_materials')  }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Listed Building: </p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'listed_building') }}</p>
            </td>
        </tr>
{{--        <tr>--}}
{{--            <td>--}}
{{--                <p>No. of Floors:</p>--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'size_floors') }}</p>--}}
{{--            </td>--}}
{{--        </tr>--}}
        <tr>
            <td>
                <p>No. of Staircases: </p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'size_staircases') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>No. of Lift:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'size_lifts') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Electric Meter:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'electrical_meter') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Gas Meter:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'gas_meter') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Loft Void:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'loft_void') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>No of Bedrooms:</p>
            </td>
            <td>
                <p>{{ $assessment->property->propertySurvey->size_bedrooms ?? '' }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Net Area per Floor:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'size_net_area') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Gross Area:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'size_gross_area') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Parking Arrangements:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'parking_arrangements') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Nearest Hospital:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'nearest_hospital') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Restrictions & Limitations:</p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'restrictions_limitations') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Unusual Features: </p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'unusual_features') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Evacuation Strategy: </p>
            </td>
            <td>
                <p>{{ \CommonHelpers::getPropertyInfoField($propertyInfo, 'evacuation_strategy') }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Comments:</p>
            </td>
            <td>
                <p> {!! nl2br($assessment->property->comments ?? '') ?? null !!}</p>
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

<div style="page-break-before: always;"></div>
<div id="asbestos-risk-items" style="margin-top:30px;">
    @if($assessment->assessmentInfo->setting_show_vehicle_parking == VEHICLE_PARKING)
    <div class="textdecoration alignment-title">
        <p class="alignment-text">2.0 Fire Exits, Assembly Points and Vehicle Parking</p>
    </div>
    @else
        <div class="textdecoration alignment-title">
            <p class="alignment-text">2.0 Fire Exits and Assembly Points</p>
        </div>
    @endif
    <div class="content">
        <div class="tableItems tableGray" style="margin-top: 10px">
            <h3 class="mt30 mb30"><strong>Fire Exits</strong></h3>
            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:98%;">
                <thead>
                <tr>
                    <th width="15%">Floor</th>
                    <th width="15%">Room</th>
                    <th width="10%">Type</th>
                    <th width="10%">Accessibility</th>
                    <th width="25%">Comment</th>
                    <th width="25%">Photograph</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($assessment->fireExits) && count($assessment->fireExits))
                    @foreach($assessment->fireExits as $fire_exist)
                        <tr>
                            <td>{{$fire_exist->area->title_presentation ?? 'N/A'}}</td>
                            <td>{{$fire_exist->location->title_presentation ?? 'N/A'}}</td>
                            <td>{{$fire_exist->type_disp ?? ''}}</td>
                            <td>{{$fire_exist->accessibility == 1 ? 'Yes' : 'No'}}</td>
                            <td>{{$fire_exist->comment ?? ''}}</td>
                            <td><img style="width: 150px;margin:10px;margin-left: 40px" src="{{ \CommonHelpers::getFile($fire_exist->id ?? 0, FIRE_EXIT_PHOTO, $is_pdf) }}"></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No Information.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <div class="tableItems tableGray" style="margin-top: 10px">
            <h3 class="mt30 mb30"><strong>Assembly Points</strong></h3>
            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:98%;">
                <thead>
                <tr>
                    <th width="15%">Floor</th>
                    <th width="15%">Room</th>
                    <th width="10%">Accessibility</th>
                    <th width="35%">Comment</th>
                    <th width="25%">Photograph</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($assessment->assemblyPoints) && count($assessment->assemblyPoints))
                    @foreach($assessment->assemblyPoints as $assembly_points)
                        <tr>
                            <td>{{$assembly_points->area->title_presentation ?? 'N/A'}}</td>
                            <td>{{$assembly_points->location->title_presentation ?? 'N/A'}}</td>
                            <td>{{$assembly_points->accessibility == 1 ? 'Yes' : 'No'}}</td>
                            <td>{{$assembly_points->comment ?? ''}}</td>
                            <td><img style="width: 150px; margin:10px;margin-left: 40px" src="{{ \CommonHelpers::getFile($assembly_points->id ?? 0, ASSEMBLY_POINT_PHOTO, $is_pdf) }}"></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No Information.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    @if($assessment->assessmentInfo->setting_show_vehicle_parking == VEHICLE_PARKING)
        <div class="tableItems tableGray" style="margin-top: 10px">
            <h3 class="mt30 mb30"><strong>Vehicle Parking</strong></h3>
            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:98%;">
                <thead>
                <tr>
                    <th width="15%">Floor</th>
                    <th width="15%">Room</th>
                    <th width="10%">Accessibility</th>
                    <th width="35%">Comment</th>
                    <th width="25%">Photograph</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($assessment->vehicleParking) && count($assessment->vehicleParking))
                    @foreach($assessment->vehicleParking as $vehicle_parking)
                        <tr>
                            <td>{{$vehicle_parking->area->title_presentation ?? 'N/A'}}</td>
                            <td>{{$vehicle_parking->location->title_presentation ?? 'N/A'}}</td>
                            <td>{{$vehicle_parking->accessibility == 1 ? 'Yes' : 'No'}}</td>
                            <td>{{$vehicle_parking->comment ?? ''}}</td>
                            <td><img style="width: 150px;margin:10px;margin-left: 40px;" src="{{ \CommonHelpers::getFile($vehicle_parking->id ?? 0, VEHICLE_PARKING_PHOTO, $is_pdf) }}"></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No Information.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    @endif
    </div>
</div>

<div style="page-break-before: always;"></div>
<div id="asbestos-risk-items" style="margin-top:30px;">
    <div class="textdecoration alignment-title">
        <p class="alignment-text"> 3.0 Executive Summary</p>
    </div>
    <div class="content">
        <h3 class="mt30 mb30"><strong>Executive Summary</strong></h3>
        <div class="mt30 mb30" style="word-break: break-all;">
            {!! optional($assessment->assessmentInfo)->executive_summary !!}
        </div>
{{--        <h3 class="mt30 mb30"><strong>Introduction</strong></h3>--}}
{{--        <p class="mt30 mb30">--}}
{{--            {{$assessment->assessor->clients->name ?? ''}} was instructed by {{$assessment->property->clients->mainUser->full_name ?? ''}} of {{$assessment->property->clients->name ?? ''}}, to provide a Fire Risk Assessment at--}}
{{--            {{ $assessment->property->name ?? ''}} to meet the requirements of The Regulatory Reform (Fire Safety) Order 2005.--}}
{{--        </p>--}}
{{--        <h3 class="mt30 mb30"><strong>Legislation</strong></h3>--}}
{{--        <p class="mt30">--}}
{{--            The Health & Safety at Work Act 1974 (HSWA) and The Regulatory Reform (Fire Safety) Order 2005, imposes a statutory duty on--}}
{{--            employers to provide reasonable fire safety for all personnel who work within the building or have reason to be within the property, for--}}
{{--            example contractors, visitors and members of the public. In order to comply with their legal duties, as detailed in the ACoP, employers--}}
{{--            and those with responsibility for the control premises should:--}}
{{--        </p>--}}
{{--        <ul style="padding-left: 35px;margin: 20px 0px" >--}}
{{--            <li>Identify and assess source of risk</li>--}}
{{--            <li>Carry out a suitable and sufficient risk assessment</li>--}}
{{--            <li>Prepare a scheme for preventing or controlling the risk</li>--}}
{{--            <li>Implement, manage and monitor precautions</li>--}}
{{--            <li>Keep records of precautions</li>--}}
{{--            <li>Appoint a person to be managerially responsible</li>--}}
{{--        </ul>--}}
{{--        <h3 class="mt30 mb30"><strong>Scope of Assessment</strong></h3>--}}
{{--        <p class="mt30 mb30">--}}
{{--            All the accessible areas of the site were assessed, hazards that have been identified, photographed, risk scored and numbered so future--}}
{{--            control measures can be implemented, An asset register and basic schematic are included within the report--}}
{{--        </p>--}}
{{--        <h3 class="mt30 mb30"><strong>Exclusions</strong></h3>--}}
{{--        <p class="mt30 mb30">--}}
{{--            The assessment has been undertaken on a non-destructive and non-intrusive basis, and is limited to those items in plain sight that may--}}
{{--            be safely accessed. It is neither practical nor possible to assess all materials used in the property. It should therefore be noted that not--}}
{{--            all material present can or have been assessed for their suitability of use. {{$assessment->assessor->clients->name ?? ''}} cannot be accountable for any omissions to--}}
{{--            this report resulting from the information, data, systems or plant not made readily and reasonably accessible by {{$assessment->property->clients->name ?? ''}}.--}}
{{--        </p>--}}
{{--        <p class="mt30 mb30">--}}
{{--            The scope of work excludes a formal written scheme for preventing risk. It also excludes undertaking an evaluation (practical or financial)--}}
{{--            of the feasibility of the removal or replacement of any plant or equipment identified as presenting a reasonably foreseeable risk of causing--}}
{{--            a fire. Some or all of these actions may be necessary upon completion of this Risk Assessment. Please note that this Risk Assessment--}}
{{--            only addresses one of many requirements of the RRO and is therefore not alone sufficient to ensure complete compliance with the law.--}}
{{--        </p>--}}
{{--        <h3 class="mt30 mb30"><strong>Limitations on Access</strong></h3>--}}
{{--        <p class="mt30 mb30">--}}
{{--            During the course of the assessment, every effort was made to gain access to all areas within the agreed scope. However, where it has--}}
{{--            not been possible to gain suitable and sufficient access due to specific circumstances, these areas were escalated at the time of the site--}}
{{--            visit. Where assess could not be made a no-access pro forma has been completed and signed by the site representative.--}}
{{--        </p>--}}
{{--        <h3 class="mt30 mb30"><strong>Assessor Competence</strong></h3>--}}
{{--        <p class="mt30 mb30">--}}
{{--            The {{$assessment->assessor->clients->name ?? ''}} Fire Risk Assessor meets the training and experience of a competent person as defined in the Approved Code--}}
{{--            of Practice and Guidance L138. The Fire Risk Assessor participate in the {{$assessment->assessor->clients->name ?? ''}} Audit Scheme and maintain satisfactory--}}
{{--            performance.--}}
{{--        </p>--}}
{{--        <h3 class="mt30 mb30"><strong>Advice</strong></h3>--}}
{{--        <p class="mt30 mb30">--}}
{{--            {{$assessment->assessor->clients->name ?? ''}} can provide advice on this matter should it be necessary. In the first instance please contact {{$assessment->assessor->clients->name ?? ''}} on--}}
{{--            {{$assessment->assessor->clients->clientAddress->telephone ?? ''}} or by email at {{$assessment->assessor->clients->email ?? ''}} and we will be pleased to help.--}}
{{--        </p>--}}
{{--        <h3 class="mt30 mb30"><strong>Evacuation Strategy: {{ CommonHelpers::getPropertyInfoField(json_decode($assessment->assessmentInfo->property_information ?? '', true), 'evacuation_strategy') }}</strong></h3>--}}
    </div>
</div>
</body>

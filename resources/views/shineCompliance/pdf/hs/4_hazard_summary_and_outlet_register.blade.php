<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
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
        .mt30a{
            margin-top: 5px!important;
            color: #575756;
            font-size: 10pt;
            line-height: 1.6;
            text-align: left;
            word-spacing: 1.0px;
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
        .red tr th{
            background-color: #ff0000;
        }
        .brown tr th{
            background-color: #ad8049;
        }
        .orange tr th{
            background-color: #f7a416;
        }
        .yellow tr th{
            background-color: #f3e600;
            color: black;
        }
        .tableItems tr {
            height: 10px;
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

        .textdecoration{
            margin-left: 20px;
            margin-top: 80px;
        }
        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }
        .tableItems tr td{
            word-break: break-word;
            color: #575756;
            padding-left: 10px;
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

        .tableAp3 tr td {
            text-align: left;
            padding-left: 10px;
        }
        .tableAp3 tr th {
            font-size: 14px;
            text-align: left;
            padding-left: 10px;
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
        .unset-border td {
            border: 0!important;
        }
        table.unset-border{
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

        .overall td{
            border: 5px solid;
        }
        .title-break{
            border:1px solid white!important;
            border-bottom: 1px solid black!important;
            padding-bottom: 5px;
        }
        .th-break{
            border: 0!important;
            background-color: white!important;
            color: black!important;
            padding-left: 0px!important;
            margin-left: 0px!important;
        }
        .bg_commnet{
            background-color: #dadada!important;
        }
    </style>
</head>
<body>
<div style="page-break-before: always;"></div>
<div id="executisve-summary" style="margin-top:30px;">
        <div class="textdecoration alignment-title">
            <p class="alignment-text">{{ isset($new_title['hazard']) ? $new_title['hazard'] : '' }}.0 Summary of Hazards with Recommendations</p>
        </div>
    <div class="content">
        <div class="mt30">
            <p>
                Following this Risk Assessment by {{$assessment->assessor->full_name ?? ''}},
                @if($count_hazard == 0)
                    no hazards have been identified at this property.
                @else
                    a total of {{ $count_hazard }} hazards have been identified within the property that have been deemed to be of sufficient significance to be recorded. Associated action/recommendations have been included within the assessment. These should be resolved in line with your existing policy and procedures.
                @endif
            </p>
        </div>

        <!-- red table -->
        <div class="tableItems red" >
            <table >
                <thead>
                <tr class="title-break">
                    <th colspan="9" class="th-break">
                        <h3 class="mt30a">
                            <strong>Very High Risk - Immediate Action Required</strong>
                        </h3>
                    </th>
                </tr>
                <tr>
                    <th >Hazard Name</th>
                    <th >Hazard Type</th>
                    <th >Floor</th>
                    <th >Room</th>
                    <th >Equipment Name</th>
                    <th >Equipment Type</th>
                    <th >Recommendations</th>
                    <th >Photography</th>
                </tr>
                </thead>
                <tbody>
                @if(count($vhigh_risk_hazards))
                    @foreach($vhigh_risk_hazards as $hazard)
                        <tr>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->name ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 120px">
                                    {{ $hazard->hazardType->description ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->area->title_presentation ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 130px">
                                    {{ $hazard->location->title_presentation ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 120px">
                                    {{ $hazard->nonconformity->equipment->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->nonconformity->equipment->equipmentType->description ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->action_recommendations ?? '' }}
                                </div>
                            </td>

                            @if(!$hazard->photo_override)
                                <td>
                                    <div width="170px">
                                        <img src="{{ (ComplianceHelpers::getFileImage($hazard->id ?? 0, HAZARD_PHOTO, $is_pdf)) }}" height="150px" width="150px">
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div>
                                        <img src="{{ (ComplianceHelpers::getFileImage(0, HAZARD_PHOTO, $is_pdf)) }}" height="150px" width="150px">
                                    </div>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td class="bg_commnet">Comments</td>
                            <td colspan="8"><div>{{ $hazard->comment ?? '' }}</div></td>
                        </tr>
                    @endforeach
                @else
                        <tr>
                            <td colspan="9">No Information.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- red table -->
        <div class="tableItems red" >
            <table >
                <thead>
                <tr class="title-break">
                    <th colspan="9" class="th-break">
                        <h3 class="mt30a">
                            <strong>High Risk - Urgent - Action Required within 1 Month Assessment</strong>
                        </h3>
                    </th>
                </tr>
                <tr>
                    <th >Hazard Name</th>
                    <th >Hazard Type</th>
                    <th >Floor</th>
                    <th >Room</th>
                    <th >Equipment Name</th>
                    <th >Equipment Type</th>
                    <th >Recommendations</th>
                    <th >Photography</th>
                </tr>
                </thead>
                <tbody>
                @if(count($high_risk_hazards))
                    @foreach($high_risk_hazards as $hazard)
                        <tr>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->name ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 120px">
                                    {{ $hazard->hazardType->description ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->area->title_presentation ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 130px">
                                    {{ $hazard->location->title_presentation ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 120px">
                                    {{ $hazard->nonconformity->equipment->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->nonconformity->equipment->equipmentType->description ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->action_recommendations ?? '' }}
                                </div>
                            </td>

                            @if(!$hazard->photo_override)
                                <td>
                                    <div width="170px">
                                        <img src="{{ (ComplianceHelpers::getFileImage($hazard->id ?? 0, HAZARD_PHOTO, $is_pdf)) }}" height="150px" width="150px">
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div>
                                        <img src="{{ (ComplianceHelpers::getFileImage(0, HAZARD_PHOTO, $is_pdf)) }}" height="150px" width="150px">
                                    </div>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td class="bg_commnet">Comments</td>
                            <td colspan="8"><div>{{ $hazard->comment ?? '' }}</div></td>
                        </tr>
                    @endforeach
                @else
                        <tr>
                            <td colspan="9">No Information.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- red table -->
        <div class="tableItems brown" >
            <table >
                <thead>
                <tr class="title-break">
                    <th colspan="9" class="th-break">
                        <h3 class="mt30a">
                            <strong>Medium Risk - Action Required within 3 Months of Assessment</strong>
                        </h3>
                    </th>
                </tr>
                <tr>
                    <th >Hazard Name</th>
                    <th >Hazard Type</th>
                    <th >Floor</th>
                    <th >Room</th>
                    <th >Equipment Name</th>
                    <th >Equipment Type</th>
                    <th >Recommendations</th>
                    <th >Photography</th>
                </tr>
                </thead>
                <tbody>
                @if(count($medium_risk_hazards))
                    @foreach($medium_risk_hazards as $hazard)
                        <tr>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->name ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 120px">
                                    {{ $hazard->hazardType->description ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->area->title_presentation ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 130px">
                                    {{ $hazard->location->title_presentation ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 120px">
                                    {{ $hazard->nonconformity->equipment->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->nonconformity->equipment->equipmentType->description ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->action_recommendations ?? '' }}
                                </div>
                            </td>

                            @if(!$hazard->photo_override)
                                <td>
                                    <div width="170px">
                                        <img src="{{ (ComplianceHelpers::getFileImage($hazard->id ?? 0, HAZARD_PHOTO, $is_pdf)) }}" height="150px" width="150px">
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div>
                                        <img src="{{ (ComplianceHelpers::getFileImage(0, HAZARD_PHOTO, $is_pdf)) }}" height="150px" width="150px">
                                    </div>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td class="bg_commnet">Comments</td>
                            <td colspan="8"><div>{{ $hazard->comment ?? '' }}</div></td>
                        </tr>
                    @endforeach
                @else
                        <tr>
                            <td colspan="9">No Information.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- red table -->
        <div class="tableItems orange" >
            <table >
                <thead>
                <tr class="title-break">
                    <th colspan="9" class="th-break">
                        <h3 class="mt30a">
                            <strong>Low Risk - Action Required within 6 Months of Assessment</strong>
                        </h3>
                    </th>
                </tr>
                <tr>
                    <th >Hazard Name</th>
                    <th >Hazard Type</th>
                    <th >Floor</th>
                    <th >Room</th>
                    <th >Equipment Name</th>
                    <th >Equipment Type</th>
                    <th >Recommendations</th>
                    <th >Photography</th>
                </tr>
                </thead>
                <tbody>
                @if(count($low_risk_hazards))
                    @foreach($low_risk_hazards as $hazard)
                        <tr>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->name ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 120px">
                                    {{ $hazard->hazardType->description ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->area->title_presentation ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 130px">
                                    {{ $hazard->location->title_presentation ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 120px">
                                    {{ $hazard->nonconformity->equipment->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->nonconformity->equipment->equipmentType->description ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->action_recommendations ?? '' }}
                                </div>
                            </td>

                            @if(!$hazard->photo_override)
                                <td>
                                    <div width="170px">
                                        <img src="{{ (ComplianceHelpers::getFileImage($hazard->id ?? 0, HAZARD_PHOTO, $is_pdf)) }}" height="150px" width="150px">
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div>
                                        <img src="{{ (ComplianceHelpers::getFileImage(0, HAZARD_PHOTO, $is_pdf)) }}" height="150px" width="150px">
                                    </div>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td class="bg_commnet">Comments</td>
                            <td  colspan="8"><div>{{ $hazard->comment ?? '' }}</div></td>
                        </tr>
                    @endforeach
                @else
                        <tr>
                            <td colspan="9">No Information.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- red table -->
        <div class="tableItems yellow" >
            <table >
                <thead>
                <tr class="title-break">
                    <th colspan="9" class="th-break">
                        <h3 class="mt30a">
                            <strong>Very Low Risk - Action Required within 12 Months of Assessment</strong>
                        </h3>
                    </th>
                </tr>
                <tr>
                    <th >Hazard Name</th>
                    <th >Hazard Type</th>
                    <th >Floor</th>
                    <th >Room</th>
                    <th >Equipment Name</th>
                    <th >Equipment Type</th>
                    <th >Recommendations</th>
                    <th >Photography</th>
                </tr>
                </thead>
                <tbody>
                @if(count($vlow_risk_hazards))
                    @foreach($vlow_risk_hazards as $hazard)
                        <tr>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->name ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 120px">
                                    {{ $hazard->hazardType->description ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->area->title_presentation ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 130px">
                                    {{ $hazard->location->title_presentation ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 120px">
                                    {{ $hazard->nonconformity->equipment->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->nonconformity->equipment->equipmentType->description ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="width: 125px">
                                    {{ $hazard->action_recommendations ?? '' }}
                                </div>
                            </td>

                            @if(!$hazard->photo_override)
                                <td>
                                    <div width="170px">
                                        <img src="{{ (ComplianceHelpers::getFileImage($hazard->id ?? 0, HAZARD_PHOTO, $is_pdf)) }}" height="150px" width="150px">
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div>
                                        <img src="{{ (ComplianceHelpers::getFileImage(0, HAZARD_PHOTO, $is_pdf)) }}" height="150px" width="150px">
                                    </div>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td class="bg_commnet">Comments</td>
                            <td  colspan="8"><div>{{ $hazard->comment ?? '' }}</div></td>
                        </tr>
                    @endforeach
                @else
                        <tr>
                            <td colspan="9">No Information.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>

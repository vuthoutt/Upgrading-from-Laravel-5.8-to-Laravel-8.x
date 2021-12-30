<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{$assessment->reference ?? ''}}</title>
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
        .tableItems td {
            padding-left: 10px;
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
        .tableGray td {
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
    </style>
</head>
<body>
<div style="page-break-before: always;"></div>
<div id="executisve-summary" style="margin-top:30px;">
    <div class="textdecoration alignment-title">
        <p class="alignment-text">6.0 Non-Assessed or Limited Accessed Rooms and Equipment</p>
    </div>
    <div class="content">
        <div class="mt30">
            <p>
                Whilst every effort has been made to adhere to the scope of the assessment, the following areas and equipment have not been accessed
                or fully accessed for the defined reasons. Hazards must be presumed to be present in these areas or equipment until full access can be
                obtained to definitively determine whether hazards are present or not, through inspection.
            </p>
        </div>
        <p class="mt30 mb30"><strong>Please note: </strong> hazards should be presumed to be present within all locations not accessed until a further assessment can be undertaken.</p>

        <h3 class="mt30 mb30"><strong>Non-Accessed Rooms</strong></h3>

        <div class="tableItems tableGray" >
            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:98%;">
                <thead>
                <tr>
                    <th width="15%">Floor</th>
                    <th width="15%">Room</th>
                    <th width="35%">Reason for No Access</th>
                    <th width="30%">Photograph</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $count_hz = 0;
                @endphp
                @if(isset($assessment->unDecommissionHazardPdf) && count($assessment->unDecommissionHazardPdf) > 0)
                    @foreach($assessment->unDecommissionHazardPdf as $hazard)
                        @if($hazard->type == HAZARD_TYPE_INACCESSIBLE_ROOM )
                            @php
                                $count_hz++;
                            @endphp
                            <tr>
                                <td>{{$hazard->area->title_presentation ?? 'N/A'}}</td>
                                <td>{{$hazard->location->title_presentation ?? 'N/A'}}</td>
                                <td>{{ ($hazard->inaccessReason->description ?? '') . ($hazard->reason == HAZARD_TYPE_INACCESSIBLE_ROOM ? ", ". ($hazard->reason_other ?? '') : '') }}</td>
                                <td ><img style="height: 100px; width: 150px; text-align: center;margin-bottom: 10px;margin-top: 5px" src="{{ CommonHelpers::getFile($hazard->id, HAZARD_PHOTO, $is_pdf) }}" alt="Hazard Photo"/></td>
                            </tr>
                        @endif
                    @endforeach
                    @if($count_hz == 0)
                        <tr>
                            <td colspan="5">No Information.</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="5">No Information.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <p class="mt30 mb30"><strong>Non-Accessed Items</strong></p>
        <!-- blue table -->
        <div class="tableItems tableGray" >
            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX>
                <thead>
                <tr>
                    <th width="15%">Floor</th>
                    <th width="15%">Room</th>
                    <th width="12%">Item</th>
                    <th width="28%">Reason for No Access</th>
                    <th width="15%">Photograph</th>
                </tr>
                </thead>
                <tbody>
                @if(count($equipments) > 0)
                    @php
                        $count_eq = 0;
                    @endphp
                    @foreach($equipments as $equipment)
                        @if($equipment->state == 0)
                            @php
                                $count_eq++;
                            @endphp
                            <tr>
                                <td>{{$equipment->area->title_presentation ?? 'N/A'}}</td>
                                <td>{{$equipment->location->title_presentation ?? 'N/A'}}</td>
                                <td>{{($equipment->name ?? '')}}</td>
                                <td>
                                    {{($equipment->inaccessReason->description ?? ''). ($equipment->reason_other ?? '')}}
                                </td>
                                <td><img style="height: 100px; width: 150px; text-align: center;margin-bottom: 10px;margin-top: 5px" src="{{ \ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_PHOTO, $is_pdf) }}" alt="Items Image"/></td>
                            </tr>
                        @endif
                    @endforeach
                    @if($count_eq == 0)
                        <tr>
                            <td colspan="6">No Information.</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="6">No Information.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>

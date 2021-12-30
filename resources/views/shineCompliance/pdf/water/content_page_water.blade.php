<!DOCTYPE HTML>
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $assessment->reference ?? ''}}</title>
    <style type="text/css">
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 10px;
            color: #575756;
        }

        .mt30{
            margin-bottom: 10px;
            margin-left: 20px;
            color: black;
            font-size: 10pt;
            line-height: 1.6;
            text-align: left;
            word-spacing: 1.5px;
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
            background-color: #026734;
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
            width: 95%;
            margin-left:45px;
        }
        .tableItems th {
            font-size: 14px;
            color: #ffffff;
            height: 30px;
        }
        #red tr th{
            background-color: #026734;
        }
        .tableItems tr {
            height: 30px;
        }
        .tableItems td {
            padding-left: 20px;
            padding-right: 20px;
        }
        #tableYellow tr th{
            background-color: #ffc000;
        }
        #tableGreen tr th {
            background-color: #92d050;
        }
        #tableBlue th {
            background-color: #0070c0;
        }
        #tableYesNo th {
            text-align: center;
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
            text-align: center;
            color: black;
            background-color: #026734;
            height: 50px;
        }
        /*EndtableItems*/

        .titleAppendix{
            color: black;
            padding-left:100px;
        }
        .textdecoration{
            border-bottom: 2px solid;
            margin-left: 20px;
            margin-top: 80px;
        }

        table, td, th {
            border: 1px solid black;
            font-size: 10pt;
        }

        table {
            color: black;
            margin-top: 50px;
            margin-bottom: 50px;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            font-weight: normal;
            color: white;
            background-color: #026734;
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
            text-align: center;
        }
        .tableAp3 tr th {
            font-size: 14px;
            text-align: center;
        }
        .text-content{
            margin-top: 30px;
            margin-bottom: 30px;
            margin-left: 30px;
        }
        .text-title{
            margin-bottom: 35px;
            margin-left: 20px;
            font-weight:normal;
        }
        .contents-section{
            margin-left: 0px;
            margin-bottom: 35px;
        }

        .title{
            padding:0px 30px;
            /*border: 3px solid green;*/
            background-color: #e6fbbb;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .contents{
            margin: 50px 0px;
        }
        .appendix{
            margin-bottom: 10px;
        }
        .titleAppendixTitle{
            padding-left: 120px;
        }
        .titleAdix{
            padding-left: 49px;
        }
        .titleAppendixTitleAp{
            padding-left: 80px;
        }
        th{
            text-align: left;
            padding-left:20px;
        }
        p{
            margin-bottom: 0px;
            font-size: 10pt;
        }
        h3{
            font-size: 11pt!important;
        }
        h2{
            font-size: 20px;
        }
        .alignment-title{
            background-color: #706f6f;
            color: white;
            width: 100%;
            height: 40px;
        }
        .alignment-text{
            padding-top: 8px;
            padding-left: 30px;
            font-size: 16px!important;
        }
    </style>
</head>
<body>
<div id="page" style="width: 900px">
    <div class="contents">
        <div class="textdecoration alignment-title">
            <p style="font-size: 16px!important;" class="alignment-text">Contents Page</p>
        </div>
        <div class="tableContent" style="padding-top: 0px">
            <div class="contents-section"style="margin-top: 30px;">
                <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[2]['offset'] : '' }}</span> 1.0 Property Information and Assessment Signatory</h3>
            </div>
            @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[4]['offset'] : '' }}</span> 2.0 Executive Summary</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[5]['offset'] : '' }}</span> 3.0 Summary of Hazards with Recommendations</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[6]['offset'] : '' }}</span> 4.0 Summary of Nonconformities</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[7]['offset'] : '' }}</span> 5.0 Outlet Register</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[8]['offset'] : '' }}</span> 6.0 Non-Assessed or Limited Accessed Rooms and Equipment</h3>
                </div>
                @if($assessment->type == ASSESS_TYPE_WATER_RISK)
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[9]['offset'] : '' }}</span> 7.0 Water Risk Audit</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[10]['offset'] : '' }}</span> 8.0 Summary of Water System(s)</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[11]['offset'] : '' }}</span> 9.0 Equipment Register</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[12]['offset'] : '' }}</span> 10.0 Risk Assessment Information</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[13]['offset'] : '' }}</span> 11.0 Example Water Pre-Planned Maintenance Schedule</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[13]['offset'] : '' }}</span> Appendix 1 Schematics</h3>
                @else
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[10]['offset'] : '' }}</span> 7.0 Summary of Water System(s)</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[11]['offset'] : '' }}</span> 8.0 Equipment Register</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[12]['offset'] : '' }}</span> 9.0 Risk Assessment Information</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[13]['offset'] : '' }}</span> 10.0 Example Water Pre-Planned Maintenance Schedule</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[13]['offset'] : '' }}</span> Appendix 1 Schematics</h3>
                </div>
                @endif

                </div>
            @else
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[3]['offset'] : '' }}</span> 2.0 Fire Exits, Assembly Points and Vehicle Parking</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[4]['offset'] : '' }}</span> 3.0 Executive Summary</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[5]['offset'] : '' }}</span> 4.0 Summary of Hazards with Recommendations</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[6]['offset'] : '' }}</span> 5.0 Summary of Nonconformities</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[7]['offset'] : '' }}</span> 6.0 Outlet Register</h3>
                </div>
                <div class="contents-section">
                    <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[8]['offset'] : '' }}</span> 7.0 Non-Assessed or Limited Accessed Rooms and Equipment</h3>
                </div>
                @if($assessment->type == ASSESS_TYPE_WATER_RISK)
                    <div class="contents-section">
                        <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[9]['offset'] : '' }}</span> 8.0 Water Risk Audit</h3>
                    </div>
                    <div class="contents-section">
                        <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[10]['offset'] : '' }}</span> 9.0 Summary of Water System(s)</h3>
                    </div>
                    <div class="contents-section">
                        <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[11]['offset'] : '' }}</span> 10.0 Equipment Register</h3>
                    </div>
                    <div class="contents-section">
                        <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[12]['offset'] : '' }}</span> 11.0 Risk Assessment Information</h3>
                    </div>
                    <div class="contents-section">
                        <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[13]['offset'] : '' }}</span> 12.0 Example Water Pre-Planned Maintenance Schedule</h3>
                    </div>
                    <div class="contents-section">
                        <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[13]['offset'] : '' }}</span> Appendix 1 Schematics</h3>
                    </div>
                @else
                    <div class="contents-section">
                        <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[10]['offset'] : '' }}</span> 8.0 Summary of Water System(s)</h3>
                    </div>
                    <div class="contents-section">
                        <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[11]['offset'] : '' }}</span> 9.0 Equipment Register</h3>
                    </div>
                    <div class="contents-section">
                        <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[12]['offset'] : '' }}</span> 10.0 Risk Assessment Information</h3>
                    </div>
                    <div class="contents-section">
                        <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[13]['offset'] : '' }}</span> 11.0 Example Water Pre-Planned Maintenance Schedule</h3>
                    </div>
                    <div class="contents-section">
                        <h3 class="text-title"><span style="padding-right: 30px;">{{ isset($tocData) ? $tocData[13]['offset'] : '' }}</span> Appendix 1 Schematics</h3>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>


<!--Container - set width -->
</body>
</html>

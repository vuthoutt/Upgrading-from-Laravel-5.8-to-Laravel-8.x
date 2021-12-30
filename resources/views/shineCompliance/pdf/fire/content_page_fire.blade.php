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
            font-size: 12pt!important;
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
            <p style="font-size: 18px!important;" class="alignment-text">Contents Page</p>
        </div>
        <div class="tableContent" style="padding-top: 0px">
            <div class="contents-section"style="margin-top: 30px;">
                <h3 class="text-title">1.0 Property Information and Assessment Signatory</h3>
            </div>
            @if($assessment->assessmentInfo->setting_show_vehicle_parking == VEHICLE_PARKING)
                <div class="contents-section">
                    <h3 class="text-title">2.0 Fire Exits, Assembly Points and Vehicle Parking</h3>
                </div>
            @else
                <div class="contents-section">
                    <h3 class="text-title">2.0 Fire Exits and Assembly Points</h3>
                </div>
            @endif
            <div class="contents-section">
                <h3 class="text-title">3.0 Executive Summary</h3>
            </div>
            <div class="contents-section">
                <h3 class="text-title">4.0 Management and Other Information</h3>
            </div>
            <div class="contents-section">
                <h3 class="text-title">5.0 Summary of Hazards with Recommendations</h3>
            </div>
            <div class="contents-section">
                <h3 class="text-title">6.0 Non-Assessed or Limited Accessed Rooms and Equipment</h3>
            </div>
            <div class="contents-section">
                <h3 class="text-title">7.0 Fire Risk Audit</h3>
            </div>
            <div class="contents-section">
                <h3 class="text-title">8.0 Summary of Fire System(s)</h3>
            </div>
            <div class="contents-section">
                <h3 class="text-title">9.0 Equipment Register</h3>
            </div>
            <div class="contents-section">
                <h3 class="text-title">10.0 Risk Assessment Information</h3>
            </div>
            <div class="contents-section">
                <h3 class="text-title">Appendix 1 Plans</h3>
            </div>
        </div>
    </div>
</div>


<!--Container - set width -->
</body>
</html>

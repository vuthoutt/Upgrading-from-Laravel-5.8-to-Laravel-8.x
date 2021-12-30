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
            height: 50px;
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
            margin-top: 0px;
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
        .unset-border td {
            border: 0!important;
        }
        table.unset-border{
            color: #575756;
            border: 0!important;
            margin-left: 5px
        }
        tr{
            height: 50px;
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
            <table cellspacing="0" class="unset-border">
                @if(isset($new_title['info']))
                <tr>
                    <td width="5%">
                        <p>{{ isset($tocData) ? $tocData[2]['offset'] : '' }}</p>
                    </td>
                    <td width="60%">
                        <p>{{ isset($new_title['info']) ? $new_title['info'] : '' }}.0 Property Information and Assessment Signatory</p>
                    </td>
                </tr>
                @endif
                @if(isset($new_title['fire']))
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[3]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>{{ isset($new_title['fire']) ? $new_title['fire'] : '' }}.0 Fire Exits, Assembly Points and Vehicle Parking</p>
                        </td>
                    </tr>
                @endif
                @if(isset($new_title['summary']))
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[4]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>{{ $new_title['summary'] }}.0 Executive Summary</p>
                        </td>
                    </tr>
                @endif
                @if(isset($new_title['hazard']))
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[5]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>{{$new_title['hazard']}}.0 Summary of Hazards with Recommendations</p>
                        </td>
                    </tr>
                @endif
                @if(isset($new_title['nonconformities']))
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[6]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>{{$new_title['hazard']}}.0 Summary of Nonconformities</p>
                        </td>
                    </tr>
                @endif
                @if(isset($new_title['outlet']))
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[7]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>{{$new_title['outlet']}}.0 Outlet Register</p>
                        </td>
                    </tr>
                @endif
                @if(isset($new_title['non']))
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[8]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>{{ $new_title['non'] }}.0 Non-Assessed or Limited Accessed Rooms</p>
                        </td>
                    </tr>
                @endif
                @if(isset($new_title['Hs']))
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[9]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>{{ $new_title['Hs'] }}.0 H&S Risk Audit</p>
                        </td>
                    </tr>
                @endif
                @if(isset($new_title['system']))
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[10]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>{{ $new_title['system'] }}.0 Summary of H&S System(s)</p>
                        </td>
                    </tr>
                @endif
                @if(isset($new_title['equiment']))
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[11]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>{{ $new_title['equiment'] }}.0 Equipment Register</p>
                        </td>
                    </tr>
                @endif
                @if(isset($new_title['risk']))
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[12]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>{{ $new_title['risk'] }}.0 Risk Assessment Information</p>
                        </td>
                    </tr>
                @endif
                @if(isset($new_title['example']))
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[13]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>{{ $new_title['example'] }}.0 Example H&S Pre-Planned Maintenance Schedule</p>
                        </td>
                    </tr>
                @endif
                    <tr>
                        <td>
                            <p>{{ isset($tocData) ? $tocData[13]['offset'] : '' }}</p>
                        </td>
                        <td>
                            <p>Appendix 1 Schematics</p>
                        </td>
                    </tr>
            </table>
        </div>
    </div>
</div>


<!--Container - set width -->
</body>
</html>

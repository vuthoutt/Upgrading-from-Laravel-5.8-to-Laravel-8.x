<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" >
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{$incident_data->reference . " : " . isset($incident_data->property->name) ? $incident_data->property->name : "" }}</title>
    <style type="text/css">
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 8pt !important;
            margin:0;
            padding:0;
            color: #595861!important;
        }

        h1, h4, h2, h3, h5, h6 {
            margin: 2px 0;
        }

        p {
            margin: 5px 0;
            text-align: justify;
        }

        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }

        .page {
            margin-top: 10px;
        }

        #survey-report {
            margin-right: 60px;
        }
        #watermark {
            height: 70px;
        }
        .coverphoto {
            margin-right: 60px;
        }
        .uprn{
            color: #595861;
            margin-top: 20px ;
        }
        .header{
            margin-bottom: 20px;
        }
        .title{
            padding:0px 30px;
            text-align: center;
            background-color: #e6fbbb;
            font-size: 20px;
            margin-bottom: 30px;
        }
        .content{
            margin:30px 0px;
        }
        .content-title{
            font-size: 15px;
        }
        .rpi{
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .table table, td, th {
            border: 1px solid black;
        }

        .table table {
            text-align: left;
            border-collapse: collapse;
            width: 100%;
        }

        .table th {
            padding-left: 20px;
            background-color: #e6fbbb;
        }
        .table td {
            padding-left: 20px;
            padding-right: 20px;
        }
        .pagecover{
            height: 900px;
            position: relative;
            margin: 0;
            /*padding: 70px 30px 10px 20px;*/
{{--            background: url({{ public_path('img/back_ground_cover.png') }}) no-repeat right center;--}}
        }
        .footerpagecover{
            position: absolute;
            bottom: 0px;
            left: 0px;
            width: 100%;
        }
        .ohf{
            margin-top: 60px;
        }
        h3{
            font-size: 10pt!important;
        }
        h2{
            font-size: 25px;
        }
        .title-h1{
            font-size: 30px;
            width: 45%;
            margin-top: 50px;
            margin-bottom: 30px;
            margin-left: 15px;
        }
        .tableGray th{
            background-color: #57585a;
            color: white;
            padding-left: 15px;
        }
        .tableGray td{
            padding-left: 15px;
            height: 25px!important;
        }
        /*.tableGray{*/
        /*    font-size: 8pt!important;*/
        /*}*/
        .mt30{
            margin-top: 20px;
            margin-left: 15px;
            margin-bottom: 30px;
            color: black;
            font-size: 8pt!important;
            line-height: 1.8;
            text-align: left;
            word-spacing: 1.0px;
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
        .system th,td{
            text-align: left;
            padding-left: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="pagecover">
        <h1 class="title-h1">Summary</h1>

        <table cellspacing="0" class="unset-border">
            <tr>
                <td width="25%">
                    <p>Incident Report Reference:</p>
                </td>
                <td width="60%">
                    <p>{{ $incident_data->reference ?? '' }}</p>
                </td>
            </tr>
            <tr style="border-top: unset!important;border-bottom: unset!important;">
                <td>
                    <p>Incident Report Form Type:</p>
                </td>
                <td>
                    <p>{{ $incident_data->incidentType->description ?? ''}}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Incident Report Date:</p>
                </td>
                <td>
                    <p>{{ $incident_data->date_of_report ?? ''}}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Incident Report Time: </p>
                </td>
                <td>
                    <p>{{ $incident_data->time_of_report ?? ''}}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Date of Incident:</p>
                </td>
                <td>
                    <p>{{ $incident_data->date_of_incident ?? ''}}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Time  of Incident:</p>
                </td>
                <td>
                    <p>{{ $incident_data->time_of_incident ?? ''}}</p>
                </td>
            </tr>
            <tr>
                <td  valign="top">
                    <p>Address:</p>
                </td>
                <td>
                    <p>{!!$property->property_reference!!}</p>
                    <p>{!!$property->pblock!!}</p>
                    <p>{!!$property->name!!}</p>
                    <p>
                        {!! implode(", ", array_filter([
                            optional($property->propertyInfo)->address1,
                            optional($property->propertyInfo)->address2,
                            optional($property->propertyInfo)->address3,
                            optional($property->propertyInfo)->address4,
                            optional($property->propertyInfo)->address5
                        ]))!!}
                    </p>
                    <p>{{$property->postcode}}</p>
                </td>
            </tr>
            <tr>
                <td>
                    Equipment:
                </td>
                <td>{{ $incident_data->equipment->name ?? '' }}</td>
            </tr>
            <tr>
                <td>
                    System:
                </td>
                <td>{{ $incident_data->system->name ?? '' }}</td>
            </tr>
        </table>

        <h3 class="mt30">
            {{ $incident_data->incidentType->description }} Details
        </h3>
        <p class="mt30"> {{ $incident_data->details }}</p>
        <div class="tableItems tableGray" style="margin-top: 20px;padding-left: 15px">
            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:100%;">
                <thead>
                <tr class="thup">
                    <th width="20%">Question</th>
                    <th width="79%" >
                        Responses
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td >Injury Type</td>
                    <td >[Response], [Response], [Response]</td>
                </tr>
                <tr>
                    <td>Part(s) of the body affected</td>
                    <td >[Response], [Response], [Response]</td>
                </tr>
                <tr>
                    <td> Apparent Cause </td>
                    <td >[Response], [Response], [Response]</td>
                </tr>

                </tbody>
            </table>
        </div>
        <table cellspacing="0" class="unset-border" style="margin: 20px 0px">
            <tr>
                <td width="25%">
                    <p>Confidential:</p>
                </td>
                <td width="60%">
                    <p>{{ $incident_data->confidential == 1 ? "Yes" : "No"  }}</p>
                </td>
            </tr>
        </table>
        <h3 class="mt30">
            Supporting Documents
        </h3>
        <table cellspacing="0" class="unset-border" s>
            <tr>
                <td width="25%">
                    <p>Supporting Documents:</p>
                </td>
                <td width="60%">
                    <p>[No] OR [000]</p>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>

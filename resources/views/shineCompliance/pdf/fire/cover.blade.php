<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" >
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{$assessment->reference . " : " . isset($property->name) ? $property->name : "" }}</title>
    <style type="text/css">
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 10pt;
            margin:0;
            padding:0;
            color: #575756;
        }

        h1, h4, h2, h3, h5, h6, p {
            margin: 2px 0;
            color: #575756;
        }

        /*p {*/
        /*    margin: 1px 0;*/
        /*    text-align: justify;*/
        /*}*/

        /*table, tr, td, th, tbody, thead, tfoot {*/
        /*    page-break-inside: avoid !important;*/
        /*    color: #575756;*/
        /*}*/

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
            color: black;
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
            padding: 0;
            /*height: 1300px;*/
            /*position: relative;*/
            margin: 0;
            /*padding: 70px 30px 10px 20px;*/
            {{--background: url({{ public_path('img/back_ground_cover.png') }}) no-repeat right center;--}}
        }
        .footerpagecover{
            position: absolute;
            bottom: 80px;
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
        p{
            font-size: 10pt!important;
        }
        .abs-footer{
            /*width: 100%;*/
            padding: 0;
            margin: 0;
        }
        .unset-border td {
            border: 0!important;
        }
        table.unset-border{
            border: 0!important;
            margin-left: 30px
        }
        .alignment{
            margin: 0px 50px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="pagecover">
        <div class="alignment">
            <div style="float: left;width: 30%;margin-left: 0px">
                <img src="{{ (CommonHelpers::getFile($assessment->property->client_id ?? 0, CLIENT_LOGO, $is_pdf))  }}" height="120px">
            </div>
            <div id="client-thumb" style="margin-bottom:30px;clear:left">
                    @if(CommonHelpers::checkFile($assessment->id, PROPERTY_ASSESS_IMAGE))
                        <img style="height: 180px;padding-top: 20px" src="{{ \CommonHelpers::getFile($assessment->id, PROPERTY_ASSESS_IMAGE, $is_pdf) }}"/>
                    @elseif(CommonHelpers::checkFile($assessment->property_id, PROPERTY_IMAGE))
                        <img style="height: 180px;padding-top: 20px" src="{{ (CommonHelpers::getFile($assessment->property_id, PROPERTY_IMAGE, $is_pdf))  }}"/>
                    @endif
            </div>
        </div>

        <div class="page" >
            <div class="header alignment" style="min-height: 220px">
                <h2>Fire Risk Assessment Report</h2>
                <div class="uprn">
                    <h3>{{ $property->property_reference ?? '' }}</h3>
                    <h3>{{ $property->pblock ?? ''}}</h3>
                    <h3>{{ $property->name ?? '' }}</h3>
                    <h3>{{ implode(", ", array_filter([$property->propertyInfo->flat_number ?? '', $property->propertyInfo->building_name ?? ''])) }}</h3>
                    <h3>{{ implode(", ", array_filter([$property->propertyInfo->street_number ?? '', $property->propertyInfo->street_name ?? ''])) }}</h3>
                    <h3>{{ $property->propertyInfo->town ?? '' }}</h3>
                    <h3>{{ $property->propertyInfo->address5 ?? '' }}</h3>
                    <h3>{{ $property->propertyInfo->postcode ?? '' }}</h3>
                </div>
            </div>
            <table cellspacing="0" class="unset-border alignment" width="45%" style="margin-top: 30px;margin-left: 50px;margin-bottom: 20px">
                <tr>
                    <td width="25%">
                        <h3  >
                            Assessment Reference:
                        </h3>
                    </td>
                    <td width="20%">
                        {{ ($assessment->reference ?? '')  }}
                    </td>
                </tr>
                <tr style="border-top: unset!important;border-bottom: unset!important;">
                    <td>
                        <h3>Linked Project Reference:</h3>
                    </td>
                    <td>
                        {{ $assessment->project->reference ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td >
                        <h3 >
                            Assessment Date:
                        </h3>
                    </td>
                    <td >
                        {{-- {{implode(" - ", array_filter([$assessment->assess_start_date ?? '', $assessment->assess_finish_date ?? '']))}} --}}
                        {{ $assessment->sent_back_day ?? '' }}
                    </td>
                </tr>
                <tr style="line-height: 40px;">
                    <td>
                        <h3 >
                            FRA Overall Risk Rating:
                        </h3>
                    </td>
                    <td>
                        <span style="padding-left: 20px;padding-right: 20px;padding-top: 5px;padding-bottom: 5px;{{ CommonHelpers::getStyleFRARiskRating(json_decode($assessment->assessmentInfo->property_information ?? '', true)) }}">
                        {{ CommonHelpers::getPropertyInfoField(json_decode($assessment->assessmentInfo->property_information ?? '', true), 'fra_overall') }}
                        </span>
                    </td>
                </tr>
            </table>

            <div class="abs-footer" style="margin-bottom: 0px">
                <div style="width: 100%;height: 50px;background-color: #9d9d9c;text-align: center;">
                    <h1 style="color: white;padding: 10px">
                        Next Fire Risk Assessment Required {{ $assessment->next_inspection_assessment ?? '00/00/0000' }}
                    </h1>
                </div>

                <div class="ohf alignment" style="margin-top: 30px">
                    <h3 style="font-weight:normal;"><strong>Identified Actions</strong></h3>
                </div>
                <table cellspacing="0" class="unset-border alignment" width="60%"  style="margin-left: 50px;margin-top: 10px">
                    <tr>
                        <td width="25%">
                            <p  >
                                Very High Risk - Immediate Action Required:
                            </p>
                        </td>
                        <td width="5%" style="background-color: #8a1812;color: white;text-align: center">
                            {{$count_very_high_risk_hazard ?? 0}}
                        </td>
                    </tr>
                    <tr style="border-top: unset!important;border-bottom: unset!important;">
                        <td>
                            <p  >High Risk - Urgent - Action Required within 1 Month Assessment:</p>
                        </td>
                        <td style="background-color: #e30b17;color: white;text-align: center">
                            {{$count_high_risk_hazard ?? 0}}
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <p >
                                Medium Risk - Action Required within 3 Months of Assessment:
                            </p>
                        </td>
                        <td style="background-color: #ad8049;color: white;text-align: center">
                            {{$count_medium_risk_hazard ?? 0}}
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <p >
                                Low Risk - Action Required within 6 Months of Assessment:
                            </p>
                        </td>
                        <td style="background-color: #f7a416;color: white;text-align: center">
                            {{$count_low_risk_hazard ?? 0}}
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <p >
                                Very Low Risk - Action Required within 12 Months of Assessment:
                            </p>
                        </td>
                        <td style="background-color: #f3e600;color: black;text-align: center">
                            {{$count_very_low_risk_hazard ?? 0}}
                        </td>
                    </tr>
                </table>
                <div class="ohf alignment" style="margin-top: 10px">
                    <h3 style="font-weight:normal;"><strong>Assessment by</strong></h3>
                </div>
                <img class="coverphoto alignment" style="max-height: 150px;margin-top:10px;margin-bottom: 25px" src="{{ (CommonHelpers::getFile($assessment->assessor->client_id ?? 0, CLIENT_LOGO, $is_pdf))}}"/>

                <div class="client alignment" style="padding-bottom: 0px;margin-bottom: 0px">
                    <div class="row">
                        <h3 style="display:inline-block;width:25%;text-align:left;">
                            Consultant:</h3>
                        <p style="display:inline-block;width:40%;text-align:left;margin: 0">{{ $assessment->assessor->clients->name ?? '' }}
                        </p>
                    </div>
                    <div class="row">
                        <h3 style="display:inline-block;width:25%;text-align:left;">
                            Consultant Reference:</h3>
                        <p style="display:inline-block;width:40%;text-align:left;margin: 0">{{ $assessment->assessor->clients->reference ?? '' }}
                        </p>
                    </div>
                    <div class="row">
                        <h3 style="display:inline-block;width:25%;text-align:left;">
                            Consultant Contact:</h3>
                        <p style="display:inline-block;width:40%;text-align:left;margin: 0">{{ $assessment->assessor->clients->mainUser->full_name ?? '' }}</p>
                    </div>
                    <div class="row">
                        <h3 style="display:inline-block;width:25%;text-align:left;">
                            Consultant Address:</h3>
                        <p style="display:inline;width:40%;text-align:left;margin: 0">{{ $assessment->assessor->clients->clientAddress->list_address ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

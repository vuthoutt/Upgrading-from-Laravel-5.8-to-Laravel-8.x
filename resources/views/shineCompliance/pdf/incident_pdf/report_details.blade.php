<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" >
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{$incident_data->reference . ($incident_data->property ? (" : " . ($incident_data->property->name ?? '')) : "") }}</title>
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
            margin: 5px 5px 5px 0;
            text-align: justify;
        }

        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
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
            padding-left: 0;
            background-color: #e6fbbb;
        }
        .table td {
            padding-left: 0;
            padding-right: 0;
        }
        .pagecover{
            height: 900px;
            position: relative;
            margin: 0;
            /*padding: 70px 30px 10px 20px;*/
{{--            background: url({{ public_path('img/back_ground_cover.png') }}) no-repeat right center;--}}
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
        .title-h1{
            font-size: 30px;
            margin-top: 50px;
            margin-left: 0;
            margin-bottom: 35px;
            padding-top: 30px;
        }
        .tableGray th{
            background-color: #57585a;
            color: white;
            padding-left: 0;
        }
        .tableGray td{
            padding-left: 15px;
            height: 25px!important;
        }
        .tableGray{
            margin-top: 10px;
        }
        .mt30{
            margin-bottom: 30px;
            margin-top: 30px;
            margin-left: 0;
            color: black;
            font-size: 8pt!important;
            line-height: 1.8;
            text-align: left;
            word-spacing: 1.0px;
        }
        .involved-person {
            margin: 10px 0;
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
            margin-left: 0
        }
        p{
            font-size: 10pt!important;
            text-align: justify!important;
        }
        .system th,td{
            text-align: left;
            padding-left: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="pagecover">
        <h1 class="title-h1">{{ $incident_data->incidentType->description }} Report Details</h1>
        <h3 class="mt30">Incident Report Reporter</h3>
        <table cellspacing="0" class="unset-border">
            <tr>
                <td width="1200px">
                    <p>Incident Report Reporter:</p>
                </td>
                <td width="60%">
                    <p>{{ \CommonHelpers::getUserFullname($incident_data->reported_by) }}</p>
                </td>
            </tr>
            @if ($incident_data->reportRecorder->is_call_centre_staff)
                <tr>
                    <td>
                        <p>Call Centre Team Member Name:</p>
                    </td>
                    <td>
                        <p>{{ $incident_data->call_centre_team_member_name ?? '' }}</p>
                    </td>
                </tr>
            @endif
            <tr style="border-top: unset!important;border-bottom: unset!important;">
                <td>
                    <p>Job Role:</p>
                </td>
                <td>
                    <p>{{ $user->contact->job_title ?? '' }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Department:</p>
                </td>
                <td>
                    <p>{{  implode("- ", array_filter([
                            $user->department->parents->name ?? '',
                            $user->department->name ?? ''
                        ]))
                        }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Telephone: </p>
                </td>
                <td>
                    <p>{{ $user->contact->telephone ?? '' }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Mobile:</p>
                </td>
                <td>
                    <p>{{ $user->contact->mobile ?? ''  }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Email:</p>
                </td>
                <td>
                    <p>{{ $user->email ?? '' }}</p>
                </td>
            </tr>
        </table>

        <h3 class="mt30">Incident Report Information</h3>
        <table cellspacing="0" class="unset-border">
            <tr>
                <td width="1200px">
                    <p>Incident Report Reporter:</p>
                </td>
                <td width="60%">
                    <p>{{ $incident_data->reference }}</p>
                </td>
            </tr>
            <tr style="border-top: unset!important;border-bottom: unset!important;">
                <td>
                    <p>Incident Report Date:</p>
                </td>
                <td>
                    <p>{{ $incident_data->date_of_report }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Incident Report Time:</p>
                </td>
                <td>
                    <p>{{ $incident_data->time_of_report }}</p>
                </td>
            </tr>
            @if($incident_data->type == INCIDENT || $incident_data->type == SOCIAL_CARE)
                <tr>
                    <td>
                        <p>Date of Incident: </p>
                    </td>
                    <td>
                        <p>{{ $incident_data->date_of_incident }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Time of Incident:</p>
                    </td>
                    <td>
                        <p>{{ $incident_data->time_of_incident }}</p>
                    </td>
                </tr>
            @endif
            <tr>
                <td>
                    <p>Created Date:</p>
                </td>
                <td>
                    <p>{{ $incident_data->created_at->format('d/m/Y')}}</p>
                </td>
            </tr>
            @if(!empty($incident_data->published_date))
                <tr>
                    <td>
                        <p>Published Date:</p>
                    </td>
                    <td>
                        <p>{{ $incident_data->published_date_pdf }}</p>
                    </td>
                </tr>
            @endif
        </table>
        <h3 class="mt30">
            Document Authorisation
        </h3>
        <div class="signature" style="margin-top: 10px;width: 50%; float: left;text-align: center">
            <img img style="max-height: 50px; max-width: 200px; margin-top: 7px"  src="{{ \CommonHelpers::getFile($user->id, USER_SIGNATURE, $is_pdf) }}" class="image-signature">
            <p style="text-align: center!important;font-size: 8pt !important;">{{ $user->full_name ?? '' }}</p>
            <p style="text-align: center!important;font-size: 8pt !important;">{{ $incident_data->call_centre_team_member_name ?? '' }}</p>
            <p style="text-align: center!important;font-size: 8pt !important;">Incident Report Reporter</p>
        </div>
        @if($incident_data->asbestos_lead)
            <div class="signature" style="margin-top: 10px;width: 50%; float: left;text-align: center">
                <img img style="max-height: 50px; max-width: 200px; margin-top: 7px"  src="{{ \CommonHelpers::getFile($incident_data->asbestos_lead, USER_SIGNATURE, $is_pdf) }}" class="image-signature">
                <div style="margin-left: 10px;padding-right: 10px">
                    <p style="text-align: center!important;font-size: 8pt !important;">{{ \CommonHelpers::getUserFullname($incident_data->asbestos_lead) }}</p>
                    <p style="text-align: center!important;font-size: 8pt !important;">H&S Lead</p>
                </div>
            </div>
        @endif
    </div>
    <div style="page-break-before: always;"></div>
    <div class="pagecover">
        <h1 class="title-h1">Summary</h1>

        <table cellspacing="0" class="unset-border">
            <tr>
                <td width="1200px">
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
            @if($incident_data->type == INCIDENT)
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
            @endif
            <tr>
                <td  valign="top">
                    <p>Address of Incident:</p>
                </td>
                <td>
                    @if($incident_data->is_address_in_wcc)
                        <p>{!! $property->property_reference ?? '' !!}</p>
                        <p><strong>{!! $property->pblock ?? '' !!}</strong></p>
                        <p><strong>{!! $property->name ?? '' !!}</strong></p>
                        <p>{!! optional($property->propertyInfo)->flat_number ?? '' !!}</p>
                        <p>{!! optional($property->propertyInfo)->building_name ?? '' !!}</p>
                        <p>{!! optional($property->propertyInfo)->street_number ?? '' !!}</p>
                        <p>{!! optional($property->propertyInfo)->town ?? '' !!}</p>
                        <p>{!! optional($property->propertyInfo)->address5 ?? '' !!}</p>
                        <p>{{ optional($property->propertyInfo)->postcode ?? '' }}</p>
                    @else
                        <p>{!! $incident_data->address_building_name ?? '' !!}</p>
                        <p>{!! $incident_data->address_street_number ?? '' !!}</p>
                        <p>{!! $incident_data->address_street_name ?? '' !!}</p>
                        <p>{!! $incident_data->address_town ?? '' !!}</p>
                        <p>{!! $incident_data->address_county ?? '' !!}</p>
                        <p>{!! $incident_data->address_postcode ?? '' !!}</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <p>Equipment:</p>
                </td>
                <td><p>{{ $incident_data->equipment->name ?? '' }}</p></td>
            </tr>
            <tr>
                <td>
                    <p>System:</p>
                </td>
                <td><p>{{ $incident_data->system->name ?? '' }}</p></td>
            </tr>
        </table>

        <h3 class="mt30">
            {{ $incident_data->incidentType->description }} Details
        </h3>
        <p class="mt30"> {{ $incident_data->details }}</p>

        @if($incident_data->type == INCIDENT)
            <table cellspacing="0" class="unset-border">
                <tr>
                    <td width="1200px">
                        <p>Category of Works (Contractor Work Only):</p>
                    </td>
                    <td width="60%">
                        <p>{{ $incident_data->categoryOfWorkType->description ?? ''}}</p>
                    </td>
                </tr>
                <tr>
                    <td width="1200px">
                        <p>Was there a Risk Assessment for this Work?</p>
                    </td>
                    <td width="60%">
                        <p>{{ $incident_data->is_risk_assessment == 1 ? "Yes" : "No"  }}</p>
                    </td>
                </tr>
            </table>
        @endif

        @if($data_involved)
            @foreach($data_involved as $data)
                <h3 class="mt30">
                    Who was involved
                </h3>
                <p class="involved-person">
                    @if($data->user_id == -1)
                        {{ $data->non_user ?? '' }}
                    @else
                        {{ \CommonHelpers::getUserFullname($data->user_id) }}
                    @endif
                </p>
                <div class="tableItems tableGray">
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
                        <td>{{ trim(implode("", array_filter([\CommonHelpers::getDataIncidentReportInvolvedPerson($data->injury_type), \CommonHelpers::getDataInvolvedReasonOther($data->injury_type_other)])), ", ")  }}</td>
                    </tr>
                    <tr>
                        <td>Part(s) of the body affected</td>
                        <td>{{ \CommonHelpers::getDataIncidentReportInvolvedPerson($data->part_of_body_affected)  }}</td>
                    </tr>
                    <tr>
                        <td> Apparent Cause </td>
                        <td>
                            {{ trim(implode("", array_filter([\CommonHelpers::getDataIncidentReportInvolvedPerson($data->apparent_cause), \CommonHelpers::getDataInvolvedReasonOther($data->apparent_cause_other)])), ", ") }}
                        </td>
                    </tr>

                    </tbody>
                </table>
                </div>
            @endforeach
        @endif

        @if($incident_data->type == INCIDENT)
            <table cellspacing="0" class="unset-border" style="margin: 20px 0px;padding-left: 0">
                <tr>
                    <td width="250px" >
                        <p>Confidential:</p>
                    </td>
                    <td style="width:55%">
                        <p>{{ $incident_data->confidential == 1 ? "Yes" : "No"  }}</p>
                    </td>
                </tr>
            </table>
        @endif

        <h3 class="mt30">
            Supporting Documents
        </h3>
        <table cellspacing="0" class="unset-border" style="padding-left: 0px">
            <tr>
                <td width="250px">
                    <p>Supporting Documents:</p>
                </td>
                <td >
                    <p>{{ $count_doc ?? 0 }}</p>
                </td>
            </tr>
        </table>
        </div>
    </div>
</div>
</body>
</html>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" >
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    @if($work_request->is_major == 1 )
    <title>{{$work_request->reference }}</title>
    @else
    <title>{{$work_request->reference . " : " . $work_request->property->name ?? "" }}</title>
    @endif
    <style type="text/css">
        body {
            padding: 10px 30px 10px 20px !important;
            color: #666 !important;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif !important;
            font-size: 10pt !important;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #333;
            margin: 5px 0;
        }

        p {
            margin: 1px 0;
            text-align: justify;
        }

        .page {
            margin-top: 30px;
        }

        #survey-report {
            height: 1100px;
        }

        #watermark {
            height: 70px;
        }
    </style>
</head>
<body>
<div id="container" style="width: 859px; page-break-inside: avoid;">
    <div id="survey-report" class="page">
        <div id="client-thumb" style="margin-bottom:30px">
            <img height="100" src="{{ \CommonHelpers::getFile(1, CLIENT_LOGO, true) }}"/>
        </div>
        <div id="property-thumb" style="margin-bottom:20px">
            @if(\CommonHelpers::checkFile($work_request->property->id ?? 0, PROPERTY_PHOTO))
                    <img height="250"
                         src="{{ \CommonHelpers::getFile($work_request->property->id ?? 0, PROPERTY_PHOTO, true) }}"
                         alt="Property Image"/>
            @endif
        </div>
        <div id="asbestos-survey-report">
            @if(isset($work_request))
                <h2>{{ $work_request->work_type ?? ''}} Work Request <span>{{date("d/m/Y", $work_request->created_date)}}</span></h2>
                <div style="margin-bottom:30px;">
                    <h3>Work Request Priority: {{ $work_request->workPriority->description ?? ''}}</h3>
                    @if(optional($work_request->workPriority)->other == 1)
                        <h3>Priority Reason: {{ $work_request->priority_reason ?? '' }}</h3>
                    @endif
                </div>
{{--                @if(isset($work_request->orchardJob) and !is_null($work_request->orchardJob) and $work_request->orchardJob->status == 1)--}}
                <div style="margin-bottom:30px;">
                    <h3>Orchard Job Number: {{ $work_request->orchardJob->job_number ?? 'MANUAL'}}</h3>
                </div>
{{--                @endif--}}
            @endif
            @if($work_request->is_major == 0)
            <div id="property-details1">
                <p><strong>{{$work_request->property->property_reference ?? ''}}</strong></p>
                <p><strong>{{$work_request->property->pblock ?? ''}}</strong></p>
                <p><strong>{{$work_request->property->name ?? ''}}</strong></p>
                <p>{{$work_request->property->propertyInfo->flat_number ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->building_name ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->street_number ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->street_name ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->address3 ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->address4 ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->address5 ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->postcode ?? ''}}</p>
            </div>
            @endif
            <div id="surveyed-by" style="margin-top:30px;">
                <p>IMPORTANT: The asbestos management information is live, meaning that the system is constantly being
                    updated and amended. Summaries created from information in either the asbestos register or management
                    system will therefore only be valid for the time and date they are created.</p>
                <p>&nbsp;</p>
                <p>Summaries provide a limited overview of the data available, they are not designed to be used by those
                    carrying out major refurbishment or works involving alterations to the fabric of a building or its
                    services.</p>
                <p>&nbsp;</p>
                <p>Contact the Asbestos Management Team for more detail.</p>
            </div>

        </div>
    </div><!-- PAGE 01 -->
    <div id="watermark">
        <p style="font-style:italic;">
            Powered by </p>
        <div id="powered-by-thumb">
            <img src="{{public_path('img/shineAsbestosLogo.png')}}" height="50" alt="ShineAsbestos Logo">
        </div>
    </div>
</div><!--Container - set width -->

</body>
</html>

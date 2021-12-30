<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" >
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{$incident_data->reference . ($property ? (" : " . $property->name ?? '') : "") }}</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 10px 0;
            color: #666;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 10pt;
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
            height: 1250px;
        }

        #watermark {
            height: 70px;
        }

        .incident-cover-footer {
            position: absolute;
            bottom: 0;
            left: 0;
        }
    </style>
</head>
<body>
<div id="container" style="width: 859px; page-break-inside: avoid;">
    <div id="survey-report" class="page">
        <div style="display: block;height:100px;margin-bottom: 10px;">
            <img style="display:inline-block;max-height: 100px;" height="100px" src="{{ CommonHelpers::getFile(1, CLIENT_LOGO, $is_pdf) }}"/>
        </div>
        <div id="property-thumb" style="margin-bottom:25px">
            @if($property && CommonHelpers::checkFile($property->id, PROPERTY_PHOTO))
                <img style="max-height: 250px;" height="250" src="{{ CommonHelpers::getFile($property->id, PROPERTY_PHOTO, $is_pdf) }}" alt="Property Image"/>
            @endif
        </div>
        <div id="asbestos-survey-report">
            <h2>{{ $incident_data->incidentType->description ?? ''}} Report</h2>
            <div id="property-details1" style="margin-top:15px;">
                <h3>
                    <strong>Incident Report Date: </strong>
                    <span>
                        {{ $incident_data->date_of_report }}
                    </span>
                </h3>
                @if(!empty($incident_data->date_of_incident))
                    <h3 style="margin-top:15px;">
                        <strong>Date of Incident: </strong>
                        <span>
                        {{ $incident_data->date_of_report }}
                    </span>
                    </h3>
                @endif
                <div class="property-info-pdf" style="margin-top:15px;">
                    @if($incident_data->is_address_in_wcc)
                        <p>{!! $property->property_reference ?? '' !!}</p>
                        <p>{!! $property->pblock ?? '' !!}</strong></p>
                        <p>{!! $property->name ?? '' !!}</strong></p>
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
                </div>
            </div>
            <div id="surveyed-by" style="margin-top:30px;">
                <p>IMPORTANT: The H&S management information is live, meaning that the system is constantly being updated and amended.
                    Summaries created from information in either the H&S register or management system will therefore only be valid for the time and date they are created. </p>
                <p>&nbsp;</p>
                <p>Contact the H&S Management Team for more detail.</p>
            </div>

        </div>
    </div><!-- PAGE 01 -->
    <div class="incident-cover-footer">
        <p style="font-style: italic">Powered by</p>
        <img src="{{public_path('img/shineCompliance.png')}}" height="30px" alt="ShineCompliance Logo">
    </div>
</div><!--Container - set width -->

</body>
</html>

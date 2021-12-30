<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" >
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{$ss_ref}}</title>
    <style type="text/css">
        body {
            padding: 10px 30px 10px 20px;
            color: #666;
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        h2 {
            font-size: 1.5em;
        }

        p {
            margin: 1px 0;
            text-align: justify;
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-row-group
        }

        tr {
            page-break-inside: avoid
        }

        .page {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        @media print {
            .element-that-contains-table {
                overflow: visible !important;
            }
        }

        #survey-report {
            height: 1100px;
        }
    </style>
</head>
<body>

<div id="container" style="width: 859px">
    <div id="survey-report" class="page">
        <div id="client-thumb" style="margin-bottom:30px">
            <img height="200" src="{{ CommonHelpers::getFile($property->client_id, CLIENT_LOGO, $is_pdf) }}"/>
        </div>
        <div id="property-thumb" style="margin-bottom:20px">
            <img height="250" src="{{ CommonHelpers::getFile($property->id, PROPERTY_PHOTO, $is_pdf) }}"/>
        </div>
        <div id="asbestos-survey-report">
            <h2>Health & Safety Register <span><?= date("d/m/Y") ?></span></h2>
            <h2>{{$ss_ref}}</h2>

            <div style="margin-bottom:30px;" id="property-details1">
                <p><strong>{!! $property->name ?? '' !!}</strong></p>
                <p>{!! $property->propertyInfo->address1 ?? '' !!}</p>
                <p>{!! $property->propertyInfo->address2 ?? '' !!}</p>
                <p>{!! $property->propertyInfo->address3 ?? '' !!}</p>
                <p>{!! $property->propertyInfo->address4 ?? '' !!}</p>
                <p>{!! $property->propertyInfo->address5 ?? '' !!}</p>
                <p>{!! $property->propertyInfo->postcode ?? '' !!}</p>
            </div>
        </div>
        <br>

        @if(count($warning_message))
            <!-- warning message here -->
            @foreach($warning_message as $text)
                <div class="des-field mt-1"
                     style="background: #f2dede;border: 1px solid #eed3d7;color: #b94a48;padding: 10px;margin-top: 10px;float: left; margin-right: 10px; border-radius: 4px">
                    <strong><em>{{ $text }}</em></strong>
                </div>
            @endforeach
        @endif
        <div style="clear: both"></div>
        <div style="text-align: left; margin-top: 40px;">
            <p>IMPORTANT: The water management information is live, meaning that the system is constantly being
                updated and amended. Summaries created from information in either the health & safety register or management
                system will therefore only be valid for the time and date they are created.</p>
            <p>&nbsp;</p>
            <p>Summaries provide a limited overview of the data available, they are not designed to be used by those
                carrying out major refurbishment or works involving alterations to the fabric of a building or its
                services.</p>
            <p>&nbsp;</p>
            <p>Contact the Health & Safety Safety Team for more details.</p>
        </div>
    </div><!-- PAGE 01 -->


    <div id="watermark">
        <p style="font-style:italic;">
            Powered by </p>
        <div id="powered-by-thumb">
            <img src="{{public_path('img/shineCompliance.png')}}" height="50" alt="ShineCompliance Logo">
        </div>
    </div>
</div>
</body>
</html>

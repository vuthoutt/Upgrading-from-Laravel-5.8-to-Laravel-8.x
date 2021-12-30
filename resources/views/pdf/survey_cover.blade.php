<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" >
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{$survey->reference . " : " . isset($property->name) ? $property->name : "" }}</title>
    <style type="text/css">
        body {
            padding: 10px 30px 10px 20px;
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
        @if($survey->client_id == 5)
            <p style="font-style:italic;"> Surveyed by </p>
            <div style="display: block;height:180px;margin-bottom: 10px;">
                @if(CommonHelpers::checkFile($survey->client_id, CLIENT_LOGO))
                    <img style="display:inline-block;max-height: 180px;" height="180px" src="{{ CommonHelpers::getFile($survey->client_id, CLIENT_LOGO, $is_pdf) }}"/>
                <@endif

                @if($survey->client_id != 1 && CommonHelpers::checkFile($survey->client_id, UKAS_IMAGE))
                    <img style="display:inline-block;position: fixed;margin-left: 500px;margin-top: 20px;max-height: 90px;" height="90px" src="{{ CommonHelpers::getFile($survey->client_id, UKAS_IMAGE, $is_pdf) }}"/>
                <@endif
            </div>
        @else
            <div id="client-thumb" style="margin-bottom:30px">
                <img style="max-height: 200px;" height="200" src="{{ CommonHelpers::getFile($property->client_id, CLIENT_LOGO, $is_pdf) }}"/>
            </div>
        @endif

        <div id="property-thumb" style="margin-bottom:20px">
            @if(CommonHelpers::checkFile($survey->id, PROPERTY_SURVEY_IMAGE))
                <img style="max-height: 250px;" height="250" src="{{ CommonHelpers::getFile($survey->id, PROPERTY_SURVEY_IMAGE, $is_pdf) }}" alt="Property Survey Image"/>
            @elseif(CommonHelpers::checkFile($property->id, PROPERTY_PHOTO))
                <img style="max-height: 250px;" height="250" src="{{ CommonHelpers::getFile($property->id, PROPERTY_PHOTO, $is_pdf) }}" alt="Property Image"/>
            @endif
        </div>
        <div id="asbestos-survey-report">
            @if($survey)
                <h2>Asbestos Survey Report
                    <span>
                        @if(isset($survey->surveyDate))
                            @php
                                if ($survey->surveyDate->surveying_start_date == $survey->surveyDate->surveying_finish_date) {
                                    $date_title =  $survey->surveyDate->surveying_start_date;
                                } else {
                                    $date_title =    $survey->surveyDate->surveying_start_date . ' - ' . $survey->surveyDate->surveying_finish_date;
                                }

                            @endphp
                            {{$date_title}}
                        @endif
                    </span>
                </h2>

                <h3>{{$survey->survey_type_text}}</h3>
                @if($survey->project)
                    <h3>{{$survey->project->title}}</h3>
                    @if ($survey->project->reference)
                        <h3>{{"Project Reference Number - " . $survey->project->reference}}</h3>
                    @endif
                @endif
                <h3>{{$survey->survey_type_text . " - Report Reference - " . $survey->reference}}</h3>
            @else
                <h3 style="margin-bottom:30px;">Asbestos Remedial Action Summary</h3>
            @endif
            <div id="property-details1"  style="margin-top:30px;">
                <p><strong>{!!$property->property_reference!!}</strong></p>
                <p><strong>{!!$property->pblock!!}</strong></p>
                <p><strong>{!!$property->name!!}</strong></p>
                <p>
                    {!! implode(", ", array_filter([
                        optional($property->propertyInfo)->flat_number,
                        optional($property->propertyInfo)->building_name,
                        optional($property->propertyInfo)->street_number,
                        optional($property->propertyInfo)->street_name,
                        optional($property->propertyInfo)->town,
                        optional($property->propertyInfo)->address5,
                        optional($property->propertyInfo)->postcode
                    ]))!!}
                </p>
                <p>{{$property->postcode}}</p>
            </div>
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

                <div id="powered-by-thumb">
                    @if($survey->client_id == 5 || $survey->client_id == 1)
                    <div id="client-thumb" style="margin-top:90px;margin-left: 0px;">
                        <img style="max-height: 200px;" height="200" src="{{ CommonHelpers::getFile($property->client_id, CLIENT_LOGO, $is_pdf) }}"/>
                    </div>
                    @elseif ($survey->client_id == 4 || $survey->client_id == 3)
                    <p style="font-style:italic;margin-top: 100px;"> Surveyed by </p>
                    <div style="display: block;height:180px;margin-top: 10px;margin-bottom: 10px;">
                        @if(CommonHelpers::checkFile($survey->client_id, CLIENT_LOGO))
                            <img style="display:inline-block;max-height: 90px;" height="90px" src="{{ CommonHelpers::getFile($survey->client_id, CLIENT_LOGO, $is_pdf) }}"/>
                        @endif
                        @if(CommonHelpers::checkFile($survey->client_id, UKAS_IMAGE))
                            <img style="display:inline-block;margin-left: 700px;margin-top: -130px;max-height: 90px;" height="90px" src="{{ CommonHelpers::getFile($survey->client_id, UKAS_IMAGE, $is_pdf) }}"/>
                        @endif
                    </div>
                    @else
                    <p style="font-style:italic;margin-top: 100px;"> Surveyed by </p>
                    <div style="display: block;height:180px;margin-top: 10px;margin-bottom: 10px;">
                        @if(CommonHelpers::checkFile($survey->client_id, CLIENT_LOGO))
                            <img style="display:inline-block;max-height: 180px;" height="180px" src="{{ CommonHelpers::getFile($survey->client_id, CLIENT_LOGO, $is_pdf) }}"/>
                        @endif
                        @if(CommonHelpers::checkFile($survey->client_id, UKAS_IMAGE))
                            <img style="display:inline-block;margin-left: 50px;max-height: 180px;" height="180px" src="{{ CommonHelpers::getFile($survey->client_id, UKAS_IMAGE, $is_pdf) }}"/>
                        @endif
                    </div>
                    @endif

                    <div style="width: 100px;height:40px;margin-top: 20px;">
                        @if(CommonHelpers::checkFile($survey->client_id, PROPERTY_SURVEY_IMAGE) || CommonHelpers::checkFile($survey->client_id, PROPERTY_PHOTO))
                            @if($survey->client_id == 5)
                                <img style="margin-top: 75px;" src="{{public_path('img/shineAsbestosLogo.png')}}" height="40px" alt="ShineAsbestos Logo">
                            @else
                                <img src="{{public_path('img/shineAsbestosLogo.png')}}" height="40px" alt="ShineAsbestos Logo">
                        @endif
                        @else
                            @if($survey->client_id == 5)
                                <img style="margin-top: 75px;" src="{{public_path('img/shineAsbestosLogo.png')}}" height="40px" alt="ShineAsbestos Logo">
                            @else
                                <img src="{{public_path('img/shineAsbestosLogo.png')}}" height="40px" alt="ShineAsbestos Logo">
                            @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div><!-- PAGE 01 -->
</div><!--Container - set width -->

</body>
</html>

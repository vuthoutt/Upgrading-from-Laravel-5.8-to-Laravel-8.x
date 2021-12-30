{{-- <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> --}}
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" media="print" href="{{ asset('css/pdf.css') }}">
    <style type="text/css">
        body {
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            color: #666;
            margin: 19px;
            background: #FFF;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 0px;
            padding-bottom: 0px;
        }

        h2 {
            font-size: 35px;
            color: #909495;
            font-weight: normal;
            margin: 0px;
            padding: 0px;
        }

        h3 {
            font-size: 17px;
            padding: 0px;
            margin-top: 20px;
            margin-right: 0px;
            margin-bottom: 0px;
            margin-left: 0px;
        }

        h4 {
            font-size: 15px;
            margin-bottom: 0px;
            padding-bottom: 0px;
        }

        p.contents {
            margin: 0px;
            padding: 0px;
        }

        p.belowh1 {
            margin: 0px;
            padding: 0px;
            font-size: 20px;
        }

        p {
            margin-bottom: 8px;
            text-align: justify;
            margin-top: 0px;
            padding-top: 0px;
        }

        .header {
            width: 760px;
            text-align: right;
            margin-bottom: 10px;
        }

        .nobreak {
            page-break-inside: avoid;
        }

        .info {
            margin: 0 0 3px 0;
            float: left;
        }

        .info label {
            width: 166px;
            float: left;
        }

        .info span {
            width: 582px;
            float: left;
        }

        table.data tbody tr:nth-child(odd) {
            background: #FFF !important;
        }

        table.data tbody tr:nth-child(even) {
            background: #E5E5E5 !important;
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

        .paddingTopImg {
            padding-top: 3px;
        }

        .spanMiddle {
            height: 115px; /* image's height */
            /*display: table-cell;*/
            float: inherit;
            vertical-align: middle;
            font-weight: bold;
            font-size: medium;
            padding-left: 15px;
        }

        @media print {
            .element-that-contains-table {
                overflow: visible !important;
            }
        }
    </style>
</head>
<body>
<p id="pdf-title" style="display: none;">
    {{ ($summary_name ?? '').'_' . date("d_m_Y") . "-" . date("H_i") . "-UID" . sprintf("%03d", \Auth::user()->id) }}
</p>
<div class="container">
    <div class="container">
        <div id="tab1">
            <div>
                <table style="border:none;height: 115px;">
                    <tr>
                        <td>
                            <img height="115" src="{{ CommonHelpers::getFile(1, CLIENT_LOGO, $is_pdf) }}" alt="Client Image"/>
                        </td>
                        <td>
                        {{-- table header --}}
                        <span class="spanMiddle">shine<span style="font-weight: normal;">Asbestos</span>
                                @if ($summary_type == "priorityforaction")
                                     Priority for Action ACM Item Summary
                                @elseif ($summary_type == "volume")
                                     ACM Volume Summary
                                @elseif ($summary_type == "survey")
                                     Technical Managers Survey Summary
                                @elseif ($summary_type == "user")
                                     User Summary
                                @elseif ($summary_type == "risk")
                                    @if ($criteriaText == "All")
                                       @php $criteriaText = "Estate"; @endphp
                                    @elseif ($criteriaText == "Room/location")
                                        @php $criteriaText = "ACM Room/location";  @endphp
                                    @endif
                                     {{$criteriaText }} " Risk Overview Summary
                                @elseif ($summary_type == "reinspectionProgramme")
                                     Re-Inspection Programme Summary
                                @elseif ($summary_type == "actionRecommendation")
                                     Action/recommendation Summary
                               @else
                                     {{ ucfirst($summary_type) }} Risk Summary
                                @endif
                        </span>
                        </td>
                    </tr>
                </table>
                <div id="tableGenerate">
                        {{-- table content --}}
                        @yield('summary_table_content')
                        {{-- end table content --}}

                    {{-- table footer --}}
                    <br/> <br/>
                    <div style="page-break-inside: avoid">
                        <strong>IMPORTANT:</strong>
                        The asbestos management information is live, meaning that the system
                        is constantly being updated and amended. Summaries created from
                        information in either the asbestos register or management system
                        will therefore only be valid for the time and date they are created.
                        Summaries provide a limited overview of the data available, they are
                        not designed to be used by those carrying out major refurbishment or
                        works involving alterations to the fabric of a building or its
                        services. Contact the Asbestos Management Team for a more detailed
                        overview of the data available, they are not designed to be used by
                        those carrying out major refurbishment or works involving
                        alterations to the fabric of a building
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
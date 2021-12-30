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

        .topTitle {
            font-size: 1.17em;
            font-weight: bold;
            color: #333;
        }

        .topTitleSmall {
            font-size: 1.12em;
            color: #333;
            font-weight: bold;
        }

        .topTitleBig {
            font-size: 1.5em;
            color: #333;
            font-weight: bold;
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
    </style>
</head>
<body>

<div id="container" style="width: 859px">
    <h2>Fire Hazard Register</h2>
    <?php
    if (count($hazards)) {
        $hazardRegisterCount = 0;
        foreach ($hazards as $hazard) {
        if ($hazardRegisterCount % 2 == 0 && $hazardRegisterCount != 0) {
        ?>
        <div style="page-break-before: always;"></div><p style="margin-top:60px;">&nbsp;</p>
        <?php } elseif ($hazardRegisterCount == 0) { ?><?php } else { ?>
        <hr style="height:1px;border-width:0;color:gray;background-color:gray; margin-top: 5px; margin-bottom: 0;"/>
        <?php } ?>
        <div style="margin-top:30px; font-size: 8pt;">
            <div style="display:inline-block;width:64%;margin-bottom:5px;">
                <div style="margin-top:3px;">

                    <div style="display:inline-block;width:30%;">
                        <img style="max-height: 80px;" width="160" height="80" src="{{ CommonHelpers::getFile($hazard->id, HAZARD_LOCATION_PHOTO, $is_pdf) }}" />
                        <p style='margin-top:5px'>Location</p>
                    </div>
                    <div style="display:inline-block;width:30%;">
                        <img style="max-height: 80px;" width="160" height="80" src="{{ CommonHelpers::getFile($hazard->id, HAZARD_PHOTO, $is_pdf) }}" />
                        <p style='margin-top:5px'>Hazard</p>
                    </div>
                    <div style="display:inline-block;width:30%;">
                        <img style="max-height: 80px;" width="160" height="80" src="{{ CommonHelpers::getFile($hazard->id, HAZARD_ADDITION_PHOTO, $is_pdf) }}" />
                        <p style='margin-top:5px'>Additional</p>
                    </div>
                </div>

                <div style="margin-top:5px;">
                    <div>
                        <p style="display:inline-block;width:39%;">Hazard Reference: </p>
                        <p style="display:inline-block;width:59%;">
                            {{ $hazard->reference ?? '' }}
                        </p>
                    </div>
                </div>
                <div style="margin-top:5px;">
                    <div>
                        <p style="display:inline-block;width:39%;">Hazard Type: </p>
                        <p style="display:inline-block;width:59%;">
                            {{ $hazard->hazardType->description ?? '' }}
                        </p>
                    </div>
                </div>
                <div style="margin-top:5px;">
                    <div>
                        <p style="display:inline-block;width:39%;">Date Created: </p>
                        <p style="display:inline-block;width:59%;">
                            {{ $hazard->created_date ?? '' }}
                        </p>
                    </div>
                </div>
                <div style="margin-top:5px;">
                    <div>
                        <p style="display:inline-block;width:39%;">Floor: </p>
                        <p style="display:inline-block;width:59%;">
                            {{ $hazard->area->title_presentation ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                <div style="margin-top:5px;">
                    <div>
                        <p style="display:inline-block;width:39%;">Room: </p>
                        <p style="display:inline-block;width:59%;">
                            {{ $hazard->location->title_presentation ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                <div style="margin-top:5px;">
                    <div>
                        <p style="display:inline-block;width:39%;">Hazard Potential: </p>
                        <p style="display:inline-block;width:59%;">
                            {{ $hazard->hazardPotential->description ?? '' }}
                        </p>
                    </div>
                </div>
                <div style="margin-top:5px;">
                    <div>
                        <p style="display:inline-block;width:39%;">Likelihood of Harm: </p>
                        <p style="display:inline-block;width:59%;">
                            {{ $hazard->hazardLikelihoodHarm->description ?? '' }}
                        </p>
                    </div>
                </div>
                <div style="margin-top:5px;">
                    <div>
                        <p style="display:inline-block;width:39%;">Overall Risk Assessment: </p>
                        <p style="display:inline-block;width:59%;">
                            <span  class="badge {{ \CommonHelpers::getTotalHazardText($hazard->total_risk)['color'] }}" id="risk-color" style="width: 30px; color: {{\CommonHelpers::getTotalRiskHazardText($hazard->total_risk)['color']}} !important;">
                                {{ $hazard->total_risk ?? '' }}
                            </span>
                        </p>
                    </div>
                </div>
                <div style="margin-top:5px;">
                    <div>
                        <p style="display:inline-block;width:39%;">Actions/Recommendations: </p>
                        <p style="display:inline-block;width:59%;">
                            {{ $hazard->action_recommendations ?? '' }}
                        </p>
                    </div>
                </div>
                <div style="margin-top:5px;">
                    <div>
                        <p style="display:inline-block;width:39%;">Action Responsibility: </p>
                        <p style="display:inline-block;width:59%;">
                            {{ $hazard->actionResponsibility->description ?? '' }}
                        </p>
                    </div>
                </div>
                <div style="margin-top:5px;">
                    <div>
                        <p style="display:inline-block;width:39%;">Comments: </p>
                        <p style="display:inline-block;width:59%;">
                            {!! $hazard->comment ?? '' !!}
                        </p>
                    </div>
                </div>
                <!-- Misssing Data time stamp here -->
            </div>
        </div>
        <?php
        $hazardRegisterCount++;
        }//foreach
    } else {
        echo '<p style="margin-top:20px;margin-bottom:10px">There were no hazards present within the Fire Register at the time the export was requested.</p>';
    }//endif ?>
</div><!--Container - set width -->
</body>
</html>

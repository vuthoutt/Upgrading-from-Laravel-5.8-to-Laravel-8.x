<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <style type="text/css">
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 10pt;
            color: #575756;
        }

        .blue-text{color: #092591}
        .page {
            margin-top: 30px;
        }
        /*EndtableItems*/

        .textdecoration{
            border-bottom: 2px solid;
            margin-top: 80px;
        }

        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }

        table, td, th {
            border: 1px solid black;
        }

        table {
            color: black;
            margin-bottom: 20px;
            border-collapse: collapse;
            width: 100%;
            word-break: break-word;
        }

        th {
            word-break: break-word;
            background-color: #e6fbbb;
        }
        table tr td{
            font-size: 12px;
            padding-right: 15px;
            line-height: 1.5;
            word-spacing: 1px;
        }

        h2{
            font-size: 20px;
        }
        h4{
            font-size: 15px;
        }
        .text-title{
            margin-bottom: 10px;
            margin-left: 30px;
        }
        .header-report{
            width: 99%;
            margin-left: 0px;
            margin-top: 80px;
        }
        .scope_table{
            background-color: #dfecbd;
            color: #808275;
        }
        .page {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .alignment-title{
            background-color: #706f6f;
            color: white;
            height: 40px;
        }
        .alignment-text{
            padding-top: 13px;
            padding-left: 30px;
            font-size: 16px!important;
        }
        .mt30{
            margin-top: 5px!important;
            color: #575756;
            font-size: 10pt;
            line-height: 1.6;
            text-align: left;
            word-spacing: 1.0px;
        }
        .unset-border td {
            border: 0!important;
            color: #575756;
        }
        table.unset-border{
            border: 0!important;
            margin-bottom: 10px;
            color: #575756;
        }
        .tableGray tr th {
            background-color: #dadada!important;
            color: #575756;
        }
        .tableGray tr td {
            color: #575756;
        }
        th{
            font-size: 12px!important;
            font-weight: normal;
        }
        table tr td{
            font-size: 12px;
            padding-right: 15px;
            line-height: 1.5;
            word-spacing: 1px;
            word-break: break-word;
        }
        .tableItems tr td{
            word-break: break-word!important;
        }
        .system th{
            text-align: left;
            padding-left: 10px;
        }
        .system td{
            text-align: left;
            padding-left: 10px;
        }
        .resizetd td{
            height: 30px;
        }
        li{
            margin-top: 2px;
        }
    </style>
</head>
<body>

<div style="page-break-before: always;"></div>
<div style="margin-top:30px;">
    @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)
        @if($assessment->type == ASSESS_TYPE_WATER_RISK)
        <div class="textdecoration alignment-title">
            <p class="alignment-text">
                11.0 Example Water Pre-Planned Maintenance Schedule
            </p>
        </div>
        @else
            <div class="textdecoration alignment-title">
                <p class="alignment-text">
                    10.0 Example Water Pre-Planned Maintenance Schedule
                </p>
            </div>
        @endif
    @else
        @if($assessment->type == ASSESS_TYPE_WATER_RISK)
        <div class="textdecoration alignment-title">
            <p class="alignment-text">
                12.0 Example Water Pre-Planned Maintenance Schedule
            </p>
        </div>
        @else
            <div class="textdecoration alignment-title">
                <p class="alignment-text">
                    11.0 Example Water Pre-Planned Maintenance Schedule
                </p>
            </div>
        @endif
    @endif
    <div class="content" style="margin-top: 30px">
        <div class="tableItems tableGray">
            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:100%" class="system resizetd">
                <tr>
                    <th width="55%"  >Equipment Monitored</th>
                    <th  width="30%">Pre-Planned Maintenance Task</th>
                    <th >Timescale</th>
                </tr>
                <tr>
                    <td rowspan="5" style="border-bottom: 0!important;vertical-align: top;padding-top: 5px">
                        Cold Water Storage Tanks Including all Domestic, Laboratory, Pool Balance & Process Tanks
                    </td>
                    <td > Temperature Monitoring - Ball Valve </td>
                    <td style="padding: 4px">
                        <div style="background-color:#24387d;text-align:center;color: white" >
                            180
                        </div>
                    </td>
                </tr>
                <tr>
                    <td >Temperature Monitoring - Remote</td>
                    <td style="padding: 4px">
                        <div style="background-color:#24387d;text-align:center;color: white" >
                            180
                        </div>
                    </td>
                </tr>
                <tr>
                    <td > Visual Inspection </td>
                    <td style="padding: 4px">
                        <div style="background-color:#24387d;text-align:center;color: white" >
                            180
                        </div>
                    </td>
                </tr>
                <tr>
                    <td >Biological Monitoring</td>
                    <td style="padding: 4px">
                        <div style="background-color:#9d9d9c;text-align:center;color: white" >
                            As Required
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="border-top:0!important">Clean & Disinfection</td>
                    <td style="padding: 4px">
                        <div style="background-color:#9d9d9c;text-align:center;color: white" >
                            As Required
                        </div>
                    </td>
                </tr>
                <tr>
                    <td rowspan="6" style="vertical-align: top;padding-top: 5px">
                        Calorifiers include Storage Vessels, Buffer Vessels, Direct Gas Fired & Plate Heat Exchangers. Temperature Monitoring - Manual
                        Where Multiple Calorifiers are linked the monitoring must include the Flow & Return of Each Unit.
                    </td>
                    <td >
                        Temperature Monitoring - Manual
                    </td>
                    <td style="padding: 4px">
                        <div style="background-color:#f7a416;text-align:center;color: white" >
                            030
                        </div>
                    </td>
                </tr>
                <tr>
                    <td >Temperature Monitoring - Auto</td>
                    <td style="padding: 4px">
                        <div style="background-color:#f7a416;text-align:center;color: white" >
                            030
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Visual Inspection</td>
                    <td style="padding: 4px">
                        <div style="background-color:#f1e511;text-align:center;color: black" >
                            090
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Blowdown</td>
                    <td style="padding: 4px">
                        <div style="background-color:#f1e511;text-align:center;color: black" >
                            090
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Biological Monitoring
                    </td>
                    <td style="padding: 4px">
                        <div style="background-color:#9d9d9c;text-align:center;color: white" >
                            As Required
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Pasteurisation/disinfection
                    </td>
                    <td style="padding: 4px">
                        <div style="background-color:#9d9d9c;text-align:center;color: white" >
                            As Required
                        </div>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" style="vertical-align: top;">Point of Use Heaters (<15 litres)</td>
                    <td>Temperature Monitoring</td>
                    <td style="padding: 4px">
                        <div style="background-color:#f7a416;text-align:center;color: white" >
                            030
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Visual Inspection</td>
                    <td style="padding: 4px">
                        <div style="background-color:#f1e511;text-align:center;color: black" >
                            090
                        </div>
                    </td>
                </tr>
                <tr>
                    <td rowspan="4" style="vertical-align: top;padding-top: 5px">
                        Hot Water Services & Cold Water Service Distribution. The Sentinel Taps must be representative of
                        the system as a whole & must be direct from the system, not from TMVs, or Mixer Taps.
                    </td>
                    <td>Temperature Monitoring - Sentinels</td>
                    <td style="padding: 4px">
                        <div style="background-color:#f7a416;text-align:center;color: white" >
                            030
                        </div>
                    </td>
                </tr>
                <tr>
                    <td >Biological Monitoring</td>
                    <td style="padding: 4px">
                        <div style="background-color:#9d9d9c;text-align:center;color: white" >
                            As Required
                        </div>
                    </td>
                </tr>
                <tr>
                    <td >Clean & Disinfection</td>
                    <td style="padding: 4px">
                        <div style="background-color:#9d9d9c;text-align:center;color: white" >
                            As Required
                        </div>
                    </td>
                </tr>
                <tr>
                    <td >Dead-leg/little Used Area Flushing</td>
                    <td style="padding: 4px">
                        <div style="background-color:#e30613;text-align:center;color: white" >
                            007
                        </div>
                    </td>
                </tr>
                <tr>
                    <td rowspan="3" style="vertical-align: top;padding-top: 5px">
                        Thermostatic Mixing Valves including Shower Mixers.
                    </td>
                    <td>Temperature Monitoring</td>
                    <td style="padding: 4px">
                        <div style="background-color:#f7a416;text-align:center;color: white">
                            030
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Visual Inspection</td>
                    <td style="padding: 4px">
                        <div style="background-color:#9d9d9c;text-align:center;color: white" >
                            365
                        </div>
                    </td>
                </tr>
                <tr>
                    <td >Servicing/failsafe</td>
                    <td style="padding: 4px">
                        <div style="background-color:#9d9d9c;text-align:center;color: white" >
                            365
                        </div>
                    </td>
                </tr>
                <tr>
                    <td rowspan="5" style="vertical-align: top;padding-top: 5px">Shower Heads & Hoses.</td>
                    <td>Temperature Monitoring</td>
                    <td style="padding: 4px">
                        <div style="background-color:#f7a416;text-align:center;color: white" >
                            030
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>General Inspections</td>
                    <td style="padding: 4px">
                        <div style="background-color:#f1e511;text-align:center;color: black" >
                            090
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Biological Monitoring</td>
                    <td style="padding: 4px">
                        <div style="background-color:#9d9d9c;text-align:center;color: white" >
                            As Required
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Shower Head Clean & Descale</td>
                    <td style="padding: 4px">
                        <div style="background-color:#9d9d9c;text-align:center;color: white" >
                            As Required
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Shower Head Flushing - Little Used</td>
                    <td style="padding: 4px">
                        <div style="background-color:#e30613;text-align:center;color: white" >
                            007
                        </div>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" style="vertical-align: top;padding-top: 5px">Air Conditioning/handling</td>
                    <td>Inspection/cleaning of Glass Traps</td>
                    <td style="padding: 4px">
                        <div style="background-color:#f1e511;text-align:center;color: black" >
                            090
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Inspection of Drip Trays & Batteries</td>
                    <td style="padding: 4px">
                        <div style="background-color:#f1e511;text-align:center;color: black" >
                            090
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;padding-top: 5px">Recirculating Pumps</td>
                    <td>Alternate Usage, if fitted</td>
                    <td style="padding: 4px">
                        <div style="background-color:#e30613;text-align:center;color: white" >
                            007
                        </div>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>
</body>

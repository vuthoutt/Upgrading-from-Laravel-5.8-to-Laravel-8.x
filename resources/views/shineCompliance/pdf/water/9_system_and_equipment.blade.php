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
<div style="margin-top:30px;">
    @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)
        @if($assessment->type == ASSESS_TYPE_WATER_RISK)
        <div class="textdecoration alignment-title">
            <p class="alignment-text">8.0 Summary of Water System(s)</p>
        </div>
        @else
        <div class="textdecoration alignment-title">
            <p class="alignment-text">7.0 Summary of Water System(s)</p>
        </div>
        @endif
    @else
        @if($assessment->type == ASSESS_TYPE_WATER_RISK)
        <div class="textdecoration alignment-title">
            <p class="alignment-text">9.0 Summary of Water System(s)</p>
        </div>
        @else
        <div class="textdecoration alignment-title">
            <p class="alignment-text">8.0 Summary of Water System(s)</p>
        </div>
        @endif
    @endif
    <div class="content">
        <div class="mt30">
            <p>
            @if(count($systems) == 0)
                No water systems were identfied at the property.
            @else
                A total of {{ count($systems) }} water systems were assessed as part of the risk assessment.
            @endif
            </p>
        </div>
    </div>
    @foreach($systems as $system)
        <h3 class="mt30">{{ $system->name ?? '' }} - {{ $system->systemType->description ?? '' }}</h3>
        <div class="tableItems tableGray" style="margin-top: 10px">
            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:100%;" class="system">
                <thead>
                <tr >
                    <th width="15%" colspan="5">System Comments</th>
                </tr>
                <tr >
                    <td colspan="5">{!! $system->comment ?? 'No Comment.' !!}</td>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th width="20%">Equipment Name (Nearest)</th>
                        <th width="20%">Equipment Type</th>
                        <th width="20%">Floor</th>
                        <th width="20%">Room</th>
                        <th width="20%">Photograph</th>
                    </tr>
                    @if(count($system->equipments))
                        @php $has_nearest = false @endphp
                        @foreach($system->equipments as $equipment)
                            @if(isset($equipment->equipmentConstruction->nearest_furthest) and isset($equipment->equipmentConstruction->sentinel))
                                @if(($equipment->equipmentConstruction->sentinel == 1) and ($equipment->equipmentConstruction->nearest_furthest == NEAREST_DROPDOWN))
                                @php $has_nearest = true @endphp
                                <tr>
                                    <td>
                                        {{ $equipment->name ?? '' }}
                                    </td>
                                    <td>{{ $equipment->equipmentType->description ?? '' }}</td>
                                    <td>{{ $equipment->area->title_presentation ?? 'N/A' }}</td>
                                    <td>{{ $equipment->location->title_presentation ?? 'N/A' }}</td>
                                    <td><img style="height: 100px; width: 150px; text-align: center;margin-bottom: 10px;margin-top: 5px" src="{{ ComplianceHelpers::getSystemFile($equipment->id ?? '', EQUIPMENT_LOCATION_PHOTO) }}"></td>
                                </tr>
                                @endif
                            @endif
                        @endforeach
                        @if(!$has_nearest)
                            <tr><td style="text-align: left" colspan="5">No Information.</td></tr>
                        @endif
                    @else
                        <tr><td style="text-align: left" colspan="5">No Information.</td></tr>
                    @endif
                    <tr>
                        <th width="20%">Equipment Name (Furthest)</th>
                        <th width="20%">Equipment Type</th>
                        <th width="20%">Floor</th>
                        <th width="20%">Room</th>
                        <th width="20%">Photograph</th>
                    </tr>
                    @if(count($system->equipments))
                        @php $has_furthest = false @endphp
                        @foreach($system->equipments as $equipment)
                            @if(isset($equipment->equipmentConstruction->nearest_furthest) and isset($equipment->equipmentConstruction->sentinel))
                                @if(($equipment->equipmentConstruction->sentinel == 1) and ($equipment->equipmentConstruction->nearest_furthest == FURTHEST_DROPDOWN))
                                @php $has_furthest = true @endphp
                                <tr>
                                    <td>
                                        {{ $equipment->name ?? '' }}
                                    </td>
                                    <td>{{ $equipment->equipmentType->description ?? '' }}</td>
                                    <td>{{ $equipment->area->title_presentation ?? 'N/A' }}</td>
                                    <td>{{ $equipment->location->title_presentation ?? 'N/A' }}</td>
                                    <td><img style="height: 100px; width: 150px; text-align: center;margin-bottom: 10px;margin-top: 5px" src="{{ ComplianceHelpers::getSystemFile($equipment->id ?? '', EQUIPMENT_LOCATION_PHOTO) }}"></td>
                                </tr>
                                @endif
                            @endif
                        @endforeach
                        @if(!$has_furthest)
                            <tr><td style="text-align: left" colspan="5">No Information.</td></tr>
                        @endif
                    @else
                        <tr><td style="text-align: left" colspan="5">No Information.</td></tr>
                    @endif
                </tbody>

            </table>
        </div>
    @endforeach
</div>

</body>

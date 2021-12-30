<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <style type="text/css">
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 10pt;
        }

        .mt30{
            margin-left: 20px;
            margin-top: 10px;
            color: black;
            font-size: 10pt;
            line-height: 1.6;
            text-align: left;
            word-spacing: 1.0px;
        }
        .tableCover table, td, th {
            border: 1px solid black;
        }
        .tableCover table {
            color: black;
            border-collapse: collapse;
            width: 100%;
        }

        .tableCover th {
            background-color: #e6fbbb;
            height: 50px;
        }
        .tableCover tr {
            height: 30px;
        }
        .tableCover td {
            padding-left: 20px;
            padding-right: 20px;
        }

        .tableItems table{
            margin-top: 0px;
            width: 98%;
            margin-left:20px;
        }
        .tableItems th {
            text-align: left;
            padding-left: 10px;
            color: #ffffff;
            height: 10px;
            font-size: 12px!important;
            font-weight: normal;
        }
        #red tr th{
            background-color: #ff0000;
        }
        .tableItems tr {
            height: 10px!important;
        }
        .tableItems td {
            padding-left: 10px;
            padding-right: 20px;
        }
        #tableYellow tr th{
            background-color: #ffc000;
        }
        .tableGreen tr th {
            background-color: #026735;
        }
        .tableBlue th {
            background-color: #1e71b8;
        }
        #tableYesNo th {
            text-align: left;
            padding-left: 10px;
            color: black;
            background-color: #e6fbbb;
            height: 50px;
        }
        #tableYesNo td span{
            background-color: #ffff00;
        }
        .colspan{
            height: 120px;
            position: relative;
        }
        #tableYesNo tr th{
            height: 150px;
        }

        /*.textdecoration{*/
        /*    border-bottom: 2px solid #1e71b8 ;*/
        /*    margin-left: 20px;*/
        /*    margin-top: 80px;*/
        /*}*/
        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }
        .tableItems tr td{
            word-break: break-word;
        }
        table, td, th {
            border: 1px solid black;
        }

        table {
            color: black;
            margin-bottom: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #e6fbbb;
        }
        table tr td{
            font-size: 12px;
            padding-left: 15px;
            padding-right: 15px;
            line-height: 1.5;
            word-spacing: 1px;
        }

        .tableAp3 tr td {
            text-align: left;
            padding-left: 10px;
        }
        .tableAp3 tr th {
            font-size: 14px;
            text-align: left;
            padding-left: 10px;
        }
        .text-title{
            margin-bottom: 10px;
            margin-left: 30px;
        }

        /*p{*/
        /*    font-size: 20px;*/
        /*}*/
        h3{
            font-size: 15px;
        }
        .scope_table{
            background-color: #dfecbd!important;
            color: rgba(0, 0, 0, 0.81) !important;
        }
        p{
            text-align: justify!important;
        }
        .textdecoration{
            border-bottom: 2px solid;
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
        .ml-2{
            margin-left: 20px;
        }
        .image-item{
            width: 160px;
            height: 130px;
            object-fit:cover;
        }
    </style>
</head>
<body>
<div style="margin-top:30px;" class="page">
    @if(count($incoming_mains))
        @foreach($incoming_mains as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Incoming Main</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($miscs) || count($mixer_outlets) || count($cold_water_outlets)
                    || count($hot_water_outlets) || count($point_use_heaters) || count($hot_water_services) || count($cold_storage_tanks)
                    || count($boilers) || count($calorifires) || count($hot_heaters) || count($instants)
                    || count($combined_outlet_hots) || count($combined_outlet_mixers)
                    || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs)
                    )
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach


    @endif

    @if(count($cold_storage_tanks))
        @foreach($cold_storage_tanks as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Cold Water Storage Tank</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($miscs) || count($mixer_outlets) || count($cold_water_outlets)
                    || count($hot_water_outlets) || count($point_use_heaters) || count($hot_water_services)
                    || count($boilers) || count($calorifires) || count($hot_heaters) || count($instants)
                    || count($combined_outlet_hots) || count($combined_outlet_mixers)
                    || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs)
                    )
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
        {{--    @else--}}
        {{--        @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">9.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @else--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">10.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @endif--}}
        {{--        <div style="margin-top: 20px">--}}
        {{--            <div style="width:99%;">--}}
        {{--                <h4>Cold Water Storage Tank</h4>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div style="page-break-after: always;"></div>--}}
    @endif

    @if(count($hot_water_services))
        @foreach($hot_water_services as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Hot Water Services</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($miscs) || count($mixer_outlets) || count($cold_water_outlets)
                    || count($hot_water_outlets) || count($point_use_heaters) || count($boilers)
                    || count($calorifires) || count($hot_heaters) || count($instants)
                    || count($combined_outlet_hots) || count($combined_outlet_mixers)
                    || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs)
                    )
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
        {{--    @else--}}
        {{--        @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">9.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @else--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">10.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @endif--}}
        {{--        <div style="margin-top: 20px">--}}
        {{--            <div style="width:99%;">--}}
        {{--                <h4>Hot Water Services</h4>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div style="page-break-after: always;"></div>--}}
    @endif

    @if(count($point_use_heaters))
        @foreach($point_use_heaters as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Point of Use Heater</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($miscs) || count($mixer_outlets) || count($cold_water_outlets)
                    || count($hot_water_outlets) || count($boilers) || count($calorifires) || count($hot_heaters) || count($instants)
                || count($combined_outlet_hots) || count($combined_outlet_mixers)
                || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
        {{--    @else--}}
        {{--        @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">9.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @else--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">10.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @endif--}}
        {{--        <div style="margin-top: 20px">--}}
        {{--            <div style="width:99%;">--}}
        {{--                <h4>Point of Use Heater</h4>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div style="page-break-after: always;"></div>--}}
    @endif

    @if(count($hot_water_outlets))
        @foreach($hot_water_outlets as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Hot Water Outlet</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($miscs) || count($mixer_outlets) || count($cold_water_outlets)
                    || count($boilers) || count($calorifires) || count($hot_heaters) || count($instants)
                || count($combined_outlet_hots) || count($combined_outlet_mixers)
                || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
        {{--    @else--}}
        {{--        @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">9.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @else--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">10.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--            @endif--}}
        {{--        <div style="margin-top: 20px">--}}
        {{--            <div style="width:99%;">--}}
        {{--                <h4>Hot Water Outlet</h4>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div style="page-break-after: always;"></div>--}}
    @endif

    @if(count($cold_water_outlets))
        @foreach($cold_water_outlets as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Cold Water Outlet</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($mixer_outlets) || count($boilers) || count($miscs)
                ||  count($boilers) || count($calorifires) || count($hot_heaters) || count($instants)
                || count($combined_outlet_hots) || count($combined_outlet_mixers)
                || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
        {{--    @else--}}
        {{--        @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">9.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @else--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">10.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @endif--}}
        {{--        <div style="margin-top: 20px">--}}
        {{--            <div style="width:99%;">--}}
        {{--                <h4>Cold Water Outlet</h4>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div style="page-break-after: always;"></div>--}}
    @endif

    @if(count($mixer_outlets))
        @foreach($mixer_outlets as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Mixer Outlet</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($miscs) || count($boilers) || count($calorifires) || count($hot_heaters) || count($instants)
                    || count($combined_outlet_hots) || count($combined_outlet_mixers)
                    || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif

        @endforeach
        {{--    @else--}}
        {{--        @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">9.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @else--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">10.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @endif--}}
        {{--        <div style="margin-top: 20px">--}}
        {{--            <div style="width:99%;">--}}
        {{--                <h4>Mixer Outlet</h4>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div style="page-break-after: always;"></div>--}}
    @endif

    @if(count($miscs))
        @foreach($miscs as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Miscellaneous Equipment</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($boilers) || count($calorifires) || count($hot_heaters) || count($instants)
                || count($combined_outlet_hots) || count($combined_outlet_mixers)
                || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
        {{--    @else--}}
        {{--        @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">9.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @else--}}
        {{--            <div class="textdecoration alignment-title">--}}
        {{--                <p class="alignment-text">10.0 Equipment Register</p>--}}
        {{--            </div>--}}
        {{--        @endif--}}
        {{--        <div style="margin-top: 20px">--}}
        {{--            <div style="width:99%;">--}}
        {{--                <h4>Miscellaneous Equipment</h4>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div style="page-break-after: always;"></div>--}}
    @endif

    @if(count($boilers))
        @foreach($boilers as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Boiler (Combi) Equipment</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($calorifires) || count($hot_heaters) || count($instants) || count($combined_outlet_hots) || count($combined_outlet_mixers)
                || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
    @endif

    @if(count($calorifires))
        @foreach($calorifires as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Calorifier/Cylinder Equipment</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($hot_heaters) || count($instants) || count($combined_outlet_hots) || count($combined_outlet_mixers)
                    || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
    @endif

    @if(count($hot_heaters))
        @foreach($hot_heaters as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Hot Water Storage Heater Equipment</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($instants) || count($combined_outlet_hots) || count($combined_outlet_mixers)
                    || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
    @endif

    @if(count($instants))
        @foreach($instants as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Instant Water Heater Equipment</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($combined_outlet_hots) || count($combined_outlet_mixers)
                    || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
    @endif

    @if(count($combined_outlet_hots))
        @foreach($combined_outlet_hots as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Combined Outlets Hot Equipment</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($combined_outlet_mixers) || count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
    @endif

    @if(count($combined_outlet_mixers))
        @foreach($combined_outlet_mixers as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Combined Outlets Mixer Equipment</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($combined_outlet_colds) || count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
    @endif

    @if(count($combined_outlet_colds))
        @foreach($combined_outlet_colds as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Combined Outlets Cold Equipment</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($combined_outlet_cold_and_hots) || count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
    @endif

    @if(count($combined_outlet_cold_and_hots))
        @foreach($combined_outlet_cold_and_hots as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Combined Outlets Hot and Cold Equipment</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @else
                @if(count($tmvs))
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endforeach
    @endif

    @if(count($tmvs))
        @foreach($tmvs as $equipment)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">{{ isset($new_title['equiment']) ? $new_title['equiment'] : '' }}.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4 class="ml-2">Thermostatic Mixing Valve (TMV) Equipment</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    @endif
</div>

</body>

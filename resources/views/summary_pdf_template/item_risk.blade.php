@extends('summary_pdf_template.index')
@section('summary_table_content')
@php
if ($summary_type == "priorityforaction") {
    $riskTitle = "Priority for Action ACM Item Summary (" . count($items) . ")";
} else {
    $riskTitle = ucfirst($summary_type) . " Risk (" . count($items) . ")";
}
@endphp
<table width="98%" style="background:#57585A;color:#FFF;text-align:center;margin:0;font-size: 12px;font-family: Arial, Helvetica, sans-serif;" CELLPADDING=1 CELLSPACING=1>
        <tr>
            <td><strong style="color:#FFF">{{ $riskTitle }}</strong></td>
        </tr>
</table>
<table width="98%" style="margin-top:0;border-top: none;font-size: 12px;font-family: Arial, Helvetica, sans-serif;" class="data" BORDER=1 CELLPADDING=1
       CELLSPACING=1 RULES=COLS FRAME=BOX>
    <thead>
    <tr style="background:#D8D8D8;margin:0;">
        <td width="14%" align="center"><strong>Item Ref.</strong></td>
        @switch ($criteria)
            @case("estate")
            @case("zone")
                <td align="center"><strong> Property Name </strong></td>
                <td align="center"><strong> Room/location Ref. </strong></td>
                @break
            @case("property")
                <td align="center"><strong> Floor/area Ref. </strong></td>
                <td align="center"><strong> Room/location Ref. </strong></td>
                @break
            @case("areafloor")
            @case("roomlocation")
                <td align="center"><strong> Room/location Ref. </strong></td>
                @break
            @endswitch
        <td width="33%" align="center"><strong>Product/debris Type </strong></td>
        @switch ($summary_type)
            @case("riskassessment")
                <td style="margin:0;" width="7%" align="center"><strong>MAS </strong></td>
                <td style="margin:0;" width="7%" align="center"><strong>PAS </strong></td>
                <td style="margin:0;" width="7%" align="center"><strong>OAS </strong></td>
                @break
            @case("priorityforaction")
                <td style="margin:0;" width="7%" align="center"><strong>PfA </strong></td>
                @break
            @case("volume")
                @break
            @default
                <td style="margin:0;" width="7%" align="center"><strong>PAS </strong></td>
                @break
            @endswitch
    </tr>
    </thead>
    <tbody>
        {{ \CommonHelpers::pdfDisplayRow($items, $criteria, $risk, $riskScore, $numberItem, $summary_type, null, null, $is_pdf ) }}
    </tbody>
</table>
<div style="padding: 20px 0 0 0;">
    @if($summary_type == "riskassessment")
        <div class="row">
            <div class="col-2">
                <strong>PAS, MAS:</strong>
            </div>
            <div class="col-8">
                <img class="paddingTopImg"
                     src="{{CommonHelpers::getAssetFile('/img/ass-yellow.png', $is_pdf) }}"/> &nbsp;Very Low (<img
                    class="paddingTopImg" src="{{CommonHelpers::getAssetFile('/img/le_grey.png', $is_pdf) }}"
                    height="10"/>4) &nbsp;&nbsp;<img class="paddingTopImg"
                                                     src="{{CommonHelpers::getAssetFile('/img/ass-orange.png', $is_pdf) }}"/> &nbsp;Low (5-6) &nbsp;&nbsp;
                <img class="paddingTopImg"
                     src="{{CommonHelpers::getAssetFile('/img/ass-brown.png', $is_pdf) }}"/> &nbsp;Medium (7-9) &nbsp;&nbsp;
                <img class="paddingTopImg"
                     src="{{CommonHelpers::getAssetFile('/img/ass-red.png', $is_pdf) }}"/> &nbsp;High (10<img
                    class="paddingTopImg" src="{{CommonHelpers::getAssetFile('/img/ge_grey.png', $is_pdf) }}"
                    height="10"/>) &nbsp;&nbsp;
            </div>
        </div>
        <div class="row">
            <div class="col-2">
                <strong>OAS:</strong>
            </div>
            <div class="col-8">
                <img class="paddingTopImg" src="{{CommonHelpers::getAssetFile('/img/ass-yellow.png', $is_pdf) }}"/> &nbsp;Very Low (<img class="paddingTopImg" src="{{CommonHelpers::getAssetFile('/img/le_grey.png', $is_pdf) }}" height="10"/>9)
                &nbsp;&nbsp;<img class="paddingTopImg" src="{{CommonHelpers::getAssetFile('/img/ass-orange.png', $is_pdf) }}"/> &nbsp;Low (10-13) &nbsp;&nbsp;
                <img class="paddingTopImg"
                     src="{{CommonHelpers::getAssetFile('/img/ass-brown.png', $is_pdf) }}"/> &nbsp;Medium (14-19) &nbsp;&nbsp;
                <img class="paddingTopImg"
                     src="{{CommonHelpers::getAssetFile('/img/ass-red.png', $is_pdf) }}"/> &nbsp;High (20<img
                    class="paddingTopImg" src="{{CommonHelpers::getAssetFile('/img/ge_grey.png', $is_pdf) }}"
                    height="10"/>) &nbsp;
            </div>
        </div>&nbsp;
    @elseif ($summary_type == "priorityforaction")

                <img class="paddingTopImg"
             src="{{CommonHelpers::getAssetFile('/img/ass-yellow.png', $is_pdf) }}"/> &nbsp;Very Low (<img
                class="paddingTopImg" src="{{CommonHelpers::getAssetFile('/img/le_grey.png', $is_pdf) }}"
                height="10"/>9) &nbsp;&nbsp;<img class="paddingTopImg"
                                                 src="{{CommonHelpers::getAssetFile('/img/ass-orange.png', $is_pdf) }}"/> &nbsp;Low (10-13) &nbsp;&nbsp;
        <img class="paddingTopImg"
             src="{{CommonHelpers::getAssetFile('/img/ass-brown.png', $is_pdf) }}"/> &nbsp;Medium (14-19) &nbsp;&nbsp;
        <img class="paddingTopImg"
             src="{{CommonHelpers::getAssetFile('/img/ass-red.png', $is_pdf) }}"/> &nbsp;High (20<img
                class="paddingTopImg" src="{{CommonHelpers::getAssetFile('/img/ge_grey.png', $is_pdf) }}"
                height="10"/>) &nbsp;&nbsp;
    @else
        <img class="paddingTopImg"
             src="{{CommonHelpers::getAssetFile('/img/ass-yellow.png', $is_pdf) }}"/> &nbsp;Very Low (<img
                class="paddingTopImg" src="{{CommonHelpers::getAssetFile('/img/le_grey.png', $is_pdf) }}"
                height="10"/>4) &nbsp;&nbsp;<img class="paddingTopImg"
                                                 src="{{CommonHelpers::getAssetFile('/img/ass-orange.png', $is_pdf) }}"/> &nbsp;Low (5-6) &nbsp;&nbsp;
        <img class="paddingTopImg"
             src="{{CommonHelpers::getAssetFile('/img/ass-brown.png', $is_pdf) }}"/> &nbsp;Medium (7-9) &nbsp;&nbsp;
        <img class="paddingTopImg"
             src="{{CommonHelpers::getAssetFile('/img/ass-red.png', $is_pdf) }}"/> &nbsp;High (10<img
                class="paddingTopImg" src="{{CommonHelpers::getAssetFile('/img/ge_grey.png', $is_pdf) }}"
                height="10"/>) &nbsp;&nbsp;
    @endif
</div>
@endsection

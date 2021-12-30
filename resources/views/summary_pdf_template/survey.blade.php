@extends('summary_pdf_template.index')
@section('summary_table_content')
<table width="98%" style="background:#57585A;color:#FFF;text-align:center;margin:0"
       CELLPADDING=1 CELLSPACING=1>
    <tr>
        <td><strong style="color:#FFF;;font-size: 12px;font-family: Arial, Helvetica, sans-serif;">Technical Managers Survey Summary</strong></td>
    </tr>
</table>
<table width="98%" style="margin-top:0;border-top: none;;font-size: 12px;font-family: Arial, Helvetica, sans-serif;" class="data" BORDER=1 CELLPADDING=1
       CELLSPACING=1 RULES=COLS FRAME=BOX>
    <thead>
    <tr style="background:#D8D8D8;margin:0;;font-size: 12px;font-family: Arial, Helvetica, sans-serif;">
        <td width="7%" align="center"><strong>Room</strong></td>
        <td width="7%" align="center"><strong>Floor</strong></td>
        <td width="8%" align="center"><strong>Room Description</strong></td>
        <td width="10%" align="center"><strong>Room Comments</strong></td>
        <td width="10%" align="center"><strong>Specific Location</strong></td>
        <td width="10%" align="center"><strong>Item Ref.</strong></td>
        <td width="10%" align="center"><strong>Sample Reference</strong></td>
        <td width="15%" align="center"><strong>Asbestos Type</strong></td>
        <td width="8%" align="center"><strong>Extent</strong></td>
        <td width="15%" align="center"><strong>Item Comments</strong></td>
    </tr>
    </thead>
    <tbody>
    @if(!is_null($items) and count($items) > 0)
        @foreach($items as $item)
        <tr>
            <td align="left" style="padding-left:5px;">{{ $item->location->location_reference ?? '' }}</td>
            <td align="left" style="padding-left:5px;">{{ $item->area->area_reference ?? '' }}</td>
            <td align="left" style="padding-left:5px;">{{ $item->location->description ?? '' }}</td>
            <td align="left" style="padding-left:5px;">{!! $item->location->locationInfo->comments ?? '' !!}</td>
            <td align="left" style="padding-left:5px;">{{ $item->specificLocationView->specific_location ?? 'N/A' }}</td>
            <td align="left" style="padding-left:5px;">{{ $item->name ?? 'N/A' }} {{ (optional($item->sample)->original_item_id == $item->record_id) ? '(OS)' : ''}}</td>
            <td align="left" style="padding-left:5px;">{{ $item->sample->reference ?? 'N/A' }}</td>
            <td align="left" style="padding-left:5px;">{{ $item->state == 1 ? 'No ACM Detected' : ($item->asbestosTypeView->asbestos_type ?? '') }}</td>
            <td align="left" style="padding-left:5px;">{{ (isset($item->itemInfo->extent) and $item->itemInfo->extent) != '' ? (($item->itemInfo->extent ?? '').' '. ($item->extentView->extent ?? '')) : '' }}</td>
            <td align="left" style="padding-left:5px;">{!! $item->itemInfo->comments ?? 'N/A' !!}</td>
        </tr>
        @endforeach
    @else
       <tr><td colspan='10' align='center'><strong>No data found</strong></td></tr>
    @endif

    </tbody>
</table>
@endsection
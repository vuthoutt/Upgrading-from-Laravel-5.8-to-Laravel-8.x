@extends('tables.main_table', [
        'header' => $header
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td>{{ $dataRow->name }}</td>
            <td>{{ $dataRow->reference }}</td>
            <td>{{ $dataRow->category }}</td>
            <td style="width: 70px !important">
                {!!
                    \CommonHelpers::getDocumentsViewing(0,
                    route('shineCompliance.document.view',['id'=>$dataRow->id,'type'=>VIEW_COMPLIANCE_DOCUMENT]),
                    route('shineCompliance.document.download',['id'=>$dataRow->id,'type'=>DOWNLOAD_COMPLIANCE_DOCUMENT]))
                !!}</td>
            <td>{{ date("d/m/Y", $dataRow->date ?? '')}}</td></tr>
        @endforeach
    @endif
@overwrite

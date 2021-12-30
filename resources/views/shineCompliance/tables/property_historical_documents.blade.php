@extends('shineCompliance.tables.main_table', [
        'header' => ['Document Name','Document Reference','Status','File','Date Completed'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a href="javascript:void(0)" data-toggle="modal" data-target="#property-document-edit" data-url="{{route('shineCompliance.property.post_add.historical_documents',['document_id'=>$dataRow->id])}}"
                   data-name="{{$dataRow->name ?? ''}}" data-type="{{$dataRow->type ?? ''}}" data-property_id="{{$dataRow->property_id ?? ''}}" data-date="{{$dataRow->date ?? ''}}"
                   data-id="{{$dataRow->id ?? ''}}"
                >{{$dataRow->name ?? ''}}</a></td>
            <td>{{$dataRow->reference}}</td>
            <td>New Document</td>
            <td style="width: 70px !important">
                {!!
                    \CommonHelpers::getDocumentsViewing($dataRow->status,
                    route('shineCompliance.document.view',['id'=>$dataRow->id,'type'=>VIEW_COMPLIANCE_HISTORICAL_DOCUMENT]),
                    route('shineCompliance.document.download',['id'=>$dataRow->id,'type'=>DOWNLOAD_COMPLIANCE_HISTORICAL_DOCUMENT]))
                !!}</td>
            <td>{{$dataRow->date ?? ''}}</td>
        </tr>
        @endforeach
    @endif
@overwrite

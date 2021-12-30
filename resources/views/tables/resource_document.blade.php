@extends('tables.main_table', [
        'header' => ["Title", "File", "Last Revision"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        @if(in_array($dataRow->id , $list_doc_permission))
        <tr>
            @if(in_array($dataRow->id , $list_doc_update_permission))
            <td><a data-id="{{ $dataRow->id }}" data-toggle="modal" href="#historic-edit-{{$dataRow->id}}">{{ $dataRow->name }}</a></td>
            @else
            <td>{{ $dataRow->name }}</td>
            @endif
            <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, RESOURCE_DOCUMENT, 5, $dataRow->name) !!}</td>
            <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp($dataRow->getOriginal('added')) }} </span>{{ $dataRow->added }}</td>
        </tr>
        @include('modals.resource_document_edit',['color' => 'red', 'modal_id' => 'historic-edit-'.$dataRow->id, 'url' => route('ajax.resource_document'), 'data' => $dataRow ])
        @endif
        @endforeach
    @endif
@overwrite


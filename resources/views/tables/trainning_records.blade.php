@extends('tables.main_table', [
        'header' => ["Document", "Last Updated", "File"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
            <td><a href="#edit-traning-record-{{$dataRow->id}}" data-toggle="modal">{{ $dataRow->name }}</a></td>
            @else
            <td>{{ $dataRow->name }}</td>
            @endif
            <td>{{ $dataRow->added }}</td>
            <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, TRAINING_RECORD_FILE, 5, $dataRow->name) !!}</td>
        </tr>
        @include('modals.training_record_edit',['color' => 'red', 'modal_id' => 'edit-traning-record-'.$dataRow->id,'action' => 'edit', 'client_id' => $dataRow->client_id,'data' => $dataRow, 'url' => route('ajax.training_record'), 'doc_type' => 'training'])
        @endforeach
    @endif
@overwrite


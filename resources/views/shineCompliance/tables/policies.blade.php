@extends('shineCompliance.tables.main_table', [
        'header' => ["Document", "Last Updated", "File"],
        'row_col' => 'col-md-12'
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
            <td><a href="#edit-policy-{{$dataRow->id}}" data-toggle="modal">{{ $dataRow->name }}</a></td>
            @else
            <td>{{ $dataRow->name }}</td>
            @endif
            <td>{{ $dataRow->added }}</td>
            <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, POLICY_FILE, 5, $dataRow->name) !!}</td>
        </tr>
        @include('shineCompliance.modals.training_record_edit',['color' => 'red', 'modal_id' => 'edit-policy-'.$dataRow->id,'action' => 'edit', 'client_id' => $dataRow->client_id,'data' => $dataRow, 'url' => route('ajax.training_record'), 'doc_type' => 'policy', 'title' => 'Edit Policy Document'])
        @endforeach
    @endif
@overwrite


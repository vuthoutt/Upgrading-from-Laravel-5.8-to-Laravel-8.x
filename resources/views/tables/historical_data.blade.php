@extends('tables.main_table', [
        'header' => ["Document", "Reference", "Last Revision", "File"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
            <td><a data-id="{{ $dataRow->id }}" data-toggle="modal" href="#historic-edit-{{$dataRow->id}}">{{ $dataRow->name }}</a></td>
            @else
            <td>{{ $dataRow->name }}</td>
            @endif
            <td>{{ $dataRow->reference }}</td>
            <td>{{ $dataRow->added }}</td>
            <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, HISTORICAL_DATA, 5, $dataRow->name, $propertyData->id) !!}</td>
        </tr>
        @include('modals.historical_edit',['color' => 'red', 'modal_id' => 'historic-edit-'.$dataRow->id, 'url' => route('ajax.create_historical_doc'), 'data' => $dataRow ])
        @endforeach
    @endif
@overwrite


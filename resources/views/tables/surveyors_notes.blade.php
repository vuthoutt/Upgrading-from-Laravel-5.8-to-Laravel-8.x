@extends('tables.main_table', [
        'header' => ['Document','Reference','Description','File']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
            <td><a href="#surveyor_note-modal-{{$dataRow->id}}" data-toggle="modal">{{ $dataRow->name }}</a></td>
            @else
            <td>{{ $dataRow->name }}</td>
            @endif
            <td>{{ $dataRow->reference }}</td>
            <td>{{ $dataRow->plan_reference }}</td>
            <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, PLAN_FILE, $dataRow->survey->status ?? '',$dataRow->name, $dataRow->property_id ?? '' ) !!}</td>
        </tr>
        @include('modals.property_plan_edit',['color' => isset($survey) ? 'orange' : 'red', 'modal_id' => 'surveyor_note-modal-'.$dataRow->id, 'url' => route('ajax.property_plan'), 'data' => $dataRow , 'unique_value' => \Str::random(8), 'doc_type' => 'surveyor_note', 'surveyor_note' => true])
        @endforeach
    @endif
@overwrite

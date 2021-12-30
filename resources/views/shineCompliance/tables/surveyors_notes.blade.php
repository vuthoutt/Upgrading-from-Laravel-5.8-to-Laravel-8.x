@extends('shineCompliance.tables.main_table', [
        'header' => ['Document','Reference','Description','File'],
        'row_col' => 'col-md-12'
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
            <td><a href="#surveyor_note-modal-{{$dataRow->id}}" data-toggle="modal">{{ $dataRow->plan_reference }}</a></td>
            @else
            <td>{{ $dataRow->plan_reference }}</td>
            @endif
            <td>{{ $dataRow->reference }}</td>
            <td>{{ $dataRow->description }}</td>
            <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, NOTE_FILE_ASSESSMENT, $dataRow->assessment->status ?? '',$dataRow->plan_reference, $dataRow->property_id ?? '' ) !!}</td>
        </tr>
        @include('shineCompliance.modals.property_plan_edit',['color' => 'orange', 'modal_id' => 'surveyor_note-modal-'.$dataRow->id, 'url' => route('shineCompliance.ajax.property_plan'), 'data' => $dataRow , 'unique_value' => \Str::random(8), 'doc_type' => 'surveyor_note', 'surveyor_note' => true, 'file' => NOTE_FILE_ASSESSMENT])
        @endforeach
    @endif
@overwrite

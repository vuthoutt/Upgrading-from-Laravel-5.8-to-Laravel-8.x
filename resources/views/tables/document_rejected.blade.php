@extends('tables.main_table', [
        'header' => ["Project Reference", "Property Name", "Project Title", "Document Type", "File", "Rejected Note"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td><a href="{{ route('project.index',['project_id' => $dataRow->project_id]) }}">{{ $dataRow->project_reference }}</a></td>
                <td><a href="{{ route('property_detail', ['property_id' =>$dataRow->property_id,'section' => SECTION_DEFAULT]) }}">{{ $dataRow->property_name ?? '' }}</a></td>
                <td><a href="{{ route('project.index', ['project_id' => $dataRow->project_id]) }}">{{ $dataRow->project_title }}</a></td>
                <td>{{ $dataRow->doc_type }}</td>
                <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, DOCUMENT_FILE, 6,$dataRow->doc_name, $dataRow->property_id) !!}</td>
                <td>
                    <a href="#rejected-note" data-note="{{ $dataRow->note }}" data-due-date="{{ CommonHelpers::convertTimeStampToTime($dataRow->deadline) }}" data-survey-ref="{{ $dataRow->doc_name }}" data-toggle="modal">View</a>
                </td>
            </tr>
        @endforeach
    @endif

@overwrite


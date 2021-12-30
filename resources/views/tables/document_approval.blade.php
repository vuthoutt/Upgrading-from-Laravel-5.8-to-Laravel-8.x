@extends('tables.main_table', [
        'header' => ["Project Reference", "Property Name", "Document Type", "Uploaded Date", "File","Confirmation"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)

        @foreach($data as $dataRow)
            <tr>
                <td><a href="{{ route('project.index',['project_id' => $dataRow->project_id]) }}">{{ $dataRow->project_reference }}</a></td>
                <td><a href="{{ route('property_detail', ['property_id' =>$dataRow->property_id,'section' => SECTION_DEFAULT]) }}">{{ $dataRow->property_name ?? '' }}</a></td>
                <td>{{ $dataRow->doc_type }}</td>
                <td>{{ \CommonHelpers::convertTimeStampToTime($dataRow->added) }}</td>
                <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, DOCUMENT_FILE, 1,$dataRow->doc_name, $dataRow->property_id) !!}</td>
                <td style="min-width: 160px">
                    @if(\Auth::user()->id == $dataRow->lead_key || \Auth::user()->id == $dataRow->second_lead_key || in_array( \Auth::user()->id, $asbestos_lead_admin ))
                        <button type="button" class="btn btn-success approval_survey" data-doc-id="{{ $dataRow->id }}" data-doc-name="{{ $dataRow->doc_name }}" data-toggle="modal" data-target="#project-confirm{{ $unique ?? '' }}" >Confirm</button>
                        &nbsp;
                        <button type="button" class="btn btn-danger approval_survey" data-doc-id="{{ $dataRow->id }}" data-doc-date="{{ \CommonHelpers::convertTimeStampToTime($dataRow->added) }}" data-doc-name="{{ $dataRow->doc_name }}" data-toggle="modal" data-target="#project-reject{{ $unique ?? '' }}" >Reject</button>
                    @endif
                </td>
            </tr>
        @endforeach
        @include('modals.project_doc_confirm',[ 'modal_id' => 'project-confirm'.($unique ?? ''),'color' => 'red', 'header' => 'shinePrism - Document Approval','unique' => ($unique ?? '')])
        @include('modals.project_doc_reject',[ 'modal_id' => 'project-reject'.($unique ?? ''),'color' => 'red', 'header' => 'shinePrism - Document Rejection', 'url' => route('document.reject'),'unique' => ($unique ?? '') ])
    @endif

@overwrite



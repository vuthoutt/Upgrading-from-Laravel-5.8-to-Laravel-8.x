@extends('shineCompliance.tables.main_table', [
        'header' => ['Project Reference', 'Property Name', 'Document Type', 'Uploaded Date', 'File', 'Confirmation'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $key => $dataRow)
            <tr>
                <td><a href="{{ route('project.index',['project_id' => $dataRow->project_id]) }}">{{ $dataRow->project_reference }}</a></td>
                <td><a href="{{ route('property_detail', ['property_id' =>$dataRow->property_id,'section' => SECTION_DEFAULT]) }}">{{ $dataRow->property_name ?? '' }}</a></td>
                <td>{{ $dataRow->doc_type }}</td>
                <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp($dataRow->added ?? '') }} </span>{{ \CommonHelpers::convertTimeStampToTime($dataRow->added) }}</td>
                <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, DOCUMENT_FILE, 1,$dataRow->doc_name, $dataRow->property_id) !!}</td>
                <td width="10%">
                <div style="width: 150px!important;text-align: center">
                    @if(\Auth::user()->id == $dataRow->lead_key || \Auth::user()->id == $dataRow->second_lead_key || in_array( \Auth::user()->id, $asbestos_lead_admin ))
                        <input class="button-img" type="image" data-doc-id="{{ $dataRow->id }}" data-doc-name="{{ $dataRow->doc_name }}" data-type="cancel" data-toggle="modal" data-target="#project-cancel{{ $unique ?? '' }}" src="{{asset('img/cancel.png')}}" title="Cancel" >
                        <input class="button-img" type="image" data-doc-id="{{ $dataRow->id }}" data-doc-name="{{ $dataRow->doc_name }}" data-type="approval" data-toggle="modal" data-target="#project-confirm{{ $unique ?? '' }}" src="{{asset('img/approval.png')}}" title="Approval" >
                        <input class="button-img" type="image" data-doc-id="{{ $dataRow->id }}" data-doc-name="{{ $dataRow->doc_name }}" data-doc-date="{{ CommonHelpers::convertTimeStampToTime($dataRow->added) }}" data-type="reject" data-toggle="modal" data-target="#project-reject{{ $unique ?? '' }}" src="{{asset('img/reject.png')}}" title="Reject" >
                    @endif
                </div>
                </td>
            </tr>
        @endforeach
    @endif
@overwrite
@push('css')
    <style>
        .approval_assessment {
            border: none !important;
            border-radius: unset !important;
        }
    </style>
@endpush


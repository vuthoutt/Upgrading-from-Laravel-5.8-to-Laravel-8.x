@extends('tables.main_table', [
        'header' => ["Project Reference", "Property Reference", "Property Name", "Document Type", "Uploaded Date", "File","Confirmation"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td>
                    @if(isset($dataRow->is_major))
                        {{ $dataRow->reference ?? ''}}
                    @else
                        <a href="{{ route('project.index', ['project_id' => $dataRow->project_id]) }}">{{ $dataRow->reference ?? ''}}</a>
                    @endif
                </td>
                <td>
                    @if(isset($dataRow->is_major))
                        {{ $dataRow->pref ?? ''}}
                    @else
                        <a href="{{ route('property_detail', ['id' => $dataRow->property_id ?? 0]) }}">{{ $dataRow->pref ?? '' }}</a>
                    @endif</td>
                <td>
                    @if(isset($dataRow->is_major))
                        {{ $dataRow->pname ?? ''}}
                    @else
                        <a href="{{ route('property_detail', ['id' => $dataRow->property_id ?? 0]) }}">{{ $dataRow->pname ?? '' }}</a>
                    @endif
                </td>
                @if(isset($dataRow->survey_id))
                    <td><a href="{{ route('property.surveys', ['survey_id' => $dataRow->survey_id, 'section' => 1]) }}">{{ $dataRow->doc_type ?? ''}}</a></td>
                @elseif(isset($dataRow->work_reference))
                    <td><a href="{{ route('wr.details', ['id' => $dataRow->id]) }}">{{ $dataRow->work_reference}}</a></td>
                @else
                    <td>{{ $dataRow->doc_type ?? '' }}</td>
                @endif
                <td>{{ \CommonHelpers::convertTimeStampToTime($dataRow->published_date) ?? ''}}</td>
                @if(isset($dataRow->survey_id))
                    <td style="width: 70px !important">
                    {!!
                        \CommonHelpers::getPdfViewingSurvey($dataRow->status,
                        route('survey.view.pdf',['type'=> VIEW_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($dataRow->id)]),
                        route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($dataRow->id)]))
                    !!}</td>
                @elseif(isset($dataRow->work_reference))
                    <td style="width: 70px !important">
                        {!!
                            \CommonHelpers::getWorkPdfViewing($dataRow->id, $dataRow->status)
                        !!}</td>
                @else
                    <td style="width: 40px !important">{!! \CommonHelpers::getFilePdfViewing($dataRow->id, DOCUMENT_FILE, 1,$dataRow->doc_name ?? '', $dataRow->property_id ?? 0) !!}</td>
                @endif
                <td style="min-width: 160px">
                    @if(isset($dataRow->survey_id))
                        <button type="button" class="btn btn-success approval_survey" data-survey-id="{{ $dataRow->id }}" data-survey-ref="{{ $dataRow->doc_type }}" data-type="approval" data-toggle="modal" data-target="#approval-survey" >Confirm</button>&nbsp;
                        <button type="button" class="btn btn-danger approval_survey" data-survey-id="{{ $dataRow->id }}" data-due-date="{{ CommonHelpers::convertTimeStampToTime($dataRow->due_date) }}" data-survey-ref="{{ $dataRow->reference }}" data-type="reject" data-toggle="modal" data-target="#rejected-survey" >Reject</button>
                    @elseif(isset($dataRow->work_reference))
                        @if(isset($dataRow->is_major))
                            <button type="button" class="btn btn-success approval_survey" data-work-id="{{ $dataRow->id }}" data-work-ref="{{ $dataRow->work_reference }}" data-sor-code="0" data-type="approval" data-toggle="modal" data-target="#approval-work-major" >Confirm</button>&nbsp;
                        @else
                            <button type="button" class="btn btn-success approval_survey" data-work-id="{{ $dataRow->id }}" data-work-ref="{{ $dataRow->work_reference }}" data-sor-code="{{ $dataRow->sor_id }}" data-type="approval" data-toggle="modal" data-target="#approval-work" >Confirm</button>&nbsp;
                        @endif
                        <button type="button" class="btn btn-danger approval_survey" data-work-id="{{ $dataRow->id }}" data-due-date="{{ CommonHelpers::convertTimeStampToTime($dataRow->published_date) }}" data-work-ref="{{ $dataRow->work_reference }}" data-type="reject" data-toggle="modal" data-target="#rejected-work" >Reject</button>
                    @else
                        @if($dataRow->status != 3)
                            <button type="button" class="btn btn-success approval_survey" data-doc-id="{{ $dataRow->id }}" data-doc-name="{{ $dataRow->doc_name }}" data-toggle="modal" data-target="#project-confirm" >Confirm</button>&nbsp;
                            <button type="button" class="btn btn-danger approval_survey" data-doc-id="{{ $dataRow->id }}" data-doc-date="{{ \CommonHelpers::convertTimeStampToTime($dataRow->added) }}" data-doc-name="{{ $dataRow->doc_name }}" data-toggle="modal" data-target="#project-reject" >Reject</button>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach

        @include('modals.approval_work_request',[ 'modal_id' => 'approval-work', 'header' => 'Work Request Approval','type' => 'approval_wr' ])
        @include('modals.approval_major_work_request',[ 'modal_id' => 'approval-work-major', 'header' => 'Programmed Work Request Approval','type' => 'major_approval_wr' ])
        @include('modals.reject_work_request',[ 'modal_id' => 'rejected-work', 'header' => 'Work Request Rejection','type' => 'reject' ])
        @include('modals.approval_survey',[ 'modal_id' => 'approval-survey', 'header' => 'Survey Approval','type' => 'approval' ])
        @include('modals.reject_survey',[ 'modal_id' => 'rejected-survey', 'header' => 'Survey Rejection','type' => 'reject' ])
        @include('modals.project_doc_confirm',[ 'modal_id' => 'project-confirm','color' => 'red', 'header' => 'shinePrism - Document Approval'])
        @include('modals.project_doc_reject',[ 'modal_id' => 'project-reject','color' => 'red', 'header' => 'shinePrism - Document Rejection', 'url' => route('document.reject') ])
    @endif

@overwrite


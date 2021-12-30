@extends('shineCompliance.tables.main_table', [
        'header' => ['Assessment ID','Property Name', 'Project Title', 'Published Date', 'File', 'Confirmation'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $key => $dataRow)
            <tr>
                <td><a href="{{ route('shineCompliance.assessment.show', ['assess_id' => $dataRow->id]) }}">{{ $dataRow->reference }}</a></td>
                <td><a href="{{ route('shineCompliance.property.property_detail', ['id' => $dataRow->property_id]) }}">{{ $dataRow->property->name ?? '' }}</a></td>
                <td>{{ optional($dataRow->project)->title}}</td>
                <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp($dataRow->getOriginal('published_date')) }} </span>{{ $dataRow->published_date ?? '' }}</td>
{{--                <td style="width: 85px;">{!!   CommonHelpers::getPdfViewingSurvey($dataRow->status, route('survey.view.pdf',['type'=>VIEW_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurveyApproval($dataRow->publishedSurvey)]), route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurveyApproval($dataRow->publishedSurvey)]))!!}</td>--}}
                <td width="8%">{!!   ComplianceHelpers::getPdfViewingAssessment(4, route('shineCompliance.assessment.pdf_download',['type'=>'view','id'=> $dataRow->lastPublishedAssessments->id ?? 0]), route('shineCompliance.assessment.pdf_download',['type'=>'download','id'=>$dataRow->lastPublishedAssessments->id ?? 0]))!!}</td>
                <td class="approval-button" width="18%">
                    @if(\Auth::user()->id == $dataRow->lead_by || \Auth::user()->id == $dataRow->second_lead_by)
                        <input class="button-img" type="image" data-assessment-id="{{ $dataRow->id }}" data-assessment-ref="{{ $dataRow->reference }}" data-type="cancel" data-toggle="modal" data-target="#cancel-assessment" src="{{asset('img/cancel.png')}}" alt="Submit">
                        <input class="button-img" type="image" data-assessment-id="{{ $dataRow->id }}" data-assessment-ref="{{ $dataRow->reference }}" data-type="approval" data-toggle="modal" data-target="#approval-assessment" src="{{asset('img/approval.png')}}" title="Approval">
                        <input class="button-img" type="image" data-assessment-id="{{ $dataRow->id }}" data-assessment-ref="{{ $dataRow->reference }}" data-type="reject" data-toggle="modal" data-target="#rejected-assessment" src="{{asset('img/reject.png')}}" title="Reject" >
                        {{--                        <button type="button" class="btn btn-success approval_assessment fs-8pt" data-assessment-id="{{ $dataRow->id }}" data-assessment-ref="{{ $dataRow->reference }}" data-type="approval" data-toggle="modal" data-target="#approval-assessment" >Confirm</button>--}}
                        &nbsp;
                        {{--                        <button type="button" class="btn btn-danger approval_assessment fs-8pt" data-assessment-id="{{ $dataRow->id }}" data-due-date="{{ $dataRow->due_date ?? null }}" data-assessment-ref="{{ $dataRow->reference }}" data-type="reject" data-toggle="modal" data-target="#rejected-assessment" >Reject</button>--}}
                    @endif
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


@extends('shineCompliance.tables.main_table', [
        'header' => ['Survey Reference','Project Name', 'Project Title', 'Published Date', 'File', 'Confirmation'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $key => $dataRow)
            <tr>
                <td><a href="{{ route('property.surveys',['survey_id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->reference }}</a></td>
                <td><a href="{{ route('property_detail', ['property_id' =>$dataRow->property_id,'section' => SECTION_DEFAULT]) }}">{{ $dataRow->property->name ?? '' }}</a></td>
                <td><a href="{{ route('project.index', ['project_id' => optional($dataRow->project)->id]) }}"> {{ optional($dataRow->project)->title }}</a></td>
                <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp(optional($dataRow->surveyDate)->getOriginal('published_date')) }} </span>{{ optional($dataRow->surveyDate)->published_date }}</td>
                <td width="8%">{!!   CommonHelpers::getPdfViewingSurvey($dataRow->status, route('survey.view.pdf',['type'=>VIEW_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurveyApproval($dataRow->publishedSurvey)]), route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurveyApproval($dataRow->publishedSurvey)]))!!}</td>
                <td class="approval-button" width="18%">
                    @if(\Auth::user()->id == $dataRow->lead_by || \Auth::user()->id == $dataRow->second_lead_by || in_array( \Auth::user()->id, $asbestos_lead_admin ))
                        <input class="button-img" type="image" data-survey-id="{{ $dataRow->id }}" data-survey-ref="{{ $dataRow->reference }}" data-type="cancel" data-toggle="modal" data-target="#cancel-survey" src="{{asset('img/cancel.png')}}" title="Cancel" >
                        <input class="button-img" type="image" data-survey-id="{{ $dataRow->id }}" data-survey-ref="{{ $dataRow->reference }}" data-type="approval" data-toggle="modal" data-target="#approval-survey" src="{{asset('img/approval.png')}}" title="Approval" >
                        <input class="button-img" type="image" data-survey-id="{{ $dataRow->id }}" data-survey-ref="{{ $dataRow->reference }}" data-due-date="{{ CommonHelpers::convertTimeStampToTime($dataRow->due_date) }}" data-type="reject" data-toggle="modal" data-target="#rejected-survey" src="{{asset('img/reject.png')}}" title="Reject" >
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


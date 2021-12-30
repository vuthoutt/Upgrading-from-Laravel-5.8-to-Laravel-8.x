@extends('tables.main_table', [
        'header' => ["Survey Reference", "Property Name", "Project Title", "Published Date", "File", "Confirmation"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td><a href="{{ route('property.surveys',['survey_id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->reference }}</a></td>
                <td><a href="{{ route('property_detail', ['property_id' =>$dataRow->property_id,'section' => SECTION_DEFAULT]) }}">{{ $dataRow->property->name ?? '' }}</a></td>
                <td><a href="{{ route('project.index', ['project_id' => optional($dataRow->project)->id]) }}"> {{ optional($dataRow->project)->title }}</a></td>
                <td>{{ optional($dataRow->surveyDate)->published_date }}</td>
                <td style="width: 85px;">{!!   CommonHelpers::getPdfViewingSurvey($dataRow->status, route('survey.view.pdf',['type'=>VIEW_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurveyApproval($dataRow->publishedSurvey)]), route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurveyApproval($dataRow->publishedSurvey)]))!!}</td>
                <td style="min-width: 160px">
                    @if(\Auth::user()->id == $dataRow->lead_by || \Auth::user()->id == $dataRow->second_lead_by || in_array( \Auth::user()->id, $asbestos_lead_admin ))
                        <button type="button" class="btn btn-warning approval_survey" data-survey-id="{{ $dataRow->id }}" data-survey-ref="{{ $dataRow->reference }}" data-type="cancel" data-toggle="modal" data-target="#cancel-survey" >Cancel</button>
                        <button type="button" class="btn btn-success approval_survey" data-survey-id="{{ $dataRow->id }}" data-survey-ref="{{ $dataRow->reference }}" data-type="approval" data-toggle="modal" data-target="#approval-survey" >Confirm</button>
                        <button type="button" class="btn btn-danger approval_survey" data-survey-id="{{ $dataRow->id }}" data-due-date="{{ CommonHelpers::convertTimeStampToTime($dataRow->due_date) }}" data-survey-ref="{{ $dataRow->reference }}" data-type="reject" data-toggle="modal" data-target="#rejected-survey" >Reject</button>
                    @else
                        <button type="button" class="btn btn-warning approval_survey" data-survey-id="{{ $dataRow->id }}" data-survey-ref="{{ $dataRow->reference }}" data-type="cancel" data-toggle="modal" data-target="#cancel-survey" >Cancel</button>
                    @endif
                </td>
            </tr>
        @endforeach
    @endif

@overwrite


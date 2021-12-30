@extends('shineCompliance.tables.main_table', [
        'header' => ["Survey Reference", "Property Name", "Project Title", "File", "Rejected Note"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td><a href="{{ route('property.surveys',['survey_id' => ($dataRow->id ?? 0), 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->survey_reference ?? '' }}</a></td>
                <td><a href="{{ route('property_detail', ['property_id' =>($dataRow->property_id ?? 0),'section' => SECTION_DEFAULT]) }}">{{ $dataRow->property_name ?? '' }}</a></td>
                <td><a href="{{ route('project.index', ['project_id' => ($dataRow->project_id ?? 0)]) }}">{{ $dataRow->title ?? '' }}</a></td>
                <td width="8%">{!!   CommonHelpers::getPdfViewingSurvey(6, route('survey.view.pdf',['type'=>VIEW_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($dataRow->id ?? 0)]), route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($dataRow->id ?? 0)]))!!}</td>
                <td width="15%">
                    <a href="#rejected-note" data-note="{{ $dataRow->comments ?? '' }}" data-due-date="{{ CommonHelpers::convertTimeStampToTime($dataRow->due_date ?? 0) }}" data-survey-ref="{{ $dataRow->survey_reference ?? '' }}" data-toggle="modal" alt="Rejection Note" title="Rejection Note">View</a>
                </td>
            </tr>
        @endforeach
    @endif

@overwrite


@extends('tables.main_table', [
        'header' => ["Survey Reference", "Property Name", "Project Title", "File", "Rejected Note"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td><a href="{{ route('property.surveys',['survey_id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->survey_reference }}</a></td>
                <td><a href="{{ route('property_detail', ['property_id' =>$dataRow->property_id,'section' => SECTION_DEFAULT]) }}">{{ $dataRow->property_name ?? '' }}</a></td>
                <td><a href="{{ route('project.index', ['project_id' => $dataRow->project_id]) }}">{{ $dataRow->title }}</a></td>
                <td style="width: 85px;">{!!   CommonHelpers::getPdfViewingSurvey(6, route('survey.view.pdf',['type'=>VIEW_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($dataRow->id)]), route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($dataRow->id)]))!!}</td>
                <td>
                    <a href="#rejected-note" data-note="{{ $dataRow->comments }}" data-due-date="{{ CommonHelpers::convertTimeStampToTime($dataRow->due_date) }}" data-survey-ref="{{ $dataRow->survey_reference }}" data-toggle="modal">View</a>
                </td>
            </tr>
        @endforeach
    @endif

@overwrite


@extends('tables.main_table', [
        'header' => ['Survey ID','Survey Type','Project Reference','Project Title','Status','File','Date Completed'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
    @foreach($data as $dataRow)
    <tr>
        <td><a href="{{ route('property.surveys', ['survey_id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->reference }}</a></td>
        <td>{{ $dataRow->survey_type_text }}</td>
        <td>{{ optional($dataRow->project)->reference}}</td>
        <td>{{ optional($dataRow->project)->title}}</td>
        <td>{!! $dataRow->status_text !!}</td>
        <td style="width: 70px !important">
            {!!
                \CommonHelpers::getPdfViewingSurvey($dataRow->status,
                route('survey.view.pdf',['type'=> VIEW_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($dataRow->id)]),
                route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($dataRow->id)]))
            !!}</td>
        <td>{{ optional($dataRow->surveyDate)->completed_date }}</td>
    </tr>
    @endforeach
    @endif
@overwrite

@extends('shineCompliance.tables.main_table', [
        'header' => ['Assessment ID','Property Name', 'Project Title', 'Status', 'File', 'Assessment Finished'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
    @foreach($data as $key => $dataRow)
        <tr>
            <td><a href="{{ route('shineCompliance.assessment.show', ['assess_id' => $dataRow->id]) }}">{{ $dataRow->reference }}</a></td>
            <td><a href="{{ route('shineCompliance.property.property_detail', ['id' => $dataRow->property_id]) }}">{{ $dataRow->property->name ?? '' }}</a></td>
            <td>{{ optional($dataRow->project)->title}}</td>
            <td>{!! $dataRow->status_text !!}</td>
            <td style="width: 70px !important">
                {!!
                    \ComplianceHelpers::getAssessmentPdfViewing($dataRow->lastPublishedAssessments->id ?? 0,
                    $dataRow->status, route('shineCompliance.assessment.pdf_download',['type'=>'view','id'=> $dataRow->lastPublishedAssessments->id ?? 0]),
                    route('shineCompliance.assessment.pdf_download',['type'=>'download','id'=>$dataRow->lastPublishedAssessments->id ?? 0]))
                    !!}
{{--                {!!--}}
{{--                    \CommonHelpers::getPdfViewingSurvey($dataRow->status,--}}
{{--                    route('survey.view.pdf',['type'=> VIEW_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($dataRow->id)]),--}}
{{--                    route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($dataRow->id)]))--}}
{{--                !!}--}}
            </td>
            <td>{{ $dataRow->assess_finish_date }}</td>
        </tr>
    @endforeach
    @endif
@overwrite

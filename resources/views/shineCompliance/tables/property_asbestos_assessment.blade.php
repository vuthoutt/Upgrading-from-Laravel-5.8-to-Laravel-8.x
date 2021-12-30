@extends('shineCompliance.tables.main_table', [
        'header' => ['Survey ID','Survey Type', 'Project Reference', 'Project Title','Status','File','Surveying Finished'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
    @foreach($data as $key => $dataRow)
        <tr>
            <td><a href="{{ route('property.surveys', ['survey_id' => $dataRow->id,'section' => SECTION_DEFAULT]) }}">{{ $dataRow->reference }}</a></td>
            <td>{{ $dataRow->getSurveyTypeTextAttribute()  ?? ''}}</td>
            <td>{{ optional($dataRow->project)->reference ?? ''}}</td>
            <td>{{ optional($dataRow->project)->title ?? ''}}</td>
            <td>{!! $dataRow->status_text !!}</td>
            <td style="width: 70px !important">
                {!!
                    \CommonHelpers::getSurveyPdfViewing($dataRow->id, $dataRow->status)
                !!}
            </td>
            <td><span class="d-none">{{ $dataRow->surveyDate->surveying_finish_date_sort ?? '' }} </span>{{ $dataRow->surveyDate->surveying_finish_date ?? '' }}</td>
        </tr>
    @endforeach
    @endif
@overwrite

@extends('shineCompliance.tables.main_table', [
        'header' => [ "Survey Reference", "Project Reference", "Property name", "Company Name", "Status", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a href="{{ route('property.surveys',['survey_id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->reference }}</a></td>
             <td>{{ optional($dataRow->project)->reference }}</td>
            <td><a href="{{ route('shineCompliance.property.property_detail', ['property_id' =>$dataRow->property_id,'section' => SECTION_DEFAULT]) }}">{{ $dataRow->property->name ?? '' }}</a></td>
            <td>{{ optional($dataRow->clients)->name }}</td>
            <td>{!! $dataRow->status_text !!}</td>
            <td> <span class="badge {{ $dataRow->surveyDate->risk_color ?? '' }}" {{ (isset($dataRow->surveyDate->risk_color) && $dataRow->surveyDate->risk_color == 'yellow') ? "style=color:#000 !important;" : '' }}>
                {{ isset($dataRow->surveyDate->risk_warning) ? $dataRow->surveyDate->risk_warning : 0 }}
            </span> Days Remaining</td>
        </tr>
        @endforeach
    @endif
@overwrite

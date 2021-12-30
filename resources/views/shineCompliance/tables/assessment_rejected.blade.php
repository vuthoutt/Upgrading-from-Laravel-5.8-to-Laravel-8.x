@extends('shineCompliance.tables.main_table', [
        'header' => ["Reference", "Type", "UPRN", "Block", "PropertyName", "File", "Rejected Note"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td><a href="{{ route('shineCompliance.assessment.show',['assess_id' => ($dataRow->id ?? 0)]) }}">{{ $dataRow->reference ?? '' }}</a></td>
                <td>{{ Str::title(str_replace('_',' ',$dataRow->assess_type)) }}</td>
                <td>{{ $dataRow->property->property_reference ?? '' }}</td>
                <td>{{ $dataRow->property->pblock ?? '' }}</td>
                <td><a href="{{ route('shineCompliance.property.property_detail', ['id' => $dataRow->property->id ?? 0, 'section' => SECTION_DEFAULT])  }}">{{ $dataRow->property->name ?? '' }}</a></td>
                <td width="8%">{!!   ComplianceHelpers::getPdfViewingAssessment(4, route('shineCompliance.assessment.pdf_download',['type'=>'view','id'=> $dataRow->lastPublishedAssessments->id ?? 0]), route('shineCompliance.assessment.pdf_download',['type'=>'download','id'=>$dataRow->lastPublishedAssessments->id ?? 0]))!!}</td>
                <td width="15%">
                    <a href="#rejected-assessment-note" data-note="{{ $dataRow->assessmentInfo->comment ?? '' }}" data-due-date="{{ $dataRow->due_date }}" data-survey-ref="{{ $dataRow->reference ?? '' }}" data-toggle="modal" alt="Rejection Note" title="Rejection Note">View</a>
                </td>
            </tr>
        @endforeach
    @endif

@overwrite


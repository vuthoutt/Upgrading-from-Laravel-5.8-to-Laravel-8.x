@extends('shineCompliance.tables.main_table', [
        'header' => [ "Reference", "Type", "UPRN", "Block", "Property Name","Group", "Project", "Status", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a href="{{ route('shineCompliance.assessment.show', ['assess_id' => $dataRow->id]) }}">{{ $dataRow->reference  ?? ''}}</a></td>
            <td></td>
            <td>{{ $dataRow->property->property_reference ?? ''}}</td>
            <td>{{ $dataRow->property->pblock ?? ''}}</td>
            <td><a href="{{ route('shineCompliance.property.property_detail',['id' => $dataRow->property_id]) }}">{{ $dataRow->property->name ?? ''}}</a></td>
            <td></td>
            <td>{{ $dataRow->project->reference ?? ''}}</td>
            <td>{!! $dataRow->status_text ?? '' !!}</td>
            <td> <span class="badge red_gradient">{{ $dataRow->risk_warning ?? 0 }}</span> Days Remaining</td>
        </tr>
        @endforeach
    @endif
@overwrite

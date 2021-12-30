@extends('shineCompliance.tables.main_table', [
        'header' => ["Reference", "Type", "UPRN", "Block", "Property Name", "Category", "Project Reference", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>

            <td>{{ $dataRow->doc_reference }}</td>
            <td>{{ $dataRow->doc_type }}</td>
            <td>{{ $dataRow->prop_uprn }}</a></td>
            <td>{{ $dataRow->pblock }}</a></td>
            <td><a href="{{ route('shineCompliance.property.property_detail', ['property_id' => $dataRow->prop_id]) }}">{{ $dataRow->property_name }}</a></td>
            <td>{{ \ComplianceHelpers::getProjectDocumentCategory($dataRow->category) }}</td>
            <td><a href="{{ route('project.index', ['project_id' => $dataRow->project_id]) }}">{{ $dataRow->project_reference }}</a></td>
            <td>
                @if(!isset($dataRow->deadline))
                    <span class="badge red_gradient"> QA Required </span>
                @else
                    @if(CommonHelpers::getDaysRemaninng($dataRow->deadline) > 0)
                        <span class="badge {{ $risk_color }}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }}>{{ CommonHelpers::getDaysRemaninng($dataRow->deadline) }}</span> Days Remaining
                    @else
                        <span class="badge {{$risk_color}}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }} style="font-size: 85% !important">Overdue</span>
                    @endif
                @endif
            </td>
        </tr>
        @endforeach
    @endif
@overwrite


@extends('tables.main_table', [
        'header' => ["Work Request Reference","Work Request Type","Priority", "Property UPRN", "Property Name", "File", "Rejected Note"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td><a href="{{ route('wr.details', ['id' => $dataRow->id]) }}">{{ $dataRow->reference }}</a></td>
                @if($dataRow->is_major == 0)
                    <td>{{ $dataRow->workData->parents->description ?? '' }} - {{ $dataRow->workData->description ?? '' }}</td>
                @else
                    <td>{{ $dataRow->workData->parents->parents->description ?? '' }} -  {{ $dataRow->workData->parents->description ?? '' }} - {{ $dataRow->workData->description ?? '' }}</td>
                @endif
                <td>{{ $dataRow->workPriority->description ?? '' }}</td>
                @if($dataRow->is_major == 0)
                    <td><a href="{{ route('property_detail',['id' => $dataRow->property_id]) }}">{{ optional($dataRow->property)->reference }}</a></td>
                     <td><a href="{{ route('property_detail',['id' => $dataRow->property_id]) }}">{{ optional($dataRow->property)->name }}</a></td>
                @else
                    <td>Multiple</td>
                    <td>Multiple</td>
                @endif
                <td style="width: 85px;">{!! \CommonHelpers::getWorkPdfViewing($dataRow->id, $dataRow->status) !!}</td>
                <td>
                    <a href="#rejected-work" data-note="{{ $dataRow->comments }}" data-due-date="{{ $dataRow->due_date }}" data-survey-ref="{{ $dataRow->survey_reference }}" data-toggle="modal">View</a>
                </td>
            </tr>
        @endforeach
    @endif

@overwrite


@extends('tables.main_table', [
        'header' => ["Work Request Reference","Work Request Type","Priority", "Property UPRN", "Property Name", "Published Date", "File", "Confirmation"]
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

                <td>{{ $dataRow->published_date }}</td>
                <td style="width: 85px;">
                    {!! \CommonHelpers::getWorkPdfViewing($dataRow->id, $dataRow->status) !!}</td>
                <td style="min-width: 160px">
                    @if(\Auth::user()->id == $dataRow->asbestos_lead  || in_array( \Auth::user()->id, \CommonHelpers::getGetAdminAsbestosLead() ))
                        @if($dataRow->is_major == 0)
                        <button type="button" class="btn btn-success approval_survey" data-work-id="{{ $dataRow->id }}" data-work-ref="{{ $dataRow->reference }}" data-sor-code="{{ $dataRow->sor_id }}" data-type="approval" data-toggle="modal" data-target="#approval-work" >Confirm</button>
                        @else
                        <button type="button" class="btn btn-success approval_survey" data-work-id="{{ $dataRow->id }}" data-work-ref="{{ $dataRow->reference }}" data-type="approval" data-toggle="modal" data-target="#approval-work-major" >Confirm</button>
                        @endif
                        &nbsp;
                        <button type="button" class="btn btn-danger approval_survey" data-work-id="{{ $dataRow->id }}" data-due-date="{{ $dataRow->due_date }}" data-work-ref="{{ $dataRow->reference }}" data-type="reject" data-toggle="modal" data-target="#rejected-work" >Reject</button>
                    @endif
                </td>
            </tr>
        @endforeach
    @endif

@overwrite


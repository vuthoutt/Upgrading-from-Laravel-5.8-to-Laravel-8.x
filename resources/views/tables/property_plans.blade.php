@extends('tables.main_table', [
        'header' => ['Document','Reference','Description',(isset($type) and $type == 'survey_plans') ? 'Lastest Version' :'Last Updated','File']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
            <td><a href="#property-plan-modal-{{$dataRow->id}}" data-toggle="modal">{{ $dataRow->name }}</a></td>
            @else
            <td>{{ $dataRow->name }}</td>
            @endif
            <td>{{ $dataRow->reference }}</td>
            <td>{{ $dataRow->plan_reference }}</td>
            <td>{{ $dataRow->added }}</td>
            <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, PLAN_FILE, $dataRow->survey->status ?? '', $dataRow->name, $dataRow->property_id) !!}</td>
        </tr>
        @include('modals.property_plan_edit',['color' => isset($survey) ? 'orange' : 'red', 'modal_id' => 'property-plan-modal-'.$dataRow->id, 'url' => route('ajax.property_plan'), 'data' => $dataRow , 'unique_value' => \Str::random(8), 'doc_type' => 'plan'])
        @endforeach
    @endif
@overwrite


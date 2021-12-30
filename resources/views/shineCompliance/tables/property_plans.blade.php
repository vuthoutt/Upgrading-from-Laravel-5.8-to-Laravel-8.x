@extends('shineCompliance.tables.main_table', [
        'header' => ['Document','Reference','Description',(isset($type) and $type == 'survey_plans') ? 'Lastest Version' :'Last Updated','File'],
        'row_col' => 'col-md-12'
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
            <td><a href="#property-plan-modal-{{$dataRow->id}}" data-toggle="modal">{{ $dataRow->plan_reference }}</a></td>
            @else
            <td>{{ $dataRow->plan_reference }}</td>
            @endif
            <td>{{ $dataRow->reference }}</td>
            <td>{{ $dataRow->description }}</td>
            <td>{{ $dataRow->plan_date }}</td>
            <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, PLAN_FILE_ASSESSMENT, $dataRow->assessment->status ?? '', $dataRow->plan_reference, $dataRow->property_id) !!}</td>
        </tr>
        @include('shineCompliance.modals.property_plan_edit',['color' => isset($survey) ? 'orange' : 'red', 'modal_id' => 'property-plan-modal-'.$dataRow->id, 'url' => route('shineCompliance.ajax.property_plan'), 'data' => $dataRow , 'unique_value' => \Str::random(8), 'doc_type' => 'plan', 'file' => PLAN_FILE_ASSESSMENT])
        @endforeach
    @endif
@overwrite

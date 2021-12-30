@extends('shineCompliance.tables.main_table', [
        'header' => ['Document','Reference','Description','Lastest Version','File'],
        'row_col' => 'col-md-12'
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                @if(isset($edit_permission) and $edit_permission == true)
                    <td><a href="#property-sample-modal-{{$dataRow->id}}" data-toggle="modal">{{ $dataRow->sample_reference }}</a></td>
                @else
                    <td>{{ $dataRow->sample_reference }}</td>
                @endif
                <td>{{ $dataRow->reference }}</td>
                    <td>{{ $dataRow->description }}</td>
                    <td>{{  isset( $dataRow->date) ? date("d/m/Y", $dataRow->date) : '' }}</td>
                    <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, SAMPLE_CERTIFICATE_ASSESSMENT, 7, $dataRow->sample_reference, $dataRow['property_id']) !!}</td>
            </tr>
            @include('shineCompliance.modals.assessment_sampling_edit',[
                'color' => isset($survey) ? 'orange' : 'red',
                'modal_id' => 'property-sample-modal-'.$dataRow->id,
                'url' => route('shineCompliance.ajax.assessment_sampling'),
                'data' => $dataRow ,
                'unique_value' => \Str::random(8),
                'doc_type' => 'plan'
            ])
        @endforeach
    @endif
@overwrite

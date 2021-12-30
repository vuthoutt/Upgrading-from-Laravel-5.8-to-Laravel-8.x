@extends('shineCompliance.tables.main_table', [
        'header' => ["Document", "Shine Reference", "Description", "Lastest Version", "File"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
            <td><a href="#sample-certificate-modal-{{ $type.$dataRow->id }}" data-toggle="modal">
                {{ $dataRow->{$reference} }}
            </a></td>
            @else
                <td>{{ $dataRow->{$reference} }}</td>
            @endif
            <td>{{ $dataRow->reference }}</td>
            <td>{{ $dataRow->description }}</td>
            <td>{{ $dataRow->updated_date }}</td>
            <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, SAMPLE_CERTIFICATE_FILE, $dataRow->survey->status ?? '',$dataRow->reference, $dataRow->survey->property_id ?? '' ) !!}</td>
        </tr>
        @include('shineCompliance.modals.property_plan_edit',['color' => 'orange',
                                            'modal_id' => 'sample-certificate-modal-'.$type.$dataRow->id,
                                            'url' => $url,
                                            'data' => $dataRow ,
                                            'unique_value' => \Str::random(8),
                                            'data_site' => $survey,
                                            'survey' => true,
                                            'assessment' =>true,
                                            'doc_type' => $type,
                                            'file' => SAMPLE_CERTIFICATE_FILE,
                                            'date' => $date,
                                            'reference' => $reference,
                                            'description' => 'description',
                                            ])
        @endforeach
    @endif
@overwrite


@extends('tables.main_table', [
        'header' => ["Reference","Type","Property Reference","Property Name","Status","File",$tableDateName]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td><a href="{{ route('wr.details', ['id' => $dataRow->id])  }}">{{ $dataRow->reference }}</a></td>
                <td>{{ optional($dataRow->workData)->description }}</td>
                @if($dataRow->is_major == 0)
                    <td><a href="{{ route('property_detail', ['id' => $dataRow->property_id])  }}">{{ optional($dataRow->property)->property_reference }}</a></td>
                    <td><a href="{{ route('property_detail', ['id' => $dataRow->property_id])  }}">{{ optional($dataRow->property)->name }}</a></td>
                @else
                    <td>Multiple</td>
                    <td>Multiple</td>
                @endif
                <td>{{ ($dataRow->decommisioned == 1) ? 'Decommisioned': $dataRow->status_text }}</td>
                <td style="width: 80px">
                    {!!
                        \CommonHelpers::getWorkPdfViewing($dataRow->id, $dataRow->status)
                    !!}
                </td>
                <td>

                    @if($viewDate == 'completed')
                        {{ $dataRow->completed_date }}
                    @elseif($viewDate == 'created')
                        {{$dataRow->created_at->format('d/m/Y')}}
                    @elseif($viewDate == 'updated')
                        {{$dataRow->updated_at ? $dataRow->updated_at->format('d/m/Y') : ''}}
                    @endif

                </td>
            </tr>
        @endforeach
    @endif
@overwrite


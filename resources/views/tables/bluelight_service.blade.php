@extends('tables.main_table',[
        'header' => ['Documents', 'Shine Reference', 'Last Revision', 'File']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td>{{ $dataRow->zone_name }}</td>
                <td>{{ $dataRow->reference }}</td>
                <td>{{ $dataRow->bluelightService->last_revision ?? 'N/A' }}</td>
                 <td style="width: 15px">
                        <a href="{{ route('generate_bls_file',['zone_id' => $dataRow->id]) }}"  class="btn btn-outline-secondary btn-sm">
                            <i class="fa fa-download"></i>
                        </a>
                 </td>
{{--                 <td style="width: 15px">
                    @if(isset($dataRow->bluelightService->status) and ($dataRow->bluelightService->status == 2))
                    <a href="{{ route('get_zip_file_BS',['zone_id' => $dataRow->id]) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-download"></i>
                    </a>
                    @endif
                </td>
                <td style="width: 90px">
                    @if(isset($dataRow->bluelightService->status) and ($dataRow->bluelightService->status == 1))
                        <button type="button" class="btn btn-warning approval_survey" >Generating</button>
                    @else
                        <a href="{{ route('generate_bls_file',['zone_id' => $dataRow->id]) }}">
                            <button type="button" class="btn btn-primary approval_survey" >Generate</button>
                        </a>
                    @endif
                </td> --}}
            </tr>
        @endforeach
    @endif
@overwrite

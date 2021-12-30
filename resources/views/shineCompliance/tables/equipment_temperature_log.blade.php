@extends('shineCompliance.tables.main_table', [
        'header' => ['Record','Previous Recorded Value', 'Previous Recorded Date', 'Update']
    ])
@section('datatable_content')
    @if(isset($data['active_field']) and count($data['active_field']) > 0)
        @foreach($data['active_field'] as $dataRow)
            @if($equipment->getActiveTextAttribute($dataRow))
                <tr>
                    <td>{{$equipment->getActiveTextAttribute($dataRow)}}</td>
                    <td>
                        @if($equipment->tempLog->where("$dataRow", '!=', null)->last() && isset($equipment->tempLog->where("$dataRow", '!=', null)->last()->{$dataRow}))
                            {{$equipment->tempLog->where("$dataRow", '!=', null)->last()->{$dataRow} }} {{$dataRow != 'ph' ? '°C' : '' }}
                        @endif
                        (<a href="javascipt:void(0)" class="temperature-history-btn" data-equip-id="{{$equipment->id}}"
                            data-key-id="{{$dataRow}}">History</a>)
                    </td>
                    <td>
                        @if($equipment->tempLog->where("$dataRow", '!=', null)->last() && isset($equipment->tempLog->where("$dataRow", '!=', null)->last()->created_at))
                            {{$equipment->tempLog->where("$dataRow", '!=', null)->last()->created_at->format('d/m/Y') }}
                        @endif
                    </td>
                    <td>
                        <a href="javascipt:void(0)" class="edit-temperature-btn" data-key-id="{{$dataRow}}">Edit</a>
                    </td>
                </tr>
            @endif
        @endforeach
    @endif
@overwrite

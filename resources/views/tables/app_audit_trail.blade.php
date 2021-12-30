@extends('tables.main_table',[
        'header' => $header
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td>{{ $dataRow->app_audit_ref }}</td>
                <td>
                    @if($dataRow->type == 1)
                        {{ $dataRow->survey->reference ?? '' }}
                    @elseif($dataRow->type == 2)
                        {{ $dataRow->audit->reference ?? '' }}
                    @endif
                </td>
                <td>{{ $dataRow->actionType->action_type ?? '' }}</td>
                <td>{{ $dataRow->user->full_name ?? '' }}</td>
                <td>{{ $dataRow->user->clients->name ?? '' }}</td>
                <td>{{ $dataRow->date }}</td>
                <td>{{ $dataRow->time }}</td>
                <td>{{ $dataRow->ip }}</td>
                <td>
                    @if($dataRow->type == 1)
                        {{ $dataRow->survey->property->name ?? '' }}
                    @elseif($dataRow->type == 2)
                        {{ $dataRow->audit->property->name ?? '' }}
                    @endif
                </td>
                <td>{{ $dataRow->comment }}</td>
            </tr>
        @endforeach
    @endif
@overwrite

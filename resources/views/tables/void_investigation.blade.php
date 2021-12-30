@extends('tables.main_table',[
        'header' => false,
        'normalTable' => true
    ])
@section('datatable_content')
        <tr>
            <td>Ceiling Void</td>
            <td>
                @if(isset($data->locationVoid->ceiling_void_operative))
                    <div style="text-align: center;
                    {{$data->locationVoid->ceiling_void_operative == 'Accessible'
                    ? ' color: #468847;'
                    : ' color: #b94a48;'}}
                    ">
                        <strong><em>{{$data->locationVoid->ceiling_void_operative}}</em></strong>
                    </div>
                @endif
            </td>
        </tr>
        <tr>
            <td>Floor Void</td>
            <td>
                @if(isset($data->locationVoid->floor_void_operative))
                    <div style="text-align: center;
                    {{$data->locationVoid->floor_void_operative == 'Accessible'
                    ? 'color: #468847;'
                    : 'color: #b94a48;'}}
                        ">
                        <strong><em>{{$data->locationVoid->floor_void_operative}}</em></strong>
                    </div>
                @endif
            </td>
        </tr>
        <tr>
            <td>Cativies</td>
            <td>
                @if(isset($data->locationVoid->cativies_operative))
                    <div style="text-align: center;
                    {{$data->locationVoid->cativies_operative == 'Accessible'
                    ? 'color: #468847;'
                    : 'color: #b94a48;'}}
                        ">
                        <strong><em>{{$data->locationVoid->cativies_operative}}</em></strong>
                    </div>
                @endif
            </td>
        </tr>
        <tr>
            <td>Risers</td>
            <td>
                @if(isset($data->locationVoid->risers_operative))
                    <div style="text-align: center;
                    {{$data->locationVoid->risers_operative == 'Accessible'
                    ? 'color: #468847;'
                    : 'color: #b94a48;'}}
                        ">
                        <strong><em>{{$data->locationVoid->risers_operative}}</em></strong>
                    </div>
                @endif
            </td>
        </tr>
        <tr>
            <td>Ducting</td>
            <td>
                @if(isset($data->locationVoid->ducting_operative))
                    <div style="text-align: center;
                    {{$data->locationVoid->ducting_operative == 'Accessible'
                    ? 'color: #468847;'
                    : 'color: #b94a48;'}}
                        ">
                        <strong><em>{{$data->locationVoid->ducting_operative}}</em></strong>
                    </div>
                @endif
            </td>
        </tr>
        <tr>
            <td>Boxing</td>
            <td>
                @if(isset($data->locationVoid->boxing_operative))
                    <div style="text-align: center;
                    {{$data->locationVoid->boxing_operative == 'Accessible'
                    ? 'color: #468847;'
                    : 'color: #b94a48;'}}
                        ">
                        <strong><em>{{$data->locationVoid->boxing_operative}}</em></strong>
                    </div>
                @endif
            </td>
        </tr>
        <tr>
            <td>Pipework</td>
            <td>
                @if(isset($data->locationVoid->pipework_operative))
                    <div style="text-align: center;
                    {{$data->locationVoid->pipework_operative == 'Accessible'
                    ? 'color: #468847;'
                    : 'color: #b94a48;'}}
                        ">
                        <strong><em>{{$data->locationVoid->pipework_operative}}</em></strong>
                    </div>
                @endif
            </td>
        </tr>
@overwrite

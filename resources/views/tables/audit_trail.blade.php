@extends('tables.main_table',[
        'header' => $header
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                @if(isset($summary))
                    <td>{{ $dataRow->shine_reference }}</td>
                    <td>{{ $dataRow->object_reference }}</td>
                    <td>{{ $dataRow->action_type }}</td>
                    <td>{{ $dataRow->user_name }}</td>
                    <td>{{ $dataRow->date }}</td>
                    <td>{{ $dataRow->date_hour }}</td>
                    <td>{{ $dataRow->comments }}</td>
                @else
                    <td>{{ $dataRow->comments }}</td>
                    <td>{{ $dataRow->date }}</td>
                    <td>{{ $dataRow->date_hour }}</td>
                @endif
            </tr>
        @endforeach
    @endif
@overwrite

@extends('tables.main_table',[
        'header' => ['Record','Previous Course Date','Next Course Date','Risk Warning'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td>{{$dataRow['name']}}</td>
                <td>{{isset($dataRow['previous_course_date']) && $dataRow['previous_course_date'] > 0 ? date("d/m/Y",  $dataRow['previous_course_date']) : ""}}</td>
                <td>{{isset($dataRow['next_course_date']) && $dataRow['next_course_date'] > 0 ? date("d/m/Y",  $dataRow['next_course_date']) : ""}}</td>
                <td><span class="badge light_blue_gradient">{{$dataRow['days_remaining']}}</span> &nbsp; Days Remaining</td>
            </tr>
        @endforeach
    @endif
@overwrite

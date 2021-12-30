@extends('tables.main_table', [
        'header' => $header
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
    @foreach($data as $row)

        <tr>
            <td style="width: 100px">{{ \CommonHelpers::getAdminToolActionText($row->action) }}</td>
            <td>{{ $row->description }}</td>
            <td>{{ $row->reason }}</td>
            <td>{{ $row->user->full_name ?? '' }}</td>
            <td style="width: 125px">{{ $row->created_at }}</td>
            <td>{{ ($row->result == 'success' ? 'Success' : 'Fail') }}</td>
            @if($roll_back)
                <td>
                    @if($row->result == 'success')
                    <button type="button" class="btn btn-warning approval_survey"
                        data-roll-id="{{ $row->id }}"
                        data-description="{{ $row->description ?? '' }}"
                        data-type="{{ $row->action ?? '' }}"
                        data-toggle="modal" data-target="#roll-back" >
                    Rollback</button>
                    @endif
                </td>
            @endif
        </tr>
    @endforeach
    @endif

@overwrite
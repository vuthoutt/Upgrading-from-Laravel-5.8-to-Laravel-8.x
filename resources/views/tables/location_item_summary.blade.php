@extends('tables.main_table', [
        'header' => ['Risk Warning','Record Count'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
    @foreach($data as $key => $value)

    <tr>
        <td>{{ $key }}</td>
        <td>
            @if($key == 'No Risk (NoACM) Item Summary')
{{--                 @if(isset($register))
                    <a href="{{ $value['link'] }}" class="orange_text">{{ $value['number'] }}</a>
                @else --}}
                    <a href="{{ $value['link'] }}" class="green_text">{{ $value['number'] }}</a>
                {{-- @endif --}}
            @else
            <a href="{{ $value['link'] }}">{{ $value['number'] }}</a>
            @endif
        </td>
    </tr>
    @endforeach
    @endif
@overwrite
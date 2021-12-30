@php
$header = ['Risk Warning'];
if($asbestos) {
    $header[] = 'Asbestos';
}
if($fire) {
    $header[] = 'Fire';
}
if($water) {
    $header[] = 'Water';
}
if($hs) {
    $header[] = 'H&S';
}
@endphp
@extends('shineCompliance.tables.main_table', [
        'header' => $header,
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
    @foreach($data as $key => $value)

    <tr>
        <td>{{ $key ?? ''}}</td>
        @if($asbestos)
        <td>
            @if(isset($value['asbestos']['link']) && $value['asbestos']['link'] == '#')
                {{ $value['asbestos']['count'] ?? 0 }}
            @else
                <a href="{{ $value['asbestos']['link'] ?? '#' }}">{{ $value['asbestos']['count'] ?? 0 }}</a>
            @endif
        </td>
        @endif
        @if($fire)
        <td>
            @if(isset($value['fire']['link']) && $value['fire']['link'] == '#')
                {{ $value['fire']['count'] ?? 0 }}
            @else
                <a href="{{ $value['fire']['link'] ?? '#' }}">{{ $value['fire']['count'] ?? 0 }}</a>
            @endif
        </td>
        @endif
{{--         <td>
            @if(isset($value['gas']['link']) && $value['gas']['link'] == '#')
                {{ $value['gas']['count'] ?? 0 }}
            @else
                <a href="{{ $value['gas']['link'] ?? '#' }}">{{ $value['gas']['count'] ?? 0 }}</a>
            @endif
        </td> --}}
        @if($water)
        <td>

            @if(isset($value['water']['link']) && $value['water']['link'] == '#')
                {{ $value['water']['count'] ?? 0 }}
            @else
                <a href="{{ $value['water']['link'] ?? '#' }}">{{ $value['water']['count'] ?? 0 }}</a>
            @endif
        </td>
        @endif
        @if($hs)
        <td>

            @if(isset($value['hs']['link']) && $value['hs']['link'] == '#')
                {{ $value['hs']['count'] ?? 0 }}
            @else
                <a href="{{ $value['hs']['link'] ?? '#' }}">{{ $value['hs']['count'] ?? 0 }}</a>
            @endif
        </td>
        @endif
    </tr>
    @endforeach
    @endif
@overwrite

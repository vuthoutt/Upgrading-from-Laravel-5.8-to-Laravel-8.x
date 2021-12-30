@extends('shineCompliance.tables.main_table', [
        'header' => ['Contact','Telephone','Email']
    ])
@section('datatable_content')
    @if(isset($contactUser) and count($contactUser) > 0)
        @foreach($contactUser as $contact)
        <tr>
            <td><a href="{{ route('profile',['user' => $contact->id]) }}">{{ $contact->full_name }}</a></td>
            <td><a href="#">{{ $contact->contact->telephone ?? ''}}</td>
            <td><a href="#">{{ $contact->email ?? ''}}</td>
        </tr>
        @endforeach
    @endif
@overwrite


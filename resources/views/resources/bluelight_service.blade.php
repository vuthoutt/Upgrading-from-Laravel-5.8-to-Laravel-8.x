@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'blue_light','data' => ''])

<div class="container prism-content">
    <h3 class="ml-2">
        Blue Light Services
    </h3>
    <div class="main-content">
        <div class="row">
           @include('tables.bluelight_service',[
             'title' => 'Site Asbestos Registers for Blue Light Services',
             'tableId' => 'bluelightService-table',
             'collapsed' => false,
             'plus_link' => false,
             'data' => $zones,
            ])
         </div>
    </div>
</div>
@endsection
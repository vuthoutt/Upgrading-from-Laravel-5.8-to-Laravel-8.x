@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'home','data' => ''])

<div class="container prism-content">
    <h3 class="ml-2">
        Edit [Job Role] Privileges
    </h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#view" title="Details"><strong>View Privileges</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#edit" title="Register"><strong>Add/Edit/Decommission Privileges</strong></a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div id="view" class="container tab-pane fade">
            </div>
            <div id="edit" class="container tab-pane fade">
            </div>
        </div>
    </div>
</div>
@endsection
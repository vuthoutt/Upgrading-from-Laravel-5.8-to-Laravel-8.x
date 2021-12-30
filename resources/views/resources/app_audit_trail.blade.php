@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'app_audit_trail','data' => ''])

<div class="container prism-content">
    <h3 class="ml-2">
        Audit Trail
    </h3>
    <div class="main-content">
        <div class="row">
           @include('tables.app_audit_trail',[
             'title' => 'App Audit Trail Active Log',
             'tableId' => 'app-audit-table',
             'collapsed' => false,
             'plus_link' => false,
             'data' => $audits,
             'header' => ["Audit Reference", "Reference", "Action Type", "Username", "Organisation", "Date", "Time", "IP Address", "Property Name", "Comments"],
             'summary' => true,
             'order_table' => 'published'
            ])
         </div>
    </div>
</div>
@endsection

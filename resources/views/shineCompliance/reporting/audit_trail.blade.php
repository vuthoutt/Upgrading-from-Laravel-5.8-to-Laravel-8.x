@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'audit_trail_compliance', 'color' => 'red'])

<div class="container prism-content">
    <h3 class="ml-2">
        Audit Trail
    </h3>
    <div class="main-content">
        <div class="row">
            @include('shineCompliance.tables.audit_trail',[
             'title' => 'Audit Trail Active Log',
             'tableId' => 'audit-table',
             'collapsed' => false,
             'plus_link' => false,
             'header' => ["Audit Reference", "Reference", "Action Type", "Username", "Date", "Time", "Comments"],
             'summary' => true,
             'order_table' => 'ajax-table'
            ])
         </div>
    </div>
</div>
@endsection

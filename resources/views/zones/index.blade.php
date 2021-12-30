@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'zones','data' => $breadcrumb_data])

<div class="container prism-content">

    <div style="border-bottom:1px #dddddd solid;text-align: center;">
        <img width="150px" src="{{ CommonHelpers::getFile(1, CLIENT_LOGO) }}" alt="{{ env('APP_DOMAIN') ?? 'GSK' }}" style="margin-bottom: 30px;"/>
    </div>
    <h3 style="padding-left: 10px;padding-top: 20px">{{ $client->name }}</h3>
    @if($type)
        @if($type == TYPE_INACCESS_ROOM_SUMMARY)
            @include('tables.inaccess_locations',[
                    'title' => $title,
                    'tableId' => $table_id,
                    'collapsed' => false,
                    'plus_link' => false,
                    'data' => $items_summary_table,
                    'pagination_type' => $pagination_type
                ])
        @else
        @include('tables.item_summary', [
            'title' => $title,
            'tableId' => $table_id,
            'collapsed' => false,
            'plus_link' => false,
            'data' => $items_summary_table,
            'pagination_type' => $pagination_type,
            'summary' => true,
            'property_head' => true,
            'header' => ['Property','Summary','Area/floor Reference','Room/location Reference','Product/debris type', 'MAS',''],
            'order_table' => 'mas-risk'
            ])
        @endif
    @else
        @include('tables.property_register_summary', [
            'title' => 'Property Group Register Summary',
            'tableId' => 'client-register-summary',
            'collapsed' => false,
            'plus_link' => false,
            'normalTable' => true,
            'count' => $dataSummary["All ACM Items"]['number'],
            'data' => $dataSummary,
            'register' => true
            ])
        @include('tables.property_group', [
            'title' => 'Property Groups',
            'tableId' => 'property-groups',
            'collapsed' => true,
            'plus_link' => \CommonHelpers::isSystemClient() ? true : false,
            'modal_id' => 'add-zone',
            'data' => $zones
        ])
    @endif
    @include('modals.edit_zone',['color' => 'red', 'modal_id' => 'add-zone','action' => 'add', 'url' => route('zone.edit'),'client_id' => $client->id ])
</div>
@endsection

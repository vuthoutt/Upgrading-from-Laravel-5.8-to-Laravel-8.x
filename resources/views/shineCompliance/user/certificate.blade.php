@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'home_shineCompliance', 'color' => 'red'])
    <div class="container prism-content pad-up">
        <div class="row">
            <h3 style="margin: 0">Certificate</h3>
        </div>
        <div class="main-content mar-up">
            <div class="row mt-4">
                @include('shineCompliance.user.partials._data_centre_sidebar')
                <div class="col-md-9 pl-0" style="padding: 0">
                    <div id="fire" class="container" style="padding-left: 0; padding-right:0;">
                        @include('shineCompliance.tables.certificate_datacentre', [
                            'title' => 'Certificate Group Notifications',
                            'tableId' => 'crt-group-notification',
                            'collapsed' => false,
                            'plus_link' => false,
                            'data' => [],
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.certificate_datacentre', [
                            'title' => 'Air Monitoring Notifications',
                            'tableId' => 'air-notification',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => [],
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.certificate_datacentre', [
                            'title' => 'Decontamination Unit Clearance Test Notifications',
                            'tableId' => 'unit-clearance',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => [],
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.certificate_datacentre', [
                            'title' => 'Smoke Test Notifications',
                            'tableId' => 'smoke-test',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => [],
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.certificate_datacentre', [
                            'title' => 'Four Stage Clearance Notifications',
                            'tableId' => 'stage-four',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => [],
                            'order_table' => 'survey-table'
                            ])


                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

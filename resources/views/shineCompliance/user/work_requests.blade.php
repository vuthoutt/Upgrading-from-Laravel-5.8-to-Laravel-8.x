@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'home_shineCompliance', 'color' => 'red'])
    <div class="container prism-content pad-up">
        <div class="row">
            <h3 style="margin: 0">Work Requests</h3>
        </div>

        <div class="row mt-4">
            @include('shineCompliance.user.partials._data_centre_sidebar')
            <div class="col-md-9 pl-0 tab-content">

                    @include('shineCompliance.tables.work_requests', [
                       'title' => 'Asbestos Work Requests',
                       'tableId' => 'asbestos-works-table',
                       'collapsed' => false,
                       'plus_link' => false,
                       'data' => $asbestos_work_requests,
                       'tableDateName' => "Created Date",
                       'viewDate' => "created",
                       'order_table' =>'[]'
                   ])

                    @include('shineCompliance.tables.work_requests', [
                       'title' => 'Fire Work Requests',
                       'tableId' => 'fire-works-table',
                       'collapsed' => false,
                       'plus_link' => false,
                       'data' => $fire_work_requests,
                       'tableDateName' => "Created Date",
                       'viewDate' => "created",
                       'order_table' =>'[]'
                   ])

            </div>
        </div>
    </div>
@endsection

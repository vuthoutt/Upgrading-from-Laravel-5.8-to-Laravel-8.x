@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'home_shineCompliance', 'color' => 'red'])
<div class="container prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $data->full_name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        <div class="row mt-4">
            @include('shineCompliance.user.partials._data_centre_sidebar')
            <div class="col-md-9 pl-0" style="padding: 0">
                @include('shineCompliance.tables.user_incident_reports', [
                    'title' => 'Incident Reports',
                    'data' => $incident_reports,
                    'status' => 1,
                    'tableId' => 'user-incident-reports',
                    'collapsed' => false,
                    'plus_link' => false,
                    'row_col' => 'col-md-12',
                    'order_table' => 'published'
                 ])

                {{--@include('shineCompliance.tables.user_incident_reports', [
                    'title' => 'Decommissioned Incident Reports',
                    'data' => $decommissioned_incident_reports,
                    'status' => 1,
                    'tableId' => 'user-decommissioned-incident-reports',
                    'collapsed' => true,
                    'plus_link' => false,
                    'row_col' => 'col-md-12',
                    'order_table' => 'published'
                 ])--}}
            </div>
        </div>
    </div>
</div>
@endsection

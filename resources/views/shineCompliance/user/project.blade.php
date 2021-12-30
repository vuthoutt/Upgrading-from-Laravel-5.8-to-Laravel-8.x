@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'home_shineCompliance', 'color' => 'red'])
    <div class="container prism-content pad-up">
        <div class="row">
            <h3 style="margin: 0">{{ $data->getFullNameAttribute() }}</h3>
        </div>
        <div class="main-content mar-up">
            <div class="row mt-4">
                @include('shineCompliance.user.partials._data_centre_sidebar')
                <div class="col-md-9 pl-0" style="padding: 0">
                    <div id="fire" class="container" style="padding-left: 0; padding-right:0;">
                        @include('tables.property_projects', [
                            'title' => 'My Projects',
                            'data' => $myProjects,
                            'tableId' => 'property-project-table',
                            'collapsed' => false,
                            'plus_link' => false,
                            'header' => ["Property Reference", "Property Name", "Project Reference", "Project Title","Project Type", "Start", "Due"],
                            'dashboard' => true,
                            'order_table' => 'my-project'
                            ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

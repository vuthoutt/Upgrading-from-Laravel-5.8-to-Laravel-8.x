@extends('shineCompliance.layouts.app')
@section('content')
    @include('partials.nav', ['breadCrumb' => 'work_requests','data' => ''])

    <div class="container prism-content">
        <h3>Work Requests</h3>
        <div class="main-content">
            <div class="form-button-search">
                <ul class="nav" id="nav-project">
                @if($asbestos)
                    <li class="nav-item">
                        <a href="{{route('wr.get_list', ['type' => WORK_REQUEST_ASBESTOS_TYPE])}}" style="text-decoration: none">
                            <button type="submit" class="fs-8pt btn shine-compliance-button {{$type == WORK_REQUEST_ASBESTOS_TYPE ? 'asbestos' : '' }}">
                                <strong>{{ __('Asbestos') }}</strong>
                            </button>
                        </a>
                    </li>
                @endif
                @if($fire)
                    <li class="nav-item">
                        <a href="{{route('wr.get_list', ['type' => WORK_REQUEST_FIRE_TYPE])}}" style="text-decoration: none">
                            <button type="submit" class="fs-8pt btn shine-compliance-button {{$type == WORK_REQUEST_FIRE_TYPE ? 'fire' : '' }}">
                                <strong>{{ __('Fire') }}</strong>
                            </button>
                        </a>
                    </li>
                @endif
                </ul>
            </div>
            <!-- Nav tabs -->
            <ul class="nav nav-pills red_gradient_nav" id="myTab">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#live"><strong>Live</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#completed"><strong>Completed</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#decommissioned"><strong>Decommissioned</strong></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="live" class="container tab-pane active">
                    <div class="row" style="margin-right: 0px !important;width: 100%;margin-left: 0px !important;">
                        @include('tables.work_requests', [
                           'title' => 'Live Work Requests',
                           'tableId' => 'live-works-table',
                           'collapsed' => false,
                           'plus_link' => (!\CompliancePrivilege::checkUpdatePermission(JR_RESOURCES,JR_WORK_REQUEST_EDIT) and \CommonHelpers::isSystemClient()) ? false :true,
                           'data' => $works_list_live,
                            'tableDateName' => "Created Date",
                            'viewDate' => "created",
                            'link' => route('wr.get_add'),
                            'order_table' =>'[]'
                       ])
                    </div>
                </div>
                <div id="completed" class="container tab-pane fade">
                    <div class="row" style="margin-right: 0px !important;width: 100%;margin-left: 0px !important;">
                        @include('tables.work_requests', [
                           'title' => 'Completed  Work Requests',
                           'tableId' => 'completed-works-table',
                           'collapsed' => false,
                           'plus_link' => false,
                           'data' => $works_list_completed,
                            'tableDateName' => "Completed Date",
                            'viewDate' => "completed",
                            'order_table' =>'[]'
                       ])
                    </div>

                </div>
                <div id="decommissioned" class="container tab-pane fade">
                    <div class="row" style="margin-right: 0px !important;width: 100%;margin-left: 0px !important;">
                        @include('tables.work_requests', [
                           'title' => 'Decommissioned Work Requests',
                           'tableId' => 'decommissioned-works-table',
                           'collapsed' => false,
                           'plus_link' => false,
                           'data' => $works_list_decommissioned,
                            'tableDateName' => "Modified Date",
                            'viewDate' => "updated",
                            'order_table' =>'[]'
                       ])
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

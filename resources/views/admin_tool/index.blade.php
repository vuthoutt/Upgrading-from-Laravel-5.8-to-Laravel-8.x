@extends('shineCompliance.layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'tool_box','data' => ''])

<div class="container prism-content">
    <div class="d-flex mt-2">
        <!-- Sidebar -->
        <div class="bg-light border-summary" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <h3>Tool Box</h3>
            </div>
            <div class="nav list-group list-group-flush">
                <a href="{{route('toolbox.move')}}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'toolbox.move' ? 'active' : '' }}">
                    Move
                </a>
                <a href="{{route('toolbox.merge')}}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'toolbox.merge' ? 'active' : '' }}">
                    Merge
                </a>
                <a href="{{route('toolbox.remove')}}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'toolbox.remove' ? 'active' : '' }}">
                    Remove
                </a>
                <a href="{{route('toolbox.revert_back')}}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'toolbox.revert_back' ? 'active' : '' }} ">
                    Revert Back
                </a>
                <a href="{{route('toolbox.upload')}}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'toolbox.upload' ? 'active' : '' }}">
                    Upload
                </a>
                <a href="{{route('toolbox.unlock')}}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'toolbox.unlock' ? 'active' : '' }}">
                    Unlock
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                @yield('toolbox_content')
            </div>
        </div>
    </div>
</div>

@endsection
@push('javascript')
<script>

</script>
@endpush

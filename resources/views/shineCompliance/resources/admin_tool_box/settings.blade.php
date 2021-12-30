@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' =>'tool_box_upload', 'color' => 'red'])

<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">Admin Functions</h3>
    </div>
    <div class="mt-3 mb-2">
        <button  type="submit" class="btn light_grey_gradient_button fs-8pt">
            <strong>Tool Box</strong>
        </button>
        <button  type="submit" class="btn light_grey_gradient_button fs-8pt">
            <strong>Settings</strong>
        </button>
    </div>

    <div class="d-flex mt-3">
        <div class="bg-light border-summary" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <h3>System Settings</h3>
            </div>
            <div class="nav list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'toolbox.move' ? 'active' : '' }}">
                    Configurations
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                @yield('system_setting_content')
            </div>
        </div>
    </div>
</div>

@endsection
@push('javascript')
<script>

</script>
@endpush

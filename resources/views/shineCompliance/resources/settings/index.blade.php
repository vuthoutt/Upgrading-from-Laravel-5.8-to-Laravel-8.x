@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' =>'configurations', 'color' => 'red'])

<div class="container prism-content pad-up">
    <h3>Admin Functions</h3>
    <div class="mt-3 mb-2">
        <a href="{{ route('shineCompliance.admin_tool.upload') }}">
            <button  type="submit" class="btn light_grey_gradient_button fs-8pt">
                <strong>Tool Box</strong>
            </button>
        </a>
        <a href="{{ route('shineCompliance.setting.configurations') }}">
            <button  type="submit" class="btn light_grey_gradient_button fs-8pt">
                <strong>Settings</strong>
            </button>
        </a>
    </div>

    <div class="d-flex mt-3">
        <div class="bg-light border-summary" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <h4>Systems Settings</h4>
            </div>
            <div class="nav list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'shineCompliance.setting.configurations' ? 'active' : '' }}">
                    Configurations
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

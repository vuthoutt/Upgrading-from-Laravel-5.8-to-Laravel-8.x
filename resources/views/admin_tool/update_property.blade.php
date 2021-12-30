@extends('layouts.app')
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
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <form id="update-property-form" action="{{route('admin_tool.post_update_property')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row register-form major_work">
                        <label  class="col-md-3 col-form-label text-md-left font-weight-bold">
                            Upload Document:
                        </label>
                        <div class="col-md-5">
                            <div class="form-group">
                                <input class="col-md-8" type="file" class="form-control-file @error('upload_document') is-invalid @enderror" name="upload_document">
                                @error('upload_document')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                            <a href="{{ route('admin_tool.post_update_property_template') }}" >Update Property Template Download</a>
                        </div>
                    </div>
                    <div class="col-md-6 offset-md-3 mt-5">
                        <button type="submit" class="btn light_grey_gradient">
                            <strong>{{ __('Add') }}</strong>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@push('javascript')
<script>

</script>
@endpush

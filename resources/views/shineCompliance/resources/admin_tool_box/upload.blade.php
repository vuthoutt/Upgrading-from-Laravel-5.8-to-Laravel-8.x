@extends('shineCompliance.resources.admin_tool_box.index')
@section('toolbox_content')
    <div>
        <h3 class="text-center mt-4">Upload Action</h3>
        <form method="POST" id="form-action" enctype="multipart/form-data" action="{{ route('shineCompliance.admin_tool.post_upload') }}">
            @csrf
            <div id="form-admin-tool">
                <div class="row register-form form-summary" >
                    <label class="font-weight-bold col-md-12" >What would you like to upload?
                    </label>
                    <div>
                        <div class="form-group col-md-12">
                            <select  class="form-control input-summary" name="type" id="select-type">
         {{--                        <option value='property'>Properties</option>
                                <option value='user'>Users</option> --}}
                                <option value='programmes'>Programmes</option>
                                <option value='systems'>Systems and Programmes</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row register-form form-summary">
                <label class="col-md-12 col-form-label text-md-left font-weight-bold">
                    Instruction:
                </label>
                <span class="col-md-12">If you have no csv template file , please <a href="#" id="template"> Download Template CSV File.</a></span>
                <div class="col-md-12 mt-4 row">
                    <label class="col-md-3 col-form-label text-md-left font-weight-bold">
                        Upload CSV File:
                    </label>
                    <div class="form-group register-form col-md-6">
                        <input class="col-md-12" type="file" class="form-control-file @error('document') is-invalid @enderror" name="document">
                        @error('document')
                        <span class="col-md-12 mt-4" style="color:red">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div style="margin-left: 75px" class="mt-4">
                <button  type="submit" class="btn light_grey_gradient_button fs-8pt">
                    Submit
                </button>
            </div>
        </form>
    </div>
@endsection
@push('javascript')
<script>
$(document).ready(function(){
        $("#select-type").change(function(){
            var type =  $(this).val();
            $("#template").attr("href", '/compliance/tool-box/template?type=' + type);
        });
        $("#select-type").trigger('change');
});
</script>
@endpush

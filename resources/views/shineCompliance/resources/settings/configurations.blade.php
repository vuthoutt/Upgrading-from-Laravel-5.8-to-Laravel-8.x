@extends('shineCompliance.resources.settings.index')
@section('toolbox_content')
    <div>
        <h3 class="text-center mt-4 mb-4">Configurations</h3>
        <form method="POST" id="form-action" enctype="multipart/form-data" action="">
            @csrf
            <div class="row col-md-12">
                <div class="col-md-6">
                    <div id="">
                        <div class="row register-form form-summary" >
                            <label class="col-md-12" >What would you like to <strong>configure</strong>?
                            </label>
                            <div>
                                <div class="form-group col-md-12">
                                    <select  class="form-control input-summary" name="type1" >
                                        {{--                        <option value='property'>Properties</option>
                                                               <option value='user'>Users</option> --}}
                                        <option value='programmes'>Dropdown Values</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="">
                        <div class="row register-form form-summary" >
                            <label class="col-md-12" >What <strong>Dropdown</strong> you like to <strong>configure</strong>?
                            </label>
                            <div>
                                <div class="form-group col-md-12">
                                    <select  class="form-control input-summary" id="document_type" name="type2" >
                                        <option value = 0>---- Please select option ---- </option>
                                        <option value='programmes'>Document Type</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="">
                        <div class="row register-form form-summary" >
                            <label class="col-md-12" ><strong>Dropdown Type Configurations</strong>?
                            </label>
                            <div>
                                <div class="form-group col-md-12">
                                    <select  class="form-control input-summary" name="type3" >
                                        {{--                        <option value='property'>Properties</option>
                                                               <option value='user'>Users</option> --}}
                                        <option value='programmes'>Dropdown Value #1</option>
                                        <option value='systems'>Dropdown Value #2</option>
                                        <option value='systems'>Dropdown Value #3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="load_dropdown">
                    </div>
                </div>
                <div class="col-md-6" id="rules" style="border-left: 1px solid #ced4da;">
                    <div id="form-admin-tool">
                        <div class="row register-form form-summary" >
                            <label class="font-weight-bold col-md-12" style="padding: 0">Rules
                            </label>
                            <div class="row register-form {{ isset($class_other) ? $class_other : '' }}">
                                <label for="first-name" class="col-md-10 col-form-label text-md-left font-weight-bold">
                                    Can the document update Programme logic
                                </label>
                                <div class="col-md-2 mt-1">
                                    <label class="switch">
                                        <input type="checkbox" name="contractor_not_required" class="primary" id="contractor_not_required">
                                        <span class="slider round" id="contractor_tick"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
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
<script type="text/javascript" src="{{ URL::asset('js/multiple_dropdown.js') }}"></script>
<script>
$(document).ready(function(){
    $('#rules').hide();
    $('body').on('change','#document_type',function () {
        var val = $('#document_type').val();
        if(val != 0){
            $('#rules').show();
        }else{
            $('#rules').hide();
        }
    })
        $("#select-type").change(function(){
            var type =  $(this).val();
            $("#template").attr("href", '/compliance/tool-box/template?type=' + type);
        });
        $("#select-type").trigger('change');
    $.ajax
    ({
        type: "GET",
        url: "{{ route('shineCompliance.ajax.dropdown_type_configurations' )}}",
        cache: false,
        success: function (data) {
            if (data) {
                $('#load_dropdown').html(data.data)
                $('#max_option').val(data.total - 1);
            }
            else {

            }
        }
    });
});
</script>
@endpush

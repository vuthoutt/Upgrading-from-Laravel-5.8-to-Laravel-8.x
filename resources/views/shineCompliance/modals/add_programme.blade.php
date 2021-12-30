<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getComplianceNavColor($color) : 'red_color' }}">
                <h5 class="modal-title" id="exampleModalLabel_{{ $unique_value ?? '' }}">shine{{ isset($color) ? 'Arc' : 'Prism' }} - {{ $action == 'edit' ? 'Edit' : 'Add' }} Programme</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-updateOrCreate-area">
                <meta name="csrf-token" content="{{ csrf_token() }}" />
                <div class="modal-body" style="text-align: center;">

                    <strong>Please Enter New Programme Information</strong>

                    <div class="mt-4">
                        <div class="row register-form">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold fs-8pt">
                                Programme Title:
                                <span style="color: red;">*</span>
                            </label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input maxlength="" type="text" class="form-control" name="name" id="form-name" value="" placeholder="">
                                    <span class="invalid-feedback font-weight-bold" role="alert" id="name-err"></span>
                                </div>
                            </div>
                        </div>
{{--                        @include('shineCompliance.forms.form_datepicker',['width_label' => 4,'title' => 'Date Inspected:','data' => date('d/m/Y'), 'name' => 'date_inspected' ])--}}
                        <div class="col-md-8 offset-md-4"><span class="invalid-feedback font-weight-bold" id="date_inspected-err" role="alert"></span></div>
                        <div class="row register-form">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold fs-8pt">
                                Inspection Period:
                                <span style="color: red;">*</span>
                            </label>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input maxlength="" type="number" class="form-control" name="inspection_period" id="form-inspection_period" value="" placeholder="">
                                </div>
                            </div>
                            <strong class="mt-1">Days</strong>
                            <div class="col-md-8 offset-md-4"><span class="invalid-feedback font-weight-bold" id="inspection_period-err" role="alert"></span></div>
                        </div>

                    </div>
                </div>
                <div class="mb-3" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button class="btn light_grey_gradient_button fs-8pt shine_submit_modal" id="submit_area">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){

    $('#submit_area').click(function(e){
        $('.invalid-feedback').html("");
        $('#reference').removeClass('is-invalid');
        $('#description').removeClass('is-invalid');
        e.preventDefault();
        var form_data = new FormData($('#form-updateOrCreate-area')[0]);
       $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

       $.ajax({
            url: "{{ $url }}",
            method: 'post',
            contentType: false,
            processData: false,
            async: false,
            cache: false,
            dataType: "json",
            data: form_data,
            success: function(data){
                  if(data.errors) {
                      $.each(data.errors, function(key, value){
                          $('#'+key+'-err').html(value[0]);
                          $('#form-'+key).addClass('is-invalid');
                          $('.invalid-feedback').show();
                      });
                  } else if(data.status_code == 200) {
                      location.href = '';
                  } else {
                      $('#fail-create').html(data.success);
                      $('.invalid-feedback').show();
                  }
              }
            });
        });

    $("#{{ isset($modal_id) ? $modal_id : '' }}").on("hidden.bs.modal", function () {
        $('.invalid-feedback').html("");
        $('.form-area_reference').removeClass('is-invalid');
        $('.form-description').removeClass('is-invalid');
    });
});

</script>
@endpush

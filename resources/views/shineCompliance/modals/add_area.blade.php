<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getComplianceNavColor($color) : 'red_color' }}">
                <h5 class="modal-title" id="exampleModalLabel_{{ $unique_value ?? '' }}">shinePrism - {{ $action == 'edit' ? 'Edit' : 'Add' }} Area/floor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-updateOrCreate-area">
                <meta name="csrf-token" content="{{ csrf_token() }}" />
                <div class="modal-body" style="text-align: center;">
                    @if($action == 'edit')
                        <strong>Edit area/floor information</strong>
                    @else
                        <strong>Please fill new area/floor information</strong>
                    @endif

                    <div class="mt-4">
                        <span class="invalid-feedback" role="alert" id="fail-create"></span>
                        @if(isset($survey))
                          <input type="hidden" class="form-control" name="survey_id" id="survey_id" value="{{ $survey->id }}">
                          <input type="hidden" class="form-control" name="property_id" id="property_id" value="{{ $survey->property_id }}">
                        @else
                          <input type="hidden" class="form-control" name="survey_id" id="survey_id" value="0">
                          <input type="hidden" class="form-control" name="property_id" id="property_id" value="{{ $property_id }}">
                        @endif
                        <div class="row register-form">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold fs-8pt">
                                Reference:
                                <span style="color: red;">*</span>
                            </label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input maxlength="" type="text" class="form-control" name="area_reference" class="form-area_reference" value="" placeholder="">

                                    <span class="invalid-feedback" role="alert"></span>
                                </div>
                            </div>
                        </div>

                        <span class="invalid-feedback font-weight-bold" role="alert"  style="font-size: 100% !important"></span>

                        <span class="invalid-feedback font-weight-bold" role="alert" id="area_reference-err" style="font-size: 100% !important"></span>
                        <div class="row register-form">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold fs-8pt">
                                Description:
                                <span style="color: red;">*</span>
                            </label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input maxlength="" type="text" class="form-control" name="description" class="form-description" value="" placeholder="">

                                    <span class="invalid-feedback" role="alert"></span>
                                </div>
                            </div>
                        </div>

                        <span class="invalid-feedback font-weight-bold" role="alert" id="description-err" style="font-size: 100% !important"></span>

                        <div class="row register-form">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold fs-8pt">
                                Accessibility:
                                <span style="color: red;">*</span>
                            </label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="switch">
                                        <input type="checkbox" checked class="accessibility_check"  name="accessibility" value="1">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row register-form inacces_reason-form">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold fs-8pt">
                                Inaccessible Reason:
                            </label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <select name="inreason" class="form-control" id="inreason">
                                        @foreach( $data_dropdown as $data )
                                            <option value="{{ $data->id ?? ''}}" >{{ $data->description ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback" role="alert"></span>
                                </div>
                            </div>
                        </div>
                        <span class="invalid-feedback font-weight-bold" role="alert"  style="font-size: 100% !important"></span>
                        <div class="row register-form comment">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold fs-8pt">
                                Comment:
                            </label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div style="height: 350%">
                                        <textarea class="text-area-form" name="comment" style="height: 150%;font-size: 14px"></textarea>
                                    </div>
                                </div>
                            </div>
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
    $('.comment').hide();
    $('body').on('change','#inreason',function () {
        if($('#inreason').val() == {{ OTHER_AREA }}){
            $('.comment').show();
        }else{
            $('.comment').hide();
        }
    })

    $('body').on('change','.accessibility_check',function () {
        if($(this).is(":checked")){
            $('.inacces_reason-form').hide();
            $('.comment').hide();
        }else{
            $('.inacces_reason-form').show();
            if($('#inreason').val() == {{ OTHER_AREA }}){
                $('.comment').show();
            }else{
                $('.comment').hide();
            }
        }
    })

    $('#inreason').trigger("change");
    $('.accessibility_check').trigger('change');

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

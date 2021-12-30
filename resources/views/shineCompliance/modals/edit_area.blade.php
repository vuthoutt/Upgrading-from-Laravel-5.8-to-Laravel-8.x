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
            <form id="form-updateOrCreate-area_{{ $unique_value ?? '' }}">
                <meta name="csrf-token" content="{{ csrf_token() }}" />
                <div class="modal-body" style="text-align: center;">
                    @if($action == 'edit')
                        <strong>Edit area/floor information</strong>
                    @else
                        <strong>Please fill new area/floor information</strong>
                    @endif
                    <div class="mt-4">
                        <span class="invalid-feedback" role="alert" id="fail-create_{{ $unique_value ?? '' }}"></span>
                        @if(isset($area_id))
                            <input type="hidden" class="form-control" name="area_id"  value="{{ $area_id}}">
                        @endif
                        @if(isset($survey))
                          <input type="hidden" class="form-control" name="survey_id"  value="{{ $survey->id }}">
                          <input type="hidden" class="form-control" name="property_id"  value="{{ $survey->property_id }}">
                        @else
                          <input type="hidden" class="form-control" name="survey_id"  value="0">
                          <input type="hidden" class="form-control" name="property_id" value="{{ $property_id }}">
                        @endif
                        <div class="row register-form">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold fs-8pt">
                                Reference:
                                <span style="color: red;">*</span>
                            </label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input maxlength="" type="text" class="form-control" name="area_reference" id="form-area_reference_{{ $data->id }}" value="{{ $data->area_reference ?? '' }}" placeholder="">

                                    <span class="invalid-feedback" role="alert"></span>
                                </div>
                            </div>
                        </div>
                        <span class="invalid-feedback font-weight-bold area_reference-err" role="alert" style="font-size: 100% !important"></span>
                        <div class="row register-form">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold fs-8pt">
                                Description:
                                <span style="color: red;">*</span>
                            </label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input maxlength="" type="text" id ='form-description_{{$data->id }}'class="form-control" name="description" class="form-description" value="{{ $data->description ?? '' }}" placeholder="">

                                    <span class="invalid-feedback" role="alert"></span>
                                </div>
                            </div>
                        </div>

                        <span class="invalid-feedback font-weight-bold description-err" role="alert" style="font-size: 100% !important"></span>

                        <div class="row register-form">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold fs-8pt">
                                Accessibility:
                                <span style="color: red;">*</span>
                            </label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="switch">
                                        <input type="checkbox" class="accessibility_check_edit" {{ $data->state == AREA_ACCESSIBLE_STATE  ? 'checked' : '' }} name="accessibility" value="1">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row register-form inacces_reason-form_edit">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold fs-8pt">
                                Inaccessible Reason:
                            </label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <select name="inreason" class="js-example-basic-multiple js-states form-control" id="inreason">
                                        @foreach( $data_dropdown as $dropdown )
                                            <option value="{{ $dropdown->id ?? ''}}" {{ $data->reason == $dropdown->id ? 'selected' :'' }} >{{ $dropdown->description ?? '' }}</option>
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
                                        <textarea class="text-area-form" name="comment" style="height: 150%">{{ $data->reason_area ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button class="btn light_grey_gradient_button fs-8pt shine_submit_modal" id="submit_area_{{ $unique_value ?? '' }}">Submit</button>
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

    $('body').on('change','.accessibility_check_edit',function () {
        if($(this).is(":checked")){
            $(this).closest('.modal-body').find('.inacces_reason-form_edit').hide()
            $('.comment').hide();
        }else{
            $('.inacces_reason-form').show();
            if($('#inreason').val() == {{ OTHER_AREA }}){
                $('.comment').show();
            }else{
                $('.comment').hide();
            }
            $(this).closest('.modal-body').find('.inacces_reason-form_edit').show()
        }
    })


    $('.accessibility_check_edit').trigger('change');
    $('#inreason').trigger('change');

    $('#submit_area_{{ $unique_value}}').click(function(e){
        $('.invalid-feedback').html("");
        var that = $(this);
        $('#reference').removeClass('is-invalid');
        $('#description').removeClass('is-invalid');
        e.preventDefault();
        var form_data = new FormData($('#form-updateOrCreate-area_{{ $unique_value}}')[0]);
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
                          console.log($(that).closest('.modal-content').find('.'+key+'-err'),'.'+key+'-err')
                          $(that).closest('.modal-content').find('.'+key+'-err').html(value[0]);
                          $('#form-'+key+"_{{ $data->id }}").addClass('is-invalid');
                          $('.invalid-feedback').show();
                      });
                  } else if(data.status_code == 200) {
                      location.href = '';
                  } else {
                      $('#fail-create_{{ $unique_value ?? '' }}').html(data.success);
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

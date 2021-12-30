<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - {{ ($action == 'add') ? 'Add' : 'Edit' }} Incident Reporting Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" enctype="multipart/form-data" id="form-x-{{ $unique ?? ''}}">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
                <div class="modal-body" style="padding-left: 20px;min-height:120px">
                    <span class="invalid-feedback font-weight-bold" role="alert" id="fail-create"></span>
                    <input type="hidden" class="form-control" name="incident_report_id" id="incident_report_id" value="{{ $incident_report_id ?? '' }}">
                    @if($action == 'edit')
                        <input type="hidden" class="form-control" name="id" id="data_id" value="{{ isset($data) ? $data->id : '' }}">
                    @endif
                    <span class="invalid-feedback font-weight-bold hide" role="alert" id="name-err" style="font-size: 100% !important;margin-left: 165px;"></span>
                    <div class="form-group row {{ ($action == 'add') ? 'mt-4' : 'mt-2' }}">
                        <label class="col-md-4 font-weight-bold" for="document">Document:</label>
                        <input class="col-md-8" type="file" class="form-control-file" id="form-document" name="document" value="">
                        @if ($action == 'edit')
                        <div style="font-size: 100% !important;padding-left: 175px;width: 90%;">
                            <strong >File present: </strong>
                            {{ ($data->filename) ?? 'No file present'}}
                        </div>
                        @endif
                        <span class="invalid-feedback font-weight-bold" role="alert" id="document-err" style="font-size: 100% !important;margin-left: 165px;"></span>
                        </input>
                    </div>
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn light_grey_gradient shine_submit_modal" id="submit_zone{{ $unique ?? ''}}">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#submit_zone{{ $unique ?? ''}}').click(function(e){
        e.preventDefault();
        jQuery('.invalid-feedback').html("");
        jQuery('#form-document').removeClass('is-invalid');
        jQuery('#form-name').removeClass('is-invalid');
        var id = jQuery('#data_id').val();
        var form_data = new FormData($('#form-x-{{ $unique ?? ''}}')[0]);
        console.log(form_data);
        jQuery.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
           }
         });
        jQuery.ajax({
           url: "{{ $url }}",
           method: 'post',
            contentType: false,
            processData: false,
            async: false,
           dataType: "json",
           data: form_data,
           success: function(data){
                 if(data.errors) {
                     jQuery.each(data.errors, function(key, value){
                         $('#'+key+'-err').html(value[0]);
                         jQuery('#form-'+key).addClass('is-invalid');
                         jQuery('.invalid-feedback').show();
                     });
                 } else if(data.status_code == 200) {
                    location.href = '';
                 } else {
                     $('#fail-create').html(data.success);
                     jQuery('.invalid-feedback').show();
                 }
             }
           });
        });

    $("#{{ isset($modal_id) ? $modal_id : '' }}").on("hidden.bs.modal", function () {
        jQuery('.invalid-feedback').html("");
        jQuery('.invalid-feedback').hide();
        jQuery('#form-name').removeClass('is-invalid');
        jQuery('#form-document').removeClass('is-invalid');
    });
});

</script>
@endpush

<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - {{ ($action == 'add') ? 'Add' : 'Edit' }} Property Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" enctype="multipart/form-data" id="form-x">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
                <div class="modal-body" style="padding-left: 20px;min-height:190px">
                    <span class="invalid-feedback font-weight-bold" role="alert" id="fail-create"></span>
                    <input type="hidden" class="form-control" name="client_id" id="client-id" value="{{ isset($client_id) ? $client_id : '' }}">
                    <input type="hidden" class="form-control" id="action" value="{{ isset($action) ? $action : '' }}">
                    @if($action == 'edit')
                    <input type="hidden" class="form-control" name="zone_id" id="zone-id" value="{{ isset($zone) ? $zone->id : '' }}">
                    @include('forms.form_text',['title' => 'Shine Reference:','width_label' => 4, 'data' => isset($zone) ? $zone->reference : ''])
                    @endif
                    @include('forms.form_input',['title' => 'Group Name:', 'name' => 'zone_name','id' => 'form-zone_name', 'width_label' => 4, 'width' => 7 , 'data' => isset($zone) ? $zone->zone_name : '', 'required' => true])
                    <span class="invalid-feedback font-weight-bold hide" role="alert" id="zone_name-err" style="font-size: 100% !important;margin-left: 165px;"></span>
                    <div class="form-group row {{ ($action == 'add') ? 'mt-4' : 'mt-2' }}">
                        <label class="col-md-4 font-weight-bold" for="zone_image">Group Image:</label>
                        <input class="col-md-8" type="file" class="form-control-file" id="form-zone_image" name="zone_image" value="{{ !($action == 'add') ? CommonHelpers::getFile($zone->id, ZONE_PHOTO) : '' }}">
                        <span class="invalid-feedback font-weight-bold" role="alert" id="zone_image-err" style="font-size: 100% !important;margin-left: 165px;"></span>
                        </input>
                    </div>
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn light_grey_gradient shine_submit_modal" id="submit_zone">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#submit_zone').click(function(e){
        e.preventDefault();
        jQuery('.invalid-feedback').html("");
        jQuery('#form-zone_image').removeClass('is-invalid');
        jQuery('#form-zone_name').removeClass('is-invalid');
        var zone_id = jQuery('#zone-id').val();
        var client_id = jQuery('#client-id').val();
        var action = jQuery('#action').val();
        var form_data = new FormData($('#form-x')[0]);

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
                    // if (action == 'add') {
                    //     location.href = '/zone/' + data.id + '?client_id=' + client_id;
                    // } else {
                       location.href = '';
                    // }
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
        jQuery('#form-zone_name').removeClass('is-invalid');
        jQuery('#form-zone_image').removeClass('is-invalid');
    });
});

</script>
@endpush
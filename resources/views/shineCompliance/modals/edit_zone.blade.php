<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? \ComplianceHelpers::getComplianceNavColor($color) : 'red_color' }}">
                <h5 class="modal-title" id="exampleModalLabel_{{$dataRow->id}}">shinePrism - {{ ($action == 'add') ? 'Add' : 'Edit' }} Property Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" enctype="multipart/form-data" id="form-x_{{$dataRow->id}}">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
                <div class="modal-body" style="padding-left: 20px;min-height:190px">
                    <span class="invalid-feedback font-weight-bold" role="alert" id="fail-create_{{$dataRow->id}}"></span>
                    <input type="hidden" class="form-control" name="client_id" id="client-id" value="{{ isset($client_id) ? $client_id : '' }}">
                    <input type="hidden" class="form-control" id="action" value="{{ isset($action) ? $action : '' }}">
                    @if($action == 'edit')
                    <input type="hidden" class="form-control" name="zone_id" id="zone-id" value="{{ isset($zone) ? $zone->id : '' }}">
                    @include('forms.form_text',['title' => 'Shine Reference:','width_label' => 4, 'data' => isset($zone) ? $zone->reference : ''])
                    @endif
                    @include('forms.form_input',['title' => 'Group Name:', 'name' => 'zone_name','id' => 'form-zone_name_'.$dataRow->id, 'width_label' => 4, 'width' => 7 , 'data' => isset($zone) ? $zone->zone_name : '', 'required' => true])
                    <span class="invalid-feedback font-weight-bold zone_name-err hide" role="alert" style="font-size: 100% !important;margin-left: 165px;"></span>
                    <div class="form-group row {{ ($action == 'add') ? 'mt-4' : 'mt-2' }}">
                        <label class="col-md-4 font-weight-bold" for="zone_image">Group Image:</label>
                        <input class="col-md-8" type="file" class="form-control-file" id="form-zone_image_{{$dataRow->id}}" name="zone_image" value="{{ !($action == 'add') ? \ComplianceHelpers::getFile($zone->id, ZONE_PHOTO) : '' }}">
                        <span class="invalid-feedback font-weight-bold" role="alert" id="zone_image-err_{{$dataRow->id}}" style="font-size: 100% !important;margin-left: 165px;"></span>
                    </div>
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn light_grey_gradient_button shine_submit_modal fs-8pt" id="submit_zone_{{$dataRow->id}}">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#submit_zone_{{$dataRow->id}}').click(function(e){
        e.preventDefault();
        var that = $(this);
        jQuery('.invalid-feedback').html("");
        jQuery('#form-zone_image_{{$dataRow->id}}').removeClass('is-invalid');
        jQuery('#form-zone_name_{{$dataRow->id}}').removeClass('is-invalid');
        var form_data = new FormData($('#form-x_{{$dataRow->id}}')[0]);

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
                         $(that).closest('.modal-content').find('.'+key+'-err').html(value[0])
                         // $('.'+key+'-err').html(value[0]);
                         jQuery('#form-'+key+"_{{$dataRow->id}}").addClass('is-invalid');
                         $(that).closest('.modal-content').find('.invalid-feedback').show();
                     });
                 } else if(data.status_code == 200) {
                    // if (action == 'add') {
                    //     location.href = '/zone/' + data.id + '?client_id=' + client_id;
                    // } else {
                       location.href = '';
                    // }
                 } else {
                     $('#fail-create_{{$dataRow->id}}').html(data.success);
                     jQuery('.invalid-feedback').show();
                 }
             }
           });
        });

    $("#{{ isset($modal_id) ? $modal_id : '' }}").on("hidden.bs.modal", function () {
        jQuery('.invalid-feedback').html("");
        jQuery('.invalid-feedback').hide();
        jQuery('#form-zone_name_{{$dataRow->id}}').removeClass('is-invalid');
        jQuery('#form-zone_image_{{$dataRow->id}}').removeClass('is-invalid');
    });
});

</script>
@endpush

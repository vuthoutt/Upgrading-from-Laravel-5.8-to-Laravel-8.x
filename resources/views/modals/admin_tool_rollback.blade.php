<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shineArc - Survey Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-shine" id="form-roll-back-{{$unique_value}}">
            @csrf
            <div class="modal-body" style="height:130px;text-align: center;">
                <strong>Are you sure you want to roll back the following?</strong>
                <input type="hidden" id="id-{{$unique_value}}" name="id">
                <input type="hidden" id="type-{{$unique_value}}" name="type">
                <div class="mt-3">
                    <span id="description"></span>
                </div>
            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn light_grey_gradient" id="submit-rollback-{{$unique_value}}" >Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
$(document).ready(function(){
    //triggered when modal is about to be shown
    $('#{{ isset($modal_id) ? $modal_id : '' }}').on('show.bs.modal', function(e) {
        var rollback_id = $(e.relatedTarget).data('roll-id');
        var description = $(e.relatedTarget).data('description');
        var type = $(e.relatedTarget).data('type');
        $('#description').html(description);
        $('#type-{{$unique_value}}').val(type);
        $('#id-{{$unique_value}}').val(rollback_id);
    });
        $('#submit-rollback-{{$unique_value}}').click(function(e){
            e.preventDefault();
            var form_data = new FormData($('#form-roll-back-{{$unique_value}}')[0]);
            $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               }
             });
            $.ajax({
                url: "{{ route('toolbox.revert')}}",
                method: 'post',
                contentType: false,
                processData: false,
                async: false,
                dataType: "json",
                data: form_data,
                success: function(data)
                    {
                        location. reload()
                    }
                });
        });
});
</script>
@endpush
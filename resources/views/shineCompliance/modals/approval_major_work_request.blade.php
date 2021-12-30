<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:1px !important;">
            <div class="modal-header" style="color: #fff; background-color: {{$color ?? 'red'}}; border-radius:0px !important;">
                <h5 class="modal-title" id="exampleModalLabel">{{ $header }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="form-approval-{{ $type }}" class="form-shine">
            @csrf
            <div class="modal-body" style="height:140px;padding-left: 80px;padding-top: 50px">
                <strong id="title-work-{{ $type }}" ></strong>

            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn light_grey_gradient">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">

//triggered when modal is about to be shown
$('#{{ isset($modal_id) ? $modal_id : '' }}').on('show.bs.modal', function(e) {
    var work_id = $(e.relatedTarget).data('work-id');
    var work_ref = $(e.relatedTarget).data('work-ref');
    var type = $(e.relatedTarget).data('type');

    $('#title-work-{{ $type }}').html('Are you sure that you want to approval work ' + work_ref + ' ?');
    $('#form-approval-{{ $type }}').attr('action', '/work-request/approval/' + work_id);
});
</script>
@endpush

<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">{{ $header }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="GET" id="form-cancel{{ $unique ?? '' }}" class="form-shine">
            @csrf
            <div class="modal-body" style="height:140px;padding-left: 45px;padding-top: 50px">
                <strong id="title-cancel{{ $unique ?? '' }}" ></strong>
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
    var doc_id = $(e.relatedTarget).data('doc-id');

    var doc_name = $(e.relatedTarget).data('doc-name');

    $('#title-cancel{{ $unique ?? '' }}').html('Are you sure that you want to cancel document: ' + doc_name + ' ?');
    $('#form-cancel{{ $unique ?? '' }}').attr('action', '/document/cancel/' + doc_id);

});
</script>
@endpush

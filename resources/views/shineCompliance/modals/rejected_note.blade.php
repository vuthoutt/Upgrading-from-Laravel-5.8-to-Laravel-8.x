<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getComplianceNavColor($color) : 'red' }}">
                <h5 class="modal-title" id="exampleModalLabel">{{ $header }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="form-approval-{{ $type }}">
            @csrf
            <div class="modal-body" style="min-height:140px;padding-left: 80px;padding-top: 50px">
                <div  class="mb-3">
                    <strong id="survey-ref"></strong>
                </div>
                <textarea id="content" class="mb-3" style="width: 100%;min-height: 80px"></textarea>
                <span id="due-date"></span>
            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
//triggered when modal is about to be shown
$('#{{ isset($modal_id) ? $modal_id : '' }}').on('show.bs.modal', function(e) {
    var note = $(e.relatedTarget).data('note');
    var survey_ref = $(e.relatedTarget).data('survey-ref');
    var due_date = $(e.relatedTarget).data('due-date');

    $('#{{ isset($modal_id) ? $modal_id : '' }} #survey-ref').html(survey_ref);
    $('#{{ isset($modal_id) ? $modal_id : '' }} #content').html(note);
    $('#{{ isset($modal_id) ? $modal_id : '' }} #due-date').html(due_date);
});
</script>
@endpush

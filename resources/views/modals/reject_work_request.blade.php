<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism – Work Request Rejection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" class="form-shine" id="form-reject-work-{{ $type }}">
                @csrf
                <div class="modal-body" style="min-height:140px;padding-left: 45px;padding-top: 50px">
                    <strong id="title-reject-work" ></strong>
                    <input type="hidden" name="id" id="work_id">
                    <div class="row register-form mt-4 mb-4">
                        <label class="col-md-4 col-form-label text-md-left font-weight-bold">
                            Rejection Comment:
                        </label>
                        <div  class="col-md-7">
                            <textarea name="note" style="width: 100%;min-height: 80px;border-radius: 6px"></textarea>
                        </div>
                    </div>
                    <div class="row register-form">
                        <label for="email" class="col-md-4 col-form-label text-md-left font-weight-bold">
                        Deadline Date:
                        </label>
                        <div class="col-md-5">
                            <div class="form-group ">
                                <input class="form-control" name="due_date" id="work_due_date" width="276" value="{{ date('d/m/Y') }}">
                            </div>
                        </div>
                    </div>

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
    $('#work_due_date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy',
        todayHighlight: true
    });

    var work_id = $(e.relatedTarget).data('work-id');

    var work_ref = $(e.relatedTarget).data('work-ref');
    // var due_date = $(e.relatedTarget).data('due-date');
    var type = $(e.relatedTarget).data('type');
    $('#work_id').val(work_id);

    $('#title-reject-work').html('Are you sure that you want to reject the following work request ' +  work_ref + ' ?');
    // $('#work_due_date').val(due_date);
    $('#form-reject-work-{{ $type }}').attr('action', '/work-request/reject/' + work_id);


});
</script>
@endpush

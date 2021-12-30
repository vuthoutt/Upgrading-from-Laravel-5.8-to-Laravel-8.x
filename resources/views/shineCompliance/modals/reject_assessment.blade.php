<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important;">
            <div class="modal-header" style="color: #fff; background-color: {{$color ?? 'orange'}}; border-radius:0px !important;">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism – Add Rejection Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" class="form-shine" id="form-approval-wr-{{ $type }}">
                @csrf
                <div class="modal-body" style="min-height:140px;padding-left: 45px;padding-top: 50px">
                    <strong id="title-reject-assessment" ></strong>
{{--                    <input type="hidden" name="id" id="doc_id">--}}
                    <div class="row register-form mt-4 mb-4">
                        <label class="col-md-4 col-form-label text-md-left font-weight-bold">
                            Rejection Comment:
                        </label>
                        <div  class="col-md-7">
                            <textarea name="comment" style="width: 100%;min-height: 80px;border-radius: 6px"></textarea>
                        </div>
                    </div>
                    <div class="row register-form">
                        <label for="email" class="col-md-4 col-form-label text-md-left font-weight-bold">
                        Due Date:
                        </label>
                        <div class="col-md-5">
                            <div class="form-group ">
                                <input class="form-control" name="due_date" id="assessment_due_date" width="276" value="{{ date('d/m/Y') }}">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn light_grey_gradient_button fs-8pt">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">

//triggered when modal is about to be shown
$('#{{ isset($modal_id) ? $modal_id : '' }}').on('show.bs.modal', function(e) {
    $('#assessment_due_date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy',
        todayHighlight: true
    });

    var assessment_id = $(e.relatedTarget).data('assessment-id');
    var assessment_ref = $(e.relatedTarget).data('assessment-ref');
    // var due_date = $(e.relatedTarget).data('due-date');
    var type = $(e.relatedTarget).data('type');

    $('#title-reject-assessment').html('Are you sure that you want to reject assessment ' +  assessment_ref + ' ?');
    // $('#survey_due_date').val(due_date);
    $('#form-approval-wr-{{ $type }}').attr('action', '/compliance/assessment/' + assessment_id + '/reject');
});
</script>
@endpush

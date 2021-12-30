<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism – Add Rejection Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  method="POST" class="form-shine" id="form-reject-wr-{{ $type }}">
                @csrf
                <div class="modal-body" style="min-height:140px;padding-left: 45px;padding-top: 50px">
                    <strong id="title-reject-survey"></strong>
                    <input type="hidden" name="id" id="doc_id">
                    <div class="row register-form mt-4 mb-4">
                        <label class="col-md-4 col-form-label text-md-left font-weight-bold">
                            Rejection Types Options:
                        </label>
                        <div  class="col-md-7">
                            <div class="form-group ">
                                @include('forms.form_multi_select_rejection_type',[ 'name' => 'rejection_type_ids', 'id' => 'rejection_type_ids', 'data_multis' => $rejection_types ?? [], 'selected' => '', 'data_other' => '' ])
                            </div>
                        </div>
                    </div>
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
                        Due Date:
                        </label>
                        <div class="col-md-5">
                            <div class="form-group ">
                                <input class="form-control" name="due_date" id="survey_due_date" width="276" value="{{ date('d/m/Y') }}">
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
    $('#survey_due_date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy',
        todayHighlight: true
    });

    var survey_id = $(e.relatedTarget).data('survey-id');
    var survey_ref = $(e.relatedTarget).data('survey-ref');
    // var due_date = $(e.relatedTarget).data('due-date');
    var type = $(e.relatedTarget).data('type');

    $('#title-reject-survey').html('Are you sure that you want to reject survey ' +  survey_ref + ' ?');
    // $('#survey_due_date').val(due_date);
    $('#form-reject-wr-{{ $type }}').attr('action', '/surveys/reject/' + survey_id);


});
</script>
@endpush

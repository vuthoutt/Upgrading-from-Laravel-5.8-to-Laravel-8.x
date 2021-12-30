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
            <form class="form-shine" id="form_submit_reject_doc{{ $unique ?? '' }}">
                <div class="modal-body" style="min-height:140px;padding-left: 45px;padding-top: 50px">
                    <strong id="project-title-reject{{ $unique ?? '' }}" ></strong>
                    <input type="hidden" name="id" id="project_reject_doc_id{{ $unique ?? '' }}">
                    <div class="row register-form mt-4 mb-4">
                        <textarea name="note" class="col-md-7 offset-md-4" style="width: 70%;min-height: 80px;margin-left: 170px;border-radius: 6px"></textarea>
                    </div>
                    <div class="row register-form">
                        <label for="email" class="col-md-4 col-form-label text-md-left font-weight-bold">
                        Deadline Date:
                        </label>
                        <div class="col-md-5">
                            <div class="form-group ">
                                <input class="form-control" name="deadline" id="project_reject_doc_date{{ $unique ?? '' }}" width="276">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                    <button id="submit_reject_doc{{ $unique ?? '' }}" class="btn light_grey_gradient">Submit</button>
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
    $('#project_reject_doc_date{{ $unique ?? '' }}').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy',
        todayHighlight: true
    });

    var doc_id = $(e.relatedTarget).data('doc-id');
    var doc_name = $(e.relatedTarget).data('doc-name');
    var doc_date = $(e.relatedTarget).data('doc-date');

    $('#project-title-reject{{ $unique ?? '' }}').html('Are you sure that you want to reject document: ' + doc_name + ' ?');
    $('#project_reject_doc_date{{ $unique ?? '' }}').val(doc_date);
    $('#project_reject_doc_id{{ $unique ?? '' }}').val(doc_id);

    $('#submit_reject_doc{{ $unique ?? '' }}').click(function(e){
        // e.preventDefault();
        var plan_date = $('#project_reject_doc_date{{ $unique ?? '' }}').val();
        var form_data = new FormData($('#form_submit_reject_doc{{ $unique ?? '' }}')[0]);
        form_data.append('deadline', plan_date);

        $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
           }
         });
        $.ajax({
           url: "{{ $url }}",
           method: 'post',
            contentType: false,
            processData: false,
            async: false,
           dataType: "json",
           data: form_data,
           success: function(data){
                location.reload();
             }
           });
        });
    });
});
</script>
@endpush
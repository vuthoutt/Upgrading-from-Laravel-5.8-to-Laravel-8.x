<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - {{ $title ?? 'Add Training Records Document' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" id="form-doc-{{ $doc_type }}">
            @csrf
            <div class="modal-body" style="padding-left: 30px;min-height:140px">
                <input type="hidden" class="form-control" name="doc_type" value="{{ $doc_type }}">
                <input type="hidden" class="form-control" name="client_id" value="{{ $client_id }}">
                @include('forms.form_input',['title' => 'Document Title:', 'name' => 'name', 'id' => 'form-name'.$doc_type , 'width' => 7,'width_label' => 4 , 'data' => '', 'required' => true])
                <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="name-err{{ $doc_type }}" ></span>
                @include('forms.form_datepicker',['title' => 'Date:', 'name' => 'traning_date','id' => 'traning_date'.$doc_type, 'width_label' => 4, 'data' => date('d/m/Y') ])
                <div class="row register-form">
                    <label class="col-md-4 font-weight-bold" for="document">Document:<span style="color: red;"> *</span></label>
                    <input class="col-md-8" type="file" class="form-control-file" id="form-document{{ $doc_type }}" name="document" value="">
                    <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="document-err{{ $doc_type }}"></span>
                    </input>
                </div>

            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button id="document_add_{{ $doc_type }}" class="btn light_grey_gradient shine_submit_modal" >Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
<script type="text/javascript">
$(document).ready(function(){
    $('#document_add_{{ $doc_type }}').click(function(e){
        e.preventDefault();
        $('.invalid-feedback').html("");
        $('#form-document{{ $doc_type }}').removeClass('is-invalid');
        $('#form-name{{ $doc_type }}').removeClass('is-invalid');
        var traning_date = $('#traning_date{{ $doc_type ?? '' }}').val();
        var form_data = new FormData($('#form-doc-{{ $doc_type }}')[0]);
        form_data.append('traning_date', traning_date);
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
                 if(data.errors) {
                     $.each(data.errors, function(key, value){
                         $('#'+key+'-err{{ $doc_type }}').html(value[0]);
                         $('#form-'+key + '{{ $doc_type }}').addClass('is-invalid');
                         $('.invalid-feedback').show();
                     });
                 } else if(data.status_code == 200) {
                    location.reload();
                 } else {
                     $('#fail-create').html(data.success);
                     $('.invalid-feedback').show();
                 }
             }
           });
        });

    $("#{{ isset($modal_id) ? $modal_id : '' }}").on("hidden.bs.modal", function () {
        $('.invalid-feedback').hide();
        $('#form-name{{ $doc_type }}').removeClass('is-invalid');
        $('#form-document{{ $doc_type }}').removeClass('is-invalid');
    });
});

</script>
@endpush
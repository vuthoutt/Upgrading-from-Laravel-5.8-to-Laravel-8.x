<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - Add Resource Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" id="document-add-{{ $unique }}">
            @csrf
            <div class="modal-body" style="padding-left: 30px;min-height:140px;width: 530px;">
                <input type="hidden" class="form-control" name="category" value="{{ $id }}">
                @include('forms.form_input',['title' => 'Document Title:', 'name' => 'name', 'id' => 'form-name'.$unique, 'width' => 7,'width_label' => 4 , 'data' => '', 'required' => true])
                <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="name-err{{ $unique }}"></span>
                @include('forms.form_datepicker',['title' => 'Date:', 'name' => 'date','id' => 'historic_date'.$unique  ,'width_label' => 4, 'data' => date('d/m/Y') ])
                <div class="row register-form">
                    <label class="col-md-4 font-weight-bold" >Document:<span style="color: red;"> *</span></label>
                    <input class="col-md-8" type="file" class="form-control-file" id="form-document{{ $unique }}" name="document" value="">
                    <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="document-err{{ $unique }}"></span>
                    </input>
                </div>


            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button id="add_historical_doc_{{$unique}}" class="btn light_grey_gradient shine_submit_modal" >Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
<script type="text/javascript">
$(document).ready(function(){
    $('#add_historical_doc_{{$unique}}').click(function(e){
        e.preventDefault();
        $('.invalid-feedback').html("");
        $('#form-document{{ $unique }}').removeClass('is-invalid');
        $('#form-name{{ $unique }}').removeClass('is-invalid');


        var form_data = new FormData($('#document-add-{{ $unique }}')[0]);

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
                         $('#'+key+'-err{{ $unique }}').html(value[0]);
                         $('#form-'+key+ '{{ $unique }}').addClass('is-invalid');
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
        $('#form-name{{ $unique }}').removeClass('is-invalid');
        $('#form-document{{ $unique }}').removeClass('is-invalid');

    });
});

</script>
@endpush
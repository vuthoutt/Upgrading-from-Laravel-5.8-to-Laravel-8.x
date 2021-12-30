<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - Edit Historical Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" id="document-edit-{{ $data->id }}">
            @csrf
            <div class="modal-body" style="padding-left: 30px;min-height:140px;min-width: 530px;">
                <input type="hidden" class="form-control" name="property_id" value="{{ $propertyData->id ?? 0 }}">
                <input type="hidden" class="form-control" name="id" value="{{ $data->id ?? null }}">
                @include('forms.form_text',['title' => 'Document Reference:', 'width' => 7,'width_label' => 4 , 'data' => $data->reference])
                @include('forms.form_input',['title' => 'Document Title:', 'name' => 'name', 'id' => 'form-name'.$data->id, 'width' => 7,'width_label' => 4 , 'data' => $data->name, 'required' => true])
                <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="name-err{{ $data->id }}"></span>

                @include('forms.form_dropdown',['title' => 'Document Type:',
                                            'data' => $historical_doc_type ,
                                            'width_label' => 4,
                                            'width' => 7,
                                            'name' => 'document_type',
                                            'id' => 'form-doc-type',
                                            'key'=> 'id',
                                            'value'=>'description',
                                            'compare_value' => $data->doc_type,
                                            'required' => true ])

                @include('forms.form_dropdown',['title' => 'Document Category:',
                                            'data' => $historical_categories ,
                                            'width_label' => 4,
                                            'width' => 7,
                                            'name' => 'category',
                                            'id' => 'form-category'.$data->id,
                                            'key'=> 'id',
                                            'value'=>'category',
                                            'compare_value' => $data->category,
                                            'required' => true ])
                <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="category-err{{ $data->id }}"></span>
                <div class="row register-form">
                    <label class="col-md-4 font-weight-bold" >Document:<span style="color: red;"> *</span></label>
                    <input class="col-md-8" type="file" class="form-control-file" id="form-document{{ $data->id }}" name="document" value="">
                    <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="document-err{{ $data->id }}"></span>
                    </input>
                </div>
                {{-- @include('forms.form_checkbox',['title' => 'Management Survey PDF:', 'data' => $data->is_external_ms,'compare' => 1, 'name' => 'is_external_ms', 'width_label' => 4 ]) --}}
                @include('forms.form_datepicker',['title' => 'Date:', 'name' => 'historic_date','id' => 'historic_date'.$data->id  ,'width_label' => 4, 'data' => $data->added ])

            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button id="submit_historical_doc_{{$data->id}}" class="btn shine_submit_modal light_grey_gradient" >Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
<script type="text/javascript">
$(document).ready(function(){
    $('#submit_historical_doc_{{$data->id}}').click(function(e){
        e.preventDefault();
        $('.invalid-feedback').html("");
        $('#form-document{{ $data->id }}').removeClass('is-invalid');
        $('#form-name{{ $data->id }}').removeClass('is-invalid');
        $('#form-category{{ $data->id }}').removeClass('is-invalid');
        var historic_date = $('#historic_date{{ $data->id ?? '' }}').val();
        var form_data = new FormData($('#document-edit-{{ $data->id }}')[0]);
        form_data.append('historic_date', historic_date);

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
                         $('#'+key+'-err{{ $data->id }}').html(value[0]);
                         $('#form-'+key+ '{{ $data->id }}').addClass('is-invalid');
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
        $('#form-name{{ $data->id }}').removeClass('is-invalid');
        $('#form-document{{ $data->id }}').removeClass('is-invalid');
        $('#form-category{{ $data->id }}').removeClass('is-invalid');
    });
});

</script>
@endpush
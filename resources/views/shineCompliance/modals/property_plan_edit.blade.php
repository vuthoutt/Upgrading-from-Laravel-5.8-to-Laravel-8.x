<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important;">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getComplianceNavColor($color) : 'orange_color' }}" style="color: #fff; border-radius:0px !important;">
                @if(isset($surveyor_note))
                    <h5 class="modal-title" id="exampleModalLabel">shineArc - Edit Site Note Document</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">shineArc - Edit Site Plan Document</h5>
                @endif
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" id="plan-edit{{ $doc_type.$data->id }}">
            @csrf
            <div class="modal-body" style="padding-left: 30px;min-height:140px">
                @if(isset($surveyor_note))
                    <input type="hidden" class="form-control" name="type" value="assessor_note">
                @else
                    <input type="hidden" class="form-control" name="type" value="assessor_plan">
                @endif
                <input type="hidden" class="form-control" name="id" value="{{ $data->id }}">
                <input type="hidden" class="form-control" name="property_id" value="{{ $data->property_id }}">
                <input type="hidden" class="form-control" name="assess_id" value="{{ ($data->assess_id ?? '') ? ($data->assess_id ?? '') : ($data->survey_id ?? '')}}">
                @include('shineCompliance.forms.form_input_modal',['title' => isset($doc_type) ? 'Reference:' : 'Plan Reference:',
                                            'name' => 'plan_reference',
                                            'id' => 'form-name'.$data->id,
                                            'width' => 7,
                                            'width_label' => 4 ,
                                            'data' => isset($reference) ? $data->{$reference} : $data->plan_reference,
                                            'required' => true
                                            ])

                <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="name-err{{ $data->id }}"></span>
                @include('shineCompliance.forms.form_datepicker',['title' => 'Date:',
                                                 'name' => 'plan_date',
                                                 'id' => 'plan_date'.$doc_type.$data->id,
                                                 'width_label' => 4,
                                                 'data' => isset($date) ? $data->{$date} : $data->plan_date ])

                <div class="row register-form">
                    <label class="col-md-4 font-weight-bold" for="document">{{ isset($doc_type) ? 'Document:' : 'Plan Document:' }}:<span style="color: red;"> *</span></label>
                    <input class="col-md-8" type="file" class="form-control-file" id="form-document{{$data->id}}" name="document" value="">
                    <div style="font-size: 100% !important;padding-left: 175px;width: 90%;">
                        <strong >File present: </strong>
                        {{ ($data->document_present == 1) ? \CommonHelpers::getFileName($data->id, $file ?? PLAN_FILE ) : 'No file present'}}
                    </div>
                    <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="document-err{{$data->id}}"></span>
                    </input>
                </div>

                <div class="row register-form mt-4">
                    <label class="col-md-4 font-weight-bold" for="document">Description:</label>
                    <div class="col-md-8">
                        <textarea name="description" style="min-height: 80px;width: 95%">{{ isset($description) ? $data->{$description} : $data->description }}</textarea>
                    </div>
                    <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="document-err{{$data->id}}"></span>
                </div>
            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                <button id="submit_plan_edit{{ $doc_type.$data->id }}" class="btn light_grey_gradient_button fs-8pt shine_submit_modal" >Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
$(document).ready(function(){
    $('#submit_plan_edit{{ $doc_type.$data->id }}').click(function(e){
        e.preventDefault();
        $('.invalid-feedback').html("");
        $('#form-document').removeClass('is-invalid');
        $('#form-name').removeClass('is-invalid');
        var plan_date = $('#plan_date{{ $doc_type.$data->id ?? '' }}').val();
        var form_data = new FormData($('#plan-edit{{ $doc_type.$data->id }}')[0]);
        form_data.append('plan_date', plan_date);

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
                         $('#'+key+'-err{{$data->id}}').html(value[0]);
                         $('#form-'+ key + {{$data->id}}).addClass('is-invalid');
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
        $('#form-name{{$data->id}}').removeClass('is-invalid');
        $('#form-document{{$data->id}}').removeClass('is-invalid');
    });
});

</script>
@endpush

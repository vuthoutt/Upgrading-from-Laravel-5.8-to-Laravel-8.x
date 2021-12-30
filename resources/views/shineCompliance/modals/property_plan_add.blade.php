<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important;">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getComplianceNavColor($color) : 'orange_color' }}" style="color: #fff; border-radius:0px !important;">
                @if(isset($assessNotes))
                    <h5 class="modal-title" id="exampleModalLabel">shineArc - {{ $title ?? 'Site Note Document' }}</h5>
                @else
                    <h5 class="modal-title" id="exampleModalLabel">shineArc - {{ $title ?? 'Site Plan Document' }}</h5>
                @endif
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" id="plan-add{{ $doc_type ?? '' }}">
                @csrf
                <div class="modal-body" style="padding-left: 30px;min-height:140px">
                    @if(isset($assessNotes))
                        <input type="hidden" class="form-control" name="type" value="assessor_note">
                    @else
                        <input type="hidden" class="form-control" name="type" value="assessor_plan">
                    @endif
                    <input type="hidden" class="form-control" name="property_id" value="{{ isset($assessment) ? $data_site->property_id ?? '' : '' }}">
                    <input type="hidden" class="form-control" name="assess_id" value="{{ isset($assessment) ? ($data_site->id ?? '') : 0 }}">
                    @include('shineCompliance.forms.form_input_modal',['title' => $reference ?? 'Plan Reference:', 'name' => 'plan_reference', 'id' => 'form-name-add'.($doc_type ?? '') , 'width' => 7,'width_label' => 4 , 'data' => '', 'required' => true])
                    <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="name-add-err{{ $doc_type ?? '' }}" ></span>
                    @include('shineCompliance.forms.form_datepicker',['title' => 'Date:', 'name' => 'plan_date', 'id' => 'plan_date'.($doc_type ?? ''), 'width_label' => 4, 'data' => date('d/m/Y') ])
                    <div class="row register-form">
                        <label class="col-md-4 font-weight-bold" for="document">{{ $doc_title ?? 'Plan Document' }}:<span style="color: red;"> *</span></label>
                        <input class="col-md-8" type="file" class="form-control-file" id="form-document{{ $doc_type ?? '' }}" name="document" value="">
                        <span class="invalid-feedback font-weight-bold" role="alert" id="document-add-err{{ $doc_type ?? '' }}" style="font-size: 100% !important;padding-left: 175px;"></span>
                    </div>

                    <div class="row register-form mt-4">
                        <label class="col-md-4 font-weight-bold" for="document">Description:</label>
                        <div class="col-md-8">
                            <textarea name="description" style="min-height: 80px;width: 95%;border-radius: 5px"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button id="submit_plan_add{{ $doc_type ?? '' }}" class="btn light_grey_gradient_button shine_submit_modal fs-8pt" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#submit_plan_add{{ $doc_type ?? '' }}').click(function(e){
                e.preventDefault();
                $('.invalid-feedback').html("");
                $('#form-document').removeClass('is-invalid');
                $('#form-name').removeClass('is-invalid');

                var form_data = new FormData($('#plan-add{{ $doc_type ?? '' }}')[0]);

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
                                $('#'+key+'-add-err{{ $doc_type ?? '' }}').html(value[0]);
                                $('#form-'+key+'-add{{ $doc_type ?? '' }}').addClass('is-invalid');
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
                $('#form-name-add{{ $doc_type ?? '' }}').removeClass('is-invalid');
                $('#form-document{{ $doc_type ?? '' }}').removeClass('is-invalid');
            });
        });

    </script>
@endpush

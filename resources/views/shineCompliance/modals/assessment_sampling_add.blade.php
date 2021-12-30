<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important;">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getComplianceNavColor($color) : 'orange_color' }}" style="color: #fff; border-radius:0px !important;">
                <h5 class="modal-title" id="exampleModalLabel">shineArc - Sample Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" id="plan-add">
                @csrf
                <div class="modal-body" style="padding-left: 30px;min-height:140px">
                    <input type="hidden" class="form-control" name="property_id" value="{{ isset($assessment) ? $data_site->property_id ?? '' : $data_site->id ?? '' }}">
                    <input type="hidden" class="form-control" name="assess_id" value="{{ isset($assessment) ? ($data_site->id ?? '') : 0 }}">
                    @include('shineCompliance.forms.form_input_modal',['title' => $reference ?? 'Reference:', 'name' => 'sample_reference', 'id' => 'form-name-add', 'width' => 7,'width_label' => 4 , 'data' => '', 'required' => true])
                    <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="name-add-err" ></span>
                    @include('shineCompliance.forms.form_datepicker',['title' => 'Date:', 'name' => 'date', 'id' => 'date', 'width_label' => 4, 'data' => date('d/m/Y') ])
                    <div class="row register-form">
                        <label class="col-md-4 font-weight-bold" for="document">Document:<span style="color: red;"> *</span></label>
                        <input class="col-md-8" type="file" class="form-control-file" id="form-document" name="document" value="">
                        <span class="invalid-feedback font-weight-bold" role="alert" id="document-add-err" style="font-size: 100% !important;padding-left: 175px;"></span>
                        </input>
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
                    <button id="submit_plan_add" class="btn light_grey_gradient_button shine_submit_modal fs-8pt" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#submit_plan_add').click(function(e){
                e.preventDefault();
                $('.invalid-feedback').html("");
                $('#form-document').removeClass('is-invalid');
                $('#form-name').removeClass('is-invalid');

                var form_data = new FormData($('#plan-add')[0]);

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
                                $('#'+key+'-add-err').html(value[0]);
                                $('#form-'+key+'-add').addClass('is-invalid');
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
                $('#form-name-add').removeClass('is-invalid');
                $('#form-document').removeClass('is-invalid');
            });
        });

    </script>
@endpush

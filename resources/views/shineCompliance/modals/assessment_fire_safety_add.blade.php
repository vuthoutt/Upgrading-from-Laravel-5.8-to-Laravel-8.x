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
            <form action="{{ $url }}" method="POST" id="fire-safety-add-form">
                @csrf
                <input type="hidden" name="assess_id" value="{{ $data->id }}">
                <div class="modal-body" style="padding-left: 30px;min-height:140px">
                    <div class="row register-form">
                        <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Fire Safety Equipment & System:</label>
                        <div class="col-md-9">
                            @include('shineCompliance.forms.form_multi_select_contruction',['name' => 'fire_safety', 'id' => 'fire_safety','data_multis' => $fireSafetyAnswer,
                                            'selected' => explode(',', $data->assessmentInfo->fire_safety ?? ''), 'data_other' => $data->assessmentInfo->fire_safety_other ?? ''])
                        </div>
                    </div>
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button id="submit_fire_safety_add" class="btn light_grey_gradient_button shine_submit_modal fs-8pt" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#submit_fire_safety_add').click(function(e){
                e.preventDefault();
                $('.invalid-feedback').html("");
                $('#form-document').removeClass('is-invalid');
                $('#form-name').removeClass('is-invalid');

                var form_data = new FormData($('#fire-safety-add-form')[0]);

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

            $("#{{ isset($modal_id) ? $modal_id : '' }}").on("hidden.bs.modal", function () {
                $('.invalid-feedback').hide();
                $('#form-name-add').removeClass('is-invalid');
                $('#form-document').removeClass('is-invalid');
            });
        });

    </script>
@endpush

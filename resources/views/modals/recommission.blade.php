<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 550px">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - {{ $header }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ $url ?? '' }}" id="{{$decommission_type}}_decommission_reason_form" class="form-shine">
            @csrf
            <div class="modal-body" style="height:140px;padding-left: 20px">
                <div style="color: red;margin-bottom: 20px;padding-left: 20px;text-align: center;padding-right: 20px;">
                    <strong>{{ \CommonHelpers::getRecommissionReasonWarning($decommission_type) }}</strong>
                </div>
                <div class="row register-form" id="not-assessed-reason-form">
                    <label class="col-md-5 col-form-label text-md-left font-weight-bold" >
                        Reason for Recommission
                    <span style="color: red;">*
                    </label>
                    <div class="col-md-7">
                        <div class="form-group ">
                                <select  class="form-control @error($name) is-invalid @enderror" name="{{ $name }}" id="{{ $name }}">

                                </select>
                                <span style="color:red" class="warning_reason">
                                    Please choose a reason before submit!
                                </span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn light_grey_gradient" id="{{$decommission_type}}_decommission_reason_submit">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
    $(document).ready(function(){
        decommission_reason();
        function decommission_reason() {
            $.ajax({
                type: "GET",
                url: "/ajax/recommission-reason?type={{ $decommission_type }}",
                cache: false,
                success: function (html) {
                    $.each( html.data, function( key, value ) {
                        selected = false;
                        if ({{ $compare ?? '-1'}} == value.id) {
                            selected = true;
                        }
                        $('#{{ $name }}').append($('<option>', {
                            value: value.id,
                            text : value.description,
                            selected : selected
                        }));
                    });
                }
            });
        }

        $(".warning_reason").hide()
        $('#{{$decommission_type}}_decommission_reason_submit').click(function(){
                var reason = $('#{{ $name }}').val();
                if (reason == 0) {
                    $(".warning_reason").show();
                } else {
                    $(".warning_reason").hide();
                    $('#{{$decommission_type}}_decommission_reason_form').submit();
                }
          });
        $("#{{ isset($modal_id) ? $modal_id : '' }}").on("hidden.bs.modal", function () {
             $(".warning_reason").hide()
        });
    });
</script>
@endpush
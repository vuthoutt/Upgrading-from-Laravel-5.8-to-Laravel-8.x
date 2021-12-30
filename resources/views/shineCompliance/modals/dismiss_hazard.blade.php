<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="color: #fff; background-color: {{$color ?? 'orange'}}; border-radius:0px !important;">
                <h5 class="modal-title" id="exampleModalLabel">{{ $header }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="form-approval-{{ $type }}" class="form-shine">
            @csrf
            <div class="modal-body" style="height:140px;padding-left: 80px;padding-top: 20px">
                <strong id="title-ques-survey-{{ $type }}" ></strong>
                <strong class="">Which type of Decommission Warning would you like to use?</strong>
                <div class="row register-form" id="not-assessed-reason-form" style="text-align: center;">
                    <div class="offset-md-2 col-md-7">
                        <div class="form-group ">
                            <select  class="form-control @error($name ?? '') is-invalid @enderror" name="{{ $name ?? '' }}" id="{{ $name ?? '' }}" required>

                            </select>
                            <span style="color:red" class="warning_reason">
                                Please choose a reason before submit!
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient fs-8pt" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn light_grey_gradient fs-8pt decommission_btn">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">

//triggered when modal is about to be shown
$('#{{ isset($modal_id) ? $modal_id : '' }}').on('show.bs.modal', function(e) {
    var survey_id = $(e.relatedTarget).data('survey-id');
    var survey_ref = $(e.relatedTarget).data('survey-ref');
    var type = $(e.relatedTarget).data('type');

    $('#form-approval-{{ $type }}').attr('action', '/compliance/hazard/decommission/' + survey_id);
});
        $(document).ready(function () {
            {{--$('.decommission_btn').click(function(e){--}}
            {{--    $('#{{$modal_id}}').modal('hide');--}}
            {{--});--}}

            $("#{{ $modal_id }} .warning_reason").hide();

            decommission_reason();
            function decommission_reason() {
                $.ajax({
                    type: "GET",
                    url: "/ajax/decommission-reason?type={{ $decommission_type }}",
                    cache: false,
                    success: function (html) {
                        $('#{{ $name }}').append($('<option>', {
                            value: 0,
                            text : ''
                        }));
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

            $('#{{ $modal_id }} #{{ $name}}').change(function () {
                if ($(this).val() != undefined && $(this).val() != '') {
                    $("#{{ $modal_id }} .warning_reason").hide();
                }
            });

            $('#{{ $modal_id }} .decommission_btn').click(function(e) {
                var reason = $('#{{ $name }}').val();
                console.log(reason);
                if (reason == 0) {
                    e.preventDefault();
                    $("#{{ $modal_id }} .warning_reason").show();
                } else {
                    $("#{{ $modal_id }} .warning_reason").hide();
                    $(this).closest('form').submit();
                }
            });
            $("#{{ isset($modal_id) ? $modal_id : '' }}").on("hidden.bs.modal", function () {
                $(".warning_reason").hide()
            });
        });
</script>
@endpush

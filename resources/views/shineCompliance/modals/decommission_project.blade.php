<!-- Modal -->
<div class="modal fade pr-0" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important;">
            <div class="modal-header light_blue_gradient" style="color: #fff; border-radius:0px !important;">
                <h5 class="modal-title" id="exampleModalLabel">shineReflect - {{$header ?? ''}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{$url ?? '#'}}" method="POST" enctype="multipart/form-data">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}" />
                <div class="modal-body" style="padding-left: 20px;min-height:50px;text-align: center;">
{{--                    <strong class="">Do you want to decommission {{$reference ?? ''}} ?</strong>--}}
                    <strong class="">Which type of Decommission Warning would you like to use?</strong>
                    <div class="row register-form" id="not-assessed-reason-form" style="text-align: center;">
                        <div class="offset-md-2 col-md-7">
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
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn light_grey_gradient_button shine_document_submit fs-8pt decommission_btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
    <script type="text/javascript">
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

            $('#{{ $modal_id }} #{{ $name }}').change(function () {
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
                    // $(this).closest('form').submit();
                }
            });
            $("#{{ isset($modal_id) ? $modal_id : '' }}").on("hidden.bs.modal", function () {
                $(".warning_reason").hide()
            });
        });
    </script>
@endpush

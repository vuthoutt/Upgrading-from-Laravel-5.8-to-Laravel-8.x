<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - Job Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  method="POST">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}" />
                <div class="modal-body" style="padding-left: 20px;min-height:100px ">
                    <input type="hidden" class="form-control" name="role_id" id="role_id" value="">
                    @include('forms.form_input',['title' => 'Job Role Name:', 'name' => 'role_title','id' => 'form-title', 'width' => 7,'width_label' => 4 , 'data' => '', 'required' => true])
                    <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="role_title-err"></span>
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn light_grey_gradient shine_submit_modal" id="submit_role">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){

            $('#submit_role').click(function(e) {
                $('.invalid-feedback').html("");
                $('#form-title').removeClass('is-invalid');
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{$url ?? '' }}",
                    method: 'post',
                    data: {
                        name: $('#form-title').val(),
                        id: $('#role_id').val()
                    },
                    success: function(data){
                        if(data.errors) {
                            $.each(data.errors, function(key, value){
                                $('#role_title-err').html(value[0]);
                                $('#form-title' ).addClass('is-invalid');
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
                $('#form-title').removeClass('is-invalid');
            });

            //triggered when modal is about to be shown
            $('#{{ isset($modal_id) ? $modal_id : '' }}').on('show.bs.modal', function(e) {
                var id = $(e.relatedTarget).data('id');
                var title = $(e.relatedTarget).data('title');

                $('#form-title').val(title);
                if (id) {
                    $('#exampleModalLabel').text('shinePrism - Edit Job Role');
                    $('#role_id').val(id);
                } else {
                    $('#exampleModalLabel').text('shinePrism - Add Job Role');
                    $('#role_id').val(0);
                }

            });
        });
    </script>
@endpush

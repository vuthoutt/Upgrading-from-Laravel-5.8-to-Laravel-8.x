<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - {{ $title ?? '' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  method="POST">
            @csrf
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <div class="modal-body" style="padding-left: 20px;min-height:100px ">
                <input type="hidden" class="form-control" name="department_id" id="department_id{{ $type }}" value="">
                <input type="hidden" class="form-control" name="parent_id" id="parent_id{{ $type }}" value="{{ $parent_id }}">
                @include('forms.form_input',['title' => 'Department Name:', 'name' => 'name','id' => 'form-department_name'.($type), 'width' => 7,'width_label' => 4 , 'data' => '', 'required' => true])
                <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="department_name-err{{ $type }}"></span>
            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn light_grey_gradient shine_submit_modal" id="submit_historical_cat{{ $type }}">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
<script type="text/javascript">
$(document).ready(function(){
    $('#submit_historical_cat{{ $type }}').click(function(e){
        $('.invalid-feedback').html("");
        $('#department_name{{ $type }}').removeClass('is-invalid');
        var department_name = $('#form-department_name{{ $type }}').val();
        var department_id = $('#department_id{{ $type }}').val();
        var parent_id = $('#parent_id{{ $type }}').val();
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
             department_name: department_name,
             id: department_id,
             parent_id: parent_id
          },
          success: function(data){
                if(data.errors) {
                    $.each(data.errors, function(key, value){
                        $('#' + key + '-err{{ $type }}').html(value[0]);
                        $('#form-'+ key + '{{ $type }}' ).addClass('is-invalid');
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
        $('#form-department_name{{ $type }}').removeClass('is-invalid');
    });

    //triggered when modal is about to be shown
  $('#{{ isset($modal_id) ? $modal_id : '' }}').on('show.bs.modal', function(e) {
      var id = $(e.relatedTarget).data('id');
      var title = $(e.relatedTarget).data('title');

      $('#form-department_name{{ $type }}').val(title);
      if (id) {
        $('#department_id{{ $type }}').val(id);
      } else {
        $('#department_id{{ $type }}').val(0);
      }

  });
});

</script>
@endpush
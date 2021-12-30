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
                <input type="hidden" class="form-control" name="property_id" id="property_id" value="{{ $propertyData->id ?? 0 }}">
                <input type="hidden" class="form-control" name="category_id" id="category_id{{ $type }}" value="">
                @include('forms.form_input',['title' => 'Category  Title:', 'name' => 'category_title','id' => 'form-category_title'.($type), 'width' => 7,'width_label' => 4 , 'data' => '', 'required' => true])
                <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="category_title-err{{ $type }}"></span>
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
        $('#category_title{{ $type }}').removeClass('is-invalid');
        var category_title = $('#form-category_title{{ $type }}').val();
        var category_id = $('#category_id{{ $type }}').val();
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
             category_title: category_title,
             property_id: $('#property_id').val(),
             id: category_id
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
        $('#form-category_title{{ $type }}').removeClass('is-invalid');
    });

    //triggered when modal is about to be shown
  $('#{{ isset($modal_id) ? $modal_id : '' }}').on('show.bs.modal', function(e) {
      var id = $(e.relatedTarget).data('id');
      var title = $(e.relatedTarget).data('title');

      $('#form-category_title{{ $type }}').val(title);
      if (id) {
        $('#category_id{{ $type }}').val(id);
      } else {
        $('#category_id{{ $type }}').val(0);
      }

  });
});

</script>
@endpush
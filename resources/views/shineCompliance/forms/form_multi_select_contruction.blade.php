<div class="mt-3" id="multiple-select-{{ $id }}">
    <select class="js-example-basic-multiple js-states form-control" id="{{ $id }}" name="{{ $name}}[]" multiple="multiple" style="width:100%">
        @foreach($data_multis as $data_multi)
            <option value="{{ $data_multi["id"] }}" aria-selected="true" >{{ $data_multi["description"] }}</option>
        @endforeach
    </select>
    <input type="text" class="form-control mt-3" name="{{$name}}-other" id="{{ $id }}-other" placeholder="Please add other option" value="{{ isset($data_other) ? $data_other : ''}}">
</div>

@push('javascript')

    <script type="text/javascript">
    $(document).ready(function(){

        $('.js-example-basic-multiple').select2({
            minimumResultsForSearch: -1,
            placeholder: "Please select an option"
        });
        // override the select2 open event
          $('#{{ $id }}').on('select2:open', function () {
            // get values of selected option
            var values = $(this).val();
            // get the pop up selection
            var pop_up_selection = $('.select2-results__options');

            if (values != null ) {
              // hide the selected values
               pop_up_selection.find("li[aria-selected=true]").hide();

            } else {
              // show all the selection values
              pop_up_selection.find("li[aria-selected=true]").show();
            }

          });


        $("#{{ $id }}-other").hide();
        $("#{{ $id }}").change(function(){
            // if value = other : show other input
            var value = $("#{{ $id }}").find(":selected").text();
            if (value.includes("Other")) {
                $("#{{ $id }}-other").show();
            } else {
                $("#{{ $id }}-other").hide();
            }
        });

        //selected otion
        $('#{{ $id }}').val(<?php echo json_encode($selected); ?>);
        $('#{{ $id }}').trigger('change'); // Notify any JS components that the value changed
    });
    </script>
@endpush

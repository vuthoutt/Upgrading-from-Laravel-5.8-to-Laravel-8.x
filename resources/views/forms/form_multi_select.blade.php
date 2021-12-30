<div class="mt-3" id="multiple-select-{{ $id }}">
    <select class="js-example-basic-multiple js-states form-control" id="{{ $id }}" name="{{ $name}}[]" multiple="multiple" style="width:100%">
        @foreach($data_multis as $data_multi)
            <option value="{{ $data_multi->id }}">{{ $data_multi->description }}</option>
        @endforeach
    </select>
    <input type="text" class="form-control mt-3 other-class" name="{{ isset($name) ? $name : ''}}-other[]" id="{{ $id }}-other" placeholder="Please add other option" value="{{ isset($data_other) ? $data_other : ''}}">
</div>
@push('javascript')

    <script type="text/javascript">
    $(document).ready(function(){

        $('.js-example-basic-multiple').select2({
            minimumResultsForSearch: -1
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

        //selected option
        $('#{{ $id }}').val(<?php echo json_encode($selected_child); ?>);
        $('#{{ $id }}').trigger('change'); // Notify any JS components that the value changed

        // display in edit form if selected
        if ($("#{{ $select }}").val() == {{ $id }}) {
            $("#multiple-select-{{ $id }}").show();
        } else {
            $("#multiple-select-{{ $id }}").hide();
        }

        // if if select parent = select child : show multi select
        $("#{{ $select }}").change(function(){
            // clear old selected value
            $("#{{ $id }}").val(null).trigger('change');
            $('#multiple-select-{{ $id }}').trigger('change');
            if ($("#{{ $select }}").val() == {{ $id }}) {
                $("#multiple-select-{{ $id }}").show();
            } else {
                $("#multiple-select-{{ $id }}").hide();
            }
        });
    });
    </script>
@endpush
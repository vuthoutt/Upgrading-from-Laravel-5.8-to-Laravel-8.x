<select  class="mb-3 form-control department_select" name="departments[]" id="department">
    <option value="" data-option="0">------ Please select an option -------</option>
    @if(isset($data) and !is_null($data))
        @foreach($data as $val)
            @if(isset($type) and $type == 'site_data')
                <option value="{{ $val->{$key} }}"   {{ in_array($val->{$key}, $compare_value) ? 'selected' : ''}} >{{ $val->{$value} }}</option>
            @else
                <option value="{{ $val->{$key} }}" data-option="{{ isset($option_data) ? $val->{$option_data} : '' }}" >{{ $val->{$value} }}</option>
            @endif
        @endforeach
    @endif
</select>

@push('javascript')

    <script type="text/javascript">

    $(document).ready(function(){
        $('body').on('change', '.department_select', function(e){
            $(this).nextAll().remove();
            var client_type = $('#client_type').val();
            var department_id = $(this).val();
            var parent = $(this).closest('.form-group');
            var clone_select = $(this).clone();
            var new_id = $('.department_select').length;
            $(clone_select).prop('id', 'department' + new_id);
            $(clone_select).find('option').remove();
            $.ajax({
               url: "{{ route('ajax.department_select') }}",
               method: 'get',
               dataType: "json",
               data: { id : department_id, client_type: client_type},
               success: function(data){
                    if(data.has_child > 0 && data.data){
                        $.each( data.data, function( key, value ) {
                            $(clone_select).append($('<option>', {
                               value: value.id,
                                text : value.name
                            }));
                        });
                        $(parent).append(clone_select);
                        $(clone_select).trigger('change');
                    }
                 }
           });
        });


        // only for add case

        // edit case will append parent and selected
    });
</script>
@endpush

<div class="row register-form {{ isset($class_other) ? $class_other : '' }}" id="{{isset($id) ? $id : $name}}-form" {{isset($hide) && $hide ? "style=display:none" : ''}}>
    <label class="col-md-{{ isset($width_label) ? $width_label : 4 }} col-form-label text-md-left font-weight-bold" >{{ isset($title) ? $title : '' }}
    @if(isset($required))
        <span style="color: red;">*</span>
    @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 6 }}">
        <div class="form-group ">
                <select  class="form-control" name="zone_parent" id="{{ $id ?? '' }}">
                </select>
                <select  class="form-control mt-4" name="zone" id="{{ $id ?? '' }}_child">
                </select>
            </div>
            @error($name)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
@push('javascript')

    <script type="text/javascript">
$(document).ready(function(){
        getClientZone();
        $("#{{ $id ?? '' }}_child").hide();
        function getClientZone(){
            $.ajax({
                type: "GET",
                url: '{{ route('ajax.client_zone') }}',
                data: {client_id: 1},
                cache: false,
                success: function (html) {
                    $.each( html, function( key, value ) {
                        $("#{{ $id ?? '' }}").append($('<option>', {
                            value: value.id,
                            text : value.zone_name
                        }));
                    });
                    getZoneChild();

                }
            });
        }
        function getZoneChild() {
            $("#{{ $id ?? '' }}").change(function(){
            $("#{{ $id ?? '' }}_child").show();
                var parent_id =  $(this).val();
                $("select[name='zone']").html("");
                $.ajax({
                    type: "GET",
                    url: '{{ route('ajax.client_zone_child') }}',
                    data: {client_id: 1, parent : parent_id},
                    cache: false,
                    success: function (html) {
                        $('#{{ $id ?? '' }}_child').append($('<option>', {
                            value: 0,
                            text : ''
                        }));
                        $.each( html, function( key, value ) {
                            $("#{{ $id ?? '' }}_child").append($('<option>', {
                                value: value.id,
                                text : value.zone_name
                            }));
                        });
                    }
                });
            });
            $("#{{ $id ?? '' }}").trigger('change');
        }
});
</script>
@endpush

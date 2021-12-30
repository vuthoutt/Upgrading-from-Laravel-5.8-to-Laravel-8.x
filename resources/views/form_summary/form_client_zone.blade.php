<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >{{ isset($title) ? $title : 'Which group would you like to use?' }}
    </label>
    <div  class="form-group col-md-12">

            <select  class="form-control input-summary" name="zone_parent" id="{{ $id ?? '' }}">
            </select>
        </div>


        <div class="form-group col-md-12 mt-3">
                <select  class="form-control input-summary" name="zone" id="{{ $id ?? '' }}_child">
                </select>
        </div>

</div>
@push('javascript')
<script>
$(document).ready(function(){
        getClientZone();
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
                var parent_id =  $(this).val();
                $("select[name='zone']").html("");
                $.ajax({
                    type: "GET",
                    url: '{{ route('ajax.client_zone_child') }}',
                    data: {client_id: 1, parent : parent_id},
                    cache: false,
                    success: function (html) {
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
        };
});

</script>
@endpush
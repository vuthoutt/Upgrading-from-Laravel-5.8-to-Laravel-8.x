<div class="row register-form" id="not-assessed-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >Assessed:
    @if(isset($required))
        <span style="color: red;">*</span>
    @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
        <div class="form-group ">
            <select  class="form-control @error('not_assessed') is-invalid @enderror" name="not_assessed" id="not-assessed">
                <option value="0" {{ $compare_value == 0 ? 'selected' : '' }}>Assessed</option>
                <option value="2" {{ $compare_value == 2 ? 'selected' : '' }}>Not Assessed</option>
            </select>
            @error('not_assessed')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="row register-form" id="not-assessed-reason-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >Reason
    <span style="color: red;">*</span>
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
        <div class="form-group ">
            <select  class="form-control @error('not_assessed_reason') is-invalid @enderror" name="not_assessed_reason" id="not-assessed-reason">

            </select>
            @error('not_assessed_reason')
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
        $('body').on('change','#not-assessed',function(){
        $('#not-assessed-reason').find('option').remove();
        $("#not-assessed-reason").html("");
        var not_assessed_id = $("#not-assessed").val();

        if (not_assessed_id == 2) {
            $("#not-assessed-reason-form").show();
            $.ajax({
                type: "GET",
                url: "/ajax/not-assessed-reason?type={{ $assess_type }}",
                cache: false,
                success: function (html) {
                    $.each( html.data, function( key, value ) {
                        selected = false;
                        if ({{ $compare_reason ?? '-1'}} == value.id) {
                            selected = true;
                        }
                        $('#not-assessed-reason').append($('<option>', {
                            value: value.id,
                            text : value.description,
                            selected : selected
                        }));
                    });
                }
            });
        } else {
            $("#not-assessed-reason-form").hide();
        }
        });
        $("#not-assessed").trigger('change');
    });
</script>
@endpush

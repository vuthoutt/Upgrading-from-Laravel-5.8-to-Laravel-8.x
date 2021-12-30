<div class="row register-form">
    <label class="col-form-label text-md-left font-weight-bold">{{ isset($title) ? $title : '' }} </label>
    <div class="input-group">
        <span class="input-group-btn" style="margin-left: -13px;margin-bottom:15px">
            <span class="btn btn-default btn-file btn-file-{{ $name }}">
                <button class="btn light_grey_gradient" >Browse…</button>  <input type="file" id="imgInp-{{ $name }}" name="{{ isset($name) ? $name : '' }}">
            </span>
        </span>
        <input type="text" class="form-control" readonly style="margin-top: 9px;">
    </div>
    @if(isset($object_id))
        <img id="{{ $name }}" src="{{ asset(\ComplianceHelpers::getFileImage($object_id, $folder)) }}" class="image-item" style="width: 100%" />
    @else
        <img id="{{ $name }}" src="{{ asset(\ComplianceHelpers::getFileImage($object_id ?? 0, $folder)) }}" class="image-item"/>
    @endif
    @error($name)
    <span class="invalid-feedback" role="alert" style="display: block">
                <strong>{{ $message }}</strong>
            </span>
    @enderror
</div>

@push('javascript')

    <script type="text/javascript">
    $(document).ready(function(){
        // image updload
        $(document).on('change', '.btn-file-<?= isset($name) ? $name : '' ?> :file', function() {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [label]);
        });
        $('.btn-file-<?= isset($name) ? $name : '' ?> :file').on('fileselect', function(event, label) {
            var input = $(this).parents('.input-group').find(':text'),
                log = label;
            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#<?= isset($name) ? $name : '' ?>').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp-<?= isset($name) ? $name : '' ?>").change(function(){
            readURL(this);
        });
        //end of image upload
    });
    </script>
@endpush

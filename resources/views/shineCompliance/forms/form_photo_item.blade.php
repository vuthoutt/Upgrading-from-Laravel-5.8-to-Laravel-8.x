<div class="row register-form parent-element">
    <label class="col-form-label text-md-left font-weight-bold fs-8pt">{{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
    </label>
    <div class="input-group">
        <span class="input-group-btn" style="margin-left: -13px;margin-bottom:15px">
            <span class="btn btn-default btn-file btn-file-{{ $name }}">
                <button class="btn light_grey_gradient" >Browse…</button>  <input type="file" id="imgInp-{{ $name }}" name="{{ isset($name) ? $name : '' }}">
            </span>
        </span>
        <input type="text" class="form-control" readonly style="margin-top: 9px;">
        <span class="invalid-feedback" role="alert">
                <strong>
                     @error($name){{ $message }}
                    @enderror
                </strong>
        </span>
    </div>
    @if(isset($object_id))
        <img id="{{ $name }}" src="{{ asset(\ComplianceHelpers::getSystemFile($object_id, $folder)) }}" class="image-item" style="width: 100%" />
    @elseif(isset($photo_id))
        <img id="{{ $name }}" src="{{ asset(\CommonHelpers::getFileCertificate($photo_id)) }}" class="image-item" style="width: 100%" />
    @else
        <img id="{{ $name }}" src="{{ asset('/img/no-image-icon.png') }}" class="image-item"/>
    @endif
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

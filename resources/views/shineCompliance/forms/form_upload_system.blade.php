<div class="row register-form {{ $class_other ?? ''}}">
    <label for="first-name" class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt">{{ isset($title) ? $title : '' }} </label>
    <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-btn" style="margin-left: -13px;margin-bottom:15px">
                    <span class="btn btn-default btn-file btn-file-{{ $id ?? $name }}">
                        <button class="btn light_grey_gradient fs-8pt">Browse…</button>  <input type="file" id="imgInp-{{ $id ?? $name }}" name="{{ isset($name) ? $name : '' }}">
                    </span>
                </span>
                <input type="text" class="form-control" readonly style="margin-top: 9px;">
                @error($name)
                    <span class="invalid-feedback" role="alert" style="display: block">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            @if(isset($object_id))
                <img id="{{ $id ?? $name }}" src="{{ asset(\ComplianceHelpers::getSystemFile($object_id, $folder)) }}" class="image-signature" />
            @else
                 <img id="{{ $id ?? $name }}" src="" class="image-signature"/>
            @endif
    </div>
</div>

@push('javascript')

    <script type="text/javascript">
    $(document).ready(function(){
        // image updload
        $(document).on('change', '.btn-file-<?= isset($id) ? $id : $name ?> :file', function() {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [label]);
        });
        $('.btn-file-<?= isset($id) ? $id : $name ?> :file').on('fileselect', function(event, label) {
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
                    $('#<?= isset($id) ? $id : $name ?>').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp-<?= isset($id) ? $id : $name ?>").change(function(){
            readURL(this);
        });
        //end of image upload
    });
    </script>
@endpush

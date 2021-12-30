<div class="row {{ $class_other ?? '' }}" id="{{ $id ?? '' }}-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}</label>
    <div class="col-md-6 form-input-text" >
        @if(isset($data))
            @if($data)
                <span>Yes</span>
            @else
                <span>No</span>
            @endif
        @endif
    </div>
</div>

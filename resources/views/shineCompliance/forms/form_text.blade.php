<div class="row {{ $class_other ?? '' }}" id="{{ $id ?? ''}}-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}</label>
    <div class="col-md-6 form-input-text" >
        @if(isset($data))
            @if(isset($link))
                <a href="{{ $link }}"> {{ isset($data) ? $data : '' }} </a> <span class="font-weight-bold">{{ $other_text ?? '' }}</span>
            @else
                <span id="{{ $id ?? '' }}">{!! isset($data) ? $data : '' !!}</span>
            @endif
        @endif
    </div>
</div>

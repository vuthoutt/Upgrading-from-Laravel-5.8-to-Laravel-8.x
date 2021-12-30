<div class="row">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold" >{{ isset($title) ? $title : '' }}</label>
    <div class="col-md-6 form-input-text" >
        @if(isset($data))
            @if(isset($link))
                <a href="{{ $link }}"> {{ isset($data) ? $data : '' }} </a>
                @else
                <span id="{{ $id ?? '' }}">{!! isset($data) ? $data : '' !!}</span>
            @endif
        @endif
    </div>
</div>

<div class="row property-detail-attribute">
    <div class="col-6 property-detail-attribute-label fs-8pt">{{ isset($title) ? $title : '' }}</div>
    <div class="col-6">
        @if(isset($data))
            @if(isset($link))
                <a href="{{ $link }}"> {{ isset($data) ? $data : '' }} </a> <span class="font-weight-bold">{{ $other_text ?? '' }}</span>
            @else
                <span id="{{ $id ?? '' }}">{!! isset($data) ? $data : '' !!}</span>
            @endif
        @endif
    </div>
</div>

<div class="row property-detail-attribute">
    <div class="col-6 property-detail-attribute-label">{!! $title ?? '' !!}</div>
    @if(isset($link))
        <div class="col-6"><a href="{{$link}}">{{$data ?? ''}}</a></div>
    @else
        <div class="col-6">{{$data ?? ''}}</div>
    @endif
</div>

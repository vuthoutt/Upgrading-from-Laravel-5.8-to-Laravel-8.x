@if(!is_null($data))
    <ul>
        @foreach($data as $item)
            <li data-id="{{$item->id}}" data-item-id="{{ $item->id }}" data-item-location-id="{{ $item->location_id }}" class="{{ $item->overall_risk }}" data-level="item">
                <span>{{ $item->productDebris }}</span>
            </li>
        @endforeach
    </ul>
@endif

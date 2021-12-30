@if(!is_null($data))
    <ul>
        @foreach($data as $location)
            <li data-id="{{ $location['location']->id }}"
                data-location-id="{{ $location['location']->id }}"
                data-location-locked="{{ $location['location']->is_locked}}"
                class="{{ ($location['location']->state == LOCATION_STATE_INACCESSIBLE) ? 'inaccessibleRoom' : '' }}"
                data-level="location">
                @if($location['location']->is_locked == 1)
                    <span>
                        {{ $location['location']->location_reference. ' - ' .$location['location']->description }}
                        &nbsp;
                        <strong class="spanWarningSurveying" style="padding: 2px"><em>Room/location is view only while surveying is in progress</em></strong>
                    </span>
                @else
                    <span>{{ $location['location']->title }}</span>
                    @if(isset($location['items']))
                        @include('surveys.area_item',['data' => $location['items']])
                    @endif
                @endif
            </li>
        @endforeach
    </ul>
@endif
@foreach($clients as $client)
    <ul>
        <li data-id="child{{$prefix}}-clients-{{$client->id}}" data-level="{{$level + 1}}" data-parent="{{$data->id}}">
            <span>{{$client->name}}</span>
            @if(isset($client->zones))
                <ul>
                    @php
                    //dd($array_check_zone);
                    @endphp
                    @foreach($client->zones as $zone)
                        <li data-zone="list-zone" data-id="child{{$prefix}}-groups-{{$zone->id}}" data-checked="{{in_array($zone->id, $array_check_zone) ? 1 : 0}}" data-level="{{$level + 2}}" data-parent="clients-{{$prefix}}">
                            <span>{{$zone->zone_name}}</span>
                            @if(isset($zone->allChildrens))
                            <ul>
                                @foreach($zone->allChildrens as $child_zone)
                                    <li data-zone="list-zone" data-id="child{{$prefix}}-groups-{{$child_zone->id}}" data-checked="{{in_array($child_zone->id, $array_check_zone) ? 1 : 0}}" data-level="{{$level + 3}}" data-parent="clients-{{$prefix}}">
                                        <span>{{$child_zone->zone_name}}
                                            <i class="fa fa-lg fa-edit edit-group" style="color: red"><a href="javascript:void(0)" class="get-propery-role" data-group="{{$child_zone->id}}"
                                                                                                         data-url="{{route('ajax_property_role')}}" ></a></i>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    </ul>
@endforeach

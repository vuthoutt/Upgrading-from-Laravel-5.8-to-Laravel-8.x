<!-- New design for Client and Group -->
<div class="parent-client-listing">
    <div class="load-client-datatable">
        @include('shineCompliance.tables.client_listing_privilege', [
                            'title' => 'Clients',
                            'tableId' =>'client-listing-privilege-'.$type,
                            'collapsed' => false,
                            'plus_link' => false,
                            'data' => []
                            ])
    </div>
    <!-- draw client -> group Tree here (view/edit) -->
    <div class="load-client-group">
        <input id="bstree-data-client-listing-{{$type}}" class="bstree-input-privilege" type="hidden" name="bstree-data-client-{{$type}}" data-ancestors="">
        <div id="mytree-client-listing-{{$type}}" class="bstree bstree-privilege">
            <ul>
                <li data-id="root-client-{{$type}}" data-root="1" data-level="0"><span>Check all below</span>
                    @foreach($client_properties as $client)
                        <ul>
                            <li data-id="child{{$prefix}}-clients-{{$client->id}}" data-level="{{$level + 1}}" data-parent="{{$data->id}}">
                                <span>{{$client->name}}</span>
                                @if(isset($client->zonePrivilege))
                                    <ul>
                                        @php
                                            //dd($array_check_zone);
                                        @endphp
                                        @foreach($client->zonePrivilege as $zone)
                                            <li data-zone="list-zone" data-id="child-{{$prefix}}-groups-{{$zone->id}}" data-value="{{$zone->id}}" data-checked="{{in_array($zone->id, $checked_array) ? 1 : 0}}" data-level="{{$level + 2}}" data-parent="clients-{{$prefix}}" data-prefix = "{{$prefix}}">
                                                @php
                                                    $count_property = array_key_exists($zone->id, $type == JOB_ROLE_VIEW ? $job_role->list_count_property_view : $job_role->list_count_property_edit) ? ($type == JOB_ROLE_VIEW ? count($job_role->list_count_property_view[$zone->id]) : count($job_role->list_count_property_edit[$zone->id])) : 0;
                                                @endphp
                                                <span> {{$zone->zone_name}} <strong class="{{$count_property > 0 ? 'text-danger' : ''}}">({{str_pad($count_property, 2, '0', STR_PAD_LEFT)}}/{{str_pad($zone->countAllProperty(), 2, '0', STR_PAD_LEFT)}})</strong>
                                                <i class="fa fa-lg fa-edit edit-group" style="color: red">
                                                    <a href="javascript:void(0)" class="get-propery-role" data-group="{{$zone->id}}" data-url="{{route('shineCompliance.ajax_property_role.compliance')}}" ></a>
                                                </i>
                                            </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        </ul>
                    @endforeach
                </li>
            </ul>
        </div>
    </div>
    <a class="btn btn_long_size light_grey_gradient mt-5" id="save-client-listing"><strong>Submit</strong></a>
</div>
@push('javascript')
@endpush

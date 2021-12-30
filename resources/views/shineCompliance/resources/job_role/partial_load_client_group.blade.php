@if($client_group)
    @if($type == JOB_ROLE_UPDATE)
        <ul>
        @include('forms.form_checkbox',['title' => 'Add Group Permission:', 'data' => $client_group->id, 'class'=>'add-group-permission', 'name' => 'add-group-permission'.($type == JOB_ROLE_VIEW ? 'view' : 'update'), 'compare' => in_array($client_group->id, explode(",", $job_role->jobRoleEditValue->add_group_permission)) ? $client_group->id : 0 ])
        </ul>
    @endif
    <input id="bstree-data-client-listing-{{$type}}" class="bstree-input-privilege-cl" type="hidden" name="bstree-data-client-{{$type}}" data-ancestors="">
    <div id="mytree-client-listing-{{$type}}" class="bstree bstree-privilege-cl">
        <ul>
            <li data-id="child{{$prefix}}-clients-{{$client_group->id}}" data-level="{{$level + 1}}" data-value="{{$client_group->id}}" data-checked="{{array_key_exists($client_group->id, $checked_client_array ?? [])}}"  data-prefix = "client-listing">
                <span>{{$client_group->name}}</span>
                @if(isset($client_group->zonePrivilege))
                    <ul>
                        @foreach($client_group->zonePrivilege as $zone)
                            <li data-zone="list-zone" data-id="child-{{$prefix}}-groups-parent-{{$zone->id}}" data-value="{{$zone->id}}" data-level="{{$level + 2}}" data-parent="clients-{{$prefix}}" data-prefix = "group-listing-parent"  data-group="{{$zone->id}}">
                                <span> {{$zone->zone_name}}</span>
                                @if(isset($zone->allChildrens) && count($zone->allChildrens) > 0)
                                    <ul>
                                    @foreach($zone->allChildrens as $z)
                                        @php
                                            $count_property = array_key_exists($z->id, $type == JOB_ROLE_VIEW ? $job_role->list_count_property_view : $job_role->list_count_property_edit) ? ($type == JOB_ROLE_VIEW ? $job_role->list_count_property_view[$z->id] : $job_role->list_count_property_edit[$z->id]) : 0;
                                        @endphp
                                        <li data-zone="list-zone" data-id="child-{{$prefix}}-groups-{{$z->id}}" data-value="{{$z->id}}" data-checked="{{in_array($z->id, $checked_client_array[$client_group->id] ?? [])}}" data-level="{{$level + 3}}" data-parent="clients-{{$prefix}}" data-prefix = "group-listing"  data-group="{{$z->id}}">
                                            <span> {{$z->zone_name}} <strong class="{{$count_property > 0 ? 'text-danger' : ''}}">({{str_pad($count_property, 2, '0', STR_PAD_LEFT)}}/{{str_pad($z->countAllProperty(), 2, '0', STR_PAD_LEFT)}})</strong>
                                                <i class="fa fa-lg fa-edit edit-group" style="color: red">
                                                    <a href="javascript:void(0)" class="get-propery-role" data-group="{{$z->id}}" data-url="{{route('shineCompliance.ajax_property_role.compliance')}}" ></a>
                                                </i>
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
    </div>
    <ul class="text-center">
        <button class="btn light_grey_gradient_button fs-8pt mt-5 save-client-listing"><strong>Submit</strong></button>
    </ul>
@endif

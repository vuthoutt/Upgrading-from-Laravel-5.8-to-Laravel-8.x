
@push('css')
<link href="{{ asset('css/shineCompliance/bstreeCompliance.css') }}" rel="stylesheet">
@endpush
@include('shineCompliance.forms.form_checkbox',['title' => 'Check all:', 'data' => 1,'compare' => 0, 'class'=>'check-all', 'name' => 'check-all' ])
<div style="margin-left: 314px">
<!-- Downloading Systems -->
<input id="bstree-data-1" type="hidden" class="bstree-input" name="bstree-data-1" data-ancestors="">
<div id="system-tree" class="bstree bstree-assessment">
    <ul>
        <li data-id="root-sys" data-level="0"><span>All Systems</span>
            <ul>
                @foreach($systems as $system)
                    <li data-id="{{ $system->id }}" data-sys-id="{{ $system->id }}" data-level="system"
                        data-type="system" data-element-id="{{ $system->id }}"
                        data-locked="{{ $system->is_locked }}"
                    >
                        <span>
                            <span class="font-weight-bold" >{{ $system->title ?? '' }}</span>
                            @if($system->is_locked == 1)
                                <strong class="spanWarningSurveying" style="padding: 2px"><em>System is view only while assessment is in progress</em></strong>
                            @endif
                        </span>
                        @if(isset($system->registerEquipments) && count($system->registerEquipments) && $system->is_locked != 1)
                            <ul>
                                @foreach($system->registerEquipments as $equipment)
                                    <li data-id="{{ $equipment->id }}"
                                        data-equipment-id="{{ $equipment->id }}"
                                        data-level="equipment"
                                        data-type="system"
                                        data-locked="{{ $equipment->is_locked }}"
                                        data-element-id="{{ $equipment->id }}">
                                        <span>
                                            {{ $equipment->title_presentation }}
                                            @if($equipment->is_locked == 1)
                                                <strong class="spanWarningSurveying" style="padding: 2px"><em>Equipment is view only while assessment is in progress</em></strong>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</div>
</div>
<div style="margin-left: 314px">
<!-- Downloading Area -> Room -> Equipment -> Hazard -->
<!-- Downloading Area -> Room -> Hazard  -->
<input id="bstree-data-2" type="hidden" class="bstree-input" name="bstree-data-2" data-ancestors="">
@php
    $list_hazard_ids = [];
@endphp
<div id="assessment-tree" class="bstree bstree-assessment">
    <ul>
        <li data-id="root-register" data-level="0"><span>Everything</span>
            <ul>
                @foreach($areas as $area)
                    <li data-id="register-{{ $area->id }}" data-area-id="{{ $area->id }}" data-level="area" data-element-id="{{ $area->id }}"><span class="font-weight-bold" >{{ $area->title_presentation ?? '' }}</span>
                        @if(isset($area->locations) && count($area->locations))
                            <ul>
                                @foreach($area->locations as $location)
                                    <!-- location includes Equipment -> Hazard and normal Hazard -->
                                    <li data-id="register-{{ $location->id }}"
                                        data-location-id="{{ $location->id }}"
                                        data-location-locked="{{ $location->is_locked}}"
                                        data-level="location"
                                        data-type="area_room"
                                        data-element-id="{{ $location->id }}">
                                    <span>{{ $location->title_presentation }}</span>
                                    <!-- Downloading Area -> Room -> Equipment -> Hazard -->
                                    @if(isset($location->registerEquipments) && count($location->registerEquipments))
                                    <ul>
                                        @foreach($location->registerEquipments as $equipment)
                                            <li data-id="register-{{ $equipment->id }}"
                                                data-equipment-id="{{ $equipment->id }}"
                                                data-locked="{{ $equipment->is_locked }}"
                                                data-level="equipment"
                                                data-type="area_room"
                                                data-element-id="{{ $equipment->id }}">
                                                <span>
                                                    {{ $equipment->title_presentation }}
                                                    @if($equipment->is_locked == 1)
                                                        <strong class="spanWarningSurveying" style="padding: 2px"><em>Equipment is view only while assessment is in progress</em></strong>
                                                    @endif
                                                </span>
                                                @if(isset($equipment->nonconformities)  && $equipment->is_locked != 1)
                                                    <ul>
                                                        @foreach($equipment->nonconformities as $nonconformity)
                                                            @if(isset($nonconformity->hazard))
                                                                @php
                                                                    $list_hazard_ids[] = $nonconformity->hazard->id ?? 0;
                                                                @endphp
{{--                                                                @foreach($nonconformity->hazard as $hazard)--}}
                                                                    <li data-id="register-{{ $nonconformity->hazard->id }}"
                                                                        data-hazard-id="{{ $nonconformity->hazard->id }}"
                                                                        data-level="hazard"
                                                                        data-locked="{{ $nonconformity->hazard->is_locked }}"
                                                                        data-type="area_room"
                                                                        data-element-id="{{ $nonconformity->hazard->id }}">
                                                                        <span>
                                                                            {{ $nonconformity->hazard->title_presentation }}
                                                                            @if($nonconformity->hazard->is_locked == 1)
                                                                                <strong class="spanWarningSurveying" style="padding: 2px"><em>Hazard is view only while assessment is in progress</em></strong>
                                                                            @endif
                                                                        </span>
                                                                    </li>
{{--                                                                @endforeach--}}
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                    <!-- Downloading Area -> Room -> Hazard  -->
                                    @if(isset($location->normalHazard) && count($location->normalHazard))
                                        <ul>
                                            @foreach($location->normalHazard as $hazard)
                                                @if(!in_array($hazard->id, $list_hazard_ids))
                                                    <li data-id="register-{{ $hazard->id }}"
                                                        data-hazard-id="{{ $hazard->id }}"
                                                        data-level="hazard"
                                                        data-locked="{{ $hazard->is_locked }}"
                                                        data-type="area_room"
                                                        data-element-id="{{ $hazard->id }}">
                                                        <span>
                                                            {{ $hazard->title_presentation }}
                                                            @if($hazard->is_locked == 1)
                                                                <strong class="spanWarningSurveying" style="padding: 2px"><em>Hazard is view only while assessment is in progress</em></strong>
                                                            @endif
                                                        </span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <!-- Equipment N/A location but have area  -->
                        @if(isset($area->naLocationRegisterEquipments) && count($area->naLocationRegisterEquipments))
{{--                                <ul>--}}
                                @foreach($area->naLocationRegisterEquipments as $equipment)
                                    <li data-id="register-{{ $equipment->id }}"
                                        data-equipment-id="{{ $equipment->id }}"
                                        data-level="equipment"
                                        data-type="area_room"
                                        data-locked="{{ $equipment->is_locked }}"
                                        data-element-id="{{ $equipment->id }}">
                                        <span class="font-weight-bold">
                                            {{ $equipment->title_presentation }}
                                            @if($equipment->is_locked == 1)
                                                <strong class="spanWarningSurveying" style="padding: 2px"><em>Equipment is view only while assessment is in progress</em></strong>
                                            @endif
                                        </span>
                                        @if(isset($equipment->nonconformities) && $equipment->is_locked != 1)
                                            <ul>
                                                @foreach($equipment->nonconformities as $nonconformity)
                                                    @if(isset($nonconformity->hazard))
                                                        @php
                                                            $list_hazard_ids[] = $nonconformity->hazard->id ?? 0;
                                                        @endphp
                                                        {{--                                                                @foreach($nonconformity->hazard as $hazard)--}}
                                                        <li data-id="register-{{ $nonconformity->hazard->id }}"
                                                            data-hazard-id="{{ $nonconformity->hazard->id }}"
                                                            data-level="hazard"
                                                            data-locked="{{ $nonconformity->hazard->is_locked }}"
                                                            data-type="area_room"
                                                            data-element-id="{{ $nonconformity->hazard->id }}">
                                                            <span>
                                                                {{ $nonconformity->hazard->title_presentation }}
                                                                @if($nonconformity->hazard->is_locked == 1)
                                                                    <strong class="spanWarningSurveying" style="padding: 2px"><em>Hazard is view only while assessment is in progress</em></strong>
                                                                @endif
                                                            </span>
                                                        </li>
                                                        {{--                                                                @endforeach--}}
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
{{--                                </ul>--}}
                        @endif
                    <!-- Hazard N/A location but have area  -->
                        @if(isset($area->naLocationNormalHazard) && count($area->naLocationNormalHazard))
{{--                                <ul>--}}
                                @foreach($area->naLocationNormalHazard as $hazard)
                                    @php
                                        $list_hazard_ids[] = $hazard->id ?? 0;
                                    @endphp
                                    <li data-id="register-{{ $hazard->id }}"
                                        data-hazard-id="{{ $hazard->id }}"
                                        data-level="hazard"
                                        data-locked="{{ $hazard->is_locked }}"
                                        data-type="area_room"
                                        data-element-id="{{ $hazard->id }}">
                                        <span class="font-weight-bold">
                                            {{ $hazard->title_presentation }}
                                            @if($hazard->is_locked == 1)
                                                <strong class="spanWarningSurveying" style="padding: 2px"><em>Hazard is view only while assessment is in progress</em></strong>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
{{--                                </ul>--}}
                        @endif
                    </li>
                @endforeach

                <!-- N/A Equipments + temp Hazard -->
                @foreach($na_equipments as $equipment)
                    <li data-id="register-{{ $equipment->id }}"
                        data-equipment-id="{{ $equipment->id }}"
                        data-level="equipment"
                        data-type="area_room"
                        data-locked="{{ $equipment->is_locked }}"
                        data-element-id="{{ $equipment->id }}">
                        <span class="font-weight-bold">
                            {{ $equipment->title_presentation }}
                            @if($equipment->is_locked == 1)
                                <strong class="spanWarningSurveying" style="padding: 2px"><em>Equipment is view only while assessment is in progress</em></strong>
                            @endif
                        </span>
                        @if(isset($equipment->nonconformities)  && $equipment->is_locked != 1)
                            <ul>
                                @foreach($equipment->nonconformities as $nonconformity)
                                    @if(isset($nonconformity->hazard))
                                        @php
                                            $list_hazard_ids[] = $nonconformity->hazard->id ?? 0;
                                        @endphp
                                        {{--                                                                @foreach($nonconformity->hazard as $hazard)--}}
                                        <li data-id="register-{{ $nonconformity->hazard->id }}"
                                            data-hazard-id="{{ $nonconformity->hazard->id }}"
                                            data-level="hazard"
                                            data-locked="{{ $nonconformity->hazard->is_locked }}"
                                            data-type="area_room"
                                            data-element-id="{{ $nonconformity->hazard->id }}">
                                            <span>
                                                {{ $nonconformity->hazard->title_presentation }}
                                                @if($nonconformity->hazard->is_locked == 1)
                                                    <strong class="spanWarningSurveying" style="padding: 2px"><em>Hazard is view only while assessment is in progress</em></strong>
                                                @endif
                                            </span>
                                        </li>
                                        {{--                                                                @endforeach--}}
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                <!-- N/A Hazards -->
                @if(isset($na_hazard) && count($na_hazard))
                    @foreach($na_hazard as $hazard)
                        @if(!in_array($hazard->id, $list_hazard_ids))
                            <li data-id="register-{{ $hazard->id }}"
                                data-hazard-id="{{ $hazard->id }}"
                                data-level="hazard"
                                data-type="area_room"
                                data-locked="{{ $hazard->is_locked }}"
                                data-element-id="{{ $hazard->id }}">
                                <span class="font-weight-bold">
                                    {{ $hazard->title_presentation }}
                                    @if($hazard->is_locked == 1)
                                        <strong class="spanWarningSurveying" style="padding: 2px"><em>Hazard is view only while assessment is in progress</em></strong>
                                    @endif
                                </span>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </li>
    </ul>
</div>
</div>
<!-- Fire Exist/Assembly Point/Vehicle Parking -->
<div style="margin-left: 314px">
<input id="bstree-data-3" type="hidden" class="bstree-input" name="bstree-data-3" data-ancestors="">
<div id="exist-tree" class="bstree bstree-assessment">
    <ul>
        <li data-id="root-exist" data-level="0"><span>All Fire Exits</span>
            <ul>
                @foreach($exists as $exist)
                    <li data-id="exist-{{ $exist->id }}" data-exist-id="{{ $exist->id }}" data-level="exist"
                        data-type="exist" data-element-id="{{ $exist->id }}" data-locked="{{ $exist->is_locked }}">
                        <span class="font-weight-bold" >
                            {{ $exist->name ?? '' }}
                            @if($exist->is_locked == 1)
                                <strong class="spanWarningSurveying" style="padding: 2px"><em>Fire Exit is view only while assessment is in progress</em></strong>
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</div>
</div>
<div style="margin-left: 314px">
<input id="bstree-data-4" type="hidden" class="bstree-input" name="bstree-data-4" data-ancestors="">
<div id="assembly-point-tree" class="bstree bstree-assessment">
    <ul>
        <li data-id="root-assembly-point" data-level="0"><span>All Assembly Points</span>
            <ul>
                @foreach($assembly_points as $assembly_point)
                    <li data-id="point-{{ $assembly_point->id }}" data-assembly-point-id="{{ $assembly_point->id }}" data-level="assembly_point"
                        data-type="assembly_point" data-element-id="{{ $assembly_point->id }}" data-locked="{{ $assembly_point->is_locked }}">
                        <span class="font-weight-bold" >
                            {{ $assembly_point->name ?? '' }}
                            @if($assembly_point->is_locked == 1)
                                <strong class="spanWarningSurveying" style="padding: 2px"><em>Assembly Point is view only while assessment is in progress</em></strong>
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</div>
</div>
<div style="margin-left: 314px">
<input id="bstree-data-5" type="hidden" class="bstree-input" name="bstree-data-5" data-ancestors="">
<div id="vehicle-parking-tree" class="bstree bstree-assessment">
    <ul>
        <li data-id="root-vehicle-parking" data-level="0"><span>All Vehicle Parking</span>
            <ul>
                @foreach($vehicle_parking as $vp)
                    <li data-id="vp-{{ $vp->id }}" data-vehicle-parking-id="{{ $vp->id }}" data-level="vehicle_parking"
                        data-type="vehicle_parking" data-element-id="{{ $vp->id }}" data-locked="{{ $vp->is_locked }}">
                        <span class="font-weight-bold" >
                            {{ $vp->name ?? '' }}
                            @if($vp->is_locked == 1)
                                <strong class="spanWarningSurveying" style="padding: 2px"><em>Vehicle Parking is view only while assessment is in progress</em></strong>
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</div>
</div>
@push('javascript')
<script src="{{ asset('js/shineCompliance/jquery.bstree.compliance.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $hiddenInput = $('.bstree-input');
        $('.bstree-assessment').bstreeCompliance({
            dataSource: $hiddenInput,
            isExpanded:true,
            initValues: $hiddenInput.data('ancestors'),
        });

        $('li[data-equipment-id]').each(function(k,v){
            let id = $(v).data('equipment-id');
            let this_class = $(v).data('type') == 'area_room' ? 'register' : '';
            $(v).find("input[type='checkbox']:first").addClass('equipment-checkbox').addClass(this_class);
        });

        $('body').on('change', '.equipment-checkbox', function(e){
            console.log(e.hasOwnProperty('originalEvent'), 333)
            if(e.hasOwnProperty('originalEvent')){
                console.log(444);
                //user clicks
                //compare duplicated equipments, if they have the same checked prop then return, other will trigger
                var this_state = $(this).prop('checked');
                var this_id = $(this).closest('li').data('equipment-id');
                if($(this).hasClass('register')){
                    var that = $('#bstreeCompliance-checkbox-' + this_id + '-equipment');
                } else {
                    var that = $('#bstreeCompliance-checkbox-register-' + this_id + '-equipment');
                }
                var other_state = $(that).prop('checked');
                if(other_state != this_state){
                    $(that).trigger('click');
                }
            } else {
                //trigger clicks
                console.log(555);
            }
        });

        $('body').on('change', '.check-all', function () {
            var is_checked = $(this).prop('checked');
            var list_btn = ['#bstreeCompliance-checkbox-root-sys-0','#bstreeCompliance-checkbox-root-register-0',
                '#bstreeCompliance-checkbox-root-exist-0','#bstreeCompliance-checkbox-root-assembly-point-0',
                '#bstreeCompliance-checkbox-root-vehicle-parking-0'];
            $.each(list_btn, function(k,v){
                $(v).prop('checked', !is_checked).trigger('click');
            });
        });

        removeInputLocked();
    });

    function removeInputLocked(){
        var li_list = $('body').find("[data-locked=1]");
        console.log(li_list, 6666);
        $.each(li_list, function(k,v){
            var id = $(v).data('id');
            // console.log($(v).find("input[id='bstreeCompliance-checkbox-" + id + "']:first"));
            // $(v).find("input[id='bstreeCompliance-checkbox-" + id + "']:first").remove();
            $(v).find("input[type='checkbox']:first").remove();
        })
    }
</script>
@endpush

<div class="add" style="position: fixed !important;height: 100%;padding-bottom: 50px">
    <div class="filter" style="height: 100%">
        <div class="form-select-button">
            <button class="btn-remove" id="close-form" ><i class="fa fa-remove fa-2x"></i></button>
            <h2>Filters</h2>
            @if(isset($asset_class) && count($asset_class) > 0)
            <p> <strong>Asset Class</strong></p>
                <ul class="select-button">
                @foreach($asset_class as $asset)
                    <li>
                        <label class="switch">
                            <input type="checkbox" class="filter_options" name="asset_class[]" value="{{$asset->id}}" {{in_array($asset->id, explode(",", request()->input('asset_class'))) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">{{$asset->description ?? ''}}</span>
                    </li>
                @endforeach
                </ul>
            @endif
        </div>
        <div class="form-select-button">
            @if(isset($property_status) && count($property_status) > 0)
                <p><strong>Property Status</strong></p>
                <ul class="select-button">
                    @foreach($property_status as $status)
                        <li>
                            <label class="switch">
                                <input type="checkbox" class="filter_options" name="property_status[]" value="{{$status->id}}" {{in_array($status->id, explode(",", request()->input('property_status'))) ? 'checked' : ''}}>
                                <span class="slider round"></span>
                            </label>
                            <span class="text-select">{{$status->description ?? ''}}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="form-select-button">
            @if(isset($accessibility) && count($accessibility) > 0)
                <p><strong>Accessibility</strong></p>
                <ul class="select-button">
                    @foreach($accessibility as $acc)
                        <li>
                            <label class="switch">
                                <input type="checkbox" class="filter_options" name="accessibility[]" value="{{$acc}}" {{request()->has('accessibility') && in_array($acc, explode(",", request()->input('accessibility'))) ? 'checked' : ''}}>
                                <span class="slider round"></span>
                            </label>
                            <span class="text-select">{{in_array($acc, [AREA_ACCESSIBLE_STATE, LOCATION_STATE_ACCESSIBLE]) ? 'Accessible' : 'Inaccessible'}}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="form-select-button">
            <p><strong>Status</strong></p>
            <ul class="select-button">
                <li>
                    <label class="switch">
                        <input type="checkbox" class="filter_options" name="status[]" value="{{PROPERTY_DECOMMISSION}}" {{request()->input('status') == PROPERTY_DECOMMISSION ? 'checked' : ''}}>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Decommissioned</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" class="filter_options" name="status[]" value="{{PROPERTY_UNDECOMMISSION}}" {{request()->has('status') && request()->input('status') == PROPERTY_UNDECOMMISSION ? 'checked' : ''}}>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Live</span>
                </li>
            </ul>
        </div>
        <div class="form-select-button">
            @if(isset($type) && $type == FILTER_PROPERTY)
                <p><strong>Identified Risks</strong></p>
                <ul class="select-button">
                    <li>
                        <label class="switch">
                            <input type="checkbox" class="filter_options" name="identified_risks[]" value="{{FILTER_INACCESSIBLE_ROOM_LOCATIONS}}" {{in_array(FILTER_INACCESSIBLE_ROOM_LOCATIONS, explode(",", request()->input('identified_risks'))) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Inaccessible Room/locations</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" class="filter_options"  name="identified_risks[]" value="{{FILTER_INACCESSIBLE_VOIDS}}" {{in_array(FILTER_INACCESSIBLE_VOIDS, explode(",", request()->input('identified_risks'))) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Inaccessible Voids</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" class="filter_options" name="identified_risks[]" value="{{FILTER_ASBESTOS_CONTAINING_MATERIALS}}" {{in_array(FILTER_ASBESTOS_CONTAINING_MATERIALS, explode(",", request()->input('identified_risks'))) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Asbestos Containing Materials</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" class="filter_options" name="identified_risks[]" value="{{FILTER_HAZARDS}}" {{in_array(FILTER_HAZARDS, explode(",", request()->input('identified_risks'))) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Hazards</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" class="filter_options" name="identified_risks[]" value="{{FILTER_NONCONFORMITY}}" {{in_array(FILTER_NONCONFORMITY, explode(",", request()->input('identified_risks'))) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Nonconformity</span>
                    </li>
                </ul>
            @endif
        </div>
        <div class="form-select-button">
            @if(isset($system_types) && count($system_types) > 0)
            <p><strong>Systems</strong></p>
                <div class="mt-3" id="multiple-select-system">
                    <select class="js-example-basic-multiple js-states form-control filter_options" id="multiple-select-system-select" name="system_type[]" multiple="multiple">
                        @foreach($system_types as $type)
                            <option value="{{ $type->id ?? 0 }}" aria-selected="true">{{ $type->description ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
        <div class="form-select-button">
            @if(isset($equipment_types) && count($equipment_types) > 0)
            <p><strong>Equipment</strong></p>
                <div class="mt-3" id="multiple-select-equipment">
                    <select class="js-example-basic-multiple js-states form-control filter_options" id="multiple-select-equipment-select" name="equipment_types[]" multiple="multiple">
                        @foreach($equipment_types as $type)
                            <option value="{{ $type->id ?? 0 }}" aria-selected="true">{{ $type->description ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
{{--                <ul class="select-button">--}}
{{--                    @foreach($equipment_types as $type)--}}
{{--                        <li>--}}
{{--                            <label class="switch">--}}
{{--                                <input type="checkbox" name="equipment_types[{{$type->id}}]" value="{{$type->id}}">--}}
{{--                                <span class="slider round"></span>--}}
{{--                            </label>--}}
{{--                            <span class="text-select">{{$type->description ?? ''}}</span>--}}
{{--                        </li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}
            @endif
        </div>
        <div class="form-select-button" style="height: 100px"></div>
    </div>
</div>
@push('javascript')

    <script type="text/javascript">
        $(document).ready(function() {

            $('.js-example-basic-multiple').select2({
                minimumResultsForSearch: -1,
                placeholder: "Please select an option"
            });

            $("#multiple-select-equipment-select").val({!!   json_encode(explode(",", urldecode(request()->input('equipment_types')))) !!}).trigger('change');
            $("#multiple-select-system-select").val({!!   json_encode(explode(",", urldecode(request()->input('system_type')))) !!}).trigger('change');

            // $("#multiple-select-equipment-select").change(function(){
            //     // $('input[type="checkbox"][name="equipment_types[]"]').trigger('change');
            // });
        });
    </script>
@endpush

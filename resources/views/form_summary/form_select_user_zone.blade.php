<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >Which Site would you like to check?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="select-property-group" id="{{ $id ?? '' }}">
                <option value="all">All Sites</option>
                @if(isset($all_zones) and !empty($all_zones))
                    @foreach($all_zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->zone_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>
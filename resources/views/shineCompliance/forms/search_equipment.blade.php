<div class="row register-form parent-element" id="form-{{ isset($id) ? $id : ''}}">
    <div class="col-md-3 fs-8pt">
            <strong>{{ $title ?? '' }}</strong>
    </div>

    <div class="col-md-7 mb-3">
        <input class="equipment-search" id="equipment-search-{{$id}}" value="{{ $data ?? ''}}" style="width: 457px !important" >
        <div class="no-equipment">
            No Equipment Available at the Property
        </div>
        <input type ="hidden" id="equipment-data-holder" name="{{ $name }}" value="{{ $value ?? ''}}"/>
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){
    $(".no-equipment").hide();
    var property_id = 0;
    var options = {
        url: function(phrase) {
            property_id = $("#property-id-search").val();
            return "{{route('shineCompliance.equipment.search_equipment')}}"+"?query_string=" + phrase + "&property_id=" + property_id;
        },
        getValue: "name",
        template: {
            type: "custom",
            method: function(value, item) {
                return  item.name + "<br /><span class='searchsmaller'>(" + item.reference +")</span>";
            }
        },
        list: {
            onClickEvent: function() {
                var value = $("#equipment-search-{{$id}}").getSelectedItemData().id;
                $("#equipment-data-holder").val(value).trigger("change");
            },
            hideAnimation: {
                type: "slide", //normal|slide|fade
                time: 250,
                callback: function() {}
            },
            maxNumberOfElements: 5
        },
        placeholder: "Search for Equipment",
        theme: "blue-light"
    };

$("#equipment-search-{{$id}}").easyAutocomplete(options);
});

</script>
@endpush

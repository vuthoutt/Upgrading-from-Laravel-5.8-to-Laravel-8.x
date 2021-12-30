<div class="row register-form parent-element" id="form-{{ isset($id) ? $id : ''}}">

    <div class="col-md-3 fs-8pt">
            <strong>{{ $title ?? '' }}</strong>
    </div>
    <div class="col-md-7 mb-3">
        <input class="system-search" id="system-search-{{$id}}" name="{{$name ?? ''}}" value="{{$data ?? ''}}" style="width: 457px !important" >
        <div class="no-system">
            No System Available at the Property
        </div>
        <input type ="hidden" id="system-data-holder" name="{{ $name }}" value="{{ $value ?? ''}}"/>
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){
    $(".no-system").hide();
    var property_id = 0;
    var options = {
        url: function(phrase) {
            property_id = $("#property-id-search").val();
            return "{{route('shineCompliance.systems.search_system')}}"+"?query_string=" + phrase + "&property_id=" + property_id;
        },
        getValue: "name",
        template: {
            type: "custom",
            method: function(value, item) {
                return item.name + "<br /><span class='searchsmaller'>(" + item.reference +")</span>";
            }
        },
        list: {
            onClickEvent: function() {
                var value = $("#system-search-{{$id}}").getSelectedItemData().id;
                $("#system-data-holder").val(value).trigger("change");
            },
            hideAnimation: {
                type: "slide", //normal|slide|fade
                time: 250,
                callback: function() {}
            },
            maxNumberOfElements: 5,
            match: {
                enabled: true
            }
        },
        placeholder: "Search for System",
        theme: "blue-light"
    };
    $("#system-search-{{$id}}").easyAutocomplete(options);
});

</script>
@endpush

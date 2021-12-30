<div class="row register-form">

    <div class="col-md-3 fs-8pt">
            <strong>{{ $title ?? '' }}</strong>
    </div>
    <div class="col-md-7 mb-3">
            <input id="system-search-{{$id}}" name="{{$name ?? ''}}" value="{{$data ?? ''}}" style="width: 457px !important" >
            <input type ="hidden" id="system-data-holder" name="{{ $name }}" value="{{ $value ?? ''}}"/>
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){
    var options = {
        url: function(phrase) {
            return "{{route('shineCompliance.systems.search_system')}}"+"?query_string=" + phrase + "&assess_id=" + {{ $assess_id ?? 0 }} + "&property_id=" + {{ $property_id ?? 0 }};
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
        placeholder: "Search for System Parent",
        theme: "blue-light"
    };

$("#system-search-{{$id}}").easyAutocomplete(options);
});

</script>
@endpush

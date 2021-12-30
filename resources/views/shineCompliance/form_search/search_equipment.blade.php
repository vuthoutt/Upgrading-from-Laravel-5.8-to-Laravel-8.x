<div class="row register-form {{ $class_other ?? '' }}" id="{{$name ?? ''}}-form" data-templates="{{ $templates ?? '' }}">
    <div class="col-md-3 fs-8pt">
            <strong>{{ $title ?? '' }}</strong>
    </div>

    <div class="col-md-7 mb-3">
        <input id="equipment-search-{{$id}}" value="{{ $data ?? ''}}" style="width: 457px !important" >
        <input type ="hidden" id="equipment-data-holder-{{ $id }}" name="{{ $name }}" value="{{ $value ?? ''}}"/>
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){
    var options = {
        url: function(phrase) {
            var url = "{{route('shineCompliance.equipment.search_equipment')}}"+"?query_string=" + phrase + "&assess_id=" + {{ $assess_id ?? 0 }} + "&property_id=" + {{ $property_id ?? 0 }};
            var templates = $('#' + "{{ $name ?? '' }}-form").data('templates') + '';
            if (templates !== '') {
                var templateArr = templates.split(',');
                $.each(templateArr, function (index, val) {
                    url += '&templates[]=' + val;
                })
            }

            return url;
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
                $("#equipment-data-holder-{{ $id }}").val(value).trigger("change");
            },
            hideAnimation: {
                type: "slide", //normal|slide|fade
                time: 250,
                callback: function() {}
            },
            maxNumberOfElements: 5
        },
        placeholder: "Search for Equipment Parent",
        theme: "blue-light"
    };

$("#equipment-search-{{$id}}").easyAutocomplete(options);
});

</script>
@endpush

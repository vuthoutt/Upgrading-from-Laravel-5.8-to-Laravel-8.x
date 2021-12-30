<div class="row register-form">
    <div class="col-md-3 fs-8pt">
            <strong>Item Name:<span style="color: red">*</span></strong>
    </div>
    <div class="col-md-7 mb-3">
            <input id="survey-search-{{$id}}" name="{{$name ?? ''}}" value="{{$data ?? ''}}" style="width: 457px !important" >
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){
    var options = {
        url: function(phrase) {
            return "{{route('item.search_item')}}"+"?query_string=" + phrase;
        },
        getValue: "name",
        template: {
            type: "custom",
            method: function(value, item) {
                return item.name + "\n";
            }
        },
        list: {
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
        placeholder: "Item Name ...",
        theme: "blue-light"
    };

$("#survey-search-{{$id}}").easyAutocomplete(options);
});

</script>
@endpush

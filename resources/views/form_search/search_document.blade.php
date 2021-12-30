<div class="row register-form">
    <div class="col-md-4">
            <strong>Document Title:<span style="color: red">*</span></strong>
    </div>
    <div class="col-md-7">
            <input id="survey-search-{{$id}}" class="input-summary" name="{{$name ?? ''}}" value="{{$data ?? ''}}" style="width: 250px !important">
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){
    var options = {
        url: function(phrase) {
            return "{{route('document.search_survey')}}"+"?query_string=" + phrase;
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
        placeholder: "Document Title ...",
        theme: "blue-light"
    };

$("#survey-search-{{$id}}").easyAutocomplete(options);
});

</script>
@endpush
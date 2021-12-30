<div class="row register-form form-summary mb-4">
    <div class="col-md-12">
            <strong>Which survey would you like to generate a summary for?</strong>
    </div>
    <div class="col-md-12">
            <input id="survey-search" class="mt-4 input-summary">
            <input type ="hidden" id="survey-data-holder" name="survey_id" />
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){
    var options = {
        url: function(phrase) {
            return "{{route('ajax.search_survey')}}"+"?query_string=" + phrase;
        },
        getValue: "reference",
        template: {
            type: "custom",
            method: function(value, item) {
                return item.reference + " - " + item.property_name + " - " + item.property_reference + " " + "|" + item.id + "|" + item.reference + "\n";
            }
        },
        list: {
            onClickEvent: function() {
                var value = $("#survey-search").getSelectedItemData().id;
                $("#survey-data-holder").val(value).trigger("change");
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
        placeholder: "Survey search ...",
        theme: "blue-light"
    };

$("#survey-search").easyAutocomplete(options);
});

</script>
@endpush
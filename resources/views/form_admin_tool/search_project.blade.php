<div class="row register-form form-summary mb-4 {{isset($hide) && $hide ? 'd-none ' : ' '}}" >
    <div class="col-md-12">
            <strong>{{ $title ?? 'Which project?' }}</strong>
    </div>
    <div class="col-md-12">
        <input id="{{$id.'-input'}}" class="mt-4 input-summary form-control">
        <input type ="hidden" name="{{$id}}" id="{{$id}}" />
        <input type ="hidden" id="{{$id.'-search'}}"  />
        <input type ="hidden" id="{{$id.'-reference'}}"  />
        <span class="invalid-feedback" role="alert">
            <strong>The project field is required.</strong>
        </span>
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){

    var options = {
        url: function(phrase) {
            return "{{route('ajax.search_project')}}"+"?query_string=" + phrase;
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
                var value = $("#{{$id.'-input'}}").getSelectedItemData().id;
                var reference = $("#{{$id.'-input'}}").getSelectedItemData().reference;
                $("#{{$id}}").val(value).trigger('change');
                $("#{{$id.'-reference'}}").val(reference);
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
        placeholder: "Project search ...",
        theme: "blue-light"
    };

$("#{{$id.'-input'}}").easyAutocomplete(options);

    function stripHtml(html){
        // Create a new div element
        var temporalDivElement = document.createElement("div");
        // Set the HTML content with the providen
        temporalDivElement.innerHTML = html;
        // Retrieve the text property of the element (cross-browser support)
        return temporalDivElement.textContent || temporalDivElement.innerText || "";
    }
});


</script>
@endpush

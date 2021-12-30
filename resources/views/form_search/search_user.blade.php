<div class="row register-form form-summary mb-4" id="form-user-search">
    <div class="col-md-12">
            <strong>Which user you like to check?</strong>
    </div>
    <div class="col-md-12">
            <input id="user-search" class="mt-4 input-summary">
            <input type ="hidden" id="user-data-holder" name="user_id" />
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){

    var options = {
        url: function(phrase) {
            return "{{route('ajax.search_user')}}"+"?query_string=" + phrase;
        },
        getValue: "full_name",
        template: {
            type: "custom",
            method: function(value, item) {
                return stripHtml(item.username) + "<br /><span class='searchsmaller'>(" + stripHtml(item.shine_reference) + ") Fullname: " + stripHtml(item.full_name) + "</span>|" + item.id + "|" + stripHtml(item.first_name) + " " + stripHtml(item.last_name) + "\n";
            }
        },
        list: {
            onClickEvent: function() {
                var value = $("#user-search").getSelectedItemData().id;
                $("#user-data-holder").val(value).trigger("change");
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
        placeholder: "User search ...",
        theme: "blue-light"
    };

    $("#user-search").easyAutocomplete(options);
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
<div class="row register-form form-summary mb-4" id="form-property-search">
    <div class="col-md-12">
            <strong>Which property would you like to generate a summary for?</strong>
    </div>
    <div class="col-md-12">
            <input id="property-search" class="mt-4 input-summary">
            <input type ="hidden" id="property-id-search" name="property_id" />
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){

    var options = {
        url: function(phrase) {
            return "{{route('ajax.search_property')}}"+"?query_string=" + phrase;
        },
        getValue: "name",
        template: {
            type: "custom",
            method: function(value, item) {
                return stripHtml(item.name) + "<br /><span class='searchsmaller'>(" + stripHtml(item.property_reference) + ") Postcode: " + item.postcode + "</span>\n" ;
            }
        },
        list: {
            onClickEvent: function() {
                var value = $("#property-search").getSelectedItemData().id;
                $("#property-id-search").val(value).trigger("change");
                getPropertyArea(value);
                getPropertyLocation(value);
            },
            hideAnimation: {
                type: "slide", //normal|slide|fade
                time: 250,
                callback: function() {}
            },
            maxNumberOfElements: 30,
            match: {
                enabled: true
            }
        },
        placeholder: "Property search ...",
        theme: "blue-light"
    };

$("#property-search").easyAutocomplete(options);

function getPropertyArea(property_id){
    $("select[name='area']").find('option').remove();
        $.ajax({
            type: "GET",
            url: '{{ route('ajax.property_area') }}',
            data: {property_id: property_id},
            cache: false,
            success: function (html) {
                $.each( html, function( key, value ) {
                    $("select[name='area']").append($('<option>', {
                        value: value.id,
                        text : stripHtml(value.area_reference) + " - " +stripHtml(value.description)
                    }));
                });
            }
        });
    }


function getPropertyLocation(property_id){
    $("#property-location").find('option').remove();
        $.ajax({
            type: "GET",
            url: '{{ route('ajax.property_location') }}',
            data: {property_id: property_id},
            cache: false,
            success: function (html) {
                $.each( html, function( key, value ) {
                    $("#property-location").append($('<option>', {
                        value: value.id,
                        text : stripHtml(value.location_reference) + " - " +stripHtml(value.description)
                    }));
                });
            }
        });
    }


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

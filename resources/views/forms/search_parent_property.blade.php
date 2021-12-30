<div class="row register-form" id="form-property-search">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold" >{{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
        <div class="form-group ">
            <input id="property-search" class="input-summary" value="{{$data->name ?? ''}}">
            <input type ="hidden" id="property-id-search" name="{{$name}}" value="{{$data->id ?? ''}}"/>
            @error($name)
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>

@push('javascript')
<script>
$(document).ready(function(){

    var options = {
        url: function(phrase) {
            return "{{route('ajax.search_property_parent')}}"+"?query_string=" + phrase;
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
                $("#property-id-search").val(value);
            },
            hideAnimation: {
                type: "slide", //normal|slide|fade
                time: 250,
                callback: function() {}
            },
            maxNumberOfElements: 30,
            // match: {
            //     enabled: true
            // },
            onHideListEvent: function(){
                var value = $("#property-search").val();
                var trimmedValue = $.trim(value);
                if (trimmedValue.length == 0) {
                    $("#property-id-search").val("");
                }
            }
        },
        placeholder: "Property search ...",
        theme: "blue-light",
    };

$("#property-search").easyAutocomplete(options);


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

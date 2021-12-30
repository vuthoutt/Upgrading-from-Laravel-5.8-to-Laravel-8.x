<div class="row register-form parent-element" id="form-property-search">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
       <div class="form-group " id="{{ $name }}-parent">
            <input id="property-search" class="form-control input-summary {{isset($required) ? 'form-require' : ''}} @error($name) is-invalid @enderror" value="{{$data->name ?? ''}}" {{isset($is_disable) && $is_disable ? "disabled" : ""}}>
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
                $("input.equipment-search").val('');
                $("input.system-search").val('');
                $("input[name=equipment_id]").val('');
                $("input[name=system_id]").val('');

                // search equipments, systems available in property
                $.ajax
                ({
                    type: "GET",
                    url: "{{route('shineCompliance.ajax.get_equipment_system')}}",
                    data: {property_id: value},
                    cache: false,
                    success: function (result) {
                        if (result.length === 0) {
                            $("input.equipment-search").hide();
                            $("input.system-search").hide();
                            $(".no-equipment").show();
                            $(".no-system").show();
                            $("input[name=equipment_id]").val('');
                            $("input[name=system_id]").val('');
                        } else {
                            // show/hide equipment input
                            if (typeof result.equipments != "undefined" && result.equipments !== 0) {
                                $("input.equipment-search").show();
                                $(".no-equipment").hide();
                            } else {
                                $("input.equipment-search").hide();
                                $(".no-equipment").show();
                                $("input[name=equipment_id]").val('');
                            }

                            // show/hide system input
                            if (typeof result.systems != "undefined" && result.systems !== 0) {
                                $("input.system-search").show();
                                $(".no-system").hide();
                            } else {
                                $("input.system-search").hide();
                                $(".no-system").show();
                                $("input[name=system_id]").val('');
                            }
                        }
                    }
                });
            },
            hideAnimation: {
                type: "slide", //normal|slide|fade
                time: 250,
                callback: function() {}
            },
            maxNumberOfElements: 5,
            match: {
                enabled: true
            },
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

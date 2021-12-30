
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<div class="row register-form property_search" id="form-property-search">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold" >{{ isset($title) ? $title : '' }}:
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
        <div class="form-group ">
            <input id="property-search" class="input-summary form-control @error($name) is-invalid @enderror " value="{{$property_name ?? ''}}">
            <input type ="hidden" id="property-id-search" name="{{$name}}" value="{{$data ?? ''}}"/>
            @error($name)
            <span style="color: #dc3545;font-size: 90%">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

{{--        <label class="spanWarningSuccess warning_text" id="warning_sucess" style="display:none; margin-left: 0px">Can create a job base on this property</label>--}}
        <label class="spanWarningSurveying warning_text" id="warning_repair_responsibility" style="display:none; margin-left: 0px">MANUAL - no WCC responsibility</label>
        <label class="spanWarningSuccess warning_text" id="warning_us" data-text="SOR User code:" style="display:none; margin-left: 0px"></label>
        <label class="spanWarningInsufficient warning_text" id="warning_insufficient"  style="display:none; margin-left: 0px">Insufficient information to select SOR</label>
        <label class="spanWarningSurveying warning_text" id="warning_not_found" style="display:none; margin-left: 0px">MANUAL - SOR not available, please contact asbestos team!</label>
        <label class="spanWarningSurveying warning_text" id="warning_not_found_property" style="display:none; margin-left: 0px">MANUAL - SOR not available, property not in orchard, job cannot be created!</label>
        <label class="spanWarningSurveying warning_text" id="warning_na" style="display:none; margin-left: 0px">MANUAL - SOR User code: #N/A</label>
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
            onChooseEvent: function() {
                var value = $("#property-search").getSelectedItemData().id;
                $("#property-id-search").val(value).trigger("change");
                getPropertyInformation(value);
                getPropertyContact(value);
            },
            hideAnimation: {
                type: "slide", //normal|slide|fade
                time: 250,
                callback: function() {}
            },
            maxNumberOfElements: 30,
            // match: {
            //     enabled: true
            // }
        },
        placeholder: "Property search ...",
        theme: "blue-light"
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

    function getPropertyInformation(id){
        $('#warning_not_found').hide();
        $('#warning_us').hide();
        $('#warning_na').hide();
        // $("#add_wr").hide();
        $.ajax({
            type: "GET",
            url: '{{ route('ajax.property_information') }}',
            data: {property_id: id},
            cache: false,
            async: true,
            success: function (html) {
                if (html) {
                    // 4 Domestic Property and 3900 Non-Domestic MANUALLY and hide Add/Edit btn
                    $('.property_search_info').show();
                    $('#asset_type').html(html.asset_type);
                    $('#access_type').html(html.access_type);
                    $('#prop_name').html(html.prop_name);
                    $('#property_ref').html(html.prop_ref);
                    $('#asset_type_id').val(html.asset_type_id);
                    $('#number_positive').val(html.number_positive);
                    $('#responsibility_id').val(html.responsibility_id);
                    $('#domestic_property_ids').val(html.domestic_property_ids);
                    $('#group_id').val(html.group_id);
                    if(html.asset_type_id == 18 || html.asset_type_id == 19 || html.asset_type_id == 20){
                        $('#number_of_rooms').closest('.register-form').show();
                        $('#number_of_rooms').val(html.size_bedrooms);
                    } else {
                        $('#number_of_rooms').closest('.register-form').hide();
                        $('#number_of_rooms').val('');
                    }
                    // Category D - Not WCC show error 'Error, No repair responsibility, Job cannot be created in Orchard'
                    // if(html.responsibility_id == 1919){
                    //     var pro_ref = $.trim($('#property_ref').text());
                    //     // $('.warning_text').hide();
                    //     // if(pro_ref.length < 5){
                    //     //     $('#warning_not_found_property').show();
                    //     //     return true;
                    //     // }
                    //     // $('#add_wr').hide();
                    //     $('.warning_text').hide();
                    //     $('#warning_repair_responsibility').show();
                    //     console.log(555);
                    // } else {
                    //     // $('#add_wr').show();
                    //     // $('#warning_sucess').show();
                    //     $('#warning_repair_responsibility').hide();
                    //     checkSor();
                    // }

                    checkSor();

                }
            }
        });
    }

    function getPropertyContact(id){
        $.ajax({
            type: "GET",
            url: '{{ route('ajax.property_contact_wr') }}',
            data: {property_id: id},
            cache: false,
            success: function (html) {
                if (html) {
                    $('#property_contact_parent').html(html)

                }
            }
        });
    }
});

</script>
@endpush

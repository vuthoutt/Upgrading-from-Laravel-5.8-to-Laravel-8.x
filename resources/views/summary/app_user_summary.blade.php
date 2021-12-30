@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    @include('form_summary.form_select_organisation',['data' => $clients, 'id' => 'user-organisation'])
    <div class="row register-form form-summary" id="form-select_extension">
        <label class="font-weight-bold col-md-12" >What App extension do you want to check?
        </label>
        <div>
            <div class="form-group col-md-12">
                <select  class="form-control input-summary" name="select_extension" id="extension">
                    <option value="1">Survey</option>
                    <option value="2">Audit</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row register-form form-summary mb-4" id="form-property-search">
        <div class="col-md-12">
            <strong>Select your Property:</strong>
        </div>
        <div class="col-md-12">
            <input id="property-search" class="mt-4 input-property-search                                                                                                                                                                                                                                  ">
            <input type ="hidden" id="property-name-search" name="property_name" />
            <input type ="hidden" id="property-id-search" name="property_id" />
        </div>
    </div>
    <div class="row register-form form-summary" id="form-survey">
        <label class="font-weight-bold col-md-12" >What Survey would you like to check?
        </label>
        <div>
            <div class="form-group col-md-12">
                <select  class="form-control input-summary" name="select_survey" id="property-survey">

                </select>
            </div>
        </div>
    </div>

    <div class="form-summary">
        <button type="submit" id="submit" class="btn light_grey_gradient ml-3">
            Export CSV File
        </button>
    </div>
</div>
@endsection
@push('javascript')
<script>
$(document).ready(function(){
    $('#extension').change(function (){
        if ($(this).val() == 1) {
            $('#property-survey').parents('#form-survey').children('label').text('What Survey would you like to check?');
            if ($('#property-id-search').val() != '') {
                getPropertySurvey($('#property-id-search').val());
            }
        } else if ($(this).val() == 2) {
            $('#property-survey').parents('#form-survey').children('label').text('What Audit would you like to check?');
            if ($('#property-id-search').val() != '') {
                getAudits($('#property-id-search').val());
            }
        }
    });

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
                var name = $("#property-search").getSelectedItemData().name;
                $("#property-id-search").val(value).trigger("change");
                $("#property-name-search").val(name).trigger("change");
                getPropertySurveyAudit(value);
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
        placeholder: "Property search ...",
        theme: "blue-light"
    };
    $("#property-search").easyAutocomplete(options);

    function getPropertySurveyAudit(property_id){
        if ($('#extension').val() == 1) {
            getPropertySurvey(property_id);
        } else if ($('#extension').val() == 2) {
            getAudits(property_id);
        }
    }

    function getPropertySurvey(property_id){
        $("#property-survey").find('option').remove();
        var data = {property_id: property_id};
        if ($('#user-organisation').val() != 'all') {
            data.client_id = $('#user-organisation').val();
        }
        $.ajax({
            type: "GET",
            url: '{{ route('ajax.property_survey') }}',
            data: data,
            cache: false,
            success: function (html) {
                $.each( html, function( key, value ) {
                    $("#property-survey").append($('<option>', {
                        value: value.id,
                        text : stripHtml(value.reference)
                    }));
                });
            }
        });
    }

    function getAudits(property_id) {
        $("#property-survey").find('option').remove();
        var data = {property_id: property_id};
        if ($('#user-organisation').val() != 'all') {
            data.client_id = $('#user-organisation').val();
        }
        $.ajax({
            type: "GET",
            url: '{{ route('ajax.property_audit') }}',
            data: data,
            cache: false,
            success: function (html) {
                $.each( html, function( key, value ) {
                    $("#property-survey").append($('<option>', {
                        value: value.id,
                        text : stripHtml(value.reference)
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

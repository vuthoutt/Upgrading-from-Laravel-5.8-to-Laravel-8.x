<div class="offset-top40">
    @include('forms.form_input_hidden',['name' => 'location_id', 'data' => $location->id ])
    @include('forms.form_input_hidden',['name' => 'area_id', 'data' => $location->area_id ])
    @include('forms.form_input_hidden',['name' => 'survey_id', 'data' => $location->survey_id ])
    @include('forms.form_input_hidden',['name' => 'property_id', 'data' => $location->property_id ])
   @include('form_search.search_item',['title' => 'Item Name:', 'name' => 'name', 'required' => true, 'data' =>'' ,'id' => 'item'])
    @include('forms.form_dropdown',['title' => 'Item Type:', 'data' => $itemTypes, 'name' => 'item-type', 'key'=> 'id', 'value'=>'description', 'required' => true ])
    @include('forms.form_dropdown_not_assessed',['assess_type' => 'item', 'compare_value' => '-1', 'compare_reason' => '-1' ])
    <div class="row register-form acm">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Accessibility:<span style="color: red;">*</label>
        <div class="col-md-5">
            <div class="form-group ">
                <select  class="form-control @error('accessibility') is-invalid @enderror" name="accessibility" id="accessibility">
                    <option value="0"></option>
                    <option value="{{ ITEM_ACCESSIBLE_STATE }}">Accessible</option>
                    <option value="{{ ITEM_INACCESSIBLE_STATE }}">Inaccessible</option>
                </select>
            </div>
        </div>
        @error('accessibility')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="row register-form acm-child inaccessible">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Assessment Type:</label>
        <div class="col-md-5">
            <div class="form-group ">
                <select  class="form-control" name="assessment" id="assessment">
                    <option value="{{ ITEM_FULL_ASSESSMENT }}">Full Assessment Inaccessible Item</option>
                    <option value="{{ ITEM_LIMIT_ASSESSMENT }}">Limited Assessment Inaccessible Item</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row register-form accessible">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Sample/link ID:</label>
        <div class="col-md-5">
            <div class="form-group ">
                <select  class="form-control" name="sample" id="sample">
                    <option></option>
                    <option value="NO_SAMPLE">NO SAMPLE REQUIRED</option>
                    <option value="OTHER">NEW SAMPLE</option>
                    @if(!is_null($surveySamples))
                        @foreach($surveySamples as $surveySample)
                            <option value="{{ $surveySample->sample_id }}">{{ $surveySample->description }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div id="sampleIDOtherDiv">
        <div class="row register-form">
            <div class="col-md-5 offset-md-3 mb-4" id="sample_ref">
                    <p>Sample Reference:</p>
                    <textarea class="text-area-form" name="sample-other" id="sampleIDOther" ></textarea>
            </div>
            <div class="col-md-5 offset-md-3 mb-4">
                    <p>Sample Comment:</p>
                    <div id="os_comment">
                        <select class="form-control" name="sample-other-comments" id="sampleIDOtherComments">
                            @foreach($sampleComments as $sampleComment)
                                <option value="{{ $sampleComment->id }}">{{ $sampleComment->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="normal_comment">
                        <span style="color: red"> Visually Referred to Original Sample </span>
                    </div>
            </div>
        </div>
    </div>

    @include('forms.form_dropdown',['title' => 'Reason:', 'data' => $reasons, 'name' => 'reasons', 'key'=> 'id', 'value'=>'description', 'class_other' =>'inaccessible acm-child' ])
    <div class="row register-form mb-4 acm-child inaccessible">
        <div class="col-md-5 offset-md-3">
            <input type="text" class="form-control" name="reasons-other" id="reasons-other" placeholder="Please add other reason">
        </div>
    </div>
    @include('forms.form_dropdown',['title' => 'Specific Location:', 'data' => $specificLocations, 'name' => 'specificLocations1','id' => 'specificLocations', 'key'=> 'id', 'value'=>'description' ])
    <div class="row" style="margin-top: -14px;">
        <div class="col-md-5 offset-md-3">
            <div class="form-group mt-3">
                <select class="form-control" name="specificLocations2" id="specificLocations2">
                    <option value="-1"></option>
                </select>
            </div>
        </div>
    </div>
    <div class="row" id="specific-form3">
        <div class="col-md-5 offset-md-3">
            <div class="form-group">
                <select class="form-control" name="specificLocations3[]" id="specificLocations3" multiple="multiple" style="max-width: 458.5px;">
                    <option value="-1"></option>
                </select>
            </div>
        </div>
    </div>
    <div class="row" id="specific-other">
        <div class="col-md-5 offset-md-3">
            <div class="form-group">
                <input type="text" class="form-control" name="specificLocations-other" placeholder="Please add other specific locations">
            </div>
        </div>
    </div>

    <div class="row register-form mb-4">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Product/debris Type:</label>
        <div class="col-md-5">
            <select class="form-control" name="productDebris[]" id="productDebris1">
            </select>

            <select class="form-control mt-4" name="productDebris[]" id="productDebris2">
            </select>
            <select class="form-control mt-4" name="productDebris[]" id="productDebris3">
            </select>
            <input type="text" class="form-control mt-4" name="productDebris-other" id="productDebris-other" placeholder="Please add other product debris">
        </div>
    </div>

    @include('forms.form_dropdown',['title' => 'Asbestos Type:', 'data' => $abestosTypes, 'name' => 'abestosTypes[]','id' => 'abestosTypes', 'key'=> 'id', 'value'=>'description', 'class_other' =>'acm' ])
    <div class="row register-form acm">
        <div class="col-md-5 offset-md-3">
            <select class="form-control" name="abestosTypes[]" id="abestosTypes1">
            </select>
        </div>
    </div>

    <div class="row register-form mt-4" id="AsbestosTypeOther">
        <div class="col-md-5 offset-md-3">
            <div class="form-group">
                <select class="form-control" multiple="multiple" name="AsbestosTypeMore[]" id="AsbestosTypeMore">
                    <option value="Chrysotile">Chrysotile</option>
                    <option value="Amosite">Amosite</option>
                    <option value="Crocidolite">Crocidolite</option>
                    <option value="Tremolite">Tremolite</option>
                    <option value="Actinolite">Actinolite</option>
                    <option value="Anthophyllite">Anthophyllite</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row register-form mb-4">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Extent:</label>
        <div class="col-md-2" >
            <input type="text" class="form-control" name="asbestosQuantityValue" id="asbestosQuantityValue">
        </div>
        <div class="col-md-2" style="max-width: 70%">
            <select class="form-control" name="extent" id="extent">
                <option value="0"></option>
                @foreach($extends as $extend)
                    <option value="{{ $extend->id }}">{{ $extend->description }}</option>
                @endforeach
            </select>
        </div>
    </div>
    {{-- @include('forms.form_dropdown',['title' => 'Accessibility / Vulnerability:', 'data' => $asccessVulners, 'name' => 'AccessibilityVulnerability', 'key'=> 'id', 'value'=>'description', 'class_other' =>'acm' ]) --}}
    {{-- @include('forms.form_dropdown',['title' => 'Additional Information:', 'data' => $additionalInfos, 'name' => 'AdditionalInformation[]','id' => 'AdditionalInformation', 'key'=> 'id', 'value'=>'description', 'class_other' =>'acm' ]) --}}
{{--     <div class="row register-form mb-4">
        <div class="col-md-5 offset-md-3">
            <select class="form-control" name="AdditionalInformation[]" id="AdditionalInformation1">
            </select>
            <input type="text" class="form-control" name="AdditionalInformation-Other" id="AdditionalInformation-Other">
        </div>
    </div> --}}
{{--     @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_license_status == ACTIVE) )
        @include('forms.form_dropdown',['title' => 'Licensed/non-licensed Title:', 'data' => $licenseds, 'name' => 'LicensedNonLicensed', 'key'=> 'id', 'value'=>'description', 'class_other' =>'acm accessible' ])
    @endif --}}
    @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_r_and_d_elements == ACTIVE) )
        @include('forms.form_checkbox',['title' => 'R&D Element:', 'data' => '','compare' => 1, 'name' => 'rAndDElement', 'class_other' =>'acm accessible' ])
    @endif
{{--     <div class="row register-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Air Test ID:</label>
        <div class="col-md-5">
            <div class="form-group ">
                <select  class="form-control" name="airtest" id="airtest">
                    <option></option>
                    <option value="0">NO AIR TEST REQUIRED</option>
                    <option value="OTHER">NEW AIR TEST</option>
                </select>
            </div>
        </div>
    </div>
    <div id="airtestIDOtherDiv">
        <div class="row register-form">
            <div class="col-md-5 offset-md-3 mb-4">
                    <p>New Air Test:</p>
                    <input type="text" class="form-control" name="airtest-other" id="airtest-other">
            </div>
            <div class="col-md-5 offset-md-3 mb-2">
                    <p>Air Test Comment:</p>
                    <select class="form-control" name="airtestIDOtherComments" id="airtestIDOtherComments">
                        @foreach($airTestComments as $airTestComments)
                            <option value="{{ $sampleComment->id }}">{{ $sampleComment->description }}</option>
                        @endforeach
                    </select>
            </div>
        </div>
    </div> --}}
    <div class="row register-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Comments:</label>
        <div class="col-md-5" style="height: 150%">
            <textarea class="text-area-form" name="comments" id="comments" style="height: 150%"></textarea>
        </div>
    </div>

</div>

@push('javascript')

    <script type="text/javascript">
    $(document).ready(function(){


        $("#reasons-other").hide();
        $("#reasons").change(function(){
            var text = $("#reasons").find(":selected").text();
            if (text == 'Other') {
                $("#reasons-other").show();
            } else {
                $("#reasons-other").hide();
            }
        });
        // sample selection
        $("#sampleIDOtherDiv").hide();
        $("#sample_ref").hide();
         $("#sample").change(function(){
            if ($("#sample").val() == 'OTHER') {
                $("#sampleIDOtherDiv").show();
                $("#normal_comment").hide();
                $("#os_comment").show();
                $("#sample_ref").show();
            } else if ($("#sample").val() == '' || $("#sample").val() == "NO_SAMPLE"){
                $("#sampleIDOtherDiv").hide();
            } else {
                $("#sampleIDOtherDiv").show();
                $("#os_comment").hide();
                $("#normal_comment").show();
                $("#sample_ref").hide();
            }
        });

        // airtest selection
        $("#airtestIDOtherDiv").hide();
         $("#airtest").change(function(){
            if ($("#airtest").val() == 'OTHER') {
                $("#airtestIDOtherDiv").show();
            } else {
                $("#airtestIDOtherDiv").hide();
            }
        });

        // Specific location
        $("#specific-other").hide();
        $('#specificLocations3').select2({
            placeholder: "Please select an option"
        }).on('select2:select', function(e){
          var id = e.params.data.id;
          var option = $(e.target).children('[value='+id+']');
          option.detach();
          $(e.target).append(option).change();
        });

        $("#specific-form3").hide();
        $("#specificLocations2").hide();

        // on change specific location 1
        $("#specificLocations").change(function(){
            $('#specificLocations3').find('option').remove();
            // show other option if
            var text = $("#specificLocations").find(":selected").text();
            if (text.includes("Other")) {
                $("#specific-other").show();
                $("#specific-form3").hide();
                $("#specificLocations2").hide();
            } else {
                $("#specific-other").hide();
                $('#specificLocations2').find('option').remove();
                var parentId = $("#specificLocations").val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('ajax.dropdowns-item') }}",
                    data: {dropdown_item_id: {{SPECIFIC_LOCATION_ID}}, parent_id: parentId},
                    cache: false,
                    success: function (html) {
                        if (html.data.length > 0) {
                            if (html.have_child == true) {
                                // $('#specificLocations3').find('option').remove();
                                $.each(html.data, function(key, value ) {
                                    $('#specificLocations2').append($('<option>', {
                                        value: value.id,
                                        text : value.description
                                    }));
                                });
                                $("#specificLocations2").show().trigger('change');
                                $("#specific-form3").show();
                                // onchangeSpecific2();
                            } else {
                                $('#specificLocations3').find('option').remove();
                                $.each(html.data, function(key, value ) {
                                    $('#specificLocations3').append($('<option>', {
                                        value: value.id,
                                        text : value.description
                                    }));
                                });
                                $("#specific-form3").show();
                                $("#specificLocations2").hide();
                            }
                        } else {
                            $("#specific-form3").hide();
                            $("#specificLocations2").hide();
                        }
                    }
                });
            }
        });
        //on change specific location 2
        $("#specificLocations2").change(function(){
            getAjaxItemData('specificLocations2','specificLocations3', {{SPECIFIC_LOCATION_ID}})
        });
        // end of specific location


        // Product Debris
        $("#productDebris2").hide();
        $("#productDebris3").hide();
        $("#productDebris-other").hide();


        $("#item-type").change(function(){
            getAjaxItemData('item-type','productDebris1', {{PRODUCT_DEBRIS_TYPE_ID}},'productDebris2', 'productDebris3');
            if ($("#item-type").val() == {{ITEM_NON_ASBESTOS_TYPE_ID}}) {
                $(".acm").hide();
                $(".acm-child").hide();
                $("#accessibility").val('');
            } else {
                $(".acm").show();
            }

        });

        $("#productDebris1").change(function(){
            getAjaxItemData('productDebris1','productDebris2', {{PRODUCT_DEBRIS_TYPE_ID}}, 'productDebris3');
            var text = $("#productDebris1").find(":selected").text();
            if (text == "Other") {
                $("#productDebris-other").show();
            } else {
                $("#productDebris-other").hide();
            }
        });

        $("#productDebris2").change(function(){
           getAjaxItemData('productDebris2','productDebris3', {{PRODUCT_DEBRIS_TYPE_ID}});
        });
        //end of product debris
        // Abestos Type
        $('#AsbestosTypeMore').select2({
            placeholder: "Please select an option"
        }).on('select2:select', function(e){
          var id = e.params.data.id;
          var option = $(e.target).children('[value='+id+']');
          option.detach();
          $(e.target).append(option).change();
        });

        $('#abestosTypes1').hide();
        $('#AsbestosTypeOther').hide();

        $("#abestosTypes1").change(function(){
            var text = $("#abestosTypes1").find(":selected").text();
            if (text == "Other" || text == 'Amphibole (exc. Crocidolite) ' || text == 'Crocidolite' || text == 'Crocidolite or other') {
                $("#AsbestosTypeOther").show();
            } else {
                $("#AsbestosTypeOther").hide();
            }
        });

        $("#abestosTypes").change(function(){
            $('#AsbestosTypeOther').hide();
           getAjaxItemData('abestosTypes','abestosTypes1', {{ASBESTOS_TYPE_ID}});
        });

        // end of abestos type

        // Addistional Information
        $('#AdditionalInformation-Other').hide();
        $('#AdditionalInformation1').hide();
        $("#AdditionalInformation").change(function(){
            var text = $("#AdditionalInformation").find(":selected").text();
            if (text.includes("Other")) {
                $("#AdditionalInformation-Other").show();
            } else {
                $("#AdditionalInformation-Other").hide();
            }
            getAjaxItemData('AdditionalInformation','AdditionalInformation1', {{ADDITIONAL_INFORMATION_ID}});
        });

        function getAjaxItemData(parent_select_id, child_select_id,dropdown_id, child_select_id2 , child_select_id3 ) {
            if (child_select_id2 === undefined) {
                child_select_id2 = 'nan';
            }
            if (child_select_id3 === undefined) {
                child_select_id3 = 'nan';
            }
            $('#' + child_select_id).find('option').remove();
            var parent_id = $('#' + parent_select_id).val();
            $.ajax({
                type: "GET",
                url: "{{ route('ajax.dropdowns-item') }}",
                data: {dropdown_item_id: dropdown_id, parent_id: parent_id},
                cache: false,
                success: function (html) {
                    if (html.data.length > 0) {
                        $.each(html.data, function(key, value ) {
                            $('#' + child_select_id).append($('<option>', {
                                value: value.id,
                                text : value.description
                            }));
                        })
                        $('#' + child_select_id).show().trigger('change');
                    } else {
                        $('#' + child_select_id).hide();
                        $('#' + child_select_id2).hide();
                        $('#' + child_select_id3).hide();
                        $("#productDebris1").show();
                    }
                },
            });
        }

        // accessibility
        $("#reasons-form").hide();

        $('body').on('change','#accessibility',function(){
            $("#assessment").removeAttr('selected');
            if ($("#accessibility").val() == {{ITEM_INACCESSIBLE_STATE}}) {
                $(".inaccessible").show();
                $(".accessible").hide();
            } else {
                $(".inaccessible").hide();
                $(".accessible").show();
            }
            if ( ($("#assessment").val() == {{ ITEM_LIMIT_ASSESSMENT }}) && ($("#accessibility").val() == {{ITEM_INACCESSIBLE_STATE}}) ) {
                $("#material-limited").show();
                $("#materialaltdiv").hide();
            } else {
                $("#material-limited").hide();
                $("#materialaltdiv").show();
            }
        })
        $("#accessibility").trigger('change');

         $("#assessment").change(function(){
            if (($("#assessment").val() == {{ ITEM_LIMIT_ASSESSMENT }}) && ($("#accessibility").val() == {{ITEM_INACCESSIBLE_STATE}})) {
                $("#material-limited").show();
                $("#materialaltdiv").hide();
            } else {
                $("#material-limited").hide();
                $("#materialaltdiv").show();
            }
        });
         $("#assessment").trigger('change');
    });

</script>
@endpush

<div class="offset-top40">
    @include('forms.form_input_hidden',['name' => 'location_id', 'data' => $item->location_id ])
    @include('forms.form_input_hidden',['name' => 'area_id', 'data' => $item->area_id ])
    @include('forms.form_input_hidden',['name' => 'survey_id', 'data' => $item->survey_id ])
    @include('forms.form_input_hidden',['name' => 'property_id', 'data' => $item->property_id ])
    @include('forms.form_input_hidden',['name' => 'item_id', 'data' => $item->id ])
    @include('forms.form_input_hidden',['name' => 'action', 'data' => 'update' ])
    @include('forms.form_text',['title' => 'Item ID:', 'data' => $item->reference ])
    @include('form_search.search_item',['title' => 'Item Name:', 'name' => 'name', 'required' => true, 'data' => $item->name ,'id' => 'item'])
    @include('forms.form_dropdown',['title' => 'Item Type:', 'data' => $itemTypes,
                                    'name' => 'item-type', 'key'=> 'id', 'value'=>'description',
                                    'compare_value' => ($item->state == ITEM_NOACM_STATE) ? ITEM_NON_ASBESTOS_TYPE_ID : 174
                                    , 'required' => true ])
    @include('forms.form_dropdown_not_assessed',['assess_type' => 'item','compare_value' => $item->not_assessed, 'compare_reason' => $item->not_assessed_reason ])
    <div class="row register-form acm">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Accessibility:<span style="color: red;">*</label>
        <div class="col-md-5">
            <div class="form-group ">
                <select  class="form-control  @error('accessibility') is-invalid @enderror" name="accessibility" id="accessibility">
                    <option value="">------ Please select an option -------</option>
                    <option value="{{ITEM_ACCESSIBLE_STATE}}" {{ ($item->state == ITEM_ACCESSIBLE_STATE ? 'selected' : '') }}>Accessible</option>
                    <option value="{{ITEM_INACCESSIBLE_STATE}}" {{ ($item->state == ITEM_INACCESSIBLE_STATE ? 'selected' : '') }}>Inaccessible</option>
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
                    <option value="{{ ITEM_FULL_ASSESSMENT }}" {{ ( optional($item->itemInfo)->assessment == ITEM_FULL_ASSESSMENT ? 'selected' : '') }}>Full Assessment Inaccessible Item</option>
                    <option value="{{ ITEM_LIMIT_ASSESSMENT }}" {{ ( optional($item->itemInfo)->assessment == ITEM_LIMIT_ASSESSMENT ? 'selected' : '') }}>Limited Assessment Inaccessible Item</option>
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
                    <option value="NO_SAMPLE" data-os="">NO SAMPLE REQUIRED</option>
                    <option value="OTHER" data-os="">NEW SAMPLE</option>
                    @if(!is_null($surveySamples))
                        @foreach($surveySamples as $surveySample)
                            <option value="{{ $surveySample->sample_id }}" data-os="{{ $surveySample->original_item_id }}"  {{ ($surveySample->sample_id == optional($selectedSample)->id) ? 'selected' : '' }} >{{ $surveySample->description }}</option>
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
                                <option value="{{ $sampleComment->id }}" {{ ($sampleComment->id == optional($selectedSample)->comment_key) ? 'selected' : '' }}>{{ $sampleComment->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="normal_comment">
                        <span style="color: red"> Visually Referred to Original Sample </span>
                    </div>
            </div>
        </div>
    </div>

    @include('forms.form_dropdown',['title' => 'Reason:', 'data' => $reasons, 'name' => 'reasons', 'key'=> 'id', 'value'=>'description', 'compare_value' => $selectedReason, 'class_other' =>'inaccessible acm-child' ])
    <div class="row register-form mb-4 acm-child inaccessible">
        <div class="col-md-5 offset-md-3">
            <input type="text" class="form-control" name="reasons-other" id="reasons-other" value="{{ $selectedReasonOther }}"  placeholder="Please add other reason">
        </div>
    </div>
    @include('forms.form_dropdown',['title' => 'Specific Location:', 'data' => $specificLocations, 'name' => 'specificLocations1','id' => 'specificLocations', 'key'=> 'id', 'value'=>'description', 'compare_value' => \CommonHelpers::checkArrayKey2($selectedSpecificLocations, 0) ])
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
                <input type="text" class="form-control" id="specific-other-input" name="specificLocations-other" value="{{ $otherSpecificLocation }}" placeholder="Please add other specific locations">
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
            <input type="text" class="form-control mt-4" name="productDebris-other" id="productDebris-other" value="{{ $otherProductDebris }}" placeholder="Please add other product debris">
        </div>
    </div>

    @include('forms.form_dropdown',['title' => 'Asbestos Type:', 'data' => $abestosTypes, 'name' => 'abestosTypes[]','id' => 'abestosTypes', 'key'=> 'id', 'value'=>'description', 'compare_value' => \CommonHelpers::checkArrayKey2($selectedAsbetosType, 0), 'class_other' =>'acm' ])
    <div class="row register-form mb-4 acm">
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
            <input type="text" class="form-control" name="asbestosQuantityValue" id="asbestosQuantityValue" value="{{ optional($item->itemInfo)->extent }}">
        </div>
        <div class="col-md-2" style="max-width: 70%">
            <select class="form-control" name="extent" id="extent">
                <option value="0"></option>
                @foreach($extends as $extend)
                    <option value="{{ $extend->id }}" {{ ($selectedExtent == $extend->id) ? 'selected' : '' }}>{{ $extend->description }}</option>
                @endforeach
            </select>
        </div>
    </div>
{{--     @include('forms.form_dropdown',['title' => 'Accessibility / Vulnerability:', 'data' => $asccessVulners, 'name' => 'AccessibilityVulnerability', 'key'=> 'id', 'value'=>'description', 'compare_value' => $selectedAsccessVulner, 'class_other' =>'acm' ])
    @include('forms.form_dropdown',['title' => 'Additional Information:', 'data' => $additionalInfos, 'name' => 'AdditionalInformation[]','id' => 'AdditionalInformation', 'key'=> 'id', 'value'=>'description', 'compare_value' => \CommonHelpers::checkArrayKey2($selectedAdditionalInfo, 0), 'class_other' =>'acm' ])
    <div class="row register-form mb-4">
        <div class="col-md-5 offset-md-3">
            <select class="form-control" name="AdditionalInformation[]" id="AdditionalInformation1">
            </select>
            <input type="text" class="form-control" name="AdditionalInformation-Other" value="{{ $otherAdditionalInfo }}"  id="AdditionalInformation-Other">
        </div>
    </div>
    @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_license_status == ACTIVE) )
        @include('forms.form_dropdown',['title' => 'Licensed/non-licensed Title:', 'data' => $licenseds, 'name' => 'LicensedNonLicensed', 'key'=> 'id', 'value'=>'description', 'compare_value' => $selectedLicensed, 'class_other' =>'acm accessible'])
    @endif --}}
    @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_r_and_d_elements == ACTIVE) )
        @include('forms.form_checkbox',['title' => 'R&D Element:', 'data' => optional($item->itemInfo)->is_r_and_d_element,'compare' => 1, 'name' => 'rAndDElement', 'class_other' =>'acm accessible' ])
    @endif
{{--     <div class="row register-form acm">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Air Test ID:</label>
        <div class="col-md-5">
            <div class="form-group ">
                <select  class="form-control" name="airtest" id="airtest">
                    <option value=""></option>
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
            <textarea class="text-area-form" name="comments" id="comments" style="height: 150%">{{optional($item->itemInfo)->comment}}</textarea>
        </div>
    </div>
</div>

@push('javascript')

    <script type="text/javascript">
    $(document).ready(function(){


        $('body').on('change','#reasons',function(){
        var text = $("#reasons").val();
            if (text == {{ ITEM_REASON_OTHER }}) {
                $("#reasons-other").show();
            } else {
                $("#reasons-other").hide();
            }
        });
        $("#reasons").trigger('change');

        // sample selection
        $("#sampleIDOtherDiv").hide();
        $("#sample_ref").hide();
         $("#sample").change(function(){
        });
        $('body').on('change','#sample',function(){
            var os_item = $("#sample").find(":selected").data('os');
            if ($("#sample").val() == '' || $("#sample").val() == "NO_SAMPLE") {
                $("#sampleIDOtherDiv").hide();
                $("#sample_ref").hide();
            } else {
                $("#sampleIDOtherDiv").show();
                if ($("#sample").val() == "OTHER") {
                    $("#sample_ref").show();
                } else {
                    $("#sample_ref").hide();
                }
            }

            if (os_item == {{ $item->record_id ?? 0 }} || $("#sample").val() == "OTHER") {
                $("#os_comment").show();
                $("#normal_comment").hide();
            } else {
                $("#os_comment").hide();
                $("#normal_comment").show();
            }

        })
        $("#sample").trigger('change');
        // / end sample selection

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
        //selected option

        $("#specific-form3").hide();
        $("#specificLocations2").hide();

        // on change specific location 1
        $("#specificLocations").change(function(){
            $("#specific-other-input").val('');
            specificLocation();
        });
        specificLocation();

        function specificLocation() {
        $('#specificLocations3').find('option').remove();
            // show other option if
            var text = $("#specificLocations").val();
            if (text == {{ ITEM_SPECIFIC_LOCATION_OTHER }}) {
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
                                var selected_val = false;
                                $.each(html.data, function(key, value ) {
                                    if (value.id == {{ count($selectedSpecificLocations) == 3 ? $selectedSpecificLocations[1] : 0 }}) {
                                        selected_val = true;
                                    }
                                    $('#specificLocations2').append($('<option>', {
                                        value: value.id,
                                        text : value.description
                                    }));
                                    selected_val = false
                                });
                                $("#specificLocations2").show().trigger('change');
                                $("#specific-form3").show();
                            } else {
                                $('#specificLocations3').find('option').remove();
                                $.each(html.data, function(key, value ) {
                                    $('#specificLocations3').append($('<option>', {
                                        value: value.id,
                                        text : value.description
                                    }));
                                });
                                //add selected multi
                                $('#specificLocations3').val(<?php echo json_encode(end($selectedSpecificLocations)); ?>);
                                $('#specificLocations3').trigger('change'); // Notify any JS components that the value changed

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
        }

        //on change specific location 2
        getAjaxItemData('specificLocations2','specificLocations3', {{SPECIFIC_LOCATION_ID}});
        $("#specificLocations2").change(function(){
            getAjaxItemData('specificLocations2','specificLocations3', {{SPECIFIC_LOCATION_ID}});
        });
        // end of specific location


        // Product Debris
        $("#productDebris2").hide();
        $("#productDebris3").hide();
        $("#productDebris-other").hide();

        getAjaxItemData('item-type','productDebris1', {{PRODUCT_DEBRIS_TYPE_ID}},{{ \CommonHelpers::checkArrayKey2($selectedProductDebris, 1) }},'productDebris2', 'productDebris3');
        if ($("#item-type").val() == {{ITEM_NON_ASBESTOS_TYPE_ID}}) {
            $(".acm").hide();
        } else {
            $(".acm").show();
        }
        $("#item-type").change(function(){
            getAjaxItemData('item-type','productDebris1', {{PRODUCT_DEBRIS_TYPE_ID}},{{ \CommonHelpers::checkArrayKey2($selectedProductDebris, 1) }},'productDebris2', 'productDebris3');
            if ($("#item-type").val() == {{ITEM_NON_ASBESTOS_TYPE_ID}}) {
                $(".acm").hide();
                $(".acm-child").hide();
                $("#accessibility").val('');
            } else {
                $(".acm").show();
            }
        });

        getAjaxItemData('productDebris1','productDebris2', {{PRODUCT_DEBRIS_TYPE_ID}},{{ \CommonHelpers::checkArrayKey2($selectedProductDebris, 2) }}, 'productDebris3');
        $("#productDebris1").change(function(){
            getAjaxItemData('productDebris1','productDebris2', {{PRODUCT_DEBRIS_TYPE_ID}},{{ \CommonHelpers::checkArrayKey2($selectedProductDebris, 2) }}, 'productDebris3');
            var text = $("#productDebris1").find(":selected").text();
            if (text == "Other") {
                $("#productDebris-other").show();
            } else {
                $("#productDebris-other").hide();
            }
        });

        getAjaxItemData('productDebris2','productDebris3', {{PRODUCT_DEBRIS_TYPE_ID}},{{ \CommonHelpers::checkArrayKey2($selectedProductDebris, 3) }});
        $("#productDebris2").change(function(){
           getAjaxItemData('productDebris2','productDebris3', {{PRODUCT_DEBRIS_TYPE_ID}},{{ \CommonHelpers::checkArrayKey2($selectedProductDebris, 3) }});

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

        $('#AsbestosTypeMore').val(<?php echo json_encode(end($selectedAsbetosType)); ?>);
        $('#AsbestosTypeMore').trigger('change'); // Notify any JS components that the value changed

        $("#abestosTypes1").change(function(){
            var text = $("#abestosTypes1").find(":selected").text();
            if (text == "Other" || text == 'Amphibole (exc. Crocidolite) ' || text == 'Crocidolite' || text == 'Crocidolite or other') {
                $("#AsbestosTypeOther").show();
            } else {
                $("#AsbestosTypeOther").hide();
            }
        });

        getAjaxItemData('abestosTypes','abestosTypes1', {{ASBESTOS_TYPE_ID}},{{ \CommonHelpers::checkArrayKey2($selectedAsbetosType, 1) }});
        $("#abestosTypes").change(function(){
            $('#AsbestosTypeOther').hide();
            getAjaxItemData('abestosTypes','abestosTypes1', {{ASBESTOS_TYPE_ID}},{{ \CommonHelpers::checkArrayKey2($selectedAsbetosType, 1) }});
        });

        // end of abestos type

        // Addistional Information


        getAjaxItemData('AdditionalInformation','AdditionalInformation1', {{ADDITIONAL_INFORMATION_ID}}, {{ \CommonHelpers::checkArrayKey2($selectedAdditionalInfo, 1) }});

         $('body').on('change','#AdditionalInformation',function(){
            var text = $("#AdditionalInformation").val();
            if (text == {{ ITEM_ADDITIONAL_INFO_OTHER }}) {
                $("#AdditionalInformation-Other").show();
            } else {
                $("#AdditionalInformation-Other").hide();
            }
            getAjaxItemData('AdditionalInformation','AdditionalInformation1', {{ADDITIONAL_INFORMATION_ID}}, {{ \CommonHelpers::checkArrayKey2($selectedAdditionalInfo, 1) }});
        });
         $("#AdditionalInformation").trigger('change');

        function getAjaxItemData(parent_select_id, child_select_id,dropdown_id, compare, child_select_id2, child_select_id3  ) {
            if (compare === undefined) {
                compare = 'nan';
            }
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
                        var selected_val = false;
                        $.each(html.data, function(key, value, selected ) {
                            if (value.id == compare) {
                                selected_val = true;
                            }
                            $('#' + child_select_id).append($('<option>', {
                                value: value.id,
                                text : value.description,
                                selected : selected_val
                            }));
                            selected_val = false;

                        })

                        $('#' + child_select_id).show().trigger('change');
                        //add selected multi
                        $('#specificLocations3').val(<?php echo json_encode(end($selectedSpecificLocations)); ?>);
                        $('#specificLocations3').trigger('change'); // Notify any JS components that the value changed

                    } else {
                        $('#' + child_select_id).hide();
                        $('#' + child_select_id2).hide();
                        $('#' + child_select_id3).hide();
                        $("#productDebris1").show();
                    }
                },
            });
        }


        $('body').on('change','#accessibility',function(){
            $("#assessment").removeAttr('selected');
            if ($("#accessibility").val() == {{ITEM_INACCESSIBLE_STATE}}) {
                $(".inaccessible").show();
                $(".accessible").hide();
                // $("#sampleIDOtherDiv").hide();
            } else {
                $(".inaccessible").hide();
                $(".accessible").show();
                // $("#sampleIDOtherDiv").show();
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

        $('body').on('change','#assessment',function(){

            if ( ($("#assessment").val() == {{ ITEM_LIMIT_ASSESSMENT }}) && ($("#accessibility").val() == {{ITEM_INACCESSIBLE_STATE}}) ) {
                $("#material-limited").show();
                $("#materialaltdiv").hide();
            } else {
                $("#material-limited").hide();
                $("#materialaltdiv").show();
            }
        })
        $("#assessment").trigger('change');
    });
</script>
@endpush

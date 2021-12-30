@extends('admin_tool.index')
@section('toolbox_content')

    <div>
        <h3 class="text-center mt-4">Merge Action</h3>
        <form method="POST" id="form-action"  action="{{ route('toolbox.post_merge') }}">
            @csrf
            <input type="hidden" id="description" name="description">
            <!-- Merge form -->
            <div  id="form-admin-tool">
                @include('form_admin_tool.form_select_merge_type',['id' => 'select-type', 'title' => 'What would you like to merge?'])
                <div class="row">
                    <div class="col-6 parent-cl" style="border-right: 1px solid #777777" id="from_tab" data-text="from">
                        <h5 class="text-center text-danger">FROM</h5>
                        @include('form_admin_tool.search_property',['id' => 'property_old', 'title' => 'What Property would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'project_old', 'name' => 'project', 'class' => 'form-option', 'title' => 'What Project would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'survey_old', 'name' => 'survey','class' => 'form-option', 'title' => 'What Survey would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'area_old', 'name' => 'area','class' => 'form-option', 'title' => 'What Area would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'location_old', 'name' => 'location','class' => 'form-option', 'title' => 'What Location would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'item_old', 'name' => 'item','class' => 'form-option', 'title' => 'What Item would you like to use?'])
                    </div>
                    <div class="col-6 parent-cl" id="to_tab" data-text="to">
                        <h5 class="text-center text-danger">TO</h5>
                        @include('form_admin_tool.search_property',['id' => 'property_new', 'hide' => true ,'class' => 'form-option', 'title' => 'What Property would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'project_new', 'name' => 'project','class' => 'form-option', 'title' => 'What Project would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'survey_new', 'name' => 'survey','class' => 'form-option', 'title' => 'What Survey would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'area_new', 'name' => 'area','class' => 'form-option', 'title' => 'What Area would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'location_new', 'name' => 'location','class' => 'form-option', 'title' => 'What Location would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'item_new', 'name' => 'item','class' => 'form-option', 'title' => 'What Item would you like to use?'])
                    </div>
                </div>
                <div class="row register-form form-summary mt-5 mb-5">
                    <label class="col-12 col-form-label text-md-left font-weight-bold" >Reason</label>
                    <div class="col-6">
                        <textarea class="form-control"  id="reason" name="reason" style="height: 110px !important;"></textarea>
                        <span class="invalid-feedback" role="alert">
                        <strong>The reason field is required.</strong>
                    </span>
                    </div>
                </div>
            </div>
            <!-- End Merge form -->
            {{--        @include('modals.toolbox_reason',['title' => 'Please give a reason for removing Item', 'modal_id' => 'reason-na'])--}}
            @include('modals.toolbox_submit',['title' => 'merge', 'modal_id' => 'toolbox', 'header' => 'Are you sure you want to merge the following?'])


            <div style="margin-left: 75px">
                <button onclick="return false;" type="submit" id="toolbox_submit" class="btn light_grey_gradient">
                    Merge
                </button>
            </div>
        </form>
    </div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            type = '';
            $('#cancel-reason').click(function(){
                var reason = $('#reason').val('');
            });

            $('#reason').change(function(){
                $(this).removeClass('is-invalid');
                $(this).parent().find('.invalid-feedback').hide();
            });
            $('#submit_form').click(function(){
                $('#form-action').submit();
            });
            $('#toolbox_submit').click(function(e){
                // var group_id = $('#client-zone').val();
                // var group_name = $("#client-zone").find(":selected").text();
                var property_old = $("#property_old-search").val();//name property
                var property_old_ref = $("#property_old-reference").val();//ref PLxxx property
                var property_new = $("#property_new-search").val();
                var property_new_ref = $("#property_new-reference").val();
                var survey_old = $("#survey_old option:selected").text();
                var survey_new = $("#survey_new  option:selected").text();
                var project_old = $("#project_old  option:selected").text();
                var project_new = $("#project_new  option:selected").text();
                var area_old = $("#area_old  option:selected").text();
                var area_new = $("#area_new  option:selected").text();
                var location_old = $("#location_old  option:selected").text();
                var location_new = $("#location_new  option:selected").text();
                var item_old = $("#item_old  option:selected").text();
                var item_new = $("#item_new  option:selected").text();
                var audit_content = '';
                var action = $('.nav.list-group.list-group-flush').find('.active').text();
                var action_lowercase = $('.nav.list-group.list-group-flush').find('.active').text().toLowerCase();
                if(type == 'survey'){
                    //Merge: Survey MS123 to MS222 from Property PL2 - Stephen Home to PL3 - Property Stephen2.
                    audit_content = action + ' Survey "' + survey_old + '" to "' + survey_new + '" from Property "'
                        + property_old_ref + ' - ' + property_old + '"';
                } else if(type == 'register_area'){
                    audit_content = action + ' Register Area "' + area_old + '" from Property "' + property_old_ref + ' - ' + property_old + '"';
                } else if(type == 'register_location'){
                    //Merge: Location RL123 - Kitchen to AF999 - 999 from MS999, AF123 - 00, Property PL2 - Stephen Home.
                    audit_content = action + ' Register Location "' + location_old + '" to "' + location_new
                        + '" from Area "' + area_old + '", "' + property_old_ref + ' - ' + property_old + '"';
                }
                var reason = $('#reason').val();
                $('#description').val(audit_content);
                $('#submit-content').html(audit_content);
                $('#submit-reason').html(reason);
                $('#red_text').text($('#red_text').text().replace('action', action_lowercase));
                //validate
                var error = false;
                $.each($('#form-admin-tool').find('input:visible, select:visible, #reason'), function(k,v){
                    if($(v).val() == '' || !$(v).val()){
                        error = true;
                        // input visible and select empty then show error
                        $(v).addClass('is-invalid');
                        $(v).parent().parent().find('.invalid-feedback').show();
                    }
                });

                if(!error){
                    $("#toolbox").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            });

            $('body').on('change', '#select-type', function(){
                type = $(this).val();
                hideError();
                //hide all fields for FROM tab
                $('.form-option').addClass('d-none');
                //hide all new field when change
                $('#to_tab').find('.row.register-form.form-summary').addClass('d-none');
                //remove all select or $('#to_tab').find('option').remove()
                $('#to_tab').find('.row.register-form.form-summary').find('option').remove();
                if(type == 'survey'){
                    showField(['survey_new']);
                } else if(type == 'register_area'){
                    showField(['area_new']);
                } else if(type == 'register_location'){
                    showField(['location_new']);
                }
                // reset new fields later
                $.each(['#property_old', '#survey_old', '#area_old', '#location_old','#item_old'], function(k,v){
                    if(v == '#property_old'){
                        $('#property_old-input').val('');
                    }
                    $(v).find('option').remove();
                })
            });
            //should remove survey_old and location_old
            $('body').on('change', '#property_old, #area_old', function(){
                hideError();
                var property_id = $('#property_old').val();
                var attr_id = $(this).prop('id');
                if(property_id){
                    // if this = input[name="property-input"] then no check
                    // note if have many new fields in the future so will ajax by new parent fields, maybe new a function for property_new, survey_new ...
                    if(type == 'survey'){
                        ajaxByField(['property_old','survey_old'], 'survey_new', property_id, attr_id);
                    }else if(type == 'register_area'){
                        ajaxByField(['property_old','area_old'], 'area_new', property_id, attr_id);
                        disableNewDropdown(this);
                    }else if(type == 'register_location'){
                        ajaxByField(['property_old','area_old','location_old'], 'location_new', property_id, attr_id);
                    }
                }
            });

            $('body').on('change', '#survey_old, #location_old', function(){
                disableNewDropdown(this);
            });

            function disableNewDropdown(that){
                var attr_id = $(that).prop('id');
                // var sub_str = attr_id.substr(attr_id.indexOf('_') + 1); // new or old
                var object_str = attr_id.substr(0, attr_id.indexOf('_')); // area/location/survey
                var old_value = $(that).val(); // hide this value on new dropdown and show the other values
                if(old_value){
                    if(object_str == 'area' && type == 'register_area'){
                        $('#area_new option').show();
                        $('#area_new option[value='+old_value+']').prop('selected', false).hide();
                        $('#area_new option').each(function(k,v){
                            if($(v).css('display') != 'none'){
                                $('#area_new').val($(v).val());
                                return false;
                            }
                        });
                    } else if(object_str == 'location' && type == 'register_location'){
                        $('#location_new option').show();
                        $('#location_new option[value='+old_value+']').prop('selected', false).hide();
                        $('#location_new option').each(function(k,v){
                            if($(v).css('display') != 'none'){
                                $('#location_new').val($(v).val());
                                return false;
                            }
                        });
                    } else if(object_str == 'survey' && type == 'survey'){
                        $('#survey_new option').show();
                        $('#survey_new option[value='+old_value+']').prop('selected', false).hide();
                        $('#survey_new option').each(function(k,v){
                            if($(v).css('display') != 'none'){
                                $('#survey_new').val($(v).val());
                                return false;
                            }
                        });
                    };
                }
            }

            $('body').on('change', '#property_new', function(){
                hideError();
            })

            $('#select-type').trigger('change');

        });

        function hideField(arr_ids){
            $.each(arr_ids, function(k,v){
                $('#' + v).closest('.row').addClass('d-none');
            });
        }

        function showField(arr_ids){
            $.each(arr_ids, function(k,v){
                $('#' + v).closest('.row').removeClass('d-none');
            });
        }

        function ajaxByField(old_fields, new_fields, property_id, this_id) {
            var next = false;
            $.each(old_fields, function (k, v) {
                var sub_str = v.substr(0, v.indexOf('_'));
                var route = '';
                if(v == this_id){
                    next = true;// one more step will run
                    return;
                }
                if(next){
                    // property -> no route
                    if (sub_str) {
                        if (sub_str == 'survey') {
                            route = '{{ route('ajax.property_survey_admin_tool') }}';
                        } else if (sub_str == 'project') {
                            route = '{{ route('ajax.property_project_admin_tool') }}';
                        }  else if (sub_str == 'area') {
                            route = '{{ route('ajax.property_area_admin_tool') }}';
                        } else if (sub_str == 'location') {
                            route = '{{ route('ajax.property_location_admin_tool') }}';
                        } else if (sub_str == 'item') {
                            route = '{{ route('ajax.property_item_admin_tool') }}';
                        }
                    }
                    // property no need ajax
                    if(route){
                        // show
                        showField([v]);
                        ajaxByType(route, v);
                    }
                }
            })
            // for new field

            if(new_fields){
                // new field id = changed old id then no run
                if (type) {
                    var route = '';
                    var sub_str = new_fields.substr(0, new_fields.indexOf('_'));
                    var this_id = this_id.substr(0, this_id.indexOf('_'));
                    // console.log(sub_str, this_id)
                    if (sub_str && sub_str != this_id) {
                        if (sub_str == 'survey') {
                            route = '{{ route('ajax.property_survey_admin_tool') }}';
                        } else if (sub_str == 'project') {
                            route = '{{ route('ajax.property_project_admin_tool') }}';
                        }  else if (sub_str == 'area') {
                            route = '{{ route('ajax.property_area_admin_tool') }}';
                        } else if (sub_str == 'location') {
                            route = '{{ route('ajax.property_location_admin_tool') }}';
                        } else if (sub_str == 'item') {
                            route = '{{ route('ajax.property_item_admin_tool') }}';
                        }
                    }
                }
                console.log(route);
                // property no need ajax
                if(route){
                    // showed when change type
                    // showField([v]);
                    ajaxByType(route, new_fields);
                }
            }
            // $('#'+new_fields).removeClass('d-none');
        }

        // get new property/location/area id for new fields later
        function ajaxByType(route, field_id){
            $("#" + field_id).find('option').remove();
            var sub_str = field_id.substr(field_id.indexOf('_') + 1);
            var property_id = '';
            var survey_id = 0;
            var area_id = '';
            var location_id = '';
            if($("#property_old").closest('.row').is(":visible")) {
                property_id = $("#property_old").val();
            }
            // console.log($("#survey_old").closest('.row').is(":visible"), $("#survey_old").val());
            if($("#survey_old").closest('.row').is(":visible")) {
                survey_id = $("#survey_old").val();
            }
            if($("#area_old").closest('.row').is(":visible")) {
                area_id = $("#area_old").val();
            }
            if($("#location_old").closest('.row').is(":visible")) {
                location_id = $("#location_old").val();
            }

            $.ajax({
                type: "GET",
                url: route,
                data: {property_id: property_id, survey_id: survey_id, area_id: area_id, location_id: location_id, is_locked: true},
                cache: false,
                async: false,
                success: function (html) {
                    $.each( html, function( key, value ) {
                        $("#" + field_id).append($('<option>', {
                            value: value.id,
                            text : function(){
                                var str = '';
                                if(value.reference){
                                    str = stripHtml(value.reference);
                                }
                                if(value.description){
                                    if(str){
                                        str += " - " +stripHtml(value.description);
                                        if(value.shine_reference){
                                            str += " - " +stripHtml(value.shine_reference);
                                        }
                                    } else {
                                        str = stripHtml(value.description);
                                    }
                                }
                                return str;
                            }
                        }));
                    });

                    if(sub_str == 'new'){
                        var length = $("#" + field_id + ' option').length;
                        if(length < 2){
                            $("#" + field_id).find("option").eq(0).remove();
                        } else {
                            $("#" + field_id).find("option").eq(0).hide();
                            $("#" + field_id).find("option").eq(1).attr('selected', true);
                        }
                    }
                }
            });
        }

        //remove error
        function hideError(){
            $('#form-admin-tool').find('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').hide();
            $('#description').val('');
        }

        function stripHtml(html){
            // Create a new div element
            var temporalDivElement = document.createElement("div");
            // Set the HTML content with the providen
            temporalDivElement.innerHTML = html;
            // Retrieve the text property of the element (cross-browser support)
            return temporalDivElement.textContent || temporalDivElement.innerText || "";
        }



    </script>
@endpush

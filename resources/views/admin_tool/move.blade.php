@extends('admin_tool.index')
@section('toolbox_content')

    <div>
        <h3 class="text-center mt-4">Move Action</h3>
        <form method="POST" id="form-action"  action="{{ route('toolbox.post_move') }}">
            @csrf
            <input type="hidden" id="description" name="description">
            <!-- Move form -->
            <div  id="form-admin-tool">
                @include('form_admin_tool.form_select_move_type',['id' => 'select-type', 'title' => 'What would you like to move?'])
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
            <!-- End Move form -->
            {{--        @include('modals.toolbox_reason',['title' => 'Please give a reason for removing Item', 'modal_id' => 'reason-na'])--}}
            @include('modals.toolbox_submit',['title' => 'move', 'modal_id' => 'toolbox', 'header' => 'Are you sure you want to move the following?'])


            <div style="margin-left: 75px">
                <button onclick="return false;" type="submit" id="toolbox_submit" class="btn light_grey_gradient">
                    Move
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
                    //Move: Survey MS123 from Property PL2 - Stephen Home to PL3 - Property Stephen2.
                    audit_content = action + ' Survey "' + survey_old + '" from Property "'
                        + property_old_ref + ' - ' + property_old + '" to Property "' + property_new_ref + ' - ' + property_new + '"';
                } else if(type == 'project'){
                    //Move: Project PR123 from Property PL2 - Stephen Home to PL3 - Property Stephen2.
                    audit_content = action + ' Project "' + project_old + '" from Property "'
                        + property_old_ref + ' - ' + property_old + '" to Property "' + property_new_ref + ' - ' + property_new + '"';
                } else if(type == 'survey_location'){
                    //Move: Location RL123 - Kitchen to AF999 - 999 from MS999, AF123 - 00, Property PL2 - Stephen Home.
                    audit_content = action + ' Survey Location "' + location_old
                        + '" to Area "' + area_new + '" from Survey "' + survey_old + '", "' + area_old + '", "' + property_old_ref + ' - ' + property_old + '"';
                } else if(type == 'survey_item'){
                    //Move Item IN222 to Location RL222 - 00 from Survey MS99, Location RL123, Area AF23, Property PL
                    audit_content = action + ' Survey Item "' + item_old
                        + '" to Location "' + location_new + '" from Survey "' + survey_old + '", "' + location_old + '", "' + area_old + '", "' + property_old_ref + ' - ' + property_old + '"';
                } else if(type == 'register_location'){
                    //Move: Location RL123 - Kitchen to AF999 - 999 from MS999, AF123 - 00, Property PL2 - Stephen Home.
                    audit_content = action + ' Register Location "' + location_old
                        + '" to Area "' + area_new + '" from Area "' + area_old + '", "' + property_old_ref + ' - ' + property_old + '"';
                } else if(type == 'register_item'){
                    //Move Item IN222 to Location RL222 - 00 from Location RL123, Area AF23, Property PL
                    audit_content = action + ' Register Item "' + item_old
                        + '" to Location "' + location_new + '" from Location "' + location_old + '", "' + area_old + '", "' + property_old_ref + ' - ' + property_old + '"';
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
                if(type == 'survey' || type == 'project'){
                    showField(['property_new']);
                } else if(type == 'survey_location'){
                    showField(['area_new']);
                } else if(type == 'survey_item'){
                    showField(['area_new','location_new']);
                } else if(type == 'register_location'){
                    showField(['area_new']);
                } else if(type == 'register_item'){
                    showField(['area_new','location_new']);
                }
                // reset new fields later
                $.each(['#property_old', '#survey_old', '#area_old', '#location_old','#item_old'], function(k,v){
                    if(v == '#property_old'){
                        $('#property_old-input').val('');
                    }
                    $(v).find('option').remove();
                })
            });

            $('body').on('change', '#property_old, #survey_old, #area_old, #location_old', function(){
                hideError();
                var property_id = $('#property_old').val();
                var attr_id = $(this).prop('id');
                if(property_id){
                    // if this = input[name="property-input"] then no check
                    if(type == 'survey'){
                        ajaxByField(['property_old','survey_old'], ['property_new'], attr_id);
                    }else if(type == 'project'){
                        ajaxByField(['property_old','project_old'], ['property_new'], attr_id);
                    }  else if(type == 'survey_location'){
                        ajaxByField(['property_old','survey_old','area_old','location_old'], ['area_new'], attr_id);
                    } else if(type == 'survey_item'){
                        ajaxByField(['property_old','survey_old','area_old','location_old','item_old'], ['area_new','location_new'], attr_id);
                    } else if(type == 'register_location'){
                        ajaxByField(['property_old','area_old','location_old'], ['area_new'], attr_id);
                    } else if(type == 'register_item'){
                        ajaxByField(['property_old','area_old','location_old','item_old'], ['area_new','location_new'], attr_id);
                    }
                }
            });
            $('body').on('change', '#area_new', function(){
                var attr_id = $(this).prop('id');
                if(type == 'survey_item' || type == 'register_item'){
                    ajaxByField(['area_new','location_new'], [], attr_id, true);
                }
            });


            // new function for new fields here

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

        function ajaxByField(old_fields, new_fields, this_id, is_new) {
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
                        if(is_new){
                            ajaxByNewType(route, v);
                        } else {
                            ajaxByType(route, v);
                        }
                    }
                }
            })
            // for new field
            if(new_fields){
                // move Item Register/Survey type, new fields only change when property old change
                // cause Item can move to dif area in the same property
                if((type == 'register_item' || type == 'survey_item') && (this_id != 'property_old' && this_id != 'survey_old')){
                    return;
                }
                $.each(new_fields, function(k,v){
                    if (type) {
                        var route = '';
                        var sub_str = v.substr(0, v.indexOf('_'));
                        var this_id_check = this_id.substr(0, this_id.indexOf('_'));
                        // console.log(sub_str, this_id_check)
                        if (sub_str && sub_str != this_id_check) {
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
                    // property no need ajax
                    if(route){
                        // showed when change type
                        // showField([v]);
                        ajaxByType(route, v);
                    }
                })
            }
            // $('#'+new_fields).removeClass('d-none');
        }


        function ajaxByType(route, field_id){
            $("#" + field_id).find('option').remove();
            var property_id = '';
            var survey_id = 0;
            var area_id = '';
            var location_id = '';

            if($("#property_old").closest('.row').is(":visible")) {
                property_id = $("#property_old").val();
            }
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
                data: {property_id: property_id, survey_id: survey_id, area_id: area_id, location_id: location_id},
                cache: false,
                async: false,
                success: function (html) {
                    $.each( html, function( key, value ) {
                        var type = 0;

                        if(value.type && value.type > 0){
                            type = value.type;
                        }

                        $("#" + field_id).append($('<option>', {
                            value: value.id,
                            'data-type': type,
                            text : function(){
                                var str = '';
                                if(value.reference){
                                    str = stripHtml(value.reference);
                                }
                                if(value.description){
                                    if(str){
                                        str += " - " +stripHtml(value.description);
                                    } else {
                                        str = stripHtml(value.description);
                                    }
                                }
                                return str;
                            }
                        }));
                    });
                }
            });
        }

        // for new field
        function ajaxByNewType(route, field_id){
            $("#" + field_id).find('option').remove();
            var property_id = '';
            var survey_id = 0;
            var area_id = '';
            var location_id = '';
            //when property new is not visible then get value of property old
            if($("#property_new").closest('.row').is(":visible")) {
                property_id = $("#property_new").val();
            } else if($("#property_old").closest('.row').is(":visible")){
                property_id = $("#property_old").val();
            }

            if($("#survey_new").closest('.row').is(":visible")) {
                survey_id = $("#survey_new").val();
            } else if($("#survey_old").closest('.row').is(":visible")) {
                survey_id = $("#survey_old").val();
            }
            if($("#area_new").closest('.row').is(":visible")) {
                area_id = $("#area_new").val();
            }
            if($("#location_new").closest('.row').is(":visible")) {
                location_id = $("#location_new").val();
            }

            $.ajax({
                type: "GET",
                url: route,
                data: {property_id: property_id, survey_id: survey_id, area_id: area_id, location_id: location_id},
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
                                    } else {
                                        str = stripHtml(value.description);
                                    }
                                }
                                return str;
                            }
                        }));
                    });
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

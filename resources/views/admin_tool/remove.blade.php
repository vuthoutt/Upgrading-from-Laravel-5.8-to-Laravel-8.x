@extends('admin_tool.index')
@section('toolbox_content')

    <div>
        <h3 class="text-center mt-4">Remove Action</h3>
        <form method="POST" id="form-action"  action="{{ route('toolbox.post_remove') }}">
            @csrf
            <input type="hidden" id="description" name="description">
            <!-- Move form -->
            <div  id="form-admin-tool">
                @include('form_admin_tool.form_select_remove_type',['id' => 'select-type', 'title' => 'What would you like to remove?'])
                <div class="row">
                    <div class="col-6 parent-cl" id="from_tab" data-text="from">
                        @include('form_admin_tool.form_select_zone',['id' => 'group_old', 'name' => 'group', 'class' => 'form-option', 'title' => 'What Group would you like to use?'])
                        @include('form_admin_tool.form_select_document_category',['id' => 'document_category_old', 'name' => 'document category','class' => 'form-option', 'title' => 'What Document Category would you like to use?'])
                        @include('form_admin_tool.form_select_document_type',['id' => 'document_type_old', 'name' => 'document type','class' => 'form-option', 'title' => 'What Document Type would you like to use?'])
                        @include('form_admin_tool.search_incident_report',['id' => 'incident_old', 'hide' => true, 'class' => 'form-option', 'title' => 'What Incident Report would you like to use?'])
                        @include('form_admin_tool.search_property',['id' => 'property_old', 'hide' => true, 'class' => 'form-option', 'title' => 'What Property would you like to use?'])
                        @include('form_admin_tool.search_survey',['id' => 'survey_search_old',  'hide' => true, 'class' => 'form-option', 'title' => 'What Survey would you like to use?'])
                        @include('form_admin_tool.search_project',['id' => 'project_search_old',  'hide' => true, 'class' => 'form-option', 'title' => 'What Project would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'project_old', 'name' => 'project', 'class' => 'form-option', 'title' => 'What Project would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'survey_old', 'name' => 'survey','class' => 'form-option', 'title' => 'What Survey would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'area_old', 'name' => 'area','class' => 'form-option', 'title' => 'What Area would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'location_old', 'name' => 'location','class' => 'form-option', 'title' => 'What Location would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'item_old', 'name' => 'item','class' => 'form-option', 'title' => 'What Item would you like to use?'])
                        @include('form_admin_tool.form_select',['id' => 'document_old', 'name' => 'document','class' => 'form-option', 'title' => 'What Document would you like to use?'])
                    </div>
                    <div class="col-6 parent-cl" id="to_tab" data-text="to">
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
            @include('modals.toolbox_submit',['title' => 'Remove', 'modal_id' => 'toolbox', 'header' => 'Are you sure you want to remove the following?'])


            <div style="margin-left: 75px">
                <button onclick="return false;" type="submit" id="toolbox_submit" class="btn light_grey_gradient">
                    Remove
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
                var incident_old = $("#incident_old-search").val();//name property
                var property_old_ref = $("#property_old-reference").val();//ref PLxxx property
                var incident_old_ref = $("#incident_old-reference").val();//ref PLxxx property
                var survey_old = $("#survey_old option:selected").text();
                var project_old = $("#project_old  option:selected").text();
                var area_old = $("#area_old  option:selected").text();
                var location_old = $("#location_old  option:selected").text();
                var item_old = $("#item_old  option:selected").text();
                var group_old = $("#group_old  option:selected").text();
                var document_old = $("#document_old option:selected").text();
                var document_type = $('#document_category_old').val();
                var from_document_type = '';
                if(document_type == 'property'){
                    from_document_type = '" from Property "'
                        + property_old_ref + ' - ' + property_old + '"';
                } else if(document_type == 'project'){
                    var project_search = $('#project_search_old-reference').val();
                    from_document_type = '" from Project "'
                        + project_search + '"';
                } else if(document_type == 'survey'){
                    var survey_search = $('#survey_search_old-reference').val();
                    from_document_type = '" from Survey "'
                        + survey_search + '"';
                } else if(document_type == 'incident'){
                    var incident_search = $('#incident_old-reference').val();
                    from_document_type = '" from Incident Report "'
                        + incident_search + '"';
                }


                var audit_content = '';
                var action = $('.nav.list-group.list-group-flush').find('.active').text();
                var action_lowercase = $('.nav.list-group.list-group-flush').find('.active').text().toLowerCase();
                if(type == 'survey'){
                    //Remove: Survey MS123 from Property PL2 - Stephen Home
                    audit_content = action + ' Survey "' + survey_old + '" from Property "'
                        + property_old_ref + ' - ' + property_old + '"';
                } else if(type == 'project'){
                    //Remove: Project PR123 from Property PL2 - Stephen Home
                    audit_content = action + ' Project "' + project_old + '" from Property "'
                        + property_old_ref + ' - ' + property_old + '"';
                } else if(type == 'group'){
                    //Remove: Group  PG123 - Ware.
                    audit_content = action + ' Group "' + group_old + '"';
                } else if(type == 'properties'){
                    //Remove: Property  Pl123 - Ware.
                    audit_content = action + ' Property "' + property_old + '"';
                } else if(type == 'document'){
                    //Remove: Document OD222 - Ware.
                    audit_content = action + ' Document "' + document_old  + from_document_type;
                } else if(type == 'register_area'){
                    audit_content = action + ' Register Area "' + area_old + '" from Property "' + property_old_ref + ' - ' + property_old + '"';
                }  else if(type == 'register_location'){
                    audit_content = action + ' Register Location "' + location_old
                        + '" from Area "' + area_old + '", "' + property_old_ref + ' - ' + property_old + '"';
                } else if(type == 'register_item'){
                    audit_content = action + ' Register Item "' + item_old
                         + '" from Location "' + location_old + '", "' + area_old + '", "' + property_old_ref + ' - ' + property_old + '"';
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
                // $('#from_tab').find('.row.register-form.form-summary').find('option').remove();
                hideField(['property_old','survey_search_old','project_search_old','incident_old'], true);
                // reset new fields later
                $.each(['#group_old','#group_old_parent','#property_old','#project_old','#document_old','#survey_old', '#area_old', '#location_old','#item_old'], function(k,v){
                    $(v).find('option').remove();
                    $(v).closest('.form-option').addClass('d-none');
                })
                //Show initial value
                if(type == 'properties' || type == 'survey'|| type == 'project'|| type == 'register_area'
                    || type == 'register_item'|| type == 'register_location'){
                    showField(['property_old']);
                } else if(type == 'group'){
                    ajaxGroup();
                } else if(type == 'document'){
                    showField(['document_category_old']);
                    $('#document_category_old').trigger('change');
                }
            });

            $('body').on('change', '#group_old, #property_old, #area_old, #location_old,' +
                ' #item_old, #survey_old, #project_old, #survey_search_old, #project_search_old, #incident_old', function(){
                hideError();
                var attr_id = $(this).prop('id');
                console.log(attr_id);
                    // if this = input[name="property-input"] then no check
                if(type == 'group'){
                    ajaxByField(['group_old'], '', attr_id);
                }else if(type == 'properties'){
                    ajaxByField(['property_old'], '', attr_id);
                }else if(type == 'survey'){
                    ajaxByField(['property_old','survey_old'], '', attr_id);
                }else if(type == 'project'){
                    ajaxByField(['property_old','project_old'], '', attr_id);
                } else if(type == 'document'){
                    if(attr_id == 'property_old' || attr_id == 'survey_search_old' || attr_id == 'project_search_old' || attr_id == 'incident_old'){
                        ajaxDocument(true);
                    }
                } else if(type == 'register_area'){
                    ajaxByField(['property_old','area_old'], '', attr_id);
                } else if(type == 'register_location'){
                    ajaxByField(['property_old','area_old','location_old'], '', attr_id);
                } else if(type == 'register_item'){
                    ajaxByField(['property_old','area_old','location_old','item_old'], '', attr_id);
                }
            });

            // $('body').on('change', '#property_old, #survey_search_old, #project_search_old', function(){
            //     //show document + remove option
            //     if(type == 'document'){
            //         ajaxDocument(true);
            //     }
            // });

            $('#document_category_old').change(function(){
                //show document + remove option
                var val = $(this).val();
                $("#document_type_old option[data-type=" + val + "]").show();
                $("#document_type_old option[data-type!=" + val + "]").hide();
                $('#document_type_old option').each(function () {
                    if ($(this).css('display') != 'none') {
                        $(this).prop("selected", true);
                        return false;
                    }
                });
                $("#document_type_old").closest('.row').removeClass('d-none');
                $("#document_type_old").trigger('change');
                ajaxDocument();
            });

            $('#document_type_old').change(function(){
                //show document + remove option
                var search_type = $("#document_category_old").val();
                hideField(['property_old','survey_search_old','project_search_old', 'incident_old'], true);
                if(search_type == 'property'){
                    showField(['property_old-input']);
                } else if(search_type == 'survey'){
                    showField(['survey_search_old-input']);
                } else if(search_type == 'project'){
                    showField(['project_search_old-input']);
                } else if(search_type == 'incident'){
                    showField(['incident_old-input']);
                }
                ajaxDocument();
            });

            $('#select-type').trigger('change');

        });

        function hideField(arr_ids, search_type){
            $.each(arr_ids, function(k,v){
                $('#' + v).closest('.row').addClass('d-none');
                if(search_type){
                    $('#' + v).val('');
                    $('#' + v + '-input').val('');
                    $('#' + v + '-search').val('');
                    $('#' + v + '-reference').val('');
                }
            });
        }

        function showField(arr_ids){
            $.each(arr_ids, function(k,v){
                $('#' + v).closest('.row').removeClass('d-none');
            });
        }



        function ajaxByField(old_fields, new_fields, this_id) {
            var next = false;
            $.each(old_fields, function (k, v) {
                // show
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
                        } else if (sub_str == 'group') {
                            route = '{{ route('ajax.zone_admin_tool') }}';
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
                        ajaxByType(route, v);
                        showField([v]);
                    }
                }
            })

        }
        //show and remove options for document
        function ajaxDocument(flag) {
            showField(['document_old']);
            var type = $('#document_type_old').val();
            var survey_id = $('#survey_search_old').val();
            var property_id = $('#property_old').val();
            var project_id = $('#project_search_old').val();
            var incident_id = $('#incident_old').val();
            if(flag){
                $.ajax({
                    type: "GET",
                    url: '{{ route('ajax.document_admin_tool') }}',
                    data: {type: type, survey_id: survey_id, property_id: property_id, project_id: project_id, incident_id: incident_id},
                    cache: false,
                    async: false,
                    success: function (html) {
                        $.each( html, function( key, value ) {
                            $("#document_old").append($('<option>', {
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
            } else {
                $("#document_old").find('option').remove();
            }
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
                                if(value.shine_reference){
                                    if(str){
                                        str += " - " +stripHtml(value.shine_reference);
                                    } else {
                                        str = stripHtml(value.shine_reference);
                                    }
                                }
                                return str;
                            }
                        }));
                    });
                }
            });
        }

        function ajaxGroup(){
            $.ajax({
                type: "GET",
                url: '{{ route('ajax.zone_admin_tool') }}',
                cache: false,
                async: false,
                success: function (html) {
                    $.each( html, function( key, value ) {
                        if(value.parent_id == 0){
                            //parent
                            $("#group_old_parent").append($('<option>', {
                                value: value.id,
                                'data-parent': '0',
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
                        } else {
                            //children
                            $("#group_old").append($('<option>', {
                                value: value.id,
                                'data-parent': value.parent_id,
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
                        }
                    });
                }
            });
            showField(['group_old']);
            updateZone($('#group_old_parent').val());
        }

        $('body').on('change','#group_old_parent',function(){
            updateZone($(this).val());
        });


        function updateZone(parent_id){
            $('#group_old').find('option').hide();
            if(parent_id){
                $('#group_old option[data-parent="'+parent_id+'"]').show();
                $("#group_old").val($('#group_old option[data-parent="'+parent_id+'"]:first').val());
            }
            //showed base on parent_id
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

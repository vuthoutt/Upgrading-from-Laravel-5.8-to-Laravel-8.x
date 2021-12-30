@extends('admin_tool.index')
@section('toolbox_content')

    <div>
        <h3 class="text-center mt-4">Unlock Action</h3>
        <form method="POST" id="form-action"  action="{{ route('toolbox.post_unlock') }}">
            @csrf
            <input type="hidden" id="description" name="description">
            <div  id="form-admin-tool">
                @include('form_admin_tool.form_select_unlock_type',['id' => 'select-type', 'title' => 'What would you like to unlock?'])
                <div class="row">
                    <div class="col-6 parent-cl" id="from_tab" data-text="from">
                        @include('form_admin_tool.search_survey',['id' => 'survey_search_old',  'hide' => false, 'type_search' => 1 ,'class' => 'form-option', 'title' => 'What Survey would you like to use?'])
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
            {{--        @include('modals.toolbox_reason',['title' => 'Please give a reason for removing Item', 'modal_id' => 'reason-na'])--}}
            @include('modals.toolbox_submit',['title' => 'unlock', 'modal_id' => 'toolbox', 'header' => 'Are you sure you want to unlock the following?'])


            <div style="margin-left: 75px">
                <button onclick="return false;" type="submit" id="toolbox_submit" class="btn light_grey_gradient">
                    Unlock
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
                var survey_old = $("#survey_search_old-reference").val();
                var audit_content = '';
                var action = $('.nav.list-group.list-group-flush').find('.active').text();
                var action_lowercase = $('.nav.list-group.list-group-flush').find('.active').text().toLowerCase();
                if(type == 'survey'){
                    audit_content = action + ' Survey "' + survey_old + '"';
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
                    $('#red_text').html('Please be informed that all surveyor data on the selected survey will be permanently lost');
                    $("#toolbox").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            });

            $('body').on('change', '#survey_search_old-input', function(){
                hideError();
            })

            $('body').on('change', '#select-type', function(){
                hideError();
                type = $(this).val();
            });

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
                if (type) {
                    var route = '';
                    if (type == 'survey_location' || type == 'register_location') {
                        route = '{{ route('ajax.property_area_admin_tool') }}';
                    } else if (type == 'survey_item' || type == 'register_item' ) {
                        route = '{{ route('ajax.property_location_admin_tool') }}';
                    }
                }
                // property no need ajax
                if(route){
                    // showed when change type
                    // showField([v]);
                    ajaxByType(route, new_fields);
                }
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

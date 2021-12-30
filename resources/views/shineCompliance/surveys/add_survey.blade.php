@extends('shineCompliance.layouts.app')
@section('content')
    @if(isset($property->parents))
        @include('shineCompliance.partials.nav', ['breadCrumb' => 'sub_survey_add_shineCompliance', 'color' => 'orange', 'data' => $property])
    @else
        @include('shineCompliance.partials.nav', ['breadCrumb' => 'survey_add_shineCompliance','color' => 'orange', 'data' => $property])
    @endif
<div class="container prism-content">
    <h3>Add Survey</h3>
        <div class="main-content">
        <form method="POST" action="{{ route('shineCompliance.survey.post_add_survey',['property_id' => $property_id]) }}" id="form_add_survey" enctype="multipart/form-data" class="form-shine">
            @csrf
            <input type="hidden" name="list_area[]">
            <input type="hidden" name="list_location[]">
            <input type="hidden" name="list_item[]">
            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Survey Type:</label>
                <div class="col-md-5">
                    <div class="form-group ">
                        <select  class="form-control" name="surveyType" id="surveyType">
                            <option value='1'>Management Survey</option>
                            <option value='2'>Refurbishment Survey</option>
                            <option value='3'>Re-inspection Report</option>
                            <option value='4'>Demolition Survey</option>
                            <option value='5'>Management Survey – Partial</option>
                            <option value='6'>Sample Survey</option>
                        </select>
                    </div>
                </div>
            </div>
            @include('shineCompliance.forms.form_datepicker',['title' => 'Survey Date:', 'data' => date('d/m/Y'), 'name' => 'date' ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Survey Due Date:', 'data' => date('d/m/Y'), 'name' => 'due-date' ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Survey Start Date:', 'data' => date('d/m/Y'), 'name' => 'sv-start-date' ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Survey Finish Date:', 'data' => date('d/m/Y'), 'name' => 'sv-finish-date' ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Samples Sent to Lab:', 'data' => date('d/m/Y'), 'name' => 'sample-sent-to-lab-date' ])
            {{-- @include('shineCompliance.forms.form_datepicker',['title' => 'Samples Received from Lab:', 'data' => date('d/m/Y'), 'name' => 'sample-received-from-lab-date' ]) --}}
            @include('shineCompliance.forms.form_dropdown',['title' => 'Organisation:', 'data' => $clients, 'name' => 'clientKey', 'key'=> 'id', 'value'=>'name', 'compare_value' => \Auth::user()->client_id, 'required' => true ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Workstream/Programme:', 'data' => $work_streams, 'name' => 'work_stream', 'key'=> 'id', 'value'=>'description', 'required' => false ])
            @include('shineCompliance.forms.form_input',['title' => 'Survey Cost:', 'data' => '', 'name' => 'cost', 'maxlength' => 200])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Asbestos Lead:', 'data' => $surveyUsers, 'name' => 'leadBy', 'key'=> 'id', 'value'=>'full_name', 'required' => true ])
            {{-- @include('shineCompliance.forms.form_dropdown',['title' => 'Second Asbestos Lead:', 'data' => $surveyUsers, 'name' => 'secondLeadBy', 'key'=> 'id', 'value'=>'full_name' ]) --}}
            @include('shineCompliance.forms.form_select_ajax',['title' => 'Surveyor:', 'name' => 'surveyor' ])
            @include('shineCompliance.forms.form_select_ajax',['title' => 'Secondary Asbestos Lead:', 'name' => 'secondLeadBy' ])
            {{-- @include('shineCompliance.forms.form_select_ajax',['title' => 'Second Surveyor:', 'name' => 'secondSurveyor' ]) --}}
            @include('shineCompliance.forms.form_dropdown',['title' => 'Second Surveyor:', 'data' => $secondSurveyors, 'name' => 'secondSurveyor', 'key'=> 'id', 'value'=>'full_name'])
            @include('shineCompliance.forms.form_select_ajax',['title' => 'Project Manager:', 'name' => 'consultantKey' ])
            {{-- @include('shineCompliance.forms.form_select_ajax',['title' => 'CAD Technician:', 'name' => 'cad_tech' ]) --}}
{{--            @include('shineCompliance.forms.form_dropdown',['title' => 'CAD Technician:', 'data' => $cadTechnicans, 'name' => 'cad_tech', 'key'=> 'id', 'value'=>'full_name'])--}}
            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >
                    Linked Project:
                    @if(isset($projects) and !is_null($projects) and !$projects->isEmpty())
                        <span style="color: red;">*</span>
                    @endif
                </label>
                <div class="col-md-5">
                    <div class="form-group ">
                        <select  class="form-control @error('projectKey') is-invalid @enderror" name="projectKey" id="projectKey">
                            @if(isset($projects) and !is_null($projects) and !$projects->isEmpty())
                            <option value="-1">------ Please select a project -------</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" >{{ $project->reference }}</option>
                                @endforeach
                            @else
                                <option value="0">No Projects</option>
                            @endif
                        </select>
                        @error('projectKey')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
{{--            @include('shineCompliance.forms.form_select_ajax',['title' => 'Quality Checked By:', 'name' => 'qualityKey' ])--}}
            @include('shineCompliance.forms.form_select_ajax',['title' => 'Analyst:', 'name' => 'analystKey' ])
            @include('shineCompliance.forms.form_checkbox_survey',['title' => 'Settings:', 'name' => 'priorityAssessmentRequired','id' => 'priority_ass','title_right' => 'Priority Risk Assessment (PAS)', 'data' => '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'constructionDetailsRequired','id' => 'construction_detail','title_right' => 'Property Construction Details','data' => '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'locationVoidInvestigationsRequired','id' => 'location_void','title_right' => 'Location Void Investigations','data' => '','compare' => 1 ])
{{--             <div id="location_void" style="margin-left: 90px">
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'all_void','title_right' => 'Select All Voids','data' => '','compare' => 1, 'id' => 'location_void_all' ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'ceiling_void','title_right' => 'Ceiling Void','data' => '','compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'floor_void','title_right' => 'Floor Void','data' => '','compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'cavities','title_right' => 'Cavities','data' => '','compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'risers','title_right' => 'Risers','data' => '','compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'ducting','title_right' => 'Ducting','data' => '','compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'boxing','title_right' => 'Boxing','data' => '','compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'pipework','title_right' => 'Pipework','data' => '','compare' => 1 ])
            </div> --}}
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'locationConstructionDetailsRequired','id' => 'location_con','title_right' => 'Location Construction Details','data' => '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'RDinManagementAllowed','id' => 'r_and_d','title_right' => 'R&D Elements','data' => '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'licenseStatusRequired','id' => 'license_status','title_right' => 'Licensed/non Licensed','data' => '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'photosRequired','id' => 'photo_re','title_right' => 'Item Photos Required','data' => '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'propertyPlanPhoto','id' => 'property_plan','title_right' => 'Surveyors Notes Required','data' => '','compare' => 1 ])
{{--            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'external_laboratory','id'=> 'external_laboratory','title_right' => 'External Laboratory','data' => '','compare' => 1 ])--}}


            <div class="row register-form mt-5">
                <label class="col-md-2 col-form-label text-md-left font-weight-bold" >Room/locations:</label>
                <div class="col-md-9" >
                     @if(!empty($dataProperty))
                    <div class="select-option setting-survey" style="margin-left: 100px; margin-bottom: 30px;margin-top: 5px">
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="inaccessibleRoom" onclick="checkSettingSurvey(this)">
                            <label class="custom-control-label" for="inaccessibleRoom">Inaccessible Room/locations(s)</label>
                        </div>
                        <label class="font-weight-bold mb-3" >Accessible Room/locations with:</label>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="inaccessibleItems" onclick="checkSettingSurvey(this)">
                            <label class="custom-control-label" for="inaccessibleItems">Inaccessible Items</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="highRiskItems" onclick="checkSettingSurvey(this)">
                            <label class="custom-control-label" for="highRiskItems">High Risk Items</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="MediumRiskItems" onclick="checkSettingSurvey(this)">
                            <label class="custom-control-label" for="MediumRiskItems">Medium Risk Items</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="lowRiskItems" onclick="checkSettingSurvey(this)">
                            <label class="custom-control-label" for="lowRiskItems">Low Risk Items</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="veryLowRiskItems" onclick="checkSettingSurvey(this)">
                            <label class="custom-control-label" for="veryLowRiskItems">Very Low Risk Items</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="NoRisk" onclick="checkSettingSurvey(this)">
                            <label class="custom-control-label" for="NoRisk">No Risk Items</label>
                        </div>
                    </div>
                     @endif
                    <div id="mytree" class="bstree" style="margin-left: 60px;">
                        <ul>
                            <li data-id="root" data-level="0"><span>Everything</span>
                                <ul>
                                    @foreach($dataProperty as $area)
                                    <input id="bstree-data" type="hidden" name="bstree-data" data-ancestors="">
                                        <li data-id="{{ $area['area']->id }}" data-area-id="{{ $area['area']->id }}" data-level="area"><span class="font-weight-bold" >{{ $area['area']->title }}</span>
                                            @if(isset($area['locations']))
                                                @include('shineCompliance.surveys.area_location',['data' => $area['locations'] ])
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 offset-md-3 mt-4">
                <button type="button" id="btn-submit1" class="btn light_grey_gradient_button fs-8pt">
                    <strong>{{ __('Add') }}</strong>
                </button>
            </div>
            @include('shineCompliance.forms.form_warning_require_project',['id'=> 'missing_project'])
        </form>
    </div>
</div>
@endsection
@push('javascript')

<script type="text/javascript">
    $(document).ready(function(){
        //refresh token after a specified period of time
        var csrfToken = $('[name="csrf_token"]').attr('content');
        var period_time = 14*60*1000; // 14min
        setInterval(refreshToken, period_time); // 1 hour

        function refreshToken(){
            $.ajax
            ({
                type: "GET",
                url: "/refresh-csrf",
                data: {},
                cache: false,
                success: function (html) {
                    $('[name="csrf_token"]').attr('content', html);
                }
            });
        }




        $.ajax
        ({
            type: "GET",
            url: "/ajax/client-users/" + 1,
            data: {},
            cache: false,
            success: function (html) {
                $('#secondLeadBy').append($('<option>', {
                    value: 0,
                    text : '------ Please select an option -------'
                }));
                $.each(html, function (key, value) {
                    $('#secondLeadBy').append($('<option>', {
                        value: value.id,
                        text: value.first_name + ' ' + value.last_name
                    }));
                });
            }
        });

        $("#surveyType").change(function () {
            if ($("#surveyType").val() == {{REFURBISHMENT_SURVEY}}) {
                $("#form-r_and_d").css("opacity",0.4);
                $("#r_and_d").val(0);
                $("#r_and_d").prop('disabled', true);
            }else{
                $("#form-r_and_d").css("opacity",1);
                $("#r_and_d").removeAttr('disabled');
            }

            if($("#surveyType").val() == {{ SAMPLE_SURVEY }}){
                $("#form-r_and_d").css("opacity",0.4);
                $("#r_and_d").val(0);
                $("#r_and_d").prop('disabled', true);

                $("#form-checkbox-priority_ass").css("opacity",0.4);
                $("#priority_ass").val(0);
                $("#priority_ass").prop('disabled', true);

                $("#form-construction_detail").css("opacity",0.4);
                $("#construction_detail").val(0);
                $("#construction_detail").prop('disabled', true);

                $("#form-location_void").css("opacity",0.4);
                $("#location_void").val(0);
                $("#location_void").prop('disabled', true);

                $("#form-location_con").css("opacity",0.4);
                $("#location_con").val(0);
                $("#location_con").prop('disabled', true);

                $("#form-license_status").css("opacity",0.4);
                $("#license_status").val(0);
                $("#license_status").prop('disabled', true);

                $("#form-property_plan").css("opacity",0.4);
                $("#property_plan").val(0);
                $("#property_plan").prop('disabled', true);
            }else{
                if($("#surveyType").val() != {{ REFURBISHMENT_SURVEY }}) {
                    $("#form-r_and_d").css("opacity", 1);
                    $("#r_and_d").removeAttr('disabled');
                }
                $("#form-checkbox-priority_ass").css("opacity",1);
                $("#priority_ass").removeAttr('disabled');

                $("#form-construction_detail").css("opacity",1);
                $("#construction_detail").removeAttr('disabled');

                $("#form-location_void").css("opacity",1);
                $("#location_void").removeAttr('disabled');

                $("#form-location_con").css("opacity",1);
                $("#location_con").removeAttr('disabled');

                $("#form-license_status").css("opacity",1);
                $("#license_status").removeAttr('disabled');

                $("#form-property_plan").css("opacity",1);
                $("#property_plan").removeAttr('disabled');
            }
        });

        $("#surveyType").trigger('change');

        $("#clientKey").change(function () {
            var client_id =  $(this).val();
            if (client_id == 1) {
                $("#form-external_laboratory").show();
            } else {
                $("#form-external_laboratory").hide();
            }
            $("#surveyor").html("");
            // $("#secondSurveyor").html("");
            $("#analystKey").html("");
            $("#consultantKey").html("");
            // $("#cad_tech").html("");

            $.ajax
            ({
                type: "GET",
                url: "/ajax/client-users/" + client_id,
                data: {},
                cache: false,
                success: function (html) {
                    if (html) {
                        // $('#cad_tech').append($('<option>', {
                        //     value: 0,
                        //     text : '------ Please select an option -------'
                        // }));
                        // $.each( html, function( key, value ) {
                        //     $('#cad_tech').append($('<option>', {
                        //         value: value.id,
                        //         text : value.first_name + ' ' + value.last_name
                        //     }));
                        // });
                        // $('#secondSurveyor').append($('<option>', {
                        //     value: 0,
                        //     text : '------ Please select an option -------'
                        // }));
                        $('#surveyor').append($('<option>', {
                            value: 0,
                            text : '------ Please select an option -------'
                        }));
                        $('#analystKey').append($('<option>', {
                            value: 0,
                            text : '------ Please select an option -------'
                        }));
                        $('#consultantKey').append($('<option>', {
                            value: 0,
                            text : '------ Please select an option -------'
                        }));
                        $.each( html, function( key, value ) {
                            // $('#secondSurveyor').append($('<option>', {
                            //     value: value.id,
                            //     text : value.first_name + ' ' + value.last_name
                            // }));
                            $('#analystKey').append($('<option>', {
                                value: value.id,
                                text : value.first_name + ' ' + value.last_name
                            }));

                            $('#consultantKey').append($('<option>', {
                                value: value.id,
                                text : value.first_name + ' ' + value.last_name
                            }));
                            $('#surveyor').append($('<option>', {
                                value: value.id,
                                text : value.first_name + ' ' + value.last_name
                            }));
                        });
                    }
                }
            });
        });
        $("#clientKey").trigger('change');

        $("#location_void_required").change(function () {
            var location_void_required = $(this).is(':checked');
            if (location_void_required) {
                 $("#location_void").show();
            } else {
                $("#location_void").hide();
                $(".location_void").prop( "checked", false );
            }
        });
        $("#location_void_required").trigger('change');

        $("#location_void_all").change(function () {
            var location_void_all = $(this).is(':checked');
            if (location_void_all) {
                 $(".location_void").prop( "checked", true );
            } else {
                $(".location_void").prop( "checked", false );
            }
        });
        $("#location_void_all").trigger('change');

      $hiddenInput = $('#bstree-data');
          $('#mytree').bstree({
            dataSource: $hiddenInput,
            initValues: $hiddenInput.data('ancestors'),
            onDataPush: function (values) {
                return;
              var def = '<strong class="pull-left">Values,&nbsp;</strong>'
              for (var i in values) {
                def += '<span class="pull-left">' + values[i] + '&nbsp;</span>'
              }
              $('#status').html(def)
            },
            updateNodeTitle: function (node, title) {
              // return title
              // return '[' + node.attr('data-id') + '] ' + title + ' ( ' + node.attr('data-level') + ' )'
            }
          });
          $('#btn-submit1').click(function(e){
              e.preventDefault();
              setValueInput();
              if($('#projectKey').val() != '-1'){ //No Project or have to select a project
                  $('#missing_project').hide();
                  $('#form_add_survey').submit();
              } else {
                  $('#missing_project').show();
                  return;
              }
          });
          // lock_list = $('#mytree').find("[data-location-locked="1"]");
          // $.each(li_list, function (k, v) {
          //   alert($(v).data('id'));
          //   });
           //get value checked item/location/area
           //list_area
          function getCheckedNode(type) {
              var arr_result = [];
              if (type) {
                  var li_list = $('#mytree').find("[data-level='" + type + "']");
                  $.each(li_list, function (k, v) {
                      var check = false;
                      var id = $(v).data('id');
                      if (id) {
                        //area and location need to find any checked checkbox then count as checked
                        if(type == 'item'){
                            check = $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked');
                        } else {
                            check = $(v).find('input.bstree-checkbox:checkbox:checked').length > 0;
                        }
                      }
                      if (check) {
                          arr_result.push(id);
                      }
                  });
                  return arr_result;
              }
          }
          //set value for input
        function setValueInput(){
            // get area
            var arr_area = getCheckedNode('area');
            var arr_location = getCheckedNode('location');
            var arr_item = getCheckedNode('item');
            if(arr_area) {
                $('input:hidden[name="list_area[]"]').val(arr_area);
            }
            if(arr_location){
                $('input:hidden[name="list_location[]"]').val(arr_location);
            }
            if(arr_item){
                $('input:hidden[name="list_item[]"]').val(arr_item);
            }
        }


        // remove locked locations
        removeInputLocked();
    });

    //checked item base on setting survey
    function checkSettingSurvey(that){
        //if uncheck setting then trigger click these checkbox bolong to that setting
        var is_checked_setting = $(that).prop('checked');
        // console.log(is_checked_setting);
        // return;
        var check = false;
        var list_setting = $(that).closest('.setting-survey').find('input[type=checkbox]');
        $.each(list_setting, function(k,v){
            if($(v).prop('checked') == true){
                check = true;
                return false;
            }
        })


            var element_id = $(that).prop('id');
            var arr_checked = [];
            var arr_unchecked_item = []; // for location inacc setting
            var list_input = $('#bstree-checkbox-root').closest('li').find('.bstree-children input[type=checkbox]');// get all input excep everything
            $.each(list_input, function(k,v){
                if($(v).prop('checked') == true){
                    arr_checked.push(v);
                } else {
                    if($(v).closest('li').data('item-id')){
                        arr_unchecked_item.push(v);
                    }
                }
            })
            // alert(element_id);
            if(element_id){
               var arr_location_id = [];
               var list_ele = $('#mytree').find('.'+element_id);
               //before trigger click, need to get all checked checkbox then set to them selected after
                // cause when select 2 setting survey, the second setting trigger click maybe unchecked inputs that the perious setting do
                // case setting for inacc location, need to get un checked input because it will un check all items inside those inacc locations
               if(list_ele){
                    $.each(list_ele, function(k,v){
                        var id = $(v).data('id');
                        if(id){
                            //click setting else uncheck setting
                            if(is_checked_setting == true){
                                // only item has no child
                                // var is_has_child = $(v).data('item-id');
                                // console.log($(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked'));
                                if(element_id == 'inaccessibleRoom'){
                                    //when trigger click state change then need to revert the state to correct trigger
                                    var cur_state = $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked') ? false :true;
                                    $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked',!cur_state);
                                }
                                $(v).find("input[id='bstree-checkbox-" + id + "']:first").trigger('click');
                                // console.log(v);
                                //set prop check for location or location and area
                                // console.log($(v).parent('li'));
                                $.each(arr_checked, function(k,v){
                                    $(v).prop('checked',true);
                                });
                                if(element_id == 'inaccessibleRoom'){
                                    $(v).find("input[id='bstree-checkbox-" + id + "']:first").data('inaccess-location', '1');
                                    // console.log($(v).data('inaccess-location'));
                                    $.each(arr_unchecked_item, function(k,v){
                                        $(v).prop('checked',false);
                                    });
                                }

                            } else {
                                //find all and update item that has been checked from that setting from checked to unchecked
                                // get list location id of all those items to check later
                                // case check location setting, need check to find any childs and checked input
                                // if have no then trigger click else do nothing
                                if(element_id == 'inaccessibleRoom'){
                                    var list_item_inside = $(v).find('.bstree-children input[type=checkbox]');
                                    var uncheck = true;
                                    $.each(list_item_inside, function(k1,v1){
                                       if($(v1).prop('checked')){
                                           uncheck = false;
                                           return false;
                                       }
                                    });
                                    $(v).find("input[id='bstree-checkbox-" + id + "']:first").data('inaccess-location',null);
                                    if(uncheck && $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked')){
                                        $(v).find("input[id='bstree-checkbox-" + id + "']:first").trigger('click');
                                    }
                                } else {
                                    //for setting item
                                    var location_id = $(v).data('item-location-id');
                                    if(location_id){
                                        arr_location_id.push(location_id);
                                    }
                                    // console.log(v);
                                    $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked',false);
                                }
                            }
                        }
                    });
               }

                //check if there is no checked item inside then set that location check = true and trigger click
                if(arr_location_id.length > 0){
                    $.each(unique(arr_location_id), function(i, value){
                        var list_item_inside = $('li[data-location-id="'+value+'"]').find('.bstree-children input[type=checkbox]');
                        // console.log(list_item_inside);
                        var uncheck = true;
                        $.each(list_item_inside, function(k1,v1){
                            if($(v1).prop('checked')){
                                uncheck = false;
                                return false;
                            }
                        });
                        var location = $('li[data-location-id="'+value+'"]').find("input[id='bstree-checkbox-" + value + "']:first");
                        // console.log(unique(arr_location_id));
                        // console.log(value);
                        // console.log(uncheck);
                        // console.log($(location).prop('checked'));
                        // console.log($(location).data('inaccess-location'));
                        if(location && uncheck  && !$(location).data('inaccess-location')){
                            if($(location).prop('checked')){
                                $(location).trigger('click');
                            } else {
                                $(location).prop('indeterminate', false);
                            }
                        }
                    });
                }
            }
    }
    //for unique an array
    function unique(array){
        return $.grep(array,function(el,index){
            return index == $.inArray(el,array);
        });
    }

    function removeInputLocked(){
        var li_list = $('#mytree').find("[data-location-locked=1]");
        $.each(li_list, function(k,v){
            var id = $(v).data('id');
            // console.log($(v).find("input[id='bstree-checkbox-" + id + "']:first"));
            $(v).find("input[id='bstree-checkbox-" + id + "']:first").remove();
        })
    }
</script>
@endpush

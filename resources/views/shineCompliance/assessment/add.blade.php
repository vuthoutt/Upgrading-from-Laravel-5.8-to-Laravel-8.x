@extends('shineCompliance.layouts.app')

@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_add', 'color' => 'orange', 'data'=> $property])
    <div class="container-cus prism-content pad-up pl-0">
        <div class="row">
            <h3 class="col-12 title-row">Add Assessment</h3>
        </div>
        <div class="main-content">
            <form id="form_edit_assessment" method="POST" action="{{ route('shineCompliance.assessment.post_add', ['classification' => $classification]) }}" enctype="multipart/form-data" class="form-shine">
                @csrf
                <input type="hidden" name="list_system">
                <input type="hidden" name="list_equipment">
                <input type="hidden" name="list_hazard">
                <input type="hidden" name="list_exist">
                <input type="hidden" name="list_assembly_point">
                <input type="hidden" name="list_vehicle">
                <input type="hidden" id="classification" value="{{ $classification }}">
                @include('shineCompliance.forms.form_input_hidden', ['name' => 'property_id', 'data' => $property_id])
                @include('shineCompliance.forms.form_dropdown',['title' => 'Assessment Type:', 'name' => 'type', 'data' => $assessmentTypes, 'key' => 'key', 'value' => 'value'])
                @include('shineCompliance.forms.form_datepicker',['title' => 'Due Date:', 'name' => 'due_date', 'data' => date('d/m/Y')])
                @include('shineCompliance.forms.form_datepicker',['title' => 'Started Date:', 'name' => 'started_date', 'data' => date('d/m/Y')])
                @include('shineCompliance.forms.form_datepicker',['title' => 'Assessment Start Date:', 'name' => 'assess_start_date', 'data' => date('d/m/Y')])
                @include('shineCompliance.forms.form_timepicker',['title' => 'Assessment Start Time:', 'name' => 'assess_start_time', 'data' => date('H:i')])
                @include('shineCompliance.forms.form_datepicker',['title' => 'Assessment Finished:', 'name' => 'assess_finish_date', 'data' => date('d/m/Y')])
                @include('forms.form_dropdown',['title' => 'Organisation:', 'data' => $clients, 'name' => 'clientKey', 'key'=> 'id', 'value'=>'name', 'compare_value' => \Auth::user()->client_id, 'required' => true ])
                @include('shineCompliance.forms.form_dropdown',['title' => Str::title(str_replace('_',' ',$classification)) . ' Lead:', 'name' => 'lead_by', 'data' => $leads, 'key' => 'id', 'value' => 'full_name', 'required' => true])
                @include('shineCompliance.forms.form_dropdown',['title' => 'Second ' . Str::title(str_replace('_',' ',$classification)) . ' Lead:', 'name' => 'second_lead_by', 'data' => $leads, 'key' => 'id', 'value' => 'full_name'])
                @if($classification == "H&S")
                    @include('shineCompliance.forms.form_select_ajax',['title' => 'H&S Assessor:', 'name' => 'assessor_id' ,'required' => true])
                @else
                    @include('shineCompliance.forms.form_select_ajax',['title' => Str::title(str_replace('_',' ',$classification)) . ' Risk Assessor:', 'name' => 'assessor_id' ,'required' => true])
                @endif
                    {{--                @include('shineCompliance.forms.form_dropdown',['title' => Str::studly($classification) . ' Risk Assessor:', 'name' => 'assessor_id', 'data' => $users, 'key' => 'id', 'value' => 'full_name', 'required' => true])--}}
{{--                @include('shineCompliance.forms.form_dropdown',['title' => 'Quality Checked By:', 'name' => 'quality_checker', 'data' => $leads, 'key' => 'id', 'value' => 'full_name', 'required' => true])--}}
                @include('shineCompliance.forms.form_select_ajax',['title' => 'Quality Checked By:', 'name' => 'quality_checker' ,'required' => true])
                @include('shineCompliance.forms.form_dropdown',['title' => 'Linked Project:', 'name' => 'project_id', 'data' => $projects, 'key' => 'id', 'value' => 'reference'])
{{--                @include('shineCompliance.forms.form_dropdown',['title' => 'Link Work Request:', 'name' => 'work_request_id' ])--}}
                @include('shineCompliance.forms.form_checkbox',['title' => 'Equipment Details:', 'name' => 'setting_equipment_details', 'data'=> 1, 'compare' => 1])
                @if($classification == FIRE || $classification == HS)
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Nonconformities:', 'name' => 'setting_non_conformities', 'data'=> 1, 'compare' => 1])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Property Size/volume Details:', 'name' => 'setting_property_size_volume', 'data'=> 1, 'compare' => 1])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Fire Safety Systems & Equipment:', 'name' => 'setting_fire_safety', 'data'=> 1, 'compare' => 1])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Vehicle Parking:', 'name' => 'setting_show_vehicle_parking', 'data'=> 1, 'compare' => 1])
                @endif
                @include('shineCompliance.forms.form_checkbox',['title' => 'Item Photos Required:', 'name' => 'setting_hazard_photo_required', 'data'=> 1, 'compare' => 1])
                @include('shineCompliance.forms.form_checkbox',['title' => 'Assessors Notes Required:', 'name' => 'setting_assessors_note_required', 'data'=> 1, 'compare' => 1])
                @include('shineCompliance.assessment.download_data', [
                            'systems' => $systems,
                            'areas' => $areas,
                            'exists' => $fire_exists,
                            'assembly_points' => $assembly_points,
                            'vehicle_parking' => $vehicle_parking
                          ])
                <div class="col-md-6 offset-md-3">
                    <button type="button" id="submit-btn" class="btn light_grey_gradient_button fs-8pt">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('click', '#submit-btn', function(e){
                // e.preventDefault();
                var arr_system = getCheckedNode('system-tree','system');
                // var arr_equipment = getCheckedNode('system-tree','equipment');//get from system tree
                var arr_equipment = getCheckedNode('assessment-tree','equipment');//get from area/floor tree are the same
                var arr_hazard = getCheckedNode('assessment-tree','hazard');
                var arr_exist = getCheckedNode('exist-tree','exist');
                var arr_assembly_point = getCheckedNode('assembly-point-tree','assembly_point');
                var arr_vehicle = getCheckedNode('vehicle-parking-tree','vehicle_parking');
                // console.log(arr_system, arr_equipment, arr_hazard);
                if(arr_system) {
                    $('input:hidden[name="list_system"]').val(arr_system);
                    // console.log($('input:hidden[name="list_system[]"]').val());
                }
                if(arr_equipment){
                    $('input:hidden[name="list_equipment"]').val(arr_equipment);
                }
                if(arr_hazard){
                    $('input:hidden[name="list_hazard"]').val(arr_hazard);
                }
                if(arr_exist){
                    $('input:hidden[name="list_exist"]').val(arr_exist);
                }
                if(arr_assembly_point){
                    $('input:hidden[name="list_assembly_point"]').val(arr_assembly_point);
                }
                if(arr_vehicle){
                    $('input:hidden[name="list_vehicle"]').val(arr_vehicle);
                }
                $('#form_edit_assessment').submit();
            });

            $('#type').on('change', function (){
                if ($('#classification').val() == 'fire') {
                    if($(this).val() == 2) {
                        $('#setting_show_vehicle_parking-form').show();
                    } else {
                        $('#setting_show_vehicle_parking-form').hide();
                    }
                }
            });
        });

        $("#clientKey").change(function () {
            var client_id =  $(this).val();
            $("#assessor_id").html("");
            $("#quality_checker").html("");
            $.ajax
            ({
                type: "GET",
                url: "/compliance/ajax/client-users-assessment/" + client_id,
                data: {},
                cache: false,
                success: function (html) {
                    if (html) {
                        $.each( html, function( key, value ) {
                            $('#assessor_id').append($('<option>', {
                                value: value.id,
                                text : value.first_name + ' ' + value.last_name
                            }));
                            $('#quality_checker').append($('<option>', {
                                value: value.id,
                                text : value.first_name + ' ' + value.last_name
                            }));
                        });
                    }
                }
            });
        });
        $("#clientKey").trigger('change');



        //get value checked item/location/area
        //list_area
        function getCheckedNode(tree_id, type) {
            var arr_result = [];
            if (type) {
                var li_list = $('#'+tree_id).find("[data-level='" + type + "']");
                $.each(li_list, function (k, v) {
                    var check = false;
                    var id = $(v).data('element-id');
                    if (id) {
                        //area and location need to find any checked checkbox then count as checked
                        // check = $(v).find("input[type='checkbox']:first").prop('checked');
                        // check = $(v).find('.custom-control custom-checkbox').children("input[type='checkbox']")
                        // console.log($(v).find('.custom-checkbox').children().first().prop('checked'), 444);
                        check = $(v).find('.custom-checkbox').children().first().prop('checked');
                    }
                    if (check) {
                        arr_result.push(id);
                    }
                });
                return arr_result;
            }
        }


    </script>
@endpush

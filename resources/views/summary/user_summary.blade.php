@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    @include('form_summary.form_user_group',['id' => 'user-group'])
    @include('form_summary.form_select_organisation',['data' => $clients, 'id' => 'user-organisation'])
    @include('form_search.search_user',['id' => 'user-search'])
    @include('form_summary.form_select_user_department',['id' => 'user-department','departmentList' => $departmentList, 'departmentContractorList' => $departmentContractorList])
    @include('form_summary.form_select_user_department_child',['id' => 'user-department-child','departmentList' => $child_departments ])
    @include('form_summary.form_select_user_zone',['id' => 'user-zone','all_zones' => $all_zones])
    @include('form_summary.form_user_action')
    @include('form_summary.form_user_time')
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
    $("#user-group").change(function(){
        var type = $("#user-group").val();
        $("#user-search").val('');

        $("#form-user-search").hide();
        $("#form-user-zone").hide();
        $("#form-user-organisation").hide();
        $("#form-user-department").hide();
        $("#form-user-department-child").hide();

        if (type == 'organisation') {
            $("#form-user-zone").show();
            $("#form-user-organisation").show();
        } else if(type == 'user') {
            $("#form-user-search").show();
        }
    });
    $("#user-group").trigger('change');

    $("#user-organisation").change(function(){
        var type = $("#user-organisation").val();
        $("#form-user-department").hide();
        if (type != 'all') {
             $("#form-user-department").show();
        } else {
             $("#form-user-department").hide();
        }
        $('.client-option').hide();
        $('.contractor-option').hide();

        var client_type = $("#user-organisation").find(":selected").attr('data-client-type');
        if (client_type == 0) {
            $('.client-option').show();
        } else {
            $('.contractor-option').show();
        }
    });

    $("#user-department").change(function(){
        var client_type = $("#user-organisation").find(":selected").attr('data-client-type');
        if (client_type == 0 && $(this).val() == 5) {
            //show contractor department
            $("#form-user-department-child").show();
        } else {
            //hide contractor department
            $("#form-user-department-child").hide();
        }
    });
    $("#user-organisation").trigger('change');
});

</script>
@endpush

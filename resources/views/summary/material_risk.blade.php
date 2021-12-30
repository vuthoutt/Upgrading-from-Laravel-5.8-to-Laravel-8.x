@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    <input type="hidden" name="summary_type" value="material">
    @include('form_summary.form_select_type',['id' => 'select-type'])
    @include('form_search.search_property',['id' => 'property-search'])
    @include('form_summary.form_client_zone',['id' => 'client-zone'])
    @include('form_summary.form_property_area',['id' => 'property-area'])
    @include('form_summary.form_property_location',['id' => 'property-location'])
    @include('form_summary.form_item_risk')
    @include('form_summary.form_item_limit')
    @include('form_summary.form_input_summary')
    <div class="form-summary" onclick="return false;">
        <button class="btn light_grey_gradient ml-3" id="btnGenPdf">
            Export to PDF
        </button>
    </div>
</div>
@endsection
@push('javascript')
<script>
$(document).ready(function(){

    $("#select-type").change(function(){
        var type = $("#select-type").val();
        $("#property-search").val('');
        $("select[name='area']").find('option').remove();
        $("#property-location").find('option').remove();

        $("#form-client-zone").hide();
        $("#form-property-search").hide();
        $("#form-property-area").hide();
        $("#form-property-location").hide();
        if (type == 'zone') {
            $("#form-client-zone").show();
        } else if(type == 'property') {
            $("#form-property-search").show();
        }else if(type == 'areafloor') {
            $("#form-property-search").show();
            $("#form-property-area").show();
        } else {
            $("#form-property-search").show();
            $("#form-property-location").show();
        }
    });
    $("#select-type").trigger('change');
});

</script>
@endpush

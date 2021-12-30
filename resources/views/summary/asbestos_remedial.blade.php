@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    <input type="hidden" name="summary_type" value="asbestos_remedial">
    @include('form_search.search_property',['id' => 'property-search'])
    @include('form_summary.form_asbestos_remedial',['id' => 'asbestos-remedial'])
    @include('form_summary.form_asbestos_remedial_action_recommendation',['id' => 'action-recommendation'])
    <div class="row register-form form-summary" id="form-type">
        <label class="font-weight-bold col-md-12" >Where would you like to look?
        </label>
        <div>
            <div class="form-group col-md-12">
                <select  class="form-control input-summary" name="asbestos_remedial_location_type" id="asbestos-remedial-type">
                    <option value='property'>Property</option>
                    <option value='areafloor'>Area/floor</option>
                    <option value='roomlocation'>Room/location</option>
                </select>
            </div>
        </div>
    </div>
    @include('form_summary.form_property_area',['id' => 'property-area'])
    @include('form_summary.form_property_location',['id' => 'property-location'])
    @include('form_summary.form_item_risk',['id' => 'item-risk'])
    @include('form_summary.form_product_debris_type',['id' => 'product-debirs'])
    <div class="row form-summary mt-4">
        <label for="first-name" class="col-md-12 col-form-label text-md-left font-weight-bold">What reason would you like to generate a summary for?</label>
        <div class="col-md-9">
                <textarea name="reason_asbestos" id="reason_asbestos"></textarea>
        </div>
    </div>
    <div class="form-summary">
        <button type="submit" id="btnGenPdf" class="btn light_grey_gradient ml-3 mt-4">
            Export to PDF
        </button>
    </div>
</div>
@endsection

@push('javascript')
<script type="text/javascript">
    $(document).ready(function(){
    CKEDITOR.replace( 'reason_asbestos' );
        $("#asbestos-remedial").change(function(){
            var type = $("#asbestos-remedial").val();
            $("#property-search").val('');
            $("select[name='area']").find('option').remove();
            $("#property-location").find('option').remove();

            $("#form-type").hide();
            $("#form-item-risk").hide();
            $("#form-property-area").hide();
            $("#form-property-location").hide();
            $("#form-product-debirs").hide();
            $(".action-recommendation").hide();
            $("#action-recommendation-list").hide();
            if (type == 'location') {
                $("#form-type").show();
            } else if(type == 'riskType') {
                $("#form-item-risk").show();
            }else if(type == 'actionRecommendation') {
                $(".action-recommendation").show();
            } else if(type == 'productDebris'){
                $("#form-product-debirs").show();
            }
        });
        $("#asbestos-remedial").trigger('change');

        $("#asbestos-remedial-type").change(function(){
            var type = $("#asbestos-remedial-type").val();
            $("#form-property-area").hide();
            $("#form-property-location").hide();
            if (type == 'areafloor') {
                $("#form-property-area").show();
            } else if(type == 'roomlocation') {
                $("#form-property-location").show();
            }
        });
    });
</script>
@endpush

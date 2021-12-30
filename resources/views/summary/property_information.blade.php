@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    <div class="row register-form form-summary">
        <label class="font-weight-bold col-md-12" >What would you like to export?
        </label>
        <div>
            <div class="form-group col-md-12">
                <select  class="form-control input-summary" id="export_type" name="export_type">
                    <option value='all' selected>All</option>
                    <option value='service_area'>Service Area</option>
                    <option value='property_type'>Property Type</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row register-form form-summary dropdown_summary_prop_info">
        <label class="font-weight-bold col-md-12" >Which Property Type would you like to export?
        </label>
        <div>
            <div class="form-group col-md-12">
                <select  class="form-control input-summary" id="property_type" name="property_type">
                    <option value='1'>Corporate Properties</option>
                    <option value='2'>Commercial Properties</option>
                    <option value='3'>Housing – Communal</option>
                    <option value='4'>Housing – Domestic</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row register-form form-summary dropdown_summary_prop_info">
        <label class="font-weight-bold col-md-12" >Which Service Area would you like to export?
        </label>
        <div>
            <div class="form-group col-md-12">
                <select  class="form-control input-summary" id="service_area_type" name="service_area_type">
                    <option value='all'>All</option>
                    <option value='1'>Central Area</option>
                    <option value='2'>North Area</option>
                    <option value='3'>South Area</option>
                    <option value='4'>West Area</option>
                </select>
            </div>
        </div>
    </div>
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
            $('.dropdown_summary_prop_info').hide();
            $('body').on('change', '#export_type', function(k,v){
                var val = $(this).val();
                $('.dropdown_summary_prop_info').hide();
                //Service Area and Property Type have the same dropdown service_area_type
                //Service Area will hide All option for service_area_type dropdown
                $('#service_area_type  option[value="all"]').show();
                if(val == 'all'){
                    //hide all other dropdowns
                    $('.dropdown_summary_prop_info').hide();
                } else if(val == 'service_area'){
                    $('#service_area_type  option[value="all"]').hide();
                    $('#service_area_type').closest('.row').show();
                    $('#service_area_type  option[value!="all"]:first').prop('selected', true);
                } else if(val == 'property_type'){
                    $('#property_type').closest('.row').show();
                    $('#service_area_type').closest('.row').show();
                }
            });

        });

    </script>
@endpush

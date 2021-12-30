<div class="offset-top40">
    <div id="material-limited">
        <p>This has a Material Risk Assessment Score of 12 (Presumed High Risk)</p>
    </div>
    <div id="materialaltdiv">
        @include('forms.form_dropdown',['title' => 'Product Type (a)', 'data' => $assessmentTypeKeys, 'name' => 'assessmentTypeKey', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])
        @include('forms.form_dropdown',['title' => 'Extent of Damage (b)', 'data' => $assessmentDamageKeys, 'name' => 'assessmentDamageKey', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])
        @include('forms.form_dropdown',['title' => 'Surface Treatment (c)', 'data' => $assessmentTreatmentKeys, 'name' => 'assessmentTreatmentKey', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])
        @include('forms.form_dropdown',['title' => 'Asbestos Fibre (d)', 'data' => $assessmentAsbestosKeys, 'name' => 'assessmentAsbestosKey', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])

        <div class="row register-form">
            <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Total:</label>
            <div class="col-md-5">
                <span id="total-MAS">
                </span>
                <input type="hidden" class="form-control" name="total-MAS" id="total-mas-form"  value="">
            </div>
        </div>
        <div class="row register-form">
            <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Material Risk Assessment</label>
            <div class="col-md-5">
                <span id="risk-color" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
                &nbsp;
                <span id="risk-text"></span>
            </div>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
    $(document).ready(function(){
        countTotal()
        function countTotal() {
            $('#risk-color').removeAttr('class');
            var total = 0;
            var product_type = parseInt($("#assessmentTypeKey").find(":selected").data('option'));
            var extent_dame = parseInt($("#assessmentDamageKey").find(":selected").data('option'));
            var surface_treatment = parseInt($("#assessmentTreatmentKey").find(":selected").data('option'));
            var asbestos_fibre = parseInt($("#assessmentAsbestosKey").find(":selected").data('option'));
            if (!isNaN(product_type) && !isNaN(product_type) && !isNaN(product_type) && !isNaN(product_type)) {
                total = product_type + extent_dame + surface_treatment + asbestos_fibre;
            }

            var color = '';
            var risk = '';
            switch (true) {
                case (total == 0):
                    color = "green";
                    risk = "No Risk";
                    break;
                case (total < 5):
                    color = "yellow";
                    risk = "Very Low";
                    break;
                case (total < 7):
                    color = "orange";
                    risk = "Low";
                    break;
                case (total < 10):
                    color = "brown";
                    risk = "Medium";
                    break;
                case (total < 13):
                    color = "red";
                    risk = "High";
                    break;
            }

            $('#total-MAS').html(total);
            $('#total-mas-form').val(total);
            $('#risk-text').html(risk);
            $('#risk-color').addClass("badge " + color);
        }

        $("#assessmentTypeKey").change(function(){
            countTotal();
        });
        $("#assessmentDamageKey").change(function(){
            countTotal();
        });
        $("#assessmentTreatmentKey").change(function(){
            countTotal();
        });
        $("#assessmentAsbestosKey").change(function(){
            countTotal();
        });
    });
</script>
@endpush
<div class="offset-top40">
    <div class="row register-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Total:</label>
        <div class="col-md-5">
            <span id="overall-total-MAS">
                {{ sprintf("%02d",$item->total_mas_risk) }}
            </span>
        </div>
    </div>
    <div class="row register-form mb-4">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Material Risk Assessment:</label>
        <div class="col-md-5">
            <span id="overall-risk-color-mas" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
            &nbsp;
            <span id="overall-risk-text-mas"></span>
        </div>
    </div>
    <div class="row register-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Total:</label>
        <div class="col-md-5">
            <span id="overall-total-PAS">
                {{ sprintf("%02d",$item->total_pas_risk) }}
            </span>
        </div>
    </div>
    <div class="row register-form mb-4">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Priority Risk Assessment:</label>
        <div class="col-md-5">
            <span id="overall-risk-color-pas" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
            &nbsp;
            <span id="overall-risk-text-pas"></span>
        </div>
    </div>
    <div class="row register-form ">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Overall Total:</label>
        <div class="col-md-5">
            <span id="overall-total-risk">
                {{ sprintf("%02d",$item->total_risk) }}
            </span>
        </div>
    </div>
    <div class="row register-form mb-4">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Overall Risk Assessment:</label>
        <div class="col-md-5">
            <span id="risk-color-total" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
            &nbsp;
            <span id="risk-text-total"></span>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
    $(document).ready(function(){
        getColor('overall-total-PAS','overall-risk-text-pas','overall-risk-color-pas');
        getColor('overall-total-MAS','overall-risk-text-mas','overall-risk-color-mas');
        getColor('overall-total-risk','risk-text-total','risk-color-total');


        function getColor(total_id,risk_id,color_id) {
            var total = $('#'+total_id).html();
            if (total_id == 'overall-total-risk') {
                switch (true) {
                    case (total == 0):
                        color = "green";
                        risk = "No Risk";
                        break;
                    case (total < 10):
                        color = "yellow";
                        risk = "Very Low";
                        break;
                    case (total < 14):
                        color = "orange";
                        risk = "Low";
                        break;
                    case (total < 20):
                        color = "brown";
                        risk = "Medium";
                        break;
                    case (total < 25):
                        color = "red";
                        risk = "High";
                        break;
                }
            } else {
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
            }


        $('#'+risk_id).html(risk);
        $('#'+color_id).addClass("badge " + color);

        }
    });

</script>
@endpush
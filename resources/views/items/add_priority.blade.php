<div class="offset-top40">
    <div id="materialaltdiv" style="display:none;">
        <p>Priority Risk Assessment Not Needed</p>
    </div>
    @include('forms.form_text_right',['title' => '  Normal Occupancy Activity'])
    @include('forms.form_dropdown',['title' => 'Primary', 'data' => $pasPrimaries, 'name' => 'pasPrimary', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])
    @include('forms.form_dropdown',['title' => 'Secondary', 'data' => $pasSecondaryies, 'name' => 'pasSecondary', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])

    @include('forms.form_text_right',['title' => 'Likelihood of Disturbance'])
    @include('forms.form_dropdown',['title' => 'Location', 'data' => $pasLocations, 'name' => 'pasLocation', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])
    @include('forms.form_dropdown',['title' => 'Accessibility', 'data' => $pasAccessibilities, 'name' => 'pasAccessibility', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])
    @include('forms.form_dropdown',['title' => 'Extent/Amount', 'data' => $pasExtents, 'name' => 'pasExtent', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])

    @include('forms.form_text_right',['title' => '  Human Exposure Potential'])
    @include('forms.form_dropdown',['title' => 'Number', 'data' => $pasNumbers, 'name' => 'pasNumber', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])
    @include('forms.form_dropdown',['title' => 'Frequency', 'data' => $pasHumanFrequencys, 'name' => 'pasHumanFrequency', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])
    @include('forms.form_dropdown',['title' => 'Average Time', 'data' => $pasAverageTimes, 'name' => 'pasAverageTime', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])

    @include('forms.form_text_right',['title' => '  Maintenance Activity'])
    @include('forms.form_dropdown',['title' => 'Type', 'data' => $pasTypes, 'name' => 'pasType', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])
    @include('forms.form_dropdown',['title' => 'Frequency', 'data' => $pasMaintenanceFrequencys, 'name' => 'pasMaintenanceFrequency', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score' ])

    <div class="row register-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Total:</label>
        <div class="col-md-5">
            <span id="total-PAS">
            </span>
        </div>
    </div>
    <div class="row register-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Priority Risk Assessment</label>
        <div class="col-md-5">
            <span id="risk-color-pas" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
            <input type="hidden" class="form-control" name="total-PAS" id="total-pas-form"  value="">
            &nbsp;
            <span id="risk-text-pas"></span>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
    $(document).ready(function(){
        countTotalPas()
        function countTotalPas() {
            $('#risk-color').removeAttr('class');
            var total = 0;
            var pas_primary = parseInt($("#pasPrimary").find(":selected").data('option'));
            var pas_secondary = parseInt($("#pasSecondary").find(":selected").data('option'));
            if (!isNaN(pas_primary) && !isNaN(pas_secondary)) {
                var normal_occupancy = Math.round( (pas_primary + pas_secondary)/ 2 );
                total += normal_occupancy;
            }

            var pas_location = parseInt($("#pasLocation").find(":selected").data('option'));
            var pas_access = parseInt($("#pasAccessibility").find(":selected").data('option'));
            var pas_extent = parseInt($("#pasExtent").find(":selected").data('option'));
            if (!isNaN(pas_location) && !isNaN(pas_access) && !isNaN(pas_extent)) {
                var likelihood = Math.round( (pas_location + pas_access + pas_extent)/ 3 );
                total += likelihood;
            }

            var pas_number = parseInt($("#pasNumber").find(":selected").data('option'));
            var pas_human_frequency = parseInt($("#pasHumanFrequency").find(":selected").data('option'));
            var pas_average_time = parseInt($("#pasAverageTime").find(":selected").data('option'));
            if (!isNaN(pas_number) && !isNaN(pas_human_frequency) && !isNaN(pas_average_time)) {
                var human_exposure = Math.round( (pas_number + pas_human_frequency + pas_average_time)/ 3 );
                total += human_exposure;
            }

            var pas_type = parseInt($("#pasType").find(":selected").data('option'));
            var pas_maintain_frequency = parseInt($("#pasMaintenanceFrequency").find(":selected").data('option'));
            if (!isNaN(pas_type) && !isNaN(pas_maintain_frequency)) {
                var maintenance_activity = Math.round( (pas_type + pas_maintain_frequency)/ 2 );
                total += maintenance_activity;
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

            $('#total-PAS').html(total);
            $('#total-pas-form').val(total);
            $('#risk-text-pas').html(risk);
            $('#risk-color-pas').addClass("badge " + color);
        }

        $("#pasPrimary").change(function(){
            countTotalPas();
        });
        $("#pasSecondary").change(function(){
            countTotalPas();
        });
        $("#pasLocation").change(function(){
            countTotalPas();
        });
        $("#pasAccessibility").change(function(){
            countTotalPas();
        });
        $("#pasExtent").change(function(){
            countTotalPas();
        });
        $("#pasNumber").change(function(){
            countTotalPas();
        });
        $("#pasHumanFrequency").change(function(){
            countTotalPas();
        });
        $("#pasAverageTime").change(function(){
            countTotalPas();
        });
        $("#pasType").change(function(){
            countTotalPas();
        });
        $("#pasMaintenanceFrequency").change(function(){
            countTotalPas();
        });
    });

</script>
@endpush
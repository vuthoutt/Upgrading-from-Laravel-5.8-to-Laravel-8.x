<div class="offset-top40">
    <div id="materialaltdiv" style="display:none;">
        <p>Priority Risk Assessment Not Needed</p>
    </div>
    @include('forms.form_text_right',['title' => '  Normal Occupancy Activity'])
    @include('forms.form_text',['title' => 'Primary:', 'data' => $pasPrimary ])
    @include('forms.form_text',['title' => 'Secondary:', 'data' => $pasSecondary ])

    @include('forms.form_text_right',['title' => 'Likelihood of Disturbance'])
     @include('forms.form_text',['title' => 'Location:', 'data' => $pasLocation ])
     @include('forms.form_text',['title' => 'Accessibility:', 'data' => $pasAccessibility ])
     @include('forms.form_text',['title' => 'Extent/Amount:', 'data' => $pasExtent ])

    @include('forms.form_text_right',['title' => '  Human Exposure Potential'])
     @include('forms.form_text',['title' => 'Number:', 'data' => $pasNumber ])
     @include('forms.form_text',['title' => 'Frequency:', 'data' => $pasHumanFrequency ])
     @include('forms.form_text',['title' => 'Average Time:', 'data' => $pasAverageTime ])

    @include('forms.form_text_right',['title' => '  Maintenance Activity'])
    @include('forms.form_text',['title' => 'Type:', 'data' => $pasType ])
    @include('forms.form_text',['title' => 'Frequency:', 'data' => $pasMaintenanceFrequency ])

    <div class="row register-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Total:</label>
        <div class="col-md-5">
            <span id="total-PAS">
                {{ $item->total_pas_risk }}
            </span>
        </div>
    </div>
    <div class="row register-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Priority Risk Assessment:</label>
        <div class="col-md-5">
            <span id="risk-color-pas" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
            &nbsp;
            <span id="risk-text-pas"></span>
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
    $(document).ready(function(){
        var total = $('#total-PAS').html();
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
        $('#risk-text-pas').html(risk);
        $('#risk-color-pas').addClass("badge " + color);
    });

</script>
@endpush

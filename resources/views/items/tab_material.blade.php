<div class="offset-top40">
    @if($item->state == ITEM_INACCESSIBLE_STATE and optional($item->itemInfo)->assessment == ITEM_LIMIT_ASSESSMENT)
    <div id="material-limited">
        <p>This has a Material Risk Assessment Score of 12 (Presumed High Risk)</p>
    </div>
    @else
         @include('forms.form_text',['title' => 'Product Type (a):', 'data' => $assessmentTypeKey ])
         @include('forms.form_text',['title' => 'Extent of Damage (b):', 'data' => $assessmentDamageKey ])
         @include('forms.form_text',['title' => 'Surface Treatment (c):', 'data' => $assessmentTreatmentKey ])
         @include('forms.form_text',['title' => 'Asbestos Fibre (d):', 'data' => $assessmentAsbestosKey ])

        <div class="row register-form">
            <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Total:</label>
            <div class="col-md-5">
                <span id="total-MAS">
                    {{ $item->total_mas_risk }}
                </span>
            </div>
        </div>
        <div class="row register-form">
            <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Material Risk Assessment:</label>
            <div class="col-md-5">
                <span id="risk-color" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
                &nbsp;
                <span id="risk-text"></span>
            </div>
        </div>
    @endif
</div>
@push('javascript')

<script type="text/javascript">
    $(document).ready(function(){
        var total = $('#total-MAS').html();
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
        $('#risk-text').html(risk);
        $('#risk-color').addClass("badge " + color);
    });
</script>
@endpush
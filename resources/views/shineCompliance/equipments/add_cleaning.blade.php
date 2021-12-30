<div class="offset-top40">
    @include('shineCompliance.forms.form_dropdown',['title' => 'Operational Exposure:', 'data' => $operation_exposure, 'name' => 'operational_exposure', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Evidence of Stagnation:', 'data' => $evidence_stages, 'name' => 'envidence_stagnation', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Degree of Fouling:', 'data' => $degree_of_fouling, 'name' => 'degree_fouling', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Degree of Biological Slime:', 'data' => $degree_of_bios, 'name' => 'degree_biological', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Extent of Corrosion:', 'data' => $extent_corrosion, 'name' => 'extent_corrosion', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Cleanliness:', 'data' => $cleanliness, 'name' => 'cleanliness', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Ease of Cleaning:', 'data' => $ease_cleaning, 'name' => 'ease_cleaning', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    <div class="row register-form equipment_section" id="comments-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Comments:</label>
        <div class="col-md-5" style="height: 150%">
            <textarea class="text-area-form" name="comments" id="comment" style="height: 150%"></textarea>
        </div>
    </div>
</div>
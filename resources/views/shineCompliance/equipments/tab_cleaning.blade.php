<div class="offset-top40">

    @include('shineCompliance.forms.form_text',['title' => 'Operational Exposure:', 'data' => $equipment->cleaning->operationalExposure->description ?? ''
                                                ,'class_other' => 'equipment_section','id' => 'operational_exposure'])
    @include('shineCompliance.forms.form_text',['title' => 'Evidence of Stagnation:', 'data' => $equipment->cleaning->envidenceStagnation->description ?? ''
                                                ,'class_other' => 'equipment_section','id' => 'envidence_stagnation'])
    @include('shineCompliance.forms.form_text',['title' => 'Degree of Fouling:', 'data' => $equipment->cleaning->degreeFouling->description ?? ''
                                                ,'class_other' => 'equipment_section','id' => 'degree_fouling'])
    @include('shineCompliance.forms.form_text',['title' => 'Degree of Biological Slime:', 'data' => $equipment->cleaning->degreeBiological->description ?? ''
                                                ,'class_other' => 'equipment_section','id' => 'degree_biological'])
    @include('shineCompliance.forms.form_text',['title' => 'Extent of Corrosion:', 'data' => $equipment->cleaning->extentCorrosion->description ?? ''
                                                ,'class_other' => 'equipment_section','id' => 'extent_corrosion'])
    @include('shineCompliance.forms.form_text',['title' => 'Cleanliness:', 'data' => $equipment->cleaning->cleanlinessRelation->description ?? ''
                                                ,'class_other' => 'equipment_section','id' => 'cleanliness'])
    @include('shineCompliance.forms.form_text',['title' => 'Ease of Cleaning:', 'data' => $equipment->cleaning->easeCleaning->description ?? ''
                                                ,'class_other' => 'equipment_section','id' => 'ease_cleaning'])
    @include('shineCompliance.forms.form_text',['title' => 'Comments:', 'data' => $equipment->cleaning->comments ?? ''
                                                ,'class_other' => 'equipment_section','id' => 'comments'])
</div>
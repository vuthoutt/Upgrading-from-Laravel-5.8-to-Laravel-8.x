    @if($data->classification == ASSESSMENT_HS_TYPE)
        <div class="row">
            <div class="col-12 mb-1">
                @include('shineCompliance.tables.assessment_hazards', [
                    'title' => 'Assessment Health & Safety Hazard Summary',
                    'tableId' => 'assessment_hazard',
                    'over_all_text' => strtoupper($data->over_all_text),
                    'collapsed' => false,
                    'plus_link' => ($assessment->is_locked == 0) and $canBeUpdateSurvey ? true : false,
                    'link' => route('shineCompliance.assessment.get_add_hazard',['property_id' => $data->property_id,'assess_id' => $data->id, 'assess_type' => $data->classification]),
                    'data' => $data->unDecommissionHazard,
                    'order_table' => "[]"
                    ])
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-1">
                @include('shineCompliance.tables.assessment_decommissioned_hazards', [
                    'title' => 'Assessment Decommissioned Health & Safety Hazard Summary',
                    'tableId' => 'assessment_decommissioned_hazard',
                    'over_all_text' => strtoupper($data->over_all_text),
                    'collapsed' => true,
                    'plus_link' => false,
                    'data' => $data->decommissionHazard,
                    'order_table' => "[]"
                    ])
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12 mb-1">
                @include('shineCompliance.tables.assessment_hazards', [
                    'title' => 'Assessment ' .Str::title(str_replace('_',' ',$data->assess_classification)).' Hazard Summary',
                    'tableId' => 'assessment_hazard',
                    'over_all_text' => strtoupper($data->over_all_text),
                    'collapsed' => false,
                    'plus_link' => ($assessment->is_locked == 0) and $canBeUpdateSurvey ? true : false,
                    'link' => route('shineCompliance.assessment.get_add_hazard',['property_id' => $data->property_id,'assess_id' => $data->id, 'assess_type' => $data->classification]),
                    'data' => $data->unDecommissionHazard,
                    'order_table' => "[]"
                    ])
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-1">
                @include('shineCompliance.tables.assessment_decommissioned_hazards', [
                    'title' => 'Assessment Decommissioned ' .Str::title(str_replace('_',' ',$data->assess_classification)).' Hazard Summary',
                    'tableId' => 'assessment_decommissioned_hazard',
                    'over_all_text' => strtoupper($data->over_all_text),
                    'collapsed' => true,
                    'plus_link' => false,
                    'data' => $data->decommissionHazard,
                    'order_table' => "[]"
                    ])
            </div>
        </div>
    @endif


@include('shineCompliance.modals.confirm_hazard',[ 'modal_id' => 'approval-survey', 'header' => 'Hazard Confirmation','type' => 'approval' ])
@include('shineCompliance.modals.dismiss_hazard',[ 'modal_id' => 'rejected-survey',
                                                    'header' => 'Hazard Decommission','type' => 'reject',
                                                    'decommission_type' => \ComplianceHelpers::getAssessmentDecomissionReasonType($data->classification),
                                                    'name' => 'decomission_reason',
                                                ])

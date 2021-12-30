{{--@if(isset(optional($assessment->assessmentInfo)->executive_summary) && !empty(optional($assessment->assessmentInfo)->executive_summary) )--}}
    <div class="card mt-4 mb-3 ml-0" id="executiveSummary">
        <div class="card-header table-header">
            <h6 class="table-title">Executive Summary</h6>
            <div class="btn collapse-table table-collapse-button " data-toggle="collapse" data-target="#collapse-executive-summary" aria-expanded="false" aria-controls="collapse-{{ isset($tableId) ? $tableId : '' }}">
                <i class="fa fa-lg " aria-hidden="true"></i>
            </div>
        </div>
        <div id="collapse-executive-summary" class="collapse" data-parent="#executiveSummary">
            <div class="card-body">
                {!! (isset(optional($assessment->assessmentInfo)->executive_summary) and !is_null(optional($assessment->assessmentInfo)->executive_summary)) ? optional($assessment->assessmentInfo)->executive_summary : 'No Information.' !!}
            </div>
        </div>
    </div>
{{--@endif--}}
@if(isset(optional($assessment->assessmentInfo)->executive_summary) && !empty(optional($assessment->assessmentInfo)->executive_summary) )
<div class="card ml-0" id="accordionExample">
@else
<div class="card ml-0 mt-4" id="accordionExample">
@endif
    <div class="card-header table-header">
        <h6 class="table-title">Objective/scope</h6>
        <div class="btn collapse-table table-collapse-button " data-toggle="collapse" data-target="#collapse-objective-scope" aria-expanded="false" aria-controls="collapse-{{ isset($tableId) ? $tableId : '' }}">
            <i class="fa fa-lg " aria-hidden="true"></i>
        </div>
    </div>
    <div id="collapse-objective-scope" class="collapse show" data-parent="#accordionExample">
        <div class="card-body">
            {!! (isset(optional($assessment->assessmentInfo)->objective_scope) and !is_null(optional($assessment->assessmentInfo)->objective_scope)) ? optional($assessment->assessmentInfo)->objective_scope : 'No Information.' !!}
        </div>
    </div>
</div>

@if(in_array($assessment->type,ASSESS_TYPE_FIRE_RISK_ALL_TYPE) and ($assessment->classification == ASSESSMENT_FIRE_TYPE))
<div>
    {{-- <div class="row"> --}}
        @include('shineCompliance.tables.assessment_management_info', [
            'title' => 'Management Information',
            'tableId' => 'assessment-management-info_table',
            'collapsed' => false,
            'plus_link' => false,
            'order_table' => 'published',
            'data' => $managementInfoQueries,
            'count' => count($managementInfoQueries)
            ])
    {{-- </div> --}}

    {{-- <div class="row"> --}}
        @include('shineCompliance.tables.assessment_other_info', [
            'title' => 'Other Information',
            'tableId' => 'assessment-other-info_table',
            'collapsed' => false,
            'plus_link' => false,
            'order_table' => 'published',
            'data' => $otherInfoQueries,
            'count' => count($otherInfoQueries)
            ])
    {{-- </div> --}}
</div>
@endif
<div class="offset-top20 ml-0">
    @if($data->is_locked == SURVEY_UNLOCKED)
        @if($data->status == ASSESSMENT_STATUS_COMPLETED)
            <div class="spanWarningSurveying" style="width: 400px !important;">
                <strong>
                    <em>Survey is view only because technical activity is complete</em>
                </strong>
            </div>
        @else
            @if($canBeUpdateSurvey)
            <a href="{{ route('shineCompliance.assessment.get_edit_objective_scope', ['assess_id' => $data->id]) }}" class="btn light_grey_gradient_button fs-8pt"><strong>Edit</strong></a>
            @endif
        @endif
    @endif
</div>

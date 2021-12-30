<div class="row">
    @php
        $count = 0;
    @endphp
    @foreach($sections as $parent_sections)
        <div class="col-md-12">
            <div class="row">
                <strong class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt text-dark mt-3" > {{$parent_sections->description ?? ''}}</strong>
            </div>
        </div>
        @foreach($parent_sections->children as $section)
            <div class="col-12">
                @if ($section->id == OTHER_HAZARD_IDENTIFIED_SECTION_ID && count($other_hazard_answers))
                    @include('shineCompliance.tables.assessment_other_hazard_question', [
                        'title' => $section->description,
                        'tableId' => 'method-question'.$section->id,
                        'collapsed' => ($count == 0) ? false : true ,
                        'plus_link' => false,
                        'order_table' => 'published',
                        'data' => $other_hazard_answers,
                        'count' => count($other_hazard_answers)
                    ])
                @else
                    @include('shineCompliance.tables.assessment_question', [
                    'title' => $section->description,
                    'tableId' => 'method-question'.$section->id,
                    'collapsed' => ($count == 0) ? false : true ,
                    'plus_link' => false,
                    'order_table' => 'published',
                    'data' => $section->questions,
                    'count' => count($section->questions)
                    ])
                @endif
            </div>
        @endforeach
        @php
            $count++;
        @endphp
    @endforeach
    {{--    @if($data->status == AUDIT_COMPLETED_STATUS)--}}
    {{--        <div class="mt-4 ml-3">--}}
    {{--            <strong><em><span class="spanWarningSurveying">Audit is view only because technical activity is complete</span></em></strong>--}}
    {{--        </div>--}}
    {{--    @elseif($data->status == AUDIT_PULISHED_STATUS)--}}
    {{--        <div  class="mt-4 ml-3">--}}
    {{--            <strong><em><span class="spanWarningSuccess">Audit is view only because technical activity is in progress</span></em></strong>--}}
    {{--        </div>--}}
    {{--    @else--}}
    @if($assessment->is_locked != 1)
        @if($canBeUpdateSurvey)
            <a  class="btn light_grey_gradient_button mt-5 ml-3 fs-8pt" href="{{ route('shineCompliance.assessment.get_edit_questionnaire',['assess_id' => $data->id]) }}"><strong>Edit</strong></a>
        @endif
    @endif
    {{--    @endif--}}
</div>

@extends('shineCompliance.layouts.app')
@section('content')
    @if($assessment->classification == ASSESSMENT_FIRE_TYPE || $assessment->classification == ASSESSMENT_HS_TYPE)
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_executive_summary_edit', 'color' => 'orange', 'data'=> $assessment])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_objective_scope_edit', 'color' => 'orange', 'data'=> $assessment])
    @endif

    <div class="container-cus prism-content pad-up">

        <div class="main-content">
        </div>
        <form method="POST" action="{{ route('shineCompliance.assessment.post_edit_objective_scope', ['assess_id' => $assessment->id]) }}" class="form-shine">
            @csrf

            @if($assessment->classification == ASSESSMENT_FIRE_TYPE || $assessment->classification == ASSESSMENT_HS_TYPE)
                <div class="row">
                    <h4 class="title-row">Executive Summary</h4>
                </div>
                @include('shineCompliance.forms.form_ckEditor',['name' => 'executive_summary', 'data' => optional($assessment->assessmentInfo)->executive_summary ])
            @endif
            <div class="row">
                <h4 class="title-row">Objective/Scope </h4>
            </div>
            @include('shineCompliance.forms.form_ckEditor',['name' => 'objective_scope', 'data' => optional($assessment->assessmentInfo)->objective_scope ])

            @if($assessment->classification == ASSESSMENT_FIRE_TYPE)
            <div class="row">
                <h4 class="title-row">Management Information</h4>
            </div>
            @foreach($managementInfoQueries as $key => $question)
                <div class="row mt-3">
                    <h6  class="col-md-5 col-form-label text-md-left" >
                        {{ $question->description }}
                    </h6>

                    <input type="hidden" class="form-control" name="management_question[]" value="{{ $question->id }}">
                    <div class="col-md-6">
                        <div class="form-group ">
                            @if($question->answer_type == 1)
                                @php($other = 0)
                                <select class="form-control choose-answer" name="management_answer[{{ $question->id }}]">
                                    <option value="0" data-option="0"></option>
                                    @foreach($question->answers as $answer)
                                        @if($answer->other == 1 && optional($question->answerValue()->where('assess_id', $assessment->id)->first())->answer_id == $answer->id)
                                            @php($other = 1)
                                        @endif
                                        <option value="{{ $answer->id }}"
                                            {{ $answer->id  == ($question->answerValue()->where('assess_id', $assessment->id)->first()->answer_id ?? NULL) ? 'selected' : ''}}
                                        >{{ $answer->description }}</option>
                                    @endforeach
                                </select>
                                <div style="margin-top: 15px; height: 150%; {{ $other ? 'display:block;' : 'display:none;' }}">
                                    <textarea class="text-area-form"
                                              name="management_answer_other[{{ $question->id }}]"
                                              style="height: 150%;" maxlength="1000">{{ $question->answerValue()->where('assess_id', $assessment->id)->first() !== null
                                                ? $question->answerValue()->where('assess_id', $assessment->id)->first()->answer_other
                                                : '' }}</textarea>
                                </div>
                            @elseif($question->answer_type == 2)
                                <textarea class="text-area-form"
                                          name="management_answer[{{ $question->id }}]"
                                          style="height: 150%" maxlength="1000">{{ isset($question->answerValue()->where('assess_id', $assessment->id)->first()->answer_other)
                                            ? $question->answerValue()->where('assess_id', $assessment->id)->first()->answer_other
                                            : $question->pre_loaded }}</textarea>
                            @elseif($question->answer_type == 3)
                                @php($other = 0)
                                <select class="form-control multi-answer" name="management_answer[{{ $question->id }}][]" multiple>
                                    <option value="0" data-option="0"></option>
                                    @foreach($question->answers as $answer)
                                        @if($answer->other == 1 && strpos(optional($question->answerValue()->where('assess_id', $assessment->id)->first())->answer_id, $answer->id . '') !== false)
                                            @php($other = 1)
                                        @endif
                                        <option value="{{ $answer->id }}"
                                            {{ strpos(optional($question->answerValue()->where('assess_id', $assessment->id)->first())->answer_id, $answer->id . '') !== false ? 'selected' : ''}}
                                        >{{ $answer->description }}</option>
                                    @endforeach
                                </select>
                                <div class="other_answer_multi" style="margin-top: 15px; height: 150%; {{ $other ? 'display:block;' : 'display:none;' }}">
                                    <textarea class="text-area-form"
                                              name="management_answer_other[{{ $question->id }}]"
                                              style="height: 150%;" maxlength="1000">{{ $question->answerValue()->where('assess_id', $assessment->id)->first() !== null
                                                ? $question->answerValue()->where('assess_id', $assessment->id)->first()->answer_other
                                                : '' }}</textarea>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="row">
                <h4 class="title-row">Other Information</h4>
            </div>
                @foreach($otherInfoQueries as $key => $question)
                    <div class="row mt-3">
                        <h6  class="col-md-5 col-form-label text-md-left" >
                            {{ $question->description }}
                        </h6>

                        <input type="hidden" class="form-control" name="other_question[]" value="{{ $question->id }}">
                        <div class="col-md-6">
                            <div class="form-group ">
                                @if($question->answer_type == 1)
                                    @php($other = 0)
                                    <select class="form-control choose-answer" name="other_answer[{{ $question->id }}]">
                                        <option value="0" data-option="0"></option>
                                        @foreach($question->answers as $answer)
                                            @if($answer->other == 1 && optional($question->answerValue()->where('assess_id', $assessment->id)->first())->answer_id == $answer->id)
                                                @php($other = 1)
                                            @endif
                                            <option value="{{ $answer->id }}"
                                                {{ $answer->id  == ($question->answerValue()->where('assess_id', $assessment->id)->first()->answer_id ?? NULL) ? 'selected' : ''}}
                                            >{{ $answer->description }}</option>
                                        @endforeach
                                    </select>
                                    <div style="margin-top: 15px; height: 150%; {{ $other ? 'display:block;' : 'display:none;' }}">
                                    <textarea class="text-area-form"
                                              name="other_answer_other[{{ $question->id }}]"
                                              style="height: 150%;" maxlength="1000">{{ $question->answerValue()->where('assess_id', $assessment->id)->first() !== null
                                                ? $question->answerValue()->where('assess_id', $assessment->id)->first()->answer_other
                                                : '' }}</textarea>
                                    </div>
                                @else
                                    <textarea class="text-area-form"
                                              name="other_answer[{{ $question->id }}]"
                                              style="height: 150%" maxlength="1000">{{ $question->answerValue()->where('assess_id', $assessment->id)->first() !== null
                                            ? $question->answerValue()->where('assess_id', $assessment->id)->first()->answer_other
                                            : '' }}</textarea>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="btn survey-information-submit" >
                <button type="submit" class="btn light_grey_gradient">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    </div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            $('.choose-answer').change(function () {
                if ($(this).find(":selected").text() == 'Other') {
                    $(this).siblings('div').show();
                } else {
                    $(this).siblings('div').hide();
                    $(this).siblings('div').find('textarea').val('');
                }
            });

            $('.multi-answer').select2({
                minimumResultsForSearch: -1,
                placeholder: "Please select an option"
            });
            // override the select2 open event
            $('.multi-answer').on('select2:open', function () {
                // get values of selected option
                var values = $(this).val();
                // get the pop up selection
                var pop_up_selection = $('.select2-results__options');

                if (values != null ) {
                    // hide the selected values
                    pop_up_selection.find("li[aria-selected=true]").hide();

                } else {
                    // show all the selection values
                    pop_up_selection.find("li[aria-selected=true]").show();
                }
            });


            $('.multi-answer').change(function(e){
                // if value = other : show other input
                var value = $(this).find(":selected").text();
                if (value.includes("Other")) {
                    $(this).siblings(".other_answer_multi").show();
                } else {
                    $(this).siblings(".other_answer_multi").hide();
                    $(this).siblings(".other_answer_multi").find('textarea').text(null);
                }
            });
        });
    </script>
@endpush

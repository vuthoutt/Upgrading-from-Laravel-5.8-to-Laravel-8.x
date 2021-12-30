@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_questionnaire_edit', 'color' => 'orange', 'data'=> $assessment])
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">Edit Assessment Questionnaire</h3>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-3">
                    <div class="nav list-group list-group-flush" id="myList" role="tablist">
                        @foreach($sections as $key => $parent_sections)
                            <div class="list-group-item-action bg-white" style="padding-top: 10px">
                                <h6 class="pt-2 pt-2">{{ $parent_sections->description ?? '' }}</h6>
                            </div>
                            @foreach($parent_sections->children as $section)
                                <a href="#content_{{ $section->id ?? '' }}" data-toggle="list" class="list-group-item list-group-item-action bg-light nav-link {{ $loop->first && $key == 0 ? 'active' : '' }}" ><span class="pl-1">{{ $section->description ?? '' }}</span></a>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                <div class="col-9">
                    <form method="POST" action="{{ route('shineCompliance.assessment.post_edit_questionnaire',['assess_id' => $assessment->id]) }}" enctype="multipart/form-data" class="form-shine">
                        @csrf
                        <div class="tab-content">
                            @foreach($sections as $key => $parent_sections)
                                @foreach($parent_sections->children as $section)
                                    <div id="content_{{ $section->id ?? '' }}" class="container tab-pane {{ $loop->first && $key == 0 ? 'active' : 'fade' }}">
                                        @if(!is_null($section->children) and count($section->children) > 0)
                                            @foreach($section->children as $child_section)
                                                @if(!$loop->first)
                                                    <hr style="width: 92%">
                                                @endif
                                                <h5>{{ $child_section->description ?? '' }}</h5>
                                                @include('shineCompliance.forms.form_question_assessment',['questions' => $child_section->questions])
                                            @endforeach
                                        @else
                                            @include('shineCompliance.forms.form_question_assessment',['questions' => $section->questions])
                                        @endif
                                        <input type="hidden" name="section_id[]" value="{{  $section->id }}">
                                        <input type="hidden" name="score[{{ $section->id }}]" value="" class="final-score">
                                        <input type="hidden" name="total_question[{{  $section->id }}]" value="" class="total-question">
                                        <input type="hidden" name="fail_key_question[{{  $section->id }}]" value="" class="fail-key-question">
                                        <div class="row mt-3 mb-3">
                                            <h6  class="col-md-5 col-form-label text-md-left" >Section Audit Result:</h6>
                                            <div class="col-md-2 score-result mt-1">
                                                00 / 00
                                            </div>
                                            <div class="col-md-4">
                                                <div><span class="spanWarningSuccess font-weight-bold pass-result" >Pass</span></div>
                                                <div><span class="spanWarningSurveying font-weight-bold false-result" style="display: none">Fail</span></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        <div class="col-md-3 mt-4 pull-left">
                            <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                                <strong>{{ __('Save') }}</strong>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('shineCompliance.modals.add_hazard',['assess_id' => $assessment->id ?? 0, 'assess_type' => $assessment->classification, 'modal_id' => 'add_hazard','color' => true])
@endsection
@push('css')
    <style>
        .list-group .list-group-item.active {
            background-color: #f9e6c5 !important;
        }
        .list-group-item.active {
            z-index: 2;
            color: #000;
            background-color: #f9e6c5;
            border-color: #f9e6c5;
        }
    </style>
@endpush
@push('javascript')
    <script type="text/javascript">
        $('#myList a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        });
        $(".pass-result").hide();
        $(".false-result").hide();
        $(document).ready(function(){
            $(".choose-answer").each(function(k,v){
                var parent_tab = $(this).closest('.tab-pane');
                var questions = $(parent_tab).find(".choose-answer");
                var result_pass = $(parent_tab).find(".pass-result");
                var result_false = $(parent_tab).find(".false-result");

                var key = $(parent_tab).find(".choose-answer").data('key');

                var score = 0;
                var total_questions = 0;
                // answer no on key question
                var fail_key_question = 0;

                $.each(questions, function(k,question){
                    score_option = $(question).children("option:selected").data('option');
                    // key_question = $(question).data('key');
                    option_selected = $(question).children("option:selected").val();
                    fail_answers = $(question).data('promptHazard') + '';

                    fail_answer = fail_answers.split(',');

                    // answer no on key question
                    // if ((key_question == true) & (score_option == 0)) {
                    //     fail_key_question +=  1;
                    // }
                    // if do not select N/A
                    // if (score_option != 3) {
                    if (option_selected != 0 && option_selected != 5 && option_selected != 8) {
                        if ($(question).data('question_id') != 193) {
                            total_questions += 1;
                        }

                        if (fail_answers == '') {
                            if ($(question).data('question-id') != 193) {
                                if (option_selected != 2 && option_selected != 4 && option_selected != 7 && option_selected != 11) {
                                    fail_key_question += 1;
                                } else {
                                    score += 1;
                                }
                            }
                        } else {
                            if (fail_answer.length == 1) {
                                if (fail_answer.includes(option_selected) || option_selected == 9 || option_selected == 12) {
                                    fail_key_question += 1;
                                } else {
                                    score += 1;
                                }
                            } else {
                                if ((fail_answer == ["1", "2"] || fail_answer == ["3", "4"] || fail_answer == ["6", "7"] || fail_answer == ["10", "11"])
                                    && option_selected != 1 && option_selected != 3 && option_selected != 6 && option_selected != 10) {
                                    fail_key_question += 1;
                                } else if (fail_answer.includes(option_selected)) {
                                    fail_key_question += 1;
                                } else {
                                    score += 1;
                                }
                            }
                        }
                    }

                });

                if (fail_key_question > 0) {
                    $(result_pass).hide();
                    $(result_false).show();
                } else {
                    $(result_pass).show();
                    $(result_false).hide();
                }

                //score-result
                $(parent_tab).find(".score-result").html(FormatNumberLength(score,2) + ' / ' + FormatNumberLength(total_questions,2));
                var result  = score / total_questions;

                $(parent_tab).find(".final-score").val(score);
                $(parent_tab).find(".total-question").val(total_questions);
                $(parent_tab).find(".fail-key-question").val(fail_key_question);
            });
            $(document).on('change', ".choose-answer", function(event, param) {
                var parent_tab = $(this).closest('.tab-pane');
                var question_id = $(this).data('question-id');
                var questions = $(parent_tab).find(".choose-answer");
                var result_pass = $(parent_tab).find(".pass-result");
                var result_false = $(parent_tab).find(".false-result");

                var key = $(parent_tab).find(".choose-answer").data('key');

                var score = 0;
                var total_questions = 0;
                // answer no on key question
                var fail_key_question = 0;

                $.each(questions, function(k,question){
                    score_option = $(question).children("option:selected").data('option');
                    // key_question = $(question).data('key');
                    option_selected = $(question).children("option:selected").val();
                    fail_answers = $(question).data('promptHazard') + '';

                    fail_answer = fail_answers.split(',');
                    // answer no on key question
                    // if ((key_question == true) & (score_option == 0)) {
                    //     fail_key_question +=  1;
                    // }
                    // if do not select N/A
                    // if (score_option != 3) {
                    if (option_selected != 0 && option_selected != 5 && option_selected != 8) {
                        if ($(question).data('question_id') != 193) {
                            total_questions += 1;
                        }

                        if (fail_answers == '') {
                            if ($(question).data('question-id') != 193) {
                                if (option_selected != 2 && option_selected != 4 && option_selected != 7 && option_selected != 11) {
                                    fail_key_question += 1;
                                } else {
                                    score += 1;
                                }
                            }
                        } else {
                            if (fail_answer.length == 1) {
                                if (fail_answer.includes(option_selected) || option_selected == 9 || option_selected == 12) {
                                    fail_key_question += 1;
                                } else {
                                    score += 1;
                                }
                            } else {
                                if (JSON.stringify(fail_answer) == JSON.stringify(["1", "2"]) || JSON.stringify(fail_answer) == JSON.stringify(["3", "4"])
                                        || JSON.stringify(fail_answer) == JSON.stringify(["6", "7"]) || JSON.stringify(fail_answer) == JSON.stringify(["10", "11"])) {
                                    if (option_selected != 1 && option_selected != 3 && option_selected != 6 && option_selected != 10) {
                                        fail_key_question += 1;
                                    } else {
                                        score += 1;
                                    }
                                } else {
                                    if (fail_answer.includes(option_selected)) {
                                        fail_key_question += 1;
                                    } else {
                                        score += 1;
                                    }
                                }
                            }
                        }
                    }

                });

                if (fail_key_question > 0) {
                    $(result_pass).hide();
                    $(result_false).show();
                } else {
                    $(result_pass).show();
                    $(result_false).hide();
                }

                // Written Scheme
                if ($(this).attr('name') == 'answer[122]' && $(this).val() == 2) {
                    $(this).parents('#content_36').find('select').each(function (i, e) {
                        if ($(e).attr('name') != 'answer[122]') {
                            $(e).val(5);
                            score = 0;
                            total_questions = 1;
                        }
                    });
                }

                //score-result
                $(parent_tab).find(".score-result").html(FormatNumberLength(score,2) + ' / ' + FormatNumberLength(total_questions,2));
                var result  = score / total_questions;

                $(parent_tab).find(".final-score").val(score);
                $(parent_tab).find(".total-question").val(total_questions);
                $(parent_tab).find(".fail-key-question").val(fail_key_question);

                //show modal add hazard
                if (typeof param == 'undefined' || !param) {
                    if (($(this).data('promptHazard') + '').includes($(this).val())){
                        $('#add_hazard').find('.add_hazard_btn').data('question-id', question_id);
                        $('#add_hazard').find('.add_hazard_btn').data('verb-id', $(this).data('verb-id'));
                        $('#add_hazard').find('.add_hazard_btn').data('noun-id', $(this).data('noun-id'));
                        $('#add_hazard').find('.add_hazard_btn').data('name', $(this).data('name'));
                        $('#add_hazard').modal('show');
                    }
                }
            });
            // $(".choose-answer").trigger('change');
            // For Statement
            $('.multi-statement').select2({
                width: '100%',
                minimumResultsForSearch: -1,
                placeholder: "Please select an option"
            });
            // override the select2 open event
            $('.multi-statement').on('select2:open', function () {
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


            $('.multi-statement').change(function(e){
                // if value = other : show other input
                var value = $(this).find(":selected").text();
                if (value.includes("Other")) {
                    $(this).siblings(".statement_other_area").show();
                } else {
                    $(this).siblings(".statement_other_area").hide();
                    $(this).siblings(".statement_other_area").find('textarea').text(null);
                }
            });
        });
        function FormatNumberLength(num, length) {
            var r = "" + num;
            while (r.length < length) {
                r = "0" + r;
            }
            return r;
        }
    </script>
@endpush

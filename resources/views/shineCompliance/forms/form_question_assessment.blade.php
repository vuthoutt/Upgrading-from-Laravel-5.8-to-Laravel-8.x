@if(!is_null($questions))
    @foreach($questions as $key => $question)
        @if ($question->id !== OTHER_HAZARD_IDENTIFIED_QUESTION_ID)
            <div class="row mt-3">
                <h6 class="col-md-5 col-form-label text-md-left" >
                    {{ $question->description }}
                    @if($question->key_question == 1)
                        <span style="color: red;"> *</span>
                    @endif
                </h6>

                <input type="hidden" class="form-control" name="question[]" value="{{ $question->id }}">
                <div class="col-md-2">
                    <div class="form-group ">
                        <select  class="form-control choose-answer" name="answer[{{ $question->id }}]" data-key="{{ $question->key_question == 1 ? 'true' : 'false' }}"
                                 data-prompt-hazard="{{ $question->hazard_answer_value }}" data-question-id="{{$question->id}}"
                                 data-verb-id="{{ $question->hz_verb_id }}" data-noun-id="{{ $question->hz_noun_id }}"
                                 data-name="{{ $question->hz_name }}">
                            <option value="0" data-option="0"></option>
                            @if(!is_null($question->answerType))
                                @foreach($question->answerType as $answer)
                                    <option value="{{ $answer->id }}" data-option="{{ $answer->score ?? 0 }}"
                                        {{ $answer->id  == ($question->answer->answer_id ?? NULL) ? 'selected' : ''}}>
                                        {{ $answer->description }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <textarea name="comment[{{ $question->id }}]" class="text-area-form @error('comment') is-invalid @enderror" style="height: 200px"
                              placeholder="{{ $question->unique_id == 1 ? 'Please Enter An Unique ID Number' : 'Please Add a Comment' }}"
                    >{{  $question->answer->other ?? $question->preloaded_comment ?? '' }}</textarea>
                    {{--                @error('comment')--}}
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message ?? '' }}</strong>
                        </span>
                    {{--                @enderror--}}
                </div>
            </div>
        @else
            <div id="load_other_hazard_questions"></div>
            @push('javascript')
                <script type="text/javascript" src="{{ URL::asset('js/shineCompliance/other_hazard_identified_questions.js') }}"></script>
                <script type="text/javascript">
                    $.ajax
                    ({
                        type: "GET",
                        url: "{{ route('shineCompliance.ajax.other_hazard_identified_questions' )}}",
                        data: {assessment_id : "{{ $assessment->id }}" },
                        async: false,
                        cache: false,
                        success: function (value) {
                            if (value) {
                                $('#load_other_hazard_questions').html(value.data);
                            }
                        }
                    });
                </script>
            @endpush
        @endif
        @if($assessment->classification == ASSESSMENT_FIRE_TYPE )
        <div class="row mt-2">
            <h6  class="col-md-7  col-form-label text-md-left" >
                Observations:
            </h6>
            <div class="col-md-5">
                <div class="statement_other_area" style="margin-top: 15px;">
                                    <textarea class="text-area-form" style="height: 100px"
                                              name="observations[{{ $question->id }}]"
                                              >{{ $question->answer()->where('assess_id', $assessment->id)->first() !== null
                                                ? $question->answer()->where('assess_id', $assessment->id)->first()->observations
                                                : '' }}</textarea>
                </div>
            </div>
        </div>
        @endif
    @endforeach

@endif


@php
    isset($max_option) ? $max_option = $max_option : $max_option = 9 ;
@endphp
<div class="other-hazard-identified-questions">
    <input type="hidden" id="max_option" value="{{ $max_option }}">
    <input type="hidden" class="form-control" name="question[]" value="{{ $question->id }}">
    <!-- if have data then display all data else display an empty option -->
    {{-- add --}}
    @if(count($answers) == 0)
        <div class="row row-other-hazard-question mt-3">
            <h6 class="col-md-4 col-form-label text-md-left" >
                {{ $question->description }}
                @if($question->key_question == 1)
                    <span style="color: red;"> *</span>
                @endif
            </h6>
            <div class="col-md-2">
                <div class="form-group ">
                    <input type="hidden" class="form-select-answer" value="0">
                    <select class="form-control choose-answer" name="answer[{{ $question->id }}][]" data-key="{{ $question->key_question == 1 ? 'true' : 'false' }}"
                             data-prompt-hazard="{{ $question->hazard_answer_value }}" data-question-id="{{$question->id}}"
                             data-verb-id="{{ $question->hz_verb_id }}" data-noun-id="{{ $question->hz_noun_id }}"
                             data-name="{{ $question->hz_name }}">
                        <option value="0" data-option="0"></option>
                        @if(!is_null($question->answerType))
                            @foreach($question->answerType as $answer)
                                <option value="{{ $answer->id }}" data-option="{{ $answer->score ?? 0 }}">
                                    {{ $answer->description }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <textarea name="comment[{{ $question->id }}][]" class="text-area-form other-hazard-comment @error('comment') is-invalid @enderror" style="height: 200px"
                          placeholder="{{ $question->unique_id == 1 ? 'Please Enter An Unique ID Number' : 'Please Add a Comment' }}"
                ></textarea>
                <span class="invalid-feedback" role="alert"><strong>{{ $message ?? '' }}</strong></span>
            </div>
            <div class="col-md-1 btn-option">
                <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        {{-- edit --}}
    @else
        @php
            $arr_data_decode = $answers;
            $count = count($arr_data_decode) - 1;// for check with key
            $index = 0;
        @endphp
        @foreach($arr_data_decode as $key => $value)
            <div class="row row-other-hazard-question mt-3">
                <h6 class="col-md-3 col-form-label text-md-left" >
                    {{ $question->description }}
                    @if($question->key_question == 1)
                        <span style="color: red;"> *</span>
                    @endif
                </h6>
                <div class="col-md-2">
                    <div class="form-group ">
                        <input type="hidden" class="form-select-answer" value="{{ $key }}">
                        <select class="form-control choose-answer" name="answer[{{ $question->id }}][]" data-key="{{ $question->key_question == 1 ? 'true' : 'false' }}"
                                data-prompt-hazard="{{ $question->hazard_answer_value }}" data-question-id="{{$question->id}}"
                                data-verb-id="{{ $question->hz_verb_id }}" data-noun-id="{{ $question->hz_noun_id }}"
                                data-name="{{ $question->hz_name }}">
                            <option value="0" data-option="0"></option>
                            @if(!is_null($question->answerType))
                                @foreach($question->answerType as $answer)
                                    <option value="{{ $answer->id }}" data-option="{{ $answer->score ?? 0 }}"
                                        {{ $answer->id  == ($value->answer_id ?? NULL) ? 'selected' : ''}}>
                                        {{ $answer->description }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                <textarea name="comment[{{ $question->id }}][]" class="text-area-form other-hazard-comment @error('comment') is-invalid @enderror" style="height: 200px"
                          placeholder="{{ $question->unique_id == 1 ? 'Please Enter An Unique ID Number' : 'Please Add a Comment' }}"
                >{{  $value->other ?? '' }}</textarea>
                    <span class="invalid-feedback" role="alert"><strong>{{ $message ?? '' }}</strong></span>
                </div>
                <div class="col-md-1 btn-option">
                    @if($key == 0 && $key < $count)
                        <!-- first but not last -->
                            <a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </a>
                    @elseif($key == 0 && $key == $count)
                        <!-- first but last -->
                            <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                    @elseif($key < $max_option && $key < $count)
                        <!-- middle can add/minus -->
                            <a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </a>
                    @elseif($key < $max_option && $key = $count)
                        <!-- last but can add more -->
                            <a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </a>
                            <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                    @else
                        <!-- last -->
                            <a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </a>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>

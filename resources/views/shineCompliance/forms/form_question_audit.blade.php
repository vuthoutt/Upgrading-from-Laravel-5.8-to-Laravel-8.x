@if(!is_null($questions))
    @foreach($questions as $key => $question)
        <div class="row mt-3">
            <h6  class="col-md-5 col-form-label text-md-left" >
                {{ $question->description }}
                @if($question->key_question == 1)
                <span style="color: red;"> *</span>
                @endif
            </h6>

            <input type="hidden" class="form-control" name="question[]" value="{{ $question->id }}">
            <div class="col-md-2">
                    <div class="form-group ">
                        <select  class="form-control choose-answer" name="answer[{{ $question->id }}]" data-key="{{ $question->key_question == 1 ? 'true' : 'false' }}">
                            <option value="0" data-option="0"></option>
                            @if(!is_null($question->answers))
                                @foreach($question->answers as $answer)
                                    <option value="{{ $answer->id }}" data-option="{{ $answer->score }}"
                                        {{ (isset($data_selected['answer'][$question->id]) and $data_selected['answer'][$question->id] == $answer->id) ? 'selected' : '' }}>
                                        {{ $answer->description }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
            </div>
            <div class="col-md-4">
                <input type="text" name="comment[{{ $question->id }}]" class="form-control @error('comment') is-invalid @enderror"
                value="{{  $data_selected['comment'][$question->id] ?? '' }}"
                placeholder="{{ $question->unique_id == 1 ? 'Please Enter An Unique ID Number' : 'Please Enter A Comment' }}"
                >
                @error('comment')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    @endforeach

@endif

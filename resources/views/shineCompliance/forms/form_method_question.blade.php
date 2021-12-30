<div class="row register-form">
    <label  class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ isset($question['description']) ? $question['description'] : '' }}</label>
    @if($question['other'] === false)
        <input type="hidden" class="form-control" name="question[]" value="{{ $question['id'] }}">
        <div class="col-md-3">
            <div class="form-group ">
                <select  class="form-control " name="answer[]" >
                    <option value=""></option>
                    @if(count($question['answers']))
                        @foreach($question['answers'] as $answer)
                            <option value="{{ $answer['id'] }}" {{ ($answer['id'] == $question['selected']) ? 'selected' : ''}}>{{ $answer['description'] }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <input type="text" name="comment[]" class="form-control @error('comment') is-invalid @enderror" value="{{ $question['comment'] }}">
            @error('comment')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    @else
        <div class="col-md-3">
            <div class="form-group ">
                <input type="hidden" class="form-control" name="question-other[]" value="{{ $question['id'] }}">
                <input type="text" name="other-answer[]" class="form-control @error('other-answer') is-invalid @enderror" value="{{ $question['other'] }}">
                    @error('other-answer')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
            </div>
        </div>
    @endif
</div>

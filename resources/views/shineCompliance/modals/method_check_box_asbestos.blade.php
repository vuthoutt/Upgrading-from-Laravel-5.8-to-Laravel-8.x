<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shineArc - Method Options</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('method_option',['survey_id' => $survey->id, 'property_id' => $survey->property_id, 'type' => 'method-table']) }}" method="POST" class="form-shine">
            @csrf
            <div class="modal-body" style="padding-left: 150px">
                <strong>How would you like to proceed?</strong>
                    @if($survey->status != COMPLETED_SURVEY_STATUS)
                    <div class="form-check mt-2">
                        <label class="form-check-label" for="radio1">
                            <input type="radio" class="form-check-input" id="radio1" name="methodStyle" value="{{ METHOD_STYLE_TEXT_BOX }}" {{ ($survey->methodStyle == METHOD_STYLE_TEXT_BOX) ? 'checked' : '' }}>Fill in the Text Box
                        </label>
                    </div>
                    @endif
                    <div class="form-check mt-2">
                        <label class="form-check-label" for="radio2">
                            <input type="radio" class="form-check-input" id="radio2" name="methodStyle" value="{{ METHOD_STYLE_QUESTION }}" {{ ($survey->methodStyle == METHOD_STYLE_QUESTION) ? 'checked' : '' }}>Fill in the Questionnaire
                        </label>
                    </div>
            </div>
            <div class="mb-3" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn light_grey_gradient" >Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
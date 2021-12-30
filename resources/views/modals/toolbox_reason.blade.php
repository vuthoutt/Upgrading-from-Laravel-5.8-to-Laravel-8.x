<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 550px !important;">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - {{ $title ?? '' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body" style="min-height:190px;text-align: center;">
                    <textarea id="reason-content" class="mb-3" style="width: 90%;min-height: 160px;border-radius: 6px" name="reason-content"></textarea>
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient" id="cancel-reason" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn light_grey_gradient" data-dismiss="modal">Select</button>
                </div>
        </div>
    </div>
</div>

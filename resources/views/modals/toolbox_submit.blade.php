<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - {{ $title ?? '' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body" style="min-height:140px;margin-left: 25px">
                    <strong>{{ $header ?? ''}}</strong>
                    <div class="mt-3">
                        <strong>Remove Group: </strong><span id="submit-content"></span>
                    </div>
                    <div class="mt-3">
                        <strong>Reason: </strong><span id="submit-reason"></span>
                    </div>

                    <div class="mt-3">
                        <span class="red_text">Please note this wil permanently remove your selected item!</span>
                    </div>

                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient" data-dismiss="modal">No</button>
                    <button type="submit" class="btn light_grey_gradient" id="submit_zone">Yes</button>
                </div>
        </div>
    </div>
</div>

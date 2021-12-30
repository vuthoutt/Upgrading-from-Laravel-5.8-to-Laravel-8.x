<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shineArc - Survey Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" class="form-shine">
            @csrf
            <div class="modal-body" style="padding-left: 100px;height:140px ">
                <strong>Are you sure that you want to send {{ $survey->reference ?? '' }} to {{ \CommonHelpers::getUserFullname($data->surveyor_id) }}?</strong>
                <div class="mt-4">
                    <span>This survey will remain locked in Arc until the data has been sent back from Light.</span>
                </div>

            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn light_grey_gradient_button fs-8pt" >Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

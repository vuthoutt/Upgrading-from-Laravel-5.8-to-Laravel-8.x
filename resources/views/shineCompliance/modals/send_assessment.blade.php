<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important;">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getComplianceNavColor($color) : 'red_color' }}" style="border-radius:0px !important;">
                <h5 class="modal-title" id="exampleModalLabel">shine{{ isset($color) ? 'Arc' : 'Prism' }} - Assessment Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" class="form-shine">
            @csrf
            <div class="modal-body" style="padding-left: 100px;height:140px ">
                <strong>Are you sure that you want to send {{ $assessment->reference ?? '' }} to {{ \CommonHelpers::getUserFullname($data->assessor_id) ?? '' }}?</strong>
                <div class="mt-4">
                    <span>This assessment will remain locked in shineCompliance until the data has been sent back from Shine X.</span>
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

<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important;">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getComplianceNavColor($color) : 'red_color' }}" style="border-radius:0px !important;">
                <h5 class="modal-title" id="exampleModalLabel">shineCompliance - Assessment Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" class="form-shine">
            @csrf
            <div class="modal-body" style="padding-left: 130px;height:140px ">
                <strong>Are you sure that you want to publish {{ $assessment->reference }} ?</strong>

                    <div class="form-check" style="margin-top: 35px;">
                        <label class="switch">
                            <input type="checkbox" name="assessment_draft" >
                            <span class="slider round"></span>
                        </label>
                        <strong style="margin-left: 15px;margin-top: 5px;">Assessment Draft Only</strong>
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

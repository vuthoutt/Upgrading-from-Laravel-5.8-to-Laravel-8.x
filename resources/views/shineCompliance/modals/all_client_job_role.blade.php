<!-- Modal -->
<div class="modal fade pr-0" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important;">
            <div class="modal-header" style="color: #fff; background-color:orange; border-radius:0px !important;">
{{--                <h5 class="modal-title" id="exampleModalLabel">shineArc - Add Hazard</h5>--}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding-left: 20px;min-height:50px;text-align: center;">
                <strong class="content-text" data-content-select="Would you like to select all Clients?" data-content-deselect="Would you like to deselect all Clients?">
                    Would you like to select all Clients?
                </strong>
            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient_button fs-8pt cancel_all_client_btn" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn light_grey_gradient_button shine_document_submit fs-8pt all_client_btn" data-question-id="0">Submit</button>
            </div>
        </div>
    </div>
</div>

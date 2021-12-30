<!-- Modal -->
<div class="modal fade" id="addFireAndAssembly" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getComplianceNavColor($color) : 'red_color' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism – Add Fire Exit/Assembly Point</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('shineCompliance.property.add_fire_exist_assembly', ['property_id' => $property_id]) }}" method="POST" class="form-shine">
            @csrf
            <div class="modal-body" style="padding-left: 150px">
                <strong>What would you like to add?</strong>
                    <div class="form-check mt-2">
                        <label class="form-check-label" for="radio1">
                            <input type="radio" class="form-check-input" id="radio1" name="methodStyle" value="fire">Fire Exit
                        </label>
                    </div>
                    <div class="form-check mt-2">
                        <label class="form-check-label" for="radio2">
                            <input type="radio" class="form-check-input" id="radio2" name="methodStyle" value="assembly">Assembly Point
                        </label>
                    </div>
            </div>
            <div class="mb-3 mt-3" style="padding-left: 150px">
                <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn light_grey_gradient_button shine_document_submit fs-8pt decommission_btn">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

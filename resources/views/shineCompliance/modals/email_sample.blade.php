<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - {{ $title ?? 'Site Plan Document' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" >
                @csrf
                <input type="hidden" name="survey_id" value="{{  $data->id ?? 0 }}">
                <div class="modal-body" style="padding-left: 30px;">
                    <div class="row register-form">
                        <label class="col-md-3 col-form-label text-md-left font-weight-bold">Email
                                <span style="color: red;">*
                        </label>
                        <div class="col-md-7">
                            <div class="form-group">
                                <select  class="form-control" name="sample_email" >
                                    <option value="" data-option="0">------ Please select an option -------</option>
                                    <option value="sample">Sample Results Updated</option>
                                    <option value="cad">CAD Drawing Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn light_grey_gradient_button fs-8pt" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>

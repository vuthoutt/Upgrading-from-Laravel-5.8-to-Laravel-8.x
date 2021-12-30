<!-- Modal -->
<div class="modal fade pr-0" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important;">
            <div class="modal-header" style="color: #fff; background-color: #be1e2d; border-radius:0px !important;">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}" />
                <input type="hidden" class="property_id" name="property_id" value="{{$property_id}}" >
                <input type="hidden" class="system_id" name="system_id" value="{{$system_id ?? 0}}" >
                <input type="hidden" class="equipment_id" name="equipment_id" value="{{$equipment_id ?? 0}}" >
                <input type="hidden" class="program_id" name="program_id" value="{{$program_id ?? 0}}" >
                <div class="modal-body" style="padding-left: 20px;min-height:100px">
                    @include('shineCompliance.forms.form_input',['title' => 'Category  Title:', 'name' => 'category_title',
                              'width' => 7,'width_label' => 4 , 'data' => '', 'required' => true])
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn light_grey_gradient_button shine_category_submit fs-8pt">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

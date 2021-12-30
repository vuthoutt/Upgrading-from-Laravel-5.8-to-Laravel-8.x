<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important; width: 110%">
            <div class="modal-header" style="color: #fff; background-color: #be1e2d; border-radius:1px;margin-left: -1px;">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - Add Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}" />
                <input type="hidden" class="property_id" name="property_id" value="{{$property_id}}" >
                <div class="modal-body" style="padding-left: 20px;min-height:190px;max-height: 400px;overflow-y: scroll;">
                    @include('shineCompliance.forms.form_input',['title' => 'Document Title:','width_label' => 4,'name'=>'name','width'=>7, 'data' => '', 'required' => true])
                    @include('shineCompliance.forms.form_dropdown',['title' => 'Document Category:',
                                                                    'data' => $historical_categories ,
                                                                    'width_label' => 4,
                                                                    'width' => 7,
                                                                    'name' => 'category_id',
                                                                    'id' => 'form-category',
                                                                    'key'=> 'id',
                                                                    'value'=>'name',
                                                                    'required' => true,'no_first_op' => true ])
                    @include('shineCompliance.forms.form_dropdown_document_type',['title' => 'Document Type:','width_label' => 4,'name'=>'type[]','id'=>'','width'=>7,
                    'data' => $document_types,'key'=>'id','value'=>'description','other'=>'type_other', 'other_id' => 'other_id'.$system_type_id,
                    'other_value'=>'' ,'form_class' => 'doc_type mb-3' ,])
                    @include('forms.form_checkbox_hd_document',['title' => 'ACMs:', 'name' => 'is_identified_acm','data' => '','compare' => 1, 'class' => 'boolean_switches' ])
                    @include('forms.form_checkbox_hd_document',['title' => 'Inaccessible Room/locations:', 'name' => 'is_inaccess_room','data' => '','compare' => 1, 'class' => 'boolean_switches' ])

                    @include('shineCompliance.forms.form_dropdown_document',['title' => 'Parent:','width_label' => 4,'name'=>'parent_type','width'=>7,
                    'data' => $parent_document_types,'key'=>'id','value'=>'description','required' => true,'form_class'=>'parent_type'])

                    <div class="row register-form">
                        <label class="col-md-4 font-weight-bold fs-8pt" >Document:<span style="color: red;"> *</span></label>
                        <div class="col-md-7">
                            <input class="form-control" type="file" class="form-control-file" name="document" value="" style="min-height: 40px !important;">
                        </div>
                        <span class="invalid-feedback font-weight-bold modal-err-text" role="alert"><strong></strong></span>
                    </div>
                    @include('shineCompliance.forms.form_datepicker',['title' => 'Document Date:','width_label' => 4,'name'=>'date', 'id'=>'date'.Str::random(5), 'required' => true])
                    <div class="fire-section">
                        @include('shineCompliance.forms.form_datepicker',['title' => 'Enforcement Deadline:','width_label' => 4,'name'=>'enforcement_deadline', 'id'=>'enforcement_deadline'.Str::random(5), 'required' => true, 'class' => 'enforcement_deadline'])
                        @include('shineCompliance.forms.form_dropdown',['title' => 'Document Status:',
                                                                        'data' => $document_statuses ,
                                                                        'width_label' => 4,
                                                                        'width' => 7,
                                                                        'name' => 'document_status',
                                                                        'id' => 'document_status',
                                                                        'key'=> 'id',
                                                                        'value'=>'description',
                                                                        'required' => true ])
                    </div>
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn light_grey_gradient_button shine_document_submit fs-8pt">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

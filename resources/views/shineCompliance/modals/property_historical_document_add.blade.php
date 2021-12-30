<!-- Modal -->
<div class="modal fade pr-0" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important; width: 110%">
            <div class="modal-header" style="color: #fff; background-color: #be1e2d; border-radius:0px !important;">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - Add Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}" />
                <input type="hidden" class="property_id" name="property_id" value="{{$property_id}}" >
                <div class="modal-body" style="padding-left: 20px;min-height:190px">
                    @include('shineCompliance.forms.form_input',['title' => 'Document Title:','width_label' => 4,'name'=>'name','width'=>7, 'data' => '', 'required' => true])

                    @include('shineCompliance.forms.form_dropdown_document_type',['title' => 'Document Type:','width_label' => 4,'name'=>'document_type','id'=>'','width'=>7,
                    'data' => $document_types,'key'=>'id','value'=>'description','other'=>'type_other', 'other_id' => 'historical_other',
                    'other_value'=>'' ,'form_class' => 'doc_type','option_data' => 'is_external_ms','form_class' => 'document_type','required' => true])
                    <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="document_type-err"></span>
                    @include('shineCompliance.forms.form_dropdown',['title' => 'Document Category:',
                                                'data' => $historical_categories ,
                                                'width_label' => 4,
                                                'width' => 7,
                                                'name' => 'category',
                                                'id' => 'form-category',
                                                'key'=> 'id',
                                                'value'=>'category',
                                                'required' => true ])

                    <div class="row register-form">
                        <label class="col-md-4 font-weight-bold fs-8pt" >Document:<span style="color: red;"> *</span></label>
                        <div class="col-md-7">
                            <input class="form-control" type="file" class="form-control-file" name="document" value="" style="min-height: 40px !important;">
                        </div>
                        <span class="invalid-feedback font-weight-bold modal-err-text" role="alert"><strong></strong></span>
                    </div>
                    @include('shineCompliance.forms.form_datepicker',['title' => 'Date:','width_label' => 4,'name'=>'historic_date', 'id'=>'date'.Str::random(5)])
                    @include('forms.form_checkbox_hd_document',['title' => 'ACMs:', 'name' => 'is_identified_acm','data' => '','compare' => 1, 'class' => 'boolean_switches' ])
                    @include('forms.form_checkbox_hd_document',['title' => 'Inaccessible Room/locations:', 'name' => 'is_inaccess_room','data' => '','compare' => 1, 'class' => 'boolean_switches' ])
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn light_grey_gradient_button shine_historical_document_submit fs-8pt">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

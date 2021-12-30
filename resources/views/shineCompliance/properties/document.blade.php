@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav', ['color' => 'red'])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">1 Broadley Street</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_search',['backRoute' => route('shineCompliance.property'),'addRoute' =>'#'])
        <div class="row">
            @include('shineCompliance.properties.partials._property_sidebar')
            <div class="col-md-9 pl-0 pr-0">
                <div class="card-data mar-up">
                    <div class="col-md-12" id="overallPropertySummary" style="padding-right: 0px; padding-left: 15px;">
                        <div class="card discard-border-radius" style="border:none!important;">
                            <div class="card-header table-header discard-border-radius" style="background-color: #d1d3d4">
                                <h6 class="table-title">AOV Documentation (3)</h6>
                                <div class="btn collapse-table table-collapse-button" data-toggle="collapse"
                                     data-target="#collapse-overallPropertySummary" aria-expanded="true" aria-controls="collapse-property-contact">
                                    <i class="fa fa-lg " aria-hidden="true"></i>
                                </div>
                                <div class="btn collapse-table table-plus-button" data-toggle="modal" data-target="#tender-doc-modal" aria-hidden="true">
                                    <i class="fa fa-plus"></i>
                                </div>
                            </div>
                            <div id="collapse-overallPropertySummary" class="collapse show"
                                 data-parent="#overallPropertySummary" style="">
                                <div class="card-body" style="padding-left: 21px;padding-bottom: 0px;">
                                    <table class="table table-bordered shineDatatable normal-table-content">
                                        <thead>
                                        <tr>
                                            <th style="width: 20%">Document Name</th>
                                            <th style="width: 15%">Document Reference</th>
                                            <th style="width: 20%">Parent</th>
                                            <th style="width: 20%">Status</th>
                                            <th style="width: 8%">File</th>
                                            <th style="width: 15%">Date Completed</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Annual Service</td>
                                                <td>
                                                    HD1
                                                </td>
                                                <td>
                                                    <a href="#">AOV Control Point #1</a>
                                                </td>
                                                <td>
                                                    Completed
                                                </td>
                                                <td>
                                                    <a href="#">
                                                    <img src="{{ asset('img/pdf-green.png') }}" width="19" height="19" class="fileicon" alt="View File" border="0"></a>
                                                    <a href="#" class="btn btn-outline-secondary btn-sm">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    10/11/2020
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Call-out</td>
                                                <td>
                                                    HD2
                                                </td>
                                                <td>
                                                    <a href="#">AOV Control Point #1</a>
                                                </td>
                                                <td>
                                                    Published(Awaiting Approval)
                                                </td>
                                                <td>
                                                    <a href="#">
                                                        <img src="{{ asset('img/pdf-orange.png') }}" width="19" height="19" class="fileicon" alt="View File" border="0"></a>
                                                    <a href="#" class="btn btn-outline-secondary btn-sm">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Remedial</td>
                                                <td>
                                                    HD3
                                                </td>
                                                <td>
                                                    <a href="#">AOV Control Point #1</a>
                                                </td>
                                                <td>
                                                    Rejected
                                                </td>
                                                <td>
                                                    <a href="#">
                                                        <img src="{{ asset('img/pdf-red.png') }}" width="19" height="19" class="fileicon" alt="View File" border="0"></a>
                                                    <a href="#" class="btn btn-outline-secondary btn-sm">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-data mar-up">
                    <div class="col-md-12" id="propertyDecommissionedACMs" style="padding-right: 0px; padding-left: 15px;">
                        <div class="card discard-border-radius" style="border:none!important;">
                            <div class="card-header table-header discard-border-radius" style="background-color: #d1d3d4">
                                <h6 class="table-title">
                                    Emergency Lighting Documentation (0)
                                </h6>
                                <div class="btn collapse-table table-collapse-button collapsed" data-toggle="collapse"
                                     data-target="#collapse-propertyDecommissionedACMs" aria-expanded="false" aria-controls="collapse-property-contact">
                                    <i class="fa fa-lg " aria-hidden="true"></i>
                                </div>
                                <div class="btn collapse-table table-plus-button">
                                    <a href="#" style="text-decoration: none;color: inherit"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div id="collapse-propertyDecommissionedACMs" class="collapse "
                                 data-parent="#propertyDecommissionedACMs" style="">
                                <div class="card-body"  style="padding-left: 21px;padding-bottom: 0px;">
                                    <table class="table table-bordered shineDatatable normal-table-content">
                                        <thead>
                                        <tr>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-data mar-up">
                    <div class="col-md-12" id="asbestosPropertySummary" style="padding-right: 0px; padding-left: 15px;">
                        <div class="card discard-border-radius" style="border:none!important;">
                            <div class="card-header table-header discard-border-radius" style="background-color: #d1d3d4">
                                <h6 class="table-title">Fall and Arrest Documentation (0)</h6>
                                <div class="btn collapse-table table-collapse-button collapsed" data-toggle="collapse"
                                     data-target="#collapse-asbestosPropertySummary" aria-expanded="false" aria-controls="collapse-property-contact">
                                    <i class="fa fa-lg " aria-hidden="true"></i>
                                </div>
                                <div class="btn collapse-table table-plus-button">
                                    <a href="#" style="text-decoration: none;color: inherit"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div id="collapse-asbestosPropertySummary" class="collapse show"
                                 data-parent="#asbestosPropertySummary" style="">
                                <div class="card-body" style="padding-left: 21px;padding-bottom: 0px;">
                                    <table class="table table-bordered shineDatatable normal-table-content">
                                        <thead>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add-document-register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header red">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism - Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form>
                <input type="hidden" name="_token" value="zOurPySmoxYKQLXlEk56u3QIhDxjq5fma6kWRKrU">
                <meta name="csrf-token" content="zOurPySmoxYKQLXlEk56u3QIhDxjq5fma6kWRKrU">
                <div class="modal-body" style="text-align: center;">
                    <div class="mt-4">
                        <div class="row register-form">
                            <label class="col-md-4 col-form-label text-md-left font-weight-bold">
                                Category Title:
                                <span style="color: red;">*</span>
                            </label>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <input maxlength="" type="text" class="form-control" name="area_reference" id="form-area_reference" value="" placeholder="">
                                    <span class="invalid-feedback" role="alert"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button font-weight-bold" data-dismiss="modal">Cancel</button>
                    <button class="btn light_grey_gradient_button shine_submit_modal font-weight-bold" id="submit_area">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade " id="tender-doc-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 560px">
            <div class="modal-header red">
                <h5 class="modal-title" id="exampleModalLabel">shinePrism  - Add Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data" id="form_tender">
                <div class="modal-body" style="padding-left: 30px;min-height:140px ">

                    <div class="row register-form mb-4">
                        <div class="col-md-5">
                            <strong>Document Title:<span style="color: red">*</span></strong>
                        </div>
                        <div class="col-md-7">
                            <div class="easy-autocomplete eac-blue-light" style="width: 250px;">
                                <input class="form-control" name="name" value="" style="width: 250px !important"  autocomplete="off">
                                <div class="easy-autocomplete-container">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row register-form" id="type-form">
                        <label class="col-md-5 col-form-label text-md-left font-weight-bold">Document Type: <span style="color: red;"> *</span>
                        </label>
                        <div class="col-md-7">
                            <div class="form-group" style="width: 250px !important">
                                <select class="form-control " name="type" >
                                    <option value="" data-option="0">------ Please select an option -------</option>
                                    <option value="24" data-option="">Service Record</option>
                                    <option value="25" data-option="">Call-out</option>
                                    <option value="26" data-option="">Certificate of Re-occupation</option>
                                    <option value="27" data-option="">Remedial</option>
                                    <option value="28" data-option="">Work Order</option>
                                    <option value="29" data-option="">Other</option>
                                </select>
                                <input type="hidden" class="form-control" id="other-input-x" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row register-form mb-4">
                        <div class="col-md-5">
                            <strong>Parent:<span style="color: red">*</span></strong>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group" style="width: 250px !important">
                                <select class="form-control" id="parent_id" name="type" >
                                    <option value="" data-option="0">------ Please select an option -------</option>
                                    <option value="1" data-option="">No Parent Required</option>
                                    <option value="2" data-option="">System </option>
                                    <option value="3" data-option="">Equipment  </option>
                                </select>
                                <input type="hidden" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row register-form mb-4" id="parent_system">
                        <div class="col-md-5">
                        </div>
                        <div class="col-md-7">
                            <div class="form-group" style="width: 250px !important">
                                <select class="form-control" id="parent_system_id" name="type" >
                                    <option value="" data-option="0">------ Please select an option -------</option>
                                    <option value="1" data-option="">Property System #1</option>
                                    <option value="2" data-option="">Property System #1</option>
                                    <option value="3" data-option="">Property System #1</option>
                                </select>
                                <input type="hidden" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row register-form mb-4" id="property_system">
                        <div class="col-md-5">
                        </div>
                        <div class="col-md-7">
                            <div class="form-group" style="width: 250px !important">
                                <select class="form-control" id="property_system_id" name="type" >
                                    <option value="0" data-option="0">------ Please select an option -------</option>
                                    <option value="1" data-option="">System Programme #1</option>
                                    <option value="2" data-option="">System Programme #2</option>
                                    <option value="3" data-option="">System Programme #3</option>
                                </select>
                                <input type="hidden" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row register-form mb-4" id="equipment">
                        <div class="col-md-5">
                        </div>
                        <div class="col-md-7">
                            <div class="form-group" style="width: 250px !important">
                                <select class="form-control" id="equipment_id" name="type" >
                                    <option value="0" data-option="0">------ Please select an option -------</option>
                                    <option value="1" data-option="">Property Equipment #1</option>
                                    <option value="2" data-option="">Property Equipment #2</option>
                                    <option value="3" data-option="">Property Equipment #3</option>
                                </select>
                                <input type="hidden" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row register-form mb-4" id="property_equipment">
                        <div class="col-md-5">
                        </div>
                        <div class="col-md-7">
                            <div class="form-group" style="width: 250px !important">
                                <select class="form-control" id="property_equipment_id" name="type" >
                                    <option value="0" data-option="0">------ Please select an option -------</option>
                                    <option value="1" data-option="">Property Equipment #1</option>
                                    <option value="2" data-option="">Property Equipment #2</option>
                                    <option value="3" data-option="">Property Equipment #3</option>
                                </select>
                                <input type="hidden" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row register-form mb-4">
                        <label class="col-md-5 font-weight-bold" for="document_file">Document: <span style="color: red;"> *</span></label>
                        <input class="col-md-7" type="file" id="form-document_filetender" name="document_file" value="" required="">
                        <span class="invalid-feedback font-weight-bold modal-err-text" role="alert" id="document_file-errtender" style="display: none;"></span>
                    </div>
                    <div class="row register-form mb-4">
                        <label for="email" class="col-md-5 col-form-label text-md-left font-weight-bold">
                            Date:
                        </label>
                        <div class="col-md-5">
                            <div class="form-group ">
                                <div role="wrapper" class="gj-datepicker gj-datepicker-bootstrap gj-unselectable input-group" style="width: 276px;">
                                    <input class="form-control" name="deadline" value="" id="deadlinetender" width="276">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button font-weight-bold" data-dismiss="modal">Cancel</button>
                    <button id="add_document_tender" class="btn light_grey_gradient_button shine_submit_modal font-weight-bold">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            $('#parent_system').hide();
            $('#property_system').hide();
            $('#equipment').hide();
            $('#property_equipment').hide();
            $('body').on('change', '#parent_id', function(){
                $('#parent_system').hide();
                $('#property_system').hide();
                $('#equipment').hide();
                $('#property_equipment').hide();
                var parent_val = $('#parent_id').val();
                if(parent_val == 2){
                    $('#parent_system').show();
                    $('#equipment').hide();
                }
                if(parent_val == 3){
                    $('#parent_system').hide();
                    $('#equipment').show();
                }
            })
            $('body').on('change', '#parent_system_id', function(){
                var parent_system_val = $('#parent_system_id').val();
                if(parent_system_val != 0){
                    $('#property_system').show();
                }
            })
            $('body').on('change', '#equipment_id', function(){
                var equipment_id_val = $('#equipment_id').val();
                if(equipment_id_val != 0){
                    $('#property_equipment').show();
                }
            })

            var options = {
                url: function(phrase) {
                    return "{{route('shineCompliance.document.search_document')}}"+"?query_string=" + phrase;
                },
                getValue: "name",
                template: {
                    type: "custom",
                    method: function(value, item) {
                        return item.name + "\n";
                    }
                },
                list: {
                    hideAnimation: {
                        type: "slide", //normal|slide|fade
                        time: 250,
                        callback: function() {}
                    },
                    maxNumberOfElements: 8,
                    match: {
                        enabled: true
                    }
                },
                placeholder: "Parent Search...",
                theme: "blue-light"
            };

            $("#survey-search-form-nametender").easyAutocomplete(options);
        });

        $('#deadlinetender').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'dd/mm/yyyy',
            todayHighlight: true
        });
    </script>
@endpush

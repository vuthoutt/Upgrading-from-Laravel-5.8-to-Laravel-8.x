@extends('shineCompliance.layouts.app')

@section('content')

    @include('shineCompliance.partials.nav',['breadCrumb' => 'programme_document_compliance', 'color' => 'red', 'data' => $object])
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">{{ $object->name ?? '' }}</h3>
        </div>
        <div class="main-content mar-up">
            @include('shineCompliance.properties.partials._property_button_search',
                    [ 'backRoute' =>  route('shineCompliance.property.property_detail',['property_id' => $object->property_id ?? 0 ]),
                        'addRoute' => $can_add_new ? '#property-category-add' : false,"class_css" => "35px"
                    ])

            <div class="row">
                @include('shineCompliance.properties.partials._property_system_programme_sidebar',
                ['image' =>  asset(\ComplianceHelpers::getSystemFile($object->id, COMPLIANCE_PROGRAMME_PHOTO)),
                'id' => $object->id ?? 0,
                'route' => 'shineCompliance.programme.detail',
                'route_document' => route('shineCompliance.programme.document.list', ['id'=>$object->id ?? 0, 'type'=> DOCUMENT_PROGRAMME_TYPE])
                ])

                <div class="col-md-9 pt-0" style="padding: 0" >
                    <div class="property-detail" >
                        <div class="row">
                            <div class="col-12 mb-1 pr-0">
                                <input type="hidden" class="sys_id" name="sys_id" value="{{$object->system_id}}">
                                <input type="hidden" class="prop_id" name="prop_id" value="{{$object->property_id}}">
                                <input type="hidden" class="prog_id" name="prog_id" value="{{$object->id}}">
                                @foreach($categories_with_documents as $category)
                                    @include('shineCompliance.properties.documents.category_document_tab', [
                                                'data' => $category,
                                                'edit_link'=>true,
                                                'edit_modal'=>'property-category-edit'])
                                @endforeach
                            </div>
                        </div>
                        @include('shineCompliance.modals.property_document_add',['color' => 'red',
                                    'modal_id' => 'property-document-add',
                                    'url' => route('shineCompliance.property.post_add.documents'),
                                    'parent_document_types'=>$parent_document_types,
                                    'system_type_id'=>0,
                                    'historical_categories' => $categories_with_documents,
                                    'property_id' => $object->property_id,
                                    'document_types'=>$document_types])
                        @include('shineCompliance.modals.property_document_edit',['color' => 'red',
                            'modal_id' => 'property-document-edit',
                            'parent_document_types'=>$parent_document_types,
                            'system_type_id'=>0,
                            'historical_categories' => $categories_with_documents,
                            'property_id' => $object->property_id,
                            'document_types'=>$document_types])

                        @include('shineCompliance.modals.property_category_add',[
                            'modal_id' => 'property-category-add', 'url' => route('shineCompliance.property.post_add.category'),
                            'property_id' => $object->property_id,'program_id' => $object->id
                            ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            //hide some field for fire document type
            $('.fire-section').hide();
            //hide all error when hidden
            //remove all data + input type file + select
            $("#property-document-edit").on("show.bs.modal", function (e) {
                $('.child-document-type').remove();
                $(this).closest('.modal').find('.boolean_switches').prop('checked',false).hide();
                var that = $(this);
                var url = $(e.relatedTarget).data('url');
                var name = $(e.relatedTarget).data('name');
                var type = $(e.relatedTarget).data('type');
                var type_other = $(e.relatedTarget).data('type_other');
                var parent_type = $(e.relatedTarget).data('parent_type');
                var system_id = $(e.relatedTarget).data('system_id');
                var equipment_id = $(e.relatedTarget).data('equipment_id');
                var programme_id = $(e.relatedTarget).data('programme_id');
                var property_id = $(e.relatedTarget).data('property_id');
                var date = $(e.relatedTarget).data('date');
                var doc_id = $(e.relatedTarget).data('id');
                var category_id = $(e.relatedTarget).data('category_id');
                var compliance_type = $(e.relatedTarget).data('compliance_type');
                var is_identified_acm = $(e.relatedTarget).data('is_identified_acm');
                var is_inaccess_room = $(e.relatedTarget).data('is_inaccess_room');
                var enforcement_deadline = $(e.relatedTarget).data('enforcement_deadline');
                var document_status = $(e.relatedTarget).data('document_status');
                $(that).closest('.modal').find('form').attr('action', url);
                $(that).closest('.modal').find('input[name="name"]').val(name);
                $(that).closest('.modal').find('.doc_type').val(compliance_type);
                $(that).closest('.modal').find('input[name="date"]').val(date);
                $(that).closest('.modal').find('.type_other').val(type_other);
                $(that).closest('.modal').find('.document_id').val(doc_id);
                $(that).closest('.modal').find('select[name="category_id"]').val(category_id);
                $(that).closest('.modal').find('input[name="is_identified_acm"]').prop('checked', is_identified_acm);
                $(that).closest('.modal').find('input[name="is_inaccess_room"]').prop('checked', is_inaccess_room);
                $(that).closest('.modal').find('input[name="enforcement_deadline"]').val(enforcement_deadline);
                $(that).closest('.modal').find('select[name="document_status"]').val(document_status);

                // if($(e.relatedTarget).data('modal-category')){
                //     $(that).closest('.modal').find('select[name="category_id"]').closest('.row').show();
                // } else {
                //     $(that).closest('.modal').find('select[name="category_id"]').closest('.row').hide();
                // }

                if(compliance_type && compliance_type > 0 && $(that).closest('.modal').find('.doc_type').find('option:selected').text() != 'Other'){
                    $(that).closest('.modal').find('.type_other').hide();
                    var clone_select = $(that).closest('.modal').find('.doc_type').clone();
                    $(clone_select).find('option:not(:first)').remove();
                    $(clone_select).attr('class', 'form-control form-require child-document-type mb-3');
                    $.ajax({
                        url: "{{ route('shineCompliance.ajax.post_compliance_document_type') }}",
                        method: 'get',
                        dataType: "json",
                        data: { id : compliance_type},
                        success: function(data){
                            if(data.data){
                                $.each( data.data, function( key, value ) {
                                    let is_selected = type == value.id;
                                    $(clone_select).append($('<option>', {
                                        value: value.id,
                                        text : value.description,
                                        selected:is_selected,
                                        'data-option': value.is_external_ms
                                    }));
                                });
                                $(clone_select).insertAfter($(that).closest('.modal').find('.doc_type'));
                                $(clone_select).trigger('change');
                            }
                        }
                    });
                } else {
                    $(that).closest('.modal').find('.type_other').show();
                }

                if (compliance_type == {!! FIRE_DOCUMENT_TYPE !!}) {
                    $('.fire-section').show();
                } else {
                    $('.fire-section input[name="enforcement_deadline"]').val('');
                    $('.fire-section #document_status').val('');
                    $('.fire-section').hide();
                }

                var that_parent = $(that).closest('.modal').find('.parent_type').val(parent_type);
                //no require then hide all
                $(that_parent).closest('.modal').find('.property_system option:not(:first)').remove();
                $(that_parent).closest('.modal').find('.property_programme option:not(:first)').remove();
                $(that_parent).closest('.modal').find('.property_equipment option:not(:first)').remove();

                //remove option and ajax
                $(that_parent).closest('.modal').find('.property_system').hide();
                $(that_parent).closest('.modal').find('.property_programme').hide();
                $(that_parent).closest('.modal').find('.property_equipment').hide();
                if(parent_type == 2 || parent_type == 3){//2 system, 3 equipment, 4 programme
                    ajaxSystem(that_parent, property_id, system_id);
                    $(that_parent).closest('.modal').find('.property_system').show();
                    if(parent_type == 3){
                        displayAllOption(that_parent, true, system_id);
                        if(system_id >= 0){
                            ajaxEquipment(that_parent, property_id, system_id, equipment_id);
                            ajaxProgramme(that_parent, property_id, system_id, programme_id);
                        }
                        $(that_parent).closest('.modal').find('.property_equipment').show();
                        $(that_parent).closest('.modal').find('.property_programme').show();
                    // } else if(parent_type == 4) {
                    //     displayAllOption(that_parent, true, system_id);
                    //     if(system_id >= 0) {
                    //         ajaxProgramme(that_parent, property_id, system_id, programme_id);
                    //     }
                    //     $(that_parent).closest('.modal').find('.property_programme').show();
                    } else {
                        displayAllOption(that_parent, false);
                        if(system_id >= 0){
                            ajaxProgramme(that_parent, property_id, system_id, programme_id);
                        }
                        $(that_parent).closest('.modal').find('.property_programme').show();
                    }
                }
            });

            //default select System -> this System
            $("#property-document-add").on("show.bs.modal", function (e) {
                var that = $(this);
                var parent_type = 2;//default selected programme
                var system_id = $('.sys_id').val();
                var equipment_id = '';
                var programme_id = $('.prog_id').val();
                var property_id = $('.prop_id').val();

                var that_parent = $(that).closest('.modal').find('.parent_type').val(parent_type);
                //no require then hide all
                $(that_parent).closest('.modal').find('.property_system option:not(:first)').remove();
                $(that_parent).closest('.modal').find('.property_programme option:not(:first)').remove();
                $(that_parent).closest('.modal').find('.property_equipment option:not(:first)').remove();

                //remove option and ajax
                $(that_parent).closest('.modal').find('.property_system').hide();
                $(that_parent).closest('.modal').find('.property_programme').hide();
                $(that_parent).closest('.modal').find('.property_equipment').hide();
                if(parent_type == 2 || parent_type == 3){//2 system, 3 equipment, 4 programme
                    ajaxSystem(that_parent, property_id, system_id);
                    $(that_parent).closest('.modal').find('.property_system').show();
                    if(parent_type == 3){
                        displayAllOption(that_parent, true, system_id);
                        if(system_id >= 0){
                            ajaxEquipment(that_parent, property_id, system_id, equipment_id);
                            ajaxProgramme(that_parent, property_id, system_id, programme_id);
                        }
                        $(that_parent).closest('.modal').find('.property_equipment').show();
                        $(that_parent).closest('.modal').find('.property_programme').show();
                    // } else if(parent_type == 4) {
                    //     displayAllOption(that_parent, true, system_id);
                    //     if(system_id >= 0) {
                    //         ajaxProgramme(that_parent, property_id, system_id, programme_id);
                    //     }
                    //     $(that_parent).closest('.modal').find('.property_programme').show();
                    } else {
                        displayAllOption(that_parent, false);
                        if(system_id >= 0){
                            ajaxProgramme(that_parent, property_id, system_id, programme_id);
                        }
                        $(that_parent).closest('.modal').find('.property_programme').show();
                    }
                }
                var category_id = $(e.relatedTarget).data('modal-category-id');
                //todo show and selected category
                // $(that).closest('.modal').find('select[name="category_id"]').closest('.row').hide();
                $(that).closest('.modal').find('select[name="category_id"]').val(category_id);
                //clear modal
                $(that).closest('.modal').find('.child-document-type').trigger('change');

                var doc_type =  $(that).closest('.modal').find('.doc_type').val();
                if (doc_type == {!! FIRE_DOCUMENT_TYPE !!}) {
                    $('.fire-section').show();
                } else {
                    $('.fire-section input[name="enforcement_deadline"]').val('');
                    $('.fire-section #document_status').val('');
                    $('.fire-section').hide();
                }
            });

            $('body').on('click', '.shine_document_submit', function(e){
                e.preventDefault();
                var that = this;
                var is_error = false;
                var arr_validate = ['.parent_type','.property_system','.property_equipment',
                    'input[name="date"]','input[name="name"]','select[name="type[]"]',
                    'input[name="document"]', 'select[name="document_status"]', 'input[name="enforcement_deadline"]'];
                var array_new_type  = ['{!! FIRE_CALL_OUT !!}', '{!! FIRE_CERTIFICATE_OF_RE_OCCUPATION !!}', '{!! FIRE_REMEDIAL !!}', '{!! FIRE_RISK_ASSESSMENT !!}',
                    '{!! FIRE_SERVICE_RECORD !!}', '{!! FIRE_WORK_ORDER !!}', '{!! FIRE_OTHER !!}'];
                if(array_new_type.includes($(that).closest('form').find('.child-document-type').val())){
                    arr_validate = ['.parent_type','.property_system','.property_equipment',
                        'input[name="date"]','input[name="name"]','select[name="type[]"]',
                        'input[name="document"]'];
                }
                if($(that).closest('form').find('.document_id').val() > 0){
                    var index = arr_validate.indexOf('input[name="document"]');
                    if (index !== -1) {
                        arr_validate.splice(index, 1);
                    }
                }
                $.each(arr_validate, function(k,v){
                    let element = $(that).closest('form').find(v);
                    if(element.length == 1){
                        let val = $(element).val();
                        let visible = $(element).is(":visible");
                        if(visible && !val){
                            is_error = true;
                            $(element).addClass('is-invalid');
                            $(element).closest('.form-group').find('span strong').html('This field can not be empty.');
                        } else {
                            $(element).removeClass('is-invalid');
                            $(element).closest('.form-group').find('span strong').html('');
                        }
                    } else {
                        $.each(element, function(k, element2){
                            let val = $(element2).val();
                            let visible = $(element2).is(":visible");
                            if(visible && !val){
                                is_error = true;
                                $(element2).addClass('is-invalid');
                                $(element2).closest('.form-group').find('span strong').html('This field can not be empty.');
                            } else {
                                $(element2).removeClass('is-invalid');
                                $(element2).closest('.form-group').find('span strong').html('');
                            }
                        });
                    }
                });
                if(!is_error){  //send request
                    $(that).closest('form').submit();
                }
            });

            //todo merge into parent document + check type
            $('body').on('change', '.doc_type', function(){
                var val = $(this).val();
                var that = $(this);
                //remove option and ajax
                $('.child-document-type').remove();
                $(this).closest('.modal').find('.boolean_switches').prop('checked',false).hide();
                if(val && val > 0 && $(this).find('option:selected').text() != 'Other'){
                    var clone_select = $(this).clone();
                    $(clone_select).find('option:not(:first)').remove();
                    $(clone_select).attr('class', 'form-control form-require child-document-type mb-3');
                    $.ajax({
                        url: "{{ route('shineCompliance.ajax.post_compliance_document_type') }}",
                        method: 'get',
                        dataType: "json",
                        data: { id : val},
                        success: function(data){
                            if(data.data){
                                $.each( data.data, function( key, value ) {
                                    $(clone_select).append($('<option>', {
                                        value: value.id,
                                        text : value.description,
                                        'data-option': value.is_external_ms
                                    }));
                                });
                                $(clone_select).insertAfter(that);
                                // $(clone_select).trigger('change');
                            }
                        }
                    });
                }
                if (val == {!! FIRE_DOCUMENT_TYPE !!}) {
                    $('.fire-section').show();
                } else {
                    $('.fire-section input[name="enforcement_deadline"]').val('');
                    $('.fire-section #document_status').val('');
                    $('.fire-section').hide();
                }
            });

            $('body').on('change', '.child-document-type', function(){
                var is_extend_ms = $(this).find('option:selected').data('option');
                var text = $(this).find('option:selected').text();
                if($(this).closest('.modal').find('.is_external_ms').length){
                    if(is_extend_ms == 1 || is_extend_ms == 2 || text == 'Asbestos Refurbishment Survey'){
                        $(this).closest('.modal').find('.boolean_switches').show();
                    } else {
                        $(this).closest('.modal').find('.boolean_switches').hide();
                    }
                } else {
                    if(is_extend_ms == 1 || is_extend_ms == 2 || text == 'Asbestos Refurbishment Survey'){
                        $(this).closest('.modal-content').find('.boolean_switches').show();
                    } else {
                        $(this).closest('.modal-content').find('.boolean_switches').hide();
                    }
                }
                var array_new_type  = ['{!! FIRE_CALL_OUT !!}', '{!! FIRE_CERTIFICATE_OF_RE_OCCUPATION !!}', '{!! FIRE_REMEDIAL !!}', '{!! FIRE_RISK_ASSESSMENT !!}',
                    '{!! FIRE_SERVICE_RECORD !!}', '{!! FIRE_WORK_ORDER !!}', '{!! FIRE_OTHER !!}'];
                if($(this).closest('.form-group').find('.doc_type').val() == {!! FIRE_DOCUMENT_TYPE !!}){
                    if( array_new_type.includes($(this).val()) ) {
                        $(this).closest('.modal').find('.fire-section').hide();
                        $(this).closest('.modal').find('input[name="enforcement_deadline"]').val('');
                        $(this).closest('.modal').find('#document_status').val('');
                    } else {
                        $(this).closest('.modal').find('.fire-section').show();
                    }
                }
            });

            $('body').on('change', '.parent_type', function(){
                var val = $(this).val();
                var property_id = $(this).closest('form').find('.property_id').val();
                var that = $(this);
                //no require then hide all
                $(this).closest('.row').find('.property_system option:not(:first)').remove();
                $(this).closest('.row').find('.property_programme option:not(:first)').remove();
                $(this).closest('.row').find('.property_equipment option:not(:first)').remove();

                //remove option and ajax
                $(this).closest('.row').find('.property_system').hide();
                $(this).closest('.row').find('.property_programme').hide();
                $(this).closest('.row').find('.property_equipment').hide();
                if(val == 2 || val == 3){//2 system, 3 equipment, 4 programme
                    ajaxSystem(that, property_id);
                    $(this).closest('.row').find('.property_system').show();
                    var system_id =  $(this).closest('.row').find('.property_system').val();
                    if(val == 3){
                        displayAllOption(that, true);
                        if(system_id.length > 0){
                            ajaxEquipment(that, property_id, system_id);
                            ajaxProgramme(that, property_id, system_id);
                        }
                        $(this).closest('.row').find('.property_equipment').show();
                        $(this).closest('.row').find('.property_programme').show();
                    // } else if(val == 4) {
                    //     displayAllOption(that, true);
                    //     if(system_id.length > 0) {
                    //         ajaxProgramme(that, property_id, system_id);
                    //     }
                    //     $(this).closest('.row').find('.property_programme').show();
                    } else {
                        displayAllOption(that, false);
                        if(system_id.length > 0){
                            ajaxProgramme(that, property_id, system_id);
                        }
                        $(this).closest('.row').find('.property_programme').show();
                    }
                }
            });

            $('body').on('change', '.property_system', function(){
                //change system show programme
                var val = $(this).val();
                var property_id = $(this).closest('form').find('.property_id').val();
                var that = $(this);
                $(this).closest('.row').find('.property_programme option:not(:first)').remove();
                $(this).closest('.row').find('.property_equipment option:not(:first)').remove();
                console.log(val >= 0, 222);
                if(val.length > 0){
                    var parent_type = $(this).closest('form').find('.parent_type').val();
                    if(parent_type == 3){
                        ajaxEquipment(that, property_id, val);
                        ajaxProgramme(that, property_id, val);
                        // } else if(parent_type == 4){
                        //     ajaxProgramme(that, property_id, val);
                    } else {
                        ajaxProgramme(that, property_id, val);
                    }
                }
            });

            //for display all option in system
            function displayAllOption(that, is_display, default_id){
                if(is_display){
                    $(that).closest('.row').find('.property_system').append($('<option>', {
                        value: 0,
                        text : 'All',
                        selected : default_id == 0
                    }));
                } else {
                    $(that).closest('.row').find('.property_system option[value=0]').remove();
                }
            }
            function ajaxSystem(that, property_id, default_id){
                $('#overlay').fadeIn();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $(that).closest('form').find('meta[name="csrf-token"]').attr('content'),
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "{{ route('shineCompliance.ajax.post_compliance_system') }}",
                    data: {
                        property_id : property_id
                    },
                    cache: false,
                    success: function (html) {
                        $('#overlay').fadeOut();
                        $.each( html, function( key, value ) {
                            selected = value.id == default_id;
                            $(that).closest('.row').find('.property_system').append($('<option>', {
                                value: value.id,
                                text : value.name,
                                selected : selected
                            }));
                        });
                    }
                });
            }

            function ajaxProgramme(that,property_id,system_id, default_id){
                $('#overlay').fadeIn();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $(that).closest('form').find('meta[name="csrf-token"]').attr('content'),
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "{{ route('shineCompliance.ajax.post_compliance_programme') }}",
                    data: {
                        property_id : property_id,
                        system_id : system_id
                    },
                    cache: false,
                    success: function (html) {
                        $('#overlay').fadeOut();
                        $.each( html, function( key, value ) {
                            selected = value.id == default_id;
                            $(that).closest('.row').find('.property_programme').append($('<option>', {
                                value: value.id,
                                text : value.name,
                                selected : selected
                            }));
                        });
                    }
                });
            }

            function ajaxEquipment(that,property_id,system_id, default_id){
                $('#overlay').fadeIn();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $(that).closest('form').find('meta[name="csrf-token"]').attr('content'),
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "{{ route('shineCompliance.ajax.post_compliance_equiment') }}",
                    data: {
                        property_id : property_id,
                        system_id : system_id
                    },
                    cache: false,
                    success: function (html) {
                        $('#overlay').fadeOut();
                        $.each( html, function( key, value ) {
                            selected = value.id == default_id;
                            $(that).closest('.row').find('.property_equipment').append($('<option>', {
                                value: value.id,
                                text : value.name,
                                selected : selected
                            }));
                        });
                    }
                });
            }

            $('body').on('click', '.shine_category_submit', function(e){
                e.preventDefault();
                var that = this;
                var is_error = false;
                var arr_validate = ['input[name="category_title"]'];
                $.each(arr_validate, function(k,v){
                    let element = $(that).closest('form').find(v);
                    let val = $(element).val();
                    let visible = $(element).is(":visible");
                    if(visible && !val){
                        is_error = true;
                        $(element).addClass('is-invalid');
                        $(element).closest('.form-group').find('span strong').html('This field can not be empty.');
                    } else {
                        $(element).removeClass('is-invalid');
                        $(element).closest('.form-group').find('span strong').html('');
                    }
                });
                console.log(is_error, 444);
                if(!is_error){  //send request
                    $(that).closest('form').submit();
                }
            });
        });
    </script>
@endpush

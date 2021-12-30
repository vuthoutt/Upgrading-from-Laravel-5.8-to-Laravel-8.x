@extends('shineCompliance.layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'job_role','data' => ''])

<div class="container prism-content">
    <h3 class="ml-2">
        Edit {{$job_role->name}} Privileges
    </h3>
    <div class="main-content">
        <input type="hidden" name="job_role_id" id="job_role_id" value="{{$job_role->id}}">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active role_nav" id="view-tab" data-tab="{{VIEW_TAB}}" data-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="true"><strong>View Privileges</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link role_nav" id="update-tab" data-tab="{{UPDATE_TAB}}" data-toggle="tab" href="#update" role="tab" aria-controls="update" aria-selected="false"><strong>Add/Edit/Decommission Privileges</strong></a>
            </li>
        </ul>
        <div class="tab-content mt-3 " id="myTabContent">
            <div class="tab-pane fade show active" id="view" role="tabpanel" aria-labelledby="view-tab">
                <div class="row">
                    <div class="col-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            @if(!$privilege_view->isEmpty())
                                <a class="nav-link active list-group-item list-group-item-action bg-light everything-group" id="v-pills-everything-tab" data-toggle="pill" href="#v-pills-everything" role="tab" aria-controls="v-pills-everything" aria-selected="false">Everything</a>
                                @foreach($privilege_view as $nav)
                                    <a class="nav-link list-group-item list-group-item-action bg-light" id="v-{{$nav->id}}-view-tab" data-toggle="pill" href="#v-{{$nav->id}}-pills-view" role="tab" aria-controls="v-{{$nav->id}}-pills-view" aria-selected="true" style="{{$data_view->is_everything == 1 ? 'display:none' : ''}}">{{$nav->name}}</a>
                                @endforeach
                                <a class="nav-link list-group-item list-group-item-action bg-light operative-group" id="v-pills-operative-tab" data-toggle="pill" href="#v-pills-operative" role="tab" aria-controls="v-pills-operative" aria-selected="false">Site Operative View</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            @if(!$privilege_view->isEmpty())
                                <div class="tab-pane fade show active" id="v-pills-everything" role="tabpanel" aria-labelledby="v-pills-everything-tab">
                                    @include('forms.form_checkbox',['title' => 'Check all:', 'data' => 1,'compare' => $data_view->is_everything, 'class'=>'everything-role', 'name' => 'everything-view' ])
                                </div>
                                @foreach($privilege_view as $nav)
                                    <div class="tab-pane fade " id="v-{{$nav->id}}-pills-view" role="tabpanel" aria-labelledby="v-{{$nav->id}}-pills-view-tab">
                                        <!-- bstree here inside partial privilege view-->
                                        <input type="hidden" class="privilege_parent" name="list_data_view_{{$nav->id}}[]">
                                        <input id="bstree-data-{{$nav->id}}" class="bstree-input-privilege" type="hidden" name="bstree-data-{{$nav->id}}" data-ancestors="">
                                        <div id="mytree-{{$nav->id}}" class="bstree bstree-privilege">
                                            <ul>
                                                <li data-id="root-{{$nav->id}}" data-root="1" data-level="0"><span>Check all below</span>
                                                    @include('resources.partial_privilege',['data' => $nav,'level' => 0, 'prefix' => '', 'array_check_zone' => $data_view->list_checked_zone, 'data_normal' => $data_view->privilige, 'data_role' => $data_view, 'type' => JOB_ROLE_VIEW])
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="tab-pane fade" id="v-pills-operative" role="tabpanel" aria-labelledby="v-pills-operative-tab">
                                    @include('forms.form_checkbox',['title' => 'Site Operative View:', 'data' => 1,'compare' => $data_view->is_operative, 'class'=>'operative-role', 'name' => 'operative-view' ])
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="update" role="tabpanel" aria-labelledby="update-tab">
                <div class="row">
                    <div class="col-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab-update" role="tablist" aria-orientation="vertical">
                            @if(!$privilege_update->isEmpty())
                                <a class="nav-link active list-group-item list-group-item-action bg-light everything-group" id="v-pills-update-tab" data-toggle="pill" href="#v-pills-everything-update" role="tab" aria-controls="v-pills-everything-update" aria-selected="false">Everything</a>
                                @foreach($privilege_update as $nav)
                                    <a class="nav-link list-group-item list-group-item-action bg-light" id="v-{{$nav->id}}-update-tab" data-toggle="pill" href="#v-{{$nav->id}}-pills-update" role="tab" aria-controls="v-{{$nav->id}}-pills-update" aria-selected="true"  style="{{$data_update->is_everything == 1 ? 'display:none' : ''}}">{{$nav->name}}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            @if(!$privilege_update->isEmpty())
                                <div class="tab-pane active" id="v-pills-everything-update" role="tabpanel" aria-labelledby="v-pills-update-tab">
                                    @include('forms.form_checkbox',['title' => 'Check all:', 'data' => 1,'compare' => $data_update->is_everything, 'class'=>'everything-role','name' => 'everything-update' ])
                                </div>
                                @foreach($privilege_update as $nav)
                                    <div class="tab-pane" id="v-{{$nav->id}}-pills-update" role="tabpanel" aria-labelledby="v-{{$nav->id}}-pills-update-tab">
                                        <!-- bstree here inside partial privilege view-->
                                        <input type="hidden" class="privilege_parent" name="list_data_update_{{$nav->id}}[]">
                                        <input id="bstree-data-update-{{$nav->id}}" class="bstree-input-privilege" type="hidden" name="bstree-data-update-{{$nav->id}}" data-ancestors="">
                                        <div id="mytree-update-{{$nav->id}}" class="bstree bstree-privilege">
                                            <ul>
                                                <li data-id="root-update-{{$nav->id}}" data-level="0"><span>Check all below</span>
                                                    @include('resources.partial_privilege',['data' => $nav,'level' => 0, 'prefix' => '-update', 'array_check_zone' => $data_update->list_checked_zone, 'data_role' => $data_update, 'data_normal' => $data_update->privilige,'type' => JOB_ROLE_UPDATE])
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="parent-property-modal">
            <div class="modal fade" id="property-listing-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header red_gradient">
                            <h5 class="modal-title" id="exampleModalLabel">Property Selection</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" class="form-shine" action="{{route('ajax_save_property_role')}}">
                            @csrf
                            <input type="hidden" name="group_id" id="group_id" value="">
                            <input type="hidden" name="tab" id="tab-active" value="">
                            <input type="hidden" name="role_id" id="role_id" value="{{isset($role_id) ? $role_id : ''}}">
                            <div class="modal-body" style="padding-left: 35px">

                                @include('modals.list_property_job_role',['data'=> NULL, 'has_full_checked_propery' => false])

                            </div>
                            <div class="mb-4" style="text-align: center;">
                                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn light_grey_gradient" id="add-all-property">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

            <form id="form_save_role" action="{{route('save_role')}}" method="POST">
                @csrf
                <input type="hidden" name="view_is_everything" />
                <input type="hidden" name="view_is_operative" />
                <input type="hidden" name="update_is_everything" />
                <input type="hidden" name="view_privilege_data" />
                <input type="hidden" name="project_type_view" />
                <input type="hidden" name="project_info_view" />
                <input type="hidden" name="organisation_view" />
                <input type="hidden" name="reporting_view" />
                <input type="hidden" name="category_view" />
                <input type="hidden" name="update_privilege_data" />
                <input type="hidden" name="project_type_update" />
                <input type="hidden" name="project_info_update" />
                <input type="hidden" name="organisation_update" />
                <input type="hidden" name="category_update" />
                <input type="hidden" name="data_center_update" />
                <input type="hidden" name="role_save_id" id="role_save_id" value="{{isset($role_id) ? $role_id : ''}}">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-9">
                        <button type="button" id="submit_role" class="btn light_grey_gradient">
                            <strong>Save</strong>
                        </button>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection
@push('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        // need test using class to bstree all or need to set one by one ?
        $hiddenInput = $('.bstree-input-privilege');
        $('.bstree-privilege').bstree({
            dataSource: $hiddenInput,
            isExpanded:true,
            initValues: $hiddenInput.data('ancestors'),
            onDataPush: function (values) {
                return;
                var def = '<strong class="pull-left">Values,&nbsp;</strong>'
                for (var i in values) {
                    def += '<span class="pull-left">' + values[i] + '&nbsp;</span>'
                }
                $('#status').html(def)
            },
            updateNodeTitle: function (node, title) {
            }
        });

        if($('.get-propery-role').data('url')) {
            var table = $('#property-dt-table').DataTable({
                'ajax': {
                    'url': $('.get-propery-role').data('url')
                },
                "lengthChange": false,
                "ordering": false,
                pageLength: 10,
                bInfo: false,
                "pagingType": "full_numbers",
                'columnDefs': [{
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta) {
                        //need check checkboxes are checked
                        var is_checked = '';
                        if (full[2]) {
                            is_checked = 'checked';
                        }
                        // console.log(data);
                        // console.log(full[2]);
                        // console.log(is_checked);
                        // console.log(full);
                        // console.log(meta);
                        return '<input type="checkbox" name="list_property[]" value="' + $('<div/>').text(data).html() + '"' + is_checked + '>';
                    }
                }],
                'order': [[1, 'asc']]
            });
        }


        $('body').on('click','.edit-group',function(e){
            // alert(123);
            e.preventDefault();
            var group_id = $(this).find('a').data('group');
            var id = $(this).closest('li').data('id');
            var check_group = $(this).closest('li').find("input[id='bstree-checkbox-" + $(this).closest('li').data('id') + "']:first");
            if(check_group.length && !check_group.prop('checked')){
                //need checked this group because when unchecked the group, all properties inside will be removed in db
                check_group.prop('checked',true);
            }
            var url = $('.get-propery-role').data('url')+'?group_id='+group_id+'&role_id='+$('#job_role_id').val()+'&tab='+$('body').find('.role_nav.active').data('tab');
            if(url && group_id){
                //set value for save later
                $('#group_id').val(group_id);
                $('#tab-active').val($('body').find('.role_nav.active').data('tab'));
                table.ajax.url(url).load();
                // table.draw();
                console.log('done');
                //         // open modal
                $('#property-listing-modal').modal('show')
                //     }
                // });
            }
        });

        // Handle click on "Select all" control
        $('#checkbox-select-all').on('click', function(){
            // Get all rows with search applied
            var rows = table.rows({ 'search': 'applied' }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        //set indeterminate state for select all input
        $('#property-dt-table tbody').on('change', 'input[type="checkbox"]', function(){
            // If checkbox is not checked
            if(!this.checked){
                var el = $('#checkbox-select-all').get(0);
                // If "Select all" control is checked and has 'indeterminate' property
                if(el && el.checked && ('indeterminate' in el)){
                    // Set visual state of "Select all" control
                    // as 'indeterminate'
                    el.indeterminate = true;
                }
            }
        });
        //for click save button
        $('body').on('click','#add-all-property', function(){
            var url = $(this).closest('form').attr('action');
            // $('#tab-active').val($('.role_nav .active').data('tab'));
            // create checked checkbox elements to submit
            var form = $(this).closest('form');
            // Iterate over all checkboxes in the table
            $('body').find('.temp_class').remove();
            table.$('input[type="checkbox"]').each(function(){
                // If checkbox doesn't exist in DOM
                if(!$.contains(document, this)){
                    // If checkbox is checked
                    if(this.checked){
                        // Create a hidden element
                        $(form).append(
                            $('<input>')
                                .attr('type', 'hidden')
                                .attr('name', this.name)
                                .attr('class', 'temp_class')
                                .val(this.value)
                        );
                    }
                }
            });

            if(url){
                $.ajax
                ({
                    type: "POST",
                    url: url,
                    data: $(this).closest('form').serialize(),
                    cache: false,
                    success: function (result) {
                        // close modal and show successfully
                    }
                });
            }
            $('#property-listing-modal').modal('hide')
            // close modal
        });

        $('body').on('click','#bstree-checkbox-child-42, #bstree-checkbox-child-update-29', function(){
           //click check all properties need to hide Edit btn to prevent open popup-property
            console.log($(this).prop('checked'));
            if($(this).prop('checked')){
                console.log($(this).closest('.bstree-children'));
                console.log($(this).closest('.bstree-children').find('.edit-group'));
                var list_edit_btn = $(this).closest('.bstree-children').find('.edit-group');
                $.each(list_edit_btn, function (k,v){
                    $(v).addClass('d-none');//class hide of boostrap
                })
            } else {
                //enable
                var list_edit_btn = $(this).closest('.bstree-children').find('.edit-group');
                $.each(list_edit_btn, function (k,v){
                    $(v).removeClass('d-none');
                })
            }
        });

        //for click submit both view/update page
        $('body').on('click','#submit_role', function(){
            var view_parent_id = '#view';
            var update_parent_id = '#update';
            //for view data
            var eveything_view, operative_view = 0;
            var view_privilege_data = getCheckedNode('normal', view_parent_id);
            var project_type_view = getCheckedNode('project-type', view_parent_id);
            var project_info_view = getCheckedNode('project-info', view_parent_id);
            var organisation_view = getDataContractor(view_parent_id);
            var reporting_view = getCheckedNode('summary', view_parent_id);
            var category_view =  getCheckedNode('template-child', view_parent_id);

            var eveything_update = 0;
            var update_privilege_data = getCheckedNode('normal', update_parent_id);
            var project_type_update = getCheckedNode('project-type', update_parent_id);
            var project_info_update = getCheckedNode('project-info', update_parent_id);
            var organisation_update = getDataContractor(update_parent_id);
            var category_update =  getCheckedNode('template-child', update_parent_id);
            var data_center_update =  getCheckedNode('document-approval', update_parent_id);

            if($('input[name="everything-view"]').prop('checked')){
                eveything_view = 1;
            }
            if($('input[name="everything-update"]').prop('checked')){
                eveything_update = 1;
            }
            if($('input[name="operative-view"]').prop('checked')){
                operative_view = 1;
            } else {
                operative_view = 0;
            }

            $("input[name='view_is_everything']").val(eveything_view);
            $("input[name='view_is_operative']").val(operative_view);
            $("input[name='update_is_everything']").val(eveything_update);
            $("input[name='view_privilege_data']").val(view_privilege_data);
            $("input[name='project_type_view']").val(project_type_view);
            $("input[name='project_info_view']").val(project_info_view);
            $("input[name='organisation_view']").val(organisation_view);
            $("input[name='reporting_view']").val(reporting_view);
            $("input[name='category_view']").val(category_view);
            $("input[name='update_privilege_data']").val(update_privilege_data);
            $("input[name='project_type_update']").val(project_type_update);
            $("input[name='project_info_update']").val(project_info_update);
            $("input[name='organisation_update']").val(organisation_update);
            $("input[name='category_update']").val(category_update);
            $("input[name='data_center_update']").val(data_center_update);
            $('#form_save_role')[0].submit();
        });
        //for click check all roles
        $('body').on('click','.everything-role', function(){
            var set_checked = $(this).prop('checked');
            var tab_id = $('body').find('.role_nav.active').attr('href');
            var parent = $(tab_id);
            if(set_checked){
                $(parent).find('.everything-group').siblings().not(".operative-group").hide();
                // to hide other tabs

                //to checked all everything other tab
                // var list_root = $(parent).find('[data-root=1]');
                // // console.log(tab_id);
                // // console.log(list_root);
                // // return;
                // $.each(list_root, function(k,v){
                //     var id = $(v).data('id');
                //     var checkbox = $(v).find("input[id='bstree-checkbox-" + id + "']:first");
                //     if(checkbox.length && (!$(checkbox).prop('checked') || $(checkbox).prop('indeterminate'))){
                //         // console.log(checkbox);
                //         // console.log($(checkbox).prop('indeterminate'));
                //         if($(checkbox).prop('indeterminate')){
                //             $(checkbox).prop('checked', false);
                //         }
                //         $(checkbox).trigger('click');
                //     }
                // })
            } else {
                $(parent).find('.everything-group').siblings().not(".operative-group").show();
            }
        });
        removeInputLocked();
    });

    function removeInputLocked(){
        var li_list = $('.bstree-privilege').find("[data-checked=1]");
        $.each(li_list, function(k,v){
            var id = $(v).data('id');
            // var level = $(v).data('level');
            var is_has_child = $(v).find('li');
            if(id && !is_has_child.length){
                //only check child
                $(v).find("input[id='bstree-checkbox-" + id + "']:first").trigger('click');
            }
            // // console.log($(v).find("input[id='bstree-checkbox-" + id + "']:first"));
            // $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked',true);
        })
    }
    //get data for contractor
    function getDataContractor(parent_id){
        var my_organisation = {};
        //[1=>[2,3,4], 2=>[3.45.6]]
        var li_list = $(parent_id).find(".organisation_child");
        $.each(li_list, function(k,v){
            var check = false;
            var id = $(v).data('id');
            if (id) {
                check = $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked');
            }
            if (check) {
                var client_id = $(v).data('parent');
                if(!my_organisation[client_id]){
                    my_organisation[client_id] = new Array();
                }
                // my_organisation.push({1:$(v).data('value')});
                my_organisation[client_id].push($(v).data('value'));
            }
        });
        if(!$.isEmptyObject(my_organisation)){
            return JSON.stringify(my_organisation);
        }
        return null;
    }
    // normal and dynamic types as project, project information
    function getCheckedNode(type, parent_id) {
        var arr_result = [];
        if (type) {
            var li_list = $(parent_id).find("[data-" + type + "=1]");
            $.each(li_list, function (k, v) {
                var check = false;
                var id = $(v).data('id');
                if (id) {
                    check = $(v).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked');
                }
                if (check) {
                    arr_result.push($(v).data('value'));
                }
            });
            return arr_result;
        }
    }
</script>
@endpush

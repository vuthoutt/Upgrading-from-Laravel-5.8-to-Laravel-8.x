@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'job_role_detail','data' => $job_role])
    <div class="container prism-content">
        <h3 class="ml-2">
            Edit {{$job_role->name}} Privileges
        </h3>
        <div class="row mr-bt-top">
            <div class="full-width">
                <div class="form-button-search" >
                    <a href="{{route('shineCompliance.get_job_role.compliance',['id' => $job_role->id, 'type' => JOB_ROLE_GENERAL]) }}" style="text-decoration: none">
                        <button type="submit" class="fs-8pt btn shine-compliance-button {{  $type == JOB_ROLE_GENERAL ?  'overall' : ''  }}">
                            <strong>{{ __('General') }}</strong>
                        </button>
                    </a>
                    <a href="{{route('shineCompliance.get_job_role.compliance',['id' => $job_role->id, 'type' => JOB_ROLE_ASBESTOS]) }}" style="text-decoration: none">
                        <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == JOB_ROLE_ASBESTOS ?  ASBESTOS : '' }}" >
                            <strong>{{ __('Asbestos') }}</strong>
                        </button>
                    </a>
                    <a href="{{route('shineCompliance.get_job_role.compliance',['id' => $job_role->id, 'type' => JOB_ROLE_FIRE]) }}" style="text-decoration: none">
                        <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == JOB_ROLE_FIRE ?  FIRE : '' }}">
                            <strong>{{ __('Fire') }}</strong>
                        </button>
                    </a>
{{--                     <a href="{{route('shineCompliance.get_job_role.compliance',['id' => $job_role->id, 'type' => JOB_ROLE_GAS]) }}" style="text-decoration: none">
                        <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == JOB_ROLE_GAS ?  GAS : '' }}">
                            <strong>{{ __('Gas') }}</strong>
                        </button>
                    </a> --}}
{{--                     <a href="{{route('shineCompliance.get_job_role.compliance',['id' => $job_role->id, 'type' => JOB_ROLE_ME]) }}" style="text-decoration: none">
                        <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == JOB_ROLE_ME ?  ME : '' }}">
                            <strong>{{ __('M&E') }}</strong>
                        </button>
                    </a> --}}
{{--                    <a href="{{route('shineCompliance.get_job_role.compliance',['id' => $job_role->id, 'type' => JOB_ROLE_RCF]) }}" style="text-decoration: none">--}}
{{--                        <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == JOB_ROLE_RCF ?  RCF : '' }}">--}}
{{--                            <strong>{{ __('RCF') }}</strong>--}}
{{--                        </button>--}}
{{--                    </a>--}}
                    @if(env('WATER_MODULE'))
                    <a href="{{route('shineCompliance.get_job_role.compliance',['id' => $job_role->id, 'type' => JOB_ROLE_WATER]) }}" style="text-decoration: none">
                        <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == JOB_ROLE_WATER ?  WATER : '' }}">
                            <strong>{{ __('Water') }}</strong>
                        </button>
                    </a>
                    @endif
                    <a href="{{route('shineCompliance.get_job_role.compliance',['id' => $job_role->id, 'type' => JOB_ROLE_H_S]) }}" style="text-decoration: none">
                        <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == JOB_ROLE_H_S ?  'hs' : '' }}">
                            <strong>{{ __('H&S') }}</strong>
                        </button>
                    </a>
                </div>
            </div>
        </div>
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
                                        <a class="nav-link list-group-item list-group-item-action bg-light" id="v-{{$nav->id}}-view-tab" data-toggle="pill" href="#v-{{$nav->id}}-pills-view" role="tab" aria-controls="v-{{$nav->id}}-pills-view" aria-selected="true" style="{{$job_role->getCommonEverythingViewByType($type) == 1 && !in_array($nav->id, [JR_GENERAL_SITE_OPERATIVE, JR_GENERAL_TROUBLE_TICKET]) ? 'display:none' : ''}}" >{{$nav->name}}</a>
                                    @endforeach
                                @endif
{{--                                @if($type == JOB_ROLE_GENERAL)--}}
{{--                                    <a class="nav-link list-group-item list-group-item-action bg-light operative-group" id="v-pills-operative-tab" data-toggle="pill" href="#v-pills-operative" role="tab" aria-controls="v-pills-operative" aria-selected="false">Site Operative View</a>--}}
{{--                                    <a class="nav-link list-group-item list-group-item-action bg-light tt-group" id="v-pills-tt" data-toggle="pill" href="#v-pills-tt" role="tab" aria-controls="v-pills-tt" aria-selected="false" style="">View Trouble Ticket Icon</a>--}}
{{--                                @endif--}}
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                @if(!$privilege_view->isEmpty())
                                    <div class="tab-pane fade show active" id="v-pills-everything" role="tabpanel" aria-labelledby="v-pills-everything-tab">
                                        @include('shineCompliance.forms.form_checkbox',['title' => 'Check all:', 'data' => 1,'compare' => $job_role->getCommonEverythingViewByType($type), 'class'=>'everything-role', 'name' => 'everything-view' ])
                                    </div>
                                    @foreach($privilege_view as $nav)
                                        <div class="tab-pane fade " id="v-{{$nav->id}}-pills-view" role="tabpanel" aria-labelledby="v-{{$nav->id}}-pills-view-tab">
                                            <!-- bstree here inside partial privilege view-->
                                            <input type="hidden" class="privilege_parent" name="list_data_view_{{$nav->id}}[]">
                                            <input id="bstree-data-{{$nav->id}}" class="bstree-input-privilege" type="hidden" name="bstree-data-{{$nav->id}}" data-ancestors="">
                                            @if(!in_array($nav->id, [JR_GENERAL_SITE_OPERATIVE,JR_GENERAL_TROUBLE_TICKET]))
                                                <div id="mytree-{{$nav->id}}" class="bstree bstree-privilege">
                                                    <ul>
                                                        <li data-id="root-{{$nav->id}}" data-root="1" data-level="0"><span>Check all below</span>
                                                            @include('shineCompliance.resources.job_role.partial_privilege',['data' => $nav,'level' => 0, 'prefix' => '', 'data_normal' => $job_role->getStaticViewValue($type), 'type' => JOB_ROLE_VIEW, 'tab' => $type])
                                                        </li>
                                                    </ul>
                                                </div>
                                                @if(in_array($nav->id, [JR_GENERAL_CLIENT_LISTING_VIEW]))
                                                    @include('shineCompliance.resources.job_role.partial_client_listing_view', ['data' => $nav,'level' => 0, 'prefix' => 'client-property-view', 'type' => JOB_ROLE_VIEW, 'checked_array' => $job_role->getGeneralValueViewByType($type, 'group'), 'tab' => $type])
                                                @endif
                                                @if(in_array($nav->id, [JR_GENERAL_ORGANISATION_LISTING_VIEW]))
                                                    @include('shineCompliance.resources.job_role.partial_organisation_listing_view', ['data' => $nav,'level' => 0, 'prefix' => 'organisation-view', 'type' => JOB_ROLE_VIEW, 'tab' => $type])
                                                @endif
                                            @else
                                                @if(in_array($nav->id, [JR_GENERAL_SITE_OPERATIVE]))
                                                    @include('forms.form_checkbox',['title' => 'Site Operative View:', 'data' => 1, 'class'=>'operative-role', 'name' => 'operative-view', 'compare' => $job_role->jobRoleViewValue->general_is_operative ?? 0])
                                                @elseif(in_array($nav->id, [JR_GENERAL_TROUBLE_TICKET]))
                                                    @include('forms.form_checkbox',['title' => 'View Trouble Ticket Icon:', 'data' => 1, 'class'=>'tt-role', 'name' => 'tt-view', 'compare' => $job_role->jobRoleViewValue->general_is_tt ?? 0])
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
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
                                        <a class="nav-link list-group-item list-group-item-action bg-light" id="v-{{$nav->id}}-update-tab" data-toggle="pill" href="#v-{{$nav->id}}-pills-update" role="tab" aria-controls="v-{{$nav->id}}-pills-update" aria-selected="true" style="{{$job_role->getCommonEverythingUpdateByType($type) == 1 ? 'display:none' : ''}}">{{$nav->name}}</a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                @if(!$privilege_update->isEmpty())
                                    <div class="tab-pane active" id="v-pills-everything-update" role="tabpanel" aria-labelledby="v-pills-update-tab">
                                        @include('shineCompliance.forms.form_checkbox',['title' => 'Check all:', 'data' => 1,'compare' => $job_role->getCommonEverythingUpdateByType($type), 'class'=>'everything-role','name' => 'everything-update' ])
                                    </div>
                                    @foreach($privilege_update as $nav)
                                        <div class="tab-pane" id="v-{{$nav->id}}-pills-update" role="tabpanel" aria-labelledby="v-{{$nav->id}}-pills-update-tab">
                                            <!-- bstree here inside partial privilege view-->
                                            <input type="hidden" class="privilege_parent" name="list_data_update_{{$nav->id}}[]">
                                            <input id="bstree-data-update-{{$nav->id}}" class="bstree-input-privilege" type="hidden" name="bstree-data-update-{{$nav->id}}" data-ancestors="">
                                            <div id="mytree-update-{{$nav->id}}" class="bstree bstree-privilege">
                                                <ul>
                                                    <li data-id="root-update-{{$nav->id}}" data-level="0"><span>Check all below</span>
                                                        @include('shineCompliance.resources.job_role.partial_privilege',['data' => $nav,'level' => 0, 'prefix' => '-update', 'data_normal' => $job_role->getStaticEditValue($type),'type' => JOB_ROLE_UPDATE, 'tab' => $type])
                                                    </li>
                                                </ul>
                                            </div>
                                            @if(in_array($nav->id, [JR_GENERAL_CLIENT_LISTING_UPDATE]))
                                                @include('shineCompliance.resources.job_role.partial_client_listing_view', ['data' => $nav,'level' => 0, 'prefix' => 'client-property-view', 'type' => JOB_ROLE_UPDATE, 'checked_array' => $job_role->getGeneralValueViewByType($type, 'group'), 'tab' => $type])
                                            @endif
                                            @if(in_array($nav->id, [JR_GENERAL_ORGANISATION_LISTING_UPDATE]))
                                                @include('shineCompliance.resources.job_role.partial_organisation_listing_view', ['data' => $nav,'level' => 0, 'prefix' => 'organisation-view', 'type' => JOB_ROLE_UPDATE, 'tab' => $type])
                                            @endif
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
                            <form method="POST" class="form-shine" action="{{route('shineCompliance.ajax_save_property_role.compliance')}}">
                                @csrf
                                <input type="hidden" name="group_id" id="group_id" value="">
                                <input type="hidden" name="role_id" value="{{$job_role->id ?? 0}}">
                                <input type="hidden" name="tab" id="tab-active" value="">
                                <div class="modal-body" style="padding-left: 35px">

                                    @include('shineCompliance.modals.list_property_job_role',['data'=> NULL, 'has_full_checked_propery' => false])

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

            <form id="form_save_role" action="{{route('shineCompliance.post_list_job_role.compliance',['id' => $job_role->id ?? 0])}}"  method="POST">
                @csrf
                <input type="hidden" name="common_everything_view" />
                <input type="hidden" name="common_static_values_view" />
                <input type="hidden" name="common_dynamic_values_view" />
{{--                <input type="hidden" name="common_property_information_view" />--}}
{{--                <input type="hidden" name="general_property_listing_view" />--}}
{{--                <input type="hidden" name="general_email_notifications_view" />--}}
{{--                <input type="hidden" name="general_organisational_view" />--}}
{{--                <input type="hidden" name="general_resources_view" />--}}
{{--                <input type="hidden" name="general_audit_trail_view" />--}}
{{--                <input type="hidden" name="general_site_operative_view_view" />--}}
{{--                <input type="hidden" name="general_view_trouble_ticket_view" />--}}
{{--                <input type="hidden" name="general_is_operative_view" />--}}
{{--                <input type="hidden" name="general_is_tt_view" />--}}
{{--                <input type="hidden" name="general_work_request_view" />--}}

                <input type="hidden" name="common_everything_update" />
                <input type="hidden" name="common_static_values_update" />
                <input type="hidden" name="common_dynamic_values_update" />
{{--                <input type="hidden" name="common_property_information_update" />--}}
{{--                <input type="hidden" name="common_data_centre_update" />--}}
{{--                <input type="hidden" name="general_organisational_update" />--}}
{{--                <input type="hidden" name="general_resources_update" />--}}

                <input type="hidden" name="role_type" value="{{$type ?? 0}}"/>
                @if(!$privilege_view->isEmpty())
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <ul>
                                <button type="button" id="submit_role" class="btn light_grey_gradient_button fs-8pt">
                                    <strong>Save</strong>
                                </button>
                            </ul>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
    @include('shineCompliance.modals.all_client_job_role', ['modal_id' => 'all_client_md'])
    @include('shineCompliance.modals.all_organisation_job_role', ['modal_id' => 'all_organisation_md'])
@endsection
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // need test using class to bstree all or need to set one by one ?
            @if(!$privilege_view->isEmpty())
            $hiddenInput = $('.bstree-input-privilege');
            $('.bstree-privilege').bstree({
                dataSource: $hiddenInput,
                isExpanded:true,
                initValues: $hiddenInput.data('ancestors'),
                onDataPush: function (values) {
                    return;
                },
                updateNodeTitle: function (node, title) {
                }
            });
            @endif

            var table_properties = $('#property-dt-table').DataTable({
                'ajax': {
                    'url': '{!! route('shineCompliance.ajax_property_role.compliance') !!}',
                    "destroy" : true,
                },
                "lengthChange": false,
                "ordering": false,
                pageLength: 10,
                bInfo:false,
                "pagingType": "full_numbers",
                'columnDefs': [{
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta){
                        //need check checkboxes are checked
                        var is_checked = '';
                        if(full[2]){
                            is_checked = 'checked';
                        }
                        // console.log(data);
                        // console.log(full[2]);
                        // console.log(is_checked);
                        // console.log(full);
                        // console.log(meta);
                        return '<input type="checkbox" class="check_property" name="list_property[]" value="' + $('<div/>').text(data).html() + '"' + is_checked + '>';
                    }
                }],
                'order': [[1, 'asc']]
            });
        //
        //
            PRIVILEGE_GROUP_ID = null;
            $('body').on('click','.edit-group',function(e){
                // alert(123);
                e.preventDefault();
                var group_id = $(this).find('a').data('group');
                PRIVILEGE_GROUP_ID = $(this);
                var id = $(this).closest('li').data('id');
                // var check_group = $(this).closest('li').find("input[id='bstree-checkbox-" + $(this).closest('li').data('id') + "']:first");
                // if(check_group.length && !check_group.prop('checked')){
                //     //need checked this group because when unchecked the group, all properties inside will be removed in db
                //     check_group.prop('checked',true);
                // }
                var url = '{!! route('shineCompliance.ajax_property_role.compliance') !!}'+'?group_id='+group_id+'&role_id='+$('#job_role_id').val()+'&tab='+$('body').find('.role_nav.active').data('tab');
                if(url && group_id){
                    //set value for save later
                    $('#group_id').val(group_id);
                    $('#tab-active').val($('body').find('.role_nav.active').data('tab'));
                    $('#overlay').fadeIn();
                    table_properties.rows().clear().draw();
                    table_properties.ajax.url(url).load(fadeOut);//call back fade out
                    $('#property-listing-modal').modal('show');
                }
            });


            // Handle click on "Select all" control
            $('#checkbox-select-all').on('click', function(){
                // Get all rows with search applied
                var rows = table_properties.rows({ 'search': 'applied' }).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('.check_property', rows).prop('checked', this.checked);
            });

            //for click save button
            $('body').on('click','#add-all-property', function(){

                var url = $(this).closest('form').attr('action');
                var list_property_id = [];
                table_properties.$('.check_property').each(function (k, v) {
                    if($(v).prop('checked')){
                        list_property_id.push($(v).val());
                    }
                });
                list_property_id = list_property_id.join(",");
                var data = {
                    list_property: list_property_id,
                    group_id: $('#group_id').val(),
                    role_id : '{{$job_role->id ?? 0}}',
                    tab : $('body').find('.role_nav.active').data('tab')
                };
                $('#overlay').fadeIn();
                if(url){
                    $.ajax
                    ({
                        type: "POST",
                        url: url,
                        data: data,
                        cache: false,
                        success: function (result) {
                            var str = '(' + pad(result.count_property, 2) + '/' + pad(result.total_property, 2) + ')';
                            var strong = $(PRIVILEGE_GROUP_ID).closest('li').find("strong");
                            $(strong).removeAttr('class');
                            if(result.count_property > 0){
                                $(strong).attr('class', 'text-danger');
                            }
                            $(strong).html(str);
                            // close modal and show successfully
                            $('#overlay').fadeOut();
                        }
                    });
                }
                $('#property-listing-modal').modal('hide');
                // close modal
            });
        //
            $('body').on('click','#bstree-checkbox-child-5, #bstree-checkbox-child-update-2', function(){
                //click check all properties need to hide Edit btn to prevent open popup-property
                if($(this).prop('checked')){
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
        //
        //     //for click submit both view/update page
            $('body').on('click','#submit_role', function(){
                $('#overlay').fadeIn();
                var view_parent_id = '#view';
                var update_parent_id = '#update';
                //for view data
                var everything_view, operative_view, trouble_ticket_view, everything_update, all_client_view, all_client_update, all_organisation_view, all_organisation_update;
                everything_view = operative_view = trouble_ticket_view= everything_update  = all_client_view = all_client_update = 0;
                var static_view, static_update;
                var dynamic_view = {};
                var dynamic_update = {};
                if($('input[name="everything-view"]').prop('checked')){
                    everything_view = 1;
                }
                if($('input[name="everything-update"]').prop('checked')){
                    everything_update = 1;
                }
                if($('input[name="operative-view"]').prop('checked')){
                    operative_view = 1;
                }
                if($('input[name="tt-view"]').prop('checked')){
                    trouble_ticket_view = 1;
                }
                if($('input[name="all-clientsview"]').prop('checked')){
                    all_client_view = 1;
                }
                if($('input[name="all-clientsupdate"]').prop('checked')){
                    all_client_update = 1;
                }
                if($('input[name="all-organisationsview"]').prop('checked')){
                    all_organisation_view = 1;
                }
                if($('input[name="all-organisationsupdate"]').prop('checked')){
                    all_organisation_update = 1;
                }

                static_view = getCheckedNode('normal', view_parent_id);
                dynamic_view.email_notification = getCheckedNode('email-notification', view_parent_id);
                dynamic_view.reporting = getCheckedNode('reporting', view_parent_id);
                dynamic_view.organisation = getDataContractor('organisation-view', view_parent_id);
                dynamic_view.client = getDataContractor('client-view', view_parent_id);
                dynamic_view.contractor = getDataContractor('contractor-view', view_parent_id);
                dynamic_view.work_flow = getCheckedNode('work-flow', view_parent_id);
                dynamic_view.work_request = getCheckedNode('work-request', view_parent_id);
                dynamic_view.category = getCheckedNode('template-child-view', view_parent_id);
                dynamic_view.group = getCheckedNode('client-property-view', view_parent_id);
                dynamic_view.all_client = all_client_view;
                dynamic_view.all_organisation = all_organisation_view;

                static_update = getCheckedNode('normal', update_parent_id);
                dynamic_update.organisation = getDataContractor('organisation-update', update_parent_id);
                dynamic_update.client = getDataContractor('client-update', update_parent_id);
                dynamic_update.contractor = getDataContractor('contractor-update', update_parent_id);
                dynamic_update.group = getCheckedNode('client-property-update', update_parent_id);
                dynamic_update.category = getCheckedNode('template-child-update', update_parent_id);
                dynamic_update.all_client = all_client_update;
                dynamic_update.all_organisation = all_organisation_update;
                // console.log(static_view, dynamic_view, static_update, dynamic_update);
                // return;

                // var view_privilege_data = getCheckedNode('normal', view_parent_id);
                // $('#form_save_role')[0].submit();
                $.ajax
                ({
                    type: "POST",
                    url: "{{route('shineCompliance.post_list_job_role.compliance',['id' => $job_role->id ?? 0])}}",
                    data: {
                        role_type : '{{$type ?? 0}}',
                        common_everything_view : everything_view,
                        common_static_values_view : JSON.stringify(static_view),
                        common_dynamic_values_view : JSON.stringify(dynamic_view),
                        general_site_operative_view : operative_view,
                        general_view_trouble_ticket_view : trouble_ticket_view,
                        common_everything_update : everything_update,
                        common_static_values_update : JSON.stringify(static_update),
                        common_dynamic_values_update : JSON.stringify(dynamic_update)
                    },
                    // contentType: "application/json; charset=utf-8",
                    // dataType: "json",
                    cache: false,
                    success: function (data) {
                        $('#overlay').fadeOut();
                        // if(data.status_code == 200) {
                            location.reload();
                        // }
                    }
                });
            });
        //     //for click check all roles
            $('body').on('click','.everything-role', function(){
                var set_checked = $(this).prop('checked');
                var tab_id = $('body').find('.role_nav.active').attr('href');
                var parent = $(tab_id);
                if(set_checked){
                    $(parent).find('.everything-group').siblings().not("#v-37-view-tab").not("#v-38-view-tab").hide();
                } else {
                    $(parent).find('.everything-group').siblings().not("#v-37-view-tab").not("#v-38-view-tab").show();
                }
            });
            removeInputLocked();
            //tick all client

            $('body').on('click','.all-clients', function(e){
                var is_selected = $(this).prop('checked');
                if(is_selected){
                    $('#all_client_md').find('.content-text').html($('#all_client_md').find('.content-text').data('content-select'));
                } else {
                    $('#all_client_md').find('.content-text').html($('#all_client_md').find('.content-text').data('content-deselect'));
                }
                $('#all_client_md').modal('show');
            });
            // cancel re-update state select

            //click submit
            $('body').on('click','.all_client_btn', function(e){
                var active_tab = $('body').find('.role_nav.active').data('tab');
                if(active_tab == '{{JOB_ROLE_VIEW}}'){
                    var is_selected = $('#view').find('.all-clients').prop('checked');
                } else {
                    var is_selected = $('#update').find('.all-clients').prop('checked');
                }
                var is_selected = is_selected ? 1 : 0;
                //hide load client-group partial
                if(active_tab == '{{JOB_ROLE_VIEW}}'){
                    $('#view').find('.load-client-group').html('');
                } else {
                    $('#update').find('.load-client-group').html('');
                }
                $('#overlay').fadeIn();
                $.ajax
                ({
                    type: "GET",
                    url: "{!!route('shineCompliance.check_all_client.compliance',['id' => $job_role->id ?? 0, 'role_type' => $type ?? 0]) !!}",
                    data: {is_selected: is_selected, tab : active_tab},
                    // dataType: "json",
                    cache: false,
                    success: function (html) {
                        if(html) {
                            $('#all_client_md').modal('hide');
                            $('#overlay').fadeOut();
                        }
                    }
                });
            });
            //click cancel
            $('body').on('click','.cancel_all_client_btn', function(e){
                //revert state select
                var active_tab = $('body').find('.role_nav.active').data('tab');
                if(active_tab == '{{JOB_ROLE_VIEW}}'){
                    $('#view').find('.all-clients').prop('checked', !$('#view').find('.all-clients').prop('checked'));
                } else {
                    $('#update').find('.all-clients').prop('checked', !$('#update').find('.all-clients').prop('checked'));
                }
                $('#all_client_md').modal('hide');
            });

            //organisation listing
            $('body').on('click','.all-organisations', function(e){
                var is_selected = $(this).prop('checked');
                if(is_selected){
                    $('#all_organisation_md').find('.content-text').html($('#all_organisation_md').find('.content-text').data('content-select'));
                } else {
                    $('#all_organisation_md').find('.content-text').html($('#all_organisation_md').find('.content-text').data('content-deselect'));
                }
                $('#all_organisation_md').modal('show');
            });


            //click submit
            $('body').on('click','.all_organisation_btn', function(e){
                var active_tab = $('body').find('.role_nav.active').data('tab');
                if(active_tab == '{{JOB_ROLE_VIEW}}'){
                    var is_selected = $('#view').find('.all-organisations').prop('checked');
                } else {
                    var is_selected = $('#update').find('.all-organisations').prop('checked');
                }
                var is_selected = is_selected ? 1 : 0;
                $('#overlay').fadeIn();
                $.ajax
                ({
                    type: "GET",
                    url: "{!!route('shineCompliance.check_all_organisation.compliance',['id' => $job_role->id ?? 0, 'role_type' => $type ?? 0]) !!}",
                    data: {is_selected: is_selected, tab : active_tab},
                    // dataType: "json",
                    cache: false,
                    success: function (html) {
                        if(html) {
                            $('#all_organisation_md').modal('hide');
                            if(active_tab == '{{JOB_ROLE_VIEW}}'){
                                //reload datatable
                                $('#organisation-listing-privilege-1').DataTable().ajax.reload(null, false);
                            } else {
                                //reload datatable
                                $('#organisation-listing-privilege-2').DataTable().ajax.reload(null, false);
                            }
                            $('#overlay').fadeOut();
                        }
                    }
                });
            });

            //click cancel
            $('body').on('click','.cancel_all_organisation_btn', function(e){
                //revert state select
                var active_tab = $('body').find('.role_nav.active').data('tab');
                if(active_tab == '{{JOB_ROLE_VIEW}}'){
                    $('#view').find('.all-organisations').prop('checked', !$('#view').find('.all-organisations').prop('checked'));
                } else {
                    $('#update').find('.all-organisations').prop('checked', !$('#update').find('.all-organisations').prop('checked'));
                }
                $('#all_organisation_md').modal('hide');
            });
            //save organisation listing
            $('body').on('click', '.save-organisation-listing', function () {
                var that = this;
                var checkboxes = $(this).closest('.parent-organisation-listing').find('.organisation_detail');
                var data = {};
                var uncheck = [];
                $.each(checkboxes, function(k,v){
                    let client_id = $(v).data('value');
                    if(!data[client_id]){
                        data[client_id] = new Array();
                    }
                    if($(v).prop('checked')){
                        data[client_id].push($(v).val());
                    }
                });
                $.each(data, function(k,v){
                    if(!data[k].length){
                        uncheck.push(k);
                    }
                });
                var active_tab = $('body').find('.role_nav.active').data('tab');
                $('#overlay').fadeIn();
                $.ajax
                ({
                    type: "POST",
                    url: "{!!route('shineCompliance.post_list_organisation.compliance',['id' => $job_role->id ?? 0]) !!}",
                    data: {
                        tab : active_tab,
                        data : data,
                        uncheck : uncheck,
                    },
                    cache: false,
                    success: function (html) {
                        if(html) {
                            //reload datatable
                            if(active_tab == '{{JOB_ROLE_VIEW}}'){
                                //reload datatable
                                $('#organisation-listing-privilege-1').DataTable().ajax.reload(null, false);
                            } else {
                                //reload datatable
                                $('#organisation-listing-privilege-2').DataTable().ajax.reload(null, false);
                            }
                            $('#overlay').fadeOut();
                        }
                    }
                });
            });

            //load client group

            $('body').on('click', '.view-client', function () {
                var that = this;
                var active_tab = $('body').find('.role_nav.active').data('tab');
                //hide load client-group partial
                $(that).closest('.parent-client-listing').find('.load-client-group').html('');
                var client_id = $(this).data('id');
                $('#overlay').fadeIn();
                //ajax and show tree and re-build tree
                $.ajax
                ({
                    type: "GET",
                    url: "{!!route('shineCompliance.get_list_client_group.compliance',['id' => $job_role->id ?? 0]) !!}",
                    data: {
                        client_id:client_id,
                        tab : active_tab
                    },
                    cache: false,
                    success: function (html) {
                        if(html) {
                            $(that).closest('.parent-client-listing').find('.load-client-group').html(html);

                            $hiddenInput2 = $(that).closest('.parent-client-listing').find('.bstree-input-privilege-cl');
                            $(that).closest('.parent-client-listing').find('.bstree-privilege-cl').bstree({
                                dataSource: $hiddenInput2,
                                isExpanded:true,
                                initValues: $hiddenInput2.data('ancestors'),
                                onDataPush: function (values) {
                                    return;
                                },
                                updateNodeTitle: function (node, title) {
                                }
                            });
                            checkCLientGroup();
                            $(that).closest('.parent-client-listing').find('.load-client-group').show();
                            $('#overlay').fadeOut();
                        }
                    }
                });
            });

            $('body').on('click', '.save-client-listing', function () {
                var that = $(this).closest('.parent-client-listing');
                var active_tab = $('body').find('.role_nav.active').data('tab');

                var li = $(that).find("[data-prefix='client-listing']");
                var client_id = $(li).data('value');
                var id = $(li).data('id');
                var client_id_check = $(li).find("input[id='bstree-checkbox-" + id + "']:first").prop('checked');
                var group_ids = getCheckedNode('group-listing', $(that));
                var add_group_permission = 0;
                if($(that).find('.add-group-permission') && $(that).find('.add-group-permission').prop('checked')){
                    add_group_permission = 1;
                }
                $('#overlay').fadeIn();
                $(that).find('.load-client-group').html('');
                //ajax and show tree and re-build tree
                $.ajax
                ({
                    type: "POST",
                    url: "{!!route('shineCompliance.post_list_client_group.compliance',['id' => $job_role->id ?? 0]) !!}",
                    data: {
                        tab : active_tab,
                        client_id : client_id,
                        client_id_check : client_id_check ? 1 : 0,
                        group_ids : group_ids,
                        add_group_permission : add_group_permission,
                    },
                    cache: false,
                    success: function (html) {
                        console.log(html)
                        if(html) {
                            $(that).find('.load-client-group').html(html);
                            $hiddenInput2 = $(that).find('.bstree-input-privilege-cl');
                            $(that).find('.bstree-privilege-cl').bstree({
                                dataSource: $hiddenInput2,
                                isExpanded:true,
                                initValues: $hiddenInput2.data('ancestors'),
                                onDataPush: function (values) {
                                    return;
                                },
                                updateNodeTitle: function (node, title) {
                                }
                            });
                            checkCLientGroup();
                            $(that).find('.load-client-group').show();
                            $('#overlay').fadeOut();
                        }
                    }
                });
            });
        });
        //
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
            // hide all check boxes in property listing
            // $('li').find("[data-id='child-7']").find('input[type="checkbox"]').addClass('d-none');
            // $('li').find("[data-id='child-update-4']").find('input[type="checkbox"]').addClass('d-none');
            // $('li').find("[data-id='child-7']").find('label').removeClass('custom-control-label');
            // $('li').find("[data-id='child-7']").find('.custom-control').addClass('pl-0');
            // $('li').find("[data-id='child-update-4']").find('label').removeClass('custom-control-label');
            // $('li').find("[data-id='child-update-4']").find('.custom-control').addClass('pl-0');
        }

        function checkCLientGroup(){
            var li_list = $('.bstree-privilege-cl').find("[data-checked=1]");
            $.each(li_list, function(k,v){
                var id = $(v).data('id');
                // var level = $(v).data('level');
                var is_has_child = $(v).find('li');
                if(id && !is_has_child.length){
                    //only check child
                    $(v).find("input[id='bstree-checkbox-" + id + "']:first").trigger('click');
                }
            })
        }
        // //get data for contractor
        function getDataContractor(type, parent_id){
            var my_organisation = {};
            //[1=>[2,3,4], 2=>[3.45.6]]
            var li_list_parent = $(parent_id).find("[data-prefix='" + type + "']");
            // console.log(li_list_parent);
            $.each(li_list_parent, function (kp, vp){
                var li_list = $(vp).find(".organisation_child");
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
            });
            if(!$.isEmptyObject(my_organisation)){
                return JSON.stringify(my_organisation);
            }
            return [];
        }
        // // normal and dynamic types as project, project information
        function getCheckedNode(type, parent_id) {
            var arr_result = [];
            if (type) {
                var li_list = $(parent_id).find("[data-prefix='" + type + "']");
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
        function pad(num, size) {
            num = num.toString();
            while (num.length < size) num = "0" + num;
            return num;
        }

        function fadeOut(){

            $('#overlay').fadeOut();
        }
    </script>
@endpush

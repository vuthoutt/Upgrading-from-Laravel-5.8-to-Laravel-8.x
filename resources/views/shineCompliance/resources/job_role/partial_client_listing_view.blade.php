<!-- New design for Client and Group -->
<ul>
@include('forms.form_checkbox',['title' => 'All Clients:', 'data' => 1, 'class'=>'all-clients', 'name' => 'all-clients'.($type == JOB_ROLE_VIEW ? 'view' : 'update'),
'compare' => $type == JOB_ROLE_VIEW ? $job_role->getGeneralValueViewByType($tab, 'all_client') :  $job_role->getGeneralValueEditByType($tab, 'all_client')])
</ul>
<div class="parent-client-listing">
    <div class="load-client-datatable">
        <ul>
            @include('shineCompliance.tables.client_listing_privilege', [
                                'title' => 'Clients',
                                'tableId' =>'client-listing-privilege-'.$type,
                                'collapsed' => false,
                                'plus_link' => false,
                                'job_id' => $job_role->id,
                                'order_table' => 'ajax-table',
                                'data' => []
                                ])
        </ul>
    </div>
    <!-- draw client -> group Tree here (view/edit) -->
    <div class="load-client-group" style="display: none">
        @include('shineCompliance.resources.job_role.partial_load_client_group',
            ['type' => $type, 'prefix' => $prefix, 'client_group' => [], 'level' => 1, 'checked_array' => $job_role->getGeneralValueViewByType(JOB_ROLE_GENERAL, 'group')])
    </div>
</div>
@push('javascript')
@endpush

<!-- New design for Organisation -->
<ul>
@include('forms.form_checkbox',['title' => 'All Organisations:', 'data' => 1, 'class'=>'all-organisations', 'name' => 'all-organisations'.($type == JOB_ROLE_VIEW ? 'view' : 'update'),
'compare' => $type == JOB_ROLE_VIEW ? $job_role->getGeneralValueViewByType($tab, 'all_organisation') :  $job_role->getGeneralValueEditByType($tab, 'all_organisation')])
</ul>
<div class="parent-organisation-listing">
    <div class="load-organisation-datatable">
        <ul>
            @include('shineCompliance.tables.organisation_listing_privilege', [
                                'title' => 'Clients',
                                'tableId' =>'organisation-listing-privilege-'.$type,
                                'collapsed' => false,
                                'plus_link' => false,
                                'job_id' => $job_role->id,
                                'order_table' => 'ajax-table',
                                'data' => []
                                ])
        </ul>
    </div>
    <ul class="text-center">
        <button class="btn light_grey_gradient_button fs-8pt mt-5 save-organisation-listing"><strong>Submit</strong></button>
    </ul>
</div>
@push('javascript')
@endpush

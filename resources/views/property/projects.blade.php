<div class="row">
    @include('tables.property_projects', [
        'title' => 'Projects',
        'data' => $projects,
        'tableId' => 'property-project-table',
        'collapsed' => false,
        'plus_link' => (!empty(\CompliancePrivilegegetUpdatePermission(PROJECTS_TYPE_UPDATE_PRIV)) and \CommonHelpers::isSystemClient()) ? true : false,
        'link' => route('project.get_add', ['property_id' => $propertyData->id]),
        'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
        'dashboard' => false,
        'order_table' => 'published'
     ])
</div>

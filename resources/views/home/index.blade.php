@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'home'])

<div class="container prism-content">
    <h3>Homepage</h3>
    <div class="main-content">
      <div class="row" style="margin-left: 0px">
          <label class="col-md-2 col-form-label text-md-left font-weight-bold" >Recent Views: </label>
          <div class="col-md-8 form-input-text" >
                  @if(!is_null($recentViews))
                      @foreach($recentViews as $data)
                      <a href="{{ $data->link ?? '#' }}">{{ $data->title ?? '' }}</a>
                      @if(!$loop->last)
                           ,
                       @endif
                       @endforeach
                  @endif
          </div>
      </div>
      @if(\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkPermission(DASHBOARD_GRAPHICS_VIEW_PRIV))
      <div class="col-md-6">
        <form method="POST" action="{{route('chart.generate')}}" id="form_chart" enctype="multipart/form-data" class="form-shine">
            @csrf
            @include('forms.form_dropdown_homepage',['title' => 'Display Options', 'data' => $charts, 'name' => 'chart_type', 'key'=> 'code', 'value'=>'description', 'no_first_op' => true, 'option_data' => 'value' ])
            @include('forms.form_dropdown_children_homepage',['title' => '', 'data' => ['1'=>'Inaccessible Overview'], 'name' => 'na-options', 'no_first_op' => true, 'hide' => true])
            @include('forms.form_dropdown_homepage',['title' => '', 'data' => [], 'name' => 'zone', 'key'=> 'id', 'value'=>'zone_name', 'hide' => true ])
            @include('forms.form_dropdown_children_homepage',['title' => '', 'data' => ['1'=>'Inaccessible Overview'], 'name' => 'na-options', 'no_first_op' => true, 'hide' => true])
            @include('forms.form_dropdown_children_homepage',['title' => '', 'data' => $years, 'name' => 'year', 'no_first_op' => true, 'hide' => true])
            <div class="row register-form">
                <label class="col-md-4 col-form-label text-md-left font-weight-bold" ></label>
                <div class="col-md-5">
                    <button type="button" name="submit" id="submit" class="btn light_grey_gradient" title="Submit to generate graphic chart">
                        <strong>Submit</strong>
                    </button>
                </div>
            </div>
        </form>
        @include('chart.index')
    </div>
    @endif
{{--     @if(\CommonHelpers::isSystemClient())
    @include('tables.recent_property',[
       'title' => 'Recent Properties',
       'tableId' => 'recent-property',
       'collapsed' => false,
       'plus_link' => false,
       'data' => $recentSites,
       ])
       @endif --}}

       @include('tables.property_projects', [
        'title' => 'My Projects',
        'data' => $myProjects,
        'tableId' => 'property-project-table',
        'collapsed' => \CommonHelpers::isSystemClient() ? true : false,
        'plus_link' => false,
        'header' => ["Property Reference", "Property Name", "Project Reference", "Project Title","Project Type", "Start", "Due"],
        'dashboard' => true,
        'order_table' => 'my-project'
        ])
        @if(\CommonHelpers::isSystemClient())
        @include('tables.my_document_approval', [
            'title' => 'My Document Approvals',
            'data' => $theProjectDocs,
            'tableId' => 'document-approval-table',
            'collapsed' => true,
            'plus_link' => false
            ])
        @endif
{{--         @if(\Auth::user()->client_id == 1)
              @include('tables.audit_trail', [
                'title' => 'Recent Activity',
                'data' => $checked_audits,
                'tableId' => 'audit-trail-table',
                'collapsed' => true,
                'plus_link' => false,
                'header' => ["Activity Log", "Date", "Time"],
             ])
             @endif --}}
         </div>
     </div>
@endsection

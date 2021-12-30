@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => $breadcrumb_name,'data' => $breadcrumb_data])

<div class="container prism-content">
    <h3>{{ $propertyData->name }}</h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <input type="hidden" id="active_tab" value="{{$active_tab}}">
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
            @if(!\CompliancePrivilege::checkPermission(DETAILS_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#detail" title="Details"><strong>Details</strong></a>
                </li>
            @endif

            @if(!\CompliancePrivilege::checkPermission(REGISTER_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#register" title="Register"><strong>Register</strong></a>
                </li>
            @endif

            @if(!\CompliancePrivilege::checkPermission(SURVEYS_PROP_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#surveys" title="Surveys"><strong>Surveys</strong></a>
                </li>
            @endif

{{--             @if(!empty(\CompliancePrivilege::getPermission(PROJECT_TYPE_PERMISSION)) and \CommonHelpers::isSystemClient())
            @else --}}
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#projects" title="Projects" ><strong>Projects</strong></a>
            </li>
            {{-- @endif --}}

            @if(!\CompliancePrivilege::checkPermission(HISTORICAL_DATA_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#historical-data" title="Historical Data"><strong>Historical Data</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#historical-category" title="Historical Categories"><strong>Historical Categories</strong></a>
            </li>
            @endif
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div id="detail" class="container tab-pane active">
                @include('property.property_detail', ['property' => $propertyData])
            </div>
            <div id="register" class="container tab-pane fade">
                @if($type)
                    @if($type == TYPE_INACCESS_ROOM_SUMMARY)
                        @include('tables.inaccess_locations',[
                                'title' => $title,
                                'tableId' => $table_id,
                                'collapsed' => false,
                                'plus_link' => false,
                                'pagination_type' => $pagination_type,
                                'data' => $items_summary_table
                            ])
                    @else
                    @include('property.item_summary')
                    @endif
                @else
                    @switch($section)
                        @case(SECTION_AREA_FLOORS_SUMMARY)
                            @include('property.area',['areas' => $dataTab, 'decommissionedAreas' => $dataDecommisstionTab,'property' => $propertyData, 'surveyAssessment' => $dataSummary])
                        @break
                        @case(SECTION_ROOM_LOCATION_SUMMARY)
                            @include('property.location',['locationData'=> $locationData, 'property' => $propertyData, 'surveyAssessment' => $dataSummary])
                        @break
                        @default
                            @include('property.register',[ 'areas' => $dataTab, 'decommissionedAreas' => $dataDecommisstionTab,'property' => $propertyData, 'surveyAssessment' => $dataSummary])
                    @endswitch
                @endif
            </div>
            <div id="surveys" class="container tab-pane fade">
                @include('property.surveys', ['surveys' => $surveys, 'decommissionedSurveys' => $decommissionedSurveys])
            </div>
            <div id="projects" class="container tab-pane fade">
                @include('property.projects', ['projects' => $projects])
            </div>
            <div id="historical-data" class="container tab-pane fade">
                @include('property.historical', ['historical_categories' => $propertyData->historicalDocCategory])
            </div>
            <div id="historical-category" class="container tab-pane fade">
                @include('property.historical_category', ['historical_categories' => $propertyData->historicalDocCategory])
            </div>
        </div>

    </div>
</div>
</div>
@endsection
@push('javascript')
<script type="text/javascript">
$(document).ready(function(){
        var currentTab = $('#myTab a.active').attr("title");
        var active_tab = $('#active_tab').val();
        if(active_tab){
            localStorage.setItem('activeTab', active_tab);
        }

        //Tab change
        $('#myTab a').click(function () {
            var tab = $(this).attr("title");

            if (currentTab != tab) {
                auditTab(tab);
            }
            currentTab = tab
        });

        function auditTab(tab) {
            $.ajax({
                type: "POST",
                url: "{{route('ajax.ajax_audit')}}",
                data: {id: {{ $propertyData->id }},type : 'property', tab: tab, _token:"{{ csrf_token() }}"}
            });
        }
});
</script>
@endpush

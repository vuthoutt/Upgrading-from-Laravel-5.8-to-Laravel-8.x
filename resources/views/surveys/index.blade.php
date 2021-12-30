@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => $breadcrumb_name,'color' => 'orange', 'data' => $breadcrumb_data])

<div class="container prism-content">
    <h3>{{ $survey->property->name }} {{ $survey->reference }}</h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <input type="hidden" id="active_tab" value="{{$active_tab}}">
        <ul class="nav nav-pills orange_gradient_nav" id="myTab">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#Details" title="Details"><strong>Details</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Assessments" title="Assessments"><strong>Assessments</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Samples" title="Samples"><strong>Samples</strong></a>
            </li>
            @if($survey->survey_type != SAMPLE_SURVEY)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#executive-summary" title="Executive Summary"><strong>Executive Summary</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#objective-scope" title="Objective/scope"><strong>Objective/scope</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#limitations" title="Limitations"><strong>Limitations</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#method" title="Method"><strong>Method</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#air-test" title="Air Test"><strong>Air Test</strong></a>
                </li>
            @else
                <!-- keep order sections -->
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#objective-scope" title="Objective/scope"><strong>Objective/scope</strong></a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#plans" title="Plans"><strong>Plans</strong></a>
            </li>
            @if($survey->survey_type != SAMPLE_SURVEY)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#property-information" title="Property Information"><strong>Property Information</strong></a>
                </li>
            @endif
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div id="Details" class="container tab-pane active">
                @include('surveys.detail',['data' => $survey])
            </div>

            <div id="Assessments" class="container tab-pane fade">
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
                            @include('surveys.area',['areaData'=> $areaData, 'areas' => $dataTab, 'decommissionedAreas' => $dataDecommisstionTab,'survey' => $survey, 'surveyAssessment' => $dataSummary])
                        @break
                        @case(SECTION_ROOM_LOCATION_SUMMARY)
                            @include('surveys.location',['locationData'=> $locationData, 'survey' => $survey, 'surveyAssessment' => $dataSummary])
                        @break
                        @default
                            @include('surveys.assessments',[ 'areas' => $dataTab, 'decommissionedAreas' => $dataDecommisstionTab,'survey' => $survey, 'surveyAssessment' => $dataSummary])
                    @endswitch
                @endif
            </div>

            <div id="Samples" class="container tab-pane fade">
                @include('surveys.samples',['data' => $survey])
            </div>
            <div id="executive-summary" class="container tab-pane fade">
                @include('surveys.executive_summary',['data' => $survey])
            </div>
            <div id="objective-scope" class="container tab-pane fade">
                @include('surveys.objectives_scope',['data' => $survey])
            </div>
            <div id="limitations" class="container tab-pane fade">
                @include('surveys.limitations',['data' => $survey])
            </div>
            <div id="method" class="container tab-pane fade">
                @include('surveys.method',['data' => $survey])
            </div>
            <div id="air-test" class="container tab-pane fade">
                @include('surveys.air_test_certificate',['survey' => $survey])
            </div>
            <div id="plans" class="container tab-pane fade">
                @include('surveys.plans',['surveyorsNotes' => $surveyorsNotes, 'plans' => $plans])
            </div>
            <div id="property-information" class="container tab-pane fade">
                @include('surveys.property_information',['data' => $survey])
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
                data: {id: {{ $survey->id }},type : 'survey', tab: tab, _token:"{{ csrf_token() }}"}
            });
        }
});
</script>
@endpush

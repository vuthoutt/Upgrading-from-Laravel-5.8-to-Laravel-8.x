@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' =>'data_center', 'color' => 'red'])
    <div class="container prism-content pad-up">
        <div class="row">
            <h3 style="margin: 0">Critical</h3>
        </div>

        <div class="row">
            @include('shineCompliance.dataCentre.partials._data_centre_sidebar')

            <div class="col-md-9 pl-0" style="padding: 0">
                <div id="fire" class="container" style="padding-left: 0; padding-right:0;">
                    @if(\CommonHelpers::isSystemClient())
                        @include('shineCompliance.tables.missing_survey_table', [
                            'title' => 'Assessment Missing Notifications',
                            'tableId' => 'assessment-missing-critical',
                            'order_table' => 'ajax-table',
                            'collapsed' => false,
                            'plus_link' => false,
                            'type' => 'survey',
                            'data' => [],
                            'count' => $missing_assessments_count
                            ])

                        @include('shineCompliance.tables.missing_survey_table', [
                            'title' => 'Survey Missing Notifications',
                            'tableId' => 'survey-missing-critical',
                            'order_table' => 'ajax-table',
                            'collapsed' => true,
                            'plus_link' => false,
                            'type' => 'survey',
                            'data' => [],
                            'count' => $missing_surveys_count
                            ])

                    @endif
                    @include('shineCompliance.tables.overdue_surveys_datacentre_critical', [
                        'title' => 'Assessment Overdue Notifications',
                        'tableId' => 'assessment-overdue-critical',
                        'collapsed' => true,
                        'plus_link' => false,
                        'type' => 'assessment',
                        'data' => $overdue_assessments
                        ])
                    @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_CRITICAL,JOB_ROLE_ASBESTOS))
                    @else
                        @include('shineCompliance.tables.overdue_surveys_datacentre_critical', [
                            'title' => 'Audit Overdue Notifications',
                            'tableId' => 'audit-overdue-critical',
                            'collapsed' => true,
                            'plus_link' => false,
                            'type' => 'audit',
                            'data' => $overdue_audits
                            ])

                        @include('shineCompliance.tables.overdue_surveys_datacentre_critical', [
                            'title' => 'Certificate Overdue Notifications',
                            'tableId' => 'cert-overdue-critical',
                            'collapsed' => true,
                            'plus_link' => false,
                            'type' => 'certificate',
                            'data' => []
                            ])
                    @endif
                    @include('shineCompliance.tables.overdue_documents_datacentre', [
                        'title' => 'Project Document Overdue Notifications',
                        'tableId' => 'planning-document-overdue',
                        'collapsed' => true,
                        'plus_link' => false,
                        'type' => 'survey',
                        'data' => $project_docs,
                        'order_table' => '[]'
                        ])

                    @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_CRITICAL,JOB_ROLE_ASBESTOS))
                    @else
                    @include('shineCompliance.tables.overdue_surveys_datacentre_critical', [
                        'title' => 'Survey Overdue Notifications',
                        'tableId' => 'survey-overdue-critical',
                        'collapsed' => true,
                        'plus_link' => false,
                        'type' => 'survey',
                        'data' => $overdue_surveys
                        ])

                    @include('shineCompliance.tables.overdue_surveys_re_inspections_datacentre_critical2', [
                        'title' => 'Survey Re-Inspection Overdue Notifications',
                        'tableId' => 're-inspection-overdue',
                        'collapsed' => true,
                        'plus_link' => false,
                        'type' => 'survey',
                        'data' => $reinspection_sites
                        ])
                    @endif

                    @include('shineCompliance.tables.overdue_surveys_re_inspections_datacentre_critical2', [
                        'title' => 'Assessment Re-Inspection Overdue Notifications',
                        'tableId' => 'assessment-re-inspection-overdue',
                        'collapsed' => true,
                        'plus_link' => false,
                        'type' => 'survey',
                        'data' => $reinspection_assessments
                        ])

                    @include('shineCompliance.tables.pre_plan_urgent_to_deadline', [
                        'title' => 'Pre-Planned Maintenance Critical Notifications',
                        'tableId' => 'pre-plan-maintenance-overdue',
                        'collapsed' => true,
                        'type' => 'survey',
                        'plus_link' => false,
                        'data' => []
                        ])

                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            //todo add search btn
            // $("#assessment-missing-critical").on("preInit.dt", function(){
            //     $("#assessment-missing-critical_filter input[type='search']").after("<button type='button' id='btnexample_search' class='btn btn-light'><i class=\"fa fa-search\"></i></button>");
            // });
            //ajax search/total page/sort
            var assessment_missing_table = $('#assessment-missing-critical').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            }).DataTable({
                stateSave: true,
                stateSaveParams: function (settings, data) {
                    data.search.search = "";
                },
                bAutoWidth: false,
                processing: true,
                serverSide: true,
                aaSorting: [[0, "asc"]],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                },
                "initComplete":function(){onint();},
                "ajax": {
                    "url": "{{route('shineCompliance.data_centre.ajax.assessment_missing')}}",
                    "method":"GET",
                    // "data": {
                    //     "client_id": 0,
                    //     "page": 0,
                    //     "offset": 0,
                    //     "limit": 15
                    // },
                    // "dataSrc":""
                },
                "columns": [
                    { "data": "reference", render: function (dataField, type, full, meta) {
                            var link = "/compliance/property/detail/"+full.id;
                            return dataField ? '<a href="'+link+'">'+dataField+'</a>' : '';
                        }
                    },
                    { "data": "property_reference", render: function (dataField, type, full, meta) {
                            var link = "/compliance/property/detail/"+full.id;
                            return dataField ? '<a href="'+link+'">'+dataField+'</a>' : '';
                        }
                    },
                    { "data": "name", render: function (dataField, type, full, meta) {
                            var link = "/compliance/property/detail/"+full.id;
                            return  dataField ? '<a href="'+link+'">'+dataField+'</a>' : '';
                        }
                    },
                    { "data": "created_at" },
                    { "data": "created_at", render: function (dataField, type, full, meta) {
                            return '<span class="badge {{ $risk_color }}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }}>'+getCriticalDaysDate(dataField)+'</span> Days Remaining';
                        }
                    },
                ]
            });

            // surveys missing
            var survey_missing_table = $('#survey-missing-critical').on( 'processing.dt', function ( e, settings, processing ) {
                $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
            }).DataTable({
                stateSave: true,
                stateSaveParams: function (settings, data) {
                    data.search.search = "";
                },
                bAutoWidth: false,
                processing: true,
                serverSide: true,
                aaSorting: [[0, "asc"]],
                responsive: true,
                "pagingType": "full_numbers",
                "footerCallback": function ( row, data, start, end, display ) {
                    $(this).closest('.row').next().addClass('footer-dt-table');
                },
                "initComplete":function(){onint();},
                "ajax": {
                    "url": "{{route('shineCompliance.data_centre.ajax.survey_missing')}}",
                    "method":"GET",
                },
                "columns": [
                    { "data": "reference", render: function (dataField, type, full, meta) {
                            var link = "/compliance/property/detail/"+full.id;
                            return dataField ? '<a href="'+link+'">'+dataField+'</a>' : '';
                        }
                    },
                    { "data": "property_reference", render: function (dataField, type, full, meta) {
                            var link = "/compliance/property/detail/"+full.id;
                            return dataField ? '<a href="'+link+'">'+dataField+'</a>' : '';
                        }
                    },
                    { "data": "name", render: function (dataField, type, full, meta) {
                            var link = "/compliance/property/detail/"+full.id;
                            return dataField ? '<a href="'+link+'">'+dataField+'</a>' : '';
                        }
                    },
                    { "data": "created_at" },
                    { "data": "created_at", render: function (dataField, type, full, meta) {
                            return '<span class="badge {{ $risk_color }}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }}>'+getCriticalDaysDate(dataField)+'</span> Days Remaining';
                        }
                    },
                ]
            });
            $(assessment_missing_table.table().container()).children('.row').eq(1).addClass('datatable-margin');
            $(survey_missing_table.table().container()).children('.row').eq(1).addClass('datatable-margin');
        });

        // $('#assessment-missing-critical_length').on('change', function(){
        //     assessment_missing_table.ajax.reload();
        // });

        // this function is used to intialize the event handlers
        //todo add search btn
        function onint(){
            // take off all events from the searchfield
            // $("#assessment-missing-critical_filter input[type='search']").off();
            // // Use return key to trigger search
            // $("#assessment-missing-critical_filter input[type='search']").on("keydown", function(evt){
            //     if(evt.keyCode == 13){
            //         $("#assessment-missing-critical").DataTable().search($("input[type='search']").val()).draw();
            //     }
            // });
            // $("#btnexample_search").button().on("click", function(){
            //     $("#assessment-missing-critical").DataTable().search($("input[type='search']").val()).draw();
            // });
        }

        function getCriticalDaysDate(date){
            var date1 = new Date(date);
            var date2 = new Date();
            var datetime_date1 = date1.getTime();
            var datetime_date2 = date2.getTime();
            var timeRemain = parseInt((datetime_date1 - datetime_date2) / 86400);
            var time = (timeRemain > 0) ? timeRemain : 0;
            return time.toString().padStart(3,'0');
        }
    </script>
@endpush

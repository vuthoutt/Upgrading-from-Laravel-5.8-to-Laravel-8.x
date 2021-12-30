 <div class="row offset-top40">
    <div class="col-md-12">
        @include('forms.form_text',['title' => 'Incident Report Reference:', 'data' => $incident->reference ?? '' ])
        @include('forms.form_text',['title' => 'Created Date:', 'data' => $incident->created_at->format('d/m/Y H:i') ?? '' ])
        @include('forms.form_text',['title' => 'Published Date:', 'data' => $incident->published_date ?? '' ])
        @include('forms.form_text',['title' => 'Completed Date:', 'data' => $incident->completed_date ?? ''])
        @include('forms.form_text',['title' => 'Status:', 'data' => $incident->status_display ?? '' ])

    </div>

    <div class="mt-5 ml-3 pb-2" >
            @if($incident->status == INCIDENT_REPORT_COMPLETE)
                <div class="spanWarningSurveying">
                    <strong><em>Incident Report is view only because technical activity is complete</em></strong>
                </div>
            @else
            @if(\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(JR_RESOURCES,JR_INCIDENT_REPORT_EDIT))
                    @if(!$incident->is_lock)
                        <a href="{{ route('shineCompliance.incident_reporting.get_edit', ['id' => $incident->id]) }}" style="text-decoration: none">
                            <button type="submit" class="btn light_grey_gradient_button fs-8pt ">
                                <strong>{{ __('Edit') }}</strong>
                            </button>
                        </a>
                    @endif
                    @if($incident->decommissioned == SURVEY_UNDECOMMISSION)
                    <a href="" data-toggle="modal" data-target="#decommission_incident_report" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt ">
                            <strong>{{ __('Decommission') }}</strong>
                        </button>
                    </a>
                        @include('shineCompliance.modals.decommission_assessment',[
                                'modal_id' => 'decommission_incident_report',
                                'header' => 'Decommission Incident Report',
                                'decommission_type' => 'incident_report',
                                'name' => 'decommission_reason',
                                'reference' => $incident->reference ?? '',
                                'url' => route('shineCompliance.incident_reporting.decommission', ['incident_id' => $incident->id]),
                            ])
                    @else
                    <a href="" data-toggle="modal" data-target="#recommission_incident_report" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt ">
                            <strong>{{ __('Recommission') }}</strong>
                        </button>
                    </a>
                        @include('shineCompliance.modals.recommission_assessment',[
                                'modal_id' => 'recommission_incident_report',
                                'header' => 'Recommission Incident Report',
                                'reference' => $incident->reference ?? '',
                                'url' => route('shineCompliance.incident_reporting.recommission', ['incident_id' => $incident->id]),
                            ])
                    @endif
                    @if(!$incident->is_lock)
                        <a href="#" style="text-decoration: none">
                            <button type="submit" class="btn light_grey_gradient_button fs-8pt" data-toggle="modal" data-target="#publish-incident">
                                <strong>{{ __('Publish') }}</strong>
                            </button>
                        </a>
                    @endif
                    @include('shineCompliance.modals.publish_incident',['color' => 'red','incident' => $incident, 'modal_id' => 'publish-incident', 'url' => route('shineCompliance.incident_reporting.publish',['incident' => $incident->id])])
                @endif
            @endif
    </div>
    @include('shineCompliance.tables.work_request_history', [
        'title' => 'Incident Report History',
        'data' =>$incident->publishedIncidentReport,
        'status' => $incident->status,
        'tableId' => 'work-request-history',
        'collapsed' => false,
        'plus_link' => false,
        'row_col' => 'col-md-12',
        'order_table' => 'published'
     ])
</div>

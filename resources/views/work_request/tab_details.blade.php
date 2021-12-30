 <div class="row offset-top40">
    <div class="col-md-12">
        @include('forms.form_text',['title' => 'Work Request Reference:', 'data' => $data->reference ])
        @include('forms.form_text',['title' => 'Created Date:', 'data' => $data->created_at->format('d/m/Y') ])
        @include('forms.form_text',['title' => 'Published Date:', 'data' => $data->published_date ])
        @include('forms.form_text',['title' => 'Completed Date:', 'data' => $data->completed_date ])
        @include('forms.form_text',['title' => 'Status:', 'data' => $data->status_text ])
        @if($data->decommissioned == 1)
        @include('forms.form_text',['title' => 'Reason for Decommission:', 'data' => ($data->decommisioned_reason) == 2011 ? 'Survey Cancelled' : $data->decommissionedReason->description ])
        @endif
    </div>

    <div class="mt-5 ml-2 pb-5">
        @if(!\CompliancePrivilege::checkUpdatePermission(JR_RESOURCES,JR_WORK_REQUEST_EDIT) and \CommonHelpers::isSystemClient())
        @else
            @if($data->status == WORK_REQUEST_COMPLETE)
                <div class="spanWarningSurveying">
                    <strong><em>Work request is view only because technical activity is complete</em></strong>
                </div>
            @elseif($data->status == WORK_REQUEST_AWAITING_APPROVAL)
                    <div class="spanWarningSurveying">
                    <strong><em>Work request is view only because technical activity is in progress</em></strong>
                </div>
            @else
                <a href="{{ route('wr.get_edit',['id' => $data->id]) }}" style="text-decoration: none">
                    <button type="submit" class="btn light_grey_gradient_button fs-8pt ">
                        <strong>{{ __('Edit') }}</strong>
                    </button>
                </a>
                {{-- <a href="{{ route('wr.decommission',['id' => $data->id]) }}" style="text-decoration: none"> --}}

                        {{-- @if(\CommonHelpers::checkDecommissionPermission()) --}}
                            @if($data->decommissioned == 0)
                            <a class="btn  light_grey_gradient_button fs-8pt" data-toggle="modal" data-target="#decommission_location">
                                <strong>Decommission</strong>
                            </a>
                            @include('modals.decommission',[ 'color' => 'red',
                                                            'modal_id' => 'decommission_location',
                                                            'header' => 'Decommission Work Request',
                                                            'decommission_type' => 'work_request',
                                                            'name' => 'decommission_reason',
                                                            'url' => route('wr.decommission',['id' => $data->id])
                                                            ])
                            @else
                            <a href="{{ route('wr.recommission',['id' => $data->id]) }}" style="text-decoration: none">
                                <button type="submit" class="btn light_grey_gradient ">
                                        <strong>{{ __('Recommission') }}</strong>
                                </button>
                            </a>
                            {{-- @endif --}}
                        @endif

                @if ($data->status != WORK_REQUEST_AWAITING_APPROVAL)
                    <a href="#" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt" data-toggle="modal" data-target="#publish_work_request">
                            <strong>{{ __('Publish') }}</strong>
                        </button>
                    </a>
                    @include('modals.publish_work_request',['work_request' => $data, 'modal_id' => 'publish_work_request', 'url' => route('wr.publish',['id' => $data->id])])
                @endif
            @endif
        @endif
    </div>
    @include('tables.work_request_history', [
        'title' => 'Work Request History',
        'data' => $data->publishedWorkRequest,
        'countData' => count($data->publishedWorkRequest),
        'is_completed_wr' => $data->is_major != 1 && (isset($data->orchardJob) && $data->orchardJob->status == 1),
        'status' => $data->status,
        'tableId' => 'work-request-history',
        'collapsed' => false,
        'plus_link' => false,
        'order_table' => 'published'
     ])
</div>

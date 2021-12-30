@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => ($item->survey_id == 0) ? 'properties_item' : 'survey_item','data' => $item,'color' => ($item->survey_id == 0) ? 'red' : 'orange'])

<div class="container prism-content">
    <h3>{{ $item->name }}</h3>

    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills {{ ($item->survey_id == 0) ? \CommonHelpers::getNavItemColor('red') : \CommonHelpers::getNavItemColor('orange') }}" id="myTab">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#details" title="Details" title="Details"><strong>Details</strong></a>
            </li>
            @if($item->state !== ITEM_NOACM_STATE)
                <li class="nav-item acm">
                    <a class="nav-link" data-toggle="tab" href="#material" title="Material"><strong>Material</strong></a>
                </li>
                @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_priority_assessment == ACTIVE) )
                <li class="nav-item acm">
                    <a class="nav-link" data-toggle="tab" href="#Priority" title="Priority"><strong>Priority</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Overall" title="Overall"><strong>Overall</strong></a>
                </li>
            @endif
            @endif
            @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_photos == ACTIVE) )
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Photography" title="Photography"><strong>Photography</strong></a>
            </li>
            @endif
            @if($item->state !== ITEM_NOACM_STATE)
            <li class="nav-item acm">
                <a class="nav-link" data-toggle="tab" href="#recommendations" title="Action/recommendations"><strong>Action/recommendations</strong></a>
            </li>
            @endif
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div id="details" class="container tab-pane active">
                @include('items.tab_details')
            </div>

            <div id="material" class="container tab-pane fade">
                @include('items.tab_material')

            </div>

            @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_priority_assessment == ACTIVE) )
            <div id="Priority" class="container tab-pane fade">
                @include('items.tab_priority')
            </div>

            <div id="Overall" class="container tab-pane fade">
                @include('items.tab_overall')
            </div>
            @endif
            @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_photos == ACTIVE) )
            <div id="Photography" class="container tab-pane fade">
                @include('items.tab_photography')
            </div>
            @endif
            <div id="recommendations" class="container tab-pane fade">
                @include('items.tab_recommendations')
            </div>
        </div>
        <div class="col-md-6 mt-4">

            @if($canBeUpdateThisItem)
                @if($item->is_locked == ITEM_LOCKED)
                    @if ((isset($survey->status) and $survey->status == COMPLETED_SURVEY_STATUS))
                        <div class="spanWarningSurveying col-md-8" title="Details"><strong><em>Item is view only because surveying is completed</em></strong></div>
                    @else
                        <div class="spanWarningSurveying col-md-8" title="Details"><strong><em>Item is view only while surveying is in progress</em></strong></div>
                    @endif
                @else
                    <a href="{{ route('item.get_edit', ['id' => $item->id,
                                                    'position' => $position ?? 0,
                                                    'category' => $category ?? 0,
                                                    'pagination_type' => $pagination_type ?? 0,
                                                    ]) }}" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient">
                            <strong>{{ __('Edit') }}</strong>
                        </button>
                    </a>
                    @if(\CommonHelpers::checkDecommissionPermission() || (!empty($survey)) )
                        @if($item->decommissioned == 0)
                            <a class="btn  light_grey_gradient" data-toggle="modal" data-target="#decommission_item">
                                    <strong>Decommission</strong>
                            </a>
                            @include('modals.decommission',[ 'color' => ($item->survey_id == 0) ? 'red' : 'orange',
                                                                'modal_id' => 'decommission_item',
                                                                'header' => 'Decommission Item',
                                                                'decommission_type' => 'item',
                                                                'name' => 'item_decommisson_reason_add',
                                                                'url' => route('item.decommission', ['item_id' => $item->id]),
                                                                ])
                        @else
                            <a class="btn  light_grey_gradient" data-toggle="modal" data-target="#recommission_item">
                                    <strong>Recommission</strong>
                            </a>
                            @include('modals.recommission',[ 'color' => ($item->survey_id == 0) ? 'red' : 'orange',
                                                            'modal_id' => 'recommission_item',
                                                            'header' => 'Recommission Item',
                                                            'decommission_type' => 'item',
                                                            'name' => 'item_decommisson_reason_add',
                                                            'url' => route('item.decommission', ['item_id' => $item->id]),
                                                            ])
                        @endif
                    @endif
                @endif
            @endif
        </div>

    </div>
    @include('vendor.pagination.simple-bootstrap-4-customize')
@endsection
@push('javascript')
<script type="text/javascript">
$(document).ready(function(){
        var currentTab = $('#myTab a.active').attr("title");

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
                data: {id: {{ $item->id }},type : 'item', tab: tab, _token:"{{ csrf_token() }}"}
            });
        }
});
</script>
@endpush

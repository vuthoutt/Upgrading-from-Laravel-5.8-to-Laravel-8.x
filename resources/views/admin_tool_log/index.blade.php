@extends('shineCompliance.layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' =>'home','data' => ''])

<div class="container prism-content">
    <h3>Admin Tool Logs</h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#Remove" title="Remove"><strong>Remove</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Move" title="Move"><strong>Move</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Unlock" title="Unlock"><strong>Unlock</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Merge" title="Merge" ><strong>Merge</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Revert-Back" title="Revert Back"><strong>Revert Back</strong></a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div id="Remove" class="container tab-pane active"  style="padding: 0px">
                @include('tables.tool_box_log', [
                    'title' => 'Action Remove',
                    'tableId' => 'remove-action',
                    'collapsed' => false,
                    'plus_link' => false,
                    'roll_back' => true,
                    'data' => $removes,
                    'header' => ['Action List','Description', 'Reason', 'Created By', 'Date', 'Result',''],
                    'order_table' => '[]'
                ])

                @include('tables.tool_box_log', [
                    'title' => 'Rollback Action Remove ',
                    'tableId' => 'remove-back-action',
                    'collapsed' => true,
                    'plus_link' => false,
                    'roll_back' => false,
                    'data' => $removes_back,
                    'header' => ['Action List','Description', 'Reason', 'Created By', 'Date', 'Result']
                ])
            </div>
            <div id="Move" class="container tab-pane fade" style="padding: 0px">
                @include('tables.tool_box_log', [
                    'title' => 'Action Move',
                    'tableId' => 'move-action',
                    'collapsed' => false,
                    'plus_link' => false,
                    'roll_back' => true,
                    'data' => $moves,
                    'header' => ['Action List','Description', 'Reason', 'Created By', 'Date', 'Result',''],
                    'order_table' => '[]'
                ])

                @include('tables.tool_box_log', [
                    'title' => 'Rollback Action Move ',
                    'tableId' => 'move-back-action',
                    'collapsed' => true,
                    'plus_link' => false,
                    'roll_back' => false,
                    'data' => $moves_back,
                    'header' => ['Action List','Description', 'Reason', 'Created By', 'Date', 'Result']
                ])
            </div>
            <div id="Unlock" class="container tab-pane fade" style="padding: 0px">
                @include('tables.tool_box_log', [
                    'title' => 'Action Unlock',
                    'tableId' => 'unlock-action',
                    'collapsed' => false,
                    'plus_link' => false,
                    'roll_back' => false,
                    'data' => $unlocks,
                    'header' => ['Action List','Description', 'Reason', 'Created By', 'Date', 'Result']
                ])

{{--                 @include('tables.tool_box_log', [
                    'title' => 'Rollback Action Unlock ',
                    'tableId' => 'unlock-back-action',
                    'collapsed' => true,
                    'plus_link' => false,
                    'roll_back' => false,
                    'data' => $unlocks_back,
                    'header' => ['Action List','Description', 'Reason', 'Created By', 'Date', 'Result']
                ]) --}}
            </div>
            <div id="Merge" class="container tab-pane fade" style="padding: 0px">
                @include('tables.tool_box_log', [
                    'title' => 'Action Merge',
                    'tableId' => 'merge-action',
                    'collapsed' => false,
                    'plus_link' => false,
                    'roll_back' => true,
                    'data' => $merges,
                    'header' => ['Action List','Description', 'Reason', 'Created By', 'Date', 'Result',''],
                    'order_table' => '[]'
                ])

                @include('tables.tool_box_log', [
                    'title' => 'Rollback Action Merge ',
                    'tableId' => 'merge-back-action',
                    'collapsed' => true,
                    'plus_link' => false,
                    'roll_back' => false,
                    'data' => $merges_back,
                    'header' => ['Action List','Description', 'Reason', 'Created By', 'Date', 'Result']
                ])
            </div>
            <div id="Revert-Back" class="container tab-pane fade" style="padding: 0px">
                @include('tables.tool_box_log', [
                    'title' => 'Action Revert',
                    'tableId' => 'revert-action',
                    'collapsed' => false,
                    'plus_link' => false,
                    'roll_back' => false,
                    'data' => $reverts,
                    'header' => ['Action List','Description', 'Reason', 'Created By', 'Date', 'Result']
                ])

{{--                 @include('tables.tool_box_log', [
                    'title' => 'Rollback Action Revert ',
                    'tableId' => 'revert-back-action',
                    'collapsed' => true,
                    'plus_link' => false,
                    'roll_back' => false,
                    'data' => $reverts_back,
                    'header' => ['Action List','Description', 'Reason', 'Created By', 'Date', 'Result']
                ]) --}}
            </div>
            @include('modals.admin_tool_rollback',['color' => 'red', 'modal_id' => 'roll-back', 'unique_value' => \Str::random(10) ])
        </div>
    </div>
</div>
</div>
@endsection
@push('javascript')
<script type="text/javascript">

</script>
@endpush

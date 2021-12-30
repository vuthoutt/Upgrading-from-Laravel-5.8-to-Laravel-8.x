@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'items_list', 'data' => $location, 'color' => 'red'])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $location->reference ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_search', [
            'backRoute' =>  route('shineCompliance.location.list',['area_id' => $location->area_id ?? 0]),
            'addRoute' => $can_add_new ? route('shineCompliance.item.get_add',['location_id' => $location->id ?? 0]) : false,
            'is_locked' => (($location->is_locked === LOCATION_LOCKED)? TRUE : FALSE)
        ])
        <div class="row">
            @include('shineCompliance.properties.partials._location_sidebar',[
                    'location_id' => $location->id,
                    'image' => \ComplianceHelpers::getFileImage($location->id ?? 0, LOCATION_IMAGE)
                    ])
            <div class="col-md-9 pl-0 pr-0" style="{{ ($location->is_locked === LOCATION_LOCKED) ? "margin-top:15px;" : ''}}">
                @if($location->is_locked === LOCATION_LOCKED)
                    <div style="display: inline-block">
                        <div class="spanWarningSurveying" style="margin: 0">
                            @if(isset($area))
                                <strong><em>Area/Floor is view only while surveying or assessing is in progress</em></strong>
                            @elseif(isset($location))
                                <strong><em>Room/location is view only while surveying or assessing is in progress</em></strong>
                            @elseif(isset($asbestos))
                                <strong><em>Item is view only surveying is in progress</em></strong>
                            @else
                                <strong><em>Rcf Item is view only assessing is in progress</em></strong>
                            @endif
                        </div>
                    </div>
                @endif
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_REGISTER_ASBESTOS, JOB_ROLE_ASBESTOS))
                @else
                    @if($type)
                        @if($type == TYPE_INACCESS_ROOM_SUMMARY)
                            @include('shineCompliance.tables.inaccess_locations',[
                                    'title' => $title,
                                    'tableId' => $table_id,
                                    'collapsed' => false,
                                    'plus_link' => false,
                                    'pagination_type' => '',
                                    'data' => $items_summary_table
                                ])
                        @else
                        @include('shineCompliance.tables.item_summary', [
                            'title' => $title,
                            'tableId' => $table_id,
                            'collapsed' => false,
                            'plus_link' => false,
                            'data' => $items_summary_table,
                            'pagination_type' => '',
                            'summary' => true,
                            'header' => ['Summary','Area/floor Reference','Room/location Reference','Product/debris type', 'MAS',''],
                            'order_table' => 'mas-risk-child'
                            ])
                        @endif
                    @else
                        @include('shineCompliance.tables.property_register_summary', [
                            'title' => 'Room/location Assessment Summary',
                            'tableId' => 'location-assetment-summary',
                            'collapsed' => false,
                            'plus_link' => false,
                            'normalTable' => true,
                            'link' => route('shineCompliance.item.get_add',['location' => $location->id]),
                            'count' => $dataSummary["All ACM Items"]['number'],
                            'data' => $dataSummary,
                            'register' => true,
                            'pagination_type' => ''
                            ])

                        @include('shineCompliance.tables.property_decommissioned_items', [
                            'title' => 'Room/location Decommissioned Items',
                            'tableId' => 'location-dec-item',
                            'collapsed' => true,
                            'plus_link' => false,
                            'header' =>  ['Reference','Product/debris type','MAS','Reason', 'Item Comments'],
                            'data' => $dataDecommisstionItems,
                            'pagination_type' => ''
                            ])
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>


@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            $('#filter').click( function(e) {
                $('.add').toggleClass('add-aimation');
            });
            $('#close-form').click( function() {
                $('.add').removeClass('add-aimation');
            });
        });
    </script>
@endpush

@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' =>'item_list', 'color' => 'red', 'data' =>$location ?? ''])
<div class="container prism-content pad-up">
    <div class="row">
        <h3 style="margin: 0">{{ $location->name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_search', [ 'backRoute' => '#','addRoute' => '#'])
        <div class="row">
            @include('shineCompliance.properties.partials._location_sidebar',[
                    'location_id' => $location->id,
                    'image' => \ComplianceHelpers::getFileImage($item->id, ITEM_PHOTO_LOCATION)
                    ])
            <div class="column-right" style="padding: 0" >
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
                        'plus_link' => true,
                        'normalTable' => true,
                        'link' => route('item.get_add',['location' => $location->id]),
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

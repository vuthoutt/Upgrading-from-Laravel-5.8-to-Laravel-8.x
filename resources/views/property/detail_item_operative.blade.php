@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => $breadcrumb_name,'data' => $breadcrumb_data])

    <div class="container prism-content">
            <div style="border-bottom:1px #dddddd solid;text-align: center;">
                <img width="150px" src="{{ CommonHelpers::getFile(1, CLIENT_LOGO) }}" alt="{{ env('APP_DOMAIN') ?? 'GSK' }}" style="margin-bottom: 30px;"/>
            </div>
            <h3 class="offset-top20">{!! $location->reference . " - " . $location->description !!}</h3>
            @if($risk_type_two)
                <div class="offset-top20 offset-bottom20 notification-lsop">
                    <strong><em>Property Built In or After 2000, No Asbestos Detected</em></strong>
                </div>
            @elseif($risk_type_one)
                @if($location->state == LOCATION_STATE_INACCESSIBLE)
                    <div class="offset-top20 offset-bottom20 notification-lsop-warning">
                        <strong><em>Inaccessible Room/locations; Asbestos must be Presumed to be Present;<br> An Inspection
                                to be
                                Undertaken Prior to any Routine Occupation,<br> Please contact the Asbestos Team for Further
                                Advice </em>
                        </strong>
                    </div>
                @else
                    @if($location->countItemACM->count() == 0 && $location->allAccessibleItemACM->count() == 0)
                        <div class="offset-top20 offset-bottom20 notification-lsop-warning">
                            <strong><em>Management Survey conducted and no Presumed/Identified Asbestos Found; <br>
                                    Further Inspection to be Undertaken Prior to Refurbishment Works. </em>
                            </strong>
                        </div>
                    @endif
                @endif
                @if($is_inacc_void_location)
                    <div class="offset-top20 offset-bottom20 notification-lsop-warning">
                        <strong><em>Inaccessible Void Asbestos must be Presumed to be Present. </em>
                        </strong>
                    </div>
                @endif
            @endif
            <div class="offset-top20 offset-bottom60">
                @include('tables.void_investigation',[
                                    'title' => 'Void Investigation',
                                    'tableId' => 'void_investigation',
                                    'notCountable' => true,
                                    'collapsed' => false,
                                    'plus_link' => false,
                                    'data' => $location,
                                    'header' => false
                                ])
            </div>
        <?php
        $location_data_stamping = CommonHelpers::get_data_stamping($location);
        ?>
        <table width="98%" class="offset-top20 offset-bottom60 ml-3">
            <tr>
                <td width="10%">Data Stamp:</td>
                <td style="padding-left:5px;"><?= $location_data_stamping['data_stamping']; ?></td>
            </tr>
            <tr>
                <td width="10%">Organisation:</td>
                <td style="padding-left:5px;"><?= $location_data_stamping['organisation']; ?></td>
            </tr>
            <tr>
                <td width="10%">Username:</td>
                <td style="padding-left:5px;"><?= $location_data_stamping['username']; ?></td>
            </tr>
            <tr>
                <td width="10%">Creation Date:</td>
                <td style="padding-left:5px;"><?= $location_data_stamping['data_stamping_create']; ?></td>
            </tr>
            <tr>
                <td width="10%">Organisation:</td>
                <td style="padding-left:5px;"><?= $location_data_stamping['organisation_create']; ?></td>
            </tr>
            <tr>
                <td width="10%">Username:</td>
                <td style="padding-left:5px;"><?= $location_data_stamping['username_create']; ?></td>
            </tr>
        </table>

        <!-- list property operative -->
        <div class="container">
            @if(count($items))
                @php
                //dd($items);
                // merger item from survey
                $count = 0;
                @endphp
                <div class="row">
                    @foreach($items as $key => $item)
                        @php
                            //dd($item);
                        $display = CommonHelpers::getTotalRiskText($item->total_risk);
                        @endphp
                        <div class="col-3">
                            <!-- update link item normal -->
                            <a href="{{ route('item.index', ['id' => $item->id, 'position' => $key, 'pagination_type' => TYPE_SITE_OPERSTIVE])}}" style="text-decoration: none;">
                                <div class="property-opt">
                                    <div class="unit-operative">
                                        <img class="img-client-operative" src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO) }}" alt="Item Image" style="width:100%">
                                    </div>

                                    <div class="property-opt-des" style="background: {{$display['bg_color']}}; color: {{$display['color']}};">
                                        <strong class="">{{$display['risk']}}</strong>
                                    </div>

                                    <div class="name-field" title="{{ $item->reference }}">
                                        <strong class="name">{!! $item->reference !!}</strong>
                                    </div>
                                    <!-- download button -->
                                </div>
                            </a>
                        </div>
                        @if($count > 0 && (($count+1)%4) == 0)
                            </div><div class="row">
                        @endif
                    @php
                        // thieu item from survey
                            $count++;
                    @endphp
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            $(".name").each(function(){
                var showChar = 37;
                var text = $(this).text();

                if(text.length > 37){
                    text = text.substring(0, 36) + " ...";
                }
                $(this).text(text);

                $('#collapse-void_investigation .card-body').css('padding', '0');
            });
        });
    </script>
@endpush

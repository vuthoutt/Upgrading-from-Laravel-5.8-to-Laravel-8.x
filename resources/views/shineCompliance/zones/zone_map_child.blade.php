@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav', ['color' => 'red'])

<div class="container prism-content">

    <div style="border-bottom:1px #dddddd solid;text-align: center;">
        <img width="150px" src="{{ \ComplianceHelpers::getFile(1, CLIENT_LOGO) }}" alt="{{ env('APP_DOMAIN') ?? 'NHG' }}" style="margin-bottom: 30px;"/>
    </div>

        <div class="row mt-5 ml-2">
            <div style="" class="site-map">
                <a href="{{ route('zone.group',['zone_id' => "",'client_id' => ""]) }}" style="text-decoration: none">
                <img  src="{{ asset('img/Corporate.png') }}" alt="City Of Westminster" height="160px"  class="image-map">
                <div class="light_grey_gradient span-image-map">
                    <span style="color:#333333"><b>Corporate Properties</b></span>
                </div>
                </a>
            </div>
            <div class="site-map">
                <a href="{{ route('zone.group',['zone_id' => "",'client_id' => ""]) }}" style="text-decoration: none">
                <img  src="{{ asset('img/Commercial.png') }}" alt="City Of Westminster" height="160px"  class="image-map"/>
                <div class="light_grey_gradient span-image-map">
                    <span style="color:#333333"><b>Commercial Properties</b></span>
                </div>
                </a>
            </div>
            <div class="site-map">
                <a href="{{ route('zone.group',['zone_id' => "",'client_id' => ""]) }}" style="text-decoration: none">
                <img  src="{{ asset('img/Non-Domestic.png') }}" alt="City Of Westminster"  height="160px"  class="image-map"/>
                <div class="light_grey_gradient span-image-map">
                    <span style="color:#333333"><b>Housing – Communal</b></span>
                </div>
                </a>
            </div>
            <div class="site-map">
                <a href="{{ route('zone.group',['zone_id' => "",'client_id' => ""]) }}" style="text-decoration: none">
                <img  src="{{ asset('img/Domestic.png') }}" alt="City Of Westminster" height="160px" class="image-map"/>
                <div class="light_grey_gradient span-image-map">
                    <span style="color:#333333"><b>Housing - Domestic </b></span>
                </div>
                </a>
            </div>
    </div>
</div>
@endsection

@push('javascript')
    <script>
        $(document).ready(function(){
              $(function() {
                $('.map').maphilight(
                 {
                    fill: true,
                    fillColor: '000000',
                    fillOpacity: 0.2,
                    stroke: true,
                    strokeColor: 'ffffff',
                    strokeOpacity: 1,
                    strokeWidth: 1,
                    fade: true,
                    alwaysOn: false,
                    neverOn: false,
                    groupBy: false,
                    wrapClass: true,
                    shadow: false,
                    shadowX: 0,
                    shadowY: 0,
                    shadowRadius: 6,
                    shadowColor: '000000',
                    shadowOpacity: 0.8,
                    shadowPosition: 'outside',
                    shadowFrom: false
                }
                );
              });
        });
    </script>
@endpush

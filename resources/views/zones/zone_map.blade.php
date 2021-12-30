@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'zone_map','data' => ''])
<style>
    .site-map {
        margin-bottom: 34px;
        width: 43%;
        margin-left: 40px;
        border: solid 2px #bababa;
        text-align: center;
        height: 250px;
        border-radius: 10px 10px 0px 0px;
    }
</style>
<div class="container prism-content">

    <div style="text-align: center;">
        <img width="300px" src="{{ CommonHelpers::getFile(1, CLIENT_LOGO) }}" alt="{{ env('APP_DOMAIN') ?? 'GSK' }}" />
    </div>

    <div>
        <div style="float: left;border-right: 1px solid silver;margin-left: -25px;">
            <img src="{{ asset('img/map.png') }}" border="0" usemap="#image-map" class="map" >
            <map name="image-map">
                <area data-maphilight='{"strokeColor":"FBB03B"}' target="" class="south_area" alt="" title="South Area" href="{{ route('zone_map_child',['zone_id' => 4,'client_id' => $client_id]) }}"shape="poly" alt="" coords="531,218, 531,222, 532,228, 534,233, 536,240, 538,246, 541,248, 546,252, 548,254, 550,254, 555,249, 565,244, 577,242, 590,238, 600,234, 608,229, 618,224, 623,222, 625,225, 627,229, 628,236, 632,245, 639,255, 642,266, 643,274, 643,279, 636,280, 629,282, 622,285, 611,290,
604,295, 600,301,593,309, 589,324, 587,343, 586,357, 586,363, 586,369, 585,383, 583,400, 582,414, 581,422, 579,431, 576,444, 575,453, 573,460, 566,473, 557,488, 546,502, 536,512, 524,517, 516,520, 511,522, 503,525, 498,526, 492,526, 482,526, 476,526, 471,524, 463,522, 457,521, 453,519, 448,514, 442,511, 432,507, 422,503, 417,501, 411,496,
402,487, 397,479, 397,471, 397,460, 397,454, 396,449, 393,439, 390,429, 389,424, 388,420, 387,416, 386,410, 386,402, 385,398, 382,395, 377,390, 374,387, 373,383, 372,374, 371,368, 370,360, 370,356, 366,357, 359,361, 354,364, 351,369, 347,372, 342,374, 333,380, 324,385, 319,388, 313,388, 307,389, 296,392, 288,395, 284,395,
281,395, 275,396, 264,398, 253,400, 247,401, 246,396, 245,386, 244,378, 243,371, 243,367, 239,367, 232,367, 228,367, 224,365, 219,365, 217,359, 213,345, 208,325, 202,306, 198,292, 196,286, 200,285, 213,283, 229,279, 245,276, 258,273, 264,272, 272,270, 291,266, 314,261, 337,256, 355,252, 363,251, 371,250, 389,246, 413,241,
439,235, 464,230, 482,226, 490,225, 498,224, 512,221, 525,219, 531,218">
                <area data-maphilight='{"strokeColor":"22B573"}' target="" alt="" title="West Area" href="{{ route('zone_map_child',['zone_id' => 5,'client_id' => $client_id]) }}" shape="poly" alt="" coords="343,249, 346,249, 352,248, 354,246, 352,243, 346,237, 334,225, 319,208, 302,191, 289,177, 280,167, 270,155, 257,141, 245,128, 239,121, 234,113, 229,108, 225,111, 221,115, 217,117, 210,121, 202,126, 189,136, 173,146, 159,155, 152,159, 149,159, 146,160, 139,161, 128,164,
120,167, 114,169,115,173, 118,178, 120,186, 122,199, 122,210, 124,221, 127,231, 127,237, 128,239, 132,239, 140,238, 149,236, 153,235, 154,238, 156,247, 158,257, 159,262, 159,264, 160,266, 164,264, 166,268, 169,275, 171,279, 170,282, 175,281, 187,280, 201,279, 214,278, 220,277, 228,276, 245,272, 268,267, 294,261, 317,256, 335,251, 343,249">
                <area data-maphilight='{"strokeColor":"276EAB"}' title="North Area" shape="poly" alt="" href="{{ route('zone_map_child',['zone_id' => 3,'client_id' => $client_id]) }}" coords="225,104, 222,100, 215,91, 205,79, 197,69, 192,62, 184,51, 177,42, 170,37, 165,31, 163,36, 160,46, 157,52, 157,55, 160,58, 160,63, 157,72, 156,76, 138,109, 137,111, 133,114, 130,111, 129,109, 128,103, 128,98, 128,90, 124,84, 116,78, 109,71, 101,65, 89,64, 73,66, 60,70,
53,74, 47,75, 36,78,
27,84, 28,87, 29,91, 28,94, 27,95, 27,99, 26,106, 26,111, 28,115, 31,121, 33,123, 39,123, 52,123, 67,125, 77,128, 84,135, 87,141, 90,147, 96,154, 107,158, 109,160, 110,165, 116,163, 127,159, 139,157, 148,153, 165,144, 184,132, 203,120, 218,110, 225,104">
                <area data-maphilight='{"strokeColor":"C1272D"}' target="" class="central_area" alt="" title="Central Area" href="{{ route('zone_map_child',['zone_id' => 2,'client_id' => $client_id]) }}" shape="poly" alt="" coords="360,246, 367,245, 384,241, 410,236, 439,230, 468,224, 495,219, 514,215, 524,214, 522,212, 517,208, 511,203, 503,196, 499,189, 498,189, 494,186, 490,180, 481,169, 472,158, 465,149, 462,145, 458,145, 448,143, 438,134, 433,123, 427,105, 420,82, 413,59, 407,39, 403,24,
401,18, 397,18, 384,21,
362,30, 355,21, 351,23, 343,28, 334,33, 330,36, 326,32, 317,23, 307,13, 302,9, 299,12, 296,16, 294,19, 288,12, 280,19, 275,15, 276,2, 271,2, 260,2, 253,1, 247,1, 238,3, 231,7, 223,12, 217,15, 213,22, 205,34, 196,46, 191,52, 195,57, 205,70, 219,87, 233,105, 244,120, 250,128, 256,135, 268,149, 284,167, 303,188, 323,208, 340,226,
353,240, 360,246">
                <area data-maphilight='{"strokeColor":"#aaaaaa"}' title="Out of Borough" href="{{ route('zone_map_child',['zone_id' => 6,'client_id' => $client_id]) }}" shape="poly" alt="" coords="651,135, 651,1, 554,1, 457,1, 456,3, 457,6, 459,13, 463,27, 468,46, 473,65, 477,80, 481,87, 489,91, 498,95, 504,103, 516,118, 531,135, 545,151, 555,159, 558,161, 559,163, 561,167, 563,174, 567,174, 573,173, 585,172, 594,170, 610,163, 628,153, 643,143, 651,135">
            </map>
        </div>
{{--        <div style="float:right">--}}
            <div class="row mt-5 ml-2">
                <div class=" row col-md-12">
                    <div style="" class="site-map">
                        <a href="{{ route('shineCompliance.zone.listing',['type' => CORPORATE_PROPERTIES,'client_id' => $client_id]) }}" style="text-decoration: none">
                            <img  src="{{ asset('img/Corporate.png') }}" alt="City Of Westminster" height="160px"  class="image-map">
                            <div class="light_grey_gradient span-image-map">
                                <span style="color:#333333"><b>Corporate Properties</b></span>
                            </div>
                        </a>
                    </div>
                    <div class="site-map">
                        <a href="{{ route('shineCompliance.zone.listing',['type' => COMMERCIAL_PROPERTIES,'client_id' => $client_id]) }}" style="text-decoration: none">
                            <img  src="{{ asset('img/Commercial.png') }}" alt="City Of Westminster" height="160px"  class="image-map"/>
                            <div class="light_grey_gradient span-image-map">
                                <span style="color:#333333"><b>Commercial Properties</b></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class=" row col-md-12">
                    <div class="site-map">
                        <a href="{{ route('shineCompliance.zone.listing',['type' => HOUSING_COMMUNAL,'client_id' => $client_id]) }}" style="text-decoration: none">
                            <img  src="{{ asset('img/Non-Domestic.png') }}" alt="City Of Westminster"  height="160px"  class="image-map"/>
                            <div class="light_grey_gradient span-image-map">
                                <span style="color:#333333"><b>Housing – Communal</b></span>
                            </div>
                        </a>
                    </div>
                    <div class="site-map">
                        <a href="{{ route('shineCompliance.zone.listing',['type' => HOUSING_DOMESTIC,'client_id' => $client_id]) }}" style="text-decoration: none">
                            <img  src="{{ asset('img/Domestic.png') }}" alt="City Of Westminster" height="160px" class="image-map"/>
                            <div class="light_grey_gradient span-image-map">
                                <span style="color:#333333"><b>Housing - Domestic </b></span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
{{--        </div>--}}

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
                    strokeColor: 'C1282D',
                    strokeOpacity: 1,
                    strokeWidth: 2,
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

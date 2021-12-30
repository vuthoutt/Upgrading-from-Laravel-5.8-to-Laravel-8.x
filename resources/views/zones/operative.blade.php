@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'zones','data' => $breadcrumb_data])

<div class="container prism-content">


    <div style="border-bottom:1px #dddddd solid;text-align: center;">
        <img width="150px" src="{{ CommonHelpers::getFile(1, CLIENT_LOGO) }}" alt="{{ env('APP_DOMAIN') ?? 'GSK' }}" style="margin-bottom: 30px;"/>
    </div>
    <!-- list property operative -->
    <div class="container">
        @if($all_zones)
            <div class="row">
            @foreach($all_zones as $key => $zone)
                <div class="col-3">
                     @if(env('SETTING_PROPERTY_MAP'))
                    <a href="{{route('zone_map_child',['zone_id'=>$zone->id, 'client_id' => $client->id])}}" style="text-decoration: none;">
                    @else
                    <a href="{{route('zone.group',['zone_id'=>$zone->id, 'client_id' => $client->id])}}" style="text-decoration: none;">
                    @endif
                        <div class="property-opt">
                            <div class="unit-operative">
                                <img class="img-client-operative" src="{{ CommonHelpers::getFile($zone->id, ZONE_PHOTO) }}" alt="Property Image" style="width:100%">
                            </div>
                            <div class="property-opt-des" style="background: {{$zone->zone_operative['bg_color']}}; color: {{$zone->zone_operative['text_color']}};">
                                <em class="des-field">{{$zone->zone_operative['text']}}</em>
                            </div>
                            <div class="name-field" title="{{$zone->zone_name}}">
                                <strong class="name">{!! $zone->zone_name !!}</strong>
                            </div>
                        </div>
                    </a>
                </div>
                @if($key > 0 && (($key+1)%4) == 0)
                    </div><div class="row">
                @endif
            @endforeach
            </div>
        @endif
    </div>
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
            });
        });
    </script>
@endpush

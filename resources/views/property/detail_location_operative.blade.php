@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => $breadcrumb_name,'data' => $breadcrumb_data])

    <div class="container prism-content">


        <div style="border-bottom:1px #dddddd solid;text-align: center;">
            <img width="150px" src="{{ CommonHelpers::getFile(1, CLIENT_LOGO) }}" alt="{{ env('APP_DOMAIN') ?? 'GSK' }}" style="margin-bottom: 30px;"/>
        </div>
        <h3 class="offset-top20">{!! $areaData->reference . " - " . $areaData->description !!}</h3>
        @if($risk_type_two)
            <div class="offset-top20 offset-bottom60 notification-lsop">
                <strong><em>Property Built In or After 2000, No Asbestos Detected</em></strong>
            </div>
        @endif
        <!-- list property operative -->
        <div class="container">
            @if(!$locations->isEmpty())
                @php
                //dd($locations);
                @endphp
                <div class="row">
                    @foreach($locations as $key => $location)
                        @php
                           // dd($location);
                        @endphp
                        <div class="col-3">
                                <div class="property-opt">
                                    <a href="{{ route('property.operative.detail',['id' => $properties->id,'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $location->id] ) }}" style="text-decoration: none;">
                                        <div class="unit-operative">
                                            <img class="img-client-operative" src="{{ CommonHelpers::getFile($location->id, LOCATION_IMAGE) }}" alt="Location Image" style="width:100%">
                                        </div>
                                        <div class="property-opt-des" style="background: {{$location->location_operative['bg_color']}}; color: {{$location->location_operative['text_color']}};">
                                            <em class="des-field">{{$location->location_operative['text']}}</em>
                                        </div>

                                        <div class="property-opt-des" style="background: #7E7E7E; color: #FFFFFF;">
                                            @php

                                            @endphp
                                            <strong class="">ACM Items: {{ !is_null($location->countItemACM) ? count($location->countItemACM) : 0}}</strong>
                                        </div>
                                        <div class="name-field" title="{{$location->location_reference}}">
                                            <strong class="name">{!! $location->location_reference . " - " . $location->description !!}</strong>
                                        </div>
                                    </a>
                                    <div class="download-operative">
                                        <a title="Download Asbestos Room/Location Image" href="{{route('register.pdf',['type'=>LOCATION_REGISTER_PDF,'id'=>$location->id])}}" class="btn btn-outline-secondary w-100 download-pdf-btn">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                    <!-- download button -->
                                </div>
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

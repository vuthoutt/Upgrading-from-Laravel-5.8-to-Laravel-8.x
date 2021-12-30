@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => $breadcrumb_name,'data' => $breadcrumb_data])

    <div class="container prism-content">


        <div style="border-bottom:1px #dddddd solid;text-align: center;">
            <img width="150px" src="{{ route('retrive_image',['type'=>  CLIENT_LOGO ,'id'=> 1 ]) }}" alt="{{ env('APP_DOMAIN') ?? 'GSK' }}" style="margin-bottom: 30px;"/>
        </div>
        <h3 class="offset-top20">{!! $propertyData->name !!}</h3>
        <!-- list property operative -->
        <div class="container">
            @if(!$areas->isEmpty())
                @php
                //dd($areas);
                @endphp
                <div class="row">
                    @foreach($areas as $key => $area)
                        <div class="col-3">
                                <div class="property-opt">
                                    <a href="{{ route('property.operative.detail',['id' => $propertyData->id,'section' => SECTION_AREA_FLOORS_SUMMARY, 'area' => $area->id] ) }}" style="text-decoration: none;">
                                        <div class="unit-operative">
                                            <img class="img-client-operative" src="{{ asset('img/area_empty.jpg') }}" alt="Area Image" style="width:100%">
                                        </div>
                                        <div class="property-opt-des" style="background: {{$area->area_operative['bg_color']}}; color: {{$area->area_operative['text_color']}};">
                                            <em class="des-field">{{ $area->area_operative['text'] }}</em>
                                        </div>
                                        <div class="name-field" title="{{ $area->area_reference }}">
                                            <strong class="name">{!! $area->area_reference . " - " . $area->description !!}</strong>
                                        </div>
                                    </a>
                                    <div  class="download-operative">
                                        <a title="Download Asbestos Area/Floor Image" href="{{route('register.pdf',['type'=>AREA_REGISTER_PDF,'id'=>$area->id])}}" class="btn btn-outline-secondary w-100 download-pdf-btn">
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

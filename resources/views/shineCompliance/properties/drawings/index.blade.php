@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav', ['color' => 'red'])

<div class="container prism-content pad-up">
    <div class="row">
        <h3 style="margin: 0">1 Broadley Street</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_search',[ 'backRoute' => route('shineCompliance.property'),'addRoute' =>'#'])
        <div class="row">
            @include('shineCompliance.properties.partials._property_sidebar')
            <div class="col-md-9 pl-0 pr-0" style="padding: 0">
                <div  class="card-data mar-up">
                    <a href="{{ route('shineCompliance.property.drawings.view', 1234) }}" class="card card-img card-img-deco col-md-4" style="padding:0;">
                        <img class="card-img-top unset-border" src="{{ asset('img/system.png') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding" >
                            <strong class="str-color">Ground Floor Plan</strong>
                            <div class="row">
                                <label class="col-md-6">Shine Reference:</label>
                                <div class="col-md-6">
                                    PP000
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Area/Floor Reference:</label>
                                <div class="col-md-6">
                                    00
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Area/Floor Description:</label>
                                <div class="col-md-6">
                                    Ground Floor
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Version:</label>
                                <div class="col-md-6">
                                    VER2.0
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Latest Revision:</label>
                                <div class="col-md-6">
                                    00/00/0000
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('shineCompliance.property.drawings.view', 1234) }}" class="card card-img card-img-deco col-md-4" style="padding:0;">
                        <img class="card-img-top unset-border" src="{{ asset('img/system.png') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding" >
                            <strong class="str-color">First Floor Plan</strong>
                            <div class="row">
                                <label class="col-md-6">Shine Reference:</label>
                                <div class="col-md-6">
                                    PP000
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Area/Floor Reference:</label>
                                <div class="col-md-6">
                                    00
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Area/Floor Description:</label>
                                <div class="col-md-6">
                                    First Floor
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Version:</label>
                                <div class="col-md-6">
                                    VER2.0
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Latest Revision:</label>
                                <div class="col-md-6">
                                    00/00/0000
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('shineCompliance.property.drawings.view', 1234) }}" class="card card-img card-img-deco col-md-4" style="padding:0;">
                        <img class="card-img-top unset-border" src="{{ asset('img/system.png') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding" >
                            <strong class="str-color">2nd Floor Plan</strong>
                            <div class="row">
                                <label class="col-md-6">Shine Reference:</label>
                                <div class="col-md-6">
                                    PP000
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Area/Floor Reference:</label>
                                <div class="col-md-6">
                                    00
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Area/Floor Description:</label>
                                <div class="col-md-6">
                                    2nd Floor
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Version:</label>
                                <div class="col-md-6">
                                    VER2.0
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Latest Revision:</label>
                                <div class="col-md-6">
                                    00/00/0000
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div  class="card-data mar-up">
                    <a href="{{ route('shineCompliance.property.drawings.view', 1234) }}" class="card card-img card-img-deco col-md-4" style="padding:0;">
                        <img class="card-img-top unset-border" src="{{ asset('img/system.png') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding" >
                            <strong class="str-color">3rd Floor Plan</strong>
                            <div class="row">
                                <label class="col-md-6">Shine Reference:</label>
                                <div class="col-md-6">
                                    PP000
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Area/Floor Reference:</label>
                                <div class="col-md-6">
                                    00
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Area/Floor Description:</label>
                                <div class="col-md-6">
                                    3rd Floor
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Version:</label>
                                <div class="col-md-6">
                                    VER2.0
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Latest Revision:</label>
                                <div class="col-md-6">
                                    00/00/0000
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('shineCompliance.layouts.app')

@section('content')
    @include('shineCompliance.partials.nav')
    <style>
        .card-data-item{
            margin-right: 20px;
        }
        .row p strong{
            color: black;
        }
        .detail-item{
            padding-left: 0px;
            padding-right: 10px;
            margin-top: 10px;
        }
        .overall{
            padding-left: 10px;
            padding-right: 0px;
            margin-top: 10px;
        }
        .risk{
            margin-top: 20px;
        }
    </style>
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">Electrical Hazard #1</h3>
        </div>
        <div class="main-content mar-up">
            @include('shineCompliance.properties.partials._property_button',['backRoute' => url()->previous()])
            @if($type == WATER)
                <div class="row">
                    <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                        <div style="width:250px; " >
                            <ul class="list-group">
                                <p><strong>Location</strong></p>
                                <div class="list-group-img">
                                    <img src="{{ asset('img/WaterLocation.png') }}"  width="100%" height="200px" alt="">
                                </div>
                                <div class="list-group-button">
                                    <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                        <div style="width:250px; " >
                            <ul class="list-group">
                                <p><strong>Hazard</strong></p>
                                <div class="list-group-img">
                                    <img src="{{ asset('img/WaterHazard.png') }}"  width="100%" height="200px" alt="">
                                </div>
                                <div class="list-group-button">
                                    <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                        <div style="width:250px; " >
                            <ul class="list-group">
                                <p><strong>Additional</strong></p>
                                <div class="list-group-img">
                                    <img src="{{ asset('img/WaterAdditional.png') }}"  width="100%" height="200px" alt="">
                                </div>
                                <div class="list-group-button">
                                    <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                        <div style="width:250px; " >
                            <ul class="list-group">
                                <p><strong>Location</strong></p>
                                <div class="list-group-img">
                                    <img src="{{ asset('img/Location.jpg') }}"  width="100%" height="200px" alt="">
                                </div>
                                <div class="list-group-button">
                                    <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                        <div style="width:250px; " >
                            <ul class="list-group">
                                <p><strong>Hazard</strong></p>
                                <div class="list-group-img">
                                    <img src="{{ asset('img/Item.jpg') }}"  width="100%" height="200px" alt="">
                                </div>
                                <div class="list-group-button">
                                    <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                        <div style="width:250px; " >
                            <ul class="list-group">
                                <p><strong>Additional</strong></p>
                                <div class="list-group-img">
                                    <img src="{{ asset('img/Additional.jpg') }}"  width="100%" height="200px" alt="">
                                </div>
                                <div class="list-group-button">
                                    <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                @if($type ==  WATER)
                    <div class="col-md-6 detail-item" >
                        <div class="card discard-border-radius">
                            <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                            <div class="card-body">
                                @include('shineCompliance.forms.form_text',['title' => 'Hazard Name:', 'data' =>  'Cleanliness #1' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Hazard ID:', 'data' => 'IN37978' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Assessed:', 'data' =>'Assessed' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Reason:', 'data' =>  '' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Type:', 'data' => 'Accessible' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Area/floor Reference:', 'data' => '00' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Room/floor Reference:', 'data' => '01S03' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Specific Location:', 'data' => 'Plant' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Extent:', 'data' => '1 Number' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Action/recommendation:', 'data' => 'Remove' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Comments:', 'data' => '' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Data Stamp:', 'data' => '10/05/2020 14:30' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Organisation:', 'data' => Str::title(str_replace('_',' ',$type)) . ' Management Ltd - Technical' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Username:', 'data' => 'Charles Richards' ])
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-6 detail-item" >
                        <div class="card discard-border-radius">
                            <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                            <div class="card-body">
                                @include('shineCompliance.forms.form_text',['title' => 'Hazard Name:', 'data' =>  'Electrical Hazard #1' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Hazard ID:', 'data' => 'IN39440' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Assessed:', 'data' =>'Assessed' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Reason:', 'data' =>  '' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Type:', 'data' => 'Accessible' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Area/floor Reference:', 'data' => '01' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Room/floor Reference:', 'data' => '1B229' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Specific Location:', 'data' => 'Plant' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Extent:', 'data' => '1 Number' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Action/recommendation:', 'data' => 'Remove' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Comments:', 'data' => '' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Data Stamp:', 'data' => '09/07/2020 11:17' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Organisation:', 'data' => Str::title(str_replace('_',' ',$type)) . ' Management Ltd - Technical' ])
                                @include('shineCompliance.forms.form_text',['title' => 'Username:', 'data' => 'Richard Thompson' ])
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-6 overall" >
                    <div class="card discard-border-radius ">
                        <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>{!! Str::title(str_replace('_',' ',$type)) !!} Risk Assessment</strong></div>
                        <div class="card-body">
                            <div class="row register-form">
                                <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Hazard Potential:</label>
                                <div class="col-md-5">
                                    <span id="risk-color" class="badge orange" style="width: 30px;">2</span>
                                    &nbsp;
                                    <span id="risk-text">Unlikely</span>
                                </div>
                            </div>
                            <div class="row register-form">
                                <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Likelihood of Harm:</label>
                                <div class="col-md-5">
                                    <span id="risk-color1" class="badge orange" style="width: 30px;">2</span>
                                    &nbsp;
                                    <span id="risk-text1">Slight</span>
                                </div>
                            </div>
                            <div class="row register-form">
                                <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Overall {!! Str::title(str_replace('_',' ',$type)) !!} Risk Assessment:</label>
                                <div class="col-md-5">
                                    <span id="risk-color2" class="badge orange" style="width: 30px;">2</span>
                                    &nbsp;
                                    <span id="risk-text2">Low Risk - Action Required within 3 Months of Assessment</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


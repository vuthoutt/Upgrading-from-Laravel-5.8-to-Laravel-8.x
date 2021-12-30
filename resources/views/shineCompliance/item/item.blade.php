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
    img{
        object-fit:cover !important;
    }
</style>
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">2474-124</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button',['backRoute' => url()->previous()])

        <div class="row ">
            <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                <div style="width:250px; " >
                    <ul class="list-group">
                        <p><strong>Location</strong></p>
                        <div class="list-group-img">
                            <img src="{{ asset('img/location.png') }}"  width="100%" height="200px" alt="">
                        </div>
                        <div class="list-group-button">
                            <button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button>
                        </div>
                    </ul>
                </div>
            </div>
            <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                <div style="width:250px; " >
                    <ul class="list-group">
                        <p><strong>ACM</strong></p>
                        <div class="list-group-img">
                            <img src="{{ asset('img/acm.png') }}"  width="100%" height="200px" alt="">
                        </div>
                        <div class="list-group-button">
                            <button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button>
                        </div>
                    </ul>
                </div>
            </div>
            <div  class="card-data card-data-item" style="margin-top: 15px!important;">
                <div style="width:250px; " >
                    <ul class="list-group">
                        <p><strong>Additional</strong></p>
                        <div class="list-group-img">
                            <img src="{{ asset('img/add.png') }}"  width="100%" height="200px" alt="">
                        </div>
                        <div class="list-group-button">
                            <button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 detail-item" >
                <div class="card discard-border-radius">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4;"><strong>Details</strong></div>
                    <div class="card-body">
                        @include('shineCompliance.forms.form_text',['title' => 'Item Name:', 'data' =>  '2474-124' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Item ID:', 'data' => 'IN21495' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Assessed:', 'data' =>'Assessed' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Reason:', 'data' =>  '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Type:', 'data' => 'Accessible' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Sample/link ID:', 'data' =>'0445' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Sample Comment:', 'data' =>'Bulk Sampling Undertaken' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Area/floor Reference:', 'data' => '01' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Room/floor Reference:', 'data' => '1B229' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Specific Location:', 'data' => 'Pipe Flange' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Product/debirs Type:', 'data' => 'Insulation debris/residue' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Asbestos Type:', 'data' => 'Identified Crocidolite'])
                        @include('shineCompliance.forms.form_text',['title' => 'Extent:', 'data' => '1 Item(s)' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Action/recommendation:', 'data' => 'Remove' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Comments:', 'data' => '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Data Stamp:', 'data' => '24/09/2020 09:08' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Organisation:', 'data' => 'Santia Asbestos Management Ltd - Technical' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Username:', 'data' => 'Rory Cross' ])
                    </div>
                </div>
            </div>
            <div class="col-md-6 overall" >
                <div class="card discard-border-radius ">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Overall Risk Assessment</strong></div>
                    <div class="card-body" >
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Material Risk Assessment:</label>
                            <div class="col-md-5" style="padding-top: 5px">
                              <span class="badge red" id="risk-color" style="width: 30px;margin-right: 10px">
                                    &nbsp;12&nbsp;
                              </span>
                                <span> High Risk</span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Priority Risk Assessment:</label>
                            <div class="col-md-5" style="padding-top: 5px">
                          <span class="badge orange" id="risk-color" style="width: 30px;margin-right: 10px">
                                5
                          </span>
                                <span> Low Risk</span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Overall Risk Assessment:</label>
                            <div class="col-md-5" style="padding-top: 5px">
                          <span class="badge brown" id="risk-color" style="width: 30px;margin-right: 10px">
                                17
                          </span>
                                <span> Medium Risk</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card discard-border-radius risk">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Material Risk Assessment</strong></div>
                    <div class="card-body">
                        @include('shineCompliance.forms.form_text',['title' => 'Product Type (a):', 'data' => 2 ])
                        @include('shineCompliance.forms.form_text',['title' => 'Extent of Damage (b):', 'data' => 1 ])
                        @include('shineCompliance.forms.form_text',['title' => 'Surface Treatment (c):', 'data' => 1 ])
                        @include('shineCompliance.forms.form_text',['title' => 'Asbestos Fibre (d):', 'data' => 1 ])
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Total:</label>
                            <div class="col-md-5">
                            <span id="total-MRA">
                                5
                            </span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Material Risk Assessment:</label>
                            <div class="col-md-5">
                              <span class="badge orange" id="risk-color" style="width: 30px;margin-right: 10px;">5
                              </span>
                                <span> Low Risk</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card discard-border-radius risk" >
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4;" ><strong>Priority Risk Assessment</strong></div>
                    <div class="card-body">
                        @include('shineCompliance.forms.form_text_right',['title' => 'Primary'])
                        @include('shineCompliance.forms.form_text',['title' => 'Product Type (a):', 'data' => '1' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Secondary:', 'data' =>' 1 '])

                        @include('shineCompliance.forms.form_text_right',['title' => 'Likelihood of Disturbance'])
                        @include('shineCompliance.forms.form_text',['title' => 'Location:', 'data' => '1' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Accessibility:', 'data' => '0' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Extent/Amount:', 'data' => '0 '])

                        @include('shineCompliance.forms.form_text_right',['title' => '  Human Exposure Potential'])
                        @include('shineCompliance.forms.form_text',['title' => 'Number:', 'data' => '1' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Frequency:', 'data' => '3' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Average Time:', 'data' => '0' ])

                        @include('shineCompliance.forms.form_text_right',['title' => '  Maintenance Activity'])
                        @include('shineCompliance.forms.form_text',['title' => 'Type:', 'data' => '1' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Frequency:', 'data' => '1' ])
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Total:</label>
                            <div class="col-md-5">
                            <span id="total-PRAs">
                                5
                            </span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Priority Risk Assessment:</label>
                            <div class="col-md-5">
                                  <span class="badge orange" id="risk-color" style="width: 30px;margin-right: 10px">
                                        5
                                  </span>
                                <span>Low Risk</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            function setColor(totalSelector, text, colorSelector) {
                var total_selector = $('#' + totalSelector).html();
                if(totalSelector == 'total-MAS'){
                   var total = total_selector/2;
                }else{
                    total = total_selector;
                }
                switch (true) {
                    case (total == 0):
                        color = "green";
                        risk = "No Risk";
                        break;
                    case (total < 5):
                        color = "yellow";
                        risk = "Very Low";
                        break;
                    case (total < 7):
                        color = "orange";
                        risk = "Low";
                        break;
                    case (total < 10):
                        color = "brown";
                        risk = "Medium";
                        break;
                    case (total < 13):
                        color = "red";
                        risk = "High";
                        break;
                }
                $('#'+ text).html(risk);
                $('#' + colorSelector).addClass("badge " + color);
            }
            setColor('total-MAS','risk-text','risk-color')
            setColor('total-PRA','risk-text1','risk-color1')
            setColor('total-ORA','risk-text2','risk-color2')
            setColor('total-MRA','risk-text-mra','risk-color-mra')
            setColor('total-PRAs','risk-text-PRAs','risk-color-PRAs')
        });
    </script>
@endpush

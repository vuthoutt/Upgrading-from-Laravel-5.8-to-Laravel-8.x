@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav', ['color' => 'red'])
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
<div class="container-cus prism-content">
    <h3>0153- Gasket to 'Heating Flow' vertical pipework flange</h3>
    <div class="main-content">
        <div class="row">
            <div class="full-width">
                <div class="form-button-left" >
                    <a href="{{ url()->previous() }}" style="text-decoration: none">
                        <button type="submit" class="btn shine-compliance-button ">
                            <strong>{{ __('Back') }}</strong>
                        </button>
                    </a>
                </div>
                <div class="form-button-search" >
                    <a href="" style="text-decoration: none">
                        <button type="submit" class="btn shine-compliance-button" >
                            <strong>{{ __('Edit') }}</strong>
                        </button>
                    </a>
                    <a href="" style="text-decoration: none">
                        <button type="submit" class="btn shine-compliance-button" id="filter" style="margin-right: 0px">
                            <strong>{{ __('Decommission') }}</strong>
                        </button>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div  class="card-data card-data-item">
                <div style="width:250px; " >
                    <ul class="list-group">
                        <p><strong>Location</strong></p>
                        <div class="list-group-img">
                            <img src="{{ asset('img/item5.png') }}"  width="100%" height="200px" alt="">
                        </div>
                        <div class="list-group-button">
                            <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
                        </div>
                    </ul>
                </div>
            </div>
            <div  class="card-data card-data-item">
                <div style="width:250px; " >
                    <ul class="list-group">
                        <p><strong>ACM</strong></p>
                        <div class="list-group-img">
                            <img src="{{ asset('img/item5.png') }}"  width="100%" height="200px" alt="">
                        </div>
                        <div class="list-group-button">
                            <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
                        </div>
                    </ul>
                </div>
            </div>
            <div  class="card-data card-data-item">
                <div style="width:250px; " >
                    <ul class="list-group">
                        <p><strong>Additional</strong></p>
                        <div class="list-group-img">
                            <img src="{{ asset('img/item5.png') }}"  width="100%" height="200px" alt="">
                        </div>
                        <div class="list-group-button">
                            <button class="list-group-btn" style="margin-left:0px"><i class="fa fa-image fa-2x"></i></button>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 detail-item" >
                <div class="card discard-border-radius">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                    <div class="card-body">
                        @include('shineCompliance.forms.form_text',['title' => 'Item Name:', 'data' =>  'Matthew' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Item ID:', 'data' => 'Curran' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Assessed:', 'data' =>'ID3' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Reason:', 'data' =>  'Westminster City Council' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Type:', 'data' => 'Health & Safety' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Sample Comment:', 'data' =>'Asbestos Programme Manager' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Area/floor Ref:', 'data' => '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Room/floor Ref:', 'data' => '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Specific Location:', 'data' => '07760170273' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Product/debirs Type:', 'data' => 'MCurran@westminster.gov.uk' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Asbestos Type:', 'data' => 'Super User'])
                        @include('shineCompliance.forms.form_text',['title' => 'Extent:', 'data' => '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Action/recommendation:', 'data' => '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Comments:', 'data' => '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Data Stamp:', 'data' => '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Organisation:', 'data' => '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'UserName:', 'data' => '' ])
                    </div>
                </div>
            </div>
            <div class="col-md-6 overall" >
                <div class="card discard-border-radius ">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Overall Risk Assessment</strong></div>
                    <div class="card-body">
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Total:</label>
                            <div class="col-md-5">
                                <span id="total-MAS">
                                    05
                                </span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Material Risk Assessment:</label>
                            <div class="col-md-5">
                                <span id="risk-color" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
                                &nbsp;
                                <span id="risk-text"></span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Total:</label>
                            <div class="col-md-5">
                                <span id="total-PRA">
                                    05
                                </span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Priority Risk Assessment:</label>
                            <div class="col-md-5">
                                <span id="risk-color1" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
                                &nbsp;
                                <span id="risk-text1"></span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Overall Total:</label>
                            <div class="col-md-5">
                                <span id="total-ORA">
                                    05
                                </span>
                            </div>
                        </div>
                        <div class="row register-form">
                            <label class="col-md-6 col-form-label text-md-left font-weight-bold" >Overall Risk Assessment:</label>
                            <div class="col-md-5">
                                <span id="risk-color2" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
                                &nbsp;
                                <span id="risk-text2"></span>
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
                                <span id="risk-color-mra" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
                                &nbsp;
                                <span id="risk-text-mra"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card discard-border-radius risk">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Priority Risk Assessment</strong></div>
                    <div class="card-body">
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
                var total = $('#' + totalSelector).html();
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
        });
    </script>
@endpush

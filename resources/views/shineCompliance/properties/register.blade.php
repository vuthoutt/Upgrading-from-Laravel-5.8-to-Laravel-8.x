@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav', ['color' => 'red'])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">1 Broadley Street</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_register',['backRoute' => url()->previous() ])

        <div class="row">
            @include('shineCompliance.properties.partials._property_sidebar')
            <div class="col-md-9 pl-0 pr-0">
            @if(is_null($type))
                <div class="card-data mar-up">
                    <div class="col-md-12" id="overallPropertySummary" style="padding-right: 0px; padding-left: 15px;">
                        <div class="card discard-border-radius" style="border:none!important;">
                            <div class="card-header table-header discard-border-radius" style="background-color: #d1d3d4">
                                <h6 class="table-title">Property Overall Register Summary</h6>
                                <div class="btn collapse-table table-collapse-button" data-toggle="collapse"
                                     data-target="#collapse-overallPropertySummary" aria-expanded="true" aria-controls="collapse-property-contact">
                                    <i class="fa fa-lg " aria-hidden="true"></i>
                                </div>
                            </div>
                            <div id="collapse-overallPropertySummary" class="collapse show"
                                 data-parent="#overallPropertySummary" style="">
                                <div class="card-body" style="padding-left: 21px;padding-bottom: 0px;">
                                    <table class="table table-bordered shineDatatable normal-table-content">
                                        <thead>
                                        <tr>
                                            <th style="width: 40%">Risk Warning</th>
                                            <th style="width: 15%">Asbestos</th>
                                            <th style="width: 15%">Fire</th>
                                            <th style="width: 15%">Gas</th>
                                            <th style="width: 15%">Water</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Total Risk Count</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, ASBESTOS, 'all_acm_items']) }}">5</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, FIRE, 'all_acm_items']) }}">10</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, GAS, 'all_acm_items']) }}">0</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, WATER, 'all_acm_items']) }}">10</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Inaccessible Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, ASBESTOS, 'inaccessible_acm_items']) }}">1</a>
                                                </td>
                                                <td>
                                                    N/A
                                                </td>
                                                <td>
                                                    N/A
                                                </td>
                                                <td>
                                                    N/A
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Very High Risk Summary</td>
                                                <td>
                                                    N/A
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, FIRE, 'very_high_hazard_summary']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, GAS, 'very_high_hazard_summary']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, WATER, 'very_high_hazard_summary']) }}">1</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>High Risk Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, ASBESTOS, 'high_risk_items']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, FIRE, 'high_hazard_summary']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, GAS, 'high_hazard_summary']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, WATER, 'high_hazard_summary']) }}">1</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Medium Risk Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, ASBESTOS, 'medium_risk_items']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, FIRE, 'medium_hazard_summary']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, GAS, 'medium_hazard_summary']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, WATER, 'medium_hazard_summary']) }}">1</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Low Risk Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, ASBESTOS, 'low_risk_items']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, FIRE, 'low_hazard_summary']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, GAS, 'low_hazard_summary']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, WATER, 'low_hazard_summary']) }}">1</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Very Low Risk Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, ASBESTOS, 'very_low_risk_items']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, FIRE, 'very_low_hazard_summary']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, GAS, 'very_low_hazard_summary']) }}">1</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, WATER, 'very_low_hazard_summary']) }}">1</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>No Risk (NoACM) Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, ASBESTOS, 'no_risk_items']) }}">4</a>
                                                </td>
                                                <td>
                                                    N/A
                                                </td>
                                                <td>
                                                    N/A
                                                </td>
                                                <td>
                                                    N/A
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Inaccessible Room/locations Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, ASBESTOS, 'inaccessible_rooms']) }}">4</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, FIRE, 'inaccessible_rooms']) }}">4</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, GAS, 'inaccessible_rooms']) }}">4</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, WATER, 'inaccessible_rooms']) }}">4</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Inaccessible Voids Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, ASBESTOS, 'inaccessible_voids']) }}">2</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, FIRE, 'inaccessible_voids']) }}">2</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, GAS, 'inaccessible_voids']) }}">2</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, WATER, 'inaccessible_voids']) }}">2</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-data mar-up">
                    <div class="col-md-12" id="propertyDecommissionedACMs" style="padding-right: 0px; padding-left: 15px;">
                        <div class="card discard-border-radius" style="border:none!important;">
                            <div class="card-header table-header discard-border-radius" style="background-color: #d1d3d4">
                                <h6 class="table-title">
                                    Property Overall Decommissioned Summary
                                </h6>
                                <div class="btn collapse-table table-collapse-button collapsed" data-toggle="collapse"
                                     data-target="#collapse-propertyDecommissionedACMs" aria-expanded="false" aria-controls="collapse-property-contact">
                                    <i class="fa fa-lg " aria-hidden="true"></i>
                                </div>
                            </div>
                            <div id="collapse-propertyDecommissionedACMs" class="collapse "
                                 data-parent="#propertyDecommissionedACMs" style="">
                                <div class="card-body"  style="padding-left: 21px;padding-bottom: 0px;">
                                    <table class="table table-bordered shineDatatable normal-table-content">
                                        <thead>
                                        <tr>
                                            <th style="width: 70%">Risk Warning</th>
                                            <th >Record Count</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>ACM Summary</td>
                                            <td>
                                                <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, 'asbestos', 'all_acm_items']) }}">0</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>NoACM Summary</td>
                                            <td>
                                                <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, 'asbestos', 'no_risk_items']) }}">0</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fire Hazard Summary</td>
                                            <td>
                                                <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, FIRE, 'medium_hazard_summary']) }}">0</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Gas Hazard Summary</td>
                                            <td>
                                                <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, GAS, 'medium_hazard_summary']) }}">0</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Water Hazard Summary</td>
                                            <td>
                                                <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, WATER, 'medium_hazard_summary']) }}">0</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card-data mar-up">
                    <div class="col-md-12" id="asbestosPropertySummary" style="padding-right: 0px; padding-left: 15px;">
                        <div class="card discard-border-radius" style="border:none!important;">
                            <div class="card-header table-header discard-border-radius" style="background-color: #d1d3d4">
                                <h6 class="table-title">Property {!! Str::title(str_replace('_',' ',$type)) !!} Register Summary</h6>
                                <div class="btn collapse-table table-collapse-button" data-toggle="collapse"
                                     data-target="#collapse-asbestosPropertySummary" aria-expanded="false" aria-controls="collapse-property-contact">
                                    <i class="fa fa-lg " aria-hidden="true"></i>
                                </div>
                            </div>
                            <div id="collapse-asbestosPropertySummary" class="collapse show"
                                 data-parent="#asbestosPropertySummary" style="">
                                <div class="card-body" style="padding-left: 21px;padding-bottom: 0px;">
                                    <table class="table table-bordered shineDatatable normal-table-content">
                                        <thead>
                                        <tr>
                                            <th style="width: 70%">Risk Warning</th>
                                            <th>Record Count</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($type == ASBESTOS)
                                            <tr>
                                                <td>ACM Risk Count</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'all_acm_items']) }}">5</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Inaccessible ACM Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'inaccessible_acm_items']) }}">1</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>High Risk ACM Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'high_risk_items']) }}">1</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Medium Risk ACM Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'medium_risk_items']) }}">1</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Low Risk ACM Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'low_risk_items']) }}">1</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Very Low Risk ACM Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'very_low_risk_items']) }}">1</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>No Risk (NoACM) Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'no_risk_items']) }}">4</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Inaccessible Room/locations Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'inaccessible_rooms']) }}">4</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Inaccessible Voids Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'inaccessible_voids']) }}">2</a>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>All {!! Str::title(str_replace('_',' ',$type)) !!} Hazard Risk Count</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'all_hazards_summary']) }}">10</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Very High Risk Hazard Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'very_high_hazard_summary']) }}">2</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>High Risk Hazard Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'high_hazard_summary']) }}">2</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Medium Risk Hazard Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'medium_hazard_summary']) }}">2</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Low Risk Hazard Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'low_hazard_summary']) }}">2</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Very Low Risk Hazard Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'very_low_hazard_summary']) }}">2</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Inaccessible Room/locations Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'inaccessible_rooms']) }}">4</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Inaccessible Voids Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'inaccessible_voids']) }}">2</a>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-data mar-up">
                    <div class="col-md-12" id="propertyDecommissionedACMs" style="padding-right: 0px; padding-left: 15px;">
                        <div class="card discard-border-radius" style="border:none!important;">
                            <div class="card-header table-header discard-border-radius" style="background-color: #d1d3d4">
                                <h6 class="table-title">
                                    Property Decommissioned {!! Str::title(str_replace('_',' ',$type)) !!} {{ $type == ASBESTOS ? 'Summary' : 'Hazard Summary' }}
                                </h6>
                                <div class="btn collapse-table table-collapse-button collapsed" data-toggle="collapse"
                                     data-target="#collapse-propertyDecommissionedACMs" aria-expanded="false" aria-controls="collapse-property-contact">
                                    <i class="fa fa-lg " aria-hidden="true"></i>
                                </div>
                            </div>
                            <div id="collapse-propertyDecommissionedACMs" class="collapse hide"
                                 data-parent="#propertyDecommissionedACMs" style="">
                                <div class="card-body"  style="padding-left: 21px;padding-bottom: 0px;">
                                    <table class="table table-bordered shineDatatable normal-table-content">
                                        <thead>
                                        <tr>
                                            <th style="width: 70%">Risk Warning</th>
                                            <th>Record Count</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($type == ASBESTOS)
                                            <tr>
                                                <td>All ACM Risk Count</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'all_acm_items']) }}">0</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Inaccessible ACM Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'inaccessible_acm_items']) }}">0</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>High Risk ACM Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'high_risk_items']) }}">0</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Medium Risk ACM Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'medium_risk_items']) }}">0</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Low Risk ACM Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'low_risk_items']) }}">0</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Very Low Risk ACM Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'very_low_risk_items']) }}">0</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>No Risk (NoACM) Item Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'no_risk_items']) }}">0</a>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>All {!! Str::title(str_replace('_',' ',$type)) !!} Hazard Risk Count</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'all_hazards_summary']) }}">0</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Very High Risk Hazard Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'very_high_hazard_summary']) }}">0</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>High Risk Hazard Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'high_hazard_summary']) }}">0</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Medium Risk Hazard Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'medium_hazard_summary']) }}">0</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Low Risk Hazard Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'low_hazard_summary']) }}">0</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Very Low Risk Hazard Summary</td>
                                                <td>
                                                    <a href="{{ route('shineCompliance.property.register.summary_detail', [1234, $type, 'very_low_hazard_summary']) }}">0</a>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection

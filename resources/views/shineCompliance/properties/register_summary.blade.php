@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav', ['color' => 'red'])
<div class="container prism-content pad-up">
    <div class="row">
        <h3 style="margin: 0">1 Broadley Street</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_register',['backRoute' => url()->previous() ])

        <div class="row">
            @include('shineCompliance.properties.partials._property_sidebar')
            <div class="column-right">
                <div class="card-data mar-up">
                    <div class="col-md-12" style="padding-right: 0px; padding-left: 25px;">
                        <div class="card discard-border-radius">
                            <div class="card-header table-header discard-border-radius" style="background-color: #d1d3d4">
                                <h6 class="table-title">Property {!! Str::title(str_replace('_',' ',$type)) !!} Register Summary</h6>
                                <div class="btn collapse-table table-collapse-button collapsed" data-toggle="collapse"
                                     data-target="#property-all-acm-items_wrapper" aria-expanded="false" aria-controls="collapse-property-contact">
                                    <i class="fa fa-lg " aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="card-body" style="padding-bottom:7px;margin-bottom:-24px" !important="">
                                <div id="property-all-acm-items_wrapper"
                                     class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="dataTables_length" id="property-all-acm-items_length"><label>Show
                                                    <select name="property-all-acm-items_length"
                                                            aria-controls="property-all-acm-items"
                                                            class="custom-select custom-select-sm form-control form-control-sm">
                                                        <option value="10">10</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select> entries</label></div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div id="property-all-acm-items_filter" class="dataTables_filter"><label>Search:<input
                                                        type="search" class="form-control form-control-sm"
                                                        placeholder="" aria-controls="property-all-acm-items"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="property-all-acm-items"
                                                   class="table table-striped table-bordered shineDatatable dataTable no-footer"
                                                   role="grid" aria-describedby="property-all-acm-items_info"
                                                   style="width: 0px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc" tabindex="0"
                                                            aria-controls="property-all-acm-items" rowspan="1" colspan="1"
                                                            aria-sort="ascending"
                                                            aria-label="Summary: activate to sort column descending"
                                                            style="width: 20%;">Summary
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="property-all-acm-items" rowspan="1" colspan="1"
                                                            aria-label="Area/floor Reference: activate to sort column ascending"
                                                            style="width: 20%;">Area/floor Reference
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="property-all-acm-items" rowspan="1" colspan="1"
                                                            aria-label="Room/location Reference: activate to sort column ascending"
                                                            style="width: 20%;">Room/location Reference
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="property-all-acm-items" rowspan="1" colspan="1"
                                                            aria-label="Product/debris type: activate to sort column ascending"
                                                            style="width: 20%;">{{ $type == ASBESTOS ? 'Product/debris type' : 'Hazard Type' }}
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="property-all-acm-items" rowspan="1" colspan="1"
                                                            aria-label="MAS: activate to sort column ascending"
                                                            style="width: 150px;">{{ $type == ASBESTOS ? 'MAS' : 'FRA' }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @if($type == ASBESTOS)
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.item') }}">
                                                                24974-124
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Insulation Debris/residue</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge red" id="risk-color" style="width: 30px;">
                                                                &nbsp;12&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>High Risk</span>
                                                        </td>

                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.item') }}">
                                                                24974-211
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Insulation Debris/residue</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge red" id="risk-color" style="width: 30px;">
                                                                &nbsp;10&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>High Risk</span>
                                                        </td>

                                                    </tr>
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.item') }}">
                                                                24974-177
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                00
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                01S03
                                                            </a>
                                                        </td>
                                                        <td>Rope & Yarns Rope Gasket</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge brown" id="risk-color" style="width: 30px;">
                                                                &nbsp;8
                                                            </span>
                                                            &nbsp;
                                                            <span>Medium Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.item') }}">
                                                                24974-207
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Insulating Board Panel</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge orange" id="risk-color" style="width: 30px;">
                                                                &nbsp;6&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Low Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.item') }}">
                                                                24974-122
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                00
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                01S03
                                                            </a>
                                                        </td>
                                                        <td>Flash Pads to Fuse Box</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge yellow" id="risk-color" style="width: 30px;color: black!important;">
                                                                &nbsp;2&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Very Low Risk</span>
                                                        </td>
                                                    </tr>
                                                @elseif($type == WATER)
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Cleanliness #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                00
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                01S03
                                                            </a>
                                                        </td>
                                                        <td>Cleanliness</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge yellow" id="risk-color" style="width: 30px;color: black!important;">
                                                                &nbsp;2&nbsp;
                                                            </span>
                                                            &nbsp
                                                            <span>Very Low Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Cleanliness #2
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                00
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                1GSR14
                                                            </a>
                                                        </td>
                                                        <td>Cleanliness</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge orange" id="risk-color" style="width: 30px;">
                                                                &nbsp;4&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Low Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Construction #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                02
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                01S03
                                                            </a>
                                                        </td>
                                                        <td>Construction</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge brown" id="risk-color" style="width: 30px;">
                                                                &nbsp;10&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Medium Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Construction #2
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Construction</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge red" id="risk-color" style="width: 30px;">
                                                                &nbsp;16&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>High Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Label #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Label</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge darkred" id="risk-color" style="width: 30px;">
                                                                &nbsp;25&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Very High Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Miscellaneous #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Miscellaneous</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge yellow" id="risk-color" style="width: 30px;color: black!important;">
                                                                &nbsp;2&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Very Low Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Sample #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                00
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                1GSR14
                                                            </a>
                                                        </td>
                                                        <td>Sample</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge orange" id="risk-color" style="width: 30px;">
                                                                &nbsp;4&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Low Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Temperature #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                02
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                01S03
                                                            </a>
                                                        </td>
                                                        <td>Temperature</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge brown" id="risk-color" style="width: 30px;">
                                                                &nbsp;10&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Medium Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Temperature #2
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Temperature</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge red" id="risk-color" style="width: 30px;">
                                                                &nbsp;16&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>High Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Usage #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Usage</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge darkred" id="risk-color" style="width: 30px;">
                                                                &nbsp;25&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Very High Risk</span>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Electrical Hazard #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                00
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                01S03
                                                            </a>
                                                        </td>
                                                        <td>Electrical Hazard #1</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge yellow" id="risk-color" style="width: 30px;color: black!important;">
                                                                &nbsp;2&nbsp;
                                                            </span>
                                                            &nbsp
                                                            <span>Very Low Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Smoking #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                00
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                1GSR14
                                                            </a>
                                                        </td>
                                                        <td>Smoking</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge orange" id="risk-color" style="width: 30px;">
                                                                &nbsp;4&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Low Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Arson #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                02
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                01S03
                                                            </a>
                                                        </td>
                                                        <td>Arson</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge brown" id="risk-color" style="width: 30px;">
                                                                &nbsp;10&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Medium Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Heating Installations #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Heating Installations</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge red" id="risk-color" style="width: 30px;">
                                                                &nbsp;16&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>High Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Cooking #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Cooking</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge darkred" id="risk-color" style="width: 30px;">
                                                                &nbsp;25&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Very High Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Lightning #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Lightning</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge yellow" id="risk-color" style="width: 30px;color: black!important;">
                                                                &nbsp;2&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Very Low Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Housekeeping #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                00
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                1GSR14
                                                            </a>
                                                        </td>
                                                        <td>Housekeeping</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge orange" id="risk-color" style="width: 30px;">
                                                                &nbsp;4&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Low Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Introduced by Outside Contractor #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                02
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#">
                                                                01S03
                                                            </a>
                                                        </td>
                                                        <td>Introduced by Outside Contractor</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge brown" id="risk-color" style="width: 30px;">
                                                                &nbsp;10&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Medium Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Dangerous Substances #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Dangerous Substances</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge red" id="risk-color" style="width: 30px;">
                                                                &nbsp;16&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>High Risk</span>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">
                                                            <a href="{{ route('shineCompliance.hazardItem', $type) }}">
                                                                Other Significant Hazards #1
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                01
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="">
                                                                1B229
                                                            </a>
                                                        </td>
                                                        <td>Other Significant Hazards</td>
                                                        <td style="width: 100px;">
                                                            <span class="badge darkred" id="risk-color" style="width: 30px;">
                                                                &nbsp;25&nbsp;
                                                            </span>
                                                            &nbsp;
                                                            <span>Very High Risk</span>
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row footer-dt-table">
                                        <div class="col-sm-12 col-md-5">
                                            <div class="dataTables_info" id="property-all-acm-items_info" role="status"
                                                 aria-live="polite">Showing 1 to 10 of 22 entries
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_full_numbers"
                                                 id="property-all-acm-items_paginate">
                                                <ul class="pagination">
                                                    <li class="paginate_button page-item first disabled"
                                                        id="property-all-acm-items_first"><a href="#"
                                                                                             aria-controls="property-all-acm-items"
                                                                                             data-dt-idx="0"
                                                                                             tabindex="0"
                                                                                             class="page-link">First</a>
                                                    </li>
                                                    <li class="paginate_button page-item previous disabled"
                                                        id="property-all-acm-items_previous"><a href="#"
                                                                                                aria-controls="property-all-acm-items"
                                                                                                data-dt-idx="1"
                                                                                                tabindex="0"
                                                                                                class="page-link">Previous</a>
                                                    </li>
                                                    <li class="paginate_button page-item active"><a href="#"
                                                                                                    aria-controls="property-all-acm-items"
                                                                                                    data-dt-idx="2"
                                                                                                    tabindex="0"
                                                                                                    class="page-link">1</a>
                                                    </li>
                                                    <li class="paginate_button page-item "><a href="#"
                                                                                              aria-controls="property-all-acm-items"
                                                                                              data-dt-idx="3"
                                                                                              tabindex="0"
                                                                                              class="page-link">2</a>
                                                    </li>
                                                    <li class="paginate_button page-item "><a href="#"
                                                                                              aria-controls="property-all-acm-items"
                                                                                              data-dt-idx="4"
                                                                                              tabindex="0"
                                                                                              class="page-link">3</a>
                                                    </li>
                                                    <li class="paginate_button page-item next"
                                                        id="property-all-acm-items_next"><a href="#"
                                                                                            aria-controls="property-all-acm-items"
                                                                                            data-dt-idx="5" tabindex="0"
                                                                                            class="page-link">Next</a>
                                                    </li>
                                                    <li class="paginate_button page-item last"
                                                        id="property-all-acm-items_last"><a href="#"
                                                                                            aria-controls="property-all-acm-items"
                                                                                            data-dt-idx="6" tabindex="0"
                                                                                            class="page-link">Last</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

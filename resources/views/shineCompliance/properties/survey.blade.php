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
            <div class="col-md-9 pl-0 pr-0">
                <div class="card-data mar-up" style="margin-bottom: 38px">
                    <div class="col-md-12" id="accordionExampleproperty-survey-table" style="padding-right: 0px; padding-left: 15px;">
                        <div class="card discard-border-radius">
                            <div class="card-header discard-border-radius table-header">
                                <input type="hidden" id="type-property-survey-table" value="">
                                <input type="hidden" id="property-survey-tableorder-table" value="[]">
                                <h6 class="table-title">
                                    @if($type == ASBESTOS)
                                        Asbestos Surveys (1)
                                    @else
                                        {!! Str::title(str_replace('_',' ',$type)) !!} Risk Assessments (1)
                                    @endif
                                </h6>
                                <div class="btn collapse-table table-collapse-button " data-toggle="collapse"
                                     data-target="#collapse-property-survey-table" aria-expanded="false"
                                     aria-controls="collapse-property-survey-table">
                                    <i class="fa fa-lg " aria-hidden="true"></i>
                                </div>
                                <div class="btn collapse-table table-plus-button">
                                    <a href="#" style="text-decoration: none;color: inherit">
                                        <i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div id="collapse-property-survey-table" class="collapse show"
                                 data-parent="#accordionExampleproperty-survey-table">
                                <div class="card-body" style="padding-bottom:7px;margin-bottom:-24px" !important="">
                                    <div id="property-survey-table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="dataTables_length" id="property-survey-table_length"><label>Show
                                                        <select name="property-survey-table_length"
                                                                aria-controls="property-survey-table"
                                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                            <option value="10">10</option>
                                                            <option value="25">25</option>
                                                            <option value="50">50</option>
                                                            <option value="100">100</option>
                                                        </select> entries</label></div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div id="property-survey-table_filter" class="dataTables_filter">
                                                    <label>Search:<input type="search" class="form-control form-control-sm"
                                                                         placeholder=""
                                                                         aria-controls="property-survey-table"></label></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="property-survey-table"
                                                       class="table table-striped table-bordered shineDatatable dataTable no-footer"
                                                       role="grid" aria-describedby="property-survey-table_info">
                                                    <thead>
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0" aria-controls="property-survey-table"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Survey ID: activate to sort column ascending"
                                                            style="width: 0px;">{{ $type == ASBESTOS ? 'Survey' : 'Assessment' }} ID
                                                        </th>
                                                        @if($type == ASBESTOS)
                                                        <th class="sorting" tabindex="0" aria-controls="property-survey-table"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Survey Type: activate to sort column ascending"
                                                            style="width: 0px;">Survey Type
                                                        </th>
                                                        @endif
                                                        <th class="sorting" tabindex="0" aria-controls="property-survey-table"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Project Reference: activate to sort column ascending"
                                                            style="width: 0px;">Project Reference
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="property-survey-table"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Project Title: activate to sort column ascending"
                                                            style="width: 0px;">Project Title
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="property-survey-table"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Status: activate to sort column ascending"
                                                            style="width: 0px;">Status
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="property-survey-table"
                                                            rowspan="1" colspan="1"
                                                            aria-label="File: activate to sort column ascending"
                                                            style="width: 0px;">File
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="property-survey-table"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Date Completed: activate to sort column ascending"
                                                            style="width: 0px;">Date Completed
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr role="row" class="odd">
                                                        <td><a href="#">{{ $type == ASBESTOS ? 'MS' : 'RA' }}30</a>
                                                        </td>
                                                        @if($type == ASBESTOS)
                                                        <td>Management Survey</td>
                                                        @endif
                                                        <td>PR373</td>
                                                        <td>@if($type == ASBESTOS){!! Str::studly($type) !!} Management Survey @endif</td>
                                                        <td>Completed</td>
                                                        <td style="width: 70px !important">
                                                            <a href="#"><img
                                                                    src="{{ asset('/img/pdf-green.png') }}"
                                                                    width="19" height="19" class="fileicon" alt="View File"
                                                                    border="0"></a>
                                                            <a href="#"
                                                               class="btn btn-outline-secondary btn-sm">
                                                                <i class="fa fa-download"></i>
                                                            </a></td>
                                                        <td>10/08/2020</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row footer-dt-table">
                                            <div class="col-sm-12 col-md-5">
                                                <div class="dataTables_info" id="property-survey-table_info" role="status"
                                                     aria-live="polite">Showing 1 to 9 of 9 entries
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-7">
                                                <div class="dataTables_paginate paging_full_numbers"
                                                     id="property-survey-table_paginate">
                                                    <ul class="pagination">
                                                        <li class="paginate_button page-item first disabled"
                                                            id="property-survey-table_first"><a href="#"
                                                                                                aria-controls="property-survey-table"
                                                                                                data-dt-idx="0" tabindex="0"
                                                                                                class="page-link">First</a></li>
                                                        <li class="paginate_button page-item previous disabled"
                                                            id="property-survey-table_previous"><a href="#"
                                                                                                   aria-controls="property-survey-table"
                                                                                                   data-dt-idx="1" tabindex="0"
                                                                                                   class="page-link">Previous</a>
                                                        </li>
                                                        <li class="paginate_button page-item active"><a href="#"
                                                                                                        aria-controls="property-survey-table"
                                                                                                        data-dt-idx="2"
                                                                                                        tabindex="0"
                                                                                                        class="page-link">1</a>
                                                        </li>
                                                        <li class="paginate_button page-item next"
                                                            id="property-survey-table_next"><a href="#"
                                                                                               aria-controls="property-survey-table"
                                                                                               data-dt-idx="4" tabindex="0"
                                                                                               class="page-link">Next</a></li>
                                                        <li class="paginate_button page-item last"
                                                            id="property-survey-table_last"><a href="#"
                                                                                               aria-controls="property-survey-table"
                                                                                               data-dt-idx="5" tabindex="0"
                                                                                               class="page-link">Last</a></li>
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

                <div class="card-data mar-up">
                    <div class="col-md-12" id="accordionExampleproperty-dec-survey-table" style="padding-right: 0px; padding-left: 15px;">
                        <div class="card discard-border-radius">
                            <div class="card-header discard-border-radius table-header">
                                <input type="hidden" id="type-property-dec-survey-table" value="">
                                <input type="hidden" id="property-dec-survey-tableorder-table" value="[]">

                                <h6 class="table-title">
                                    @if($type == ASBESTOS)
                                        Decommissioned Asbestos Surveys (1)
                                    @else
                                        Decommissioned {!! Str::title(str_replace('_',' ',$type)) !!} Risk Assessments (1)
                                    @endif
                                </h6>
                                <div class="btn collapse-table table-collapse-button" data-toggle="collapse"
                                     data-target="#collapse-property-dec-survey-table" aria-expanded="true"
                                     aria-controls="collapse-property-dec-survey-table">
                                    <i class="fa fa-lg " aria-hidden="true"></i>
                                </div>
                            </div>
                            <div id="collapse-property-dec-survey-table" class="collapse show"
                                 data-parent="#accordionExampleproperty-dec-survey-table" style="">
                                <div class="card-body" style="padding-bottom:7px;margin-bottom:-24px" !important="">
                                    <div id="property-dec-survey-table_wrapper"
                                         class="dataTables_wrapper dt-bootstrap4 no-footer">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="dataTables_length" id="property-dec-survey-table_length">
                                                    <label>Show <select name="property-dec-survey-table_length"
                                                                        aria-controls="property-dec-survey-table"
                                                                        class="custom-select custom-select-sm form-control form-control-sm">
                                                            <option value="10">10</option>
                                                            <option value="25">25</option>
                                                            <option value="50">50</option>
                                                            <option value="100">100</option>
                                                        </select> entries</label></div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div id="property-dec-survey-table_filter" class="dataTables_filter">
                                                    <label>Search:<input type="search"
                                                                         class="form-control form-control-sm"
                                                                         placeholder=""
                                                                         aria-controls="property-dec-survey-table"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="property-dec-survey-table"
                                                       class="table table-striped table-bordered shineDatatable dataTable no-footer"
                                                       role="grid" aria-describedby="property-dec-survey-table_info">
                                                    <thead>
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="property-dec-survey-table" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Survey ID: activate to sort column ascending"
                                                            style="width: 0px;">{{ $type == ASBESTOS ? 'Survey' : 'Assessment' }} ID
                                                        </th>
                                                        @if($type == ASBESTOS)
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="property-dec-survey-table" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Survey Type: activate to sort column ascending"
                                                            style="width: 0px;">Survey Type
                                                        </th>
                                                        @endif
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="property-dec-survey-table" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Project Reference: activate to sort column ascending"
                                                            style="width: 0px;">Project Reference
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="property-dec-survey-table" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Project Title: activate to sort column ascending"
                                                            style="width: 0px;">Project Title
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="property-dec-survey-table" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Status: activate to sort column ascending"
                                                            style="width: 0px;">Status
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="property-dec-survey-table" rowspan="1"
                                                            colspan="1"
                                                            aria-label="File: activate to sort column ascending"
                                                            style="width: 0px;">File
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="property-dec-survey-table" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Date Completed: activate to sort column ascending"
                                                            style="width: 0px;">Date Completed
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr role="row" class="odd">
                                                        <td>
                                                            <a href="#">{{ $type == ASBESTOS ? 'MS' : 'RA' }}1009</a>
                                                        </td>
                                                        @if($type == ASBESTOS)
                                                        <td>Re-Inspection Report</td>
                                                        @endif
                                                        <td>PR1096</td>
                                                        <td>@if($type == ASBESTOS){!! Str::title(str_replace('_',' ',$type)) !!} Re-Inspection Programme @endif</td>
                                                        <td><span class="red_text">Aborted</span></td>
                                                        <td style="width: 70px !important">
                                                            <a href="#"><img
                                                                    src="{{ asset('/img/pdf-green.png') }}"
                                                                    width="19" height="19" class="fileicon"
                                                                    alt="View File" border="0"></a>
                                                            <a href="#"
                                                               class="btn btn-outline-secondary btn-sm">
                                                                <i class="fa fa-download"></i>
                                                            </a></td>
                                                        <td></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row footer-dt-table">
                                            <div class="col-sm-12 col-md-5">
                                                <div class="dataTables_info" id="property-dec-survey-table_info"
                                                     role="status" aria-live="polite">Showing 1 to 1 of 1 entries
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-7">
                                                <div class="dataTables_paginate paging_full_numbers"
                                                     id="property-dec-survey-table_paginate">
                                                    <ul class="pagination">
                                                        <li class="paginate_button page-item first disabled"
                                                            id="property-dec-survey-table_first"><a href="#"
                                                                                                    aria-controls="property-dec-survey-table"
                                                                                                    data-dt-idx="0"
                                                                                                    tabindex="0"
                                                                                                    class="page-link">First</a>
                                                        </li>
                                                        <li class="paginate_button page-item previous disabled"
                                                            id="property-dec-survey-table_previous"><a href="#"
                                                                                                       aria-controls="property-dec-survey-table"
                                                                                                       data-dt-idx="1"
                                                                                                       tabindex="0"
                                                                                                       class="page-link">Previous</a>
                                                        </li>
                                                        <li class="paginate_button page-item active"><a href="#"
                                                                                                        aria-controls="property-dec-survey-table"
                                                                                                        data-dt-idx="2"
                                                                                                        tabindex="0"
                                                                                                        class="page-link">1</a>
                                                        </li>
                                                        <li class="paginate_button page-item next disabled"
                                                            id="property-dec-survey-table_next"><a href="#"
                                                                                                   aria-controls="property-dec-survey-table"
                                                                                                   data-dt-idx="3"
                                                                                                   tabindex="0"
                                                                                                   class="page-link">Next</a>
                                                        </li>
                                                        <li class="paginate_button page-item last disabled"
                                                            id="property-dec-survey-table_last"><a href="#"
                                                                                                   aria-controls="property-dec-survey-table"
                                                                                                   data-dt-idx="4"
                                                                                                   tabindex="0"
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
</div>
@endsection

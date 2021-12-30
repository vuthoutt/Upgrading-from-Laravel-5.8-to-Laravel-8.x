@extends('shineCompliance.layouts.app')

@section('content')
    @if($parent_property)
        @include('shineCompliance.partials.nav',['breadCrumb' => 'sub_property_detail', 'color' => 'red', 'data' => $property])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_detail', 'color' => 'red', 'data' => $property])
    @endif
<div class="container-cus prism-content pad-up">
    <div class="row ">
        <h3 class="title-row" >{{$property->name ?? ''}}</h3>
    </div>
    <div class="main-content mar-up">
            @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_DETAILS))
            @else
                @if(!$parent_property)
                    @include('shineCompliance.properties.partials._property_button', [ 'backRoute' =>  route('shineCompliance.zone.details',['zone_id' => $property->zone_id]),
                    'editRoute' => route('shineCompliance.property.property_edit',['property_id' => $property->id ?? 0]),
                    'decommission' => $property->decommissioned, 'data_target' => '#decommission_prop','can_update' => $canBeUpdateThisSite,
                    'route_decommission'=> route('shineCompliance.property.decommission',['property_id' => $property->id]) ])
                @else
                    @include('shineCompliance.properties.partials._property_button', [ 'backRoute' =>  route('shineCompliance.property.property_detail',['propety_id' => $property->parent_id]),
                            'editRoute' => route('shineCompliance.property.property_edit',['property_id' => $property->id ?? 0]),
                            'decommission' => $property->decommissioned, 'data_target' => '#decommission_prop','can_update' => $canBeUpdateThisSite,
                            'route_decommission'=> route('shineCompliance.property.decommission',['property_id' => $property->id]) ])
                    @endif
                @include('shineCompliance.modals.decommission_property',[ 'color' => 'red',
                                                    'modal_id' => 'decommission_prop',
                                                    'header' => 'Decommission Property Warning',
                                                    'url' => route('shineCompliance.property.decommission',['property_id' => $property->id]),
                                                    'data' => $decommissioned_reason_prop
                                                    ])
            @endif
            <div class="row">
                @include('shineCompliance.properties.partials._property_sidebar',['property_id' => $property->id])
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_DETAILS))
                @else
                <div class="col-md-9">
                    <div class="card-data mar-up">
                        <div class="property-detail">
                            <div class="btn-toolbar" role="toolbar">
                                    @if(count($warning_message["red_warnings"]) > 0)
                                <div class="btn-group">
                                    <button id="danger-button" class="btn btn-danger dropdown-toggle box-shadow--2dp f13" type="button" data-toggle="dropdown" style="margin-right: 10px">Red Warnings
                                        <span class="caret" style="padding-right: 20px;"></span></button>
                                        <div class="dropdown-menu" style="background-color: #ffe3e6;max-height: 600px;overflow: auto;padding: 0!important;" >
                                            @foreach($warning_message["red_warnings"] as $red_warnings)
                                                <span class="dropdown-item f13 f13" style="color: #bd2130!important;" ><strong>{{ $red_warnings }}</strong></span>
                                                <div class="dropdown-divider" style="border-color: white;padding: 0!important;"></div>
                                            @endforeach
                                        </div>
                                </div>
                                @endif
                                @if(count($warning_message["amber_warnings" ]) > 0)
                                <div class="btn-group">
                                    <button id="warning-button" class="btn btn-warning dropdown-toggle box-shadow--2dp f13" type="button" data-toggle="dropdown" style="margin-right: 10px">Amber Warnings
                                        <span class="caret" style="padding-right: 20px;"></span></button>
                                        <div class="dropdown-menu" style="background-color: #FFF2CC;max-height: 600px;overflow: auto;padding: 0!important;">
                                            @foreach($warning_message["amber_warnings" ] as $amber_warnings)
                                                <span class="dropdown-item f13" style="color: #806000!important;" href="#"><strong>{{ $amber_warnings }}</strong></span>
                                                <div class="dropdown-divider" style="border-color: white;padding: 0!important;"></div>
                                            @endforeach
                                         </div>

                                </div>
                                @endif
                                @if(count($warning_message["green_warnings" ]) > 0)
                                <div class="btn-group">
                                    <button id="success-button" class="btn btn-success dropdown-toggle box-shadow--2dp f13" type="button" data-toggle="dropdown" style="margin-right: 10px">Green Warnings
                                        <span class="caret" style="padding-right: 20px;"></span></button>

                                        <div class="dropdown-menu" style="background-color: #E2EFD9;max-height: 600px;overflow: auto;padding: 0!important;">
                                            @foreach($warning_message["green_warnings" ] as $green_warnings)
                                                <span class="dropdown-item f13" style="color: #538135!important;" href="#"><strong>{{ $green_warnings }}</strong></span>
                                                <div class="dropdown-divider" style="border-color: white;"></div>
                                            @endforeach
                                        </div>

                                </div>
                                @endif
                                @if(count($warning_message["blue_warnings" ]) > 0)
                                <div class="btn-group">
                                    <button id="primary-button" class="btn btn-primary dropdown-toggle box-shadow--2dp f13" type="button" data-toggle="dropdown" style="margin-right: 10px">Blue Warnings
                                        <span class="caret" style="padding-right: 20px;"></span></button>
                                        <div class="dropdown-menu" style="background-color: #D9E2F3;max-height: 600px;overflow: auto">
                                            @foreach($warning_message["blue_warnings" ] as $blue_warnings)
                                                <span class="dropdown-item f13" style="color: #365185!important;" href="#">
                                                    <strong>{{$blue_warnings}}</strong>
                                                </span>
                                            @endforeach
                                        </div>

                                </div>
                                @endif
                            </div>
                            <div class="property-warning list-group-button" style="display: block;">

                                <div class="row-fluid " style="display: inline-block">
                                    <strong>Asbestos Register: </strong>
                                    <a href="{{route('view_property_register_pdf',['type'=>PROPERTY_REGISTER_PDF,'id'=>$property->id])}}" target="_blank"><img src="{{ asset('img/pdf-green.png') }}" width="25" height="25" alt="View PDF Asbestos Register" class="fileicon"/></a>
                                    <a title="Download Asbestos Register PDF" href="{{route('register.pdf',['type'=>PROPERTY_REGISTER_PDF,'id'=>$property->id])}}"  style=""><button class="list-group-btn" style="margin-left: 5px;padding: 6px 8px" title="Download"><i class="fa fa-download fa-2x"></i></button></a>
                                </div>

                                <div class="row-fluid list-group-button" style="display: inline-block;margin: 0 30px">
                                    <strong>Fire Register:</strong>
                                    <a class="view_fire_icon" href="{{route('shineCompliance.view_fire.pdf',['property_id'=>$property->id])}}" target="_blank"><img src="{{ asset('img/pdf-red.png') }}" width="25" height="25" alt="View PDF Asbestos Register" class="fileicon"/></a>
                                    <a title="Download Fire Register PDF" href="{{route('shineCompliance.fire.pdf',['property_id'=>$property->id])}}"  style=""><button class="list-group-btn" style="margin-left: 5px;padding: 6px 8px" title="Download"><i class="fa fa-download fa-2x"></i></button></a>
                                </div>

                                <div class="row-fluid list-group-button" style="display: inline-block">
                                    <strong>H&S Register: </strong>
                                    <a class="view_fire_icon" href="{{route('shineCompliance.view_hs.pdf',['property_id'=>$property->id])}}"><img src="{{ asset('img/hs_pdf.png') }}" width="25" height="25" alt="View PDF Asbestos Register" class="fileicon"/></a>
                                    <a title="Download Fire Register PDF" href="{{route('shineCompliance.hs.pdf',['property_id'=>$property->id])}}"  style=""><button class="list-group-btn" style="margin-left: 5px;padding: 6px 8px" title="Download"><i class="fa fa-download fa-2x"></i></button></a>
                                </div>

                                <div class="row-fluid list-group-button" style="display: inline-block;margin: 0 30px">
                                    <strong>Water Register: </strong>
                                    <a class="view_fire_icon" href="{{route('shineCompliance.view_water.pdf',['property_id'=>$property->id])}}" target="_blank"><img src="{{ asset('img/water_pdf.png') }}" width="25" height="25" alt="View PDF Asbestos Register" class="fileicon"/></a>
                                    <a title="Download Fire Register PDF" href="{{route('shineCompliance.water.pdf',['property_id'=>$property->id])}}"  style=""><button class="list-group-btn" style="margin-left: 5px;padding: 6px 8px" title="Download"><i class="fa fa-download fa-2x"></i></button></a>
                                </div>
                                <div class="red_text" style="color: #5e5f61!important;">
                                    <ul>
                                        <li>The risk register has been formed to facilitate <strong>general occupation</strong> and <strong>routine maintenance</strong></li>
                                        <li>The risk registers must be understood by the <strong>Site Manager</strong> and made available to <strong>all stakeholders</strong></li>
                                        <li><strong>Limitations</strong> and <strong>restrictions</strong> are detailed within the assessments and survey documents</li>
                                        <li><strong>Asbestos Compliance Team:</strong><a href="#"> housingasbestos@westminster.gov.uk</a></li>
                                        <li><strong>Fire Compliance Safety Team: </strong><a href="#"> firesafety@westminster.gov.uk</a></li>
                                        <li><strong>H&S Team:</strong><a href="#"> housingsafetyteam@westminster.gov.uk</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 property-detail-info-first">
                                    <div class="card h-100 discard-border-radius">
                                        <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                                        <div class="card-body " style="padding: 15px;">
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Property Reference:', 'data'=> $property->property_reference ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Block Reference:', 'data' => $property->pblock ?? null])

                                            @include('shineCompliance.forms.form_property_details',['title' => 'Parent Reference:', 'data'=> $parent_property->name ?? '',
                                            'link' => route('shineCompliance.property.property_detail',['id' => $parent_property->id ?? ''])])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Service Area:', 'data'=> $property->serviceArea->description ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Estate Code:', 'data'=> $property->estate_code ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Shine:', 'data'=> $property->reference ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Property Group:', 'data'=> $property->zone->zone_name ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Property Risk Type:', 'data'=> $property->risk_type_text ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Asset Class:', 'data'=> $property->assetClass->description ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Asset Type:', 'data'=> $property->assetType->description ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Property Access Type:', 'data'=> $property->propertySurvey->propertyProgrammeType->description ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Communal Area:', 'data'=> $property->communalArea->description ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Responsibility:', 'data'=> $property->responsibility->description ?? ''])

                                            @include('shineCompliance.forms.form_property_details',['title' => 'Property Client:', 'data'=> $property->clients->name ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['width_label' => 4,'title' => 'Evacuation Strategy:', 'data' => $property->propertySurvey->evacuation_strategy_disp ?? '' ])
                                            @include('shineCompliance.forms.form_property_details',['width_label' => 5,'title' => 'FRA Overall Risk Rating:', 'data' => $property->propertySurvey->fra_overall_disp ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Parking Arrangements:', 'data'=> optional($property->propertySurvey)->parking_arrangements_disp])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Nearest Hospital:', 'data'=> optional($property->propertySurvey)->nearest_hospital])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Restrictions & Limitations:', 'data'=> optional($property->propertySurvey)->restrictions_limitations])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Unusual Features:', 'data'=> optional($property->propertySurvey)->unusual_features])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 property-detail-info-first" style="padding-left: 0;">
                                    <div class="card h-100 discard-border-radius">
                                        <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Construction</strong></div>
                                        <div class="card-body" style="padding: 15px;">
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Property Status:', 'data'=> $property->propertySurvey->property_status_disp ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Property Occupied:', 'data'=> $property->propertySurvey->property_occupied_disp ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Primary Use:', 'data'=> $property->propertySurvey->use_primary_disp ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Secondary Use:', 'data'=> $property->propertySurvey->use_secondary_disp ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Construction Age:', 'data'=> $property->propertySurvey->construction_age ?? ''])

                                            @include('shineCompliance.forms.form_text',['width_label' => 6, 'title' => 'Stair Construction:', 'data' => optional($property->propertySurvey)->stairs_disp ])
                                            @include('shineCompliance.forms.form_text',['width_label' => 6, 'title' => 'Floor Construction:', 'data' => optional($property->propertySurvey)->floors_disp ])
                                            @include('shineCompliance.forms.form_text',['width_label' => 6, 'title' => 'External Wall Construction:', 'data' => optional($property->propertySurvey)->wall_construction_disp ])
                                            @include('shineCompliance.forms.form_text',['width_label' => 6, 'title' => 'External Wall Finish:', 'data' => optional($property->propertySurvey)->wall_finish_disp ])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Listed Building:', 'data'=> $property->propertySurvey->listed_building_disp])
{{--                                            @include('shineCompliance.forms.form_property_details',['title' => 'No. of Floors:',--}}
{{--                                            'data'=> \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_floors ?? null, $property->propertySurvey->size_floors_other ?? null)])--}}
                                            @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Floors Above Ground:', 'data' => \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->floors_above ?? null, $property->propertySurvey->floors_above_other ?? null) ])
                                            @include('shineCompliance.forms.form_text',['width_label' => 6,'title' => 'Floors Below Ground:', 'data' => \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->floors_below ?? null, $property->propertySurvey->floors_below_other ?? null) ])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'No. Staircases:',
                                            'data'=> \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_staircases ?? null, $property->propertySurvey->size_staircases_other ?? null)])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'No. of Lift:',
                                            'data'=> \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_lifts ?? null, $property->propertySurvey->size_lifts_other ?? null)])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Loft Void:',
                                            'data'=> \CommonHelpers::getPropertyDropdownData($property->propertySurvey->loft_void ?? null)])
                                            @if($property->propertyType->contains(DOMESTIC_PROPERTY))
                                                @include('shineCompliance.forms.form_property_details',['title' => 'No. of Bedroom:', 'data'=> $property->propertySurvey->size_bedrooms ?? null])
                                            @endif
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Net Area per Floor(m<sup>2</sup>):', 'data'=> $property->propertySurvey->size_net_area ?? null])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Gross Area(m<sup>2</sup>):', 'data'=> $property->propertySurvey->size_gross_area ?? null])

                                            <div class="row">
                                                <label class="col-md-6 col-form-label text-md-left" >
                                                    <span class="font-weight-bold">Linked Comments:</span>
                                                    <a href="" data-toggle="modal" data-target="#property_comment_history">(History)</a>
                                                </label>
                                                <div class="col-md-6 form-input-text" >
                                                    {!! nl2br($property->comments) ?? null !!}
                                                </div>
                                            </div>
                                            @include('shineCompliance.modals.comment_history',[ 'color' => 'red',
                                                        'modal_id' => 'property_comment_history',
                                                        'header' => 'Historical Property Comments',
                                                        'table_id' => 'property_comment_history_table',
                                                        'url' => route('shineCompliance.comment.property'),
                                                        'data' => $property->commentHistory,
                                                        'id' => $property->id
                                                        ])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 property-detail-info">
                                    <div class="card h-100 discard-border-radius">
                                        <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Address</strong></div>
                                        <div class="card-body" style="padding: 15px">
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Building Name:', 'data' => $property->propertyInfo->building_name ?? null])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Street Number:', 'data'=> $property->propertyInfo->street_number ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Street Name:', 'data'=> $property->propertyInfo->street_name ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Town:', 'data'=> $property->propertyInfo->town ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'County:', 'data'=> $property->propertyInfo->address5 ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Postcode:', 'data'=> $property->propertyInfo->postcode ?? ''])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 property-detail-info" style="padding-left: 0;">
                                    <div class="card h-100 discard-border-radius">
                                        <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Building Occupancy</strong></div>
                                        <div class="card-body" style="padding: 15px">
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Vulnerable Occupant Types:', 'data'=> $property->vulnerableOccupant->vulnerable_occupant_type ?? ''])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Average No. of Occupants:', 'data'=> str_replace('.00','', $property->vulnerableOccupant->avg_vulnerable_occupants ?? '')])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'Max No. of Occupants:', 'data'=> str_replace('.00','', $property->vulnerableOccupant->max_vulnerable_occupants ?? '')])
                                            @include('shineCompliance.forms.form_property_details',['title' => 'No. of Occupants (Last Survey):', 'data'=> str_replace('.00','', $property->vulnerableOccupant->last_vulnerable_occupants ?? '')])
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @include('shineCompliance.tables.property_contacts', [
                                'title' => 'Property Contacts',
                                'tableId' => 'property-contact',
                                'collapsed' => true,
                                'plus_link' => false,
                                'data' => $contactUser
                            ])
                        </div>
                    </div>
                </div>
                @endif
            </div>
    </div>
</div>

@endsection
@push('javascript')
    <script>
        $( document ).ready(function() {
            var permission = {{ \CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION, JR_DYNAMIC_WARNING_BANNER) ? 1 : 0 }}
            if(permission) {

                var warning = {{ count($warning_message) > 0 ? 1 : 0 }}
                var property_session = {{ $property_session }}
                if(property_session !== 0){
                    if(warning !== 0){
                        getBanner();
                    }
                }
                function getBanner() {
                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": 0,
                        "extendedTimeOut": 0,
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut",
                        "tapToDismiss": false
                    }
                    @foreach($warning_message['red_warnings'] as $red_warnings)
                    toastr.error("{{ $red_warnings }}", 'Red Warnings');
                    @endforeach
                    //
                    @foreach($warning_message['amber_warnings'] as $amber_warnings)
                    toastr.warning("{{ $amber_warnings }}", 'Amber Warnings');
                    @endforeach

                    @foreach($warning_message['green_warnings'] as $green_warnings)
                    toastr.success("{{ $green_warnings }}", 'Green Warnings');
                    @endforeach

                    @foreach($warning_message['blue_warnings'] as $blue_warnings)
                    toastr.info("{{ $blue_warnings }}", 'Blue Warnings');
                    @endforeach


                    $('#toast-container').addClass("modal-toastr");
                    $('.toast-error').attr('data-type',"{{DANGER_BANNERS_TYPE}}");
                    $('.toast-warning').attr('data-type',"{{WARNING_BANNERS_TYPE}}");
                    $('.toast-success').attr('data-type',"{{SUCCESS_BANNERS_TYPE}}");
                    $('.toast-info').attr('data-type',"{{INFO_BANNERS_TYPE}}");
                }

                $( ".toast-close-button" ).click(function() {
                    var type = $(this).closest('.toast').attr('data-type');
                    var warning = "alert";
                    $.ajax({
                        type: "GET",
                        url: "{{ route('shineCompliance.ajax.log_warning') }}",
                        data: {
                            property_id: {{ $property->id  }},
                            type : type,
                            warning: warning,
                        },
                        cache: false,
                        success: function (html) {
                        }
                    });
                });

                $( "#danger-button" ).click(function() {
                    logWarning("{{ DANGER_BANNERS_TYPE}}","Button" );
                });
                $( "#warning-button" ).click(function() {
                    logWarning("{{ WARNING_BANNERS_TYPE }}","Button");
                });
                $( "#success-button" ).click(function() {
                    logWarning("{{ SUCCESS_BANNERS_TYPE }}","Button");
                });
                $( "#primary-button" ).click(function() {
                    logWarning("{{ INFO_BANNERS_TYPE}}","Button" );
                });

                function logWarning(type,warning){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('shineCompliance.ajax.log_warning') }}",
                        data: {
                            property_id: {{ $property->id  }},
                            type : type,
                            warning : warning,
                        },
                        cache: false,
                        success: function (html) {
                        }
                    });
                }

            }
        });

        {{--switch(type){--}}
        {{--    case 'info':--}}
        {{--        toastr.info("fdhfdhfdhf", 'dsadasdasdasdasdasdas', new {timeOut:300});--}}
        {{--        break;--}}
        {{--    case 'success':--}}
        {{--        toastr.success("{{ Session::get('message') }}");--}}
        {{--        break;--}}
        {{--    case 'warning':--}}
        {{--        toastr.warning("{{ Session::get('message') }}");--}}
        {{--        break;--}}
        {{--    case 'error':--}}
        {{--        toastr.error("{{ Session::get('message') }}");--}}
        {{--        break;--}}
        {{--}--}}
    </script>
@endpush

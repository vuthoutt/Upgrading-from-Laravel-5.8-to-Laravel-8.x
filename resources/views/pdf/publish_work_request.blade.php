<!DOCTYPE HTML>
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{$work_request->reference}}</title>
<style type="text/css">
    body {
        font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
        font-size: 10pt;
        /*padding: 10px 30px 10px 20px;*/
        color: #666;
    }

    h1, h2, h3, h4, h5, h6 {
        color: #333;
    }

    h2 {
        font-size: 1.5em;
    }

    .topTitle {
        font-size: 1.17em;
        font-weight: bold;
        color: #333;
    }

    .topTitleSmall {
        font-size: 1.11em;
        color: #333;
        font-weight: bold;
    }

    .topTitleBig {
        font-size: 1.5em;
        color: #333;
        font-weight: bold;
    }

    p {
        margin: 1px 0;
        text-align: justify;
    }

    .row {
        padding: 1px 0;
        margin: 0 0 0 0;
    }

    .row p {
        padding: 1px 0;
    }

    table {
        width: 99%;
        margin: 30px 0 20px 0;
    }

    table thead th {
        vertical-align: bottom;
        text-align: center;
        background: #e2e2e2;
    }

    table th, table td {
        padding: 4px;
        line-height: 15px;
        text-align: left;
        vertical-align: top;
    }

    thead {
        display: table-header-group
    }

    tfoot {
        display: table-row-group
    }

    tr {
        page-break-inside: avoid
    }

    .page {
        margin-top: 30px;
        page-break-inside: avoid;
    }

    @media print {
        .element-that-contains-table {
            overflow: visible !important;
        }
    }

    #survey-report {
        height: 1100px;
    }

    #watermark {
        height: 70px;
    }
</style>
</head>
<body>
<div id="container" style="width: 859px">
    <div id="survey-details" style="margin-top:30px;">
        <h2>Work Request Details</h2>
        @if(!isset($work_request->orchardJob) or $work_request->orchardJob->status != 1)
            @if(count($list_contact) > 0)
                @foreach($list_contact as $contact)
                    <div style="width:99%;">
                        <h3 style="margin-top:30px;">Property Contact</h3>
                        <div class="row">
                            <p style="display:inline-block;width:49%;text-align:left;">
                                Property Contact Name: </p>
                            <p style="display:inline-block;width:49%;text-align:left;">
                                {{$contact->full_name ?? ''}}
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;width:49%;text-align:left;">
                                Telephone: </p>
                            <p style="display:inline-block;width:49%;text-align:left;">
                                {{$contact->contact->telephone ?? ''}}
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;width:49%;text-align:left;">
                                Mobile: </p>
                            <p style="display:inline-block;width:49%;text-align:left;">
                                {{$contact->contact->mobile ?? ''}}
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;width:49%;text-align:left;">
                                Email: </p>
                            <p style="display:inline-block;width:49%;text-align:left;">
                                {{$contact->email ?? ''}}
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif

            @if($work_request->non_user == 1)
                @if($list_non_user)
                    <div style="width:99%;">
                        <h3 style="margin-top:30px;">Property Contact Non User</h3>
                        <div class="row">
                            <p style="display:inline-block;width:49%;text-align:left;">
                                Property Contact Name: </p>
                            <p style="display:inline-block;width:49%;text-align:left;">
                                {{$list_non_user->first_name . " " . $list_non_user->last_name}}
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;width:49%;text-align:left;">
                                Telephone: </p>
                            <p style="display:inline-block;width:49%;text-align:left;">
                                {{$list_non_user->telephone}}
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;width:49%;text-align:left;">
                                Mobile: </p>
                            <p style="display:inline-block;width:49%;text-align:left;">
                                {{$list_non_user->mobile}}
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;width:49%;text-align:left;">
                                Email: </p>
                            <p style="display:inline-block;width:49%;text-align:left;">
                                {{$list_non_user->email}}
                            </p>
                        </div>
                    </div>
                @endif
            @endif
        @endif
        <div style="width:99%;">
            <h3 style="margin-top:30px;">Work Requester</h3>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Work Requester: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$work_requester->full_name ?? ''}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Job Role: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$work_requester->contact->job_title ?? ''}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Department: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$work_requester->department->name ?? ''}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Telephone: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$work_requester->contact->telephone ?? ''}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Mobile: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$work_requester->contact->mobile ?? ''}}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Email: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{$work_requester->email ?? ''}}
                </p>
            </div>
        </div>

        <div style="width:99%;">
            <h3 style="margin-top:30px;">{{$work_type}} Work Request Information</h3>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Work Request Reference: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{ $work_request->reference }}
                    @if($work_request_revision > 0)
                        .{{$work_request_revision}}
                    @endif
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Created Date: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{ date("d/m/Y", $work_request->created_date) }}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:49%;text-align:left;">
                    Published Date: </p>
                <p style="display:inline-block;width:49%;text-align:left;">
                    {{ $work_request->published_date }}
                </p>
            </div>
        </div>

        <div style="width:99%;">
            <h3 style="margin-top:30px; margin-bottom:10px;">Document Authorisation</h3>
            <div id="signature-lead" style="display:inline-block;width:49%;text-align:center;margin-top:10px;">
                <img style="max-height: 100px;"
                     src="{{ \CommonHelpers::getFile($work_request->created_by, USER_SIGNATURE, $is_pdf) }}">
                <p style="height: 7px;">&nbsp; </p>
                {{ $work_requester->full_name ?? '' }}
                <p style="height: 5px;">&nbsp;</p>
                Work Requester
            </div>
            <div id="signature-technical" style="display:inline-block;width:49%;text-align:center;margin-top:10px;">
                <img style="max-height: 100px;"
                     src="{{ \CommonHelpers::getFile($work_request->asbestos_lead, USER_SIGNATURE, $is_pdf) }}">
                <p style="height: 7px;">&nbsp;</p>
                {{ $work_asbestos_lead->full_name ?? '' }}
                <p style="height: 5px;">&nbsp;</p>
                Work Request Lead
            </div>
        </div>
    </div><!-- End first page -->
    <div style="page-break-before: always;"></div>
    <div id="excutive-summary" style="margin-top: 30px;">
        <h2>Requirement Details</h2>
        <div style="width:99%;">
            <h3>Property Information</h3>

            <div id="property-thumb" style="margin-top:20px;margin-bottom:20px">
                @if(\CommonHelpers::checkFile($work_request->property_id, PROPERTY_IMAGE))
                    <img height="250"
                         src="{{ \CommonHelpers::getFile($work_request->property_id, PROPERTY_IMAGE, $is_pdf) }}"
                         alt="Property Image"/>
                @endif
            </div>

            <div id="property-details1">
                <p><strong>{{$work_request->property->property_reference ?? ''}}</strong></p>
                <p><strong>{{$work_request->property->pblock ?? ''}}</strong></p>
                <p><strong>{{$work_request->property->name ?? ''}}</strong></p>
                <p>{{$work_request->property->propertyInfo->flat_number ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->building_name ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->street_number ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->street_name ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->address3 ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->address4 ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->address5 ?? ''}}</p>
                <p>{{$work_request->property->propertyInfo->postcode ?? ''}}</p>
            </div>
            @if(!isset($work_request->orchardJob) or $work_request->orchardJob->status != 1)
                @foreach($list_contact as $contact)
                    <div style="width:99%;">
                        <h3 style="margin-top:30px;">Property Contact</h3>
                        <div class="row">
                            <p style="display:inline-block;width:39%;text-align:left;">
                                Property Contact Name: </p>
                            <p style="display:inline-block;width:59%;text-align:left;">
                                {{$contact->full_name ?? ''}}
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;width:39%;text-align:left;">
                                Telephone: </p>
                            <p style="display:inline-block;width:59%;text-align:left;">
                                {{$contact->contact->telephone ?? ''}}
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;width:39%;text-align:left;">
                                Mobile: </p>
                            <p style="display:inline-block;width:59%;text-align:left;">
                                {{$contact->contact->mobile ?? ''}}
                            </p>
                        </div>
                        <div class="row">
                            <p style="display:inline-block;width:39%;text-align:left;">
                                Email: </p>
                            <p style="display:inline-block;width:59%;text-align:left;">
                                {{$contact->email ?? ''}}
                            </p>
                        </div>
                    </div>
                @endforeach
                @if($work_request->non_user == 1)
                    @if($list_non_user)
                        <div style="width:99%;">
                            <h3 style="margin-top:30px;">Property Contact Non User</h3>
                            <div class="row">
                                <p style="display:inline-block;width:39%;text-align:left;">
                                    Property Contact Name: </p>
                                <p style="display:inline-block;width:59%;text-align:left;">
                                    {{$list_non_user->first_name . " " . $list_non_user->last_name}}
                                </p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:39%;text-align:left;">
                                    Telephone: </p>
                                <p style="display:inline-block;width:59%;text-align:left;">
                                    {{$list_non_user->telephone}}
                                </p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:39%;text-align:left;">
                                    Mobile: </p>
                                <p style="display:inline-block;width:59%;text-align:left;">
                                    {{$list_non_user->mobile}}
                                </p>
                            </div>
                            <div class="row">
                                <p style="display:inline-block;width:39%;text-align:left;">
                                    Email: </p>
                                <p style="display:inline-block;width:59%;text-align:left;">
                                    {{$list_non_user->email}}
                                </p>
                            </div>
                        </div>
                    @endif
                @endif
            @endif

            <div style="margin-top: 20px">
                <div class="row">
                    <p style="display:inline-block;width:39%;text-align:left;">
                        Asset Type: </p>
                    <p style="display:inline-block;width:59%;text-align:left;">
                        {{ $work_request->property->assetType->description ?? null  }}
                    </p>
                </div>
                <div class="row" >
                    <p style="display:inline-block;width:39%;text-align:left;">
                        Property Access Type: </p>
                    <p style="display:inline-block;width:59%;text-align:left;">
                        {{ $work_request->property->propertySurvey->propertyProgrammeType->description ?? null }}
                    </p>
                </div>

                <div class="row">
                    <p style="display:inline-block;width:39%;text-align:left;">
                        Property Availability: </p>
                    <p style="display:inline-block;width:59%;text-align:left;">
                        {{ nl2br($work_request->workPropertyInfo->site_availability ?? null) }}
                    </p>
                </div>
            </div>

        <div style="margin-top: 20px">
            <div class="row">
                <p style="display:inline-block;width:39%;text-align:left;">
                    Security Requirements: </p>
                <p style="display:inline-block;width:59%;text-align:left;">
                    {{ nl2br($work_request->workPropertyInfo->security_requirements) }}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:39%;text-align:left;">
                    Parking Arrangements:	 </p>
                <p style="display:inline-block;width:59%;text-align:left;">
                    {{ ($work_request->workPropertyInfo->parking->description ?? '') == 'Other' ? ($work_request->workPropertyInfo->parking_arrangements_other ?? '') : ($work_request->workPropertyInfo->parking->description ?? '') }}
                </p>
            </div>
        </div>

        <div style="margin-top: 20px">
            <div class="row">
                <p style="display:inline-block;width:39%;text-align:left;">
                    Electricity Availability: </p>
                <p style="display:inline-block;width:59%;text-align:left;">
                    {{ ($work_request->workPropertyInfo->electricity_availability == 1) ? 'Yes' : 'No' }}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:39%;text-align:left;">
                    Water Availability:	 </p>
                <p style="display:inline-block;width:59%;text-align:left;">
                    {{ ($work_request->workPropertyInfo->water_availability == 1) ? 'Yes' : 'No' }}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:39%;text-align:left;">
                    Ceiling Height:	 </p>
                <p style="display:inline-block;width:59%;text-align:left;">
                    {{ $work_request->workPropertyInfo->ceiling->description ?? '' }}
                </p>
            </div>
        </div>
    </div>
</div>
<div style="page-break-before: always;"></div>
<div id="excutive-summary" style="margin-top: 30px;">
    <h2 style="margin-top:30px;">Work Request Scope</h2>
    <div style="width:99%;">
        <h3 style="margin-top:20px;"><strong>Scope of Works:</strong></h3>
        <p style="margin-top:20px;">{{ nl2br($work_request->workScope->scope_of_work ?? '') }}</p>
        <div style="margin-top: 20px">
            @if($work_type == 'Air Monitoring')
                <div class="row">
                    <p style="display:inline-block;width:39%;text-align:left;">
                        Air Test Type: </p>
                    <p style="display:inline-block;width:59%;text-align:left;">
                        {{ $air_test_type->description ?? '' }}
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:39%;text-align:left;">
                        Enclosure Size:	 </p>
                    <p style="display:inline-block;width:59%;text-align:left;">
                        {{ isset($work_request->workScope->enclosure_size) ? $work_request->workScope->enclosure_size . ' m²' : '' }}
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:39%;text-align:left;">
                        Duration of Work:</p>
                    <p style="display:inline-block;width:59%;text-align:left;">
                        {{ isset($work_request->workScope->duration_of_work) ? $work_request->workScope->duration_of_work . ' Days' : '' }}
                    </p>
                </div>
            @elseif($work_type == 'Remediation')
                <div class="row">
                    <p style="display:inline-block;width:39%;text-align:left;">
                        Isolation Required: </p>
                    <p style="display:inline-block;width:59%;text-align:left;">
                        {{ ($work_request->workScope->isolation_required ?? 0) == 1 ? 'Yes' : 'No' }}
                    </p>
                </div>
                <div class="row">
                    <p style="display:inline-block;width:39%;text-align:left;">
                        Decant Required:</p>
                    <p style="display:inline-block;width:59%;text-align:left;">
                        {{ ($work_request->workScope->decant_required ?? 0) == 1 ? 'Yes' : 'No' }}
                    </p>
                </div>
            @elseif($work_type == 'Survey')
                @if(in_array($work_request->property->assetType->id ?? 0, [18,19,20]))
                    <div class="row">
                        <p style="display:inline-block;width:39%;text-align:left;">
                            Number of Bedrooms: </p>
                        <p style="display:inline-block;width:59%;text-align:left;">
                            {{ isset($work_request->workScope->number_of_rooms) ? $work_request->workScope->number_of_rooms . ' Rooms' : '' }}
                        </p>
                    </div>
                @endif
                <div class="row">
                    <p style="display:inline-block;width:39%;text-align:left;">
                        Unusual Features:</p>
                    <p style="display:inline-block;width:59%;text-align:left;">
                        {{ nl2br($work_request->workScope->unusual_requirements ?? '') }}
                    </p>
                </div>
            @endif
            <div class="row">
                <p style="display:inline-block;width:39%;text-align:left;">
                    Reported by:</p>
                <p style="display:inline-block;width:59%;text-align:left;">
                    {{ nl2br($work_request->workScope->reported_by ?? '') }}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:39%;text-align:left;">
                    Access Note:</p>
                <p style="display:inline-block;width:59%;text-align:left;">
                    {{ nl2br($work_request->workScope->access_note ?? '') }}
                </p>
            </div>
            <div class="row">
                <p style="display:inline-block;width:39%;text-align:left;">
                    Location Note:</p>
                <p style="display:inline-block;width:59%;text-align:left;">
                    {{ nl2br($work_request->workScope->location_note ?? '') }}
                </p>
            </div>
        </div>
    </div>
    @if($work_type == 'Remediation')
        <h3 style="margin-top:20px;"><strong>Re-Instatement Requirements</strong></h3>
        <p style="margin-top:20px;">{{ nl2br($work_request->workScope->reinstatement_requirments ?? '') }}</p>
    @endif
        <h2 style="margin-top:30px;">Health and Safety Requirements</h2>
        <div style="width:99%;">
            <h3 style="margin-top:20px;"><strong>Property Specific Health and Safety Requirements</strong></h3>
            <p style="margin-top:20px;">{{ nl2br($work_request->workRequirement->site_hs ?? '') }}</p>
            <div style="margin-top: 20px">
                <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="margin-bottom: 20px;">
                    <thead>
                    <tr>
                        <th>Property Health and Safety Requirement</th>
                        <th>Required</th>
                        <th>Comment</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>High Level Access</td>
                        <td>{{ ($work_request->workRequirement->hight_level_access ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->hight_level_access ?? 0) == 1 && isset($work_request->workRequirement->hight_level_access_comment) ? nl2br($work_request->workRequirement->hight_level_access_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Max Height if Over 3m</td>
                        <td>{{ ($work_request->workRequirement->max_height ?? 0 ) == 1 ? 'Yes' : 'No'}}</td>
                        <td>{{ ($work_request->workRequirement->max_height ?? 0) == 1 && isset($work_request->workRequirement->max_height_comment) ? nl2br($work_request->workRequirement->max_height_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Loft Spaces</td>
                        <td>{{ ($work_request->workRequirement->loft_spaces ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->loft_spaces ?? 0) == 1 && isset($work_request->workRequirement->loft_spaces_comment) ? nl2br($work_request->workRequirement->loft_spaces_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Floor Voids</td>
                        <td>{{ ($work_request->workRequirement->floor_voids ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->floor_voids ?? 0) == 1 && isset($work_request->workRequirement->floor_voids_comment) ? nl2br($work_request->workRequirement->floor_voids_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Basements</td>
                        <td>{{ ($work_request->workRequirement->basements ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->basements ?? 0) == 1 && isset($work_request->workRequirement->basements_comment) ? nl2br($work_request->workRequirement->basements_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Ducts</td>
                        <td>{{ ($work_request->workRequirement->ducts ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->ducts ?? 0) == 1 && isset($work_request->workRequirement->ducts_comment) ? nl2br($work_request->workRequirement->ducts_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Lift Shafts</td>
                        <td>{{ ($work_request->workRequirement->lift_shafts ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->lift_shafts ?? 0) == 1 && isset($work_request->workRequirement->lift_shafts_comment) ? nl2br($work_request->workRequirement->lift_shafts_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Light Wells</td>
                        <td>{{ ($work_request->workRequirement->light_wells ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->light_wells ?? 0) == 1 && isset($work_request->workRequirement->light_wells_comment) ? nl2br($work_request->workRequirement->light_wells_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Confined Spaces</td>
                        <td>{{ ($work_request->workRequirement->confined_spaces ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->confined_spaces ?? 0) == 1 && isset($work_request->workRequirement->confined_spaces_comment) ? nl2br($work_request->workRequirement->confined_spaces_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Fumes/Duct</td>
                        <td>{{ ($work_request->workRequirement->fumes_duct ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->fumes_duct ?? 0) == 1 && isset($work_request->workRequirement->fumes_duct_comment) ? nl2br($work_request->workRequirement->fumes_duct_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Patching/Making Good</td>
                        <td>{{ ($work_request->workRequirement->pm_good ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->pm_good ?? 0) == 1 && isset($work_request->workRequirement->pm_good_comment) ? nl2br($work_request->workRequirement->pm_good_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Fragile Materials</td>
                        <td>{{ ($work_request->workRequirement->fragile_material ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->fragile_material ?? 0) == 1 && isset($work_request->workRequirement->fragile_material_comment) ? nl2br($work_request->workRequirement->fragile_material_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Hot/Live Services</td>
                        <td>{{ ($work_request->workRequirement->hot_live_services ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->hot_live_services ?? 0) == 1 && isset($work_request->workRequirement->hot_live_services_comment) ? nl2br($work_request->workRequirement->hot_live_services_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Pigeons</td>
                        <td>{{ ($work_request->workRequirement->pieons ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->pieons ?? 0) == 1 && isset($work_request->workRequirement->pieons_comment) ? nl2br($work_request->workRequirement->pieons_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Vermin</td>
                        <td>{{ ($work_request->workRequirement->vermin ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->vermin ?? 0) == 1 && isset($work_request->workRequirement->vermin_comment) ? nl2br($work_request->workRequirement->vermin_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Biological/Chemical</td>
                        <td>{{ ($work_request->workRequirement->biological_chemical ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->biological_chemical ?? 0) == 1 && isset($work_request->workRequirement->biological_chemical_comment) ? nl2br($work_request->workRequirement->biological_chemical_comment) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Vulnerable Tenant</td>
                        <td>{{ ($work_request->workRequirement->vulnerable_tenant ?? 0) == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ ($work_request->workRequirement->vulnerable_tenant ?? 0) == 1 && isset($work_request->workRequirement->vulnerable_tenant_comment) ? nl2br($work_request->workRequirement->vulnerable_tenant_comment) : '' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h3 style="margin-top:20px;"><strong>Supporting Documents</strong></h3>
            <div style="width:99%;">
                <div class="row" style="margin-top: 20px;">
                    <p style="display:inline-block;width:39%;text-align:left;">
                        Supporting Documents:	 </p>
                    <p style="display:inline-block;width:59%;text-align:left;">
                        {{ $work_request->workSupportingDocument->count() ? $work_request->workSupportingDocument->count() : 'No'}}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

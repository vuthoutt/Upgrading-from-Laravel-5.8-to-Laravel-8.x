@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'programmes_detail', 'color' => 'red', 'data'=> $programme])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $programme->name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._system_button', [
            'backRoute' =>  route('shineCompliance.programme.list',['system_id' => $programme->system_id ?? 0]),
            'route_decommission' =>  route('shineCompliance.programme.decommission',['id' => $programme->id ?? 0]),
            'route_recommission' =>  route('shineCompliance.programme.decommission',['id' => $programme->id ?? 0]),
            'decommission' => $programme->decommissioned ?? 0,
            'editRoute'  => $can_update ? route('shineCompliance.programme.get_edit',['id' => $programme->id ?? 0]) : false
            ])

        <div class="row">
            @include('shineCompliance.properties.partials._property_system_programme_sidebar',
            ['image' =>  asset(\ComplianceHelpers::getSystemFile($programme->id, COMPLIANCE_PROGRAMME_PHOTO)),
            'id' => $programme->id ?? 0,
            'route_document' => route('shineCompliance.programme.document.list', ['id'=>$programme->id ?? 0, 'type'=> DOCUMENT_PROGRAMME_TYPE])
            ])

        <div class="col-md-9 pl-0 pr-0" style="padding: 0" >
            <div class="card-data mar-up">
                <div class="col-md-6">
                    <div class="card discard-border-radius">
                        <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                        <div class="card-body" style="padding: 15px;">
                            <div class="row property-detail-attribute">
                                <div class="col-6 property-detail-attribute-label fs-8pt">Programme Name:</div>
                                <div class="col-6 fs-8pt">{{ $programme->name ?? '' }}</div>
                            </div>
                            <div class="row property-detail-attribute">
                                <div class="col-6 property-detail-attribute-label fs-8pt">Shine:</div>
                                <div class="col-6 fs-8pt">{{ $programme->reference ?? '' }}</div>
                            </div>
                            <div class="row property-detail-attribute">
                                <div class="col-6 property-detail-attribute-label fs-8pt">Date Inspected:</div>
                                <div class="col-6 fs-8pt"> {{ $programme->documentInspection->date ?? '' }}</div>
                            </div>
                            <div class="row property-detail-attribute">
                                <div class="col-6 property-detail-attribute-label fs-8pt">Next Inspection:</div>
                                <div class="col-6 fs-8pt">{{ $programme->next_inspection_display ?? '' }}</div>
                            </div>
                            <div class="row property-detail-attribute">
                                <div class="col-6 property-detail-attribute-label fs-8pt">Days Remaining:</div>
                                <div class="col-6 fs-8pt">
                                    @if(isset($programme->documentInspection))
                                        <span class="badge orange" id="risk-color" style="width: 30px;margin-right: 10px">
                                            {{ $programme->days_remaining ?? 0 }}
                                        </span>
                                        <span>Days</span>
                                    @else
                                        <span class="badge red" id="risk-color" style="margin-right: 10px">
                                            Missing
                                        </span>
                                    @endif
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

@extends('shineCompliance.layouts.app')

@section('content')

@include('shineCompliance.partials.nav',['breadCrumb' => 'system_detail', 'color' => 'red', 'data' => $system])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $system->name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._system_button', [
            'backRoute' =>  route('shineCompliance.systems.list',['property_id' => $system->property_id ?? 0]),
            'route_decommission' =>  route('shineCompliance.systems.decommission',['id' => $system->id ?? 0]),
            'route_recommission' =>  route('shineCompliance.systems.decommission',['id' => $system->id ?? 0]),
            'decommission' => $system->decommissioned ?? 0,
            'editRoute'  => route('shineCompliance.systems.get_edit',['id' => $system->id ?? 0])
            ])

    <div class="row">
        @include('shineCompliance.properties.partials._property_aov_system_sidebar',[
            'image' =>  asset(\ComplianceHelpers::getSystemFile($system->id, COMPLIANCE_SYSTEM_PHOTO)),
            'system_id' => $system->id ?? 0,
            'route_document' => route('shineCompliance.system.document.list', ['id'=>$system->id ?? 0, 'type'=> DOCUMENT_SYSTEM_TYPE])
            ])

        <div class="col-md-9 pl-0 pr-0" style="padding: 0" >
            <div class="card-data mar-up">
                <div class="col-md-6">
                    <div class="card discard-border-radius">
                        <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                        <div class="card-body" style="padding: 15px;">
                            <div class="row property-detail-attribute">
                                <div class="col-6 property-detail-attribute-label fs-8pt">System Name:</div>
                                <div class="col-6 fs-8pt">{{ $system->name ?? '' }}</div>
                            </div>
                            <div class="row property-detail-attribute">
                                <div class="col-6 property-detail-attribute-label fs-8pt">Shine:</div>
                                <div class="col-6 fs-8pt">{{ $system->reference ?? '' }}</div>
                            </div>
                            <div class="row property-detail-attribute">
                                <div class="col-6 property-detail-attribute-label fs-8pt">System Type:</div>
                                <div class="col-6 fs-8pt">{{ $system->systemType->description ?? '' }}</div>
                            </div>
                            <div class="row property-detail-attribute">
                                <div class="col-6 property-detail-attribute-label fs-8pt">Classification:</div>
                                <div class="col-6 fs-8pt">{{ $system->systemClassification->description ?? '' }}</div>
                            </div>
                            <div class="row property-detail-attribute">
                                <div class="col-6 property-detail-attribute-label fs-8pt">System Comment:</div>
                                <div class="col-6 fs-8pt">{!! $system->comment ?? '' !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

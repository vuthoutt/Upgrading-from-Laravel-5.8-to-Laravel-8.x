@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'photography_equipment_detail', 'color' => 'red', 'data' => $equipment ?? '']))
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">Photography</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._system_button', [
            'backRoute' =>  route('shineCompliance.property.equipment',['property_id' => $equipment->property_id ?? 0]),
            'route_decommission' =>  route('shineCompliance.equipment.decommission',['id' => $equipment->id ?? 0]),
            'route_recommission' =>  route('shineCompliance.equipment.decommission',['id' => $equipment->id ?? 0]),
            'decommission' => $equipment->decommissioned ?? 0,
            'editRoute'  => $can_update ? route('shineCompliance.equipment.get_edit_equipment',['id' => $equipment->id ?? 0]) : false
            ])

        <div class="row">
            @include('shineCompliance.properties.partials._property_system_programme_sidebar',
            ['image' =>  asset(\ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_PHOTO)),
            'id' => $equipment->id ?? 0,
            'route' => 'shineCompliance.register_equipment.detail',
            'route_document' => route('shineCompliance.equipment.document.list', ['id'=>$equipment->id ?? 0, 'type'=> DOCUMENT_EQUIPMENT_TYPE]),
            'display_type' => EQUIPMENT_TYPE
            ])

        <div class="col-md-9" >
            <div class="row">
                <div class="col-md-4">
                    <div style="max-height: 450px;">
                        <div class="col-md-12 client-image-show mb-3">
                            <label class="col-form-label text-md-left font-weight-bold" >Location:</label>
                        </div>
                        <div class="col-md-12 client-image-show mb-3">
                            <img class="image-item" src="{{ ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_LOCATION_PHOTO) }}" style="width: 320px">
                        </div>
                    </div>
                    <div class="col-md-12 client-image-show mb-3">
                        <a title="Download Equipment Location Image" href="{{ route('shineCompliance.retrive_image',['type'=>  EQUIPMENT_LOCATION_PHOTO ,'id'=> $equipment->id ]) }}" class="btn download-btn light_grey_gradient_button" style="min-width: 40px"><i class="fa fa-download"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="max-height: 450px;">
                        <div class="col-md-12 client-image-show mb-3">
                            <label class="col-form-label text-md-left font-weight-bold" >Equipment:</label>
                        </div>
                        <div class="col-md-12 client-image-show mb-3">
                            <img class="image-item" src="{{ ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_PHOTO) }}"  style="width: 320px">
                        </div>
                    </div>
                    <div class="col-md-12 client-image-show mb-3">
                        <a title="Download Asbestos Equipment Image" href="{{ route('shineCompliance.retrive_image',['type'=>  EQUIPMENT_PHOTO ,'id'=> $equipment->id ]) }}" class="btn download-btn light_grey_gradient_button" style="min-width: 40px"><i class="fa fa-download"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="max-height: 450px;">
                        <div class="col-md-12 client-image-show mb-3">
                            <label class="col-form-label text-md-left font-weight-bold" >Additional:</label>
                        </div>
                        <div class="col-md-12 client-image-show mb-3">
                            <img class="image-item" src="{{ ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_ADDITION_PHOTO) }}"  style="width: 320px">
                        </div>
                    </div>
                    <div class="col-md-12 client-image-show mb-3">
                        <a title="Download Equipment Additional Image" href="{{ route('shineCompliance.retrive_image',['type'=>  EQUIPMENT_ADDITION_PHOTO ,'id'=> $equipment->id ]) }}" class="btn download-btn light_grey_gradient_button" style="min-width: 40px"><i class="fa fa-download"></i></a>
                    </div>
                    {{--                @endif--}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

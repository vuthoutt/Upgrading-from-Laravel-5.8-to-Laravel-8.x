@extends('shineCompliance.layouts.app')
@section('content')
@if($location->survey_id != 0)
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'items_add_asbestos','data' => $location,'color' => ($location->survey_id == 0) ? 'red' : 'orange'])
@else
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'items_add_compliance','data' => $location,'color' => ($location->survey_id == 0) ? 'red' : 'orange'])
@endif
<div class="container-cus prism-content pl-0">
    <h3 class="title-row">New Item</h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills {{ ($location->survey_id == 0) ? \CommonHelpers::getNavItemColor('red') : \CommonHelpers::getNavItemColor('orange') }}" id="myTab" style="margin-left: -7px !important;">
            <li class="nav-item fs-8pt">
                <a class="nav-link active" data-toggle="tab" href="#details-add"><strong>Details</strong></a>
            </li>
            <li class="nav-item acm fs-8pt">
                <a class="nav-link" data-toggle="tab" href="#material-add"><strong>Material</strong></a>
            </li>
            @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_priority_assessment == ACTIVE) )
            <li class="nav-item acm fs-8pt">
                <a class="nav-link" data-toggle="tab" href="#Priority-add"><strong>Priority</strong></a>
            </li>
            @endif
            @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_photos == ACTIVE) )
            <li class="nav-item fs-8pt">
                <a class="nav-link" data-toggle="tab" href="#Photography-add"><strong>Photography</strong></a>
            </li>
            @endif
            <li class="nav-item acm fs-8pt">
                <a class="nav-link" data-toggle="tab" href="#recommendations-add"><strong>Action/recommendations</strong></a>
            </li>
        </ul>
        <form method="POST" action="{{ route('shineCompliance.item.post_add',['location_id' => $location->id]) }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="details-add" class="container tab-pane active pl-0">
                    @include('shineCompliance.item.add_details',['location' => $location,
                        'specificLocations' => $specificLocations,
                        'reasons' => $reasons,
                        'sampleComments' => $sampleComments,
                        'itemTypes' => $itemTypes,
                        'abestosTypes' => $abestosTypes,
                        'extends' => $extends,
                        'asccessVulners' => $asccessVulners,
                        'additionalInfos' => $additionalInfos,
                        'licenseds' => $licenseds,
                        'airTestComments' => $airTestComments,
                    ])
                </div>

                <div id="material-add" class="container tab-pane fade pl-0">
                     @include('shineCompliance.item.add_material',[
                        'assessmentTypeKeys' => $assessmentTypeKeys,
                        'assessmentDamageKeys' => $assessmentDamageKeys,
                        'assessmentTreatmentKeys' => $assessmentTreatmentKeys,
                        'assessmentAsbestosKeys' => $assessmentAsbestosKeys,

                     ])
                </div>
                @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_priority_assessment == ACTIVE) )
                <div id="Priority-add" class="container tab-pane fade pl-0">
                    @include('shineCompliance.item.add_priority',[
                        'pasPrimaries'=> $pasPrimaries,
                        'pasSecondaryies'=> $pasSecondaryies,
                        'pasLocations'=> $pasLocations,
                        'pasAccessibilities'=> $pasAccessibilities,
                        'pasExtents'=> $pasExtents,
                        'pasNumbers'=> $pasNumbers,
                        'pasHumanFrequencys'=> $pasHumanFrequencys,
                        'pasAverageTimes'=> $pasAverageTimes,
                        'pasTypes'=> $pasTypes,
                        'pasMaintenanceFrequencys'=> $pasMaintenanceFrequencys
                     ])
                </div>
                @endif
                @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_photos == ACTIVE) )
                <div id="Photography-add" class="container tab-pane fade">
                    @include('shineCompliance.item.add_photography')
                </div>
                @endif
                <div id="recommendations-add" class="container tab-pane fade pl-0">
                    @include('shineCompliance.item.add_recommendations',['recommendations' => $recommendations])
                </div>
            </div>
            <div class="col-md-6 offset-md-3 mt-4">
                <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                    <strong>{{ __('Add') }}</strong>
                </button>
            </div>
        </form>
    </div>
@endsection
@push('javascript')

<script type="text/javascript">

</script>
@endpush

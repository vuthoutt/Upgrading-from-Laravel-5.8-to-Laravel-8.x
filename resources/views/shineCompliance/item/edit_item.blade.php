@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav', ['breadCrumb' => 'items_edit_compliance' ,'data' => $item,'color' => ($item->survey_id == 0) ? 'red' : 'orange'])

<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{$item->name}} Details</h3>
    </div>

    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills {{ ($item->survey_id == 0) ? \CommonHelpers::getNavItemColor('red') : \CommonHelpers::getNavItemColor('orange') }}" id="myTab" style="margin-left: -7px !important;">
            <li class="nav-item">
                <a class="nav-link active fs-8pt" data-toggle="tab" href="#details-add"><strong>Details</strong></a>
            </li>
            <li class="nav-item acm">
                <a class="nav-link fs-8pt" data-toggle="tab" href="#material-add"><strong>Material</strong></a>
            </li>
            @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_priority_assessment == ACTIVE) )
            <li class="nav-item acm">
                <a class="nav-link fs-8pt" data-toggle="tab" href="#Priority-add"><strong>Priority</strong></a>
            </li>
            @endif
            @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_photos == ACTIVE) )
            <li class="nav-item">
                <a class="nav-link fs-8pt" data-toggle="tab" href="#Photography-add"><strong>Photography</strong></a>
            </li>
            @endif
            <li class="nav-item acm">
                <a class="nav-link fs-8pt" data-toggle="tab" href="#recommendations-add"><strong>Action/recommendations</strong></a>
            </li>
        </ul>
        <form method="POST" action="{{ route('shineCompliance.item.post_edit', ['id' => $item->id]) }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            <input type="hidden" name="position" value="{{ $position ?? 0 }}">
            <input type="hidden" name="category" value="{{ $category ?? 0 }}">
            <input type="hidden" name="pagination_type" value="{{ $pagination_type ?? 0 }}">
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="details-add" class="container tab-pane active pl-0">
                    @include('shineCompliance.item.edit_details',['location' => $location,
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
                     @include('shineCompliance.item.edit_material',[
                        'assessmentTypeKeys' => $assessmentTypeKeys,
                        'assessmentDamageKeys' => $assessmentDamageKeys,
                        'assessmentTreatmentKeys' => $assessmentTreatmentKeys,
                        'assessmentAsbestosKeys' => $assessmentAsbestosKeys,

                     ])
                </div>
                @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_priority_assessment == ACTIVE) )
                <div id="Priority-add" class="container tab-pane fade pl-0">
                    @include('shineCompliance.item.edit_priority',[
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
                    @include('shineCompliance.item.edit_photography')
                </div>
                @endif
                <div id="recommendations-add" class="container tab-pane fade pl-0">
                    @include('shineCompliance.item.edit_recommendations',['recommendations' => $recommendations])
                </div>
            </div>
            <div class="col-md-6 offset-md-3 mt-4">
                <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    </div>
@endsection
@push('javascript')

<script type="text/javascript">

</script>
@endpush

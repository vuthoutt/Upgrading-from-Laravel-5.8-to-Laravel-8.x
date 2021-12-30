@extends('layouts.app')
@section('content')

@include('partials.nav', ['breadCrumb' => 'survey_property_info', 'data' => $data, 'color' => 'orange'])
<div class="container prism-content">
    <h3>Edit Survey Information </h3>
    <div class="main-content">
        <form method="POST" action="{{ route('get.edit_propertyInfo',['survey_id' => $data->id]) }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            @include('forms.form_upload',['title' => 'Photo:', 'name' => 'property_survey_img', 'object_id' => $data->id, 'folder' => PROPERTY_SURVEY_IMAGE ])
            <a title="Download Asbestos Register Image" href="{{ CommonHelpers::getFile($data->id, PROPERTY_SURVEY_IMAGE) }}" target="_blank" class="btn download-btn" style="    margin-left: 300px;margin-bottom: 20px"><i class="fa fa-download"></i></a>
{{--             @if(CommonHelpers::checkFile($data->client_id, PROPERTY_IMAGE))
                @include('forms.form_display_upload',['title' => 'Photo:', 'object_id' => $data->id, 'folder' => PROPERTY_IMAGE ])
                <a title="Download Asbestos Register Image" href="{{ CommonHelpers::getFile($data->id, PROPERTY_IMAGE) }}" target="_blank" class="btn download-btn"><i class="fa fa-download"></i></a>
            @endif --}}
            <div class="{{ ($data->surveySetting->is_require_construction_details == ACTIVE)  ? '' : 'hide-div' }}">
                @include('forms.form_dropdown',['title' => 'Property Access Type:', 'data' => $programmeTypes, 'name' => 'programmeType', 'key'=> 'id', 'value'=>'description', 'compare_value' => optional($data->surveyInfo->property_data)->programmeType, 'other' => 'programmetypemore' ,'other_value' => optional($data->surveyInfo->property_data)->programmetypemore])
                @include('forms.form_dropdown',['title' => 'Primary Use:', 'data' => $primaryUses, 'name' => 'PrimaryUse', 'key'=> 'id', 'value'=>'description', 'compare_value' => optional($data->surveyInfo->property_data)->PrimaryUse, 'other' => 'primaryusemore' ,'other_value' => optional($data->surveyInfo->property_data)->primaryusemore])
                @include('forms.form_dropdown',['title' => 'Secondary Use:', 'data' => $primaryUses, 'name' => 'SecondaryUse', 'key'=> 'id', 'value'=>'description', 'compare_value' => optional($data->surveyInfo->property_data)->SecondaryUse, 'other' => 'secondaryusemore' ,'other_value' => optional($data->surveyInfo->property_data)->secondaryusemore])
                 @include('forms.form_dropdown',['title' => 'Property Status:', 'data' => $propertyStatus,
                        'name' => 'property_status', 'key'=>'id', 'value'=>'description', 'compare_value' => $data->surveyInfo->property_data->property_status ?? '' ])
                @include('forms.form_dropdown',['title' => 'Property Occupied:', 'data' => $propertyOccupied,
                        'name' => 'property_occupied', 'key'=>'id', 'value'=>'description', 'compare_value' => $data->surveyInfo->property_data->property_occupied ?? ''])
                @include('forms.form_text_area',['title' => 'Construction  Age:', 'data' => optional($data->surveyInfo->property_data)->constructionAge, 'name' => 'constructionAge'])
                @include('forms.form_text_area',['title' => 'Construction  Type:', 'data' => optional($data->surveyInfo->property_data)->constructionType, 'name' => 'constructionType'])
                @include('forms.form_propertyInfo_plus',['title' => 'No. Floors:', 'compare_value' => optional($data->surveyInfo->property_data)->sizeFloors, 'name' => 'sizeFloors', 'other' => optional($data->surveyInfo->property_data)->sizeFloors ])
                @include('forms.form_propertyInfo_plus',['title' => 'No. Staircases:', 'compare_value' => optional($data->surveyInfo->property_data)->sizeStaircases, 'name' => 'sizeStaircases', 'other' => optional($data->surveyInfo->property_data)->sizeStaircases])
                @include('forms.form_propertyInfo_plus',['title' => 'No. Lifts:', 'compare_value' => optional($data->surveyInfo->property_data)->sizeLifts, 'name' => 'sizeLifts', 'other' => optional($data->surveyInfo->property_data)->sizeLifts])

                @if(!empty($dataDropdowns))
                    @foreach($dataDropdowns as $dataDropdown)
                        @include('forms.form_dropdown',['title' => $dataDropdown['description']. ':', 'data' => $dataDropdown['value'], 'name' => $dataDropdown['name'], 'key'=> 'id', 'value'=>'description', 'compare_value' => $dataDropdown['selected'], 'type' => 'site_data'])
                    @endforeach
                @endif

                 @include('forms.form_text_area',['title' => 'Net Area per Floor:', 'data' => optional($data->surveyInfo->property_data)->sizeNetArea, 'name' => 'sizeNetArea'])
                 @include('forms.form_text_area',['title' => 'Gross Area:', 'data' => optional($data->surveyInfo->property_data)->sizeGrossArea, 'name' => 'sizeGrossArea'])
                 @include('forms.form_text_area',['title' => 'Comments:', 'data' => optional($data->surveyInfo->property_data)->sizeComments, 'name' => 'sizeComments'])
            </div>
            <div class="col-md-6 offset-md-3 mt-4">
                <button type="submit" class="btn light_grey_gradient">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('shineCompliance.layouts.app')
@section('content')
    @if($assemblyPoint->assess_id == 0)
        @include('shineCompliance.partials.nav',['breadCrumb' => 'register_assembly_point_edit', 'color' => 'red', 'data'=> $assemblyPoint])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_assembly_point_edit', 'color' => 'orange', 'data'=> $assemblyPoint])
    @endif
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">Edit Assembly Point</h3>
        </div>
        <div class="main-content">
            <form method="POST" action="{{ route('shineCompliance.assessment.post_edit_assembly_point',
            ['assembly_point_id' => $assemblyPoint->id]) }}" enctype="multipart/form-data" class="form-shine">
                @csrf
                <input type="hidden" id="assess_id" name="assess_id" value="{{ $assemblyPoint->assess_id ?? 0 }}">
                <input type="hidden" id="property_id" name="property_id" value="{{ $assemblyPoint->property_id ?? 0 }}">
                @include('shineCompliance.forms.form_input',['title' => 'Assembly Point Name:','name' => 'name', 'data' => $assemblyPoint->name, 'required' => true])
                @include('shineCompliance.forms.form_dropdown_area',['title' => 'Area/floor:', 'name' => 'area',
                'required' => true, 'key'=>'id', 'value'=>'title', 'data' => $areas, 'compare_value' => $assemblyPoint->area_id])
                @include('shineCompliance.forms.form_dropdown_area',['title' => 'Room/location:', 'name' => 'location',
                'required' => true, 'key'=>'id', 'value'=>'title', 'data' => $locations, 'compare_value' => $assemblyPoint->location_id])
                @include('shineCompliance.forms.form_checkbox',['title' => 'Accessibility:', 'name' => 'accessibility', 'data'=> 1, 'compare' => $assemblyPoint->accessibility, 'required' => true])
                @include('shineCompliance.forms.form_dropdown',['title' => 'Reason NA:', 'name' => 'reason_na', 'data' => $reasons, 'key' => 'id', 'value' => 'description', 'compare_value' => $assemblyPoint->reason_na,
                    'other' => 'reason_na_other', 'other_value' => $assemblyPoint->reason_na_other])
                @include('shineCompliance.forms.form_upload',['title' => 'Assembly Point Photo:', 'name' => 'photo', 'object_id' => $assemblyPoint->id, 'folder' => ASSEMBLY_POINT_PHOTO])
                @include('shineCompliance.forms.form_text_area', ['title' => 'Assembly Point Comments:', 'name' => 'comment', 'data' => $assemblyPoint->comment])
                <a href="">
                    <div class="col-md-6 offset-md-3">
                        <button type="submit" id="submit" class="btn light_grey_gradient_button fs-8pt">
                            Save
                        </button>
                    </div>
                </a>
            </form>
        </div>
    </div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function () {
            if ($('#accessibility').is(':checked')) {
                $('#reason_na-form').hide();
            }

            $('#accessibility').change(function () {
                if ($(this).is(':checked')) {
                    $('#reason_na-form').hide();
                    $('#reason_na').val(null);
                } else {
                    $('#reason_na-form').show();
                }
            });

            $('#area').change(function () {
                $('#overlay').fadeIn();
                if ($(this).val() > 0) {
                    $.ajax
                    ({
                        type: "GET",
                        url: "/compliance/assessment/" + $('#assess_id').val() + "/area/" + $(this).val() + "/locations",
                        cache: false,
                        success: function (data) {
                            if (data) {
                                if (data != undefined) {
                                    $('#location').html('');
                                    $('#location').append($('<option>', {
                                        value: '',
                                        text : '------ Please select an option -------'
                                    }));
                                    $.each( data, function( key, value ) {
                                        $('#location').append($('<option>', {
                                            value: value.id,
                                            text : value.location_reference + ' - ' + value.description
                                        }))
                                    });
                                    $('#location').append($('<option>', {
                                        value: -1,
                                        text : 'Other'
                                    }));
                                }
                            }
                        }
                    });
                } else {
                    $('#location').html('');
                    $('#location').append($('<option>', {
                        value: '',
                        text : '------ Please select an option -------'
                    })).append($('<option>', {
                        value: -1,
                        text : 'Other'
                    }));
                }
                $('input[name="location_reference"]').val(null);
                $('input[name="location_description"]').val(null);
                $('#location_other').hide();
                $('#overlay').fadeOut();
            });
        })
    </script>
@endpush

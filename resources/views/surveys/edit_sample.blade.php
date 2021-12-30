@extends('layouts.app')
@section('content')

@include('partials.nav', ['breadCrumb' => 'sample', 'data' => $breadcrumb_data, 'color' => 'orange'])
<div class="container prism-content">
    <h3>Sample {{ $sample->description }} - {{ $sample->reference }}</h3>
    <div class="main-content">
    <form method="POST" action="{{ route('sample_postEdit',['survey_id' => $survey_id, 'sample_id' => $sample->id]) }}" class="form-shine">
        @csrf
        <input type="hidden" name="pagination_type" value="{{ TYPE_SAMPLE }}">
        <input type="hidden" name="position" value="{{ $position }}">
        @include('forms.form_text',['title' => 'Survey ID:', 'data' => $survey_id ])
        @include('forms.form_input',['title' => 'External Reference:', 'name' => 'reference','data' => $sample->description, 'required' => true ])
        <div class="row register-form">
            <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Item(s):</label>
            <div class="col-md-5" style="margin-top: 7px">
                <div class="form-group">
                    @if(!empty($sample->item_reference) and !empty($sample->item_ids))
                         @foreach($sample->item_reference as $key => $item_reference)
                             <a href="{{ route('item.index', ['id' =>  $sample->item_ids[$key]]) }}">{{ $item_reference }}</a>
                              @include('forms.form_input_hidden',['name' => 'list_item_id[]', 'data' => $sample->item_ids[$key] ])
                             @if(!$loop->last)
                                 ,
                             @endif
                         @endforeach
                    @endif
                </div>
            </div>
        </div>
        @include('forms.form_dropdown',['title' => 'Asbestos Type:', 'data' => $abestosTypes, 'name' => 'abestosTypes[]','id' => 'abestosTypes', 'key'=> 'id', 'value'=>'description', 'compare_value' => \CommonHelpers::checkArrayKey2($selectedAsbetosType, 0), 'class_other' =>'acm' ])
        <div class="row register-form mb-4 acm">
            <div class="col-md-5 offset-md-3">
                <select class="form-control" name="abestosTypes[]" id="abestosTypes1" onchange="updateFibre();">
                </select>
            </div>
        </div>

        <div class="row register-form mt-4 acm" id="AsbestosTypeOther">
            <div class="col-md-5 offset-md-3">
                <div class="form-group">
                    <select class="form-control" multiple="multiple" name="AsbestosTypeMore[]" id="AsbestosTypeMore">
                        <option value="Chrysotile">Chrysotile</option>
                        <option value="Amosite">Amosite</option>
                        <option value="Crocidolite">Crocidolite</option>
                        <option value="Tremolite">Tremolite</option>
                        <option value="Actinolite">Actinolite</option>
                        <option value="Anthophyllite">Anthophyllite</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="asbestos-fibre">
            @include('forms.form_dropdown',['title' => 'Asbestos Fibre (d)', 'data' => $assessmentAsbestosKeys, 'name' => 'assessmentAsbestosKey', 'key'=> 'id', 'value'=>'description', 'option_data' => 'score', 'compare_value' => $selectedAssessmentAsbestosKeys ])
        </div>
        @if($canBeUpdateSample)
            <div class="btn" >
                <button type="submit" class="btn light_grey_gradient">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        @endif
    </form>
    </div>
    @include('vendor.pagination.simple-bootstrap-4-customize')
</div>
@endsection
@push('javascript')

<script type="text/javascript">
            // Abestos Type
        $('#AsbestosTypeMore').select2({
            placeholder: "Please select an option"
        }).on('select2:select', function(e){
          var id = e.params.data.id;
          var option = $(e.target).children('[value='+id+']');
          option.detach();
          $(e.target).append(option).change();
        });

        $('#abestosTypes1').hide();
        $('#AsbestosTypeOther').hide();

        $('#AsbestosTypeMore').val(<?php echo json_encode(end($selectedAsbetosType)); ?>);
        $('#AsbestosTypeMore').trigger('change'); // Notify any JS components that the value changed

        $("#abestosTypes1").change(function(){
            var text = $("#abestosTypes1").find(":selected").text();
            if (text == "Other" || text == 'Amphibole (exc. Crocidolite) '|| text == 'Crocidolite'  || text == 'Crocidolite or other') {
                $("#AsbestosTypeOther").show();
            } else {
                $("#AsbestosTypeOther").hide();
            }
        });
        $("#abestosTypes1").trigger('change');
        // OTHER SAMPLE FUNCTION
        function updateFibre() {
            // Get value
            var atud = $('#abestosTypes1').val();
            switch (atud) {
                case "382":
                case "386":
                case "390":
                    // Set
                    $('#assessmentAsbestosKey').val("615");
                    break;
                case "383":
                case "387":
                case "391":
                    $('#assessmentAsbestosKey').val("616");
                    break;
                case "384":
                case "388":
                case "392":
                    $('#assessmentAsbestosKey').val("617");
                    break;
                default:
                    $('#assessmentAsbestosKey').val("");
                    break;
            }
            // Set Fibre
        }

        function unsetFibre() {
            // Get value
            $('#assessmentAsbestosKey').val("");
        }

        $('body').on('change','#abestosTypes',function(){
            $("#AsbestosTypeOther").hide();
            getAjaxItemData('abestosTypes','abestosTypes1', {{ASBESTOS_TYPE_ID}},{{ \CommonHelpers::checkArrayKey2($selectedAsbetosType, 1) }});
            var text = $("#abestosTypes").find(":selected").text();
            if (text == "Other") {
                $("#asbestos-fibre").hide();
            } else {
                $("#asbestos-fibre").show();
            }
        });

        $("#abestosTypes").trigger('change');
        // end of abestos type
        function getAjaxItemData(parent_select_id, child_select_id,dropdown_id, compare , child_select_id2, child_select_id3 ) {
            if (compare === undefined) {
                compare = 'nan';
            }
            if (child_select_id2 === undefined) {
                child_select_id2 = 'nan';
            }
            if (child_select_id3 === undefined) {
                child_select_id3 = 'nan';
            }

            $('#' + child_select_id).find('option').remove();
            var parent_id = $('#' + parent_select_id).val();
            $.ajax({
                type: "GET",
                url: "{{ route('ajax.dropdowns-item') }}",
                data: {dropdown_item_id: dropdown_id, parent_id: parent_id},
                cache: false,
                success: function (html) {
                    if (html.data.length > 0) {
                        var selected_val = false;
                        $.each(html.data, function(key, value, selected ) {
                            if (value.id == compare) {
                                selected_val = true;
                            }
                            $('#' + child_select_id).append($('<option>', {
                                value: value.id,
                                text : value.description,
                                selected : selected_val
                            }));
                            selected_val = false;

                        })

                        $('#' + child_select_id).show().trigger('change');

                    } else {
                        $('#' + child_select_id).hide();
                        $('#' + child_select_id2).hide();
                        $('#' + child_select_id3).hide();
                        unsetFibre();
                        $("#productDebris1").show();
                    }
                },
            });
    }
</script>
@endpush

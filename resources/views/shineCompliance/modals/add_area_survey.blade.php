<!-- Modal -->
<div class="modal fade" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header {{ isset($color) ? CommonHelpers::getNavColor($color) : 'red_gradient' }}">
                <h5 class="modal-title" id="exampleModalLabel">shineArc - {{ $action == 'edit' ? 'Edit' : 'Add' }} Area/floor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form >
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}" />
                <div class="modal-body" style="text-align: center;">
                    @if($action == 'edit')
                        <strong>Edit area/floor information</strong>
                    @else
                        <strong>Please fill new area/floor information</strong>
                    @endif
                    <span class="invalid-feedback" role="alert" id="fail-create"></span>
                    <div class="mt-4">
                        @if(isset($survey))
                            <input type="hidden" class="form-control" name="survey-id" id="survey-id" value="{{ $survey->id }}">
                            <input type="hidden" class="form-control" name="property-id" id="property-id" value="{{ $survey->property_id }}">
                        @else
                            <input type="hidden" class="form-control" name="survey-id" id="survey-id" value="0">
                            <input type="hidden" class="form-control" name="property-id" id="property-id" value="{{ $property->id }}">
                        @endif
                        @include('forms.form_input',['title' => 'Reference:', 'name' => 'area_reference','id' => 'form-area_reference', 'width' => 8 , 'data' => isset($data) ? $data->area_reference : '', 'required' => true])
                        <span class="invalid-feedback font-weight-bold" role="alert" id="area_reference-err" style="font-size: 100% !important"></span>
                        @include('forms.form_input',['title' => 'Description:' ,'name' => 'description','id' => 'form-description', 'width' => 8, 'data' => isset($data) ? $data->description : '', 'required' => true ])
                        <span class="invalid-feedback font-weight-bold" role="alert" id="description-err" style="font-size: 100% !important"></span>
                    </div>
                </div>
                <div class="mb-3" style="text-align: center;">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                    <button class="btn light_grey_gradient_button fs-8pt shine_submit_modal" id="submit_area">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('javascript')
    <script>
        $(document).ready(function(){
            $('#submit_area').click(function(e){
                $('.invalid-feedback').html("");
                $('#reference').removeClass('is-invalid');
                $('#description').removeClass('is-invalid');
                var survey_id = $('#survey-id').val();
                var property_id = $('#property-id').val();
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ $url }}",
                    method: 'post',
                    data: {
                        area_reference: $('#form-area_reference').val(),
                        description: $('#form-description').val(),
                        property_id: property_id,
                        survey_id: survey_id
                    },
                    success: function(data){
                        if(data.errors) {
                            $.each(data.errors, function(key, value){
                                $('#'+key+'-err').html(value[0]);
                                $('#form-'+key).addClass('is-invalid');
                                $('.invalid-feedback').show();
                            });
                        } else if(data.status_code == 200) {
                            if (survey_id == 0) {
                                location.href = '/compliance/property/detail/' + property_id + '?section={{ SECTION_AREA_FLOORS_SUMMARY }}&area=' + data.id +'&position={{$position ?? 0}}&category={{$category ?? 0}}';
                            } else {
                                location.href = '/compliance/surveys/' + survey_id + '?section={{ SECTION_AREA_FLOORS_SUMMARY }}&area=' + data.id +'&position={{$position ?? 0}}&category={{$category ?? 0}}';
                            }
                        } else {
                            $('#fail-create').html(data.success);
                            $('.invalid-feedback').show();
                        }
                    }
                });
            });

            $("#{{ isset($modal_id) ? $modal_id : '' }}").on("hidden.bs.modal", function () {
                $('.invalid-feedback').html("");
                $('#form-area_reference').removeClass('is-invalid');
                $('#form-description').removeClass('is-invalid');
            });
        });

    </script>
@endpush

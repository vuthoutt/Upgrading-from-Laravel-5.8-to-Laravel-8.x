<!-- Modal -->
<div class="modal fade pr-0" id="{{ isset($modal_id) ? $modal_id : '' }}" tabindex="-1"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px !important;">
            <div class="modal-header" style="color: #fff; background-color: {{isset($color) ? 'orange' : 'red'}}; border-radius:0px !important;">
                <h5 class="modal-title" id="exampleModalLabel">shineArc - Add Hazard</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding-left: 20px;min-height:50px;text-align: center;">
                <strong class="">Do you want to create a Hazard?</strong>
            </div>
            <div class="mb-4" style="text-align: center;">
                <button type="button" class="btn light_grey_gradient_button fs-8pt" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn light_grey_gradient_button shine_document_submit fs-8pt add_hazard_btn" data-question-id="0">Submit</button>
            </div>
        </div>
    </div>
</div>
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.add_hazard_btn').click(function(e){
                var question_id = $(this).data('question-id');
                var verb_id = $(this).data('verb-id');
                var noun_id = $(this).data('noun-id');
                var name = $(this).data('name');
                $('#{{$modal_id}}').modal('hide');
                var link_add_hz = "{!! route('shineCompliance.assessment.get_add_hazard',[
                                            'property_id' => $assessment->property_id ?? 0,
                                            'assess_id' => $assess_id,
                                            'assess_type' => $assess_type,
                                    ]) !!}";
                if(question_id > 0){
                    link_add_hz += '&question_id=' + question_id;
                }
                if (verb_id != '') {
                    link_add_hz += '&verb_id=' + verb_id;
                }
                if (noun_id != '') {
                    link_add_hz += '&noun_id=' + noun_id;
                }
                if (name != '') {
                    link_add_hz += '&name=' + name;
                }
                window.open(link_add_hz, '_blank');
            });
        });
    </script>
@endpush

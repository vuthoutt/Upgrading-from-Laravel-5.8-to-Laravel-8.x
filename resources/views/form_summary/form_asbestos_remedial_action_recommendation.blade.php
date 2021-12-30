<div class="row register-form form-summary action-recommendation">
    <label class="font-weight-bold col-md-12" >What option would you like to view?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="action_recommendation" id="action-recommendation">
                <option value='allActionRemoval'>All Remedial & Removal Action/Recommendations</option>
                <option value='bespoke'>Bespoke</option>
            </select>
        </div>
    </div>
</div>

<div class="row register-form form-summary" id="action-recommendation-list">
    <label class="font-weight-bold col-md-12" >What Action/recommendation would you like to view?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="action_recommendation_list" id="ActionsRecommendation1">
            </select>
{{--             <select class="form-control mt-3" name="ActionsRecommendations[]" id="ActionsRecommendation2">
            </select> --}}
        </div>
    </div>
</div>
@push('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $("#action-recommendation").change(function () {
            var type = $(this).val();
            $("#action-recommendation-list").hide();
            if (type == 'bespoke') {
                $("#action-recommendation-list").show();
            } else {
                $("#action-recommendation-list").hide();
            }
        });

        getAjaxItemData('action-recommendation','ActionsRecommendation1', {{ACTIONS_RECOMMENDATIONS_ID}}, 'ActionsRecommendation2',true);

        function getAjaxItemData(parent_select_id, child_select_id,dropdown_id, child_select_id2, limit_id ) {
            $('#' + child_select_id).find('option').remove();

            var parent_id = 0;
            $.ajax({
                type: "GET",
                url: "{{ route('ajax.dropdowns-item') }}",
                data: {dropdown_item_id: dropdown_id, parent_id: parent_id},
                cache: false,
                success: function (html) {
                    if (html.data.length > 0) {
                        $.each(html.data, function(key, value ) {
                            var limit_id = {{ json_encode(ACTION_RECOMMENDATION_LIST_ID) }};
                            if (limit_id.includes(value.id)) {
                                $('#' + child_select_id).append($('<option>', {
                                    value: value.id,
                                    text : value.description
                                }));
                            }
                        })
                        $('#' + child_select_id).show().trigger('change');
                    } else {
                        $('#' + child_select_id).hide();
                        $('#' + child_select_id2).hide();
                        $("#productDebris1").show();
                    }
                },
            });
        }
    });
</script>
@endpush
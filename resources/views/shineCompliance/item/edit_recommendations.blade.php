<div class="offset-top40">
    @include('shineCompliance.forms.form_dropdown',[
    'title' => 'Actions / recommendations',
     'data' => $recommendations,
    'name' => 'ActionsRecommendations[]',
    'key'=> 'id',
    'value'=>'description',
     'compare_value' => \CommonHelpers::checkArrayKey2($selectedRecommendations, 0),
     'id' => 'ActionsRecommendations'
     ])
    <div class="row register-form" id="form_action2">
        <div class="col-md-5 offset-md-3">
            <select class="form-control" name="ActionsRecommendations[]" id="ActionsRecommendation1">
            </select>
        </div>
    </div>
    <div class="row register-form" id="other_action_rec">
        <div class="col-md-5 offset-md-3">
            <input class="form-control mt-4" type="text" name="ActionsRecommendations_other" id="ActionsRecommendations_other" value="{{ $item->ActionRecommendationValue->dropdown_other ?? '' }}" />
        </div>
    </div>
</div>
@push('javascript')

<script type="text/javascript">
    $(document).ready(function(){
        $('#ActionsRecommendation1').hide();
        $('#form_action2').hide();
        $("#ActionsRecommendations").change(function(){
           getAjaxItemData('ActionsRecommendations','ActionsRecommendation1', {{ACTIONS_RECOMMENDATIONS_ID}});
           var value_action = $(this).val();
           if (value_action == 493) {
                $("#other_action_rec").show();
           } else {
                $("#other_action_rec").hide();
           }
        });
        $("#ActionsRecommendations").trigger('change');

        function getAjaxItemData(parent_select_id, child_select_id,dropdown_id, compare, child_select_id2, child_select_id3  ) {
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
                url: "{{ route('shineCompliance.item.dropdown_item') }}",
                data: {dropdown_item_id: dropdown_id, parent_id: parent_id},
                cache: false,
                success: function (html) {
                    if (html.data.length > 0) {
                        var selected_val = false;
                        $.each(html.data, function(key, value, selected ) {
                            if (value.id == {{ \CommonHelpers::checkArrayKey2($selectedRecommendations, 1) }}) {
                                selected_val = true;
                            }
                            $('#' + child_select_id).append($('<option>', {
                                value: value.id,
                                text : value.description,
                                selected : selected_val
                            }));
                            selected_val = false;
                        })
                        $('#form_action2').show();
                        $('#' + child_select_id).show().trigger('change');
                    } else {
                        $('#form_action2').hide();
                    }
                },
            });
        }

    });
</script>
@endpush

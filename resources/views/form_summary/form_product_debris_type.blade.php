<div class="row form-summary" id="form-{{ $id ?? '' }}">
    <label class="col-md-12 col-form-label text-md-left font-weight-bold" >What Product/debris would you like to view?</label>
    <div class="col-md-12 row">
        <select class="form-control input-summary col-md-3 ml-3" name="productDebris[]" id="productDebris1">
        </select>
        <select class="form-control input-summary col-md-3 ml-3" name="productDebris[]" id="productDebris2">
        </select>
        <select class="form-control input-summary col-md-3 ml-3" name="productDebris[]" id="productDebris3">
        </select>
        <input type="text" class="form-control mt-4" name="productDebrisOther" id="productDebris-other" placeholder="Please add other product debris">
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){
    $("#productDebris2").hide();
    $("#productDebris3").hide();
    $("#productDebris-other").hide();

    getAjaxItemData(174,'productDebris1', {{PRODUCT_DEBRIS_TYPE_ID}},'productDebris2', 'productDebris3', true);

    $("#productDebris1").change(function(){
        getAjaxItemData('productDebris1','productDebris2', {{PRODUCT_DEBRIS_TYPE_ID}}, 'productDebris3',false);
        var text = $("#productDebris1").find(":selected").text();
        if (text.includes("Other")) {
            $("#productDebris-other").show();
        } else {
            $("#productDebris-other").hide();
        }
    });
    $("#productDebris2").change(function(){
        getAjaxItemData('productDebris2','productDebris3', {{PRODUCT_DEBRIS_TYPE_ID}});
    });

    function getAjaxItemData(parent_select_id, child_select_id,dropdown_id, child_select_id2, first_load ) {
        $('#' + child_select_id).find('option').remove();
        if (first_load) {
            parent_id = parent_select_id;
        } else {
            var parent_id = $('#' + parent_select_id).val();
        }

        $.ajax({
            type: "GET",
            url: "{{ route('ajax.dropdowns-item') }}",
            data: {dropdown_item_id: dropdown_id, parent_id: parent_id},
            cache: false,
            success: function (html) {
                if (html.data.length > 0) {
                    $.each(html.data, function(key, value ) {
                        $('#' + child_select_id).append($('<option>', {
                            value: value.id,
                            text : value.description
                        }));
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
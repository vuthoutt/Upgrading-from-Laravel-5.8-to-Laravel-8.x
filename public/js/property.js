$(document).ready(function(){
    $("#asset_use_primary").change(function () {
        var id = $(this).val();
        var dataString = 'id=' + id;
        var additionalInfoKey = $.trim($(this).find(":selected").text().replace(" ", "")).toLowerCase();
        if (additionalInfoKey == "other") {
            $("#primaryuseother").show();
            $("#primaryuseother").html('<input type="text" name="asset_use_primary_other" id="asset_use_primary_other" />');
        }
        else {
            $("#primaryuseother").hide();
            $("#primaryuseother").html('');
        }

    });

    $("#asset_use_secondary").change(function () {
        var id = $(this).val();
        var dataString = 'id=' + id;
        var additionalInfoKey = $.trim($(this).find(":selected").text().replace(" ", "")).toLowerCase();
        if (additionalInfoKey == "other") {
            $("#primaryusesecondother").show();
            $("#primaryusesecondother").html('<input type="text" name="asset_use_secondary_other" id="asset_use_secondary_other" />');
        }
        else {
            $("#primaryusesecondother").hide();
            $("#primaryusesecondother").html('');
        }

    });

    $("#sizeFloors").on('change',function(){
        if($("#sizeFloors").val() == 'Other'){
            $("#sizeFloorsOther").show();
            $("#sizeFloorsOther").attr("name","sizeFloors");
            $("#sizeFloors").attr("name","sizeFloorsOther");
        }else{
            $("#sizeFloorsOther").hide();
            $("#sizeFloorsOther").attr("name","sizeFloorsOther");
            $("#sizeFloors").attr("name","sizeFloors");
        }
    });
});

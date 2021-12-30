$(document).ready(function(){
    // library : hoangtran_multi_option
    // licensed key : 160595
    // when submit form need to get all value of options into a input
    $('.warning-text').hide();
    $('#warning_contractor_option_0').hide();

    // click remove
    $('body').on('click','.btn-minus',function(){
        var max_option = $('#max_option').val();
        var index = $(this).closest('.row-select').find('.form-select-option').val();
        var total = $('.form-select-option').last().val();
        var html = '';
        var length = $('.form-select-option').length;
        $('.form-select-option').each(function(k,v){
            if(k > index){
                var label_name = $('#title_option').val() + " " + k + ':';
                $(this).closest('.row-select').find('label:first').text(label_name);
                $(this).val((k-1));
                // minus or minus and plus
                if(k < max_option && k < total){
                    //middle then only have minus btn
                    html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                        '                        </a>';

                } else if(k < max_option && k == total){
                    //last and not bigger than total
                    html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                        '                        </a>\n' +
                        '                        <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                } else {
                    //last then have both btn
                    html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                }
                $(this).closest('.row-select').find('.btn-option').html(html);

            }
            if(k  == (length - 2) && index == total){ // above the elemen being delete
                    html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                        '                        </a>\n' +
                        '                        <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                    $(this).closest('.row-select').find('.btn-option').html(html);
            }
        })
        $(this).closest('.row-select').remove();
        //if there are 2 two more, then first option only have plus icon
        if(total == 1){
            html =  '<a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">\n' +
                '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                '                        </a>';
            $('.form-select-option').first().closest('.row-select').find('.btn-option').html(html);
        }

    });
    // click add
    $('body').on('click','.btn-plus',function(){
        var max_option = $('#max_option').val();
        var index = parseInt($(this).closest('.row-select').find('.form-select-option').val());
        var total = $('.form-select-option').last().val();
        var html = '';
        var append = $(this).closest('.row-select').clone();
        var label_name = $('#title_option').val() + " " + (index + 2) + ':';
        $(append).find('option:selected').removeAttr('selected');
        $(append).find('.primary').removeAttr('checked');
        $(append).find('.warning-text').hide();
        $(append).find('.form-select-option').val(index + 1);
        $(append).find('label:first').text(label_name);
        $(append).find('.project-select-contractor').attr("id","contractor_option_" + (index + 1));
        $(append).find('span:last').attr("id","warning_contractor_option_" + (index + 1));

        // not last then both
        // console.log(index, total, max_option);
        if(index < (max_option-1)){
            html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                '                        </a>\n' +
                '                        <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">\n' +
                '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                '                        </a>';
        } else {
            //last then only minus
            html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                '                        </a>';
        }
        $(append).find('.btn-option').html(html);
        $('.parent-option').append($(append));
        if(index == 0){ // case plus first select need to add minus button
            html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                '                        </a>';
            $(this).closest('.row-select').find('.btn-option').html(html);
        }
        //remove plus at old option
        $(this).closest('.row-select').find('.btn-option').find('.btn-plus').remove();
        // console.log(index);
    });


    // on change contractor select
    $('body').on('change','.project-select-contractor',function(){

        $('#selected_' + $(this).attr('id')).val($(this).val());
        var error = checkSelected(this, $(this).attr('id'));
        if(error){
            $('#warning_' + $(this).attr('id')).show();
        } else {
            $('#warning_' + $(this).attr('id')).hide();
        }
    });

    function checkSelected(that, id){
        var check_value = $(that).val();
        var check = false;

        $('.project-select-contractor').not('#'+id).find('option:selected').each(function(k,v){
            if(check_value == $(v).val()){
                check = true;
                return false;//break
            }
        })
        return check;
    }

    // $('.project-select-contractor').trigger('change');

})

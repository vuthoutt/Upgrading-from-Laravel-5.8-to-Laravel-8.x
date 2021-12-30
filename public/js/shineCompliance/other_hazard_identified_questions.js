$(document).ready(function() {
    // click remove
    $('body').on('click','.btn-minus',function(){
        var max_option = $('#max_option').val();
        var index = $(this).closest('.row-other-hazard-question').find('.form-select-answer').val();
        var total = $('.form-select-answer').last().val();
        // console.log(total); return;
        var html = '';
        var length = $('.form-select-answer').length;
        $('.form-select-answer').each(function(k,v){
            if(k > index){
                var label_name = $('#title_option').val() + " " + k + ':';
                $(this).closest('.row-other-hazard-question').find('label:first').text(label_name);
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
                $(this).closest('.row-other-hazard-question').find('.btn-option').html(html);

            }
            if(k  == (length - 2) && index == total){ // above the elemen being delete
                html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                    '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                    '                        </a>\n' +
                    '                        <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">\n' +
                    '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                    '                        </a>';
                $(this).closest('.row-other-hazard-question').find('.btn-option').html(html);
            }
        })
        $(this).closest('.row-other-hazard-question').remove();
        //if there are 2 two more, then first option only have plus icon
        if(total == 1){
            html =  '<a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">\n' +
                '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                '                        </a>';
            $('.form-select-answer').first().closest('.row-other-hazard-question').find('.btn-option').html(html);
        }
        $(".choose-answer").trigger('change', ['remove_question']);
    });
    // click add
    $('body').on('click','.btn-plus',function(){
        var max_option = $('#max_option').val();
        var index = parseInt($(this).closest('.row-other-hazard-question').find('.form-select-answer').val());
        var total = $('.form-select-answer').last().val();
        var html = '';
        var append = $(this).closest('.row-other-hazard-question').clone();
        $(append).find('option:selected').removeAttr('selected');
        $(append).find('.other-hazard-comment').val('');
        $(append).find('.form-select-answer').val(index + 1);

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
        $('.other-hazard-identified-questions').append($(append));
        if(index == 0){ // case plus first select need to add minus button
            html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                '                        </a>';
            $(this).closest('.row-other-hazard-question').find('.btn-option').html(html);
        }
        //remove plus at old option
        $(this).closest('.row-other-hazard-question').find('.btn-option').find('.btn-plus').remove();
        // console.log(index);
    });
})

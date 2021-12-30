@php
    isset($max_option) ? $max_option = $max_option : $max_option = 9 ;
@endphp
<div class="parent-option mt-3">
    <input type="hidden" id="title_option" value="{{ ($title) ? $title : '' }}">

    <input type="hidden" id="max_option" value="{{ $max_option }}">
    <!-- if have data then display all data else display an empty option -->
    {{-- add --}}

    @if(empty($selected))
        <div class="row row-select {{ isset($class) ? $class : '' }}">
            <label class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ ($title) ? $title." 1" : '' }}:</label>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="hidden" class="form-select-option" value="0">
                    <input type="file" class="form-control-file document-incident-report" name="{{ isset($name) ? $name : 'files' }}[]" id="document_0" />
                </div>
            </div>
            <div class="col-md-3 btn-option">
                <a class="btn btn-default col-md-2 btn-plus add-document" href="javascript:void(0)">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        {{-- edit --}}
    @else
        @php
            $arr_data_decode = $selected;
            $count = count($arr_data_decode) - 1;// for check with key
            $index = 0;
        @endphp
        @foreach($arr_data_decode as $key => $value)
            <div class="row row-select">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ ($title) ? $title." ".($key+1) : '' }}</label>
                <div class="col-md-3">
                    <div class="form-group mb-4">
                        @if(isset($value->filename))
                            <div class="col-md-12 form-input-text">
                                {{$value->filename}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @php
                $index++;
            @endphp
        @endforeach
        <div class="row row-select {{ isset($class) ? $class : '' }}">
            <label class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ ($title) ? $title." ".($index+1) : '' }}:</label>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="hidden" class="form-select-option" value="0">
                    <input type="file" class="form-control-file document-incident-report" name="{{ isset($name) ? $name : 'files' }}[]" id="document_0" />
                </div>
            </div>
            <div class="col-md-3 btn-option">
                <a class="btn btn-default col-md-2 btn-plus add-document" href="javascript:void(0)">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    @endif
</div>

@push('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            // click remove
            $('body').on('click', '.remove-document', function () {
                var max_option = $('#max_option').val();
                var index = $(this).closest('.row-select').find('.form-select-option').val();
                var total = $('.form-select-option').last().val();
                var html = '';
                var length = $('.form-select-option').length;
                $('.form-select-option').each(function (k, v) {
                    if (k > index) {
                        var label_name = $('#title_option').val() + " " + k + ':';
                        $(this).closest('.row-select').find('label:first').text(label_name);
                        $(this).val((k - 1));
                        // minus or minus and plus
                        if (k < max_option && k < total) {
                            //middle then only have minus btn
                            html = '<a class="btn btn-default col-md-2 remove-document" href="javascript:void(0)">\n' +
                                '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                                '                        </a>';

                        } else if (k < max_option && k == total) {
                            //last and not bigger than total
                            html = '<a class="btn btn-default col-md-2 remove-document" href="javascript:void(0)">\n' +
                                '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                                '                        </a>\n' +
                                '                        <a class="btn btn-default col-md-2 add-document" href="javascript:void(0)">\n' +
                                '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                                '                        </a>';
                        } else {
                            //last then have both btn
                            html = '<a class="btn btn-default col-md-2 remove-document" href="javascript:void(0)">\n' +
                                '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                                '                        </a>';
                        }
                        $(this).closest('.row-select').find('.btn-option').html(html);

                    }
                    if (k == (length - 2) && index == total) { // above the elemen being delete
                        html = '<a class="btn btn-default col-md-2 remove-document" href="javascript:void(0)">\n' +
                            '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                            '                        </a>\n' +
                            '                        <a class="btn btn-default col-md-2 add-document" href="javascript:void(0)">\n' +
                            '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                            '                        </a>';
                        $(this).closest('.row-select').find('.btn-option').html(html);
                    }
                })
                $(this).closest('.row-select').remove();
                //if there are 2 two more, then first option only have plus icon
                if (total == 1) {
                    html = '<a class="btn btn-default col-md-2 add-document" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                    $('.form-select-option').first().closest('.row-select').find('.btn-option').html(html);
                }

            });
            // click add
            $('body').on('click', '.add-document', function () {
                var max_option = $('#max_option').val();
                var existed_index = parseInt({!! isset($index) ? $index : 0 !!});
                var index = parseInt($(this).closest('.row-select').find('.form-select-option').val());
                var total = $('.form-select-option').last().val();
                var html = '';
                var append = $(this).closest('.row-select').clone();
                var label_name = $('#title_option').val() + " " + (index + 2) + ':';
                if (existed_index !== 0) {
                    label_name = $('#title_option').val() + " " + (existed_index + index + 2) + ':';
                }
                $(append).find('option:selected').removeAttr('selected');
                $(append).find('.primary').removeAttr('checked');
                $(append).find('.warning-text').hide();
                $(append).find('.form-select-option').val(index + 1);
                $(append).find('label:first').text(label_name);
                $(append).find('.document-incident-report').attr("id", "document_" + (index + 1));
                $(append).find('.document-incident-report').val('');
                $(append).find('span:last').attr("id", "warning_contractor_option_" + (index + 1));

                // not last then both
                // console.log(index, total, max_option);
                if (index < (max_option - 1)) {
                    html = '<a class="btn btn-default col-md-2 remove-document" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                        '                        </a>\n' +
                        '                        <a class="btn btn-default col-md-2 add-document" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                } else {
                    //last then only minus
                    html = '<a class="btn btn-default col-md-2 remove-document" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                }
                $(append).find('.btn-option').html(html);
                $('.parent-option').append($(append));
                if (index == 0) { // case plus first select need to add minus button
                    html = '<a class="btn btn-default col-md-2 remove-document" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                    $(this).closest('.row-select').find('.btn-option').html(html);
                }
                //remove plus at old option
                $(this).closest('.row-select').find('.btn-option').find('.add-document').remove();
                // console.log(index);
            });
        });
    </script>
@endpush

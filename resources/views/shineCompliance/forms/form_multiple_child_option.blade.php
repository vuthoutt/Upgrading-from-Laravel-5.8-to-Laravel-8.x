@php
    isset($max_option) ? $max_option = $max_option : $max_option = 9 ;
@endphp
<div class="{{ isset($name) ? $name : 'team' }}-parent-option mt-3">
    <input type="hidden" id="{{ isset($name) ? $name : 'team' }}_title_option" value="{{ ($title) ? $title : '' }}">

    <input type="hidden" id="{{ isset($name) ? $name : 'team' }}_max_option" value="{{ $max_option }}">
    <!-- if have data then display all data else display an empty option -->
    {{-- add --}}

    @if(empty($selected))
        <div class="row {{ isset($name) ? $name : 'team' }}-child-select">
            <label class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ $title ?? '' }}:</label>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="hidden" class="{{ isset($name) ? $name : 'team' }}-form-select-option" value="0">
                    <select class="form-control {{ isset($name) ? $name : 'team' }}-select" name="{{ $id ?? 'users' }}[0][reasons][{{ isset($name) ? $name : 'team' }}][]">
                        <option value="0">------ Please select an option -------</option>
                        @foreach($dropdown_list as $option)
                            <option value="{{$option->id}}" >{{$option->description }}</option>
                        @endforeach
                    </select>
                    <input class="form-control other_input mt-4" type="text" name="{{ $id ?? 'users' }}[0][reasons][{{ isset($name) ? $name : 'team' }}_other][]"  value="" style="display:none" placeholder="Please add other reason" />
                </div>
            </div>
            <div class="col-md-3 btn-option-child">
                <a class="btn btn-default col-md-2 add-child-select" href="javascript:void(0)">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
            {{-- <span id="warning_contractor_option_0" class="warning-text" style="color:red;margin-left: 315px;margin-bottom: 15px;margin-top:-10px;display: none;">This option is selected, Please choose another one! </span> --}}
        </div>
        {{-- edit --}}
    @else
        @php
            $arr_data_decode = $selected;
            $count = count($arr_data_decode) - 1;// for check with key
        @endphp
        @foreach($arr_data_decode as $key => $value)
            <div class="row {{ isset($name) ? $name : 'team' }}-child-select">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ $title ?? '' }}:</label>
                <div class="col-md-3">
                    <div class="form-group mb-4">
                        <input type="hidden" class="{{ isset($name) ? $name : 'team' }}-form-select-option" value="{{$key}}">
                        <select class="form-control {{ isset($name) ? $name : 'team' }}-select" name="{{ $id ?? '' }}[{{$index}}][reasons][{{ isset($name) ? $name : 'team' }}][]">
                            <option value="0">------ Please select an option -------</option>
                            @foreach($dropdown_list as $option)
                                <option value="{{$option->id}}" {{$option->id == $value[$name] ? 'selected':''}}>{{$option->description }}</option>
                            @endforeach
                        </select>
                        <input class="form-control other_input mt-4" type="text" name="{{ $id ?? 'users' }}[{{$index}}][reasons][{{ isset($name_other) ? $name_other : 'team' }}][]"  value="{{ $value[$name_other] ?? null }}" style="display:none" placeholder="Please add other reason" />
                    </div>
                </div>
                <div class="col-md-1 btn-option-child">
                @if($key == 0 && $key < $count)
                    <!-- first but not last -->
                        <a class="btn btn-default col-md-2 remove-child-select" href="javascript:void(0)">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </a>
                @elseif($key == 0 && $key == $count)
                    <!-- first but last -->
                        <a class="btn btn-default col-md-2 add-child-select" href="javascript:void(0)">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                @elseif($key < $max_option && $key < $count)
                    <!-- middle can add/minus -->
                        <a class="btn btn-default col-md-2 remove-child-select" href="javascript:void(0)">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </a>
                @elseif($key < $max_option && $key = $count)
                    <!-- last but can add more -->
                        <a class="btn btn-default col-md-2 remove-child-select" href="javascript:void(0)">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </a>
                        <a class="btn btn-default col-md-2 add-child-select" href="javascript:void(0)">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                @else
                    <!-- last -->
                        <a class="btn btn-default col-md-2 remove-child-select" href="javascript:void(0)">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>

@push('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            // click remove
            $('body').on('click', '.{!! isset($name) ? $name : 'team' !!}-child-select .remove-child-select', function () {
                var max_option = $('#{!! isset($name) ? $name : 'team' !!}_max_option').val();
                var index = $(this).closest('.{!! isset($name) ? $name : 'team' !!}-child-select').find('.{!! isset($name) ? $name : 'team' !!}-form-select-option').val();
                var total = $(this).closest('.{!! isset($name) ? $name : 'team' !!}-parent-option').find('.{!! isset($name) ? $name : 'team' !!}-form-select-option').last().val();
                var html = '';
                var length = $(this).closest('.{!! isset($name) ? $name : 'team' !!}-parent-option').find('.{!! isset($name) ? $name : 'team' !!}-form-select-option').length;
                $(this).closest('.{!! isset($name) ? $name : 'team' !!}-parent-option').find('.{!! isset($name) ? $name : 'team' !!}-form-select-option').each(function (k, v) {
                    if (k > index) {
                        $(this).val((k - 1));
                        // minus or minus and plus
                        if (k < max_option && k < total) {
                            //middle then only have minus btn
                            html = '<a class="btn btn-default col-md-2 remove-child-select" href="javascript:void(0)">\n' +
                                '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                                '                        </a>';

                        } else if (k < max_option && k == total) {
                            //last and not bigger than total
                            html = '<a class="btn btn-default col-md-2 remove-child-select" href="javascript:void(0)">\n' +
                                '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                                '                        </a>\n' +
                                '                        <a class="btn btn-default col-md-2 add-child-select" href="javascript:void(0)">\n' +
                                '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                                '                        </a>';
                        } else {
                            //last then have both btn
                            html = '<a class="btn btn-default col-md-2 remove-child-select" href="javascript:void(0)">\n' +
                                '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                                '                        </a>';
                        }
                        $(this).closest('.{!! isset($name) ? $name : 'team' !!}-child-select').find('.btn-option-child').html(html);
                    }
                    if (k == (length - 2) && index == total) { // above the elemen being delete
                        html = '<a class="btn btn-default col-md-2 remove-child-select" href="javascript:void(0)">\n' +
                            '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                            '                        </a>\n' +
                            '                        <a class="btn btn-default col-md-2 add-child-select" href="javascript:void(0)">\n' +
                            '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                            '                        </a>';
                        $(this).closest('.{!! isset($name) ? $name : 'team' !!}-child-select').find('.btn-option-child').html(html);
                    }
                })
                //if there are 2 two more, then first option only have plus icon
                if (total == 1) {
                    html = '<a class="btn btn-default col-md-2 add-child-select" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                    $(this).closest('.{!! isset($name) ? $name : 'team' !!}-parent-option').find('.{!! isset($name) ? $name : 'team' !!}-form-select-option').first().closest('.{!! isset($name) ? $name : 'team' !!}-child-select').find('.btn-option-child').html(html);
                }
                $(this).closest('.{!! isset($name) ? $name : 'team' !!}-child-select').remove();

            });
            // click add
            $('body').on('click', '.{!! isset($name) ? $name : 'team' !!}-child-select .add-child-select', function () {
                var max_option = $('#{!! isset($name) ? $name : 'team' !!}_max_option').val();
                var index = parseInt($(this).closest('.{!! isset($name) ? $name : 'team' !!}-child-select').find('.{!! isset($name) ? $name : 'team' !!}-form-select-option').val());
                var total = $(this).closest('.{!! isset($name) ? $name : 'team' !!}-child-select').find('.{!! isset($name) ? $name : 'team' !!}-form-select-option').last().val();
                var html = '';
                var append = $(this).closest('.{!! isset($name) ? $name : 'team' !!}-child-select').clone();
                $(append).find('option:selected').removeAttr('selected');
                $(append).find('.primary').removeAttr('checked');
                $(append).find('.other_input').val('');
                $(append).find('.other_input').hide();
                $(append).find('.{!! isset($name) ? $name : 'team' !!}-form-select-option').val(index + 1);

                // not last then both
                // console.log(index, total, max_option);
                if (index < (max_option - 1)) {
                    html = '<a class="btn btn-default col-md-2 remove-child-select" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                        '                        </a>\n' +
                        '                        <a class="btn btn-default col-md-2 add-child-select" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                } else {
                    //last then only minus
                    html = '<a class="btn btn-default col-md-2 remove-child-select" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                }
                $(append).find('.btn-option-child').html(html);
                $(this).closest('.{!! isset($name) ? $name : 'team' !!}-child-select').parent().append($(append));
                if (index == 0) { // case plus first select need to add minus button
                    html = '<a class="btn btn-default col-md-2 remove-child-select" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                    $(this).closest('.{!! isset($name) ? $name : 'team' !!}-child-select').find('.btn-option-child').html(html);
                }
                //remove plus at old option
                $(this).closest('.{!! isset($name) ? $name : 'team' !!}-child-select').find('.btn-option-child').find('.add-child-select').remove();
            });

            // display, hide reason other
            $(document).on('change','.{{ isset($name) ? $name : 'team' }}-select', function () {
                if ($(this).find(":selected").text() == 'Other') {
                    $(this).closest('.row').find('.other_input').show();
                } else {
                    $(this).closest('.row').find('.other_input').hide();
                }
                //validate
                if (!$(this).val()) {
                    $(this).closest('.row').find('span strong').html('This field can not be empty.');
                    $(this).addClass('is-invalid');
                } else {
                    $(this).closest('.row').find('span strong').html('');
                    $(this).removeClass('is-invalid');
                }
            });
            $('.{{ isset($name) ? $name : 'team' }}-select').trigger('change');
        });
    </script>
@endpush


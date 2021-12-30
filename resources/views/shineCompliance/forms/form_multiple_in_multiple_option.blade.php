@php
    isset($max_option) ? $max_option = $max_option : $max_option = 9 ;
@endphp
<div class="involved-persons-parent-option mt-3">
    <input type="hidden" id="involved_person_title_option" value="{{ ($title) ? $title : '' }}">

    <input type="hidden" id="involved_person_max_option" value="{{ $max_option }}">
    <!-- if have data then display all data else display an empty option -->
    {{-- add --}}

    @if(empty($selected))
        <div class="involved-persons-select">
            <div class="row {{ isset($class) ? $class : '' }}">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ ($title) ? $title." (1)" : '' }}</label>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="hidden" class="involved-persons-select-option" value="0">
                        <select class="form-control select-incident-report-person" id="involved_person_0" name="{{ isset($id) ? $id : 'users' }}[0][person]">
                            <option value="0">------ Please select an option -------</option>
                            @if(isset($non_user))
                                <option value="-1" data-option="-1" {{ isset($value) ? (-1 == $value ? 'selected' : '' ) : ''}}>Non-shine User</option>
                            @endif
                            @foreach($dropdown_list as $option)
                                <option value="{{$option->id}}" >{{$option->{$value_get} }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 btn-option">
                    <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group col-md-3 involved_insert_form">
                    <input type="{{$type ?? 'text'}}" class="form-control involved_insert_form_input" id="{{ isset($id) ? $id : '' }}_insert" name="{{ isset($id) ? $id : 'users' }}[0][name]"
                           value="{{ isset($data) ? $data : old($name) }}" placeholder="Full Name..." {{ $required_html ?? '' }}>
                </div>
            </div>
            <div class="child-dropdown-elements">
                @if(count($child_dropdown))
                    @foreach($child_dropdown as $child)
                        @include('shineCompliance.forms.form_multiple_child_option',[
                            'title' => $child['title'],
                            'data' => '',
                            'id' => isset($id) ? $id : 'users',
                            'name' => $child['name'],
                            'dropdown_list' => $child['data']
                        ])
                    @endforeach
                @endif
            </div>
        </div>
        {{-- edit --}}
    @else
        @php
            $arr_data_decode = $selected;
            $count = count($arr_data_decode) -1;// for check with key
            $index = 0;
        @endphp
        @foreach($arr_data_decode as $key => $value)
            <div class="involved-persons-select">
                <div class="row">
                    <label class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ ($title) ? $title." (". ($index+1) .")" : '' }}</label>
                    <div class="col-md-3">
                        <div class="form-group mb-4">
                            <input type="hidden" class="involved-persons-select-option" value="{{ $index }}">
                            <select class="form-control select-incident-report-person" name="{{ isset($id) ? $id : 'users' }}[{{$index}}][person]" id="{{"involved_person_".$index}}">
                                <option value="0">------ Please select an option -------</option>
                                @if(isset($non_user))
                                    <option value="-1" data-option="-1" {{ isset($value['user_id']) ? (-1 == $value['user_id'] ? 'selected' : '' ) : ''}}>Non-shine User</option>
                                @endif
                                @foreach($dropdown_list as $option)
                                    <option value="{{$option->id}}" {{$option->id == $value['user_id'] ? 'selected':''}}>{{$option->{$value_get} }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 btn-option">
                    @if($index == 0 && $key < $count)
                        <!-- first but not last -->
                            <a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </a>
                    @elseif($index == 0 && $key == $count)
                        <!-- first but last -->
                            <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                    @elseif($index < $max_option && $key < $count)
                        <!-- middle can add/minus -->
                            <a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </a>
                    @elseif($index < $max_option && $key = $count)
                        <!-- last but can add more -->
                            <a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </a>
                            <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                    @else
                        <!-- last -->
                            <a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="form-group col-md-3 involved_insert_form">
                        <input type="{{$type ?? 'text'}}" class="form-control involved_insert_form_input" name="{{ isset($id) ? $id : 'users' }}[{{$index}}][name]"
                               value="{{ isset($value['non_user']) ? $value['non_user'] : old($name) }}" placeholder="Full Name...">
                    </div>
                </div>
                <div class="child-dropdown-elements">
                    @if(count($value['injury_type']))
                        @include('shineCompliance.forms.form_multiple_child_option',[
                            'title' => INCIDENT_REPORT_INJURY_TYPE_TITLE,
                            'selected' => $value['injury_type'],
                            'id' => isset($id) ? $id : 'users',
                            'name' => 'injury_type',
                            'name_other' => 'injury_type_other',
                            'index' => $index,
                            'dropdown_list' => $injury_types
                        ])
                    @endif
                    @if(count($value['part_of_body_affected']))
                        @include('shineCompliance.forms.form_multiple_child_option',[
                            'title' => INCIDENT_REPORT_PART_OF_BODY_AFFECTED_TITLE,
                            'selected' => $value['part_of_body_affected'],
                            'id' => isset($id) ? $id : 'users',
                            'name' => 'part_of_body_affected',
                            'name_other' => 'part_of_body_affected_other',
                            'index' => $index,
                            'dropdown_list' => $part_of_body_affected_types
                        ])
                    @endif
                    @if(count($value['apparent_cause']))
                        @include('shineCompliance.forms.form_multiple_child_option',[
                            'title' => INCIDENT_REPORT_APPARENT_CAUSE_TITLE,
                            'selected' => $value['apparent_cause'],
                            'id' => isset($id) ? $id : 'users',
                            'name' => 'apparent_cause',
                            'name_other' => 'apparent_cause_other',
                            'index' => $index,
                            'dropdown_list' => $apparent_cause_types
                        ])
                    @endif
                </div>
            </div>
            @php
                $index++;
            @endphp
        @endforeach
    @endif
</div>

@push('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            // click remove parent select
            $('body').on('click', '.involved-persons-select .btn-minus', function () {
                var max_option = $('#involved_person_max_option').val();
                var index = $(this).closest('.involved-persons-select').find('.involved-persons-select-option').val();
                var total = $('.involved-persons-select-option').last().val();
                var html = '';
                var length = $('.involved-persons-select-option').length;
                $('.involved-persons-select-option').each(function (k, v) {
                    if (k > index) {
                        var label_name = $('#involved_person_title_option').val() + " (" + k + ')';
                        $(this).closest('.involved-persons-select').find('label:first').text(label_name);
                        $(this).val((k - 1));
                        // minus or minus and plus
                        if (k < max_option && k < total) {
                            //middle then only have minus btn
                            html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                                '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                                '                        </a>';

                        } else if (k < max_option && k == total) {
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
                        $(this).closest('.involved-persons-select').find('.btn-option').html(html);

                    }
                    if (k == (length - 2) && index == total) { // above the elemen being delete
                        html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                            '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                            '                        </a>\n' +
                            '                        <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">\n' +
                            '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                            '                        </a>';
                        $(this).closest('.involved-persons-select').find('.btn-option').html(html);
                    }
                })
                $(this).closest('.involved-persons-select').remove();
                //if there are 2 two more, then first option only have plus icon
                if (total == 1) {
                    html = '<a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                    $('.involved-persons-select-option').first().closest('.involved-persons-select').find('.btn-option').html(html);
                }

            });
            // click add parent select
            $('body').on('click', '.involved-persons-select .btn-plus', function () {
                var max_option = $('#involved_person_max_option').val();
                var index = parseInt($(this).closest('.involved-persons-select').find('.involved-persons-select-option').val());
                var total = $('.involved-persons-select-option').last().val();
                var html = '';
                var append = $(this).closest('.involved-persons-select').clone();
                var label_name = $('#involved_person_title_option').val() + " (" + (index + 2) + ')';
                $(append).find('option:selected').removeAttr('selected');
                $(append).find('label:first').text(label_name);
                $(append).find('.involved-persons-select-option').val(index + 1);
                $(append).find('.select-incident-report-person').attr("id", "involved_person_" + (index + 1));
                $(append).find('.select-incident-report-person').attr("name", $(append).find('.select-incident-report-person').attr("name").replace(/\d+/, new String((index + 1))));
                $(append).find('.involved_insert_form_input').attr("name", $(append).find('.involved_insert_form_input').attr("name").replace(/\d+/, new String((index + 1))));
                $(append).find('.involved_insert_form_input').val(' ');
                $(append).find('.involved_insert_form').hide();
                // remove added child elements when add new parent element
                $(append).find('.child-dropdown-elements > div').each(function (k, v) {
                    $(this).find('option:selected').removeAttr('selected');
                    $(this).find('.other_input').val('');
                    $(this).find('.other_input').hide();
                    var html_button = '<a class="btn btn-default col-md-2 add-child-select" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-plus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                    $(this).find('.row').first().find('.btn-option-child').html(html_button);
                    $(this).find('.row:not(:first)').remove();
                    $(this).find('select').attr("name", $(this).find('select').attr("name").replace(/\d+/, new String((index + 1))));
                    $(this).find('.other_input').attr("name", $(this).find('.other_input').attr("name").replace(/\d+/, new String((index + 1))));
                });

                // not last then both
                // console.log(index, total, max_option);
                if (index < (max_option - 1)) {
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
                $('.involved-persons-parent-option').append($(append));
                if (index == 0) { // case plus first select need to add minus button
                    html = '<a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">\n' +
                        '                            <i class="fa fa-minus" aria-hidden="true"></i>\n' +
                        '                        </a>';
                    $(this).closest('.involved-persons-select').find('.btn-option').html(html);
                }
                //remove plus at old option
                $(this).closest('.involved-persons-select').find('.btn-option').find('.btn-plus').remove();
            });
        });
    </script>
@endpush


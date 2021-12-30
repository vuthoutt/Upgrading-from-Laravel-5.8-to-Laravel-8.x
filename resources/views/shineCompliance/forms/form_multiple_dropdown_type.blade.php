@php
    isset($max_option) ? $max_option = $max_option : $max_option = 9 ;
@endphp
<div class="parent-option mt-3">
    <input type="hidden" id="title_option" value="{{ ($title) ? $title : '' }}">

    <input type="hidden" id="max_option" value="{{ $max_option }}">
    <!-- if have data then display all data else display an empty option -->
    {{-- add --}}

    @if(empty($selected))
        <div class="row col-md-12 row-select {{ isset($class) ? $class : '' }}">
            <div class="row form-summary">
                <div class="form-group col-md-6">
                    <input type="hidden" class="form-select-option" value="0">
                    <select class="form-control input-summary project-select-contractor" id="contractor_option_0" name="{{ isset($name) ? $name : 'team' }}[]">
                        @foreach($dropdown_list as $option)
                            <option value="{{$option['id']}}" >{{$option["$value_get"] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2 btn-option" style="padding: 0">
                <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">
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
            <div class="row row-select">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ ($title) ? $title." ".($key+1) : '' }}</label>
                <div class="col-md-3">
                    <div class="form-group mb-4">
                        <input type="hidden" class="form-select-option" value="{{$key}}">
                        <select class="form-control project-select-contractor" name="{{ isset($name) ? $name : 'team' }}[]" id="{{"contractor_option_".$key}}">
                            <option value="">------ Please select an option -------</option>
                            @foreach($dropdown_list as $option)
                                <option value="{{$option->id}}" {{$option->id == $value ? 'selected':''}}>{{$option->{$value_get} }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1 btn-option">
                @if($key == 0 && $key < $count)
                    <!-- first but not last -->
                        <a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </a>
                @elseif($key == 0 && $key == $count)
                    <!-- first but last -->
                        <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                @elseif($key < $max_option && $key < $count)
                    <!-- middle can add/minus -->
                        <a class="btn btn-default col-md-2 btn-minus" href="javascript:void(0)">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </a>
                @elseif($key < $max_option && $key = $count)
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
                <div class="col-md-3 checked-contractors">
                    @if(isset($edit_project))
                        <label class="switch">
                            <input type="checkbox" name="checked_contractors[]" class="primary" value="{{$value}}" id="{{"selected_contractor_option_".$key}}" {{ in_array($value,$checked_contractors) ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    @endif
                </div>
                {{-- <span id="{{"warning_contractor_option_".$key}}" class="warning-text" style="color:red;margin-left: 315px;margin-bottom: 15px;margin-top:-15px">This option is selected, Please choose another one! </span> --}}
            </div>
        @endforeach
    @endif
</div>


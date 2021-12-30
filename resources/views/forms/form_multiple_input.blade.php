@php
    isset($max_option) ? $max_option = $max_option : $max_option = 9 ;
@endphp
<div class="parent-option mt-3">
    <input type="hidden" id="title_option" value="{{ ($title) ? $title : '' }}">

    <input type="hidden" id="max_option" value="{{ $max_option }}">
    <!-- if have data then display all data else display an empty option -->
    {{-- add --}}

    @if(empty($email))
            <div class="row row-select {{ isset($class) ? $class : '' }}">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold " >{{ ($title) }}:</label>
                <div class="col-md-5">
                    <div class="form-group">
                        <input type="hidden" class="form-select-option" value="0">
                        <input class="form-control email_cc project-select-contractor" name="{{ isset($name) ? $name : 'team' }}[]" >
                        <span class="invalid-feedback" role="alert">
                            <strong>
                            @error($name)
                                {{ $message }}
                                @enderror
                            </strong>
                        </span>
                    </div>
                </div>

                <div class="col-md-3 btn-option">
                    <a class="btn btn-default col-md-2 btn-plus" href="javascript:void(0)">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </div>
                {{-- <span id="warning_contractor_option_0" class="warning-text" style="color:red;margin-left: 315px;margin-bottom: 15px;margin-top:-10px;display: none;">This option is selected, Please choose another one! </span> --}}
            </div>
    {{-- edit --}}
    @else
        @php
            $arr_data_decode = $email;
            $count = count($arr_data_decode) - 1;// for check with key
        @endphp
        @foreach($arr_data_decode as $key => $value)
            <div class="row row-select">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ ($title) ?? '' }}</label>
                <div class="col-md-5">
                    <div class="form-group ">
                        <input type="hidden" class="form-select-option" value="{{$key}}">
                        <input class="form-control email_cc project-select-contractor" value="{{ $value }}" name="{{ isset($name) ? $name : 'team' }}[]">
                        <span class="invalid-feedback" role="alert">
                            <strong>
                            @error($name)
                                {{ $message }}
                                @enderror
                            </strong>
                        </span>
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
                {{-- <span id="{{"warning_contractor_option_".$key}}" class="warning-text" style="color:red;margin-left: 315px;margin-bottom: 15px;margin-top:-15px">This option is selected, Please choose another one! </span> --}}
            </div>
        @endforeach
    @endif
</div>


<div class="row register-form">
    <label for="email" class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold">
    {{ isset($title) ? $title : '' }}
    @if(isset($required))
        <span style="color: red;">*</span>
    @endif
    </label>
    <div class="col-md-3">
        <div class="form-group ">
            <input type="hidden" class="form-select-option-addtional-date" value="{{$index}}">
            <input class="form-control additional-date-picker @error($name) is-invalid @enderror" name="{{ isset($name) ? $name : ''}}" value="{{ isset($data) ? $data : '' }}" id="{{ isset($id) ? $id : $name}}" width="276">
        </div>
            @error($name)
            <span class="invalid-feedback" role="alert" style="display: block">
            <strong>{{ $message }}</strong>
            </span>
            @enderror
    </div>
    <div class="col-md-2 btn-option">
        @if(isset($index) && $index == 0)
            @if($count == 0)
                <a class="btn btn-default btn-plus" href="javascript:void(0)">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            @endif
        @else
            @if($index == 0 && $index < $count)
            <!-- first but not last -->
                <a class="btn btn-default btn-minus" href="javascript:void(0)">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </a>
            @elseif($index == 0 && $index == $count)
            <!-- first but last -->
                <a class="btn btn-default btn-plus" href="javascript:void(0)">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            @elseif($index < $max_option && $index < $count)
            <!-- middle can add/minus -->
                <a class="btn btn-default btn-minus" href="javascript:void(0)">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </a>
            @elseif($index < $max_option && $index = $count)
            <!-- last but can add more -->
                <a class="btn btn-default btn-minus" href="javascript:void(0)">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </a>
                <a class="btn btn-default btn-plus" href="javascript:void(0)">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            @else
            <!-- last -->
                <a class="btn btn-default btn-minus" href="javascript:void(0)">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </a>
            @endif
        @endif
    </div>
</div>

@push('javascript')

    <script type="text/javascript">
    $('#<?= isset($id) ? $id : $name ?>').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy',
        todayHighlight: true
    });
    </script>
@endpush

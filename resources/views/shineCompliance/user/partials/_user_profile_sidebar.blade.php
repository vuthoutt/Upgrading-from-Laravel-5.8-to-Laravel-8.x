<div class="row">
    <div class="full-width button-top-left">
        <div class="form-button-search" >
            @if(\Auth::user()->role == SUPER_USER_ROLE || (\Auth::user()->id == $data->id))
                <a href="{{ route('shineCompliance.user.get-edit-profile',['user_id' => $data->id ?? '']) }}" style="text-decoration: none">
                    <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                        <strong>{{ __('Edit') }}</strong>
                    </button>
                </a>
                <a href="{{ route('shineCompliance.user.get_change_password',['user_id' => $data->id ?? '']) }}" style="text-decoration: none">
                    <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                        <strong>{{ __('Change Password') }}</strong>
                    </button>
                </a>
            @endif
            @if(\Auth::user()->role == SUPER_USER_ROLE)
                <a href="{{ route('shineCompliance.user.lock',['id' => $data->id]) }}" style="text-decoration: none">
                    <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                        @if($data->is_locked == USER_LOCKED)
                            <strong>{{ __('Unlock User') }}</strong>
                        @else
                            <strong>{{ __('Lock User') }}</strong>
                        @endif
                    </button>
                </a>
            @endif
        </div>
    </div>
</div>

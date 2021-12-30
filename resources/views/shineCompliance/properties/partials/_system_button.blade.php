@php
    $decommission = $decommission ?? 0;
@endphp
<div class="row mr-bt-top">
    <div class="col-md-12" style="padding: 0px 25px 0px 0px">
        <div class="form-button-left" >
            <a href="{{ $backRoute }}" style="text-decoration: none">
                <button type="submit" class="btn shine-compliance-button fs-8pt">
                    <strong>{{ __('Back') }}</strong>
                </button>
            </a>
        </div>
        @if(isset($can_update) and ($can_update ==  false))
        @else
            <div class="form-button-search" >
                <a href="{{ $editRoute ?? '#' }}" style="text-decoration: none">
                    <button type="submit" class="btn light_grey_gradient_button fs-8pt" data-toggle="modal" data-target="{{ $editRoute ?? '' }}" aria-hidden="true" >
                        <strong>{{ __('Edit') }}</strong>
                    </button>
                </a>

                @if($decommission == 1)
                    <a style="text-decoration: none" href="{{ $route_recommission ?? '#' }}">
                        <button type="button" class="btn light_grey_gradient_button fs-8pt" id="filter1" style="margin-right: 0px"
                                data-toggle="modal" data-target="{{ $recommission_target ?? '' }}" aria-hidden="true">
                            <strong>{{ __('Recommission') }}</strong>
                        </button>
                    </a>
                @else
                    <a style="text-decoration: none" href="{{ $route_recommission ?? '#' }}">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt" id="filter" style="margin-right: 0px"
                            data-toggle="modal" data-target="{{$data_target ?? ''}}">
                        <strong>{{ __('Decommission') }}</strong>
                    </button>
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

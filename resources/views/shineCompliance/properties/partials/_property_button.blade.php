@php
    $decommission = $decommission ?? 0;
@endphp
<div class="row mr-bt-top">
    <div class="col-md-12 button-top-left" >
        <div class="form-button-left" >
            <a href="{{ $backRoute }}" style="text-decoration: none">
                <button type="submit" class="btn shine-compliance-button fs-8pt">
                    <strong>{{ __('Back') }}</strong>
                </button>
            </a>
        </div>
        @if(isset($can_update) and ($can_update ==  false))
        @else
            <div class="form-button-search" style="display: inline!important;">
                @if(isset($is_locked) and $is_locked)
                    @if(isset($asbestos))
                    <div class="spanWarningSurveying col-md-12">
                            <strong><em>Item is view only surveying is in progress</em></strong>
                    </div>
                    @endif
                @else
                    <a href="{{ $editRoute ?? '#' }}" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt" data-toggle="modal" data-target="{{ $editRoute ?? '' }}" aria-hidden="true" >
                            <strong>{{ __('Edit') }}</strong>
                        </button>
                    </a>
                    @if($decommission == 1)
                        <a style="text-decoration: none" href="{{ $route_decommission ?? '' }}">
                            <button type="button" class="btn light_grey_gradient_button fs-8pt" id="filter" style="margin-right: 0px" data-toggle="modal" data-target="{{ $recommission_target ?? ''}}">
                                <strong>{{ __('Recommission') }}</strong>
                            </button>
                        </a>
                    @else
                        <button type="button" class="btn light_grey_gradient_button fs-8pt" id="filter" style="margin-right: 0px"
                                data-toggle="modal" data-target="{{$data_target ?? ''}}">
                            <strong>{{ __('Decommission') }}</strong>
                        </button>
                    @endif
                @endif

            </div>
        @endif
    </div>
</div>

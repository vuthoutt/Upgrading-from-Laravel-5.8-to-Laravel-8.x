@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav', ['color' => 'red'])

<div class="container prism-content pad-up">
    <div class="row">
        <h3 style="margin: 0">1 Broadley Street</h3>
    </div>
    <div class="main-content mar-up">
        <div class="row">
            <div class="full-width">
                <div class="form-button-left" >
                    <a href="{{ route('shineCompliance.property') }}" style="text-decoration: none">
                        <button type="submit" class="btn shine-compliance-button fs-8pt">
                            <strong>{{ __('Back') }}</strong>
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            @include('shineCompliance.properties.partials._property_sidebar')
            <div class="column-right">
                <div class="card-data mar-up">
                    <div style="padding-left:15px;width: 100%; height: 660px">
                        <iframe src="/cadviewer/" style="height: 100%; width: 100%; border: none"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

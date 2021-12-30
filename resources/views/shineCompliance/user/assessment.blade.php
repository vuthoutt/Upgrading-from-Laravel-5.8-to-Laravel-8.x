@extends('shineCompliance.layouts.app')

@section('content')
        @include('shineCompliance.partials.nav',['breadCrumb' => 'assessment_user', 'color' => 'red'])

<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $data->full_name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        <div class="row mt-4">
            @include('shineCompliance.user.partials._data_centre_sidebar')
            <div class="col-md-9 pl-0" style="padding: 0">
                <div id="fire" class="container" style="padding-left: 0; padding-right:0;">
                    @include('shineCompliance.tables.assessment_datacentre', [
                        'title' => 'Fire Risk Assessment Notifications',
                        'tableId' => 'fire-assessment',
                        'collapsed' => false,
                        'plus_link' => false,
                        'data' => $fires,
                        'order_table' => 'survey-table'
                        ])

                    @if(env('WATER_MODULE'))
                        @include('shineCompliance.tables.assessment_datacentre', [
                            'title' => 'Water Risk Assessment Notifications',
                            'tableId' => 'water-assessment',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => $waters,
                            'order_table' => 'survey-table'
                            ])
                    @endif

                    @include('shineCompliance.tables.assessment_datacentre', [
                        'title' => 'Health And Safety Assessment Notifications',
                        'tableId' => 'hs-assessment',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $hs,
                        'order_table' => 'survey-table'
                        ])

                    @include('shineCompliance.tables.assessment_datacentre', [
                        'title' => 'Pre-Planned Maintenance Assessment Notifications',
                        'tableId' => 'maintence-assessment',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => [],
                        'order_table' => 'survey-table'
                        ])


                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_equipment_detail', 'color' => 'orange', 'data'=> $equipment])
<div class="container-cus prism-content pl-0">
    <h3 class="title-row">{{ $equipment->name ?? '' }}</h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills {{ ($equipment->assess_id == 0) ? \CommonHelpers::getNavItemColor('red') : \CommonHelpers::getNavItemColor('orange') }}" style="margin-left: -7px !important;">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#details"><strong>Details</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="model_tab" data-toggle="tab" href="#Model"><strong>Model</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="construction_tab" data-toggle="tab" href="#Construction"><strong>Construction</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="cleaning_tab" data-toggle="tab" href="#Cleaning"><strong>Cleaning</strong></a>
            </li>
            <li class="nav-item" id="temp_tab">
                <a class="nav-link" data-toggle="tab" href="#temp_ph"><strong>Temperatures & PH</strong></a>
            </li>
            <li class="nav-item" id="sampling_tab">
                <a class="nav-link" data-toggle="tab" href="#sampling"><strong>Sampling</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Photography"><strong>Photography</strong></a>
            </li>
        </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div id="details" class="container tab-pane active pl-0">
                    @include('shineCompliance.equipments.tab_detail')
                </div>
                <div id="Model" class="container tab-pane fade pl-0">
                    @include('shineCompliance.equipments.tab_model')
                </div>
                <div id="Construction" class="container tab-pane fade pl-0">
                    @include('shineCompliance.equipments.tab_construction')
                </div>
                <div id="Cleaning" class="container tab-pane fade pl-0">
                    @include('shineCompliance.equipments.tab_cleaning')
                </div>
                <div id="temp_ph" class="container tab-pane fade pl-0">
                    @include('shineCompliance.equipments.tab_temp')
                </div>
                <div id="sampling" class="container tab-pane fade pl-0">
                    @include('shineCompliance.equipments.tab_sampling')
                </div>
                <div id="Photography" class="container tab-pane fade">
                    @include('shineCompliance.equipments.tab_photography')
                </div>
            </div>
            <div class="col-md-6 mt-4 pl-0">
                @if($equipment->decommissioned == EQUIPMENT_UNDECOMMISSION)
                    @if(($equipment->assess_id != 0) and isset($equipment->assessment->is_locked) and ($equipment->assessment->is_locked == 1))
                        <div class="spanWarningSurveying">
                            <strong><em>Equipment is view only because technical activity is complete</em></strong>
                        </div>
                    @else
                        <a href="{{ route('shineCompliance.equipment.get_edit_equipment', ['id' => $equipment->id ?? 1]) }}">
                            <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                                <strong>{{ __('Edit') }}</strong>
                            </button>
                        </a>
                        <a href="" data-toggle="modal" data-target="#decommission_hazard" style="text-decoration: none">
                            <button type="button" class="btn light_grey_gradient_button fs-8pt">
                                <strong>{{ __('Decommission') }}</strong>
                            </button>
                        </a>
                        @include('shineCompliance.modals.common_decommission_assessment',[
                            'color' => $equipment->assess_id > 0 ? 'orange' : 'red',
                            'modal_id' => 'decommission_hazard',
                            'header' => 'Decommission Equipment',
                            'reference' => $equipment->reference ?? '',
                            'url' => route('shineCompliance.assessment.decommission.equipment', ['id' => $equipment->id]),
                        ])
                    @endif
                @else
                    <a href="" data-toggle="modal" data-target="#recommission_hazard" style="text-decoration: none">
                        <button type="button" class="btn light_grey_gradient_button fs-8pt">
                            <strong>{{ __('Recommission') }}</strong>
                        </button>
                    </a>
                    @include('shineCompliance.modals.common_recommission_assessment',[
                                'color' => $equipment->assess_id > 0 ? 'orange' : 'red',
                                'modal_id' => 'recommission_hazard',
                                'header' => 'Recommission Equipment',
                                'reference' => $equipment->reference ?? '',
                                'url' => route('shineCompliance.assessment.recommission.equipment', ['id' => $equipment->id]),
                            ])
                @endif
            </div>
            @if (count($category))
                {{-- <div class="row" style="justify-content: flex-end; float: left;margin-top: 10px;padding-right: 10px;"> --}}
                <div class="row" style="justify-content: flex-end;">
                    <div class="pagination-left mar-up">
                        <nav aria-label="...">
                            {{ $category->appends(request()->except(['_', 'id']))->links('vendor.pagination.customize-pagination',['param' => 'id']) }}
                        </nav>
                    </div>
                </div>
            @endif
    </div>
@endsection
@push('javascript')

<script type="text/javascript">
$(document).ready(function(){
    // $("#type").change(function(){
        var type = {{ $equipment->type ?? 0 }};

        $.ajax({
            type: "GET",
            url: "{{ route('shineCompliance.equipment.ajax_equipment_template') }}",
            data: {type: type},
            cache: false,
            success: function (response) {
                if (response.status == 200) {
                    $('.equipment_section').hide();
                    actives = response.data;

                    actives.forEach(function(active) {
                        $('#' + active + '-form').show();
                    });
                    template_id = response.template_id;
                    // Miscellaneous Equipment template
                    if (template_id == 1) {
                        $('#temp_tab').hide();
                    } else {
                        $('#temp_tab').show();
                    }

                    // Show Sampling tab if Outlet templates
                    if (template_id == 4 || template_id == 5 || template_id == 6) {
                        $('#sampling_tab').show();
                    } else {
                        $('#sampling_tab').hide();
                    }

                    // Show Sampling tab if Outlet templates
                    // if (template_id == 10 || template_id == 12 || template_id == 13) {
                    //     $('#construction_tab').hide();
                    // } else {
                    //     $('#construction_tab').show();
                    // }
                    // Hide Cleaning tab
                    if (template_id == 10 || template_id == 8) {
                        $('#cleaning_tab').hide();
                    } else {
                        $('#cleaning_tab').show();
                    }

                    if (template_id == 8) {
                        $('#model_tab').hide();
                        $('#drain_valve-form').children('label').text('Drain Valve Fitted?');
                    } else {
                        $('#model_tab').show();
                        $('#drain_valve-form').children('label').text('Drain Valve:');
                    }

                } else {
                    $('#overlay').fadeOut();
                }
            }
        });
    // });
});
</script>
@endpush

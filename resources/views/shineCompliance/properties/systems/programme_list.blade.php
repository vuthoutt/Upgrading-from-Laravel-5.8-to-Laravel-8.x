@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'programmes_list', 'color' => 'red', 'data' => $system ?? ''])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $system->name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._programme_button_search',
        [   'backRoute' => route('shineCompliance.systems.list', $system->property_id ?? 0),
            'addRoute' => $can_add_new ? 'add-area-register' : false
        ])
        @include('shineCompliance.modals.add_programme',['property_id' => $system->property_id,
            'modal_id' => 'add-area-register','action' => '',
            'url' => route('shineCompliance.programme.post_add',['property_id' => $system->id])])
        <div class="row">
             @include('shineCompliance.properties.partials._property_aov_system_sidebar',['image' =>  asset(\ComplianceHelpers::getSystemFile($system_id, COMPLIANCE_SYSTEM_PHOTO)),
                    'route_document' => route('shineCompliance.system.document.list', ['id'=>$system_id, 'type'=> DOCUMENT_SYSTEM_TYPE])])

            <div class="list-data col-md-9 pl-0 pr-0" style="padding: 0">
                @if(count($programmes))
                <div class="card-data mar-up">
                        @foreach($programmes as $key => $programme)
                            <div class="card card-img card-img-deco col-md-4" style="padding:0;">
                                <a href="{{ route('shineCompliance.programme.detail', ['id' => $programme->id]) }}" >
                                    <img class="card-img-top unset-border" src="{{ asset(\ComplianceHelpers::getSystemFile($programme->id, COMPLIANCE_PROGRAMME_PHOTO)) }}" alt="Card image" height="300px">
                                <div class="card-body card-body-border card-padding" >
                                    <div class="property-detail-attribute mt-2">
                                        <strong class="str-color">{{ $programme->name ?? '' }}</strong>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6 fs-8pt">Shine:</label>
                                        <div class="col-md-6 fs-8pt">
                                            {{ $programme->reference ?? '' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6 fs-8pt">Date Inspected:</label>
                                        <div class="col-md-6 fs-8pt">
                                            {{ $programme->documentInspection->date ?? '' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6 fs-8pt">Next Inspection:</label>
                                        <div class="col-md-6 fs-8pt">
                                            {{ $programme->next_inspection_display ?? '' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6 fs-8pt">Days Remaining:</label>
                                        <div class="col-md-6 fs-8pt">
                                            @if(isset($programme->documentInspection))
                                                <span class="badge orange" id="risk-color" style="width: 30px;margin-right: 10px">
                                                    {{ $programme->days_remaining ?? 0 }}
                                                </span>
                                                <span>Days</span>
                                            @else
                                                <span class="badge red" id="risk-color" style="margin-right: 10px">
                                                    Missing
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            @if( ($key + 1)%3 == 0)
                                </div>
                                <div class="card-data mar-up">
                            @endif
                        @endforeach
                </div>
                <div class="pagination-right mar-up">
                    <nav aria-label="...">
                        {{ $programmes->links() }}
                    </nav>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
@endpush

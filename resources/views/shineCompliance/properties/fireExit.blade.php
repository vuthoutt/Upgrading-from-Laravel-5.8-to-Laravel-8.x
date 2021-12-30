@extends('shineCompliance.layouts.app')

@section('content')
    @if(isset($property->parents))
        @include('shineCompliance.partials.nav',['breadCrumb' => 'sub_register_fire_exit_and_assembly_list', 'color' => 'red', 'data' =>  $property])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'register_fire_exit_and_assembly_list', 'color' => 'red', 'data' =>  $property])
    @endif
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $property->name }}</h3>
    </div>
    <div class="main-content mar-up">
        @if(isset($property->parents))
            @include('shineCompliance.properties.partials._property_button_search',[
                'backRoute' => route('shineCompliance.property.property_detail',$property->parent_id ?? 0),
                'addRoute' => $can_add_new ? '#addFireAndAssembly' : false,
                'search_action' => route('shineCompliance.property.fireExit',$property->id ?? 0)
            ])
        @else
            @include('shineCompliance.properties.partials._property_button_search',[
                  'backRoute' => route('shineCompliance.property.property_detail',$property->id ?? 0),
                'addRoute' => $can_add_new ? '#addFireAndAssembly' : false,
                'search_action' => route('shineCompliance.property.fireExit',$property->id ?? 0)
            ])
        @endif
    @include('shineCompliance.modals.method_check_box',['color' => 'red','property_id' => $property_id])
        <div class="row">
            @include('shineCompliance.properties.partials._property_sidebar')

            <div class="list-data col-md-9 pl-0 pr-0" style="padding: 0" >
                    @if(count($data))
                    <div class="card-data mar-up">
                            @foreach($data as $key => $fire_assembly)
                                <div class="card card-img card-img-deco col-md-4" style="padding:0;">
                                    @if($fire_assembly->is_fire)
                                        <a href="{{ route('shineCompliance.assessment.get_fire_exit', ['id' => $fire_assembly->id]) }}" >
                                    @else
                                        <a href="{{ route('shineCompliance.assessment.get_assembly_point', ['id' => $fire_assembly->id]) }}" >
                                    @endif
                                        @if($fire_assembly->is_fire)
                                            <img class="card-img-top unset-border" src="{{ CommonHelpers::getFile($fire_assembly->id, FIRE_EXIT_PHOTO) }}" alt="Card image" height="300px">
                                        @else
                                            <img class="card-img-top unset-border" src="{{ CommonHelpers::getFile($fire_assembly->id, ASSEMBLY_POINT_PHOTO) }}" alt="Card image" height="300px">
                                        @endif
                                        <div class="card-body card-body-border card-padding" style="padding: 15px;">
                                            <div class="row property-detail-attribute mt-2">
                                                @if($fire_assembly->is_fire)
                                                    <div class="col-6 property-detail-attribute-label">Fire Exit Name:</div>
                                                @else
                                                    <div class="col-6 property-detail-attribute-label">Assembly Point Name:</div>
                                                @endif
                                                <div class="col-6">{{ $fire_assembly->name ?? '' }}</div>
                                            </div>
                                            <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Shine:</div>
                                                <div class="col-6">{{ $fire_assembly->reference ?? '' }}</div>
                                            </div>
                                            <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Status:</div>
                                                <div class="col-6"> {{ ($fire_assembly->decommissioned == 1) ? 'Decommissioned' : 'Live' }}</div>
                                            </div>
                                           <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Accessibility:</div>
                                                <div class="col-6"> {{ ($fire_assembly->accessibility == 1) ? 'Yes' : 'No' }}</div>
                                            </div>
                                           <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Inaccessible Reason:</div>
                                                <div class="col-6"> {{ $fire_assembly->reasonNotAccessible->description ?? '' }}</div>
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
{{--                         <nav aria-label="...">
                            {{ $data->links() }}
                        </nav> --}}
                    </div>
                    @endif
            </div>
        </div>
    </div>
</div>
@endsection

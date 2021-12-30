@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav', ['color' => 'red'])

<div class="container-cus prism-content pad-up" >
    <div class="row">
        <h3 class="title-row">Central Area Service Centre</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_search',['backRoute' => route('shineCompliance.property'),'addRoute' => route('shineCompliance.properties.add','property')])

        <div class="row">
            <div class="col-md-3 pl-0">
                <div  class="card-data mar-up">
                    <div style="width:100%;" >
                        <ul class="list-group">
                            <div class="list-group-img">
                                <img src="{{ asset('img/logo_com.png') }}"  width="100%" height="230px" alt="">
                            </div>
                            <div class="list-group-button">
                                <button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button>
                                <button class="list-group-btn" title="Download"><i class="fa fa-download fa-2x"></i></button>
                                <button class="list-group-btn"><i class="fa fa-cubes fa-2x"></i></button>
                                <button class="list-group-btn"><i class="fa fa-qrcode fa-2x"></i></button>
                            </div>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-danger list-group-details" >Details</a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-danger list-group-details" >Register</a>
                            <a href="#" class="list-group-item list-group-active list-group-item-action list-group-details border-unset" >Properties</a>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9 pl-0 pr-0" style="padding: 0" >
                <div  class="card-data mar-up">
                    <a href="{{ route('shineCompliance.property.detail', 1234) }}" class="card card-img card-img-deco col-md-4" style="padding:0">
                        <img class="card-img-top unset-border" src="{{ asset('img/item5.jpg') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding" >
                            <strong class="str-color">1 Broadley Street</strong>
                            <div class="row mt-2">
                                <label class="col-md-6"  >UPRN:</label>
                                <div class="col-md-6" >
                                    018004
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6" >Parent:</label>
                                <div class="col-md-6" >
                                    Church Street Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6">Estate Code:</label>
                                <div class="col-md-6">
                                    Church Street Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6" >Shine:</label>
                                <div class="col-md-6">
                                    PL2783
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('shineCompliance.property.detail', 1234) }}" class="card card-img card-img-deco col-md-4" style="padding:0">
                        <img class="card-img-top unset-border" style="object-fit:cover;" src="{{ asset('img/item2.png') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding" >
                            <strong class="str-color">1 Grendon Street</strong>
                            <div class="row mt-2">
                                <label class="col-md-6" >UPRN:</label>
                                <div class="col-md-6">
                                    038773
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6" >Parent:</label>
                                <div class="col-md-6" >
                                    1-12 Grendon Street
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6" >Estate Code:</label>
                                <div class="col-md-6">
                                    Lisson Green Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6" >Shine:</label>
                                <div class="col-md-6" >
                                    PL28007
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('shineCompliance.property.detail', 1234) }}" class="card card-img col-md-4" style="padding:0">
                        <img class="card-img-top unset-border" style="object-fit:cover;" src="{{ asset('img/item3.jpg') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding" >
                            <strong class="str-color">1 Paveley Street</strong>
                            <div class="row mt-2">
                                <label class="col-md-6">UPRN:</label>
                                <div class="col-md-6 ">
                                    046758
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6"  >Parent:</label>
                                <div class="col-md-6"  >
                                    Lisson Green Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6" >Estate Code:</label>
                                <div class="col-md-6" >
                                    Lisson Green Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6"  >Shine:</label>
                                <div class="col-md-6" >
                                    PL28095
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div  class="card-data mar-up">
                    <a href="{{ route('shineCompliance.property.detail', 1234) }}" class="card card-img col-md-4" style="padding:0">
                        <img class="card-img-top unset-border" style="object-fit:cover;" src="{{ asset('img/item4.jpg') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding" >
                            <strong class="str-color">1-6 Hester Court</strong>
                            <div class="row mt-2">
                                <label class="col-md-6"  >UPRN:</label>
                                <div class="col-md-6"  >
                                    046573
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6"  >Parent:</label>
                                <div class="col-md-6"  >
                                    Hester Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6" >Estate Code:</label>
                                <div class="col-md-6" >
                                    Hester Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6"  >Shine:</label>
                                <div class="col-md-6" >
                                    PL26019
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('shineCompliance.property.detail', 1234) }}" class="card card-img col-md-4" style="padding:0">
                        <img class="card-img-top unset-border" style="object-fit:cover;" src="{{ asset('img/item1.png') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding">
                            <strong class="str-color">12-16 Church Street</strong>
                            <div class="row mt-2">
                                <label class="col-md-6"  >UPRN:</label>
                                <div class="col-md-6"  >
                                    000414
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6"  >Parent:</label>
                                <div class="col-md-6"  >
                                    Church Street Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6" >Estate Code:</label>
                                <div class="col-md-6" >
                                    Church Street Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6"  >Shine:</label>
                                <div class="col-md-6" >
                                    PL25940
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('shineCompliance.property.detail', 1234) }}" class="card card-img col-md-4" style="padding:0">
                        <img class="card-img-top unset-border" style="object-fit:cover;" src="{{ asset('img/item6.jpg') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding" >
                            <strong class="str-color">13 Mallory Street</strong>
                            <div class="row mt-2">
                                <label class="col-md-6"  >UPRN:</label>
                                <div class="col-md-6 "  >
                                    017404
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6"  >Parent:</label>
                                <div class="col-md-6"  >
                                    Lisson Green Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6" >Estate Code:</label>
                                <div class="col-md-6" >
                                    Lisson Green Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6 " >Shine:</label>
                                <div class="col-md-6" >
                                    PL31499
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div  class="card-data mar-up">
                    <a href="{{ route('shineCompliance.property.detail', 1234) }}" class="card card-img col-md-4" style="padding:0">
                        <img class="card-img-top unset-border" style="object-fit:cover;" src="{{ asset('img/item7.jpg') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding" >
                            <strong class="str-color mt-2">19 Balcombe Street</strong>
                            <div class="row mt-2">
                                <label class="col-md-6"  >UPRN:</label>
                                <div class="col-md-6"  >
                                    000199
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6"  >Parent:</label>
                                <div class="col-md-6"  >
                                    Balcombe Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6" >Estate Code:</label>
                                <div class="col-md-6" >
                                    Balcombe Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6"  >Shine:</label>
                                <div class="col-md-6" >
                                    PL25755
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('shineCompliance.property.detail', 1234) }}" class="card card-img col-md-4" style="padding: 0">
                        <img class="card-img-top unset-border" style="object-fit:cover;" src="{{ asset('img/item8.jpg') }}" alt="Card image" height="300px">
                        <div class="card-body card-body-border card-padding" >
                            <strong class="str-color">3 Bledlow Close</strong>
                            <div class="row mt-2">
                                <label class="col-md-6"  >UPRN:</label>
                                <div class="col-md-6"  >
                                    046632
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6"  >Parent:</label>
                                <div class="col-md-6"  >
                                    Lisson Green Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6" >Estate Code:</label>
                                <div class="col-md-6" >
                                    Lisson Green Estate
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6"  >Shine:</label>
                                <div class="col-md-6" >
                                    PL27893
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="pagination-right mar-up">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item ">
                                <span class="page-link" href="#">First</span>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                            <li class="page-item active">
                              <span class="page-link">
                                1
                                <span class="sr-only">(current)</span>
                              </span>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Last</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="add" style="position: fixed !important;">
    <div class="filter">
        <div class="form-select-button">
            <button class="btn-remove" id="close-form" ><i class="fa fa-remove fa-2x"></i></button>
            <h2>Filters</h2>
            <p> <strong>Asset Class</strong></p>
            <ul class="select-button">
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Block</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Corporate Property</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Comercial Building</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Domestic Property</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Estate</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Out Building</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Outdoor Space</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Non-Domestic</span>
                </li>
            </ul>
        </div>
        <div class="form-select-button">
            <p><strong>Property Status</strong></p>
            <ul class="select-button">
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Derelict</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Operational</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Vacant</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">No Longer under Managenment</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Demolished</span>
                </li>
            </ul>
        </div>
        <div class="form-select-button">
            <p><strong>Included Attributes</strong></p>
            <ul class="select-button">
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">AOV</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Dry Riser</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Fire Alarm</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Fire Extinguishers</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Sprinklers</span>
                </li>
            </ul>
        </div>
        <div class="form-select-button">
            <p><strong>Identified Risks</strong></p>
            <ul class="select-button">
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Inaccessible Room/locations</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Inaccessible Voids</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Asbestos Containing Materials</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Hazards</span>
                </li>
                <li>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                    <span class="text-select">Nonconformity</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            $('#filter').click( function(e) {
                $('.add').toggleClass('add-aimation');
            });
            $('#close-form').click( function() {
                $('.add').removeClass('add-aimation');
            });
        });
    </script>
@endpush

<div class="container">
    @if($properties)
        <div class="row">
            @php
            // key is change from where query in collection
                $count = 0;
            @endphp
            @foreach($properties as $key => $property)
                <div class="col-3">
                        <div class="property-opt">
                            <a class="text-decoration-none" href="{{route('property.operative.detail', ['id' => $property->id, 'section' => SECTION_DEFAULT])}}" >
                                <div class="unit-operative">
                                    <img class="img-client-operative" src="{{ CommonHelpers::getFile($property->id, PROPERTY_IMAGE) }}" alt="Property Image" style="width:100%">
                                </div>
                                <div class="property-opt-des" style="background: {{$property->property_operative['bg_color']}}; color: {{$property->property_operative['text_color']}};">
                                    <em class="des-field">{{$property->property_operative['text']}}</em>
                                </div>
                                <div class="name-field" title="{{$property->name}}">
                                    <strong class="name">{{$property->name}}</strong>
                                </div>
                            </a>
                            {{--<a class="btn btn-primary" href="#" role="button">--}}
                            <div class="download-operative">
                                <a title="Download Asbestos Register PDF" href="{{route('register.pdf',['type'=>PROPERTY_REGISTER_PDF,'id'=>$property->id])}}" class="btn btn-outline-secondary w-100 download-pdf-btn">
                                    <i class="fa fa-download"></i>
                                </a>
                            </div>
                        </div>
                </div>
                @if($count > 0 && (($count+1)%4) == 0)
                    </div><div class="row">
                @endif
                @php
                    // key is change from where query in collection
                        $count ++;
                @endphp
            @endforeach
        </div>
    @endif
</div>

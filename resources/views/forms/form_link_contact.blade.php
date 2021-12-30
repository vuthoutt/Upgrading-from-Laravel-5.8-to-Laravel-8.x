<div class="row">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold" >{{ isset($title) ? $title : '' }}</label>
    <div class="col-md-6 form-input-text" >
        @if(isset($data) and count($data) > 0)
            @foreach($data as $key => $contact)
                @if($key != 0)
                     ,
                @endif
                @if(isset($contact['work_id']))
                        {{ $contact['first_name']. " " . $contact['last_name'] }}
                @else
                        <a href="{{ route('profile',['id'=>$contact['id']]) }}">{{ $contact['first_name']. " " . $contact['last_name'] }}</a>
                @endif
            @endforeach
        @endif
    </div>
</div>

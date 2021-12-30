@foreach($summary_data as $s)
    <ul>
        <li data-id="child{{$prefix}}-{{$s->id}}" data-summary="1" data-value="{{$s->id}}"
            data-checked="{{in_array($s->id,
            $checked_summary ?? []) ? 1 : 0}}"
            data-level="{{$level + 1}}" data-parent="{{$data->id}}" data-prefix = "{{$prefix}}">
            <span>{{$s->description}}</span>
        </li>
    </ul>
@endforeach

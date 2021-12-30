@foreach($items as $item)
    <ul>
        <li data-id="child-{{$prefix}}-{{$item->id}}" data-value="{{$item->id}}"
            data-checked="{{in_array($item->id,
            $checked_array ?? []) ? 1 : 0}}"
            data-level="{{$level + 1}}" data-parent="{{$data->id}}"
            data-prefix = "{{$prefix}}">
            <span>{{$item->name}}</span>
        </li>
    </ul>
@endforeach

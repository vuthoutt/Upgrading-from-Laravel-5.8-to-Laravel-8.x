<ul>
    @foreach($work_type as $wt)
        <li class="workrequest_child" data-id="child-{{$prefix}}-workrequest-{{$wt->id}}"
            data-checked="{{in_array($wt->id, $checked_array) ? 1 : 0}}"
            data-level="{{$level + 2}}" data-value="{{$wt->id}}" data-parent="{{$data->id}}" data-prefix = "{{$prefix}}">
            <span>{{$wt->description}}</span>
        </li>
    @endforeach
</ul>



<ul>
    @foreach($client_org as $c)
        <li data-id="child{{$prefix}}-clients-{{$c->id}}" data-organisation-parent="1" data-value="{{$c->id}}" data-level="{{$level + 1}}" data-parent="{{$data->id}}">
            <span>{{$c->name}}</span>
            <ul>
                @foreach($organisation as $o)
                    <li class="organisation_child" data-id="child{{$prefix}}-projects-{{$c->id}}-{{$o->id}}"
                        data-checked="{{
                            in_array($o->id,
                            $data_role->contractor[$c->id] ?? []) ? 1 : 0
                        }}"
                        data-organisation-child="1" data-value="{{$o->id}}" data-level="{{$level + 2}}" data-parent="{{$c->id}}">
                        <span>{{$o->name}}</span>
                    </li>
                @endforeach
            </ul>
        </li>
    @endforeach
</ul>

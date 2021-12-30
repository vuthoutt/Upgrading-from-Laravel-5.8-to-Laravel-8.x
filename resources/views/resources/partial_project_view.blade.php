@foreach($project_types as $p_type)
    <ul>
        <li data-id="child{{$prefix}}-projects-{{$p_type->id}}" data-project-type="1"
            data-checked="{{in_array($p_type->id,
            $data_role->project_type ?? []) ? 1 : 0}}"
            data-value="{{$p_type->id}}" data-level="{{$level + 1}}" data-parent="{{$data->id}}">
            <span>{{$p_type->description}}</span>
        </li>
    </ul>
@endforeach

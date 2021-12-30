@foreach($project_informations as $p_type)
    <ul>
        <li data-id="child{{$prefix}}-projects-{{$p_type->id}}" data-project-info="1"
            data-checked="{{in_array($p_type->id,
            $data_role->project_information ?? []) ? 1 : 0}}"
            data-value="{{$p_type->id}}" data-level="{{$level + 1}}" data-parent="{{$data->id}}">
            <span>{{$p_type->name}}</span>
        </li>
    </ul>
@endforeach

@foreach($document_approval as $d)
    <ul>
        <li data-id="child{{$prefix}}-projects-{{$d->id}}" data-document-approval="1"
            data-checked="{{in_array($d->id,
            $data_role->data_center ?? []) ? 1 : 0}}"
            data-value="{{$d->id}}" data-level="{{$level + 1}}" data-parent="{{$data->id}}">
            <span>{{$d->name}}</span>
        </li>
    </ul>
@endforeach

@foreach($template_document as $t)
    <ul>
        <li data-id="child{{$prefix}}-{{$t->id}}" data-template-parent="1" data-value="{{$t->id}}" data-level="{{$level + 1}}" data-parent="{{$data->id}}">
            <span>{{$t->category}}</span>
            @if(isset($t->template) && !$t->template->isEmpty())
                <ul>
                    @foreach($t->template as $document)
                        <li data-zone="list-zone" data-id="child{{$prefix}}-document-{{$document->id}}"
                            data-checked="{{in_array($document->id,
                            $data_role->category_box ?? []) ? 1 : 0}}"
                            data-template-child="1" data-value="{{$document->id}}" data-checked="0" data-level="{{$level + 2}}" data-parent="document-{{$prefix}}">
                            <span>{{$document->name}}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    </ul>
@endforeach

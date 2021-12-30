@extends('tables.main_table',[
        'header' => ['Document Name', 'Reference', 'Last Revision', 'View']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                @if(isset($edit_permission) and $edit_permission == true)
                    <td><a href="#edit-work-doc-{{$dataRow->id}}" data-toggle="modal">{{ $dataRow->name }}</a></td>
                @else
                    <td>{{ $dataRow->name }}</td>
                @endif
                <td>{{ $dataRow->reference }}</td>
                <td>{{ $dataRow->created_at ? $dataRow->created_at->format('d/m/Y') : '' }}</td>
                <td>{!! \CommonHelpers::getFilePdfViewing($dataRow->id, WORK_REQUEST_FILE, 0 , $dataRow->name) !!}</td>
            </tr>
            @include('modals.add_work_doc',[ 'modal_id' => 'edit-work-doc-'.$dataRow->id ,'action' => 'edit',
                                'url' => route('wr.post_document'),
                                'work_id' => $dataRow->work_id,
                                'unique' => \Str::random(5),
                                'data' => $dataRow ])
        @endforeach
    @endif
@overwrite

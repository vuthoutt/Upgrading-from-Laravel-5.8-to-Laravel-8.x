@extends('tables.main_table', [
        'header' => $header
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
            @if(\Auth::user()->clients->client_type == 0)
                <td><a href="#gsk-doc-modal-edit{{ $dataRow->id }}" data-toggle="modal">{{ $dataRow->name }}</a></td>
                @else
                    <td>{{  $dataRow->name }}</td>
                @endif
            @else
                <td>{{  $dataRow->name }}</td>
            @endif
            <td>{{ $dataRow->reference }}</td>
            <td>{{ optional($dataRow->refurbDocType)->doc_type }}</td>
            <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp($dataRow->getOriginal('added')) }} </span>{{ $dataRow->added }}</td>
            <td>{!! \CommonHelpers::getProjectDocViewing($dataRow->id, DOCUMENT_FILE, $dataRow->status, $dataRow->name, $dataRow->project->property_id ?? 0) !!}</td>
            @if($type == 'contractor' or $type == 'gskdocument')
                <td>
                    @if(is_numeric(CommonHelpers::getDocumentDaysRemain($dataRow->deadline)) && $dataRow->status == 3)
                        <span class="badge {{ CommonHelpers::getDocumentRiskColor(CommonHelpers::getDocumentDaysRemain($dataRow->deadline)) }}">{{ CommonHelpers::getDaysRemaninng($dataRow->deadline) }}</span> Days Remaining
                    @else
                        <span class="badge grey" style="font-size: 85% !important">&nbsp;&nbsp;&nbsp;NA&nbsp;&nbsp;&nbsp</span>
                    @endif
                </td>
            @endif
            @include('modals.project_document_edit',['color' => 'blue','doc_cat' => GSK_DOC_CATEGORY, 'modal_id' => 'gsk-doc-modal-edit'.$dataRow->id, 'url' => route('ajax.project_doc'), 'title' => 'Edit GSK Documents', 'doc_types' => $gsk_document_types, 'contractor_key' => 'gsk', 'data' => $dataRow, 'unique_value' => \Str::random(10)])
        </tr>
        @endforeach
    @endif
@overwrite

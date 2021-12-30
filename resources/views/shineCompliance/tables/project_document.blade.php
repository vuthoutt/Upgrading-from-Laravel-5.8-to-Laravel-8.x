@extends('shineCompliance.tables.main_table', [
        'header' => $header,
        'row_col' => 'col-md-12'
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
                @if($type == CONTRACTOR_DOC_TYPE)
                    @if($dataRow->status == 1)
                        <td><a href="#contractor-doc-modal-edit{{ $dataRow->id }}" data-toggle="modal">{{ $dataRow->name }}</a></td>
                    @else
                        <td>{{  $dataRow->name }}</td>
                    @endif
                @else
                    <td><a href="#{{$type}}-doc-modal-edit{{ $dataRow->id }}" data-toggle="modal">{{ $dataRow->name }}</a></td>
                @endif
            @else
                <td>{{  $dataRow->name }}</td>
            @endif
            <td>{{ $dataRow->reference }}</td>
            <td>{{ optional($dataRow->refurbDocType)->doc_type }}</td>
            <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp($dataRow->getOriginal('added')) }} </span>{{ $dataRow->added ?? ''}}</td>
            <td>{!! \CommonHelpers::getProjectDocViewing($dataRow->id, DOCUMENT_FILE,($type == TENDER_DOC_TYPE) ? 2 : $dataRow->status, $dataRow->name, $dataRow->project->property_id ?? 0, $dataRow->auto_approve ?? 0) !!}</td>
            @if($type == 'contractor')
                <td>
                    @if(is_numeric(CommonHelpers::getDocumentDaysRemain($dataRow->deadline)) && $dataRow->status == 3)
                        <span class="badge {{ CommonHelpers::getDocumentRiskColor(CommonHelpers::getDocumentDaysRemain($dataRow->deadline)) }}">{{ CommonHelpers::getDaysRemaninng($dataRow->deadline) }}</span> Days Remaining
                    @else
                        <span class="badge grey" style="font-size: 85% !important">&nbsp;&nbsp;&nbsp;NA&nbsp;&nbsp;&nbsp</span>
                    @endif
                </td>
                <td style="min-width: 160px">
                    @if( ( \Auth::user()->id == $project->lead_key ||
                            \Auth::user()->id == $project->second_lead_key ||
                            in_array( \Auth::user()->id, \CommonHelpers::getGetAdminAsbestosLead() )
                            ) and ($dataRow->status == 1) and ($dataRow->document_present == 1) and !in_array($project->status, [1,5]) )

                        <button type="button" class="btn btn-success approval_survey" data-doc-id="{{ $dataRow->id }}" data-doc-name="{{ $dataRow->name }}" data-toggle="modal" data-target="#project-confirm" >Confirm</button>
                        &nbsp;
                        <button type="button" class="btn btn-danger approval_survey" data-doc-id="{{ $dataRow->id }}" data-doc-date="{{ $dataRow->added }}" data-doc-name="{{ $dataRow->name }}" data-toggle="modal" data-target="#project-reject" >Reject</button>
                    @endif
                </td>
            @endif
            @if($type == TENDER_DOC_TYPE or $type == PLANNING_DOC_TYPE or $type == PRE_START_DOC_TYPE or $type == SITE_RECORD_DOC_TYPE or $type == COMPLETION_DOC_TYPE)
                @include('shineCompliance.modals.project_document_edit',['color' => 'blue','doc_cat' => $doc_cat, 'modal_id' => $type.'-doc-modal-edit'.$dataRow->id, 'url' => route('shineCompliance.ajax.project_doc'), 'title' => 'Edit '.$modal_name.' Documents', 'doc_types' => $doc_types, 'contractor_key' => $type.$dataRow->id, 'data' => $dataRow, 'unique_value' => \Str::random(10) ])
            @elseif($type == 'contractor')
                @include('shineCompliance.modals.project_document_edit',['color' => 'blue','doc_cat' => CONTRACTOR_DOC_CATEGORY, 'modal_id' => 'contractor-doc-modal-edit'.$dataRow->id, 'url' => route('shineCompliance.ajax.project_doc'), 'title' => 'Edit Contractors Documents', 'doc_types' => $contractor_document_types, 'contractor_key' => $contractor_data->id, 'data' => $dataRow, 'unique_value' => \Str::random(10)])
            @endif
        </tr>
        @endforeach
    @endif
@overwrite

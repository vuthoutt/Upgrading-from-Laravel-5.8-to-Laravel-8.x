@extends('tables.main_table', [
        'header' => $header
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                @if(isset($edit_permission) and $edit_permission == true )
                    @if($type == CONTRACTOR_DOC_TYPE)
                        @if($dataRow->status == 1)
                            <td><a style="color: {{ $dataRow->document_present == 1 ? '#007bff' : 'red' }}" href="#contractor-doc-modal-edit{{ $dataRow->id }}" data-toggle="modal">{{ $dataRow->name }}</a></td>
                        @else
                            <td>{{  $dataRow->name }}</td>
                        @endif
                    @else
                        <td><a style="color: {{ $dataRow->document_present == 1 ? '#007bff' : 'red' }}" href="#{{$type}}-doc-modal-edit{{ $dataRow->id }}" data-toggle="modal">{{ $dataRow->name }}</a></td>
                    @endif
                @else
                    <td>{{  $dataRow->name }}</td>
                @endif {{-- Document Name --}}
                <td style="width:9%">{{ $dataRow->reference }}</td> {{-- Document Reference --}}
                <td>{{ optional($dataRow->refurbDocType)->doc_type }}</td> {{-- Document Type --}}
                <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp($dataRow->getOriginal('added')) }} </span>{{ $dataRow->added ?? ''}}</td> {{-- Last Revision --}}
                <td>
                    {!! \CommonHelpers::getProjectDocViewing($dataRow->id, DOCUMENT_FILE,($type == TENDER_DOC_TYPE) ? 2 : $dataRow->status, $dataRow->name, $dataRow->project->property_id ?? 0, $dataRow->auto_approve ?? 0) !!}

                </td> {{-- View --}}
{{--                @if($type == 'contractor')--}}
                <td>{{ $dataRow->dead_line_date ?? '' }}</td> {{-- Deadline --}}
                <td style='width:12%'>
                    @if(($dataRow->dead_line_date == 'N/A') and ($dataRow->status != PROJECT_DOC_COMPLETED))
                        <span class="badge grey" style="font-size: 85% !important">&nbsp;&nbsp;&nbsp;NA&nbsp;&nbsp;&nbsp</span>
                    @elseif($dataRow->status == 2)
                        <span class="badge grey" style="font-size: 85% !important">&nbsp;&nbsp;&nbsp;NA&nbsp;&nbsp;&nbsp</span>
                    @else
                        <span class="badge <?php echo (\CommonHelpers::getDocumentRiskColor(\CommonHelpers::getDocumentDaysRemain($dataRow->deadline))); ?>">
                        {{  \CommonHelpers::getDocumentDaysRemain($dataRow->deadline) }}
                        </span> Days Remaining
                    @endif
                </td>{{-- Risk Warning --}}
                <td style="width:12%">
                    @if($dataRow->status == PROJECT_DOC_PUBLISHED
                                and $dataRow->document_present == 1
                                and $project->status == PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS
                                and $project->progress_stage == $doc_cat)
                        <input class="button-img" type="image" data-doc-id="{{ $dataRow->id }}" data-doc-name="{{ $dataRow->name }}" data-type="cancel" data-toggle="modal" data-target="#project-cancel{{ $unique ?? '' }}" src="{{asset('img/cancel.png')}}" title="Cancel" >
                        @if(\Auth::user()->id == $project->lead_key ||
                                \Auth::user()->id == $project->second_lead_key ||
                                in_array( \Auth::user()->id, \CommonHelpers::getGetAdminAsbestosLead())
                                )
                            <input class="button-img" type="image" data-doc-id="{{ $dataRow->id }}" data-doc-name="{{ $dataRow->name }}" data-type="approval" data-toggle="modal" data-target="#project-confirm{{ $unique ?? '' }}" src="{{asset('img/approval.png')}}" title="Approval" >
                            <input class="button-img" type="image" data-doc-id="{{ $dataRow->id }}" data-doc-name="{{ $dataRow->name }}" data-doc-date="{{ $dataRow->added }}" data-type="reject" data-toggle="modal" data-target="#project-reject{{ $unique ?? '' }}" src="{{asset('img/reject.png')}}" title="Reject" >
                        @endif
                    @endif
                </td>
                @if(in_array($type,[PRE_CONSTRUCTION_DOC_TYPE,
                                    DESIGN_DOC_TYPE,
                                    COMMERCIAL_DOC_TYPE,
                                    PLANNING_DOC_TYPE,
                                    PRE_START_DOC_TYPE,
                                    SITE_RECORD_DOC_TYPE,
                                    COMPLETION_DOC_TYPE,
                                ]))
                    @include('modals.project_document_edit',['color' => 'blue','doc_cat' => $doc_cat, 'modal_id' => $type.'-doc-modal-edit'.$dataRow->id, 'url' => route('ajax.project_doc'), 'title' => 'Edit '.$modal_name.' Documents', 'doc_types' => $doc_types, 'contractor_key' => $type.$dataRow->id, 'data' => $dataRow, 'unique_value' => \Str::random(10) ])
                @endif
{{--                @elseif($type == 'contractor')--}}
{{--                    @include('modals.project_document_edit',['color' => 'blue','doc_cat' => CONTRACTOR_DOC_CATEGORY, 'modal_id' => 'contractor-doc-modal-edit'.$dataRow->id, 'url' => route('ajax.project_doc'), 'title' => 'Edit Contractors Documents', 'doc_types' => $contractor_document_types, 'contractor_key' => $contractor_data->id, 'data' => $dataRow, 'unique_value' => \Str::random(10)])--}}
{{--                @endif--}}
            </tr>
        @endforeach
    @endif
@overwrite

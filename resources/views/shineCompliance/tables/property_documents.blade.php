@extends('shineCompliance.tables.main_table', [
        'header' => ['Document Name','Document Reference','Parent','Status','File','Date Completed'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($can_update) and ($can_update ==  false))
            <td>{{ $dataRow->name ?? '' }}</td>
            @else
            <td><a href="javascript:void(0)" data-toggle="modal" data-target="#property-document-edit" data-url="{{route('shineCompliance.property.post_edit.documents',['document_id'=>$dataRow->id])}}"
                   data-name="{{$dataRow->name ?? ''}}" data-type="{{$dataRow->type ?? ''}}" data-parent_type="{{$dataRow->parent_type ?? ''}}" data-type_other="{{$dataRow->type_other ?? ''}}"
                   data-system_id="{{$dataRow->system_id ?? ''}}" data-equipment_id="{{$dataRow->equipment_id ?? ''}}" data-property_id="{{$dataRow->property_id ?? ''}}" data-date="{{$dataRow->date ?? ''}}"
                   data-programme_id="{{$dataRow->programme_id ?? ''}}" data-id="{{$dataRow->id ?? ''}}"
                   data-compliance_type="{{$dataRow->compliance_type ?? ''}}" data-category_id="{{$dataRow->category_id ?? ''}}"
                   data-modal_category_id="{{$modal_category_id ?? 0}}" data-is_identified_acm="{{$dataRow->is_identified_acm ?? 0}}" data-is_inaccess_room="{{$dataRow->is_inaccess_room ?? 0}}"
                    data-enforcement_deadline="{{$dataRow->enforcement_deadline ?? ''}}" data-document_status="{{$dataRow->document_status ?? ''}}"
                >{{$dataRow->name ?? ''}}</a></td>

            @endif
            <td>{{$dataRow->reference}}</td>
            <td>
                @if($dataRow->parent_type == DOCUMENT_NO_REQUIRE_TYPE)
                    {{'No Parent Required'}}
                @else
                    @if($dataRow->programme_id > 0)
                        <a href="{{route('shineCompliance.programme.detail',[$dataRow->programme->id ?? 0])}}">{{$dataRow->programme->name ?? ''}}</a>
                    @elseif($dataRow->equipment_id > 0)
                        <a href="{{route('shineCompliance.equipment.detail',[$dataRow->equipment->id ?? 0])}}">{{$dataRow->equipment->name ?? ''}}</a>
                    @else
                        <a href="{{route('shineCompliance.systems.detail',[$dataRow->system_id ?? 0])}}">{{$dataRow->system->name ?? ''}}</a>
                    @endif
                @endif
            </td>
            <td>New Document</td>
            <td style="width: 70px !important">
                {!!
                    \CommonHelpers::getDocumentsViewing(0,
                    route('shineCompliance.document.view',['id'=>$dataRow->id,'type'=>VIEW_COMPLIANCE_DOCUMENT]),
                    route('shineCompliance.document.download',['id'=>$dataRow->id,'type'=>DOWNLOAD_COMPLIANCE_DOCUMENT]))
                !!}</td>
            <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp($dataRow->getOriginal('date')) }} </span>{{$dataRow->date ?? ''}}</td>
        </tr>
        @endforeach
    @endif
@overwrite

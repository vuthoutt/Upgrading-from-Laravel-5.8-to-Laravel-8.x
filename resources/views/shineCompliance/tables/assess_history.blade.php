@extends('shineCompliance.tables.main_table', [
        'header' => ["Document", "Last Updated", "File"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $key => $dataRow)
        <tr>
            <td>{{ $dataRow->name }} {{ $key == 0 ? '(Current)' : '' }}</td>
            <td>{{ $dataRow->created_at}}</td>
            @if($key == 0)
                <td>{!!   ComplianceHelpers::getPdfViewingAssessment($status, route('shineCompliance.assessment.pdf_download',['type'=>'view','id'=>$dataRow->id]), route('shineCompliance.assessment.pdf_download',['type'=>'download','id'=>$dataRow->id]))!!}</td>
            @else
                <td>{!!   ComplianceHelpers::getPdfViewingAssessment(1, route('shineCompliance.assessment.pdf_download',['type'=>'view','id'=>$dataRow->id]), route('shineCompliance.assessment.pdf_download',['type'=>'download','id'=>$dataRow->id]))!!}</td>
            @endif
        </tr>
        @endforeach
    @endif
@overwrite


@extends('tables.main_table', [
        'header' => ["Document", "Last Updated", "File"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $key => $dataRow)
        <tr>
            <td>{{ $dataRow->name }} {{ $key == 0 ? '(Current)' : '' }}</td>
            <td>{{ $dataRow->created_at}}</td>
            @if($key == 0)
                <td>{!!   CommonHelpers::getPdfViewingSurvey($status, route('survey.view.pdf',['type'=>VIEW_SURVEY_PDF,'id'=>$dataRow->id]), route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=>$dataRow->id]))!!}</td>
            @else
                <td>{!!   CommonHelpers::getPdfViewingSurvey(6, route('survey.view.pdf',['type'=>VIEW_SURVEY_PDF,'id'=>$dataRow->id]), route('survey.download.pdf',['type'=>DOWNLOAD_SURVEY_PDF,'id'=>$dataRow->id]))!!}</td>
            @endif
        </tr>
        @endforeach
    @endif
@overwrite


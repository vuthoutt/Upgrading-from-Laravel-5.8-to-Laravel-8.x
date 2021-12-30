@extends('shineCompliance.tables.main_table', [
        'header' => ['Query','Responses']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td>{{ $dataRow->description ?? '' }}</td>
            <td>{{ isset($dataRow->answerValue)
                    ? ($dataRow->answer_type == 1 ? ($dataRow->answerValue->answer->description ?? '') : ($dataRow->answerValue->answer_other ?? ''))
                    : ''}}</td>
        </tr>
        @endforeach
    @endif
@overwrite

@extends('shineCompliance.tables.main_table', [
        'header' => ['Questions','Responses','Additional Comments']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td>{{ isset($dataRow->dropdownQuestionData->description) ? $dataRow->dropdownQuestionData->description : '' }}</td>
            @if(isset($dataRow->dropdownQuestionData->other) and $dataRow->dropdownQuestionData->other != SURVEY_ANSWER_OTHER )
                <td>{{ isset($dataRow->dropdownAnswerData->description) ? $dataRow->dropdownAnswerData->description : '' }}</td>
            @else
                <td>{{ $dataRow->answerOther }}</td>
            @endif
            <td>{{ $dataRow->comment }}</td>
        </tr>
        @endforeach
    @endif
@overwrite

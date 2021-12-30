@extends('shineCompliance.tables.main_table', [
        'header' => ['Management Query','Responses']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td>{{ $dataRow->description ?? '' }}</td>
            <td>
                @if(isset($dataRow->answerValue))
                    @if($dataRow->answer_type == 1)
                        {{ isset($dataRow->answerValue->answer_other) ? $dataRow->answerValue->answer_other : ($dataRow->answerValue->answer->description ?? '') }}
                    @elseif($dataRow->answer_type == 3)
                        @foreach(explode(',', $dataRow->answerValue->answer_id) as $answer_id)
                            @php($answer = \ComplianceHelpers::getAssessmentManagementInfoAnswerDescription($answer_id))
                            {{ $answer == 'Other'
                                ? ($dataRow->answerValue->answer_other ?? '')
                                : $answer }}
                            <br>
                        @endforeach
                    @else
                        {{ isset($dataRow->answerValue->answer_other) ? ($dataRow->answerValue->answer_other ?? '') : ($dataRow->pre_loaded ?? '')  }}
                    @endif
                @endif
            </td>
        </tr>
        @endforeach
    @endif
@overwrite

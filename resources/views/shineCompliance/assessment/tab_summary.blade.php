<div class="row mt-5 ml-0">
    <table style="width:100%">
        <tr>
            <th style="width: 320px"></th>
            <th>Weight</th>
            <th>Result</th>
            <th>Score (%)</th>
            <th>Weighted Score</th>
        </tr>
        @php
            $total_score = $total_question = $total_weight = 0;
        @endphp
        @foreach($sections as $section)
            @php
                $total_score += $section->resultScore->total_score ?? 0;
                $total_question += $section->resultScore->total_question ?? 0;
                $total_weight += round((($section->resultScore->total_score ?? 0) / ($section->resultScore->total_question ?? 1)) * ($section->score ?? 1));
            @endphp
            <tr>
                <td class="font-weight-bold" style="height: 40px">{{ $section->description ?? '' }}:</td>
                <td>{{ sprintf("%02d",$section->score ?? 0)  }}</td>
                <td>{{ sprintf("%02d",$section->resultScore->total_score ?? 0) }}/{{ sprintf("%02d",$section->resultScore->total_question ?? 0) }}</td>
                <td>{{ round( ($section->resultScore->total_score ?? 0) / ($section->resultScore->total_question ?? 1) * 100) }}</td>
                <td style="width: 170px">{{ round((($section->resultScore->total_score ?? 0) / ($section->resultScore->total_question ?? 1)) * ($section->score ?? 1)) }}</td>
                <td style="width: 170px">
                    @if($section->resultScore != null)
                        @if(isset($section->resultScore->total_score) and isset($section->resultScore->total_question) and $section->resultScore->total_score == $section->resultScore->total_question)
                            <div><span class="spanWarningSuccess font-weight-bold pass-result">Pass</span></div>
                        @else
                            <div><span class="spanWarningSurveying font-weight-bold false-result" style="padding-left: 14px;padding-right: 17px;">Fail</span></div>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
        <tfoot class="mt-5">
        <tr>
            <td class="font-weight-bold" style="height: 40px">Total</td>
            <td>100</td>
            <td>{{ sprintf("%02d",$total_score) }}/{{ sprintf("%02d", $total_question) }}</td>
            <td>{{ round ((( $total_score) / ($total_question > 0 ? $total_question : 1)) * 100 )}}</td>
            <td>{{ sprintf("%02d", $total_weight) }}</td>
        </tr>
        <tr>
            <td class="font-weight-bold" style="height: 40px">Score Result</td>
            <td style="height: 60px">
                <div class="font-weight-bold">{{ sprintf("%02d", $total_weight) }}</div>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.assessment_count', [
            'title' => 'Assessment Count',
            'count' => 3,
            'tableId' => 'assessment_count',
            'collapsed' => false,
            'plus_link' => false,
            'data' => $assessment ?? NULL,
            'order_table' => "[]"
            ])
    </div>
</div>

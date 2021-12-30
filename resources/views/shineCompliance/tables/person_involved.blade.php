@extends('shineCompliance.tables.main_table', [
        'header' => ['Questions','Responses']
    ])
@section('datatable_content')
    <tr>
        <td>Injury Type</td>
        <td>{{ trim(implode("", array_filter([\CommonHelpers::getDataIncidentReportInvolvedPerson($data->injury_type), \CommonHelpers::getDataInvolvedReasonOther($data->injury_type_other)])), ", ") }}</td>
    </tr>
    <tr>
        <td>Part(s) of the body affected</td>
        <td>{{ \CommonHelpers::getDataIncidentReportInvolvedPerson($data->part_of_body_affected) }}</td>
    </tr>
    <tr>
        <td>Apparent Cause</td>
        <td>{{ trim(implode("", array_filter([\CommonHelpers::getDataIncidentReportInvolvedPerson($data->apparent_cause),\CommonHelpers::getDataInvolvedReasonOther($data->apparent_cause_other)])), ", ") }}</td>
    </tr>
@overwrite

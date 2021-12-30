@extends('emails.template')

@section('email_content')
<table cellspacing="0" border="0" cellpadding="0" width="100%">
    <tr>
        <td valign="middle" height="37" style="height: 37px; border-bottom-color: #d6d6d6; border-bottom-width: 1px; border-bottom-style: solid; vertical-align: middle;">
            <h2 style="margin: 0; padding: 0; font-size: 16px; font-weight: bold; color: #1F497D; text-align: left;">{{ $subject }}</h2>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                Dear {{ $username }},
            </p>
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                <table cellspacing="0" border="0" cellpadding="0" width="100%">
                    <tr>
                        <td style="width:220px;"><strong>Survey Reference:</strong></td>
                        <td>{{ $survey_reference }}</td>
                    </tr>
                    <tr>
                        <td style="width:220px;"><strong>Survey Type:</strong></td>
                        <td>{{ $survey_type }}</td>
                    </tr>
                    <tr>
                        <td style="width:220px;"><strong>Project Reference:</strong></td>
                        <td>{{ $project_reference }}</td>
                    </tr>
                    <tr>
                        <td style="width:220px;"><strong>Work Request Reference:</strong></td>
                        <td>{{ $work_request_reference }}</td>
                    </tr>
                    <tr>
                        <td style="width:220px;"><strong>Work Request Type:</strong></td>
                        <td>{{ $work_request_type }}</td>
                    </tr>
                    <tr>
                        <td style="width:220px;"><strong>Property Reference:</strong></td>
                        <td>{{ $property_reference }}</td>
                    </tr>
                    <tr>
                        <td style="width:180px;"><strong>Property Name:</strong></td>
                        <td>{{ $property_name }}</td>
                    </tr>
                </table>
            </p>
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                This is a courtesy email from {{ $company_name }} to inform you that the {{ $survey_reference ?? ''}} has been approved.
            </p>
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
            Please use this <a href="{{ $link_pdf }}" style="color: #1F497D; text-decoration: none;">link</a> to view PDF Report.
            <p>

            <p style="margin: 0; margin-top: 7px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                Kindest Regards,
            </p>
            <p style="margin: 0; margin-top: 7px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                {{ $company_name }}
            </p>
        </td>
    </tr>
    <tr>
        <td valign="top" height="40" style="height: 36px;">&nbsp;</td>
    </tr>
</table>
@endsection

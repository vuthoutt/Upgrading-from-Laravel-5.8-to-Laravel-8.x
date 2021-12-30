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
                        <td style="width:180px;"><strong>Work Request Reference:</strong></td>
                        <td>{{ $work_request_reference }}</td>
                    </tr>
                    <tr>
                        <td style="width:180px;"><strong>Work Request Type:</strong></td>
                        <td>{{ $work_requester_type }}</td>
                    </tr>
                    <tr>
                        <td style="width:180px;"><strong>Block Reference:</strong></td>
                        <td>{{ $block_reference }}</td>
                    </tr>
                    <tr>
                        <td style="width:180px;"><strong>Property Reference:</strong></td>
                        <td>{{ $property_reference }}</td>
                    </tr>
                    <tr>
                        <td style="width:180px;"><strong>Property Name:</strong></td>
                        <td>{{ $property_name }}</td>
                    </tr>
                    <tr>
                        <td style="width:180px;"><strong>Property Postcode:</strong></td>
                        <td>{{ $property_postcode }}</td>
                    </tr>
                </table>
            </p>
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                @if($subject == 'Work Request Approved')
                    This is a courtesy email from {{ $company_name }} to inform you that {{ $work_request_reference }} is now been approved. Technical activity will now begin.
                @else
                    This is a courtesy email from {{ $company_name }} to inform you that
                    {{ $work_request_reference }} has been rejected.
                @endif
            </p>
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                @if($subject != 'Work Request Approved')
                    Please <a href="{{ $domain.'data-centre/rejected' }}"> login into your account </a>  to review the rejection comments in the Data Centre Rejections and
                    make the required changes before re-publish.
                @endif
            </p>
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

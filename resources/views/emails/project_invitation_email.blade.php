@extends('emails.template')

@section('email_content')
<table cellspacing="0" border="0" cellpadding="0" width="100%">
    <tr>
        <td valign="middle" height="37" style="height: 37px; border-bottom-color: #d6d6d6; border-bottom-width: 1px; border-bottom-style: solid; vertical-align: middle;">
            <h2 style="margin: 0; padding: 0; font-size: 16px; font-weight: bold; color: #1F497D; text-align: left;">Project Invitation</h2>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                Dear User,
            </p>

            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                <table>
                    <tr>
                        <td><strong>Project Reference:</strong>
                        </td>
                        <td>{{ $project_ref }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Project Type:</strong>
                        </td>
                        <td>{{ $project_type }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Project Title:</strong>
                        </td>
                        <td>{{ $project_name }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Block Reference:</strong>
                        </td>
                        <td>{{ $property->pblock }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Property UPRN:</strong>
                        </td>
                        <td>{{ $property->property_reference }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Property Name:</strong>
                        </td>
                        <td>{{ $property->name}}
                        </td>
                    </tr>
                </table>
            </P>
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                This is a courtesy email from Westminster City Council to invite you into {{ $project_ref }}. Associated documents will be uploaded shortly for your consideration/action.
            </p>
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                Please <a href="{{ $domain }}" style="color: #1F497D; text-decoration: none;">log in to your account</a> to take further action.
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

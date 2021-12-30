@extends('emails.template')

@section('email_content')
<table cellspacing="0" border="0" cellpadding="0" width="100%">
    <tr>
        <td valign="middle" height="37" style="height: 37px; border-bottom-color: #d6d6d6; border-bottom-width: 1px; border-bottom-style: solid; vertical-align: middle;">
            <h2 style="margin: 0; padding: 0; font-size: 16px; font-weight: bold; color: #1F497D; text-align: left;">Project Archived</h2>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                Dear Contractor,
            </p>
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                This is a courtesy email to inform you that the following project has been successfully completed and  archived. You do not need to take any further action.
            </p>

            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                <table>
                    <tr>
                        <td><strong>Project Title:</strong>
                        </td>
                        <td>{{ $project_name }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Project Reference:</strong>
                        </td>
                        <td>{{ $project_ref }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Property UPRN:</strong>
                        </td>
                        <td>{{ $property->property_reference }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Block Reference:</strong>
                        </td>
                        <td>{{ $property->propertyInfo->pblock }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Property Name:</strong>
                        </td>
                        <td>{{ $property->name}}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Property Postcode:</strong>
                        </td>
                        <td>{{ $property->propertyInfo->postcode }}
                        </td>
                    </tr>
                </table>
            </P>
            <p style="margin: 0; margin-top: 7px; margin-bottom: 20px; padding: 0; font-size: 13px; font-weight: normal; color: #535353; line-height: 22px; text-align: justify;">
                Should you require any information relating to this project for audit purposes, please contact a member of the shine team.
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

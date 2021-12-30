@extends('emails.template')

@section('email_content')
<table cellspacing="0" border="0" cellpadding="0" width="100%">
    <tr>
        <td valign="middle" height="37" style="height: 37px; border-bottom-color: #d6d6d6; border-bottom-width: 1px; border-bottom-style: solid; vertical-align: middle;">
            <h2 style="margin: 0; padding: 0; font-size: 16px; font-weight: bold; color: #1F497D; text-align: left;">Asbestos Register Update</h2>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <p class="paragraph marginBot20">
                Dear User,
            </p>
            <p class="paragraph marginBot20">
                <table cellspacing="0" border="0" cellpadding="0" width="100%">
                    <tr>
                        <td style="width:180px;"><strong>Property UBRN:</strong></td>
                        <td>{{ $property_block }}</td>
                    </tr>
                    <tr>
                        <td style="width:180px;"><strong>Property UPRN:</strong></td>
                        <td>{{ $property_ref }}</td>
                    </tr>
                    <tr>
                        <td style="width:180px;"><strong>Property Name:</strong></td>
                        <td>{{ $property_name }}</td>
                    </tr>
                </table>
            </p>
            <p class="paragraph marginBot20">
                This is a courtesy email from {{ env('APP_DOMAIN') ?? 'GSK' }} to inform you that the asbestos register for this property has been updated. Please remove any printed copies.
            </p>
            <p class="paragraph marginBot20">
                For an up to date copy of the asbestos register please <a href="{{ $domain }}" style="color: #1F497D; text-decoration: none;">log in to your account</a>.
            </p>
            <p class="paragraph">
                Kindest Regards,
            </p>
            <p class="paragraph">
                {{ $company_name }}
            </p>
        </td>
    </tr>
    <tr>
        <td valign="top" height="40" style="height: 36px;">&nbsp;</td>
    </tr>
</table>
@endsection

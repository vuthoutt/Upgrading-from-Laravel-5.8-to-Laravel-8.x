@extends('emails.template')

@section('email_content')
<table cellspacing="0" border="0" cellpadding="0" width="100%">
    <tr>
        <td valign="middle" height="37" style="height: 37px; border-bottom-color: #d6d6d6; border-bottom-width: 1px; border-bottom-style: solid; vertical-align: middle;">
            <h2 style="margin: 0; padding: 0; font-size: 16px; font-weight: bold; color: #1F497D; text-align: left;">New Shine System Access Link</h2>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <p class="paragraph marginBot20">
                Dear Shine User,
            </p>
            <p class="paragraph marginBot20">
            </p>
            <p class="paragraph marginBot20">
                Please use the below link to access the new updated shineAsbetsos System
            </p>
            <p class="paragraph marginBot20">
                <a href="https://{{ env('APP_DOMAIN') ?? 'GSK' }}.shinegateway.co.uk" style="color: #1F497D;">https://{{ env('APP_DOMAIN') ?? 'GSK' }}.shinegateway.co.uk</a>
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

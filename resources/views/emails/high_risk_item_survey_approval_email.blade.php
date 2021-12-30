@extends('emails.template')

@section('email_content')
<table cellspacing="0" border="0" cellpadding="0" width="100%">
    <tr>
        <td valign="middle" height="37" style="height: 37px; border-bottom-color: #d6d6d6; border-bottom-width: 1px; border-bottom-style: solid; vertical-align: middle;">
            <h2 style="margin: 0; padding: 0; font-size: 16px; font-weight: bold; color: #1F497D; text-align: left;">Survey Approved Notification</h2>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <p class="paragraph marginBot20">
                Dear User,
            </p>
            <p class="paragraph marginBot20">
                This is a courtesy email from {{ env('APP_DOMAIN') ?? 'GSK' }} to inform you that Survey {{ $survey->reference ?? ''}} has been approved into the register of {{ $survey->property->name ?? '' }}.
            </p>
            <p class="paragraph marginBot20">
                Please use this <a href="{{ $survey->link_pdf }}" style="color: #1F497D; text-decoration: none;">link</a> to view PDF Report.
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

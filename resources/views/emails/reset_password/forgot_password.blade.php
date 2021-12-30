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
            <p class="paragraph ">
                Dear {{ $username }},
            </p>
            <p class="paragraph ">
                This is a courtesy email from {{ $company_name }} please create your new user password to access the shineCompliance portal.
            </p>
            <p class="paragraph marginBot20">
                @if ($type == 1)
                    Please use this <a href="{{ $domain.'/password/reset/' .$token. '?id=' .$user_id }}" style="color: #1F497D; text-decoration: none;"><strong>link</strong></a> to reset your password.
                @elseif ($type ==2 )
                    Please use this <a href="{{ $domain.'/password/reset/' .$token. '?id=' .$user_id }}" style="color: #1F497D; text-decoration: none;"><strong>link</strong></a> to create your password.
                @endif
            </p>
            <p class="paragraph marginBot20">
                <i>This link only available on 24 hours.</i>
            </p>
            <p class="paragraph">
                Kindest Regards,
            </p>
            <p class="paragraph">
                {{ $company_name ?? '' }}
            </p>
        </td>
    </tr>
    <tr>
        <td valign="top" height="40" style="height: 36px;">&nbsp;</td>
    </tr>
</table>
@endsection

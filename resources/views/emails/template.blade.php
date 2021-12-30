<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <!--general stylesheet-->
        <style type="text/css">
            p { padding: 0; margin: 0; }
            h1, h2, h3, p, li { font-family: Helvetica Neue, Helvetica, Arial, sans-serif; }
            td { vertical-align:top;}
            ul, ol { margin: 0; padding: 0;}
            .heading {
                border-radius: 3px;
                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                -khtml-border-radius: 3px;
                -icab-border-radius: 3px;
            }
            .paragraph{
                margin: 0;
                margin-top: 7px;
                padding: 0;
                font-size: 13px;
                font-weight: normal;
                color: #535353;
                line-height: 22px;
                text-align: justify;
            }
            .marginBot20{
                margin-bottom: 20px;
            }
        </style>
    </head>
<body>
    <table cellspacing="0" border="0" cellpadding="0" width="100%">
        <tbody>
            <tr valign="top">
                <td><!--container-->
                    <table cellspacing="0" cellpadding="0" border="0" align="center" width="750" bgcolor="#ffffff">
                        <tbody>
                            <tr><!--content-->
                                <td valign="middle" bgcolor="#ebebeb" height="30" style="vertical-align: middle; border-bottom-color: #d6d6d6; border-bottom-width: 1px; border-bottom-style: solid;">
                                    <p style="font-size: 11px; font-weight: bold; color: #8a8a8a; text-align: center;">
                                        You're receiving this email because you are registered to receive notifications on <a href="{{ $domain }}" style="color: #1F497D; text-decoration: none;">shine</a>.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" bgcolor="#ffffff" align="center">
                                    <table cellspacing="0" border="0" cellpadding="0" width="700">
                                        <tbody>
                                            <tr>
                                                <td valign="top" colspan="2" style="text-align: left;">
                                                    <h1 style="margin: 0; padding: 0; font-size: 22px; color: #1F497D; font-weight: bold;"><img src="{{ asset('/img/notifications_shinelogo.gif') }}" width="218" height="125" alt="shine"></h1>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top" width="100%">
                                                    @yield('email_content')
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top" height="40" bgcolor="#ffffff" style="height: 40px;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td valign="middle" height="58" style="height: 58px; vertical-align: middle; border-top-color: #d6d6d6; border-top-width: 1px; border-top-style: solid;">
                                                    <p style="font-size: 11px; font-weight: bold; color: #8a8a8a; text-align: center;">
                                                        This email was automated by <a href="{{ $domain }}" style="color: #1F497D; text-decoration: none;">{{ $company_name }}</a> and sent from an unmonitored mailbox; please do not reply.
                                                    </p>
                                                    <p style="font-size: 11px; font-weight: bold; color: #8a8a8a; text-align: center;">If you have any questions please contact the <a href="mailto:lee.taylor@shinevision.co.uk" style="color: #1F497D; text-decoration: none;">{{ $company_name }} Asbestos Management Team</a>.
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <!--/content-->
                            </tr>
                        </tbody>
                    </table><!--/container-->
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
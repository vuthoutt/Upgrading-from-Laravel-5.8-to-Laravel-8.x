<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" >
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
        .footer{
            border-top: 2px solid #1e71b8;
        }
        *{
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            box-sizing: border-box;
            font-size: 10pt;
            color: #575756;
        }
        .clearfix{
            margin-top: 8px;
        }
        .img-container1 {
            float: left; width: 40%;
        }
    </style>
</head>
<body>
<div class="footer">
    <div class="clearfix">
        <div style="float: left; width: 35%;">
            <div style="color: #0000F0;">
                <p style="display:inline-block;text-align:left;"></p>
            </div>
        </div>
        <div style="float: left; width: 25%;margin-top: 0!important;padding-top: 0!important">
            <img  class= "coverphoto" style="max-height: 100px; margin-left: 80px; margin-top: 0!important;padding-top: 0!important" width="100px" src="{{public_path('/img/client_logo_padding.png')}}" alt="Property Survey Image"/>
        </div>
        <div class="row" style="float: right; width: 38%;">
            <div style="float: right; width: 78%;">
                <div class="right"  >
                    <p style="text-align:right;padding: 0;margin: 0">
                        {{ $property->name }}
                    </p>
                </div>
                <div class="right">
                    <p style="text-align:right;padding: 0;margin: 0">
                        {{ \ComplianceHelpers::getDateTimeToStringToday() }} - {{ $assessment->reference ?? ''}}
                    </p>
                </div>
                <div class="right">
                    <p style="text-align:right;padding: 0;margin: 0">
                        {{ $assessment->project->reference ?? ''}}
                    </p>
                </div>
                <div class="right">
                    <p style="text-align:right;padding: 0;margin: 0">
                        Page <span id='page'></span> of <span id='topage'></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var vars={};
    var x=window.location.search.substring(1).split('&');
    for (var i in x) {
        var z=x[i].split('=',2);
        vars[z[0]] = unescape(z[1]);
    }
    document.getElementById('page').innerHTML = parseInt(vars.page) + {{ isset($current) && $current > 0 ? $current -1 :0 }};
    document.getElementById('topage').innerHTML = {{ $total ?? 0 }};
</script>
</html>

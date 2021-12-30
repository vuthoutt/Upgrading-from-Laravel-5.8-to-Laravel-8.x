<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <style type="text/css">
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 10pt;
            color: #575756;
        }

        .blue-text{color: #092591}
        .page {
            margin-top: 30px;
        }
        /*EndtableItems*/

        .textdecoration{
            border-bottom: 2px solid;
            margin-top: 80px;
        }

        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }

        table, td, th {
            border: 1px solid black;
        }

        table {
            color: black;
            margin-bottom: 20px;
            border-collapse: collapse;
            width: 100%;
            word-break: break-word;
        }

        th {
            word-break: break-word;
            background-color: #e6fbbb;
        }
        table tr td{
            font-size: 12px;
            padding-right: 15px;
            line-height: 1.5;
            word-spacing: 1px;
        }

        h2{
            font-size: 20px;
        }
        h4{
            font-size: 15px;
        }
        .text-title{
            margin-bottom: 10px;
            margin-left: 30px;
        }
        .header-report{
            width: 99%;
            margin-left: 0px;
            margin-top: 80px;
        }
        .scope_table{
            background-color: #dfecbd;
            color: #808275;
        }
        .page {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .alignment-title{
            background-color: #706f6f;
            color: white;
            height: 40px;
        }
        .alignment-text{
            padding-top: 13px;
            padding-left: 30px;
            font-size: 16px!important;
        }
        .mt30{
            margin-top: 5px!important;
            color: #575756;
            font-size: 10pt;
            line-height: 1.6;
            text-align: left;
            word-spacing: 1.0px;
        }
        .unset-border td {
            border: 0!important;
            color: #575756;
        }
        table.unset-border{
            border: 0!important;
            margin-bottom: 10px;
            color: #575756;
        }
        .tableGray tr th {
            background-color: #dadada!important;
            color: #575756;
        }
        .tableGray tr td {
            color: #575756;
        }
        th{
            font-size: 12px!important;
            font-weight: normal;
        }
        table tr td{
            font-size: 12px;
            padding-right: 15px;
            line-height: 1.5;
            word-spacing: 1px;
            word-break: break-word;
        }
        .tableItems tr td{
            word-break: break-word!important;
        }
        .system th{
            text-align: left;
            padding-left: 10px;
        }
        .system td{
            text-align: left;
            padding-left: 10px;
        }
        .resizetd td{
            height: 30px;
        }
        li{
            margin-top: 2px;
        }
    </style>
</head>
<body>
<div style="margin-top:30px;">
    <div class="textdecoration alignment-title">
        <p class="alignment-text">8.0 Summary of Fire System(s)</p>
    </div>
    <div class="content">
        <div class="mt30">
            <p>
            @if(count($systems) == 0)
                No fire systems were identfied at the property.
            @else
                A total of {{ count($systems) }} fire systems were assessed as part of the risk assessment.
            @endif
            </p>
        </div>
    </div>
    @foreach($systems as $system)
        <h3 class="mt30">{{ $system->name ?? '' }} - {{ $system->systemType->description ?? '' }}</h3>
        <div class="tableItems tableGray" style="margin-top: 10px">
            <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:100%;" class="system">
                <thead>
                <tr >
                    <th>System Comments</th>
                </tr>
                <tr >
                    <td>{!! $system->comment ?? 'No Comment.' !!}</td>
                </tr>
                </thead>
            </table>
        </div>
    @endforeach
</div>
<div style="page-break-before: always;"></div>
<div style="margin-top:30px;" class="page">
    @if(count($miscs))
        @foreach($miscs as $equipment)
            <div class="header-report alignment-title">
                <p class="alignment-text">8.0 Equipment Register</p>
            </div>
            <div style="margin-top: 20px">
                <div style="width:99%;">
                    <h4>Miscellaneous Equipment</h4>
                    @include('shineCompliance.pdf.water.equipment_template',['equipment' => $equipment])
                </div>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    @else
        <div class="header-report alignment-title">
            <p class="alignment-text">9.0 Equipment Register</p>
        </div>
        <div style="margin-top: 20px">
            <div style="width:99%;">
                <h4>Miscellaneous Equipment</h4>
            </div>
        </div>
        <div style="page-break-after: always;"></div>
    @endif
</div>

<div style="page-break-before: always;"></div>
<div style="margin-top:30px;">
    <div class="textdecoration alignment-title">
        <p class="alignment-text">10.0 Risk Assessment Information</p>
    </div>
    <div class="content" style="margin-top:30px">
        <h3 class="mt30">Regulatory Reform (Fire Safety) Order 2005 (RRO)</h3>
        <div class="mt30">
            <p>
                The RRO, which came into effect in October 2006 applies to all non-domestic premises in England and Wales, including the common
                areas of flats and houses in multiple occupation. It means that reasonable steps must be undertaken to reduce the risk from fire and to
                ensure people can safely escape in such an event.
            </p>
        </div>

        <div class="mt30">
            <p>
                Specifically, the order requires that:
            </p>
        </div>
        <ul  style="padding-left: 15px">
            <li><p>a Fire Risk Assessment is undertaken identifying any possible dangers and risks.</p></li>
            <li><p>consideration is given to who may be especially at risk.</p></li>
            <li><p>fire risk is removed or reduced as far as is reasonably possible.</p></li>
            <li><p>measures are taken to make sure there is protection if flammable or explosive materials are used or stored.</p></li>
            <li><p>a plan is created to deal with an emergency, record any findings and review where necessary or on a periodic basis.</p></li>
        </ul>
        <h3 class="mt30">Fire Risk Assessment</h3>
        <div class="mt30">
            <p>
                A Fire Risk Assessment is an organised and methodical look at your premises, the activities undertaken, and the likelihood that a fire
                could start and cause harm to those in and around the premises. It should be available for inspection or validation by any authorised
                person at all times.
            </p>
        </div>
        <div class="mt30">
            <p>
                Normally a Fire Risk Assessment does not need to involve destructive exposure and inspection of the building, however this may
                be necessary where there is justifiable concern regarding structural fire precautions and in such circumstances it is recommended
                that external specialist advice is sought. During this Assessment however, where practicable, it may be appropriate to lift a sample of
                accessible false ceiling tiles, or to open a sample of service risers.
            </p>
        </div>
        <h3 class="mt30">Aims</h3>
        <div class="mt30">
            <p>
                The aims of the fire risk assessment are to:
            </p>
        </div>
        <ul  style="padding-left: 15px">
            <li><p>identify fire hazards.</p></li>
            <li><p>reduce the risk of those hazards causing harm (to as low as reasonably practicable).</p></li>
            <li><p>decide what precautions and management arrangements are necessary to ensure the safety of all occupants.</p></li>
        </ul>
        <div class="mt30">
            <p>
                The fire precautions that must be provided (and maintained) are those required to reasonably protect relevant persons from risks to
                themselves in the event of a fire. This will be determined by the findings contained within the assessment. The term Hazard refers to
                anything that has the potential to cause harm whilst Risk denotes the chance of that harm occurring.
            </p>
        </div>
        <h3 class="mt30">Specification</h3>
        <div class="mt30">
            <p>
                The assessment method employed shares many similarities with that used in general health and safety and has been designed taking
                into account the requirements of and with reference the FSO, relevant British Standards (such as BS 9999:2008), and leading guidance
                in the area of fire safety such as PAS 79:2006, LACORS guidance and the DCLG’s Fire Safety Risk Assessment publications.
            </p>
        </div>
        <div class="mt30">
            <p>
                The assessment itself is conducted by way of a thorough and methodical visual inspection, the appraisal of relevant documented
                procedures and records and via consultation with appropriate stakeholders (where appropriate).
            </p>
        </div>
        <h3 class="mt30">Responsible Person(s)</h3>
        <div class="mt30">
            <p>
                The responsible person is a legally designated individual who has control, or a degree of control, over premises (or certain areas within)
                or fire-prevention systems within these premises. Although this will usually be obvious, in shared premises or larger businesses, several
                people may share this responsibility.
            </p>
        </div>
    </div>
</div>

<div style="page-break-before: always;"></div>
<div style="margin-top:30px;">
    <div class="textdecoration alignment-title">
        <p class="alignment-text">10.0 Risk Assessment Information</p>
    </div>
    <div style="margin-top:30px"></div>
    <div class="mt30">
        <p>It is the responsibility of the responsible person(s) to ensure that a suitable risk assessment is appropriately conducted and that everyone
            who uses the premises can escape safely in the event of a fire.
        </p>
    </div>
    <div class="mt30">
        <p>
            In accordance with the RRO, it is also the duty of the recognised responsible person(s) to provide the relevant individuals (particularly
            those most at risk) with comprehensive and relevant information, instruction and/or training, on the risks to them which have been
            identified as a result of this Fire Risk Assessment, together with any preventative and protective measures that are in place or are to be
            implemented, to deal with any such danger.
        </p>
    </div>
    <h3 class="mt30">Overall Fire Risk Level</h3>
    <div class="mt30">
        <p>
            Assessing the fire risk level is based on two subjective measurements which are to be determined following an assessment of the
            property by a fire risk assessor:
        </p>
    </div>
    <h3 class="mt30">Hazard Potential</h3>
    <div class="mt30">
        <p>
            Calculated taking into account the fire prevention measures observed during the risk assessment and determining whether it is
            considered the potential of a fire outbreak at the premises in question is either:
        </p>
    </div>
    <h3 class="mt30">Likelihood of Harm</h3>
    <div class="mt30">
        <p>
            Calculated taking into account the nature of the building & occupants, as well as fire protection and procedural arrangements observed
            and determining whether it is considered the consequences for fire safety in the event of fire outbreak would be classed as either:
        </p>
    </div>
    <div class="tableGray tableItems ">
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:100%">
            <tr>
                <th style="text-align: center!important;padding-left: 10px"width="40%">Hazard Potential</th>
                <th style="text-align: center!important;padding-left: 10px"width="20%"> Value </th>
                <th style="text-align: center!important;padding-left: 10px"width="40%">Likelihood of Harm </th>
            </tr>
            <tr>
                <td style="padding-left: 10px"> Very Low Risk</td>
                <td style="text-align: center!important;background-color: #f2e600;color: black!important;"> 01</td>
                <td style="padding-left: 10px"> Very Low Risk</td>
            </tr>
            <tr>
                <td style="padding-left: 10px"> Low Risk</td>
                <td style="text-align: center!important;background-color: #f6a316;color: white!important;"> 02</td>
                <td style="padding-left: 10px"> Low Risk</td>
            </tr>
            <tr>
                <td style="padding-left: 10px"> Medium Risk</td>
                <td style="text-align: center!important;background-color: #aa7f47;color: white!important;"> 03</td>
                <td style="padding-left: 10px"> Medium Risk</td>
            </tr>
            <tr>
                <td style="padding-left: 10px"> High Risk</td>
                <td style="text-align: center!important;background-color: #e30613;color: white!important;"> 04</td>
                <td style="padding-left: 10px"> High Risk</td>
            </tr>
            <tr>
                <td style="padding-left: 10px"> Very High Risk</td>
                <td style="text-align: center!important;background-color: #8a1002;color: white!important;"> 05</td>
                <td style="padding-left: 10px"> Very High Risk</td>
            </tr>
        </table>
    </div>
    <div class="mt30">
        <p>
            The Fire Risk Assessment methodology adopted below provides a transparent means of combining the likelihood and potential
            consequences of legionella to derive an Overall Risk Level which is expressed in terms of one of five predetermined categories. This
            risk-based assessment is to help ensure that there is effort and urgency assigned to a risk is proportional.
        </p>
    </div>
    <div class="tableGray tableItems ">
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:100%">
            <tr>
                <th rowspan="2" style="text-align: center!important;"width="35%">Hazard Potential</th>
                <th style="text-align: center!important;"colspan="5">
                    Likelihood of Harm
                </th>
            </tr>
            <tr>
                <td style="padding-left: 10px"> Very Low Risk (1)</td>
                <td style="padding-left: 10px">Low Risk (2)</td>
                <td style="padding-left: 10px"> Medium (3)</td>
                <td style="padding-left: 10px"> High Risk (4)</td>
                <td style="padding-left: 10px"> Very High Risk (5)</td>
            </tr>
            <tr>
                <td style="padding-left: 10px"> Very Low Risk (1)</td>
                <td style="text-align: center!important;background-color: #f2e600;color: black!important;"> 01</td>
                <td style="text-align: center!important;background-color: #f2e600;color: black!important;"> 02</td>
                <td style="text-align: center!important;background-color: #f2e600;color: black!important;"> 03</td>
                <td style="text-align: center!important;background-color: #f6a316;color: white!important;"> 04</td>
                <td style="text-align: center!important;background-color: #f6a316;color: white!important;"> 05</td>
            </tr>
            <tr>
                <td style="padding-left: 10px">Low Risk (2)</td>
                <td style="text-align: center!important;background-color: #f2e600;color: black!important;"> 02</td>
                <td style="text-align: center!important;background-color: #f6a316;color: white!important;"> 04</td>
                <td style="text-align: center!important;background-color: #f6a316;color: white!important;"> 06</td>
                <td style="text-align: center!important;background-color: #f6a316;color: white!important;"> 08</td>
                <td style="text-align: center!important;background-color: #aa7f47;color: white!important;"> 10</td>
            </tr>
            <tr>
                <td style="padding-left: 10px"> Medium (3)</td>
                <td style="text-align: center!important;background-color: #f2e600;color: black!important;"> 03</td>
                <td style="text-align: center!important;background-color: #f6a316;color: white!important;"> 06</td>
                <td style="text-align: center!important;background-color: #f6a316;color: white!important;"> 09</td>
                <td style="text-align: center!important;background-color: #aa7f47;color: white!important;"> 12</td>
                <td style="text-align: center!important;background-color: #aa7f47;color: white!important;"> 15</td>
            </tr>
            <tr>
                <td style="padding-left: 10px"> High Risk (4)</td>
                <td style="text-align: center!important;background-color: #f6a316;color: white!important;"> 04</td>
                <td style="text-align: center!important;background-color: #f6a316;color: white!important;"> 08</td>
                <td style="text-align: center!important;background-color: #aa7f47;color: white!important;"> 12</td>
                <td style="text-align: center!important;background-color: #e30613;color: white!important;"> 16</td>
                <td style="text-align: center!important;background-color: #e30613;color: white!important;"> 20</td>
            </tr>
            <tr>
                <td style="padding-left: 10px"> Very High Risk (5)</td>
                <td style="text-align: center!important;background-color: #f6a316;color: white!important;"> 05</td>
                <td style="text-align: center!important;background-color: #aa7f47;color: white!important;"> 10</td>
                <td style="text-align: center!important;background-color: #aa7f47;color: white!important;"> 15</td>
                <td style="text-align: center!important;background-color: #e30613;color: white!important;"> 20</td>
                <td style="text-align: center!important;background-color: #8a1002;color: white!important;"> 25</td>
            </tr>
        </table>
    </div>
    <h3 class="mt30">Overall Fire Risk Descriptions</h3>
    <div class="tableGray tableItems">
        <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:70%;border-left: 0px white solid;"style="border: 0px" class="overall" >
            <tr>
                <td style="text-align: center!important;background-color: #8a1812;color: white!important;border: 5px white solid;border-left: 0px white!important;">25</td>
                <td style="text-align: left!important;background-color: #8a1812;color: white!important;border: 5px white solid!important;padding-left: 15px!important;" >
                    Very High Risk - Immediate Action Required
                </td>
            </tr>
            <tr>
                <td style="text-align: center!important;background-color: #e30b17;color: white!important;border: 5px white solid;border-left: 1px white!important;">16-20</td>
                <td style="text-align: left!important;background-color: #e30b17;color: white!important;border: 5px white solid!important;padding-left: 15px!important;" >
                    High Risk - Urgent - Action Required within 1 Month Assessment
                </td>
            </tr>
            <tr>
                <td style="text-align: center!important;background-color: #aa7f47;color: white!important;border: 5px white solid;border-left: 1px white!important;">10-15</td>
                <td style="text-align: left!important;background-color: #aa7f47;color: white!important;border: 5px white solid;border-left: 1px white!important;padding-left: 15px!important;">
                    Medium Risk - Action Required within 3 Months of Assessment
                </td>
            </tr>
            <tr>
                <td style="text-align: center!important;background-color: #f7a416;color: white!important;border: 5px white solid;border-left: 1px white!important;">04-09</td>
                <td style="text-align: left!important;background-color: #f7a416;color: white!important;border: 5px white solid!important;padding-left: 15px!important;" >
                    Low Risk - Action Required within 6 Months of Assessment
                </td>
            </tr>
            <tr>
                <td style="text-align: center!important;background-color: #f2e600;color: black!important;border: 5px white solid;border-left: 1px white!important;">01-03</td>
                <td style="text-align: left!important;background-color: #f2e600;color: black!important;border: 5px white solid!important;padding-left: 15px!important;" >
                    Very Low Risk - Action Required within 12 Months of Assessment
                </td>
            </tr>
        </table>
    </div>
</div>

</body>

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
    @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)
        @if($assessment->type == ASSESS_TYPE_WATER_RISK)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">10.0 Risk Assessment Information</p>
            </div>
        @else
            <div class="header-report alignment-title">
                <p class="alignment-text">9.0 Risk Assessment Information</p>
            </div>
        @endif
    @else
        @if($assessment->type == ASSESS_TYPE_WATER_RISK)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">11.0 Risk Assessment Information</p>
            </div>
        @else
            <div class="textdecoration alignment-title">
                <p class="alignment-text">10.0 Risk Assessment Information</p>
            </div>
        @endif
    @endif
    <div class="content">
        <div class="mt30">
            <p>
                Under the Health and Safety at Work Act 1974 and the Control of Substances Hazardous to Health 2002 Regulations all owners or
                operators of water systems have a responsibility to ensure that the risk from Legionella is controlled and kept to an acceptable level.
            </p>
        </div>

        <div class="mt30">
            <p>
                The Approved Code of Practice L8 provides guidance on how the above legislation can be complied with and states that “A suitable and
                sufficient assessment is required to identify and assess the risk of exposure to Legionella bacteria from work activities , water systems on
                the premises and any necessary precautionary measures” in addition the ACoP L8 states that “In conducting the assessment, the person
                on whom the statutory duty falls is required to have access to competent help to assess the risks of exposure to Legionella bacteria in
                the water systems present in the premises”.
            </p>
        </div>

        <div class="mt30">
            <p>
                According to the ACoP L8, the minimum requirement is that the risk assessments is reviewed every two years – more frequently if there
                have been major changes to the water system since the previous Legionella Risk Assessment.
            </p>
        </div>
        <div class="mt30">
            <p>
                As an employer or a person in control of premises, you must appoint someone competent to help you manage your health and safety
                duties, e.g. take responsibility for managing risks. A competent person is someone with the necessary skills, knowledge and experience
                to manage health and safety, including the control measures. You could appoint one, or a combination of:
            </p>
        </div>
        <ul  style="padding-left: 15px">
            <li>yourself</li>
            <li>one or more workers</li>
            <li>someone from outside your business</li>
        </ul>
        <div class="mt30">
            <p>
                If you decide to employ contractors to carry out your risk assessment or other work, it is still the responsibility of the competent person
                to ensure that the work is carried out to the required standards. Remember, before you employ a contractor, you should be satisfied that
                they can do the work you want to the standard that you require.
            </p>
        </div>
        <div class="mt30">
            <p>
                The purpose of carrying out a risk assessment is to identify any risks in your water system. The competent person should understand
                your water systems and any associated equipment, in order to conclude whether the system is likely to create a risk from exposure to
                legionella. It is important to identify whether:
            </p>
        </div>
        <ul style="padding-left: 15px">
            <li>water is stored or re-circulated as part of your system</li>
            <li>the water temperature in some or all parts of the system is between 20–45 °C</li>
            <li>there are sources of nutrients such as rust, sludge, scale and organic matters</li>
            <li>conditions are present to encourage bacteria to multiply</li>
            <li>it is possible for water droplets to be produced and, if so, whether they could be dispersed over a wide area, e.g. showers and aerosols from cooling towers</li>
        </ul>
        <ul style="margin-top: 30px;padding-left: 15px">
            <li>
                it is likely that any of your employees, residents, visitors etc. are more susceptible to infection due to age, illness, a weakened immune
                system etc. and whether they could be exposed to any contaminated water droplets
            </li>
        </ul>
        <div class="mt30">
            <p>
                Your risk assessment should include:
            </p>
        </div>
        <ul style="padding-left: 15px">
            <li>management responsibilities, including the name of competent person and a description of your system</li>
            <li>potential sources of risk</li>
            <li>any controls in place to control risk</li>
            <li>monitoring, inspection and maintenance procedures</li>
            <li>records of the monitoring results, inspections and checks carried out</li>
            <li>a review date</li>
        </ul>
        <div class="mt30">
            <p>
                If you decide that the risks are insignificant, your assessment is complete. You need take no further action other than to review the
                assessment periodically in case anything changes in your system. It is the responsibility of the responsible person(s) to ensure that a
                suitable risk assessment is appropriately conducted with the factors affecting the risk of exposure to Legionella bacteria and the likelihood
                of persons contracting Legionnaires.
            </p>
        </div>
        <div class="mt30">
            <p>
                Disease or other form of Legionellosis include:
            </p>
        </div>
        <div class="mt30">
            <p>
                The potential for seeding of the water system with Legionella bacteria. The potential for growth of Legionella bacteria within the water
                system. The ability of the water system to generate aerosols and the amount of exposure, the susceptibility of those persons exposed,
                the effectiveness of control measures and management arrangements in preventing or controlling these risks.
            </p>
        </div>
        <div class="mt30">
            <p>
                The above have been considered for each type of water system found at the site. Risk ratings have been allocated based on the
                information provided in the survey forms and a residual risk computed for each system by comparing the system risk (i.e. potential for
                seeding, re-growth and aerosol generation and exposure) and susceptibility of those likely to be exposed with the effectiveness of the
                existing control measures and management arrangements.
            </p>
        </div>
    </div>
</div>

<div style="page-break-before: always;"></div>
<div style="margin-top:30px;">
    @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)
        @if($assessment->type == ASSESS_TYPE_WATER_RISK)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">10.0 Risk Assessment Information</p>
            </div>
        @else
            <div class="header-report alignment-title">
                <p class="alignment-text">9.0 Risk Assessment Information</p>
            </div>
        @endif
    @else
        @if($assessment->type == ASSESS_TYPE_WATER_RISK)
            <div class="textdecoration alignment-title">
                <p class="alignment-text">11.0 Risk Assessment Information</p>
            </div>
        @else
            <div class="textdecoration alignment-title">
                <p class="alignment-text">10.0 Risk Assessment Information</p>
            </div>
        @endif
    @endif
    <div class="mt30">
        <p>The risk rating for susceptibility is given to reflect that of a normal working population. The Summary of Risk Assessment Ratings shows
            the values for each item and that of the overall assessment. Additionally, there is the Residual Risk that would be anticipated once any
            remedial actions have been implemented. Assessing the risk level is based on two subjective measurements which are to be determined
            following an assessment of the property by the risk assessor.
        </p>
    </div>
    <h3 class="mt30">Hazard Potential</h3>
    <div class="mt30">
        <p>
            Calculated taking into account the legionella prevention measures observed during the risk assessment and determining whether it is
            considered the potential of a legionella outbreak at the premises in question is either:
        </p>
    </div>
    <h3 class="mt30">Likelihood of Harm</h3>
    <div class="mt30">
        <p>
            Calculated taking into account the nature of the building & occupants, as well as water protection and procedural arrangements observed
            and determining whether it is considered the consequences for life safety in the event of legionella outbreak would be classed as either:
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
            The Water Risk Assessment methodology adopted below provides a transparent means of combining the likelihood and potential
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
    <h3 class="mt30">Overall Water Risk Descriptions</h3>
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
                <td style="text-align: center!important;background-color: #ad8049;color: white!important;border: 5px white solid;border-left: 1px white!important;">10-15</td>
                <td style="text-align: left!important;background-color: #ad8049;color: white!important;border: 5px white solid!important;padding-left: 15px!important;" >
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

<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $assessment->reference ?? ''}}</title>
    <style type="text/css">
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 10pt;
            color: #575756;
        }

        .mt30{
            margin-left: 20px;
            margin-top: 5px!important;
            color: #575756;
            font-size: 10pt;
            line-height: 1.6;
            text-align: left;
            word-spacing: 1.0px;
        }
        .blue-text{color: #092591}
        .page {
            margin-top: 30px;
        }
        .content{
            margin:20px 0px;
        }
        .content-title{
            margin-bottom: 20px;
        }
        /*<tablecover>*/
        .titletable{
            color:black;
            margin: 30px 0px;
        }
        .tableCover table, td, th {
            border: 1px solid black;
        }
        .tableCover table {
            color: black;
            border-collapse: collapse;
            width: 100%;
        }

        .tableCover th {
            background-color: #e6fbbb;
            height: 50px;
        }
        .tableCover tr {
            height: 30px;
        }
        .tableCover td {
            padding-left: 20px;
            padding-right: 20px;
        }
        .textES{
            /*font-size: 15px;*/
            padding-left: 20px;
        }
        /*EndES*/
        /*tableItems*/
        .titletableItems{
            color: black;
            margin: 20px 50px;
        }
        .tableItems table{
            margin-top: 0px;
            width: 98%;
            margin-left:20px;
        }
        .tableItems th {
            text-align: left;
            padding-left: 10px;
            font-size: 14px;
            color: #ffffff;
            height: 10px;
        }
        #red tr th{
            background-color: #ff0000;
        }
        .tableItems tr {
            height: 10px;
        }
        .tableItems td {
            padding-left: 20px;
            padding-right: 20px;
        }
        #tableYellow tr th{
            background-color: #ffc000;
        }
        #tableGray tr th {
            background-color: #92d050;
        }
        .tableBlue th {
            background-color: #0070c0;
        }
        #tableYesNo th {
            text-align: left;
            padding-left: 10px;
            color: black;
            background-color: #e6fbbb;
            height: 50px;
        }
        #tableYesNo td span{
            background-color: #ffff00;
        }
        .colspan{
            height: 120px;
            position: relative;
        }
        #tableYesNo tr th{
            height: 150px;
        }
        .tableAR tr td{
            word-break: break-word;
        }
        .tableAR tr th {
            font-size: 14px;
            text-align: left;
            padding-left: 10px;
            color: black;
            background-color: #e6fbbb;
            height: 50px;
        }
        /*EndtableItems*/

        .titleAppendix{
            color: black;
            font-size: 18px;
            padding-left: 20px;
        }
        .titleAppendix1{
            color: black;
            font-size: 18px;
            padding-left: 45px;
        }
        .textdecoration{
            margin-left: 20px;
            margin-top: 80px;
        }
        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }
        .tableItems tr td{
            word-break: break-word;
        }

        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }
        .page {
            margin-top:80px;
            padding: 10px 0px 10px 0px;
            font-size: 12pt;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
        }
        .paragraph{
            margin-left: 30px;
            margin-top: 50px;
            color: black;
            /*font-size: 12pt;*/
            line-height: 1.8;
            text-align: left;
            word-spacing: 1.5px;
        }
        .text-indent{
            margin-top: 50px;
            margin-bottom: 50px;
            margin-left: 30px;
            word-spacing: 1.5px;
        }
        .paragraphdown{
            margin-top: 50px;
            color: black;
            /*font-size: 12pt;*/
            line-height: 1.8;
            text-align: left;
            word-spacing: 1.5px;
        }
        .text-indentdown{
            margin-top: 10px;
            margin-bottom: 50px;
            text-indent: 50px;
        }
        .space{
            margin-right: 50px;
        }
        .italic{
            margin-top: 50px;
            color: black;
            font-style: italic;
            margin-left: 60px;
            /*font-size: 10pt;*/
            line-height: 1.8;
            text-align: left;
            word-spacing: 1px;
        }

        table, td, th {
            border: 1px solid black;
        }

        table {
            color: black;
            margin-bottom: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #e6fbbb;
        }
        table tr td{
            font-size: 12px;
            padding-left: 15px;
            padding-right: 15px;
            line-height: 1.5;
            word-spacing: 1px;
        }
        .contentAp{
            margin-left: 30px;
        }

        .tableAp3 tr td {
            text-align: left;
            padding-left: 10px;
        }
        .tableAp3 tr th {
            font-size: 14px;
            text-align: left;
            padding-left: 10px;
        }
        .text-content{
            margin-top: 30px;
            margin-bottom: 30px;
            margin-left: 60px;
        }
        .text-title{
            margin-bottom: 10px;
            margin-left: 30px;
        }
        .contents-section{
            margin-left: 50px;
            margin-bottom: 25px;
        }

        .title{
            padding:0px 30px;
            text-align: center;
            /*border: 3px solid green;*/
            background-color: #e6fbbb;
            line-height: 1.8;
            font-size: 17px;
            margin-bottom: 30px;
        }
        .contents{
            margin: 50px 0px;
        }
        .appendix{
            margin-bottom: 30px;
        }
        .titleAppendixTitle{
            padding-left: 50px;
        }
        h2{
            font-size: 20px;
        }
        h3{
            font-size: 15px;
        }
        th{
            font-size: 12px!important;
            font-weight: normal;
        }
        .tableGray tr th {
            background-color: #dadada!important;
            color: #575756;
        }
        .tableGray tr td {
            color: #575756;
            padding-left: 10px;
        }
        .unset-border td {
            border: 0!important;
        }
        table.unset-border{
            color: #575756;
            border: 0!important;
            margin-left: 5px
        }
        p{
            font-size: 10pt!important;
            text-align: justify!important;
        }
        .alignment-title{
            background-color: #706f6f;
            color: white;
            width: 100%;
            height: 40px;
        }
        .alignment-text{
            padding-top: 13px;
            padding-left: 30px;
            font-size: 16px!important;
        }
        .system th,td{
                     text-align: left;
                     padding-left: 10px;
                 }
    </style>
</head>
<body>
<div style="page-break-before: always;"></div>
@php
    $propertyInfo = json_decode($assessment->assessmentInfo->property_information ?? '', true);
@endphp

<div style="page-break-before: always;"></div>
<div id="asbestos-risk-items" style="margin-top:30px;">
    @if(count($assessment->fireExits) == 0 &&  count($assessment->assemblyPoints) == 0 && count($assessment->vehicleParking) == 0)
        <div class="textdecoration alignment-title">
            <p class="alignment-text"> 2.0 Executive Summary</p>
        </div>
    @else
        <div class="textdecoration alignment-title">
            <p class="alignment-text"> 3.0 Executive Summary</p>
        </div>
    @endif
    <div class="content">
        <h3 class="mt30 mb30"><strong>Introduction</strong></h3>
        <p class="mt30 mb30">
            {{$assessment->assessor->clients->name ?? ''}} was instructed by {{$assessment->property->clients->mainUser->full_name ?? ''}} of {{$assessment->property->clients->name ?? ''}}, to provide a Legionella Risk Assessment of the hot and cold
            water systems at {{ $assessment->property->name ?? ''}} to meet the requirements of the HSE ACoP L8 ‘The Control of Legionella Bacteria in Water Systems’.
        </p>
        <h3 class="mt30 mb30"><strong>Legislation</strong></h3>
        <p class="mt30">
            The Health & Safety at Work Act 1974 (HSWA) and Control of Substances Hazardous to Health Regulations 2002 (amended) (COSHH)
            include for the risks from hazardous micro-organisms, including legionella, Under the Regulations Risk Assessments and the adoption of
            appropriate precautions are required to be made. Furthermore, the Approved Code of Practice and Guidance L8 (Legionnaires’ disease.
            The control of legionella bacteria in water systems), (the ACoP), sets out statutory requirements for dealing with such risks. The ACoP
            applies to the risk from legionella bacteria in any circumstances where the HSWA applies. In order to comply with their legal duties, as
            detailed in the ACoP, employers and those with responsibility for the control premises should:
        </p>
        <ul style="padding-left: 35px;margin: 20px 0px" >
            <li>Identify and assess source of risk</li>
            <li>Identify and assess source of risk</li>
            <li>Carry out a suitable and sufficient risk assessment</li>
            <li>Prepare a scheme for preventing or controlling the risk</li>
            <li>Implement, manage and monitor precautions</li>
            <li>Keep records of precautions</li>
            <li>Appoint a person to be managerially responsible</li>
        </ul>
        <h3 class="mt30 mb30"><strong>Scope of Assessment</strong></h3>
        <p class="mt30 mb30">
            All the accessible areas of the site were assessed, water assets have been identified, photographed, risk scored and numbered so future
            control measures can be implemented, An asset register and basic schematic are included within the report.
        </p>
        <h3 class="mt30 mb30"><strong>Exclusions</strong></h3>
        <p class="mt30 mb30">
            The assessment has been undertaken on a non-destructive and non-intrusive basis, and is limited to those items in plain sight that may
            be safely accessed. Whilst all efforts have been made to identify any potential dead legs associated with the systems assessed therefore,
            the complex nature of pipework installations, much of which may be hidden within the building, prevents this from being a fully complete
            and accurate list. For the same reasons, it is neither practical nor possible to assess all materials used in the construction of complex
            multi-component systems such as those covered by this document. It should therefore be noted that not all material present can or have
            been assessed for their suitability of use. {{$assessment->assessor->clients->name ?? ''}} cannot be accountable for any omissions to this report resulting from the
            information, data, systems or plant not made readily and reasonably accessible by {{$assessment->property->clients->name ?? ''}}.
        </p>
        <p class="mt30 mb30">
            The scope of work excludes a formal written scheme for preventing risk. It also excludes undertaking an evaluation (practical or financial)
            of the feasibility of the removal or replacement of any plant or equipment identified as presenting a reasonably foreseeable risk of
            causing legionellosis. Some or all of these actions may be necessary upon completion of this Risk Assessment. Please note that this
            Risk Assessment only addresses one of many requirements of the ACoP L8 and is therefore not alone sufficient to ensure complete
            compliance with the law.
        </p>
        <h3 class="mt30 mb30"><strong>Limitations on Access</strong></h3>
        <p class="mt30 mb30">
            During the course of the assessment, every effort was made to gain access to all areas within the agreed scope. However, where it has
            not been possible to gain suitable and sufficient access due to specific circumstances, these areas were escalated at the time of the site
            visit. Where assess could not be made a no-access pro forma has been completed and signed by the site representative.
        </p>
        <h3 class="mt30 mb30"><strong>Assessor Competence</strong></h3>
        <p class="mt30 mb30">
            The {{$assessment->assessor->clients->name ?? ''}} Water Risk Assessor meets the training and experience of a competent person as defined in the Approved Code
            of Practice and Guidance L8. The Legionella Risk Assessor participate in the {{$assessment->assessor->clients->name ?? ''}} Audit Scheme and maintain satisfactory
            performance.
        </p>
        <h3 class="mt30 mb30"><strong>Sub Contract</strong></h3>
        <p class="mt30 mb30">
            All associated services are undertaken by {{$assessment->assessor->clients->name ?? ''}} staff. Where water analysis samples are taken, thee are sub contracted to
            suitable UKAS accredited laboratories for analysis.
        </p>
        <h3 class="mt30 mb30"><strong>Advice</strong></h3>
        <p class="mt30 mb30">
            {{$assessment->assessor->clients->name ?? ''}} can provide advice on this matter should it be necessary. In the first instance please contact {{$assessment->assessor->clients->name ?? ''}} on
            {{$assessment->assessor->clients->clientAddress->telephone ?? ''}} or by email at {{$assessment->assessor->clients->email ?? ''}} and we will be pleased to help.
        </p>
    </div>
</div>
</body>

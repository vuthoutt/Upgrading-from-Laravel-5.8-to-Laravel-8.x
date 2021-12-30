@extends('emails.template')

@section('email_content')
<table cellspacing="0" border="0" cellpadding="0" width="100%">
    <tr>
        <td valign="middle" height="37" style="height: 37px; border-bottom-color: #d6d6d6; border-bottom-width: 1px; border-bottom-style: solid; vertical-align: middle;">
            <h2 style="margin: 0; padding: 0; font-size: 16px; font-weight: bold; color: #1F497D; text-align: left;">Survey Approved Duplicate Data Warning</h2>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <p class="paragraph marginBot20">
                Dear User,
            </p>
            <p class="paragraph marginBot20">
                This is a courtesy email from {{ env('APP_DOMAIN') ?? 'GSK' }} to inform you that duplicate entry has been created on :
            </p>
            <p class="paragraph marginBot20">
                {{ $survey->property->name ?? '' }}
            </p>
            <p class="paragraph marginBot20">
                {{ $survey->reference ?? '' }}
            </p>
            <p class="paragraph marginBot20">
                The duplicate created
                <br>
                @if(count($areas) > 0)
                     Area/floor: @foreach($areas as $area)
                        {{ $area->area_reference ?? '' }} {{ $area->description ?? '' }}
                        @if(!$loop->last)
                             ,
                        @endif
                    @endforeach
                    <br>
                @endif
                @if(count($locations) > 0)
                    Room/location: @foreach($locations as $location)
                        {{ $location->location_reference ?? '' }} {{ $location->description ?? '' }}
                        @if(!$loop->last)
                             ,
                        @endif
                    @endforeach
                @endif
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

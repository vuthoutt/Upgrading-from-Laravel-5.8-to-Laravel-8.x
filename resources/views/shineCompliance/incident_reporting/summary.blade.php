 <div class="row offset-top40">
    <div class="col-md-12">
        @include('shineCompliance.forms.form_text',['title' => 'H&S Lead:', 'data' => isset($incident->asbestos_lead) ? \CommonHelpers::getUserFullname($incident->asbestos_lead) : '',
                                                    'link' => route('shineCompliance.profile-shineCompliance', $incident->asbestos_lead ?? 0)])
        @include('shineCompliance.forms.form_text',['title' => 'Second H&S Lead:', 'data' => isset($incident->second_asbestos_lead) ? \CommonHelpers::getUserFullname($incident->second_asbestos_lead) : '',
                                                    'link' => route('shineCompliance.profile-shineCompliance', $incident->second_asbestos_lead ?? 0)])
        @include('shineCompliance.forms.form_text',['title' => 'Report Recorder:', 'data' => $incident->reportRecorder->full_name ?? '',
                                                    'link' => route('shineCompliance.profile-shineCompliance', $incident->reportRecorder->id ?? 0)])
        @if ($incident->reportRecorder->is_call_centre_staff)
            @include('shineCompliance.forms.form_text',['title' => 'Call Centre Team Member Name:', 'data' => $incident->call_centre_team_member_name ?? ''])
        @endif
        @include('shineCompliance.forms.form_text',['title' => 'Report Form Type:', 'data' => $incident->type_display ?? '' ])
        @include('shineCompliance.forms.form_text',['title' => 'Date of Report:', 'data' => $incident->report_date ?? '' ])
        @include('shineCompliance.forms.form_text',['title' => 'Time of Report:', 'data' => $incident->time_of_report ?? ''])
        @if($incident->type == INCIDENT || $incident->type == SOCIAL_CARE)
            @include('shineCompliance.forms.form_text',['title' => 'Date of Incident:', 'data' => $incident->date_of_incident ?? '' ])
            @include('shineCompliance.forms.form_text',['title' => 'Time of Incident:', 'data' => $incident->time_of_incident ?? ''])
        @endif
        @include('shineCompliance.forms.form_text',['title' => 'Reported By:', 'data' =>  $incident->reportedUser->full_name ?? '',
                                                    'link' => route('shineCompliance.profile-shineCompliance', $incident->reportedUser->id ?? 0)])
        @if($incident->is_address_in_wcc)
            @include('shineCompliance.forms.form_text',['title' => 'Address of Incident:', 'data' => $incident->property->name ?? '',
                                                        'link' => route('shineCompliance.property.property_detail', $incident->property->id ?? 0)])
        @else
            @include('shineCompliance.forms.form_text',['title' => 'Address of Incident:', 'data' => $incident->address_of_incident ?? ''])
        @endif
        @include('shineCompliance.forms.form_text',['title' => 'Equipment:', 'data' => $incident->equipment->name ?? '',
                                                    'link' => route('shineCompliance.register_equipment.detail', $incident->equipment->id ?? 0) ])
        @include('shineCompliance.forms.form_text',['title' => 'System:', 'data' => $incident->system->name ?? '',
                                                    'link' => route('shineCompliance.systems.detail', $incident->system->id ?? 0)])
        @if($incident->type == EQUIPMENT_NONCONFORMITY)
            @include('shineCompliance.forms.form_text',['title' => 'Nonconformity Details:', 'data' => $incident->details ?? '' ])
        @elseif($incident->type == IDENTIFIED_HAZARD)
            @include('shineCompliance.forms.form_text',['title' => 'Hazard Details:', 'data' => $incident->details ?? '' ])
        @elseif($incident->type == INCIDENT)
            @include('shineCompliance.forms.form_text',['title' => 'Incident Details:', 'data' => $incident->details ?? '' ])
        @elseif($incident->type == SOCIAL_CARE)
            @include('shineCompliance.forms.form_text',['title' => 'Social Care Details:', 'data' => $incident->details ?? '' ])
        @endif
        @include('shineCompliance.forms.form_text',['title' => 'Category of Works (Contractor Work Only):', 'data' => $incident->categoryOfWorkType->description ?? '' ])
        @include('shineCompliance.forms.form_text',['title' => 'Was there a Risk Assessment for this Work:', 'data' => $incident->is_risk_assessment ? 'Yes' : 'No' ])
        @if($incident->type == INCIDENT || $incident->type == SOCIAL_CARE)
            @include('shineCompliance.forms.form_text',['title' => 'Was there any person(s) involved:', 'data' => $incident->is_involved ? 'Yes' : 'No' ])
            @include('shineCompliance.forms.form_text',['title' => 'Confidential:', 'data' => $incident->confidential ? 'Yes' : 'No' ])
            @include('shineCompliance.forms.form_text',['title' => 'Who was Involved:', 'data' => '' ])
        @endif
    </div>
    @if($incident->type == INCIDENT || $incident->type == SOCIAL_CARE)
        @foreach($incident->involvedPersons as $person)
            @include('shineCompliance.tables.person_involved', [
                'title' => 'Person Involved - ' . ($person->user->full_name ?? $person->non_user ?? ''),
                'data' => $person,
                'status' => 1,
                'tableId' => 'person_involved_' . $person->id,
                'collapsed' => false,
                'plus_link' => false,
                'normalTable' => true,
                'row_col' => 'col-md-12',
                'notCountable' => true,
                'order_table' => 'published'
            ])
         @endforeach
    @endif
</div>

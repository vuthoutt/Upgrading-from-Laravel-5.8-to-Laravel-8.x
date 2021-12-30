 <div class="row offset-top40">
    @if(!\CompliancePrivilegecheckPermission(OTHER_DETAILS_VIEW_PRIV) and \CommonHelpers::isSystemClient())
    @else
        <div class="col-md-12 client-image-show" >
            <div class="col-md-5 ">
                <img src="{{ CommonHelpers::getFile($property->id, PROPERTY_IMAGE) }}" class="image-signature">
            </div>
            <!-- Auto width -->
            <div class="col-md-12 offset-top20 row">
                <div class="col-md-1">
                    <a title="Download Asbestos Register Image" href="{{ route('retrive_image',['type'=>  PROPERTY_IMAGE ,'id'=> $property->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
                </div>
                <div class="col-md-11 row"  style="display: flex;">
                    @if($property->decommissioned != PROPERTY_UNDECOMMISSION)
                        <div class="spanWarningDemolished">
                        <strong><em>
                            {{ $property->decommissionedReason->description ?? "Property No Longer under Management" }}
                        </em></strong>
                        </div>
                    @else
                        <!-- option selected in dropdown -->
                        @if(isset($property->propertyType) && !$property->propertyType->isEmpty())
                            @foreach($property->propertyType->sortBy('color') as $p_risk_type)
                                <div class="{{ CommonHelpers::getPropertyRiskTypeClass($p_risk_type->id) }} mr-2 mb-2">
                                    <strong>
                                        <em> {{ $p_risk_type->description }} </em>
                                    </strong>
                                </div>
                            @endforeach
                        @endif
                        <!-- automatic message warning -->
                        @if($warning_message)
                            @php  $texts = (explode(" with ",$warning_message)); @endphp
                            @foreach($texts as $text)
                                <div class="spanWarningSurveying mr-2 mb-2">
                                    <strong>
                                        <em> {{ $text }} </em>
                                    </strong>
                                </div>
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @if(!\CompliancePrivilegecheckPermission(PDF_ASBESTOS_REGISTER_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <div class="row-fluid offset-top20">
                    <strong>Asbestos Register: </strong>
                    <a href="{{route('view_property_register_pdf',['type'=>PROPERTY_REGISTER_PDF,'id'=>$property->id])}}" target="_blank"><img src="{{ asset('img/pdf-green.png') }}" width="19" height="19" alt="View PDF Asbestos Register" class="fileicon"/></a>
                    <a title="Download Asbestos Register PDF" href="{{route('register.pdf',['type'=>PROPERTY_REGISTER_PDF,'id'=>$property->id])}}"  class="btn download-btn" style="margin-left: 5px"><i class="fa fa-download"></i></a>
                </div>
                @if(!$risk_type_two)
                    <span class="red_text">
                        <ul>
                            <li>The Asbestos Register has been formed to facilitate <strong>general occupation and routine maintenance</strong></li>
                            <li>The Asbestos Register defines the Asbestos Containing Materials (ACMs) that were found by the survey inspection process; including negatively identified sampled materials</li>
                            <li>The Asbestos Register must be understood by the Site Manager and made available to all of those with the potential to access the ACMs</li>
                            <li>The limitations and restrictions are detailed within the survey document and should also be understood. Where any works have the potential to exceed the scope of the Site Management Survey further inspection will be necessary</li>
                            <li>A refurbishment or demolition survey must be carried out prior to any intrusive works being conducted on this site</li>
                            <li>Asbestos Department – Email - <a href="mailto:housingasbestos@Westminster.gov.uk" target="_top">housingasbestos@Westminster.gov.uk</a> or Mob: 07760 170273</li>
                        </ul>
                    </span>
                @endif
            @endif
        </div>
            <div class="col-md-6">
                @include('forms.form_text_property_detail',['title' => 'Property Reference:','width_label' => 5, 'data' => $property->property_reference ?? null])
                @include('forms.form_text_property_detail',['title' => 'Block Reference:','width_label' => 5, 'data' => $property->pblock ?? null])
                @include('forms.form_text_property_detail',['title' => 'Core Reference:','width_label' => 5, 'data' => $property->core_code ?? null])
                @include('forms.form_text_property_detail',['title' => 'Parent Reference:','width_label' => 5, 'data' => $parent_property->name ?? null,'link' => route('property_detail',['id' => $parent_property->id ?? '','section' => SECTION_DEFAULT])])
                @include('forms.form_text_property_detail',['title' => (env('APP_DOMAIN') == 'LBHC') ? 'Housing Area:' : 'Service Area:','width_label' => 5, 'data' => $property->serviceArea->description ?? null])
                @if(env('APP_DOMAIN') == 'LBHC')
                    @include('forms.form_text_property_detail',['title' => 'Ward:','width_label' => 5, 'data' => $property->ward->description ?? null])
                @endif
                @include('forms.form_text_property_detail',['title' => 'Estate Code:','width_label' => 5, 'data' => $property->estate_code ?? null])
                @include('forms.form_text_property_detail',['title' => 'Property Name:','width_label' => 5, 'data' => $property->name ?? null])
                @include('forms.form_text_property_detail',['title' => 'Shine Reference:','width_label' => 5, 'data' => $property->reference ?? null])
                @include('forms.form_text_property_detail',['title' => 'Property Group:','width_label' => 5, 'data' => $property->zone->zone_name ?? null])
                @include('forms.form_text_property_detail',['title' => 'Property Risk Type:','width_label' => 5, 'data' => $property->risk_type_text ?? null ])
                @include('forms.form_text_property_detail',['title' => 'Asset Class:','width_label' => 5, 'data' => $property->assetClass->description ?? null ])
                @include('forms.form_text_property_detail',['title' => 'Asset Type:','width_label' => 5, 'data' => $property->assetType->description ?? null ])
                @include('forms.form_text_property_detail',['title' => 'Tenure Type:','width_label' => 5, 'data' => $property->tenureType->description ?? null ])
                @include('forms.form_text_property_detail',['title' => 'Property Access Type:','width_label' => 5, 'data' => $property->propertySurvey->propertyProgrammeType->description ?? null ])
                @include('forms.form_text_property_detail',['title' => 'Communal Area:','width_label' => 5, 'data' => $property->communalArea->description ?? null ])
                @include('forms.form_text_property_detail',['title' => 'Responsibility:','width_label' => 5, 'data' => $property->responsibility->description ?? null ])
                @include('forms.form_text_property_detail',['title' => 'Property Client:','width_label' => 5, 'data' => $property->clients->name ?? null])
                @include('forms.form_text_property_detail',['title' => 'Flat Number:','width_label' => 5, 'data' => $property->propertyInfo->flat_number ?? null])
                @include('forms.form_text_property_detail',['title' => 'Building Name:','width_label' => 5, 'data' => $property->propertyInfo->building_name ?? null])
                @include('forms.form_text_property_detail',['title' => 'Street Number:','width_label' => 5, 'data' => $property->propertyInfo->street_number ?? null])
                @include('forms.form_text_property_detail',['title' => 'Street Name:','width_label' => 5, 'data' => $property->propertyInfo->street_name ?? null])
                @include('forms.form_text_property_detail',['title' => 'Town:','width_label' => 5, 'data' => $property->propertyInfo->town ?? null])
                @include('forms.form_text_property_detail',['title' => 'County:','width_label' => 5, 'data' => $property->propertyInfo->address5 ?? null])
                @include('forms.form_text_property_detail',['title' => 'Postcode:','width_label' => 5, 'data' => $property->propertyInfo->postcode ?? null])
                @include('forms.form_text_property_detail',['title' => 'Telephone:','width_label' => 5, 'data' => $property->propertyInfo->telephone ?? null])
                @include('forms.form_text_property_detail',['title' => 'Mobile:','width_label' => 5, 'data' => $property->propertyInfo->mobile ?? null])
                @include('forms.form_text_property_detail',['title' => 'Email:','width_label' => 5, 'data' => $property->propertyInfo->email ?? null])
                @include('forms.form_text_property_detail',['title' => 'App Contact:','width_label' => 5, 'data' => CommonHelpers::getUserFullname($property->propertyInfo->app_contact ?? null), 'link' => route('profile', ['id' => $property->propertyInfo->app_contact ?? 0])])
            </div>
            <div class="col-md-6">
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'Primary:', 'data' =>
                    CommonHelpers::getProgrammeType( $property->propertySurvey->asset_use_primary  ?? null,  $property->propertySurvey->asset_use_primary_other ?? null, 'primary' )
                ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'Secondary:',
                    'data' => CommonHelpers::getProgrammeType( $property->propertySurvey->asset_use_secondary ?? null,  $property->propertySurvey->asset_use_secondary_other ?? null, 'primary' )
                 ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'Age:',
                    'data' => $property->propertySurvey->construction_age ?? null
                 ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'Construction Type:',
                    'data' => $property->propertySurvey->construction_type ?? null
                 ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'No. Floors:',
                    'data' => \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_floors ?? null, $property->propertySurvey->size_floors_other ?? null)
                 ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'No. Staircases:',
                    'data' => \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_staircases ?? null, $property->propertySurvey->size_staircases_other ?? null)
                 ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'No. Lifts:',
                    'data' => \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_lifts ?? null, $property->propertySurvey->size_lifts_other ?? null)
                 ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'Electrical Meter:',
                    'data' => \CommonHelpers::getPropertyDropdownData($property->propertySurvey->electrical_meter ?? null)
                 ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'Gas Meter:',
                    'data' => \CommonHelpers::getPropertyDropdownData($property->propertySurvey->gas_meter ?? null)
                 ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'Loft Void:',
                    'data' => \CommonHelpers::getPropertyDropdownData($property->propertySurvey->loft_void ?? null)
                 ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'No. of Bedrooms:',
                    'data' => \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_bedrooms ?? null)
                 ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'Net Area per Floor:',
                    'data' => \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_net_area ?? null)
                 ])
                @include('forms.form_text_property_detail',['width_label' => 5,'title' => 'Gross Area:',
                    'data' => \CommonHelpers::getSurveyPropertyInfoText($property->propertySurvey->size_gross_area ?? null)
                    ])
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left" >
                        <span class="font-weight-bold">Linked Comments:</span>
                        <a href="" data-toggle="modal" data-target="#property_comment_history">(History)</a>
                    </label>
                    <div class="col-md-6 form-input-text" >
                        {!! nl2br($property->comments) ?? null !!}
                    </div>
                </div>
                @include('modals.comment_history',[ 'color' => 'red',
                                            'modal_id' => 'property_comment_history',
                                            'header' => 'Historical Property Comments',
                                            'table_id' => 'property_comment_history_table',
                                            'url' => route('comment.property'),
                                            'data' => $property->commentHistory,
                                            'id' => $property->id
                                            ])
            </div>

            @if(\CommonHelpers::isSystemClient() and $canBeUpdateThisSite)
                <div class="col-md-4 col-form-label text-md-left">
                     <a href="{{ route('property_edit',['property_id' => $property->id]) }}" style="text-decoration: none">
                         <button type="submit" class="btn light_grey_gradient ">
                             <strong>{{ __('Edit') }}</strong>
                         </button>
                     </a>
                    @if(\CommonHelpers::checkDecommissionPermission())
                        @if($property->decommissioned == PROPERTY_UNDECOMMISSION)
                        <a class="btn  light_grey_gradient" data-toggle="modal" data-target="#decommission_prop">
                            <strong>Decommission</strong>
                        </a>
                        @include('modals.decommission_property',[ 'color' => 'red',
                                                            'modal_id' => 'decommission_prop',
                                                            'header' => 'Decommission Property Warning',
                                                            'url' => route('property.decommission',['property_id' => $property->id]),
                                                            'data' => $decommissioned_reason_prop
                                                            ])
                        @else
                        <a href="{{ route('property.decommission',['property_id' => $property->id]) }}" style="text-decoration: none">
                            <button type="submit" class="btn light_grey_gradient ">
                                    <strong>{{ __('Recommission') }}</strong>
                            </button>
                        </a>
                        @endif
                    @endif
                 </div>
            @endif
        @endif
    @include('tables.sub_property', [
                'title' => 'Sub Properties',
                'tableId' => 'sub-properties',
                'collapsed' => false,
                'plus_link' => false,
                'data' => $child_property,
                'modal_id' => 'site-plan-add',
                'edit_permission' => (\CommonHelpers::isSystemClient() and \CompliancePrivilegecheckUpdatePermission(PROPERTY_PLAN_UPDATE_PRIV) and ($property->decommissioned == PROPERTY_UNDECOMMISSION) and $canBeUpdateThisSite) ? true : false,
                ])
    @if(!\CompliancePrivilegecheckPermission(PROPERTY_PLAN_VIEW_PRIV) and \CommonHelpers::isSystemClient())
    @else
        @include('tables.property_plans', [
            'title' => 'Property Plans',
            'tableId' => 'property-plan',
            'collapsed' => false,
            'plus_link' =>  (\CommonHelpers::isSystemClient() and \CompliancePrivilegecheckUpdatePermission(PROPERTY_PLAN_UPDATE_PRIV) and ($property->decommissioned == PROPERTY_UNDECOMMISSION) and $canBeUpdateThisSite) ? true : false,
            'data' => $property->sitePlanDocuments,
            'modal_id' => 'site-plan-add',
            'edit_permission' => (\CommonHelpers::isSystemClient() and \CompliancePrivilegecheckUpdatePermission(PROPERTY_PLAN_UPDATE_PRIV) and ($property->decommissioned == PROPERTY_UNDECOMMISSION) and $canBeUpdateThisSite) ? true : false,
            ])
        @include('modals.property_plan_add',['color' => 'red', 'modal_id' => 'site-plan-add', 'url' => route('ajax.property_plan'), 'data_site' => $property])
    @endif
    @include('tables.property_contacts', [
        'title' => 'Property Contacts',
        'tableId' => 'property-contact',
        'collapsed' => true,
        'plus_link' => false,
        'data' => $contactUser
        ])

 </div>

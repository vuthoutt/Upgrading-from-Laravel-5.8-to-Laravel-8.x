<?php
Route::get('/home', 'UserController@index')->name('home_shineCompliance')->middleware('auth');
Route::get('/assessment-user', 'UserController@assessmentUser')->name("assessment_user");

Route::group([
    'prefix'     => 'zone',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/all_zone', 'ZoneController@index')->name('zone');
    Route::get('/detail/{zone_id}', 'ZoneController@detail')->name('zone.details')->where('zone_id', '[0-9]+');
    Route::get('/listing_zones/{zone_id}', 'ZoneController@listMixedProperties')->name('zone.listing')->where('zone_id', '[0-9]+');
    Route::get('/{zone_id}/details', 'ZoneController@zoneDetail')->name('zone.zone_details')->where('zone_id', '[0-9]+');
    Route::get('/decommission/{zone_id}', 'ZoneController@decommissionZone')->name('zone.decommission');
    Route::get('/zone_map', 'ZoneController@zoneMapChild')->name('zone_map_child');
    Route::post('/update_or_create', 'ZoneController@updateOrCreateZone')->name('zone.update_or_create');
    // register summary
    Route::get('/{zone_id}/register', 'ZoneController@registerOverall')->name('zone.register');
    Route::get('/{zone_id}/gas-register', 'ZoneController@registerGas')->name('zone.gas');
    Route::get('/{zone_id}/water-register', 'ZoneController@registerWater')->name('zone.water');
    Route::get('/{zone_id}/fire-register', 'ZoneController@registerFire')->name('zone.fire');
    Route::get('/{zone_id}/asbestos-register', 'ZoneController@registerAsbestos')->name('zone.asbestos');
    Route::get('/{zone_id}/hs-register', 'ZoneController@registerHS')->name('zone.health_and_safety');
});

Route::group([
    'prefix'     => 'property',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/list', 'PropertyController@index')->name("property_list");
    Route::get('/property/plan', 'PropertyController@propertyPlan')->name("property_plan");
    Route::get('/sub_property/{id}', 'PropertyController@getSubProperty')->name("property.sub_property");
    Route::get('/detail/{id}', 'PropertyController@detail')->name("property.property_detail");
    Route::get('/edit/{property_id}', 'PropertyController@getEditProperty')->name("property.property_edit")->where('property_id', '[0-9]+');
    Route::post('/edit/{property_id}', 'PropertyController@postEditProperty')->name("property.post_edit");
    Route::get('/add/{zone_id}', 'PropertyController@getAddProperty')->name("property.get_add");
    Route::get('/sub_property/add/{zone_id}/{property_id}', 'PropertyController@getAddProperty')->name("property.get_add_sub_property");
    Route::post('/add', 'PropertyController@postAddProperty')->name("property.post_add");
    Route::get('/decommission/{property_id}', 'PropertyController@decommissionProperty')->name('property.decommission');

    // system
    Route::get('/{property_id}/systems', 'SystemController@index')->where('property_id', '[0-9]+')->name('systems.list');
    Route::get('/{property_id}/system-add', 'SystemController@getAddSystem')->where('property_id', '[0-9]+')->name('systems.get_add');
    Route::post('/{property_id}/system-add', 'SystemController@postAddSystem')->where('property_id', '[0-9]+')->name('systems.post_add');


    //document
    Route::get('/document/{property_id}', 'PropertyController@listDocument')->name("property.documents");
//    Route::get('/document/add/{property_id}{document_type}', 'PropertyController@getAddDocument')->name("property.get_add.documents");
    Route::post('/document/add', 'PropertyController@postAddDocument')->name("property.post_add.documents");
    Route::post('/document/edit/{document_id}', 'PropertyController@postEditDocument')->name("property.post_edit.documents");
    //historical doc
    Route::post('/historical-document/add', 'PropertyController@postAddHistoricalDocument')->name("property.post_add.historical_documents");
    Route::post('/historical-document/edit/{document_id}', 'PropertyController@postEditHistoricalDocument')->name("property.post_edit.historical_documents");
    //category
    Route::post('/category/add', 'PropertyController@createHistoricalCategory')->name("property.post_add.category");
    Route::post('/category/edit/{id}', 'PropertyController@updateHistoricalCategory')->name("property.post_edit.category");

    //area
    Route::get('/{property_id}/area/list', 'AreaController@listArea')->where('location_id', '[0-9]+')->name('property.list_area');
    Route::get('/{property_id}/area/{area_id}', 'AreaController@detail')->where('property_id', '[0-9]+')->where('area_id', '[0-9]+')->name('property.area_detail');
    Route::post('/decommission/area/{area_id}', 'AreaController@decommissionArea')->name('decommission_area');

    // Assessment
    Route::get('/{property_id}/assessment', 'AssessmentController@index')->name('assessment.index');

    Route::get('/{property_id}/fire-exit-assembly-points', 'PropertyController@propertyFireExitAndAssemblyPoint')->name('property.fireExit');
    Route::post('/{property_id}/add-fire-exit-assembly-points', 'PropertyController@addFireExitAndAssemblyPoint')->name('property.add_fire_exist_assembly');

    // register summary
    Route::get('/{property_id}/register', 'PropertyController@registerOverall')->name('property.register');
    Route::get('/{property_id}/gas-register', 'PropertyController@registerGas')->name('property.gas');
    Route::get('/{property_id}/water-register', 'PropertyController@registerWater')->name('property.water');
    Route::get('/{property_id}/fire-register', 'PropertyController@registerFire')->name('property.fire');
    Route::get('/{property_id}/asbestos-register', 'PropertyController@registerAsbestos')->name('property.asbestos');
    Route::get('/{property_id}/health-and-safety-register', 'PropertyController@registerHealthAndSafety')->name('property.health_and_safety');
});

Route::get('/audit-trail', 'SummaryController@getAuditTrail')->name('audit_trail')->middleware('auth');

Route::group([
    'prefix'     => 'location',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/add/{area_id}', 'LocationController@getAddLocation')->where('area_id', '[0-9]+')->name('location.get_add');
    Route::post('/add/{area_id}', 'LocationController@postAddLocation')->where('area_id', '[0-9]+')->name('location.post_add');
    Route::get('/detail/{id}', 'LocationController@detail')->where('id', '[0-9]+')->name('location.detail');
    Route::get('/edit/{id}', 'LocationController@getEditLocation')->where('id', '[0-9]+')->name('location.get_edit');
    Route::post('/edit/{id}', 'LocationController@postEditLocation')->where('id', '[0-9]+')->name('location.post_edit');
    Route::post('/decommission/{location_id}', 'LocationController@decommissionLocation')->where('id', '[0-9]+')->name('location.decommission');
    Route::get('/list/{area_id}', 'LocationController@index')->where('area_id', '[0-9]+')->name('location.list');
});

Route::group([
    'prefix'     => 'item',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/add/{location_id}', 'ItemController@getAddItem')->where('location_id', '[0-9]+')->name('item.get_add');
    Route::post('/add/{location_id}', 'ItemController@postAddItem')->where('location_id', '[0-9]+')->name('item.post_add');
    Route::get('/detail/{id}', 'ItemController@detail')->where('id', '[0-9]+')->name('item.detail');
    Route::get('/edit/{id}', 'ItemController@getEditItem')->where('id', '[0-9]+')->name('item.get_edit');
    Route::post('/edit/{id}', 'ItemController@postEditItem')->where('id', '[0-9]+')->name('item.post_edit');
    Route::post('/decommission/{id}', 'ItemController@decommissionItem')->where('id', '[0-9]+')->name('item.decommission');
    Route::get('/list/{location_id}', 'ItemController@index')->where('location_id', '[0-9]+')->name('item.list');
    Route::get('/dropdown-item', 'ItemController@getDropdownItem')->where('location_id', '[0-9]+')->name('item.dropdown_item');
    // programme

});

Route::group([
    'prefix'     => 'ajax',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/client-users/{client_id}', 'ClientController@getClientUsers')->name('ajax.client-users');
    Route::get('/client-users-assessment/{client_id}', 'ClientController@getClientAssessment')->name('ajax.client-users-assessment');
    Route::get('/client-lead-assessment/{client_id}', 'ClientController@getClientLeadsAssessment')->name('ajax.client-lead-assessment');
    Route::get('/property-group', 'PropertyController@listGroupByClient')->name('ajax.property_group');
    Route::post('/create_area', 'AreaController@createArea')->name('ajax.create_area');
    Route::post('/compliance-system', 'PropertyController@getComplianceSystem')->name('ajax.post_compliance_system');
    Route::post('/compliance-programme', 'PropertyController@getComplianceProgramme')->name('ajax.post_compliance_programme');
    Route::post('/compliance-equipment', 'PropertyController@getComplianceEquipment')->name('ajax.post_compliance_equiment');
    Route::get('/compliance-document-type', 'PropertyController@getComplianceDocumentType')->name('ajax.post_compliance_document_type');

    Route::get('/decommission-reason', 'HomeController@decommissionDropdown')->name('ajax.decommission_reason');
    Route::get('/recommission-reason', 'HomeController@recommissionDropdown')->name('ajax.recommission_reason');
    Route::get('/load-dropdown', 'ToolBoxController@dropdownTypeConfiguration')->name('ajax.dropdown_type_configurations');
    Route::post('/training-record', 'DocumentController@insertTrainingRecord')->name('ajax.training_record');
    Route::post('/property-plan', 'AssessmentController@updateOrCreatePropertyPlan')->name('ajax.property_plan');
    Route::post('/assessment-sampling', 'AssessmentController@updateOrCreateSampling')->name('ajax.assessment_sampling');
    Route::post('/assessment-fire-safety', 'AssessmentController@updateFireSafety')->name('ajax.assessment_fire_safety');
    Route::post('/asbestos-property-plan', 'SurveyController@updateOrCreatePropertyPlan')->name('asbestos.ajax.property_plan');
    Route::get('/contractor-select', 'ProjectController@contractorSelect')->name('ajax.contractor_select');
    Route::post('/project-doc', 'DocumentController@insertDocument')->name('ajax.project_doc');
    Route::post('/audit-trail', 'AuditController@ajaxAudit')->name('ajax.ajax_audit');
    Route::post('/pick-sample', 'SurveyController@pickSample')->name('ajax.pick_sample');
    Route::post('/sample-certificate', 'DocumentController@insertSampleCertificate')->name('ajax.sample_certificate');
    Route::post('/air-test-certificate', 'DocumentController@insertAirTestCertificate')->name('ajax.air_test_certificate');
    Route::get('/location-reason', 'LocationController@locationReason')->name('ajax.location_reason');
    Route::post('/resource-role', 'JobRoleController@updateOrCreateRole')->name('ajax.resource_role');
    Route::get('/get-equipment-system', 'PropertyController@getEquipmentSystem')->name('ajax.get_equipment_system');
    Route::get('/log-warning', 'PropertyController@logWarning')->name('ajax.log_warning');
    Route::get('/other-hazard-identified-questions', 'AssessmentController@getOtherHazardIdentifiedQuestions')->name('ajax.other_hazard_identified_questions');
});

Route::group([
    'prefix'     => 'comment-history',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::post('/decommission/{category}', 'CommentHistoryController@decommission')->name("comment.decommission");
    Route::post('/item', 'CommentHistoryController@item')->name("comment.item");
    Route::post('/property', 'CommentHistoryController@property')->name("comment.property");
    Route::post('/location', 'CommentHistoryController@location')->name("comment.location");
});
Route::group([
    'prefix'     => '',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('system/detail/{id}', 'SystemController@detail')->where('id', '[0-9]+')->name('systems.detail');
    Route::get('system/edit/{id}', 'SystemController@getEditSystem')->where('id', '[0-9]+')->name('systems.get_edit');
    Route::post('system/edit/{id}', 'SystemController@postEditSystem')->where('id', '[0-9]+')->name('systems.post_edit');
    Route::get('system/decommission/{id}', 'SystemController@decommission')->where('id', '[0-9]+')->name('systems.decommission');
    // search
    Route::get('system/search', 'SystemController@searchSystem')->name('systems.search_system');
    // programme
    Route::get('system/{system_id}/programme/list', 'SystemController@programmeList')->where('system_id', '[0-9]+')->name('programme.list');
    Route::get('system/{system_id}/programme/add', 'SystemController@getProgrammeAdd')->where('system_id', '[0-9]+')->name('programme.get_add');
    Route::post('system/{system_id}/programme/add', 'SystemController@postProgrammeAdd')->where('system_id', '[0-9]+')->name('programme.post_add');
    Route::get('/programme/detail/{id}', 'SystemController@programmeDetail')->where('id', '[0-9]+')->name('programme.detail');
    Route::get('/programme/edit/{id}', 'SystemController@getProgrammeEdit')->where('id', '[0-9]+')->name('programme.get_edit');
    Route::post('/programme/edit/{id}', 'SystemController@postProgrammeEdit')->where('id', '[0-9]+')->name('programme.post_edit');
    Route::get('/programme/decommission/{id}', 'SystemController@decommissionProgramme')->where('id', '[0-9]+')->name('programme.decommission');

    // equipment
    Route::get('system/{system_id}/equipment/list', 'SystemController@equipmentList')->where('system_id', '[0-9]+')->name('equipment.list');


    // Route::get('property/equipment/detail/{id}', 'SystemController@equipmentDetail')->where('id', '[0-9]+')->name('register_equipment.detail');

    Route::get('property/equipment/decommission/{id}', 'SystemController@decommissionEquipment')->where('id', '[0-9]+')->name('equipment.decommission');

    //document
    Route::get('system/{id}/document', 'PropertyController@listDocumentByType')->where('id', '[0-9]+')->name("system.document.list");
    Route::get('equipment/{id}/document', 'PropertyController@listDocumentByType')->where('id', '[0-9]+')->name("equipment.document.list");
    Route::get('programme/{id}/document', 'PropertyController@listDocumentByType')->where('id', '[0-9]+')->name("programme.document.list");
});


Route::group([
    'prefix'     => 'user',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/profile/{id}', 'UserController@profile')->name('profile-shineCompliance');
    Route::get('/profile/edit/{id}', 'UserController@getEditProfileCompliance')->name('user.get-edit-profile')->where('id', '[0-9]+');
    Route::post('/profile/edit/{id}', 'UserController@postEditProfileCompliance')->name('user.post-edit-profile')->where('id', '[0-9]+');
    Route::get('/change-password/{id}', 'UserController@getChangePassword')->name('user.get_change_password')->where('id', '[0-9]+');
    Route::post('/change-password/{id}', 'UserController@postChangePassword')->name('user.post_change_password')->where('id', '[0-9]+');
    Route::get('/lock/{id}', 'UserController@lock')->name('user.lock')->where('id', '[0-9]+');
    Route::get('/approval', 'UserController@getApproval')->name('user.approval');
    Route::get('/rejected', 'UserController@getRejected')->name('user.rejected');
    Route::get('/incident-report', 'UserController@getIncidentReports')->name('user.incident_report');
    Route::get('/certificate', 'UserController@certificate')->name('user.certificate');
    Route::get('/project', 'UserController@projects')->name('user.project');
    Route::get('/surveys', 'UserController@surveys')->name('user.survey');
    Route::get('/work-requests', 'UserController@getWorkRequests')->name('user.work_requests');
});


Route::get('/property', 'UserController@property')->name('property');
Route::get('/property/{id}/detail', 'UserController@propertyDetail')->where('id', '[0-9]+')
    ->name('property.detail');
// Route::get('/property/{id}/register/{type?}/', 'UserController@propertyRegister')
//     ->where('id', '[0-9]+')
//     ->name('property.register');
Route::get('/property/{id}/register/{type}/{summaryType}', 'UserController@propertyRegisterType')
    ->where('id', '[0-9]+')
    ->name('property.register.summary_detail');
Route::get('/property/add/{type}', 'UserController@addProperty')->name('property.add');



Route::get('/property/{id}/survey/{type}', 'UserController@propertySurvey')->name('property.survey');
Route::get('/property/{id}/drawings', 'UserController@propertyDrawing')->name('property.drawings');
Route::get('/property/{id}/drawings/view', 'UserController@propertyDrawingViewer')->name('property.drawings.view');
Route::get('/property/{id}/document', 'UserController@propertyDocumnet')->name('property.document');
Route::get('/property/{id}/sub_property', 'UserController@subProperty')->name('property.sub');
Route::get('/property/{id}/room', 'UserController@room')->name('property.room');
Route::get('/property/add_room', 'UserController@addRoom')->name('property.add_room');



Route::get('/item', 'UserController@item')->name('item');
Route::get('/{type}/hazard_item', 'UserController@hazardItem')->name('hazardItem');

Route::group([
    'prefix'     => 'summary',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/summary', 'UserController@summary')->name('view_summary');
    Route::get('/pre-planned-maintenance-summary', 'SummaryController@reportSummary')->name('pre_planned_maintenance_summary');
    Route::get('/export-summary', 'SummaryController@exportSummary')->name('export.summary');
    // general summary
    Route::get('/general-summary', 'SummaryController@generalSummary')->name('general_summary');
    // fire summary
    Route::get('/fire-summary', 'SummaryController@fireSummary')->name('fire_summary');
    Route::get('/fire-hazard-summary', 'SummaryController@fireHazardSummary')->name('fire_summary.fire_hazard');
    Route::get('/fire-assessment-summary', 'SummaryController@fireAssessmentSummary')->name('fire_summary.fire_assessment_hazard');
    Route::get('/export-fire-assessment-summary', 'SummaryController@exportFireAssessmentSummary')->name('export.fire_summary.fire_assessment_hazard');
    Route::get('/export-fire-documents-summary', 'SummaryController@fireDocumentsSummary')->name('export.fire_documents.summary');
    Route::get('/export-fire-hazard-ar-summary', 'SummaryController@exportfireHazardSummary')->name('export.fire_hazard.summary');
    // water summary
    Route::get('/water-summary', 'SummaryController@waterSummary')->name('water_summary');
    });

Route::group([
    'prefix'     => 'data_centre',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/dashboard', 'DataCentreController@dataCentre')->name('data_centre.dashboard');
    Route::get('/approval', 'DataCentreController@getApproval')->name('data_centre.approval');
    Route::get('/rejected', 'DataCentreController@getRejected')->name('data_centre.rejected');
    Route::get('/surveys', 'DataCentreController@surveys')->name("data_centre.surveys");
    Route::get('/projects', 'DataCentreController@projects')->name("data_centre.projects");
    Route::get('/critical', 'DataCentreController@critical')->name("data_centre.critical");
    Route::get('/urgent', 'DataCentreController@urgent')->name("data_centre.urgent");
    Route::get('/important', 'DataCentreController@important')->name("data_centre.important");
    Route::get('/attention', 'DataCentreController@attention')->name("data_centre.attention");
    Route::get('/deadline', 'DataCentreController@deadline')->name("data_centre.deadline");
    Route::get('/assessment', 'DataCentreController@assessment')->name("data_centre.assessment");
    Route::get('/certificate', 'DataCentreController@certificate')->name("data_centre.certificate");
    Route::get('/audit', 'DataCentreController@audit')->name("data_centre.audit");
    //ajax
    Route::get('/ajax/assessment_missing', 'DataCentreController@postAssessmentMissing')->name("data_centre.ajax.assessment_missing");
    Route::get('/ajax/survey_missing', 'DataCentreController@postSurveyMissing')->name("data_centre.ajax.survey_missing");
});

Route::group([
    'prefix'     => 'chart',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::post('/generate', 'ChartController@generateChart')->name('chart.generate');
});
//Route::get('/reporting/summary', 'UserController@reportSummary')->name('reporting.summary');
//Route::get('/reporting/summary1', 'UserController@exportSummary')->name('export.summary');
Route::get('/search-document', 'UserController@searchDocument')->name('document.search_document');

Route::get('/search', 'SearchController@search')->name('search_shine');

Route::group([
    'prefix'     => 'tool-box',
    'middleware' => [
        'auth',
    ],
], function() {
Route::get('/upload', 'ToolBoxController@getUpload')->name('admin_tool.upload');
Route::post('/upload', 'ToolBoxController@postUpload')->name('admin_tool.post_upload');
Route::get('/template', 'ToolBoxController@downloadTemplate')->name('admin_tool.template');
Route::get('/setting', 'ToolBoxController@getSettings')->name('admin_tool.get_setting');

Route::get('/configurations', 'ToolBoxController@getConfigurations')->name('setting.configurations');
});

Route::group([
    'prefix'     => 'my-organisation',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/{client_id}', 'ClientController@index')->name('my_organisation');
    Route::get('/edit/{client_id}', 'ClientController@getEditOrganisation')->name('my_organisation.get_edit');
    Route::post('/edit/{client_id}', 'ClientController@postEditOrganisation')->name('my_organisation.post_edit');
    Route::get('/department-users/{department_id}', 'ClientController@departmentUsers')->name('my_organisation.department_users');
});

Route::group([
    'prefix'     => 'contractor',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/all', 'ClientController@allContractors')->name('contractor.clients');
    Route::get('/add-user/{client_id}/{department_id}', 'ClientController@getAddUser')->name('contractor.get_add_user');
    Route::get('/{client_id}', 'ClientController@contractor')->name('contractor')->where('client_id', '[0-9]+');
});


Route::group([
    'prefix'     => 'assessment',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/{assess_id}/detail', 'AssessmentController@show')->name('assessment.show');
    Route::get('/add/{classification}', 'AssessmentController@getAdd')->name('assessment.get_add');
    Route::post('/add/{classification}', 'AssessmentController@postAdd')->name('assessment.post_add');
    Route::get('/{assess_id}/edit', 'AssessmentController@getEdit')->name('assessment.get_edit');
    Route::post('/{assess_id}/edit', 'AssessmentController@postEdit')->name('assessment.post_edit');
    Route::post('/{assess_id}/decommission', 'AssessmentController@postDecommission')->name('assessment.decommission');
    Route::post('/{assess_id}/recommission', 'AssessmentController@postRecommission')->name('assessment.recommission');
    Route::post('/{assess_id}/publish', 'AssessmentController@publishAssessment')->name('assessment.publish');
    Route::post('/{assess_id}/send', 'AssessmentController@sendAssessment')->name('assessment.send');

    Route::get('/{assess_id}/edit-objective-scope', 'AssessmentController@getEditObjectiveScope')->name('assessment.get_edit_objective_scope');
    Route::post('/{assess_id}/edit-objective-scope', 'AssessmentController@postEditObjectiveScope')->name('assessment.post_edit_objective_scope');
    Route::get('/{assess_id}/edit-property-information', 'AssessmentController@getEditPropertyInformation')->name('assessment.get_edit_property_information');
    Route::post('/{assess_id}/edit-property-information', 'AssessmentController@postEditPropertyInformation')->name('assessment.post_edit_property_information');
    Route::get('{assess_id}/edit-questionnaire', 'AssessmentController@getEditQuestionnaire')->name('assessment.get_edit_questionnaire'); //audit/assessment in assessments
    Route::post('{assess_id}/edit-questionnaire', 'AssessmentController@postEditQuestionnaire')->name('assessment.post_edit_questionnaire');

    Route::post('equipment/decommission/{id}', 'AssessmentController@decommissionEquipment')->name('assessment.decommission.equipment');
    Route::post('equipment/recommission/{id}', 'AssessmentController@recommissionEquipment')->name('assessment.recommission.equipment');

    Route::get('{assess_id}/add-system', 'SystemController@getAddAssessmentSystem')->name('system.get_add_system');
    Route::post('{assess_id}/add-system', 'SystemController@postAddAssessmentSystem')->name('system.post_add_system');

    Route::get('system-detail/{id}', 'SystemController@assessmentSystemDetail')->name('assessment.system_detail');

    Route::get('/system-edit/{id}', 'SystemController@getEditAssessmentSystem')->name('system.get_edit_system');
    Route::post('/system-edit/{id}', 'SystemController@postEditAssessmentSystem')->name('system.post_edit_system');

    Route::get('{assess_id}/area/{area_id}/locations', 'AssessmentController@getLocationsByAssessmentAndArea')->name('assessment.get_locations_by_area');

    Route::post('{assess_id}/approval', 'AssessmentController@postApproval')->name('assessment.approval');
    Route::post('{assess_id}/reject', 'AssessmentController@postReject')->name('assessment.reject');
    Route::post('{assess_id}/cancel', 'AssessmentController@postCancel')->name('assessment.cancel');

    Route::get('assessment-pdf/{id}', 'AssessmentController@downloadAssessPDF')->name('assessment.pdf_download');
});

Route::group([
    'prefix'     => 'equipment',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/add-equipment/{property_id}', 'EquipmentController@getAddEquipment')->name('equipment.get_add_equipment');
    Route::post('/add-equipment/{property_id}', 'EquipmentController@postAddEquipment')->name('equipment.post_add_equipment');

    Route::get('detail/{id}', 'EquipmentController@detail')->name('equipment.detail');
    Route::get('ajax/equipment/specific-location', 'EquipmentController@getSpecificDropdown')->name('equipment.ajax_specific');
    Route::get('equipment/template', 'EquipmentController@getActiveSection')->name('equipment.ajax_equipment_template');

    Route::get('search', 'EquipmentController@searchEquipment')->name('equipment.search_equipment');
    Route::get('search-type', 'EquipmentController@searchEquipmentType')->name('equipment.search_equipment_type');
    Route::get('search-system', 'EquipmentController@searchSystem')->name('equipment.search_system');
    Route::get('/edit/{id}', 'EquipmentController@getEditEquipment')->name('equipment.get_edit_equipment');
    Route::post('/edit/{id}', 'EquipmentController@postEditEquipment')->name('equipment.post_edit_equipment');

    Route::get('/register/{id}', 'EquipmentController@registerEquipmentDetail')->name('register_equipment.detail');
    Route::get('property-equipment/{property_id}', 'EquipmentController@propertyEquipment')->where('property_id', '[0-9]+')->name('property.equipment');


    Route::get('/photography/{id}', 'EquipmentController@getPhotographyDetails')->name('photography_equipment.detail');
    // register summary
    Route::get('/{id}/hazards', 'EquipmentController@registerOverall')->name('equipment.hazards');
    Route::get('/{id}/non-conformities', 'EquipmentController@getNonconformity')->name('equipment.nonconformities');
    Route::get('/{id}/pre-planned-maintenance', 'EquipmentController@getPreplannedMaintenance')->name('equipment.pre_planned_maintenance');
    Route::get('/{id}/temperature-ph', 'EquipmentController@getTemperature')->name('equipment.temperature_ph');
    Route::get('/ajax/equipment/temperature-history', 'EquipmentController@getHistoryTemperature')->name('equipment.ajax_temperature_history');
    Route::post('/update-temperature', 'EquipmentController@postUpdateTemperature')->name('equipment.post_update_temperature');
});

Route::group([
    'prefix'     => '',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('vehicle-parking/add/{property_id}', 'VehicleParkingController@getAddVehicleParking')->where('assess_id', '[0-9]+')->name('assessment.get_add_vehicle_parking');
    Route::post('vehicle-parking/add', 'VehicleParkingController@postAddVehicleParking')->where('assess_id', '[0-9]+')->name('assessment.post_add_vehicle_parking');
    Route::get('vehicle-parking/{vehicle_parking_id}', 'VehicleParkingController@getVehicleParking')->name('assessment.get_vehicle_parking');
    Route::get('vehicle-parking/{vehicle_parking_id}/edit', 'VehicleParkingController@getEditVehicleParking')->name('assessment.get_edit_vehicle_parking');
    Route::post('vehicle-parking/{vehicle_parking_id}/edit', 'VehicleParkingController@postEditVehicleParking')->name('assessment.post_edit_vehicle_parking');
    Route::post('vehicle-parking/{vehicle_parking_id}/decommission', 'VehicleParkingController@postDecommissionVehicleParking')->name('assessment.decommission.vehicle_parking');
    Route::post('vehicle-parking/{vehicle_parking_id}/recommission', 'VehicleParkingController@postRecommissionVehicleParking')->name('assessment.recommission.vehicle_parking');
    Route::get('property/{property_id}/list-vehicle-parking', 'VehicleParkingController@listRegisterVehicleParking')->name('property.parking');
});

Route::group([
    'prefix'     => 'fire-exit',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/add/{property_id}', 'FireExitsController@getAddFireExit')->where('property_id', '[0-9]+')->name('assessment.get_add_fire_exit');
    Route::post('/add/{property_id}', 'FireExitsController@postAddFireExit')->where('property_id', '[0-9]+')->name('assessment.post_add_fire_exit');
    Route::get('/{fire_exit_id}', 'FireExitsController@getFireExit')->name('assessment.get_fire_exit');
    Route::get('/{fire_exit_id}/edit', 'FireExitsController@getEditFireExit')->name('assessment.get_edit_fire_exit');
    Route::post('/{fire_exit_id}/edit', 'FireExitsController@postEditFireExit')->name('assessment.post_edit_fire_exit');
    Route::post('/{fire_exit_id}/decommission', 'FireExitsController@postDecommissionFireExit')->name('assessment.decommission.fire_exit');
    Route::post('/{fire_exit_id}/recommission', 'FireExitsController@postRecommissionFireExit')->name('assessment.recommission.fire_exit');
});

Route::group([
    'prefix'     => '',
    'middleware' => [
        'auth',
    ],
], function() {

    Route::get('assembly-point/add/{property_id}', 'AssemblyPointController@getAddAssemblyPoint')->where('property_id', '[0-9]+')->name('assessment.get_add_assembly_point');
    Route::post('assembly-point/add/{property_id}', 'AssemblyPointController@postAddAssemblyPoint')->where('property_id', '[0-9]+')->name('assessment.post_add_assembly_point');
    Route::get('assembly-point/{assembly_point_id}', 'AssemblyPointController@getAssemblyPoint')->name('assessment.get_assembly_point');
    Route::get('assembly-point/{assembly_point_id}/edit', 'AssemblyPointController@getEditAssemblyPoint')->name('assessment.get_edit_assembly_point');
    Route::post('assembly-point/{assembly_point_id}/edit', 'AssemblyPointController@postEditAssemblyPoint')->name('assessment.post_edit_assembly_point');
    Route::post('assembly-point-exit/{assembly_point_id}/decommission', 'AssemblyPointController@postDecommissionAssemblyPoint')->name('assessment.decommission.assembly_point');
    Route::post('assembly-point-exit/{assembly_point_id}/recommission', 'AssemblyPointController@postRecommissionAssemblyPoint')->name('assessment.recommission.assembly_point');
});

Route::group([
    'prefix'     => '',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('add-hazard/{property_id}', 'HazardController@getAddHazard')->name('assessment.get_add_hazard');
    Route::post('add-hazard/{property_id}', 'HazardController@postAddHazard')->name('assessment.post_add_hazard');
    Route::get('{hazard_id}/edit-hazard', 'HazardController@getEditHazard')->name('assessment.get_edit_hazard');
    Route::post('{hazard_id}/edit-hazard', 'HazardController@postEditHazard')->name('assessment.post_edit_hazard');
    Route::get('hazard/{id}/detail', 'HazardController@getHazardDetail')->name('assessment.get_hazard_detail');
    Route::get('ajax/hazard/specific-location', 'HazardController@getSpecificDropdown')->name('hazard.ajax_specific');
    Route::post('hazard/decommission/{id}', 'HazardController@decommissionHazard')->name('assessment.decommission.hazard');
    Route::post('hazard/recommission/{id}', 'HazardController@recommissionHazard')->name('assessment.recommission.hazard');
    Route::post('hazard/confirm/{id}', 'HazardController@confirmHazard')->name('assessment.confirm.hazard');
});
Route::group([
    'prefix'     => 'project',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/{property_id}', 'ProjectController@listProject')->name('project.project_list')->where('property_id', '[0-9]+');
//    Route::get('/detail/{project_id}', 'ProjectController@index')->name('project.index')->where('project_id', '[0-9]+');
//    Route::get('/edit/{project_id}', 'ProjectController@getEditProject')->name('project.get_edit')->where('project_id', '[0-9]+');
//    Route::post('/edit/{project_id}', 'ProjectController@postEditProject')->name('project.post_edit')->where('project_id', '[0-9]+');
//    Route::get('/add/{property_id}', 'ProjectController@getAddProject')->name('project.get_add')->where('property_id', '[0-9]+');
//    Route::post('/add/{property_id}', 'ProjectController@postAddProject')->name('project.post_add')->where('property_id', '[0-9]+');
//    Route::get('/archive/{project_id}', 'ProjectController@archiveProject')->name('project.archive')->where('project_id', '[0-9]+');

});

Route::group([
    'prefix'     => 'surveys',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/{survey_id}',function ($survey_id) {
        return redirect()->route('property.surveys',['id' => $survey_id]);
    })->name('property.surveys');

    Route::get('/add/{property_id}', 'SurveyController@getAddSurvey')->name('survey.get_add_survey');
    Route::post('/add/{property_id}', 'SurveyController@postAddSurvey')->name('survey.post_add_survey');
    Route::get('/edit/{survey_id}', 'SurveyController@getEditSurvey')->name('survey.get_edit');
    Route::get('/decommission/{survey_id}', 'SurveyController@decommissionSurvey')->name('survey.decommission');
    Route::post('/publish/{survey_id}', 'GenerateSurveyPDFController@publishSurveyPDF')->name('survey.publish');
    Route::post('/send/{survey_id}', 'SurveyController@sendSurvey')->name('survey.send');
    Route::post('/create-area', 'SurveyController@createArea')->name('survey.post_area');
    Route::post('/edit/{survey_id}', 'SurveyController@postEditSurvey')->name('survey.post_edit');
    Route::post('/decommission/area/{area_id}', 'AreaController@decommissionArea')->name('survey.decommission_area');
    Route::post('/sample-email-co1', 'SurveyController@sampleEmailCo1')->name('ajax.sample_email_co1');
    Route::get('/survey-information/{survey_id}/{type}', 'SurveyController@getEditSurveyInformation')->name('survey-information');
    Route::post('/survey-information/{survey_id}/{type}', 'SurveyController@postEditSurveyInformation')->name('post.survey-information');
    Route::post('/methodOption/{survey_id}/{type}', 'SurveyController@methodOption')->name('method_option');
    Route::get('/method-questionaire/{survey_id}', 'SurveyController@getMethodQuestion')->name('get.method_question');
    Route::post('/method-questionaire/{survey_id}', 'SurveyController@postMethodQuestion')->name('post.method_question');
    Route::get('/property-information/{survey_id}', 'SurveyController@getEditPropertyInfo')->name('get.edit_propertyInfo_asbestos');
    Route::post('/property-information/{survey_id}', 'SurveyController@postEditPropertyInfo')->name('post.edit_propertyInfo_asbestos');
});

Route::group([
    'prefix'     => 'pdf',
    'middleware' => [
    ],
], function() {
    Route::get('survey/generate-pdf', 'GenerateSurveyPDFController@generatePdfFromFile')->name('pdf.generate');
    Route::get('survey/view-pdf/{type}/{id}', 'GenerateSurveyPDFController@viewPDF')->name('survey.view.pdf');
    Route::get('survey/download-pdf/{type}/{id}', 'GenerateSurveyPDFController@downloadPDF')->name('survey.download.pdf');
    Route::get('/view-pdf/{type}/{id}', 'GeneratePDFController@viewDocument')->name('document.view');
    Route::get('/download-pdf/{type}/{id}', 'GeneratePDFController@downDownload')->name('document.download');
    Route::get('/retrieve_image/{type}/{id}', 'GeneratePDFController@downloadImage')->name('retrive_image');
    Route::get('/retrieve_fire_register/{property_id}', 'GeneratePDFController@createFireRegisterPDF')->name('fire.pdf')->where(['id' => '[0-9]+']);
    Route::get('/retrieve_water_register/{property_id}', 'GeneratePDFController@createWaterRegisterPDF')->name('water.pdf')->where(['id' => '[0-9]+']);
    Route::get('/view_fire_register/{property_id}', 'GeneratePDFController@viewFireRegisterPDF')->name('view_fire.pdf')->where(['id' => '[0-9]+']);
    Route::get('/view_water_register/{property_id}', 'GeneratePDFController@viewWaterRegisterPDF')->name('view_water.pdf')->where(['id' => '[0-9]+']);
    Route::get('/view_hs_register/{property_id}', 'GeneratePDFController@viewHSRegisterPDF')->name('view_hs.pdf')->where(['id' => '[0-9]+']);
    Route::get('/retrieve_hs_register/{property_id}', 'GeneratePDFController@createHSRegisterPDF')->name('hs.pdf')->where(['id' => '[0-9]+']);
});

Route::group([
    'prefix'     => 'document',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::post('/reject', 'DocumentController@rejectDocument')->name('document.reject');

});

Route::get('/samples/{survey_id}/{sample_id}', 'SurveyController@getEditSample')->name('sample_getEdit')->middleware('auth');
Route::post('/samples/{survey_id}/{sample_id}', 'SurveyController@postEditSample')->name('sample_postEdit')->middleware('auth');

Route::group([
    'prefix'     => 'resources',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/resource-document', 'ResourceController@resourceDocument')->name('resource_document.compliance');
    Route::get('/bluelight-service', 'ResourceController@bluelightService')->name('bluelight_service.compliance');
    Route::get('/get-zip-bluelight-service/{zone_id}', 'ResourceController@getZipbluelightService')->name('get_zip_file_BS.compliance');
    Route::get('/generate-bluelight-service/{zone_id}', 'ResourceController@generateZipProcess')->name('generate_bls_file.compliance');
    Route::get('/job-role/{id}', 'JobRoleController@getJobRole')->name('get_job_role.compliance');
    Route::get('/list-job-role', 'JobRoleController@editJobRole')->name('list_job_role.compliance');
    Route::post('/list-job-role/{id}', 'JobRoleController@postEditJobRole')->name('post_list_job_role.compliance');
    Route::get('/ajax-property-role', 'JobRoleController@ajaxShowListProperty')->name('ajax_property_role.compliance');
    Route::post('/ajax-save-property-role', 'JobRoleController@ajaxSaveListProperty')->name('ajax_save_property_role.compliance');
    Route::get('/ajax-check-all-client', 'JobRoleController@ajaxCheckAllClient')->name('check_all_client.compliance');
    Route::get('/ajax-check-all-organisation', 'JobRoleController@ajaxCheckAllOrganisation')->name('check_all_organisation.compliance');
    Route::get('/ajax-client-role', 'JobRoleController@ajaxPostShowListClient')->name('ajax_client_role.compliance');
    Route::get('/ajax-organisation-role', 'JobRoleController@ajaxPostShowListOrganisation')->name('ajax_organisation_role.compliance');
    Route::get('/ajax-list-client-group-role', 'JobRoleController@ajaxShowListClientGroup')->name('get_list_client_group.compliance');
    Route::post('/ajax-list-client-group-role', 'JobRoleController@ajaxPostListClientGroup')->name('post_list_client_group.compliance');
    Route::post('/ajax-list-organisation-role', 'JobRoleController@ajaxPostListOrganisation')->name('post_list_organisation.compliance');
    Route::post('/save_role', 'ResourceController@saveRole')->name('save_role.compliance');
    Route::get('/department-list', 'ResourceController@departmentList')->name('department_list.compliance');
    Route::get('/audit-list', 'AuditAppController@index')->name('audit_list.compliance');
});
// Incident Reporting
Route::group([
    'prefix'     => 'incident_reporting',
    'middleware' => [
        'auth',
    ],
], function() {
    Route::get('/search', 'IncidentReportController@searchIncident')->name('incident_reporting.search');
    Route::get('/add', 'IncidentReportController@getAdd')->name('incident_reporting.get_add');
    Route::post('/add', 'IncidentReportController@postAdd')->name('incident_reporting.post_add');
    Route::get('/edit/{id}', 'IncidentReportController@getEdit')->name('incident_reporting.get_edit')->where('id', '[0-9]+');
    Route::post('/edit/{id}', 'IncidentReportController@postEdit')->name('incident_reporting.post_edit')->where('id', '[0-9]+');
    Route::get('/{incident_id}', 'IncidentReportController@incidentReport')->name('incident_reporting.incident_Report');
    Route::post('publish/{incident_id}', 'IncidentGeneratePDFController@publishIncidentPDF')->name('incident_reporting.publish');
    Route::get('/view-pdf/{type}/{id}', 'IncidentGeneratePDFController@viewPDF')->name('incident_reporting.view.pdf');
    Route::get('/download-pdf/{type}/{id}', 'IncidentGeneratePDFController@downloadPDF')->name('incident_reporting.download.pdf');
    Route::post('/approval/{incident_id}', 'IncidentReportController@approvalIncident')->name('incident.approval');
    Route::post('/document', 'IncidentReportController@updateOrCreateDocument')->name('incident_reporting.post_document');
    Route::get('/download-pdf-file-viewing/{id}', 'GeneratePDFController@downloadIncidentDocumentFile')->name('incident_reporting.retrieve_document_file');
    Route::post('/reject/{incident_id}', 'IncidentReportController@rejectIncident')->name('incident_reporting.reject');
    Route::post('/cancel/{incident_id}', 'IncidentReportController@cancelIncident')->name('incident_reporting.cancel');

    Route::post('/decommission/{incident_id}', 'IncidentReportController@postDecommission')->name('incident_reporting.decommission');
    Route::post('/recommission/{incident_id}', 'IncidentReportController@postRecommission')->name('incident_reporting.recommission');
});

Route::get('/incident_reporting/view-pdf-email/{type}/{id}', 'IncidentGeneratePDFController@viewPDF')->name('incident_reporting.view_pdf.email');

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', function () {
    if(\Auth::user()->is_site_operative == 1) {
        return redirect()->route('zone.operative', ['client_id' => 1]);
    }
    return redirect('compliance/home');
})->middleware('auth')->name('home');

Route::get('/', function () {
    return redirect('compliance/home');
})->middleware('auth');

 Route::group([
            'prefix'     => 'property',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/list', 'PropertyController@index')->name("property_list");
    Route::get('/property/plan', 'PropertyController@propertyPlan')->name("property_plan");
    Route::get('/detail/{id}', function ($id){
        if(\Auth::user()->is_site_operative == 1) {
            return redirect()->route('property.operative.detail', ['id' => $id]);
        }
        return redirect()->route('shineCompliance.property.property_detail',['id' => $id]);
    })->name("property_detail")->where('id', '[0-9]+');
    Route::get('/edit/{property_id}', 'PropertyController@editProperty')->name("property_edit")->where('property_id', '[0-9]+');
    Route::post('/edit/{property_id}', 'PropertyController@postEditProperty')->name("property.post_edit");
    Route::get('/add', 'PropertyController@getAddProperty')->name("property.get_add");
    Route::post('/add', 'PropertyController@postAddProperty')->name("property.post_add");
    Route::get('/decommission/{item_id}', 'PropertyController@decommissionProperty')->name('property.decommission');
     Route::get('/property-detail/{id}', 'PropertyController@detail')->name('property.operative.detail');
});

Route::get('/samples/{survey_id}/{sample_id}', 'SurveyController@getEditSample')->name('sample_getEdit')->middleware('auth');
Route::post('/samples/{survey_id}/{sample_id}', 'SurveyController@postEditSample')->name('sample_postEdit')->middleware('auth');

Route::group([
            'prefix'     => 'data-centre',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/index', 'DataCentreController@index')->name("data_centre");
    Route::get('/my-notifications', 'DataCentreController@myNotifications')->name("data_centre.my_notifications");
    Route::get('/surveys', 'DataCentreController@surveys')->name("data_centre.surveys");
    Route::get('/projects', 'DataCentreController@projects')->name("data_centre.projects");
    Route::get('/critical', 'DataCentreController@critical')->name("data_centre.critical");
    Route::get('/urgent', 'DataCentreController@urgent')->name("data_centre.urgent");
    Route::get('/important', 'DataCentreController@important')->name("data_centre.important");
    Route::get('/attention', 'DataCentreController@attention')->name("data_centre.attention");
    Route::get('/deadline', 'DataCentreController@deadline')->name("data_centre.deadline");
    Route::get('/approval', 'DataCentreController@approval')->name("data_centre.aprroval");
    Route::get('/rejected', 'DataCentreController@rejected')->name("data_centre.rejected");
});

Route::group([
            'prefix'     => 'tool-box',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/remove', 'ToolBoxController@remove')->name("toolbox.remove");
    Route::post('/remove', 'ToolBoxController@postRemove')->name("toolbox.post_remove");
    Route::get('/move', 'ToolBoxController@move')->name("toolbox.move");
    Route::post('/post-move', 'ToolBoxController@postMove')->name("toolbox.post_move");
    Route::get('/merge', 'ToolBoxController@merge')->name("toolbox.merge");
    Route::post('/post-merge', 'ToolBoxController@postMerge')->name("toolbox.post_merge");
    Route::get('/unlock', 'ToolBoxController@unlock')->name("toolbox.unlock");
    Route::post('/post-unlock', 'ToolBoxController@postUnlock')->name("toolbox.post_unlock");
    Route::get('/revert', 'ToolBoxController@revert')->name("toolbox.revert_back");
    Route::post('/post-revert', 'ToolBoxController@postRevert')->name("toolbox.post_revert_back");
    Route::get('/upload', 'ToolBoxController@upload')->name("toolbox.upload");
    Route::post('/upload', 'ToolBoxController@postUpload')->name("toolbox.post_upload");
    Route::get('/logs', 'ToolBoxLogController@index')->name("toolboxlog");
    Route::post('/roll-back', 'ToolBoxLogController@revertBackAction')->name("toolbox.revert");
    Route::get('/upload-template', 'ToolBoxController@template')->name("toolbox.upload_template");
});

Route::group([
            'prefix'     => 'comment-history',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::post('/property', 'CommentHistoryController@property')->name("comment.property");
    Route::post('/project', 'CommentHistoryController@project')->name("comment.project");
    Route::post('/item', 'CommentHistoryController@item')->name("comment.item");
    Route::post('/location', 'CommentHistoryController@location')->name("comment.location");
    Route::post('/decommission/{category}', 'CommentHistoryController@decommission')->name("comment.decommission");
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
            'prefix'     => 'client',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/list', 'ClientController@clientList')->name('client_list');
    Route::get('/add', 'ClientController@getAddClient')->name('client.get_add');
    Route::post('/add', 'ClientController@postAddClient')->name('client.post_add');
    Route::get('/detail/{client_id}', 'ClientController@clientDetail')->name('client.detail');
    Route::get('/edit/{client_id}', 'ClientController@getEditClient')->name('client.get_edit');
    Route::post('/edit/{client_id}', 'ClientController@postEditClient')->name('client.post_edit');
    Route::get('/department-users/{department_id}', 'ClientController@departmentUsers')->name('client.department_users');
});

Route::group([
            'prefix'     => 'resources',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/e-learning', 'ResourceController@eLearning')->name('e_learning');
    Route::get('/e-learning/enroll/{user_id}', 'ElearningController@enroll')->name('e_learning.enroll');
    Route::get('/e-learning/begin/{user_id}', 'ElearningController@authenticateUser')->name('e_learning.begin');
    Route::get('/training-video/{type}', 'ResourceController@trainingVideo')->name('training_video');
    Route::get('/resource-document', 'ResourceController@resourceDocument')->name('resource_document');
    Route::get('/bluelight-service', 'ResourceController@bluelightService')->name('bluelight_service');
    Route::get('/get-zip-bluelight-service/{zone_id}', 'ResourceController@getZipbluelightService')->name('get_zip_file_BS');
    Route::get('/generate-bluelight-service/{zone_id}', 'ResourceController@generateZipProcess')->name('generate_bls_file');
    Route::get('/job-role', 'ResourceController@getJobRole')->name('get_job_role');
    Route::get('/list-job-role', 'ResourceController@editJobRole')->name('list_job_role');//
    Route::get('/ajax-property-role', 'ResourceController@ajaxShowListProperty')->name('ajax_property_role');
    Route::post('/ajax-save-property-role', 'ResourceController@ajaxSaveListProperty')->name('ajax_save_property_role');
    Route::post('/save_role', 'ResourceController@saveRole')->name('save_role');
    Route::get('/department-list', 'ResourceController@departmentList')->name('department_list');
    Route::get('/incident-report', 'ResourceController@incidentReports')->name('incident_reports');

});

Route::group([
            'prefix'     => 'contractor',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/all', 'ClientController@allContractors')->name('contractor.clients');
    Route::get('/add', 'ClientController@getAddContractor')->name('contractor.get_add');
    Route::post('/add', 'ClientController@postAddContractor')->name('contractor.post_add');
    Route::get('/edit/{client_id}', 'ClientController@getEditOrganisation')->name('contractor.get_edit');
    Route::get('/{client_id}', 'ClientController@contractor')->name('contractor')->where('client_id', '[0-9]+');
    Route::get('/department-users/{department_id}', 'ClientController@departmentUsers')->name('contractor.department_users');
    Route::get('/add-user/{client_id}/{department_id}', 'ClientController@getAddUser')->name('contractor.get_add_user');
    Route::post('/add-user', 'ClientController@postAddUser')->name('contractor.post_add_user');

});

Route::group([
            'prefix'     => 'project',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/{project_id}', 'ProjectController@index')->name('project.index')->where('project_id', '[0-9]+');
    Route::get('/edit/{project_id}', 'ProjectController@getEditProject')->name('project.get_edit')->where('project_id', '[0-9]+');
    Route::post('/edit/{project_id}', 'ProjectController@postEditProject')->name('project.post_edit')->where('project_id', '[0-9]+');
    Route::get('/add/{property_id}', 'ProjectController@getAddProject')->name('project.get_add')->where('property_id', '[0-9]+');
    Route::post('/add/{property_id}', 'ProjectController@postAddProject')->name('project.post_add')->where('property_id', '[0-9]+');
    Route::get('/archive/{project_id}', 'ProjectController@archiveProject')->name('project.archive')->where('project_id', '[0-9]+');
    Route::post('/decommission/{project_id}', 'ProjectController@decommissionProject')->name('project.decommission')->where('project_id', '[0-9]+');
    Route::post('/change_progress/{project_id}', 'ProjectController@postChangeProgress')->name('project.post_change_progress')->where('project_id', '[0-9]+');

});

Route::get('/audit-trail', 'AuditController@index')->name('audit_trail')->middleware('auth');
Route::get('/app-audit-trail', 'AuditController@indexAppAudit')->name('app_audit_trail')->middleware('auth');
Route::group([
            'prefix'     => 'summary',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/material', 'SummaryController@material')->name('summary.material');
    Route::post('/material', 'SummaryController@postMaterial')->name('summary.post_material');

    Route::get('/priority', 'SummaryController@priority')->name('summary.priority');
    Route::post('/priority', 'SummaryController@postPriority')->name('summary.post_priority');

    Route::get('/riskassessment', 'SummaryController@overall')->name('summary.riskassessment');
    Route::post('/riskassessment', 'SummaryController@postOverall')->name('summary.post_riskassessment');

    Route::get('/survey', 'SummaryController@survey')->name('summary.survey');
    Route::post('/survey', 'SummaryController@postSurvey')->name('summary.post_survey');

    Route::get('/user', 'SummaryController@user')->name('summary.user');
    Route::post('/user', 'SummaryController@postUser')->name('summary.post_user');

    Route::get('/app-user', 'SummaryController@appUser')->name('summary.appUser');
    Route::post('/app-user', 'SummaryController@postAppUser')->name('summary.post_appUser');

    Route::get('/area-check', 'SummaryController@areaCheck')->name('summary.areaCheck');
    Route::post('/area-check', 'SummaryController@postAreaCheck')->name('summary.post_areaCheck');

    Route::get('/room-check', 'SummaryController@roomCheck')->name('summary.roomCheck');
    Route::post('/room-check', 'SummaryController@postAreaCheck')->name('summary.post_roomCheck');

    Route::get('/reinspection-programme', 'SummaryController@reinspectionProgramme')->name('summary.reinspectionProgramme');
    Route::post('/reinspection-programme', 'SummaryController@postReinspectionProgramme')->name('summary.post_reinspectionProgramme');

    Route::get('/director-overview', 'SummaryController@directorOverview')->name('summary.directorOverview');
    Route::post('/director-overview', 'SummaryController@postDirectorOverview')->name('summary.post_directorOverview');

    Route::get('/manager-overview', 'SummaryController@managerOverview')->name('summary.managerOverview');
    Route::post('/manager-overview', 'SummaryController@postManagerOverview')->name('summary.post_managerOverview');

    Route::get('/kpi-summary', 'SummaryController@kpiSummary')->name('summary.KPIsummary');
    Route::post('/kpi-summary', 'SummaryController@postKpiSummary')->name('summary.post_KPIsummary');

    Route::get('/inaccessible', 'SummaryController@inaccessible')->name('summary.inaccessible');
    Route::post('/inaccessible', 'SummaryController@postInaccessible')->name('summary.post_inaccessible');

    Route::get('/action-recommendation', 'SummaryController@actionRecommendation')->name('summary.actionRecommendation');
    Route::post('/action-recommendation', 'SummaryController@postActionRecommendation')->name('summary.post_actionRecommendation');

    Route::get('/genesis-communal-csv', 'SummaryController@genesisCommunalCsv')->name('summary.genesisCommunalCsv');
    Route::post('/genesis-communal-csv', 'SummaryController@postGenesisCommunalCsv')->name('summary.post_genesisCommunalCsv');

    Route::get('/asbestos-remedial-action', 'SummaryController@asbestosRemedialAction')->name('summary.asbestosRemedialAction');
    Route::post('/asbestos-remedial-action', 'SummaryController@postAsbestosRemedialAction')->name('summary.post_asbestosRemedialAction');

    Route::get('/project-summary', 'SummaryController@projectSummary')->name('summary.projectSummary');
    Route::post('/project-summary', 'SummaryController@postProjectSummary')->name('summary.post_projectSummary');

    Route::get('/register-item-change', 'SummaryController@registerItemChange')->name('summary.registerItemChange');
    Route::post('/register-item-change', 'SummaryController@postRegisterItemChange')->name('summary.post_registerItemChange');

    Route::get('/hd-documents', 'SummaryController@hdDocuments')->name('summary.hdDocuments');
    Route::post('/hd-documents', 'SummaryController@postHdDocuments')->name('summary.post_hdDocuments');

    Route::get('/survey-summary', 'SummaryController@surveySummary')->name('summary.surveySummary');
    Route::get('/survey-pdf-preview', 'SummaryController@surveyPdfPreview')->name('summary.survey_pdf_preview');
    Route::post('/survey-summary', 'SummaryController@postSurveySummary')->name('summary.post_surveySummary');

    Route::get('/project-document-summary', 'SummaryController@projectDocumentSummary')->name('summary.projectDocumentSummary');
    Route::post('/project-document-summary', 'SummaryController@postProjectDocumentSummary')->name('summary.post_projectDocumentSummary');

    Route::get('/priorityforaction-summary', 'SummaryController@priorityforaction')->name('summary.priorityforaction');
    Route::post('/priorityforaction-summary', 'SummaryController@postpriorityforaction')->name('summary.post_priorityforaction');

    Route::get('/decommissioned-item', 'SummaryController@decommissionedItem')->name('summary.decommissionedItem');
    Route::post('/decommissioned-item', 'SummaryController@postDecommissionedItem')->name('summary.post_decommissionedItem');

    Route::get('/reinspection-freeze', 'SummaryController@reinspectionFreeze')->name('summary.reinspectionFreeze');
    Route::post('/reinspection-freeze', 'SummaryController@postReinspectionFreeze')->name('summary.post_reinspectionFreeze');

    Route::get('/user-cs', 'SummaryController@userCS')->name('summary.userCS');
    Route::post('/user-cs', 'SummaryController@postUserCS')->name('summary.post_userCS');

    Route::get('/project-metadata', 'SummaryController@projectMetaData')->name('summary.projectMetaData');
    Route::post('/project-metadata', 'SummaryController@postprojectMetaData')->name('summary.post_projectMetaData');

    Route::get('/sample-summary', 'SummaryController@sampleSummary')->name('summary.sampleSummary');
    Route::post('/sample-summary', 'SummaryController@postSampleSummary')->name('summary.post_sampleSummary');

    Route::get('/duplication-checker', 'SummaryController@duplicationChecker')->name('summary.duplicationChecker');
    Route::post('/duplication-checker', 'SummaryController@postDuplicationChecker')->name('summary.post_duplicationChecker');

    Route::get('/orchard-summary', 'SummaryController@orchardSummary')->name('summary.orchardSummary');
    Route::post('/orchard-summary', 'SummaryController@postOrchardSummary')->name('summary.postOrchardSummary');

    Route::get('/photography-size', 'SummaryController@photographySize')->name('summary.photography_size');
    Route::post('/photography-size', 'SummaryController@postPhotographySize')->name('summary.post_photography_size');

    Route::get('/project-document', 'SummaryController@projectDocument')->name('summary.project_document');
    Route::post('/project-document', 'SummaryController@postProjectDocument')->name('summary.post_project_document');

    Route::get('/santia-sample-summary', 'SummaryController@santiaSampleSummary')->name('summary.santiaSampleSummary');
    Route::post('/santia-sample-summary', 'SummaryController@postSantiaSampleSummary')->name('summary.post_santiaSampleSummary');

    Route::get('/rejection-type-summary', 'SummaryController@rejectionTypeSummary')->name('summary.rejectionTypeSummary');
    Route::post('/rejection-type-summary', 'SummaryController@postRejectionTypeSummary')->name('summary.post_rejectionTypeSummary');

    Route::get('/property-info-template', 'SummaryController@postPropertyInfoTemplate')->name('summary.property_info_template');

    Route::get('/pdf-preview', 'SummaryController@pdfPreview')->name('summary.pdf_preview');
    Route::post('/survey-preview', 'SummaryController@postAreaCheck')->name('summary.postAreaCheck');
    Route::get('/area-check-preview', 'SummaryController@areaCheckPdfPreview')->name('summary.area_check_pdf_preview');
    Route::get('/asbestos-remedial-pdf-preview', 'SummaryController@asbestosRemedialPdfPreview')->name('summary.asbestos_remedial_pdf_preview');
    Route::get('/high-chart-pdf-preview', 'SummaryController@hightChartPdfPreview')->name('summary.hight_chart');

});

Route::group([
            'prefix'     => 'document',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::post('/edit/{document_id}', 'DocumentController@updateDocument')->name('document.post_edit');
    Route::post('/add', 'DocumentController@insertDocument')->name('document.post_add');
    Route::post('/reject', 'DocumentController@rejectDocument')->name('document.reject');
    Route::get('/approval/{document_id}', 'DocumentController@approvalDocument')->name('document.approval')->where('document_id', '[0-9]+');
    Route::get('/cancel/{document_id}', 'DocumentController@cancelDocument')->name('document.cancel')->where('document_id', '[0-9]+');
    Route::get('/search-document', 'DocumentController@searchDocument')->name('document.search_survey');

});

Route::group([
            'prefix'     => 'summary',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('', 'SummaryController@index')->name('summary.index');

});
Route::group([
            'prefix'     => 'zone',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/all-zone', function (){
        if(\Auth::user()->is_site_operative == 1) {
            return redirect()->route('zone.operative', ['client_id' => 1]);
        }
        return redirect()->route('shineCompliance.zone');
    })->name('zone');
    Route::get('/zone-map', 'ZoneController@zoneMap')->name('zone_map');
    Route::get('/zone-map-child', 'ZoneController@zoneMapChild')->name('zone_map_child');
    Route::get('/detail/{zone_id}', function ($zone_id){
        if(\Auth::user()->is_site_operative == 1) {
            return redirect()->route('zone.operative.detail', ['zone_id' => $zone_id]);
        }
        return redirect()->route('shineCompliance.zone.details', $zone_id);
    })->name('zone.group')->where('zone_id', '[0-9]+');
    Route::post('/edit', 'ZoneController@updateOrCreateZone')->name('zone.edit');
    Route::get('/properties', 'ZoneController@index')->name('zone.operative');
    Route::get('/zone-detail/{zone_id}', 'ZoneController@zoneGroup')->name('zone.operative.detail')->where('zone_id', '[0-9]+');
});

Route::group([
            'prefix'     => 'item',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/add', 'ItemController@getAddItem')->name('item.get_add');
    Route::post('/add', 'ItemController@postAddItem')->name('item.post_add');
    Route::get('/{id}', 'ItemController@index')->name('item.index')->where('id', '[0-9]+');
    Route::get('/edit/{id}', 'ItemController@getEditItem')->name('item.get_edit')->where('id', '[0-9]+');
    Route::post('/edit/{id}', 'ItemController@postEditItem')->name('item.post_edit')->where('id', '[0-9]+');
    Route::post('/decommission/{item_id}', 'ItemController@decommissionItem')->name('item.decommission');
    Route::post('/decommission-reason/{item_id}', 'ItemController@decommissionItemReason')->name('item.decommission_item_reason');
    Route::get('/search-item', 'ItemController@searchItem')->name('item.search_item');
});

Route::group([
            'prefix'     => 'ajax',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/dropdowns-item', 'ItemController@getDropdownItem')->name('ajax.dropdowns-item');
    Route::get('/client-users/{client_id}', 'SurveyController@getClientUsers')->name('ajax.client-users');
    Route::get('/item-summary', 'ItemController@itemSummary')->name('ajax.item_summary');
    Route::post('/pick-sample', 'SurveyController@pickSample')->name('ajax.pick_sample');
    Route::get('/property-group', 'PropertyController@listGroupByClient')->name('ajax.property_group');
    Route::get('/contractor-select', 'ProjectController@contractorSelect')->name('ajax.contractor_select');
    Route::get('/email-cc', 'WorkRequestController@emailCC')->name('ajax.email_cc');
    Route::post('/property-plan', 'PropertyController@updateOrCreatePropertyPlan')->name('ajax.property_plan');
    Route::post('/historical-category', 'PropertyController@updateOrCreateHistoricalCategory')->name('ajax.historical-category');
    Route::post('/historical-doc', 'PropertyController@createHistoricalDoc')->name('ajax.create_historical_doc');
    Route::post('/project-doc', 'DocumentController@insertDocument')->name('ajax.project_doc');
    Route::post('/sample-certificate', 'DocumentController@insertSampleCertificate')->name('ajax.sample_certificate');
    Route::post('/air-test-certificate', 'DocumentController@insertAirTestCertificate')->name('ajax.air_test_certificate');
    Route::post('/training-record', 'DocumentController@insertTrainingRecord')->name('ajax.training_record');
    Route::get('/search-property', 'PropertyController@searchProperty')->name('ajax.search_property');
    Route::get('/search-property-parent', 'PropertyController@searchPropertyParent')->name('ajax.search_property_parent');
    Route::get('/property-info', 'PropertyController@getPropertyInformation')->name('ajax.property_information');
    Route::get('/search-user', 'UserController@searchUser')->name('ajax.search_user');
    Route::get('/search-survey', 'SurveyController@searchSurvey')->name('ajax.search_survey');
    Route::get('/client-zone', 'ZoneController@getClientZone')->name('ajax.client_zone');
    Route::get('/client-zone-child', 'ZoneController@getClientZoneChild')->name('ajax.client_zone_child');
    Route::get('/property-area', 'PropertyController@getPropertyArea')->name('ajax.property_area');
    Route::get('/property-location', 'PropertyController@getPropertyLocation')->name('ajax.property_location');
    Route::post('/summary-service', 'SummaryController@summaryService')->name('ajax.summary_service');
    Route::post('/resource-category', 'ResourceController@postResourceCategory')->name('ajax.resource_category');
    Route::post('/department-management', 'ResourceController@postDepartmentManagement')->name('ajax.depaertment_management');
    Route::post('/resource-document', 'ResourceController@postResourceDocument')->name('ajax.resource_document');
    Route::post('/resource-role', 'ResourceController@updateOrCreateRole')->name('ajax.resource_role');
    Route::post('/audit-trail', 'AuditController@ajaxAudit')->name('ajax.ajax_audit');
    Route::get('/site-operative-training', 'ElearningController@completeTraningSiteOperativeView')->name('ajax.site_operative_training');
    Route::get('/project-training', 'ElearningController@completeTraningProjectManager')->name('ajax.project_training');
    Route::get('/not-assessed-reason', 'HomeController@notAssessedDropdown')->name('ajax.not_assessed_reason');
    Route::get('/decommission-reason', 'HomeController@decommissionDropdown')->name('ajax.decommission_reason');
    Route::get('/recommission-reason', 'HomeController@recommissionDropdown')->name('ajax.recommission_reason');
    Route::get('/work-request-dropdown', 'WorkRequestController@getWorkDropdown')->name('ajax.wr_dropdown');
    Route::get('/property-contact-wr', 'WorkRequestController@propertyContactWr')->name('ajax.property_contact_wr');
    Route::get('/department-select', 'HomeController@departmentSelect')->name('ajax.department_select');
    Route::get('/department-select-parent', 'HomeController@departmentEditSelect')->name('ajax.department_select_parent');
    Route::get('/location-reason', 'LocationController@locationReason')->name('ajax.location_reason');
    Route::get('/search-survey-admin-tool', 'SurveyController@searchSurveyAdminTool')->name('ajax.search_survey_admin_tool');
    Route::get('/search-project', 'ProjectController@searchProject')->name('ajax.search_project');
    Route::get('/property-survey-admin-tool', 'PropertyController@getPropertySurveyAdminTool')->name('ajax.property_survey_admin_tool');
    Route::get('/property-survey', 'PropertyController@getPropertySurvey')->name('ajax.property_survey');
    Route::get('/property-audit', 'PropertyController@getPropertyAudit')->name('ajax.property_audit');
    Route::get('/zone-admin-tool', 'PropertyController@getZoneAdminTool')->name('ajax.zone_admin_tool');
    Route::get('/property-project-admin-tool', 'PropertyController@getPropertyProjectAdminTool')->name('ajax.property_project_admin_tool');
    Route::get('/property-area-admin-tool', 'PropertyController@getPropertyAreaAdminTool')->name('ajax.property_area_admin_tool');
    Route::get('/property-location-admin-tool', 'PropertyController@getPropertyLocationAdminTool')->name('ajax.property_location_admin_tool');
    Route::get('/property-item-admin-tool', 'PropertyController@getPropertyItemAdminTool')->name('ajax.property_item_admin_tool');
    Route::get('/search-document', 'PropertyController@searchDocument')->name('ajax.document_admin_tool');
    Route::get('/check-sor-logic', 'WorkRequestController@checkSorLogic')->name('ajax.check_sor_logic');
});

Route::group([
            'prefix'     => 'location',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('add', 'LocationController@getAddLocation')->name('get_add_location');
    Route::post('add', 'LocationController@postAddLocation')->name('post_add_location');
    Route::get('edit/{id}', 'LocationController@getEditLocation')->name('get_edit_location')->where('id', '[0-9]+');
    Route::post('edit/{id}', 'LocationController@postEditLocation')->name('post_edit_location')->where('id', '[0-9]+');
    Route::post('/decommission/{location_id}', 'LocationController@decommissionLocation')->name('location.decommission');
    Route::post('/decommission-reason/{location_id}', 'LocationController@decommissionLocationReason')->name('location.decommission_location_reason');
});

Route::group([
            'prefix'     => 'surveys',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/add/{property_id}', 'SurveyController@getAddSurvey')->name('survey.get_add');
    Route::post('/add/{property_id}', 'SurveyController@postAddSurvey')->name('survey.post_add');
    Route::get('/edit/{survey_id}', 'SurveyController@getEditSurvey')->name('survey.get_edit');
    Route::post('/edit/{survey_id}', 'SurveyController@postEditSurvey')->name('survey.post_edit');
    Route::get('/{survey_id}', 'SurveyController@index')->name('property.surveys');
    Route::get('/survey-information/{survey_id}/{type}', 'SurveyController@getEditSurveyInformation')->name('survey-information');
    Route::post('/survey-information/{survey_id}/{type}', 'SurveyController@postEditSurveyInformation')->name('post.survey-information');
    Route::post('/methodOption/{survey_id}/{type}', 'SurveyController@methodOption')->name('method_option');
    Route::get('/method-questionaire/{survey_id}', 'SurveyController@getMethodQuestion')->name('get.method_question');
    Route::post('/method-questionaire/{survey_id}', 'SurveyController@postMethodQuestion')->name('post.method_question');
    Route::post('/create-area', 'SurveyController@createArea')->name('survey.post_area');
    Route::get('/property-information/{survey_id}', 'SurveyController@getEditPropertyInfo')->name('get.edit_propertyInfo');
    Route::post('/property-information/{survey_id}', 'SurveyController@postEditPropertyInfo')->name('post.edit_propertyInfo');

    Route::post('/publish/{survey_id}', 'GeneratePDFController@publishSurveyPDF')->name('survey.publish');
    Route::get('/manual/publish', 'GeneratePDFController@getPublishSurveyManual')->name('survey.get_publish_manual');
    Route::post('/manual/publish', 'GeneratePDFController@postPublishSurveyManual')->name('survey.post_publish_manual');

    Route::post('/approval/{survey_id}', 'SurveyController@approvalSurvey')->name('survey.approval');
    Route::post('/send/{survey_id}', 'SurveyController@sendSurvey')->name('survey.send');
    Route::post('/reject/{survey_id}', 'SurveyController@rejectSurvey')->name('survey.reject');
    Route::post('/cancel/{survey_id}', 'SurveyController@cancelSurvey')->name('survey.cancel');
    Route::post('/decommission/{survey_id}', 'SurveyController@decommissionSurvey')->name('survey.decommission');
    Route::post('/decommission/area/{area_id}', 'SurveyController@decommissionArea')->name('survey.decommission_area');
    Route::post('/decommission-reason/area/{area_id}', 'SurveyController@decommissionAreaReason')->name('survey.decommission_area_reason');

});

Auth::routes();
Route::get('/login', 'Auth\LoginController@index')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name("post_login");
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/register', 'Auth\RegisterController@index')->name('register');
Route::post('/register', 'Auth\RegisterController@register')->name('post_register');
Route::post('/password/email', 'Auth\ResetPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/client-departments/{organisation}', 'Auth\RegisterController@getClientDepartments')->name('get-client-departments');

Route::group([
            'prefix'     => 'user',
            'middleware' => [
                'auth',
            ],
        ], function() {
Route::get('/profile/{id}', function ($id) {
    return redirect()->route('shineCompliance.profile-shineCompliance',['id' => $id]);
})->name('profile')->where('id', '[0-9]+');
Route::get('/edit/{id}', 'UserController@getEdit')->name('user.get-edit')->where('id', '[0-9]+');
Route::post('/edit/{id}', 'UserController@postEdit')->name('user.post-edit')->where('id', '[0-9]+');
Route::get('/change-password/{id}', 'UserController@getChangePassword')->name('user.get_change_password')->where('id', '[0-9]+');
Route::post('/change-password/{id}', 'UserController@postChangePassword')->name('user.post_change_password')->where('id', '[0-9]+');
Route::get('/lock', 'UserController@profile')->name('user.lock');
Route::get('/confirm-new-email/{token}', 'UserController@getConfirmNewEmail')->name('user.get-confirm-email');
Route::post('/confirm-new-email', 'UserController@postConfirmNewEmail')->name('user.post-confirm-email');
Route::get('/lock/{id}', 'UserController@lock')->name('user.lock')->where('id', '[0-9]+');
Route::get('/first-change-password/{id}', 'UserController@firstResetPassword')->name('user.first_change_password')->where('id', '[0-9]+');
});


Route::prefix('migration')->group(function () {
   Route::get('/migrate_user', 'MigrationController@migrate_user');
   Route::get('/migrate_property', 'MigrationController@migrate_property');
   Route::get('/migrate_client', 'MigrationController@migrate_client');
   Route::get('/migrate_department', 'MigrationController@migrate_department');
   Route::get('/migrate_property_dropdown', 'MigrationController@migrate_property_dropdown');
   Route::get('/migrate_zone', 'Migration\MigrationZoneController@migrate_zone');
   Route::get('/migrate_dropdown/{type}', 'Migration\MigrationDropdownController@migrate_dropdown')->where('type', '[0-9]+');
   Route::get('/migrate_shine_document_storage', 'Migration\MigrationShineDocumentStorageController@migrate_shine_storage');
   Route::get('/migrate_location', 'Migration\MigrationLocationController@migrate_location');
   Route::get('/migrate_area', 'Migration\MigrationAreaController@migrate_area');
   Route::get('/migrate_item', 'Migration\MigrationItemController@migrate_item');
   Route::get('/migrate_survey', 'Migration\MigrationSurveyController@migrate_survey');
    Route::get('/migrate_publish_survey', 'Migration\MigrationPublishSurveyController@migrate_publish_survey');
    Route::get('/migrate_summary_pdf', 'Migration\MigrationSummaryPDFController@migrate_summary_pdf');
    Route::get('/migrate_counter', 'Migration\MigrationCounterController@migrate_counter');
    Route::get('/migrate_historic_doc', 'Migration\MigrationHistoricDocController@migrate_historic');
    Route::get('/migrate_project', 'Migration\MigrationProjectController@migrate_project');
    Route::get('/migrate_loglogin', 'Migration\MigrationLogLoginController@migrate_logLogin');
    Route::get('/migrate_project_type', 'Migration\MigrationProjectTypeController@migrate_project_type');
   Route::get('/upload_data', 'Migration\MigrationUploadDataController@migrate_upload_data');
   Route::get('/manifest', 'Migration\MigrationManifestController@migrate_manifest');
   Route::get('/shine_app_storage', 'Migration\MigrationShineAppStorageController@migrate_shine_storage');
   Route::get('/comment', 'Migration\MigrationCommentHistoryController@migrate_comment_history');
    Route::get('/blue_light_service', 'Migration\MigrationDocumentBlueLightServiceController@migrate_data');
    Route::get('/elearning', 'Migration\MigrationElearningController@migrate_data');
   Route::get('/test_send_mail', 'MigrationController@testSendMail');
   Route::get('/main-table', 'Migration\MigrationMainTableController@index');
   Route::get('/sample', 'Migration\MigrationSampleController@migrate_sample_data');
   Route::get('/data-stamp/{type}', 'Migration\MigrationDataStampController@index');
   Route::get('/work-request/{type}', 'Migration\MigrationWorkRequest@index');
});

Route::post('/generate-chart', 'ChartController@generateChart')->name('chart.generate');
// for PDF in summary
Route::get('/generate-chart-summary', 'ChartController@generatePDFchart')->name('chart.summary.generate');

Route::get('/retrieve_asbestos_register/{type}/{id}', 'GeneratePDFController@createRegisterPDF')->name('register.pdf')->where(['type' => '[0-9]+', 'id' => '[0-9]+']);
Route::get('/view_asbestos_register/{type}/{id}', 'GeneratePDFController@viewRegisterPDF')->name('view_property_register_pdf')->where(['type' => '[0-9]+', 'id' => '[0-9]+']);

Route::prefix('test')->group(function () {
    Route::get('/test_generate_pdf', 'GeneratePDFController@downloadPropertyPDF');
});

Route::group([
            'prefix'     => 'pdf',
            'middleware' => [
            ],
        ], function() {
    Route::get('/generate-pdf', 'GeneratePDFController@generatePdfFromFile')->name('pdf.generate');
    Route::get('/view-pdf/{type}/{id}', 'GeneratePDFController@viewPDF')->name('survey.view.pdf');
    Route::get('/download-pdf/{type}/{id}', 'GeneratePDFController@downloadPDF')->name('survey.download.pdf');
    Route::get('/download-pdf-file-viewing/{type}/{id}/{property_id}', 'GeneratePDFController@downloadFile')->name('retrive_file');
    Route::get('/download-summary-pdf/{type}/{id}', 'GeneratePDFController@downloadFileSummary')->name('download_summary');
    Route::get('/view-summary-pdf/{type}/{id}', 'GeneratePDFController@viewFileSummary')->name('view_summary');
    Route::get('/retrieve_image/{type}/{id}/{is_view?}', 'GeneratePDFController@downloadImage')->name('retrive_image');
});

Route::get('/search', 'SearchController@search')->name('search-autocomplete');

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/clear-view', function() {
    Artisan::call('cache:clear');
    return "View is cleared";
});
//refresh token for some survey add/edit form that need to set up for hours
Route::get('refresh-csrf', function(){
    return csrf_token();
});

Route::get('/download-backup/{file_name}', function() {
    Artisan::call('cache:clear');
    return "View is cleared";
});
Route::get('/download-backup/{file_name}', 'ResourceController@download_backup');
Route::get('/download-backup-folder/{type}/{file_name}', 'ResourceController@download_backup_folder');

Route::group([
            'prefix'     => 'work-request',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/', 'WorkRequestController@getList')->name('wr.get_list');
    Route::get('/add', 'WorkRequestController@getAdd')->name('wr.get_add');
    Route::post('/add', 'WorkRequestController@postAdd')->name('wr.post_add');

    Route::get('/edit/{id}', 'WorkRequestController@getEdit')->name('wr.get_edit');
    Route::post('/edit/{id}', 'WorkRequestController@postEdit')->name('wr.post_edit');

    Route::get('/details/{id}', 'WorkRequestController@getDetail')->name('wr.details');
    Route::post('/decommission/{id}', 'WorkRequestController@decommission')->name('wr.decommission');
    Route::get('/recommission/{id}', 'WorkRequestController@decommission')->name('wr.recommission');

    Route::post('/reject/{id}', 'WorkRequestController@rejectWorkRequest')->name('wr.reject');
    Route::post('/approval/{id}', 'WorkRequestController@approvalWorkRequest')->name('wr.approval');
    Route::post('/publish/{id}', 'GeneratePDFController@publishWorkRequestPDF')->name('wr.publish');
    Route::post('/document', 'WorkRequestController@updateOrCreateDocument')->name('wr.post_document');

});

Route::group([
            'prefix'     => 'tool-box',
            'middleware' => [
                'auth',
            ],
        ], function() {
    Route::get('/remove', 'ToolBoxController@remove')->name("toolbox.remove");
    Route::post('/remove', 'ToolBoxController@postRemove')->name("toolbox.post_remove");
    Route::get('/move', 'ToolBoxController@move')->name("toolbox.move");
    Route::post('/post-move', 'ToolBoxController@postMove')->name("toolbox.post_move");
    Route::get('/merge', 'ToolBoxController@merge')->name("toolbox.merge");
    Route::post('/post-merge', 'ToolBoxController@postMerge')->name("toolbox.post_merge");
    Route::get('/unlock', 'ToolBoxController@unlock')->name("toolbox.unlock");
    Route::post('/post-unlock', 'ToolBoxController@postUnlock')->name("toolbox.post_unlock");
    Route::get('/revert', 'ToolBoxController@revert')->name("toolbox.revert_back");
    Route::post('/post-revert', 'ToolBoxController@postRevert')->name("toolbox.post_revert_back");
    Route::get('/logs', 'ToolBoxLogController@index')->name("toolboxlog");
    Route::post('/roll-back', 'ToolBoxLogController@revertBackAction')->name("toolbox.revert");
});

Route::group([
    'prefix'     => 'orchard',
], function() {
    Route::get('/api_no1', 'OrchardController@api_no1')->name("orchard.api_no1");
    Route::post('/api_no2', 'OrchardController@api_no2')->name("orchard.api_no2");
    Route::get('/api_no3', 'OrchardController@api_no3')->name("orchard.api_no3");
    Route::get('/api_no4', 'OrchardController@api_no4')->name("orchard.api_no4");
    Route::get('/api_no5', 'OrchardController@api_no5')->name("orchard.api_no5");
    Route::get('/api_no6', 'OrchardController@api_no6')->name("orchard.api_no6");
});

Route::group([
    'prefix'     => 'admin-tool',
], function() {
    Route::get('/update-property', 'AdminToolController@updateProperty')->name("admin_tool.update_property");
    Route::post('/update-property', 'AdminToolController@postUpdateProperty')->name("admin_tool.post_update_property");
    Route::get('/update-property-template', 'AdminToolController@postUpdatePropertyTemplate')->name('admin_tool.post_update_property_template');
});

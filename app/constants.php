<?php
define('USER_SIGNATURE', 'um');
define('AVATAR', 'ar');
define('PROPERTY_PHOTO', 'im');
define('USER_SIGNATURE_PATH', 'user/image');
define('AVATAR_PATH', 'user/ar');
define('EMPTY_PHOTO', '999');

define('PROPERTY_IMAGE', 'im');
define('PROPERTY_IMAGE_PATH', 'property/image');
define('PROPERTY_PDF', 'asbestosRegister');
define('PROPERTY_PDF_PATH', 'property/pdf');
define('PROPERTY_NOACM_STATE', 'noacm');

define('BLUELIGHT_SERVICE_FOLDER', 'bluelightservice/groups');

define('CLIENT_LOGO', 'om');
define('CLIENT_LOGO_PATH', 'organisation/logo');
define('UKAS_IMAGE', 'ukas');
define('UKAS_IMAGE_PATH', 'organisation/ukas_image');
define('UKAS_TESTING_IMAGE', 'ukasT');
define('UKAS_TESTING_IMAGE_PATH', 'organisation/ukas_testing_image');

define('LOCATION_IMAGE', 'lm');
define('LOCATION_IMAGE_PATH', 'locations');

define('TRAINING_RECORD_FILE', 'tr');
define('TRAINING_RECORD_FILE_PATH', 'organisations');

define('DOCUMENT_FILE', 'documents');
define('DOCUMENT_FILE_PATH', 'documents/projects');

define('RESOURCE_DOCUMENT', 'rd');
define('RESOURCE_DOCUMENT_PATH', 'documents/resources');

define('POLICY_FILE', 'plc');
define('POLICY_FILE_PATH', 'organisations');

define('PLAN_FILE', 'p');
define('SURVEY_PLAN_FILE_PATH', 'documents/survey/plan');
define('PROPERTY_PLAN_FILE_PATH', 'documents/property/plan');

define('HISTORICAL_DATA', 'hd');
define('HISTORICAL_DATA_PATH', 'documents/property/historical');

define('SAMPLE_CERTIFICATE_FILE', 'sct');
define('SAMPLE_CERTIFICATE_FILE_PATH', 'documents/survey/sample-certificate');

define('AIR_TEST_CERTIFICATE_FILE', 'atc');
define('IR_TEST_CERTIFICATE_FILE_PATH', 'documents/survey/air-test-certificate');

define('WORK_REQUEST_FILE', 'wd');
define('WORK_REQUEST_FILE_PATH', 'work/documents');

define('ITEM_PHOTO', 'ip');
define('ITEM_PHOTO_PATH', 'item/photo');
define('ITEM_PHOTO_LOCATION', 'ipl');
define('ITEM_PHOTO_LOCATION_PATH', 'item/photo_location');
define('ITEM_PHOTO_ADDITIONAL', 'ipa');
define('ITEM_PHOTO_ADDITIONAL_PATH', 'item/photo_addition');

define('ZONE_PHOTO', 'zon');
define('ZONE_PHOTO_PATH', 'zone/property_group');


define('PROPERTY_SURVEY_IMAGE', 'psm');
define('PROPERTY_SURVEY_IMAGE_PATH', 'survey/image');

// end of file path

define('PROPERTY_DECOMMISSION', 1);
define('PROPERTY_UNDECOMMISSION', 0);

define('ZONE_DECOMMISSION', 1);
define('ZONE_UNDECOMMISSION', 0);

define('THUMB_NAIL', 'thumb_nail');

define('STATUS_OK', 200);
define('STATUS_FAIL', 400);
define('STATUS_FAIL_CLIENT', 302);
define('MAX_PROPERTY_CONTACT', 10);

//Decommission type
define('DECOMMISSION', 1);
define('NOT_ASSESSED', 2);
define('RELEASE_FROM_SCOPE', 3);
define('RECOMMISSION', 4);

define('REQUIRE_VALUE', 1);
define('NOT_REQUIRE_VALUE', 0);
//for survey
define('NEW_SURVEY_STATUS', 1);
define('LOCKED_SURVEY_STATUS', 2);//lock
define('READY_FOR_QA_SURVEY_STATUS', 3);
define('PULISHED_SURVEY_STATUS', 4);//lock
define('COMPLETED_SURVEY_STATUS', 5);
define('REJECTED_SURVEY_STATUS', 6);
define('SENT_BACK_FROM_DEVICE_SURVEY_STATUS', 7);
define('ABORTED_SURVEY_STATUS', 8);
define('SURVEY_LOCKED', 1);
define('SURVEY_UNLOCKED', 0);
define('SURVEY_DECOMMISSION', 1);
define('SURVEY_UNDECOMMISSION', 0);
define('PROJECT_DECOMMISSION', 1);
define('PROJECT_UNDECOMMISSION', 0);
define('MANAGEMENT_SURVEY', 1);
define('REFURBISHMENT_SURVEY', 2);
define('RE_INSPECTION_REPORT', 3);
define('DEMOLITION_SURVEY', 4);
define('MANAGEMENT_SURVEY_PARTIAL', 5);
define('SAMPLE_SURVEY', 6);
define('ACTIVE', 1);
define('IN_ACTIVE', 0);


define('ORGANISATION_TYPE', 'my-organisation');
define('CONTRACTOR_TYPE', 'contractor');
define('USER_LOCKED', 1);
define('USER_UNLOCKED', 0);


define('PROGRAME_TYPE_OTHER', 17);

define('SURVEY_ANSWER_OTHER', 1);

define('METHOD_STYLE_TEXT_BOX', 0);
define('METHOD_STYLE_QUESTION', 1);

define('DROPDOWN_SURVEY_ID', 300);

define('SECTION_DEFAULT', 1);
define('SECTION_AREA_FLOORS_SUMMARY', 2);
define('SECTION_AREA_FLOOR_DETAILS', 3);
define('SECTION_ROOM_LOCATION_DETAILS', 4);
define('SECTION_ROOM_LOCATION_SUMMARY', 5);

define('TYPE_HIGH_RISK_ITEM_SUMMARY', 'high_risk_items');
define('TYPE_MEDIUM_RISK_ITEM_SUMMARY', 'medium_risk_items');
define('TYPE_LOW_RISK_ITEM_SUMMARY', 'low_risk_items');
define('TYPE_VERY_LOW_RISK_ITEM_SUMMARY', 'very_low_risk_items');
define('TYPE_NO_RISK_ITEM_SUMMARY', 'no_risk_items');
define('TYPE_INACCESS_ACM_ITEM_SUMMARY', 'inaccessible_acm_items');
define('TYPE_INACCESS_ROOM_SUMMARY', 'inaccessible_rooms');
define('TYPE_All_ACM_ITEM_SUMMARY', 'all_acm_items');

//for pagination items
define('TYPE_CLIENT', 1);
define('TYPE_ZONE', 2);
define('TYPE_PROPERTY', 3);
define('TYPE_REPORT', 4);
define('TYPE_AREA', 5);
define('TYPE_LOCATION', 6);
define('TYPE_SITE_OPERSTIVE', 7);
define('TYPE_SAMPLE', 8);

//for location
define('LOCATION_VOID_ID', [21, 23,24 ,25, 27, 30, 31 ]);
define('LOCATION_CONTRUCTION_DETAILS_ID', [20, 22, 26, 28, 29]);
define('LOCATION_REASONS', 6);

define('LOCATION_CEILING_VOID', 21);
define('LOCATION_CAVITIES', 23);
define('LOCATION_RISERS', 24);
define('LOCATION_DUCTING', 25);
define('LOCATION_FLOOR_VOID', 27);
define('LOCATION_PIPEWORK', 30);
define('LOCATION_BOXING', 31);

define('LOCATION_CEILING', 20);
define('LOCATION_WALLS', 22);
define('LOCATION_FLOOR', 26);
define('LOCATION_DOORS', 28);
define('LOCATION_WINDOWS', 29);

define('LOCATION_STATE_INACCESSIBLE', 0);
define('LOCATION_STATE_ACCESSIBLE', 1);
define('LOCATION_STATE_NO_ACM', 2);
define('LOCATION_LOCKED', 1);
define('LOCATION_UNLOCKED', 0);
define('LOCATION_DECOMMISSION', 1);
define('LOCATION_UNDECOMMISSION', 0);

// out of scope void
define('CELLING_VOID_OOS', 1994);
define('CAVITY_OOS', 1995);
define('RISER_OOS', 1996);
define('DUCTING_OOS', 1997);
define('FLOOR_VOID_OOS', 2000);
define('PIPE_WORK_OOS', 1999);
define('BOXING_OOS', 1998);
// out of construction
define('CELLING_CONSTRUCTION_OOS', 2281);
define('WALL_CONSTRUCTION_OOS', 2282);
define('FLOOR_CONSTRUCTION_OOS', 2283);
define('DOOR_CONSTRUCTION_OOS', 2284);
define('WINDOWN_CONSTRUCTION_OOS', 2285);
//for area
define('AREA_LOCKED', 1);
define('AREA_UNLOCKED', 0);
define('AREA_DECOMMISSION', 1);
define('AREA_UNDECOMMISSION', 0);

//for item
define('ITEM_NOACM_STATE', 1);
define('ITEM_INACCESSIBLE_STATE', 2);
define('ITEM_ACCESSIBLE_STATE', 3);
define('ITEM_DECOMMISSION', 1);
define('ITEM_UNDECOMMISSION', 0);
define('ITEM_LOCKED', 1);
define('ITEM_UNLOCKED', 0);
define('ITEM_REQUIRE_RAND_ELEMENT', 1);
define('ITEM_NOT_REQUIRE_RAND_ELEMENT', 0);
define('ITEM_LIMIT_ASSESSMENT', 1);
define('ITEM_FULL_ASSESSMENT', 2);
define('ITEM_NON_ASBESTOS_TYPE_ID', 62);
define('ITEM_ASBESTOS_TYPE_ID', 174);

// if product debris = Gasket
define('ITEM_ASBESTOS_WARNING_ID', [254,255,256,257,258,259,260,1851,1852,1853,1854,1855,1856,1857]);

define('PRIMARY_AND_SECONDARY_USE_ID', 1);
define('CONSTRUCTION_DETAILS_ID', 2);
define('PRODUCT_DEBRIS_TYPE_ID', 3);
define('EXTENT_ID', 4);
define('ASBESTOS_TYPE_ID', 5);
define('NO_ACCESS_REASON_ID', 6);
define('ACTIONS_RECOMMENDATIONS_ID', 7);
define('ADDITIONAL_INFORMATION_ID', 8);
define('SAMPLE_COMMENTS_ID', 9);
define('AIR_TEST_COMMENTS_ID', 10);
define('SPECIFIC_LOCATION_ID', 11);
define('ACCESSIBILITY_VULNERABILITY_ID', 12);
define('LICENSED_NONLICENSED_ID', 13);
define('UNABLE_TO_SAMPLE_ID', 14);
define('ITEM_NO_ACCESS_ID', 15);
define('NO_ACM_COMMENTS_ID', 16);
define('SIZE_VOLUME_ID', 17);
define('PRIORITY_ASSESSMENT_RISK_ID', 18);
define('MATERIAL_ASSESSMENT_RISK_ID', 19);
define('SAMPLE_ID', 500);
define('sUB_SAMPLE_ID', 502);
define('PROPERTY_INFO_PROPERTY_STATUS_ID', 1);
define('PROPERTY_INFO_PROPERTY_OCCUPIED_ID', 2);
define('PROPERTY_INFO_LISTED_BUILDING_ID', 3);
define('PROPERTY_INFO_PARKING_ARRANGEMENTS_ID', 4);
define('PROPERTY_INFO_EVACUATION_STRATEGY_ID', 6);
define('PROPERTY_INFO_FRA_OVERALL_RISK_ID', 5);
define('PROPERTY_INFO_STAIR_ID', 7);
define('PROPERTY_INFO_FLOOR_ID', 8);
define('PROPERTY_INFO_WALL_CONSTRUCTION_ID', 9);
define('PROPERTY_INFO_WALL_FINISH_ID', 10);
define('PROPERTY_INFO_PROPERTY_TYPE_ID', 11);

define('ASSESSMENT_TYPE_KEY', 600);
define('ASSESSMENT_DAMAGE_KEY', 604);
define('EXTENT_PARENT_ID', 367);
define('ASSESSMENT_TREATMENT_KEY', 609);
define('ASSESSMENT_ASBESTOS_KEY', 614);
define('PRIORITY_ASSESSMENT_ACTIVITY_PRIMARY_KEY', 619);
define('PRIORITY_ASSESSMENT_ACTIVITY_SECONDARY_KEY', 624);
define('PRIORITY_ASSESSMENT_LOCATION_KEY', 630);
define('PRIORITY_ASSESSMENT_ACCESSIBILITY_KEY', 635);
define('PRIORITY_ASSESSMENT_EXTENT_KEY', 640);
define('PRIORITY_ASSESSMENT_OCCUPANTS_KEY', 646);
define('PRIORITY_ASSESSMENT_FREQUENCY_OF_USE_KEY', 651);
define('PRIORITY_ASSESSMENT_TIME_IN_AREA_KEY', 656);
define('PRIORITY_ASSESSMENT_TYPE_OF_ACTIVITY_KEY', 662);
define('PRIORITY_ASSESSMENT_FREQUENCY_OF_ACTIVITY_KEY', 667);

//table name
define('ITEM_ACCESSIBILITY_VULNERABILITY', 'tbl_item_accessibility_vulnerability');
define('ITEM_ACTION_RECOMMENDATION', 'tbl_item_action_recommendation');
define('ITEM_ADDITIONAL_INFORMATION', 'tbl_item_additional_information');
define('ITEM_ASBESTOS_TYPE', 'tbl_item_asbestos_type');
define('ITEM_EXTENT', 'tbl_item_extent');
define('ITEM_ITEM_NO_ACCESS', 'tbl_item_no_access');
define('ITEM_LICENSED_NON_LICENSED', 'tbl_item_licensed_non_licensed');
define('ITEM_MATERIAL_ASSESSMENT_RISK', 'tbl_item_material_assessment_risk');
define('ITEM_NO_ACM_COMMENTS', 'tbl_item_no_acm_comments');
define('ITEM_PRIORITY_ASSESSMENT_RISK', 'tbl_item_priority_assessment_risk');
define('ITEM_PRODUCT_DEBRIS_TYPE', 'tbl_item_product_debris_type');
define('ITEM_SAMPLE_COMMENT', 'tbl_item_sample_comment');
define('ITEM_SAMPLE_ID', 'tbl_item_sample_id');
define('ITEM_SPECIFIC_LOCATION', 'tbl_item_specific_location');
define('ITEM_SUB_SAMPLE_ID', 'tbl_item_sub_sample_id');
define('ITEM_UNABLE_TO_SAMPLE', 'tbl_item_unable_to_sample');
define('ITEM_ACCESSIBILITY_VULNERABILITY_VALUE', 'tbl_item_accessibility_vulnerability_value');
define('ITEM_ACTION_RECOMMENDATION_VALUE', 'tbl_item_action_recommendation_value');
define('ITEM_ADDITIONAL_INFORMATION_VALUE', 'tbl_item_additional_information_value');
define('ITEM_ASBESTOS_TYPE_VALUE', 'tbl_item_asbestos_type_value');
define('ITEM_EXTENT_VALUE', 'tbl_item_extent_value');
define('ITEM_ITEM_NO_ACCESS_VALUE', 'tbl_item_no_access_value');
define('ITEM_LICENSED_NON_LICENSED_VALUE', 'tbl_item_licensed_non_licensed_value');
define('ITEM_MATERIAL_ASSESSMENT_RISK_VALUE', 'tbl_item_material_assessment_risk_value');
define('ITEM_NO_ACM_COMMENTS_VALUE', 'tbl_item_no_acm_comments_value');
define('ITEM_PRIORITY_ASSESSMENT_RISK_VALUE', 'tbl_item_priority_assessment_risk_value');
define('ITEM_PRODUCT_DEBRIS_TYPE_VALUE', 'tbl_item_product_debris_type_value');
define('ITEM_SAMPLE_COMMENT_VALUE', 'tbl_item_sample_comment_value');
define('ITEM_SAMPLE_ID_VALUE', 'tbl_item_sample_id_value');
define('ITEM_SPECIFIC_LOCATION_VALUE', 'tbl_item_specific_location_value');
define('ITEM_SUB_SAMPLE_ID_VALUE', 'tbl_item_sub_sample_id_value');
define('ITEM_UNABLE_TO_SAMPLE_VALUE', 'tbl_item_unable_to_sample_value');

define('ITEM_REASON_OTHER', 592);
define('ITEM_SPECIFIC_LOCATION_OTHER', 566);
define('ITEM_PRODUCT_DEBRIS_OTHER', 366);
define('ITEM_ASBETOS_TYPE_OTHER', 383);
define('ITEM_ASBETOS_TYPE_AMPHIBOLE', 398);
define('ITEM_ADDITIONAL_INFO_OTHER', 520);

define('ITEM_HAS_ONE_RELATION_TABLES', ['AsbestosTypeValue','ExtentValue','ProductDebrisTypeValue','ActionRecommendationValue','AdditionalInformationValue','SampleCommentValue','SpecificLocationValue','AccessibilityVulnerabilityValue','LicensedNonLicensedValue','UnableToSampleValue','ItemNoAccessValue','NoACMCommentsValue','SampleIdValue','SubSampleIdValue']);
define('ITEM_ALL_RELATION_TABLES', ['itemInfo','AsbestosTypeValue','ExtentValue','ProductDebrisTypeValue','ActionRecommendationValue','AdditionalInformationValue','SampleCommentValue','SpecificLocationValue','AccessibilityVulnerabilityValue','LicensedNonLicensedValue','UnableToSampleValue','ItemNoAccessValue','NoACMCommentsValue','SampleIdValue','SubSampleIdValue']);

// project
define('PROJECT_SURVEY_ONLY', 1);
define('PROJECT_REMEDIATION_REMOVAL', 2);
define('PROJECT_DEMOLITION', 3);
define('PROJECT_ANALYTICAL', 4);
define('FIRE_ASSESSMENT', 5);
define('FIRE_REMEDIAL_ASSESSMENT', 6);
define('FIRE_INDEPENDENT_ASSESSMENT', 7);
define('FIRE_EQUIPMENT_ASSESSMENT', 12);
define('LEGIONELLA_ASSESSMENT', 8);
define('WATER_TESTING_ASSESSMENT', 9);
define('WATER_REMEDIAL_ASSESSMENT', 10);
define('WATER_TEMP_ASSESSMENT', 11);
define('HS_ASSESSMENT_PROJECT', 13);

define('CONTRACTOR_DOC_CATEGORY', 5);
define('TENDER_DOC_CATEGORY', 4);
define('GSK_DOC_CATEGORY', 7);
// remove
define('PLANNING_DOC_CATEGORY', 1);
define('PRE_START_DOC_CATEGORY', 2);
define('SITE_RECORDS_DOC_CATEGORY', 6);
define('COMPLETION_DOC_CATEGORY', 3);
define('APPROVAL_DOC_CATEGORY', 8);
define('PRE_CONSTRUCTION_DOC_CATEGORY', 9);
define('DESIGN_DOC_CATEGORY', 10);
define('COMMERCIAL_DOC_CATEGORY', 11);

define('APPROVAL_DOCUMENT_CATEGORY_ID', [CONTRACTOR_DOC_CATEGORY, PLANNING_DOC_CATEGORY, PRE_START_DOC_CATEGORY, SITE_RECORDS_DOC_CATEGORY, COMPLETION_DOC_CATEGORY, APPROVAL_DOC_CATEGORY ]);

// privilege
define('TENDER_DOC_PRIVILEGE', 1);
define('CONTRACTOR_DOC_PRIVILEGE', 2);
define('PLANNING_DOC_PRIVILEGE', 11);
define('PRE_START_DOC_PRIVILEGE', 12);
define('SITE_RECORDS_DOC_PRIVILEGE', 13);
define('COMPLETION_DOC_DOC_PRIVILEGE', 14);
//status
define('PROJECT_CREATED_STATUS', 1);
define('PROJECT_TECHNICAL_IN_PROGRESS_STATUS', 2);
define('PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS', 3);
define('PROJECT_READY_FOR_ARCHIVE_STATUS', 4);
define('PROJECT_COMPLETE_STATUS', 5);
define('PROJECT_REJECTED_STATUS', 6);

// project progress stage
define('PROJECT_STAGE_PRE_CONSTRUCTION', 9);
define('PROJECT_STAGE_DESIGN', 10);
define('PROJECT_STAGE_COMMERCIAL', 11);
define('PROJECT_STAGE_PLANNING', 1);
define('PROJECT_STAGE_PRE_START', 2);
define('PROJECT_STAGE_SITE_RECORD', 6);
define('PROJECT_STAGE_COMPLETION', 3);
// Project document status
define('PROJECT_DOC_CREATED', 1);
define('PROJECT_DOC_COMPLETED', 2);
define('PROJECT_DOC_REJECTED', 3);
define('PROJECT_DOC_PUBLISHED', 4);
define('PROJECT_DOC_CANCELLED', 5);

//status work request
define('WORK_REQUEST_CREATED_STATUS', 1);
define('WORK_REQUEST_READY_QA', 2);
define('WORK_REQUEST_AWAITING_APPROVAL', 3);
define('WORK_REQUEST_COMPLETE', 4);
define('WORK_REQUEST_REJECT', 5);
define('MAJOR_WORK_ID', 35);
define('FIRE_MAJOR_WORK_ID', 57);

//operative view
define('OPERATIVE_ACTIVE)', 1);


define('CONTRACTOR_DOC_TYPE', 'contractor');
define('TENDER_DOC_TYPE', 'tender');
define('GSK_DOC_TYPE', 'gskdocument');
define('PLANNING_DOC_TYPE', 'planning');
define('PRE_START_DOC_TYPE', 'prestart');
define('SITE_RECORD_DOC_TYPE', 'siterecords');
define('COMPLETION_DOC_TYPE', 'completion');
define('EVALUATIONS_DOC_TYPE', 'evaluation');
define('CLARIFICATION_DOC_TYPE', 'clarification');
define('PRE_CONSTRUCTION_DOC_TYPE', 'preconstruction');
define('DESIGN_DOC_TYPE', 'design');
define('COMMERCIAL_DOC_TYPE', 'commercial');


define('ALL_CLIENT_TRAINING_ID', 0);

// WORK REQUEST
define('WR_PRIORITY_DROPDOWN', 4);
define('WR_PARKING_ARRANGE_DROPDOWN', 5);
define('WR_CEILLING_HEIGHT_DROPDOWN', 6);

define('MIN_PASS_LENGTH', 8);
define('MAX_PASS_LENGTH', 20);
define('PREV_PASS_NOT_ALLOWED_NO', 13);

//PDF download type for test
define('DOWNLOAD_PDF', 1);
define('VIEW_PDF', 2);
define('VIEW_BROWSER', 3);
//view
define('VIEW_SURVEY_PDF', 1);
define('VIEW_WORK_PDF', 2);
//download
define('DOWNLOAD_SURVEY_PDF', 1);

//create register pdf
define('PROPERTY_REGISTER_PDF', 1);
define('AREA_REGISTER_PDF', 2);
define('LOCATION_REGISTER_PDF', 3);
define('FIRE_REGISTER_PDF', 4);
define('WATER_REGISTER_PDF', 5);
//type for summary pdf
define('TYPE_AREA_CHECK', 1);
define('TYPE_ASBESTOS_REGISTER', 2);
define('TYPE_DIRECTOR_OVERVIEW', 3);
define('TYPE_MANAGER_OVERVIEW', 4);
define('TYPE_MATERIAL', 5);
define('TYPE_OVERALL', 6);
define('TYPE_PRIORITY', 7);
define('TYPE_ROOMCHECK', 8);
define('TYPE_SITECHECK', 9);
define('TYPE_SURVEY', 10);
define('TYPE_USER', 11);


define('ACTION_RECOMMENDATION_LIST_ID', [
        426, 430, 440, 456,459,428,429,431,457,2162,2163,2336,2337,2340,2341,2342,2343,2344,2345,2348
    ]);
define('SMART_SHEET_USER',[3,109,64,7,67]);

define('ACTION_RECOMMENDATION_REPARE_AND_REMOVAL', [
    456,457,458,484,459,461,462,463,464,465,466,467,468,469,470,471,472,473,474,2163
    ]);

define('HAZARD_ACTION_RECOMMENDATION_REPARE_AND_REMOVAL', [
    26,27
    ]);

//audit trail type
define('AREA_TYPE','area');
define('CLIENT_TYPE','client');
define('CONTRACTOR_AUDIT_TYPE','contractor');
define('DOCUMENT_TYPE','document');
define('DEPARTMENT_TYPE','department');
define('ELEARNING_TYPE','elearning');
define('GROUP_TYPE','group');
define('HISTORICAL_CATEGORY_TYPE','historical category');
define('HISTORICAL_DATA_TYPE','historical data');
define('ITEM_TYPE','item');
define('LOCATION_TYPE','location');
define('NOTIFICATION_TYPE','notification');
define('ORGANISATION_AUDIT_TYPE','organisation');
define('POLICY_TYPE','policy');
define('PROJECT_TYPE','project');
define('PROPERTY_TYPE','property');
define('PROPERTY_GROUP_TYPE','property group');
define('PROPERTY_GROUP_DECOMMISSIONED_LIST_TYPE','property group decommissioned list');
define('PROPERTY_GROUP_LIST_TYPE','property group list');
define('PUBLISHED_SURVEY_TYPE','published survey');
define('SAMPLE_TYPE','sample');
define('SAMPLE_CERTIFICATE_TYPE','sample certificate');
define('RESOURCE_DOCUMENT_TYPE','resource document');
define('AIR_TEST_CERTIFICATE_TYPE','air test certificate');
define('TRAINING_RECORD_TYPE','training record');
define('SITE_PLAN_DOCUMENT_TYPE','site plan document');
define('SUMMARY_TYPE','summary');
define('FIRE_DOCUMENTS_SUMMARY_TYPE','fire documents summary');
define('FIRE_HAZARD_AR_SUMMARY_TYPE','fire hazard ar summary');
define('SURVEY_TYPE','survey');
define('WORK_REQUEST_TYPE','workrequest');
define('SURVEY_PLAN_DOCUMENT_TYPE','survey plan document');
define('TEMPLATE_TYPE','template');
define('TEMPLATE_CATEGORY_TYPE','template category');
define('USER_TYPE','user');
define('ROLE','role');
define('WORK_REQUEST_DOC_TYPE','work request document');
define('ADMIN_TOOL_TYPE','admin tool');

define('AUDIT_ACTION_VIEW','view');
define('AUDIT_ACTION_EDIT','edit');
define('AUDIT_ACTION_REMOVE','remove');
define('AUDIT_ACTION_MOVE','move');
define('AUDIT_ACTION_MERGE','merge');
define('AUDIT_ACTION_REVERT_BACK','revert back');
define('AUDIT_ACTION_ADD','add');
define('AUDIT_ACTION_PUBLISH','publish');
define('AUDIT_ACTION_APPROVED','approved');
define('AUDIT_ACTION_ARCHIVE','archive');
define('AUDIT_ACTION_RESTORE','restore');
define('AUDIT_ACTION_BEGIN','begin');
define('AUDIT_ACTION_COMPLETE','complete');
define('AUDIT_ACTION_DECOMMISSION','decommission');
define('AUDIT_ACTION_DECOMMISSION_REASON','update decommission reason');
define('AUDIT_ACTION_DOWNLOAD','download');
define('AUDIT_ACTION_DRAFT_PUBLISH','draft publish');
define('AUDIT_ACTION_ENROLL','enroll');
define('AUDIT_ACTION_EXPORT','export');
define('AUDIT_ACTION_SEARCH','search');
define('AUDIT_ACTION_EXTERNAL','external');
define('AUDIT_ACTION_LOCKED','locked');
define('AUDIT_ACTION_RECOMMISSION','recommission');
define('AUDIT_ACTION_REJECTED','rejected');
define('AUDIT_ACTION_CANCELED','canceled');
define('AUDIT_ACTION_SEND','send');
define('AUDIT_ACTION_UNLOCKED','unlocked');
define('AUDIT_ACTION_ROLLBACK','rollback');

//queue
define('EMAIL_RESET_PASSWORD_QUEUE','reset_password_emails');
define('EMAIL_CHANGE_MAIL_QUEUE','change_mail_emails');
define('EMAIL_REGISTER_QUEUE','register_emails');
define('CLIENT_EMAIL_QUEUE','client_emails');
define('DUPLICATE_DATA_EMAIL_QUEUE','duplicate_data_emails');
define('PROJECT_EMAIL_QUEUE','project_emails');
define('LOG_AUDIT_QUEUE','log_audit_trail');
define('API_PROCESS_DATA','api_process_mobile_data');
define('BLUELIGHT_SERVICE_PROCESS','bluelight_service_download');
define('PUBLISHED_WORK_REQUEST_EMAIL_QUEUE','published_work_request');
define('SURVEY_APPROVAL_EMAIL_QUEUE','survey_approval_emails');
define('WORK_REQUEST_EMAIL_QUEUE','work_request_emails');
define('SURVEY_REJECT_EMAIL_QUEUE','survey_reject_emails');
define('INCIDENT_REPORT_NOTIFICATION_QUEUE','incident_report_notification');
define('INCIDENT_REPORT_READY_FOR_APPROVAL_EMAIL','incident_report_ready_for_approval_email');
define('INCIDENT_REPORT_APPROVED_EMAIL','incident_report_approved_email');
define('INCIDENT_REPORT_APPROVED_PROPERTY_CONTACT_EMAIL','incident_report_approved_property_contact_email');

//email type
define('ASBESTOS_REGISTER_EMAILTYPE',9);
define('HIGH_RISK_ITEM_EMAILTYPE',10);
define('REMEDIAL_ACTIONS_REQUIRED_EMAILTYPE',11);
define('SURVEY_APPROVED_EMAILTYPE',12);
define('CONTRACTOR_PROJECT_EMAILTYPE',13);
define('DOCUMENT_APPROVED_EMAILTYPE',14);
define('SURVEY_REJECTED_EMAILTYPE',15);
define('CONTRACTOR_APPROVED_EMAILTYPE',16);
define('CONTRACTOR_REJECT_EMAILTYPE',17);
define('REJECT_USER_ID',304);
define('PUBLISH_WORK_REQUEST_USER_ID',15);
define('LIFE_ENVIRONMENTAL_CLIENT',13);

define('PROJECT_OPPORTUNITY_EMAILTYPE',1);
define('PROJECT_TENDER_SUCCESSFUL_EMAILTYPE',3);
define('PROJECT_TENDER_UNSUCCESSFUL_EMAILTYPE',4);
define('PROJECT_ARCHIVED_EMAILTYPE',7);
define('PROJECT_INVITATION_EMAILTYPE',5);
//update/view type and constant for ROLE ID in job role view
define('JOB_ROLE_VIEW',1);
define('JOB_ROLE_UPDATE',2);
//view role
define('CLIENTS_VIEW',5);
define('CATEGORY_BOX_VIEW',41);
define('RESOURCES_WORK_REQUEST',45);
define('PROJECTS_VIEW',13);
define('PROJECT_INFORMATIONS_VIEW',14);
define('SYSTEM_OWNER_VIEW',33);
define('CONTRACTORS_VIEW',35);
define('REPORTING_VIEW',36);
define('ALL_PROPERTY_VIEW',42);
//update role
define('DATA_CENTRE_UPDATE',15);
define('CLIENTS_UPDATE',3);
define('PROJECTS_UPDATE',12);
define('PROJECT_INFORMATIONS_UPDATE',13);
define('SYSTEM_OWNER_UPDATE',20);
define('CONTRACTORS_UPDATE',22);
define('CATEGORY_BOX_UPDATE',27);
define('ALL_PROPERTY_UPDATE',29);
//current view tab job rule
define('VIEW_TAB',1);
define('UPDATE_TAB',2);
//dynamic data type
define('PROJECT_INFORMATION_TYPE',1);
define('ORGANISATION_INFORMATION_TYPE',2);
define('DOCUMENT_APPROVAL',3);

// Privilege dynamic

define('PROPERTY_PERMISSION','property');
define('PROJECT_TYPE_PERMISSION','project_type');
define('PROJECT_DOCUMENT_TYPE_PERMISSION','project_information');
define('SYSTEM_OWNER_PERMISSION','system_owner');
define('CONTRACTOR_PERMISSION','contractor');

define('CLIENT_PERMISSION','client');
define('REPORT_PERMISSION','report');
define('CATEGORY_BOX_PERMISSION','category_box');
define('DOC_PERMISSION_ID','doc_id');
define('EMAIL_NOTIFICATION_PERMISSION','email_notification');
define('WORK_FLOW_PERMISSION','work_flow');
define('WORK_REQUEST_TYPE_PERMISSION','work_request_type');

// Privilege view default
define('DASHBOARD_GRAPHICS_VIEW_PRIV',2);
define('PROPERTY_LISTING_VIEW_PRIV',3);
define('PROPERTY_EMP_VIEW_PRIV',4);
// define('CLIENTS_VIEW_PRIV',5);
define('DETAILS_VIEW_PRIV',7);
define('OTHER_DETAILS_VIEW_PRIV',8);
define('PROPERTY_PLAN_VIEW_PRIV',9);
define('PDF_ASBESTOS_REGISTER_VIEW_PRIV',10);
define('REGISTER_VIEW_PRIV',11);
define('SURVEYS_PROP_VIEW_PRIV',12);
define('PROJECTS_PROP_VIEW_PRIV',13);
// define('PROJECT_INFORMATIONS_VIEW_PRIV',14);
define('HISTORICAL_DATA_VIEW_PRIV',15);
define('DATA_CENTRE_VIEW_PRIV',16);
define('MY_NOTIFICATIONS_VIEW_PRIV',17);
define('SURVEYS_VIEW_PRIV',18);
define('PROJECTS_VIEW_PRIV',19);
define('CRITICAL_VIEW_PRIV',20);
define('URGENT_VIEW_PRIV',21);
define('IMPORTANT_VIEW_PRIV',22);
define('ATTENTION_VIEW_PRIV',23);
define('DEADLINE_VIEW_PRIV',24);
define('APPROVAL_VIEW_PRIV',25);
define('REJECTED_VIEW_PRIV',26);
define('HIGH_RISK_ITEM_VIEW_PRIV',28);
define('ASBESTOS_REGISTER_UPDATE_VIEW_PRIV',29);
define('REMEDIAL_ACTION_REQUIRED_VIEW_PRIV',30);
define('ORGANISATION_VIEW_PRIV',31);
define('ORGANISATION_EMP_VIEW_PRIV',32);
// define('SYSTEM_OWNER_VIEW_PRIV',33);
define('CLIENTS_VIEW_PRIV',34);
define('REPORT_VIEW_PRIV',36);

define('RESOURCES_VIEW_PRIV',37);
define('RESOURCE_EMP_VIEW_PRIV',38);
define('BLUE_LIGHT_SERVICES_VIEW_PRIV',39);
define('RESOURCE_E_LEARNING_VIEW_PRIV',40);
define('CATEGORY_BOX_VIEW_PRIV',41);
define('AUDIT_TRAIL_VIEW_PRIV',44);

// Contractor permission
define('CONTRACTOR_DETAILS',4);
define('CONTRACTOR_POLICY_DOCUMENTS',5);
define('CONTRACTOR_DEPARTMENTS',6);
define('CONTRACTOR_TRAINING_RECORDS',7);

// Privilege update default
define('PROPERTY_EMP_UPDATE_PRIV',2);
// define('CLIENTS_UPDATE_PRIV',3);
define('DETAILS_UPDATE_PRIV',5);
define('OTHER_DETAILS_UPDATE_PRIV',6);
define('PROPERTY_PLAN_UPDATE_PRIV',7);

define('REGISTER_UPDATE_PRIV',8);
define('SURVEYS_PROP_UPDATE_PRIV',9);
define('PROJECTS_TYPE_UPDATE_PRIV',12);
define('PROJECT_INFORMATIONS_UPDATE_PRIV',13);
define('HISTORICAL_DATA_UPDATE_PRIV',14);

define('ORGANISATION_EMP_UPDATE_PRIV',19);
define('SYSTEM_OWNER_UPDATE_PRIV',20);

define('CLIENTS_UPDATE_PRIV',21);
define('CONTRACTORS_UPDATE_PRIV',22);

define('RESOURCE_EMP_UPDATE_PRIV',24);
define('CATEGORY_BOX_UPDATE_PRIV',27);
define('RESOURCE_E_LEARNING_UPDATE_PRIV',26);

// aprroval privs
define('DATA_CENTRE_UPDATE_PRIV',16);
define('DC_SURVEYS_UPDATE_PRIV',9);
define('DC_DOCUMENT_UPDATE_PRIV',10);
define('DC_PLANNING_DOCUMENT_UPDATE_PRIV',17);
define('DC_PRE_START_DOCUMENT_UPDATE_PRIV',18);
define('DC_SITE_RECORD_DOCUMENT_UPDATE_PRIV',19);
define('DC_COMPLETION_DOCUMENT_UPDATE_PRIV',20);

define('LIMIT_SEARCH',30);

define('LEAD_WORKREQUEST',1);

define('SUB_PROPERTY_IMAGE','sub_image');
define('AREA_IMAGE','area_image');
define('ITEM_LOCATION_PHOTO','item_location_image');
define('PROPERTY_ASSESS_IMAGE','property_asses_image');

define('OTHER_AREA','2378');

define('REGISTER_OVERALL','register overall');
define('REGISTER_ASBESTOS','register asbestos');
define('REGISTER_FIRE','register fire');
define('REGISTER_GAS','register gas');
define('REGISTER_WATER','register water');
define('ASSESSMENT_TYPE','assessment');
define('ASSEMBLY_POINT','assembly point');
define('FIRE_EXIT_TYPE','fire exit');
define('HAZARD_TYPE','hazard');
define('VEHICLE_PARKING_TYPE','vehicle parking');

//project
define('ASBESTOS_PROJECT',1);
define('WATER_PROJECT',3);
define('FIRE_PROJECT',2);
define('HS_PROJECT',4);

define('EQUIPMENT_SECTION_TEMP','temp');

define('FIRE_DOCUMENT_TYPE', 2);
define('FIRE_CALL_OUT', 36);
define('FIRE_CERTIFICATE_OF_RE_OCCUPATION', 37);
define('FIRE_REMEDIAL', 38);
define('FIRE_RISK_ASSESSMENT', 39);
define('FIRE_SERVICE_RECORD', 40);
define('FIRE_WORK_ORDER', 41);
define('FIRE_OTHER', 42);

// Incident Reporting
define('EQUIPMENT_NONCONFORMITY', 1);
define('IDENTIFIED_HAZARD', 2);
define('INCIDENT', 3);
define('SOCIAL_CARE', 74);
define('EQUIPMENT_NONCONFORMITY_DETAILS_TITLE', 'Nonconformity Details');
define('IDENTIFIED_HAZARD_DETAILS_TITLE', 'Hazard Details');
define('INCIDENT_DETAILS_TITLE', 'Incident Details');
define('SOCIAL_CARE_TITLE', 'Social Care Details');

define('INCIDENT_REPORT_FORM_TYPE', 1);
define('INCIDENT_REPORT_APPARENT_CAUSE_TYPE', 2);
define('INCIDENT_REPORT_INJURY_TYPE', 3);
define('INCIDENT_REPORT_PART_OF_BODY_AFFECTED_TYPE', 4);
define('INCIDENT_REPORT_CATEGORY_OF_WORKS', 5);
define('INCIDENT_REPORT_APPARENT_CAUSE_TITLE', 'Apparent Cause');
define('INCIDENT_REPORT_INJURY_TYPE_TITLE', 'Injury Type');
define('INCIDENT_REPORT_PART_OF_BODY_AFFECTED_TITLE', 'Part(s) of the body affected');
define('INCIDENT_REPORT_INJURY_OTHER_TYPE', 47);
define('INCIDENT_REPORT_APPARENT_CAUSE_OTHER_TYPE', 24);
define('INCIDENT_REPORT_TYPE', 'incident_report_type');
define('INCIDENT_REPORT_LOCKED', 1);
define('INCIDENT_REPORT_UNLOCK', 0);

define('INCIDENT_REPORTING_TYPE', 'Incident Reporting');
define('INCIDENT_UNDECOMMISSION', 0);
define('INCIDENT_DECOMMISSIONED', 1);
define('INCIDENT_REPORTING_DOC_TYPE','incident reporting document');
define('INCIDENT_NOT_CONFIDENTIAL', 0);
define('INCIDENT_CONFIDENTIAL', 1);

//status Incident Reporting
const INCIDENT_REPORT_CREATED_STATUS = 1;
const INCIDENT_REPORT_READY_QA = 2;
const INCIDENT_REPORT_AWAITING_APPROVAL = 3;
const INCIDENT_REPORT_COMPLETE = 4;
const INCIDENT_REPORT_REJECT = 5;

// h&s lead, second h&s lead
define('HS_LEAD_USER', 122);
define('SECOND_HS_LEAD_USER', 121);

define('DYNAMIC_WARNING_BANNERS','banners');
define('WARNING_BANNERS',46);

define('DANGER_BANNERS_TYPE','danger_type');
define('WARNING_BANNERS_TYPE','warning_type');
define('SUCCESS_BANNERS_TYPE','success_type');
define('INFO_BANNERS_TYPE','info_type');
define('WARNING_ALERT','alert');
define('WARNING_BUTTON','button');

define('SENCOND_SURVEYORS_ID',[5,6]);
define('CAD_TECHNICAN_ID',[1]);

define('AUDIT_USER_SIGNATURE', 'au_sig');
define('DOMESTIC_PROPERTY', 3);
define('AUDIT_USER_SIGNATURE_PATH', 'audit_app/user');

define('WORK_REQUEST_ASBESTOS_TYPE', 1);
define('WORK_REQUEST_FIRE_TYPE', 2);

<?php

const ASBESTOS = 'asbestos';
const FIRE = 'fire';
const GAS = 'gas';
const WATER = 'water';
const HS = 'H&S';
const ME = 'me';
const RCF = 'rcf';

const EQUIPMENT_ACCESSIBLE_STATE = 1;
const EQUIPMENT_INACCESSIBLE_STATE = 2;

const ASSESS_TYPE_FIRE_EQUIPMENT = 1;
const ASSESS_TYPE_FIRE_RISK_TYPE_1 = 2;
const ASSESS_TYPE_FIRE_RISK_TYPE_2 = 3;
const ASSESS_TYPE_FIRE_RISK_TYPE_3 = 4;
const ASSESS_TYPE_FIRE_RISK_TYPE_4 = 5;

const ASSESS_TYPE_FIRE_RISK_ALL_TYPE = [2,3,4,5];

// risk rating
const TRIVIAL_RISK_RATING = 48;
const TOLERABLE_RISK_RATING = 16;
const MODERATE_RISK_RATING = 15;
const SUBSTANTIAL_RISK_RATING = 14;
const INTOLERABLE_RISK_RATING = 49;

const ASSESS_TYPE_WATER_EQUIPMENT = 1;
const ASSESS_TYPE_WATER_RISK = 2;
const ASSESS_TYPE_WATER_TEMP = 3;

const ASSESS_TYPE_HS = 1;

const MATERIAL_OTHER = 45;

const COMPLIANCE_SYSTEM_PHOTO = 'sys_photo';
const COMPLIANCE_SYSTEM_PHOTO_PATH = 'compliance/sys_photo';
const COMPLIANCE_PROGRAMME_PHOTO = 'programme_photo';
const COMPLIANCE_PROGRAMME_PHOTO_PATH = 'compliance/programme_photo';
const EQUIPMENT_PHOTO = 'eqm_photo';
const EQUIPMENT_PHOTO_PATH = 'compliance/equipment_photo';
const EQUIPMENT_LOCATION_PHOTO = 'eqm_location';
const EQUIPMENT_LOCATION_PHOTO_PATH = 'compliance/equipment_location';
const EQUIPMENT_ADDITION_PHOTO = 'eqm_add';
const EQUIPMENT_ADDITION_PHOTO_PATH = 'compliance/equipment_addition';
const COMPLIANCE_DOCUMENT_PHOTO = 'doc_photo';
const COMPLIANCE_DOCUMENT_PHOTO_PATH = 'compliance/documents';
//old historical document
const COMPLIANCE_HISTORICAL_DOCUMENT = 'historical data';
const COMPLIANCE_HISTORICAL_DOCUMENT_CATEGORY = 'historical category';
const COMPLIANCE_HISTORICAL_DOCUMENT_PHOTO = 'hd';
const COMPLIANCE_HISTORICAL_DOCUMENT_PHOTO_PATH = 'documents/property/historical';
const HAZARD_PHOTO = 'haz_photo';
const HAZARD_PHOTO_PATH = 'compliance/haz_photo';
const HAZARD_LOCATION_PHOTO = 'haz_loc_photo';
const HAZARD_LOCATION_PHOTO_PATH = 'compliance/haz_loc_photo';
const HAZARD_ADDITION_PHOTO = 'haz_add_photo';
const HAZARD_ADDITION_PHOTO_PATH = 'compliance/haz_add_photo';
const ASSEMBLY_POINT_PHOTO = 'assembly_photo';
const ASSEMBLY_POINT_PHOTO_PATH = 'compliance/assembly_point';
const FIRE_EXIT_PHOTO = 'exit_photo';
const FIRE_EXIT_PHOTO_PATH = 'compliance/fire_exit';
const VEHICLE_PARKING_PHOTO = 'parking_photo';
const VEHICLE_PARKING_PHOTO_PATH = 'compliance/vehicle_parking';

const PLAN_FILE_ASSESSMENT = 'pa';
const NOTE_FILE_ASSESSMENT = 'note';
const SAMPLE_CERTIFICATE_ASSESSMENT = 'sca';
const SAMPLE_CERTIFICATE = 'sample certificate';
const PLAN_FILE_ASSESSMENT_PATH = 'compliance/plans';
const NOTE_FILE_ASSESSMENT_PATH = 'compliance/notes';

const AREA_ACCESSIBLE_STATE = 1;
const AREA_INACCESSIBLE_STATE = 2;
const AREA_INACCESSBILE_DROPDOWN_ID = 906;
const PAGINATION_DEFAULT = 9;
//documents
const DOCUMENT_NO_REQUIRE_TYPE = 1;
const DOCUMENT_SYSTEM_TYPE = 2;
const DOCUMENT_EQUIPMENT_TYPE = 3;
const DOCUMENT_PROGRAMME_TYPE = 4;

const DOCUMENT_STATUS_NEW = 1;
const DOCUMENT_STATUS_APPROVED = 2;
const DOCUMENT_STATUS_REJECTED = 3;

const VIEW_COMPLIANCE_DOCUMENT = 1;
const DOWNLOAD_COMPLIANCE_DOCUMENT = 2;
const VIEW_COMPLIANCE_HISTORICAL_DOCUMENT = 3;
const DOWNLOAD_COMPLIANCE_HISTORICAL_DOCUMENT = 4;

const DOCUMENT_ASBESTOS_TYPE = 1;
const DOCUMENT_FIRE_TYPE = 2;
const DOCUMENT_GAS_TYPE = 3;
const DOCUMENT_ME_TYPE = 4;
const DOCUMENT_WATER_TYPE = 5;
const DOCUMENT_OTHER_TYPE = 6;

//document types
const DOCUMENT_REINSPECTED = 1;

// audit
const SYSTEM_TYPE = 'system';
const PROGRAMME_TYPE = 'programme';
const EQUIPMENT_TYPE = 'equipment';
//assessment
const ASSESSMENT_ASBESTOS_TYPE = 1;
const ASSESSMENT_FIRE_TYPE = 2; //for question type also
const ASSESSMENT_GAS_TYPE = 3;
const ASSESSMENT_WATER_TYPE = 4;
const ASSESSMENT_HS_TYPE = 5;

const ASSESSMENT_STATUS_NEW = 1;
const ASSESSMENT_STATUS_LOCKED = 2; //lock
const ASSESSMENT_STATUS_READY_FOR_QA = 3;
const ASSESSMENT_STATUS_PUBLISHED = 4; //lock
const ASSESSMENT_STATUS_COMPLETED = 5;
const ASSESSMENT_STATUS_REJECTED = 6;
const ASSESSMENT_STATUS_SENT_BACK_FROM_DEVICE = 7;
const ASSESSMENT_STATUS_ABORTED = 8;

// equipment dropdown
const EQUIPMENT_REASON_INACCESS = 1;
const FREQUENCY_USE = 2;
const INSULATION_TYPE = 3;
const LABELLING = 4;
const DRAIN_LOCATION = 5;
const COLD_FIELD_LOCATION = 6;
const OUTLET_FIELD_LOCATION = 7;
const CLEANLINESS = 8;
const EASE_OF_CLEANING = 9;
const DIRECT_INDIRECT_FIRED = 10;
const OPERATIONAL_USE = 11;
const SOURCE = 12;
const SOURCE_ACCESSIBILITY = 13;
const SOURCE_CONDITION = 14;
const INSULATION_CONDITION = 15;
const PIPE_INSULATION_CONDITION = 22;
const OPERATION_EXPOSURE = 16;
const DEGREE_OF_FOULING = 17;
const DEGREE_OF_BIO = 18;
const EXTENT_CORROSION = 19;
const CONSTRUCTION_MATERIAL = 20;
const AEROSOL_RISK = 21;
const EVIDENCE_STAGE = 23;
const PIPE_INSULATION = 24;
const NEARSEST_FURTHEST = 25;
const HORIZONTAL_VERTICAL = 26;
const MATERIAL_OF_PIPEWORK = 27;

const EQUIPMENT_UNDECOMMISSION = 0;
const EQUIPMENT_DECOMMISSION = 1;
const EQUIPMENT_SPECIFIC_LOCATION_OTHER = 566;

//hazard
const HAZARD_NEW_AREA = -1;
const HAZARD_NEW_LOCATION = -1;
const HAZARD_SURVEY_ID_DEFAULT = -1;
const HAZARD_UNDECOMMISSION = 0;
const HAZARD_DECOMMISSION = 1;
const HAZARD_TYPE_INACCESSIBLE_ROOM = 29;

const FIRE_EXIT_TYPE_FINAL = 1;
const FIRE_EXIT_TYPE_STOREY = 2;

const COMPLIANCE_ASSESSMENT_REGISTER = 0;
const COMPLIANCE_ASSESSMENT_LOCKED = 1;
const COMPLIANCE_ASSESSMENT_UNLOCKED = 0;
const COMPLIANCE_ASSESSMENT_DECOMMISSIONED = 1;
const COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED = 0;
const COMPLIANCE_TEMPLATE_HAZARD = 1;
const COMPLIANCE_NORMAL_HAZARD = 0;
const COMPLIANCE_UNDELETED_HAZARD = 0;
const COMPLIANCE_DELETED_HAZARD = 1;
const COMPLIANCE_NA_ROOM_AREA = 0;

const TYPE_ALL_HAZARD_SUMMARY = 'all-hazard-summary';
const TYPE_VERY_HIGH_RISK_HAZARD_SUMMARY = 'very-high-risk-hazard';
const TYPE_HIGH_RISK_HAZARD_SUMMARY = 'high-risk-hazard';
const TYPE_MEDIUM_RISK_HAZARD_SUMMARY = 'medium-risk-hazard';
const TYPE_LOW_RISK_HAZARD_SUMMARY = 'low-risk-hazard';
const TYPE_VERY_LOW_RISK_HAZARD_SUMMARY = 'very-low-risk-hazard';

// equipment outlet
const REGISTER_OUTLET_TEMPLATE = [4,5,6,1,9,14,15,16];

//pdf
const MARGIN_BOTTOM = 25;
const MARGIN_TOP = 10;
const MARGIN_RIGHT = 15;

// Dropdown
const NEAREST_DROPDOWN = 225;
const FURTHEST_DROPDOWN = 226;

// void accessible and inaccessible
const LOCATION_VOID_INACC_CELLING = 1108;
const LOCATION_VOID_ACC_CELLING = 1062;
const LOCATION_VOID_INACC_FLOOR = 1453;
const LOCATION_VOID_ACC_FLOOR = 1407;
const LOCATION_VOID_INACC_CAVITIES = 1216;
const LOCATION_VOID_ACC_CAVITIES = 1170;
const LOCATION_VOID_INACC_RISERS = 1280;
const LOCATION_VOID_ACC_RISERS = 1234;
const LOCATION_VOID_INACC_DUCTING = 1344;
const LOCATION_VOID_ACC_DUCTING = 1298;
const LOCATION_VOID_INACC_BOXING = 1733;
const LOCATION_VOID_ACC_BOXING = 1687;
const LOCATION_VOID_INACC_PIPEWORK = 1606;
const LOCATION_VOID_ACC_PIPEWORK = 1560;

//action recommendation for item and hazard
//No previous recommendations	Manage
//Air Test (All Actions within sub-dropdown)	Remedial
//Apply Caution (All Actions within sub-dropdown)	Manage
//Clean (All Actions within sub-dropdown)	Remedial
//Encapsulate (All Actions within sub-dropdown)	Remedial
//Enclose	Remedial
//Further Investigation Required to Remedial Works	Further Investigation Required
//Label	Remedial
//Manage (All Actions within sub-dropdown)	Manage
//Monitor	Manage
//Miscellaneous (All Actions within sub-dropdown)	Remedial
//Repair (All Actions within sub-dropdown)	Remedial
//Remove (All Actions within sub-dropdown)	Remove
//Restrict Access (All Actions within sub-dropdown)	Restrict Access & Remove
//Sample (All Actions within sub-dropdown)	Remedial
//Treat (All Actions within sub-dropdown)	Remedial
//Further Investigation Required	Further Investigation Required
const ACTION_RECOMMENDATION_REMOVE = [459];
const ACTION_RECOMMENDATION_RESTRICT_ACCESS_REMOVE = [475];
const ACTION_RECOMMENDATION_MANAGE = [411,421,443,451];//Manage (All Actions within sub-dropdown) || Monitor
const ACTION_RECOMMENDATION_REMEDIAL= [412,426,430,440,442,452,456,485,489];
const ACTION_RECOMMENDATION_FURTHER_INVESTIGATION_REQUIRED = [441,2190];
const ACTION_RECOMMENDATION_OTHER = [493];
// hazard -> graphical_chart_type -> cp_hazard_action_recommendation_verb
const ACTION_RECOMMENDATION_HZ_REMOVE = 1;
const ACTION_RECOMMENDATION_HZ_RESTRICT_ACCESS_REMOVE = 2;
const ACTION_RECOMMENDATION_HZ_MANAGE = 3;
const ACTION_RECOMMENDATION_HZ_REMEDIAL = 4;
const ACTION_RECOMMENDATION_HZ_FURTHER_INVESTIGATION_REQUIRED = 5;
const ACTION_RECOMMENDATION_HZ_OTHER = 6;

//project classification
const ASBESTOS_CLASSIFICATION = 1;
const FIRE_CLASSIFICATION = 2;
const WATER_CLASSIFICATION = 3;

const EQUIPMENT_TEMPLATE_MISC = 1;

const VEHICLE_PARKING = 1;

const FILTER_INACCESSIBLE_ROOM_LOCATIONS = 1;
const FILTER_INACCESSIBLE_VOIDS = 2;
const FILTER_ASBESTOS_CONTAINING_MATERIALS = 3;
const FILTER_HAZARDS = 4;
const FILTER_NONCONFORMITY = 5;

const FILTER_PROPERTY = 1;
const FILTER_AREA = 2;
const FILTER_LOCATION = 3;

// equipment inventory dropdown
const EQUIPMENT_INVENTORY_TYPES = 1;
const EQUIPMENT_INVENTORY_STATUS = 2;
const EQUIPMENT_INVENTORY_OPERATIONAL = 3;
const EQUIPMENT_INVENTORY_REGION = 4;
const EQUIPMENT_INVENTORY_FREQUENCY = 5;
const EQUIPMENT_INVENTORY_DOCUMENT_TYPES = 6;

// equipment inventory decommission/recommission
const EQUIPMENT_INVENTORY_UNDECOMMISSION = 0;
const EQUIPMENT_INVENTORY_DECOMMISSION = 1;
const EQUIPMENT_INVENTORY_DOCUMENT_PHOTO = 'equipment_inventory_doc_photo';
const EQUIPMENT_INVENTORY_DOCUMENT_PHOTO_PATH = 'equipment_inventory/documents';

const EQUIPMENT_INVENTORY_LAB = 'lab';
const EQUIPMENT_INVENTORY_STORE = 'store';

const INVENTORY_DOCUMENT_SERVICE = 54;
// job role type

const SUPER_USER_ROLE = 1;

const JOB_ROLE_GENERAL = 1;
const JOB_ROLE_ASBESTOS = 2;
const JOB_ROLE_FIRE = 3;
const JOB_ROLE_GAS = 4;
const JOB_ROLE_ME = 5;
const JOB_ROLE_RCF = 6;
const JOB_ROLE_WATER = 7;
const JOB_ROLE_H_S = 8;
//job role view
const JR_GENERAL_CLIENT_LISTING_VIEW = 4;
const JR_GENERAL_CLIENT_VIEW = 7;
const JR_GENERAL_EMAIL_NOTIFICATIONS_VIEW = 19;
const JR_GENERAL_ORGANISATION_LISTING_VIEW = 20;
const JR_GENERAL_SYSTEM_OWNER_VIEW = 22;
const JR_GENERAL_SYSTEM_CLIENT_VIEW = 23;
const JR_GENERAL_SYSTEM_CONTRACTOR_VIEW = 24;
const JR_GENERAL_REPORTING_VIEW = 25;
const JR_GENERAL_RESOURCES_WORKFLOW = 32;
const JR_GENERAL_RESOURCES_WORK_REQUEST_TYPE = 33;
const JR_GENERAL_SITE_OPERATIVE = 37;
const JR_GENERAL_TROUBLE_TICKET = 38;
const JR_ASBESTOS_REPORTING = 57;
const JR_FIRE_RESOURCES_WORKFLOW = 77;
const JR_WATER_RESOURCES_WORKFLOW = 118;
const JR_WORK_REQUEST = 134;
const JR_INCIDENT_REPORT = 135;
const JR_CATEGORY_BOX_VIEW = 137;
const JR_HS_RESOURCES = 162;

//job role update
const JR_GENERAL_CLIENT_LISTING_UPDATE = 1;
const JR_GENERAL_CLIENT_UPDATE = 4;
const JR_GENERAL_ORGANISATION_LISTING_UPDATE = 19;
const JR_GENERAL_SYSTEM_OWNER_UPDATE = 21;
const JR_GENERALL_SYSTEM_CLIENT_UPDATE = 22;
const JR_GENERAL_SYSTEM_CONTRACTOR_UPDATE = 23;
const JR_CATEGORY_BOX_UPDATE = 85;
// job row common
const JR_PROJECT_INFORMATION_TYPE = 1;
const JR_ORGANISATION_INFORMATION_TYPE = 2;
const JR_DOCUMENT_APPROVAL = 3;

// role view privs constant
const JR_DATA_CENTRE = 1;
const JR_DASHBOARD = 2;
const JR_CERTIFICATES = 3;
const JR_PROPERTY_LISTING = 4;
const JR_ALL_PROPERTIES = 5;
const JR_PROPERTY_EMP = 6;
const JR_GROUP_EMP = 131;
const JR_PROPERTIES_INFORMATION = 8;
const JR_DETAILS = 9;
const JR_DYNAMIC_WARNING_BANNER = 133;
const JR_REGISTER = 10;
const JR_OVERALL = 11;
const JR_SUB_PROPERTY = 12;
const JR_SYSTEMS = 13;
const JR_EQUIPMENT = 14;
const JR_AREA_FLOORS = 15;
const JR_FIRE_EXITS_ASSEMBLY_POINTS = 16;
const JR_VEHICLE_PARKING = 17;
const JR_DOCUMENTS = 18;
const JR_PROJECT_INFO = 126;
const JR_PROJECT_TENDER_DOCUMENT = 127;
const JR_PROJECT_CONTRACTOR_DOCUMENT = 128;
const JR_PROJECT_PLANNING_DOCUMENT = 139;
const JR_PROJECT_PRESTART_DOCUMENT = 140;
const JR_PROJECT_SITE_RECORD_DOCUMENT = 141;
const JR_PROJECT_COMPLETION_DOCUMENT = 142;
const JR_PROJECT_OHS_DOCUMENT = 128;
const JR_EMAIL_NOTIFICATIONS = 19;
const JR_ORGANISATIONAL = 20;
const JR_ORGANISATION_EMP = 21;
const JR_SYSTEM_OWNER = 22;
const JR_CLIENTS = 23;
const JR_CONTRACTORS = 24;
const JR_REPORTING = 25;
const JR_RESOURCES = 26;
const JR_RESOURCES_EMP = 27;
const JR_ADMINISTRATOR_FUNCTIONS = 28;
const JR_E_LEARNING = 29;
const JR_E_LEARNING_AWARENESS = 122;
const JR_E_LEARNING_SITE_OPERATIVE = 123;
const JR_E_LEARNING_PROJECT_MANAGER = 124;
const JR_JOB_ROLES = 30;
// const JR_WORK_REQUEST = 31;
const JR_WORKFLOW_REQUEST = 32;
const JR_WORK_REQUEST_TYPE = 33;
const JR_DEPARTMENT_LIST = 34;
const JR_AUDIT = 125;
const JR_LAB_WORK_LOG = 130;
const JR_AUDIT_TRAIL = 36;
const JR_SITE_OPERATIVE_VIEW = 37;
const JR_VIEW_TROUBLETICKET_ICON = 38;
const JR_ASBESTOS_PROJECTS = 40;
const JR_DATA_CENTRE_PROJECT_ASBESTOS = 41;
const JR_DATA_CENTRE_ASBESTOS_SURVEYS = 42;
const JR_DATA_CENTRE_ASBESTOS_CRITICAL = 43;
const JR_DATA_CENTRE_ASBESTOS_URGENT = 44;
const JR_DATA_CENTRE_ASBESTOS_IMPORTANT = 45;
const JR_DATA_CENTRE_ASBESTOS_ATTENTION = 46;
const JR_DATA_CENTRE_ASBESTOS_DEADLINE = 47;
const JR_DATA_CENTRE_ASBESTOS_APPROVAL = 48;
const JR_DATA_CENTRE_ASBESTOS_REJECTED = 49;
const JR_ASBESTOS_PROPERTIES_INFORMATION = 50;
const JR_ASBESTOS_REGISTER = 51;
const JR_PI_REGISTER_ASBESTOS = 52;
// const JR_PI_ASBESTOS_PROJECTS = 53;
const JR_PI_PROJECT_ASBESTOS = 54;
// const JR_ASBESTOS_ASSESSMENTS_SURVEYS = 55;
const JR_PI_ASSESSMENT_ASBESTOS = 56;
const JR_FIRE_DATA_CENTRE = 58;
// const JR_DATA_CENTRE_FIRE_PROJECTS = 59;
const JR_DATA_CENTRE_PROJECT_FIRE = 60;
const JR_DATA_CENTRE_FIRE_ASSESSMENTS = 61;
const JR_DATA_CENTRE_ASSESSMENT_FIRE = 62;
const JR_DATA_CENTRE_FIRE_CRITICAL = 63;
const JR_DATA_CENTRE_FIRE_URGENT = 64;
const JR_DATA_CENTRE_FIRE_IMPORTANT = 65;
const JR_DATA_CENTRE_FIRE_ATTENTION = 66;
const JR_DATA_CENTRE_FIRE_DEADLINE = 67;
const JR_DATA_CENTRE_FIRE_APPROVAL = 68;
const JR_DATA_CENTRE_FIRE_REJECTED = 69;
const JR_FIRE_PROPERTIES_INFORMATION = 70;
const JR_FIRE_REGISTER = 71;
const JR_PI_REGISTER_FIRE = 72;
// const JR_PI_FIRE_PROJECTS = 73;
const JR_PI_PROJECT_FIRE = 74;
// const JR_FIRE_ASSESSMENTS_SURVEYS = 75;
const JR_PI_ASSESSMENT_FIRE = 76;
const JR_FIRE_REPORTING = 77;
const JR_RCF_DATA_CENTRE = 78;
const JR_DATA_CENTRE_RCF_PROJECTS = 79;
const JR_DATA_CENTRE_PROJECT_RCF = 80;
const JR_DATA_CENTRE_RCF_ASSESSMENTS = 81;
const JR_DATA_CENTRE_ASSESSMENT_RCF = 82;
const JR_DATA_CENTRE_RCF_SURVEYS = 83;
const JR_DATA_CENTRE_RCF_CRITICAL = 84;
const JR_DATA_CENTRE_RCF_URGENT = 85;
const JR_DATA_CENTRE_RCF_IMPORTANT = 86;
const JR_DATA_CENTRE_RCF_ATTENTION = 87;
const JR_DATA_CENTRE_RCF_DEADLINE = 88;
const JR_DATA_CENTRE_RCF_APPROVAL = 89;
const JR_DATA_CENTRE_RCF_REJECTED = 90;
const JR_RCF_PROPERTIES_INFORMATION = 91;
const JR_PI_RCF_REGISTER = 92;
const JR_PI_REGISTER_RCF = 93;
const JR_PI_RCF_PROJECTS = 94;
const JR_PI_PROJECT_RCF = 95;
const JR_RCF_ASSESSMENTS_SURVEYS = 96;
const JR_PI_ASSESSMENT_RCF = 97;
const JR_WATER_DATE_CENTRE = 98;
// const JR_DATA_CENTRE_WATER_PROJECTS = 99;
const JR_DATA_CENTRE_PROJECT_WATER = 100;
const JR_DATA_CENTRE_WATER_ASSESSMENTS = 101;
const JR_DATA_CENTRE_ASSESSMENT_WATER = 102;
const JR_DATA_CENTRE_WATER_CRITICAL = 103;
const JR_DATA_CENTRE_WATER_URGENT = 104;
const JR_DATA_CENTRE_WATER_IMPORTANT = 105;
const JR_DATA_CENTRE_WATER_ATTENTION = 106;
const JR_DATA_CENTRE_WATER_DEADLINE = 107;
const JR_DATA_CENTRE_WATER_APPROVAL = 108;
const JR_DATA_CENTRE_WATER_REJECTED = 109;
const JR_WATER_PROPERTIES_INFORMATION = 111;
const JR_PI_WATER_REGISTER = 112;
const JR_PI_REGISTER_WATER = 113;
// const JR_PI_WATER_PROJECTS = 114;
const JR_PI_PROJECT_WATER = 115;
// const JR_WATER_ASSESSMENTS_SURVEYS = 116;
const JR_PI_ASSESSMENTS_WATER = 117;
const JR_WATER_REPORTING = 118;
const JR_APP_AUDIT_TRAIL = 119;
const JR_CERTIFICATE_VIEW = 121;
const JR_PRE_SURVEY_PLAN = 120;
const JR_HS_DATA_CENTRE = 143;
// const PROJECTS = 144;
const JR_DATA_CENTRE_PROJECT_HS = 145;
const ASSESSMENTS = 146;
const JR_DATA_CENTRE_ASSESSMENT_HS = 147;
const JR_DATA_CENTRE_HS_URGENT = 149;
const JR_DATA_CENTRE_HS_IMPORTANT = 150;
const JR_DATA_CENTRE_HS_ATTENTION = 151;
const JR_DATA_CENTRE_HS_DEADLINE = 152;
const JR_DATA_CENTRE_HS_APPROVAL = 153;
const JR_DATA_CENTRE_HS_REJECTED = 154;
const JR_HS_PROPERTIES_INFORMATION = 155;
const JR_DATA_CENTRE_HS_CRITICAL = 163;
const JR_HS_REGISTER = 156;
const JR_PI_REGISTER_HS = 157;
const JR_HS_PROJECTS = 158;
const JR_PI_PROJECT_HS = 159;
const JR_HS_ASSESSMENTS_SURVEYS = 160;
const JR_PI_ASSESSMENTS_HS = 161;
const JR_HS_REPORTING = 162;

const JR_CONTRACTOR_DETAILS = 3;
const JR_CONTRACTOR_POLICY_DOCUMENTS = 4;
const JR_CONTRACTOR_DEPARTMENTS = 5;
const JR_CONTRACTOR_TRAINING_RECORDS = 6;
const JR_PREPLAN_SUMMARY_FIRE = 4;
const JR_PREPLAN_SUMMARY_WATER = 5;

// role edit privs constant

const JR_UPDATE_PROPERTY_LISTING = 1;
const JR_UPDATE_ALL_PROPERTIES = 2;
const JR_UPDATE_PROPERTY_EMP = 3;
const JR_UPDATE_GROUP_EMP = 84;
const JR_UPDATE_PROPERTIES_INFOMATION = 5;
const JR_UPDATE_DETAILS = 6;
const JR_UPDATE_REGISTER = 7;
const JR_UPDATE_AREA_FLOOR = 8;
const JR_UPDATE_ROOM_LOCATION = 9;
const JR_UPDATE_SYSTEMS = 10;
const JR_UPDATE_EQUIPMENT = 11;
const JR_UPDATE_FIRE_EXITS_ASSEMBLY_POINTS = 12;
const JR_UPDATE_VEHICLE_PARKING = 13;
const JR_UPDATE_DOCUMENTS = 14;
const JR_UPDATE_PROJECT_INFO = 80;
const JR_UPDATE_PROJECT_TENDER_DOCUMENT = 81;
const JR_UPDATE_PROJECT_CONTRACTOR_DOCUMENT = 82;
const JR_UPDATE_PROJECT_OHS_DOCUMENT = 83;
const JR_UPDATE_DATA_CENTRE = 15;
const JR_UPDATE_DASHBOARD = 16;
const JR_UPDATE_CONTRACTOR_DOCUMENTS = 17;
const JR_UPDATE_PROJECT_PLANNING_DOCUMENT = 89;
const JR_UPDATE_PROJECT_PRESTART_DOCUMENT = 90;
const JR_UPDATE_PROJECT_SITE_RECORD_DOCUMENT = 91;
const JR_UPDATE_PROJECT_COMPLETION_DOCUMENT = 92;
const JR_UPDATE_PROJECT_PRE_CONSTRUCTION_DOCUMENT = 107;
const JR_UPDATE_PROJECT_DESIGN_DOCUMENT = 108;
const JR_UPDATE_PROJECT_COMMERCIAL_DOCUMENT = 109;
const JR_UPDATE_CERTIFICATES = 18;
const JR_UPDATE_ORGANISATIONAL = 19;
const JR_UPDATE_ORGANISATION_EMP = 20;
const JR_UPDATE_MY_ORGANISATION = 21;
const JR_UPDATE_CLIENTS = 22;
const JR_UPDATE_CONTRACTORS = 23;
const JR_UPDATE_RESOURCES = 24;
const JR_UPDATE_ADMINISTRATOR_FUNCTIONS = 25;
const JR_UPDATE_TOOLBOX = 26;
const JR_UPDATE_SYSTEM_SETTINGS = 27;
const JR_UPDATE_E_LEARNING = 28;
const JR_UPDATE_ASBESTOS_AWARENESS_TRAINING = 29;
const JR_UPDATE_SITE_OPERATIVE_VIEW_TRAINING = 30;
const JR_UPDATE_PROJECT_MANAGER_TRAINING = 31;
const JR_UPDATE_JOB_ROLES = 32;
const JR_UPDATE_RESOURCE_DOCUMENTS = 33;
const JR_UPDATE_WORK_REQUESTS = 34;
const JR_UPDATE_DEPARTMENT_LIST = 35;
const JR_UPDATE_ASBESTOS_PROPERTIES_INFORMATION = 36;
const JR_UPDATE_ASBESTOS_DETAILS = 37;
const JR_UPDATE_ASBESTOS_REGISTER = 38;
const JR_UPDATE_ASBESTOS_ITEMS = 39;
const JR_UPDATE_ASBESTOS_PROJECTS = 40;
const JR_UPDATE_PROJECT_ASBESTOS = 41;
const JR_UPDATE_ASBESTOS_ASSESSMENTS_SURVEYS = 42;
const JR_UPDATE_ASSESSMENT_ASBESTOS = 43;
const JR_UPDATE_CERTIFICATE_ASBESTOS = 79;
const JR_UPDATE_ASBESTOS_DATA_CENTRE = 44;
const JR_UPDATE_DATACENTRE_SURVEYS = 45;
const JR_UPDATE_FIRE_PROPERTIES_INFORMATION = 46;
const JR_UPDATE_FIRE_DETAILS = 47;
const JR_UPDATE_FIRE_REGISTER = 48;
const JR_UPDATE_REGISTER_FIRE_HAZARDS = 49;
const JR_UPDATE_FIRE_PROJECT = 50;
const JR_UPDATE_PROJECT_FIRE = 51;
const JR_UPDATE_FIRE_ASSESSMENTS = 52;
const JR_UPDATE_ASSESSMENT_FIRE = 53;
const JR_UPDATE_FIRE_DATA_CENTRE = 54;
const JR_UPDATE_DATACENTRE_FIRE_ASSESSMENTS = 55;
const JR_UPDATE_DATACENTRE_ASSESSMENT_FIRE = 56;
const JR_UPDATE_RCF_PROPERTIES_INFORMATION = 57;
const JR_UPDATE_RCF_DETAILS = 58;
const JR_UPDATE_RCF_REGISTER = 59;
const JR_UPDATE_REGISTER_RCF_ITEMS = 60;
const JR_UPDATE_RCF_PROJECT = 61;
const JR_UPDATE_PROJECT_RCF = 62;
const JR_UPDATE_RCF_ASSESSMENTS = 63;
const JR_UPDATE_RCF_RCF = 64;
const JR_UPDATE_RCF_DATA_CENTRE = 65;
const JR_UPDATE_DATACENTRE_RCF_ASSESSMENTS = 66;
const JR_UPDATE_DATACENTRE_RCF = 67;
const JR_UPDATE_WATER_PROPERTIES_INFORMATION = 68;
const JR_UPDATE_WATER_DETAILS = 69;
const JR_UPDATE_WATER_REGISTER = 70;
const JR_UPDATE_REGISTER_WATER_HAZARDS = 71;
const JR_UPDATE_WATER_PROJECT = 72;
const JR_UPDATE_PROJECT_WATER = 73;
const JR_UPDATE_WATER_ASSESSMENTS = 74;
const JR_UPDATE_ASSESSMENT_WATER = 75;
const JR_UPDATE_WATER_DATA_CENTRE = 76;
const JR_UPDATE_DATACENTRE_WATER_ASSESSMENTS = 77;
const JR_UPDATE_DATACENTRE_ASSESSMENT_WATER = 78;
const JR_CERTIFICATE_EDIT = 79;
const JR_CATEGORY_BOX_EDIT = 85;
const JR_WORK_REQUEST_EDIT = 86;
const JR_INCIDENT_REPORT_EDIT = 87;
const NONCONFORMITIES = 1;
// incident reporting
const INCIDENT_REPORTING_DOCUMENT_PATH = 'compliance/incident_reporting';
const ASSESMENT_UNLOCKED = 0;
const JR_UPDATE_HS_PROPERTIES_INFORMATION = 96;
const JR_UPDATE_HS_DETAILS = 97;
const JR_UPDATE_HS_REGISTER = 98;
const JR_UPDATE_REGISTER_HS_HAZARDS = 99;
const JR_UPDATE_HS_PROJECT = 100;
const JR_UPDATE_PROJECT_HS = 101;
const JR_UPDATE_HS_ASSESSMENTS = 102;
const JR_UPDATE_ASSESSMENT_HS = 103;
const JR_UPDATE_HS_DATA_CENTRE = 104;
const JR_UPDATE_DATACENTRE_HS_ASSESSMENTS = 105;
const JR_UPDATE_DATACENTRE_ASSESSMENT_HS = 106;


const CORPORATE_PROPERTIES = 1;
const COMMERCIAL_PROPERTIES = 2;
const HOUSING_COMMUNAL = 3;
const HOUSING_DOMESTIC = 4;

const OTHER_HAZARD_IDENTIFIED_SECTION_ID = 134;
const OTHER_HAZARD_IDENTIFIED_QUESTION_ID = 460;

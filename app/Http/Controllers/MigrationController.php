<?php

namespace app\Http\Controllers;

use App\Mail\HoangTestMail;
use App\Models\ClientAddress;
use App\Models\Department;
use App\Models\DepartmentContractor;
use App\Models\PropertyComment;
use App\Models\PropertyDropdown;
use App\Models\PropertyDropdownTitle;
use App\Models\PropertyEmailSent;
use App\Models\PropertyProgrammePhase;
use App\Models\PropertyProgrammeType;
use App\Models\PropertyPropertyType;
use App\Models\PropertyType;
use App\Models\PropertySurvey;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Contact;
use App\Models\Role;
use App\Models\PropertyInfo;
use App\Models\Property;
use App\Models\Client;
use App\Models\ClientInfo;
use Illuminate\Support\Facades\Mail;

class MigrationController extends Controller
{


    public function migrate_user(){

        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM tblusers;";
        $data = [];
        $data_contact = [];
        $data_rule = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));

        if(count($results) > 0){
            foreach ($results as $user){

                $data_user[] = [
                    'id' =>  $user->keyID,
                    'shine_reference' =>  $user->shineReference,
                    'username' =>  $user->username,
                    'password' =>  $user->password,
                    'remember_token' =>  NULL,
                    'is_site_operative' =>  $user->isSiteOperative == -1 ? 1 : 0,
                    'client_id' =>  $user->usClientID,
                    'department_id' =>  $user->usDepartmentID,
                    'first_name' =>  $user->usFirstName,
                    'last_name' =>  $user->usLastName,
                    'email' =>  $user->usEmail,
                    'surveyor' =>  $user->usSurveyor,
                    'is_admin' =>  $user->usAdmin == -1 ? 1 : 0,
                    'grant' =>  $user->usGrant,
                    'joblead' =>  $user->usJobLead,
                    'is_locked' =>  $user->usLocked == -1 ? 1 : 0,
                    'password_hash' =>  $user->password_hash,
                    'app_hash' =>  $user->app_hash,
                    'token' =>  $user->token,
                    'token_expired' =>  $user->token_expired,
                    'newEmail' =>  $user->newEmail,
                    'f2aToken' =>  $user->f2aToken,
                    'update_project_type' =>  $user->updateProjectType,
                    'update_email_type' =>  $user->updateEmailType,
                    'sp_contractor' =>  $user->spContractor,
                    'created_at' =>  NULL,
                    'updated_at' =>  NULL,
                    'deleted_at' =>  NULL
                ];
                $data_contact[] = [
                    'user_id' => $user->keyID,
                    'job_title' => $user->usJobTitle,
                    'address1' => $user->usAddress1,
                    'address2' => $user->usAddress2,
                    'town' => $user->usTown,
                    'postcode' => $user->usPostcode,
                    'country' => $user->usCountry,
                    'telephone' => $user->usTelephone,
                    'mobile' => $user->usMobile,

                    'created_at' =>  NULL,
                    'updated_at' =>  NULL,
                    'deleted_at' =>  NULL
                ];
            }

            DB::beginTransaction();
            try{
                User::insert($data_user);
                Contact::insert($data_contact);
                Role::insert($data_rule);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        return 'Done';
    }

    public function migrate_property() {

        $con_gsk_old = env('DB_CONNECTION_OLD');
        $sql = "SELECT s.*, ac.id as occupation_type1, ac.parent_id as occupation_type2, ac2.id as occupation_type3 FROM tblsites s
                LEFT JOIN tbldropdowndata d ON s.siteType = d.ID AND d.dropdownID = 901
                LEFT JOIN tbl_property_occupation_type po ON po.id = s.occupation_type
                LEFT JOIN tbl_asset_class ac ON d.description = ac.description
                LEFT JOIN tbl_asset_class ac2 ON po.description = ac2.description
                ;";
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        $data_property = [];
        $data_property_info = [];
        $data_property_survey = [];
        $data_property_type = [];
        $data_middle = [];
        if(count($results) > 0){
            foreach ($results as $property){

                $data_property[] = [
                    'id' => $property->keyID,
                    'reference' => $property->shineReference,
                    'client_id' => $property->clientKey,
                    'zone_id' => $property->zoneKey,
                    'property_reference' => $property->reference,
                    'name' => $property->name,
                    'decommissioned' => $property->decommissioned == -1 ? 1 : 0,
                    'comments' => $property->comments,
                    'programme_phase' => $property->programmePhase,
                    'register_updated' => $property->registerUpdated,
                    'pblock' => $property->pblock,
                    'service_area_id' => $property->service_center,
                    'parent_reference' => $property->parentReference,
                    'estate_code' => $property->esCode,
                    'asset_class_id' => $property->occupation_type2 ?? $property->siteType,//get parent if null, get old value
                    'asset_type_id' => $property->occupation_type3 ?? $property->occupation_type,//get children if null, get old value
//                    'tenure_type_id' => $property->pblock,//from orchard
                    'communal_area_id' => $property->communalArea,
                    'responsibility_id' => $property->responsibilitySite,
                    'core_code' => $property->core_code
                ];
                if($property->riskType){
                    $data_property_type[] = [
                        'property_id' => $property->keyID,
                        'property_type_id'=>$property->riskType
                    ];
                }
                $data_property_info[] = [
                    'property_id' => $property->keyID,
                    'address1' => $property->address1,
                    'address2' => $property->address2,
                    'address3' => $property->address3,
                    'address4' => $property->address4,
                    'address5' => $property->address5,
                    'town' => $property->town,
                    'postcode' => $property->postcode,
                    'country' => $property->address5,
                    'telephone' => $property->telephone,
                    'mobile' => $property->mobile,
                    'email' => $property->email,
                    'app_contact' => $property->appContact,
                    'team1' => $property->team1,
                    'team2' => $property->team2,
                    'team3' => $property->team3,
                    'team4' => $property->team4,
                    'team5' => $property->team5,
                    'team6' => $property->team6,
                    'team7' => $property->team7,
                    'team8' => $property->team8,
                    'team9' => $property->team9,
                    'team10' => $property->team10,
                    'estate' => $property->estate,
                    'flat_number' => $property->address1, //new fields
                    'building_name' => $property->address2,
                    'street_number' => $property->address3,
                    'street_name' => $property->address4
                ];

                $electrical_meter = null;
                $gas_meter = null;
                $loft_void = null;
                $electrical_meter_data = DB::connection($con_gsk_old)->select(DB::raw("SELECT * FROM tblpropertydropdownvalue WHERE dropdownID = 1 AND siteID = ". $property->keyID));
                $gas_meter_data = DB::connection($con_gsk_old)->select(DB::raw("SELECT * FROM tblpropertydropdownvalue WHERE dropdownID = 2 AND siteID = ". $property->keyID));
                $loft_void_data = DB::connection($con_gsk_old)->select(DB::raw("SELECT * FROM tblpropertydropdownvalue WHERE dropdownID = 3 AND siteID = ". $property->keyID));

                if (!empty($electrical_meter_data)) {
                    $electrical_meter = $electrical_meter_data[0]->dataID;
                }
                if (!empty($gas_meter_data)) {
                    $gas_meter = $gas_meter_data[0]->dataID;
                }
                if (!empty($loft_void_data)) {
                    $loft_void = $loft_void_data[0]->dataID;
                }
                $data_property_survey[] = [
                    'property_id' => $property->keyID,
                    'programme_type_other' => $property->programmeTypeOther,
                    'programme_type' => $property->programmeType,
                    'asset_use_primary' => $property->assetUsePrimary,
                    'asset_use_primary_other' => $property->assetUsePrimaryOther,
                    'asset_use_secondary' => $property->assetUseSecondary,
                    'asset_use_secondary_other' => $property->assetUseSecondaryOther,
                    'construction_age' => $property->constructionAge,
                    'construction_type' => $property->constructionType,
                    'size_floors' => is_numeric($property->sizeFloors) && $property->sizeFloors >= 0 && $property->sizeFloors <= 15 ? $property->sizeFloors : 'Other',
                    'size_staircases' => is_numeric($property->sizeStaircases) && $property->sizeStaircases >= 0 && $property->sizeStaircases <= 15 ? $property->sizeStaircases : 'Other',
                    'size_lifts' => is_numeric($property->sizeLifts) && $property->sizeLifts >= 0 && $property->sizeLifts <= 15 ? $property->sizeLifts : 'Other',
                    'size_net_area' => $property->sizeNetArea,
                    'electrical_meter' => $electrical_meter,
                    'gas_meter' => $gas_meter,
                    'loft_void' => $loft_void,
                    'size_bedrooms' => trim($property->sizeBedrooms),
                    'size_gross_area' => trim($property->sizeGrossArea),
                    'size_comments' => trim($property->sizeComments),
                    'size_floors_other' => is_numeric($property->sizeFloors) && $property->sizeFloors >= 0 && $property->sizeFloors <= 15 ? NULL : trim($property->sizeFloors),
                    'size_staircases_other' => is_numeric($property->sizeStaircases) && $property->sizeStaircases >= 0 && $property->sizeStaircases <= 15 ? NULL : trim($property->sizeStaircases),
                    'size_lifts_other' => is_numeric($property->sizeLifts) && $property->sizeLifts >= 0 && $property->sizeLifts <= 15 ? NULL : trim($property->sizeLifts)
                ];
            }

//            dd($data_property, $data_property_info, $data_property_survey, $data_property_type);

            DB::beginTransaction();
            try{

                $insert_data_property = collect($data_property);
                $chunks1 = $insert_data_property->chunk(500);
                foreach ($chunks1 as $chunk1)
                {
                    Property::insertOrIgnore($chunk1->toArray());
                }

                $insert_data_info = collect($data_property_info);
                $chunks1 = $insert_data_info->chunk(500);
                foreach ($chunks1 as $chunk1)
                {
                    PropertyInfo::insertOrIgnore($chunk1->toArray());
                }

                $insert_data_property_survey = collect($data_property_survey);
                $chunks1 = $insert_data_property_survey->chunk(500);
                foreach ($chunks1 as $chunk1)
                {
                    PropertySurvey::insertOrIgnore($chunk1->toArray());
                }

                $insert_data_property_type = collect($data_property_type);
                $chunks1 = $insert_data_property_type->chunk(500);
                foreach ($chunks1 as $chunk1)
                {
                    PropertyPropertyType::insertOrIgnore($chunk1->toArray());
                }

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        return 'Done';
    }
    public function migrate_client() {
        $con_gsk_old = env('DB_CONNECTION_OLD');
        $sql = "SELECT * FROM tblclients;";
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));

        $data_client = [];
        $data_client_info = [];
        $data_client_address = [];

        if(count($results) > 0){
            foreach ($results as $client){
                $data_client[] = [
                    'id' => $client->pk,
                    'name' => $client->name,
                    'key_contact' => $client->key_contact,
                    'reference' => $client->reference,
                    'client_type' => $client->clienttype,
                    'email' => $client->email,
                    'email_notification' => $client->email_notification,
                ];
                $data_client_info[] = [
                    'client_id' => $client->pk,
                    'ukas' => $client->ukas,
                    'ukas_reference' => $client->ukas_reference,
                    'ukas_testing_reference' => $client->ukas_testing_reference,
//                    'account_management_email' => $client->account_management_email,
                    'type_surveying' => $client->type_surveying == -1 ? 1 :0,
                    'type_removal' => $client->type_removal == -1 ? 1 :0,
                    'type_demolition' => $client->type_demolition == -1 ? 1 :0,
                    'type_analytical' => $client->type_analytical == -1 ? 1 :0,
                    'type_instructingparty' => $client->type_instructingparty == -1 ? 1 :0,
                ];
                $data_client_address[] = [
                    'client_id' => $client->pk,
                    'address1' => $client->address1,
                    'address2' => $client->address2,
                    'address3' => $client->address3,
                    'address4' => $client->address4,
                    'address5' => $client->address5,
                    'postcode' => $client->postcode,
                    'country' => $client->country,
                    'telephone' => $client->telephone,
                    'mobile' => $client->mobile,
                    'fax' => $client->fax,
                ];

            }

            DB::beginTransaction();
            try{
                Client::insert($data_client);
                ClientAddress::insert($data_client_address);
                ClientInfo::insert($data_client_info);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());
            }
        }
        return 'Done';
    }
    public function migrate_department() {
        $con_gsk_old = env('DB_CONNECTION_OLD');
        $sql = "SELECT * FROM tbldepartments;";
        $sql2 = "SELECT * FROM tbldepartmentscontractor;";
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        $results2 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql2));
        $data_department = [];
        $data_department_contractor = [];

        if(count($results) > 0){
            foreach ($results as $department){

                $data_department[] = [
                    'id' => $department->keyID,
                    'name' => $department->name,
                    'parent_id' => $department->parentID,
                    'client_id' => $department->clientID,
                ];
            }

            DB::beginTransaction();
            try{
                Department::insert($data_department);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }

        if(count($results2) > 0){
            foreach ($results2 as $department_contractor){

                $data_department_contractor[] = [
                    'id' => $department_contractor->keyID,
                    'name' => $department_contractor->name,
                    'parent_id' => 0,
                    'client_id' => $department_contractor->clientID,
                ];
            }
            DB::beginTransaction();
            try{
                DepartmentContractor::insert($data_department_contractor);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        return 'Done';
    }
    public function survey() {
        $con_gsk_old = env('DB_CONNECTION_OLD');
        $sql = "SELECT * FROM tblsurveys;";
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        $data_survey = [];
        $data_property_info = [];

        if(count($results) > 0){
            foreach ($results as $survey){

                $data_survey[] = [
                    'keyID' => $survey->keyID,
                    'clientKey' => $survey->keyID,
                    'siteKey' => $survey->keyID,
                    'projectKey' => $survey->keyID,
                    'surveyType' => $survey->keyID,
                    'reference' => $survey->keyID,
                    'date' => $survey->keyID,
                    'jobNumber' => $survey->keyID,
                    'leadBy' => $survey->keyID,
                    'secondLeadBy' => $survey->keyID,
                    'surveyorKey' => $survey->keyID,
                    'secondSurveyor' => $survey->keyID,
                    'qualityKey' => $survey->keyID,
                    'analystKey' => $survey->keyID,
                    'consultantKey' => $survey->keyID,
                    'instructingPartyID' => $survey->keyID,
                    'instructingContactID' => $survey->keyID,
                    'addedBy' => $survey->keyID,
                    'reinspection' => $survey->keyID,
                    'locked' => $survey->keyID,
                    'reason' => $survey->keyID,
                    'processing' => $survey->keyID,
                    'status' => $survey->keyID,
                    'priorityAssessmentRequired' => $survey->keyID,
                    'constructionDetailsRequired' => $survey->keyID,
                    'locationVoidInvestigationsRequired' => $survey->keyID,
                    'locationConstructionDetailsRequired' => $survey->keyID,
                    'photosRequired' => $survey->keyID,
                    'licenseStatusRequired' => $survey->keyID,
                    'sizeRequired' => $survey->keyID,
                    'RDinManagementAllowed' => $survey->keyID,
                    'propertyPlanPhoto' => $survey->keyID,
                    'decommissioned' => $survey->keyID,
                    'isGenerateBC' => $survey->keyID,
                    'objectives_scope' => $survey->keyID,
                    'comments' => $survey->keyID,
                    'executivesummary' => $survey->keyID,
                    'siteData' => $survey->keyID,
                    'method' => $survey->keyID,
                    'limitations' => $survey->keyID,
                    'methodStyle' => $survey->keyID,
                    'rpDueDate' => $survey->keyID,
                    'rpCompletedDate' => $survey->keyID,
                    'rpStartedDate' => $survey->keyID,
                    'rpSentOutDate' => $survey->keyID,
                    'rpPublishedDate' => $survey->keyID,
                    'rpSentBackDate' => $survey->keyID,
                    'svStartDate' => $survey->keyID,
                    'svFinishDate' => $survey->keyID,
                ];
                $data_property_info[] = [
                ];
            }

            DB::beginTransaction();
            try{

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        return 'Done';
    }

    public function migrate_property_dropdown() {
        $con_gsk_old = env('DB_CONNECTION_OLD');
        $sql_prop_comment= "SELECT * FROM tblpropertycomment;";
        $sql_prop_dropdown= "SELECT * FROM tblpropertydropdown;";
        $sql_prop_dropdown_title = "SELECT * FROM tblpropertydropdowntitle;";
        $sql_prop_dropdown_value = "SELECT * FROM tblpropertydropdownvalue;";
        $sql_prop_email = "SELECT * FROM tblpropertyemailsent;";
        $sql_prop_program_phase = "SELECT * FROM tblpropertyprogrammephase;";
        $sql_prop_program_type = "SELECT * FROM tblpropertyprogrammetype;";
        $sql_prop_type = "SELECT * FROM tblpropertytype;";

        $prop_comment = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_prop_comment));
        $prop_dropdown = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_prop_dropdown));
        $prop_dropdown_title  = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_prop_dropdown_title));
        $prop_dropdown_value  = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_prop_dropdown_value));
        $prop_email  = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_prop_email));
        $prop_program_phase  = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_prop_program_phase));
        $prop_program_type  = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_prop_program_type));
        $prop_type  = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_prop_type));
        $data_comment = [];
        $data_dropdown = [];
        $data_dropdown_title  = [];
        $data_dropdown_value  = [];
        $data_email  = [];
        $data_program_phase  = [];
        $data_program_type  = [];
        $data_type  = [];
        if( count($prop_comment) > 0 &&
            count($prop_dropdown) > 0 &&
            count($prop_dropdown_title) > 0 &&
            count($prop_dropdown_value) > 0 &&
            count($prop_email) > 0 &&
            count($prop_program_phase) > 0 &&
            count($prop_program_type) > 0 &&
            count($prop_type) > 0
        ){
            foreach ($prop_comment as $comment){
                $data_comment[] = [
                    'id' => $comment->ID,
                    'record_id' => $comment->recordID,
                    'comment' => $comment->comment,
                    'parent_reference' => $comment->parentReference,
                    'created_at' => date("Y-m-d H:i:s", $comment->createdDate),
                ];
            }
            foreach ($prop_dropdown as $dropdown){
                $data_dropdown[] = [
                    'id' => $dropdown->ID,
                    'dropdown_id' => $dropdown->dropdownID,
                    'description' => $dropdown->description,
                    'order' => $dropdown->order,
                    'color' => $dropdown->color,
                    'other' => $dropdown->other
                ];
            }
            foreach ($prop_dropdown_title as $dropdown_title){
                $data_dropdown_title[] = [
                    'id' => $dropdown_title->ID,
                    'name' => $dropdown_title->name,
                    'key_name' => $dropdown_title->keyName
                ];
            }
            foreach ($prop_dropdown_value as $dropdown_value){
                $data_dropdown_value[] = [
                    'property_id' => $dropdown_value->siteID,
                    'dropdown_id' => $dropdown_value->dropdownID,
                    'data_id' => $dropdown_value->dataID,
                    'data_other' => $dropdown_value->dataOther
                ];
            }
            foreach ($prop_email as $p_email){
                $data_email[] = [
                    'property_id' => $p_email->siteID,
                    'email_type' => $p_email->emailType,
                    'last_sent' => $p_email->lastSent
                ];
            }
            foreach ($prop_program_phase as $program_phase){
                $data_program_phase[] = [
                    'id' => $program_phase->ID,
                    'description' => $program_phase->description,
                    'order' => $program_phase->order,
                    'color' => $program_phase->color
                ];
            }
            foreach ($prop_program_type as $program_type){
                $data_program_type[] = [
                    'id' => $program_type->ID,
                    'description' => $program_type->description,
                    'order' => $program_type->order,
                    'color' => $program_type->color,
                    'other' => $program_type->other
                ];
            }
            foreach ($prop_type as $p_type){
                $data_type[] = [
                    'id' => $p_type->ID,
                    'description' => $p_type->description,
                    'code' => $p_type->code,
                    'order' => $p_type->order,
                    'ms_level' => $p_type->msLevel
                ];
            }

            DB::beginTransaction();
            try{
//                PropertyComment::insert($data_comment);
                PropertyDropdown::insert($data_dropdown);
                PropertyDropdownTitle::insert($data_dropdown_title);
                PropertyEmailSent::insert($data_email);
//                PropertyProgrammePhase::insert($data_program_phase);
//                PropertyProgrammeType::insert($data_program_type);
//                PropertyType::insert($data_type);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        return 'Done';
    }

    public function migrate_dropdown(){
        $con_gsk_old = env('DB_CONNECTION_OLD');
        $sql_tbl_dropdown = "SELECT * FROM tbldropdown;";
        $sql_tbl_dropdown_data = "SELECT * FROM tbldropdowndata;";
        $sql_tbl_dropdown_short = "SELECT * FROM tbldropdownshort;";
        $sql_tbl_dropdown_value = "SELECT * FROM tbldropdownvalue;";
        //get value of dropdown
        $dropdown = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_tbl_dropdown));
        $dropdown_data = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_tbl_dropdown_data));
        $dropdown_short = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_tbl_dropdown_short));
        $dropdown_value = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_tbl_dropdown_value));

        $data_dropdown = [];
        $data_dropdown_data = [];
        $data_dropdown_short = [];
        $data_dropdown_value = [];

        if( count($dropdown) > 0 &&
            count($dropdown_data) > 0 &&
            count($dropdown_short) > 0 &&
            count($dropdown_value) > 0
        ){
            foreach ($dropdown as $dropdown_val){
                $data_dropdown[] = [
                ];
            }
            foreach ($dropdown_data as $dropdown_data_val){
                $data_dropdown_data[] = [
                ];
            }
            foreach ($dropdown_short as $dropdown_short_val){
                $data_dropdown_short[] = [
                ];
            }
            foreach ($dropdown_value as $dropdown_val){
                $data_dropdown_value[] = [
                ];
            }

            DB::beginTransaction();
            try{
//                Property::insert($data_property);
//                PropertyInfo::insert($data_property_info);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        return 'Done';
    }

    public function testSendMail(){
        $data['message'] = ['Hi Hoang'];
//        $mailable = new MailableContract;
        Mail::to('hoangth@neotiq.net')->send(new HoangTestMail());
    }
}

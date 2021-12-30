<?php

namespace Modules\GSK\Http\Controllers;

use App\Mail\HoangTestMail;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Contact;
use App\Models\Role;
use App\Models\PropertyInfo;
use App\Models\Property;
use Illuminate\Support\Facades\Mail;

class MigrationController extends Controller
{
//    public function index(){
//        dd(123);
//    }

    public function migrate_user(){
//        User::insert(['email'=>'123']);dd(123);
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM tblusers;";
        $data = [];
        $data_contact = [];
        $data_rule = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
//        dd($results);
        if(count($results) > 0){
            foreach ($results as $user){
//                    $data = "INSERT INTO tbl_users us WHERE ";
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
                    'lastAsbestosTraining' =>  $user->lastAsbestosTraining,
                    'lastShineAsbestosTraining' =>  $user->lastShineAsbestosTraining,
                    'notes' =>  $user->notes,
                    'tokenID' =>  $user->tokenID,
                    'deleted_by' =>  NULL,
                    'created_at' =>  NULL,
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
                    'mobile' => $user->usMobile
                ];
                $data_rule[] = [
                    'user_id' => $user->keyID,
                    'view_emp' => $user->viewEMP,
                    'view_privilege' => $user->viewPrivilege,
                    'update_emp' => $user->updateEMP,
                    'update_privilege' => $user->updatePrivilege,
                    'update_project_type' => $user->updateProjectType,
                    'update_email_type' => $user->updateEmailType
                ];
            }
//            dd($data_user);
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
//        dd(config('database.connections.mysql_gsk_old'));
        $con_gsk_old = env('DB_CONNECTION_OLD');
        $sql = "SELECT * FROM tblsites;";
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        $data_property = [];
        $data_property_info = [];
//        dd($results);
        if(count($results) > 0){
            foreach ($results as $property){
//                    $data = "INSERT INTO tbl_users us WHERE ";
                //date("Y-m-d H:i:s", 1388516401);
                $data_property[] = [
                    'id' => $property->keyID,
                    'reference' => $property->shineReference,
                    'client_id' => $property->clientKey,
                    'area_id' => $property->zoneKey,
                    'property_reference' => $property->reference,
                    'name' => $property->name,
                    'decommissioned' => $property->decommissioned == -1 ? 1 : 0,
                    'risk_type' => $property->riskType,
                    'comments' => $property->comments,
                    'programme_type' => $property->programmeType,
                    'programme_phase' => $property->programmePhase,
                    'register_updated' => $property->registerUpdated,
                    'programme_type_other' => $property->programmeTypeOther,
                    'pblock' => $property->pblock
                ];
                $data_property_info[] = [
                    'property_id' => $property->keyID,
                    'address1' => $property->address1,
                    'address2' => $property->address2,
                    'address3' => $property->address3,
                    'address4' => $property->address4,
                    'address5' => $property->address5,
                    'postcode' => $property->postcode,
                    'country' => $property->country,
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
                    'asset_use_primary' => $property->assetUsePrimary,
                    'asset_use_primary_other' => $property->assetUsePrimaryOther,
                    'asset_use_secondary' => $property->assetUseSecondary,
                    'asset_use_secondary_other' => $property->assetUseSecondaryOther,
                    'construction_age' => $property->constructionAge,
                    'construction_type' => $property->constructionType,
                    'size_floors' => $property->sizeFloors,
                    'size_staircases' => $property->sizeStaircases,
                    'size_lifts' => $property->sizeLifts,
                    'size_net_area' => $property->sizeNetArea,
                    'size_bedrooms' => $property->sizeBedrooms,
                    'size_gross_area' => $property->sizeGrossArea,
                    'size_comments' => $property->sizeComments
                ];
            }
//            dd($data_user);
            DB::beginTransaction();
            try{
                Property::insert($data_property);
                PropertyInfo::insert($data_property_info);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        return 'Done';
    }
    public function migrate_client() {
        return 'Done';
    }
    public function migrate_department() {
        return 'Done';
    }
    public function survey() {
        return 'Done';
    }

    public function testSendMail(){
        $data['message'] = ['Hi Hoang'];
//        $mailable = new MailableContract;
        Mail::to('hoangth@neotiq.net')->send(new HoangTestMail());
    }
}

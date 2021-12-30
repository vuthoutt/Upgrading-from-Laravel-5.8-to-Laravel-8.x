<?php

namespace app\Http\Controllers\Migration;

use App\Models\WorkRequest;
use App\Models\WorkScope;
use App\Models\WorkRequirement;
use App\Models\WorkPropertyInfo;
use App\Models\PublishedWorkRequest;
use App\Models\WorkSupportingDocument;
use App\Models\WorkContact;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;

class MigrationWorkRequest extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function index($type) {

        switch ($type) {
            case 1:
                $this->workRequest();
                break;

            case 2:

                $this->publishedWork();
                break;

            default:
                # code...
                break;
        }
    }
    public function workRequest(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT wr.*,wc.team1,wc.team2,wc.team3,wc.team4,wc.team5,wc.team6,wc.team7,wc.team8,wc.team9,wc.team10,wc.non_user
                         FROM tblworkrequest wr left join tblworkrequestcontact wc on wr.keyID = wc.work_request_id
                         WHERE keyID > 0 Group by keyID;";
        $data = [];
        $data_zone = [];
        $data_work_request = [];
        $data_work_property_info = [];
        $data_scope = [];
        $data_requirement = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        $i = 0;
        if(count($results) > 0){
            foreach ($results as $work){

                $data_work_request[] = [
                    'id' => $work->keyID,
                    'reference' => $work->reference,
                    'type' => $work->type,
                    'property_id' => $work->siteID,
                    'project_id' => $work->project_id,
                    'status' => $work->status,
                    'asbestos_lead' => $work->asbestosLead,
                    'second_asbestos_lead' => $work->secondAsbestosLead,
                    'decommissioned' => ($work->decommissioned == -1) ? 1 : 0,
                    'decommisioned_reason' => $work->reason,
                    'published_date' => $work->published_date,
                    'completed_date' => $work->completed_date,
                    'rejected_date' => $work->rejected_date,
                    'due_date' => $work->due_date,
                    'created_date' => $work->created_date,
                    'document_present' => $work->dcDocumentPresent,
                    'priority' => $work->priority,
                    'priority_reason' => $work->priority_reason,
                    'comments' => $work->comments,
                    'team1' => $work->team1,
                    'team2' => $work->team2,
                    'team3' => $work->team3,
                    'team4' => $work->team4,
                    'team5' => $work->team5,
                    'team6' => $work->team6,
                    'team7' => $work->team7,
                    'team8' => $work->team8,
                    'team9' => $work->team9,
                    'team10' => $work->team10,
                    'non_user' => $work->non_user,
                    'created_at' => $work->created_date > 0 ? date("Y-m-d H:i:s", $work->created_date) : NULL
                ];

                $data_work_property_info[] = [

                    "work_id" => $work->keyID,
                    "site_occupied" => $work->siteOccupied,
                    "site_availability" => $work->siteAvailability,
                    "security_requirements" => $work->securityRequirements,
                    "parking_arrangements" => $work->parkingArrangements,
                    "electricity_availability" => $work->electricityAvailability,
                    "water_availability" => $work->waterAvailability,
                    "ceiling_height" => $work->ceilingHeight,
                ];

                $data_scope[] = [

                    'work_id' => $work->keyID,
                    'scope_of_work' => $work->scopeOfWork,
                    'air_test_type' => $work->airTestType,
                    'enclosure_size' => (int)$work->enclosureSize,
                    'duration_of_work' => (int)$work->durationOfWork,
                    'isolation_required' => $work->isolationRequired,
                    'decant_required' => $work->decantRequired,
                    'reinstatement_requirements' => $work->reinstatementRequirments,
                    'number_of_rooms' => $work->numberOfRooms,
                    'unusual_requirements' => $work->unusualRequirements,
                ];

                $data_requirement[] = [

                    'work_id' => $work->keyID,
                    'site_hs' => $work->siteHS,
                    'hight_level_access' => $work->hightLevelAccess,
                    'hight_level_access_comment' => $work->hightLevelAccessComment,
                    'max_height' => (int)$work->maxHeight,
                    'max_height_comment' => $work->maxHeightComment,
                    'loft_spaces' => $work->loftSpaces,
                    'loft_spaces_comment' => $work->loftSpacesComment,
                    'floor_voids' => $work->floorVoids,
                    'floor_voids_comment' => $work->floorVoidsComment,
                    'basements' => $work->basements,
                    'basements_comment' => $work->basementsComment,
                    'ducts' => $work->ducts,
                    'ducts_comment' => $work->ductsComment,
                    'lift_shafts' => $work->liftShafts,
                    'lift_shafts_comment' => $work->liftShaftsComment,
                    'light_wells' => $work->lightWell,
                    'light_wells_comment' => $work->lightWellComment,
                    'confined_spaces' => $work->confinedSpaces,
                    'confined_spaces_comment' => $work->confinedSpacesComment,
                    'fumes_duct' => $work->fumesDuct,
                    'fumes_duct_comment' => $work->fumesDuctComment,
                    'pm_good' => $work->pmGood,
                    'pm_good_comment' => $work->pmGoodComment,
                    'fragile_material' => $work->fragileMaterial,
                    'fragile_material_comment' => $work->fragileMaterialComment,
                    'hot_live_services' => $work->hotLiveServices,
                    'hot_live_services_comment' => $work->hotLiveServicesComments,
                    'pieons' => $work->pieons,
                    'pieons_comment' => $work->pieonsComments,
                    'vermin' => $work->vermin,
                    'vermin_comment' => $work->verminComments,
                    'biological_chemical' => $work->biologicalChemical,
                    'biological_chemical_comment' => $work->biologicalChemicalComments,
                    'vulnerable_tenant' => $work->vulnerableTenant,
                    'vulnerable_tenant_comment' => $work->vulnerableTenantComment,
                    'other' => $work->other,
                ];
                $i ++;
            }

            DB::beginTransaction();
            try{
                WorkRequest::insert($data_work_request);
                WorkPropertyInfo::insert($data_work_property_info);
                WorkScope::insert($data_scope);
                WorkRequirement::insert($data_requirement);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        dd('Done');
        return 'Done';
    }

    public function publishedWork(){
                $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql_publish = "SELECT * from published_work_request";
        $sql_doc = "SELECT * from work_supporting_documents";
        $sql_contact = "SELECT * from tblworkrequestcontact";
        $data = [];
        $data_publish = [];
        $data_contact = [];
        $data_doc = [];
        $result_publish = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_publish));
        $result_doc = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_doc));
        $result_contact = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_contact));

        if(count($result_publish) > 0){
            foreach ($result_publish as $data){
                $data_publish[] = [
                    'id' => $data->keyID,
                   'work_request_id' => $data->work_request_id,
                   'name' => $data->name,
                   'revision' => $data->revision,
                   'type' => $data->type,
                   'size' => $data->size,
                   'filename' => $data->filename,
                   'mime' => $data->mime,
                   'path' => $data->path,
                   'created_by' => $data->created_by,
                   'created_at' => $data->created_date > 0 ? date("Y-m-d H:i:s", $data->created_date) : NULL
                ];
            }
            foreach ($result_doc as $data){
                $data_doc[] = [
                    'id' => $data->id,
                    'reference' => $data->shineReference,
                    'work_id' => $data->work_id,
                    'name' => $data->name,
                    'added' => $data->dcAdded,
                    'created_at' => $data->dcAdded > 0 ? date("Y-m-d H:i:s", $data->dcAdded) : NULL
                ];
            }

            foreach ($result_contact as $data){
                $data_contact[] = [
                    'id' => $data->ID,
                    'work_id' => $data->work_request_id,
                    'first_name' => $data->first_name,
                    'last_name' => $data->last_name,
                    'telephone' => $data->telephone,
                    'mobile' => $data->mobile,
                    'email' => $data->email
                ];
            }
            DB::beginTransaction();
            try{
                PublishedWorkRequest::insert($data_publish);
                WorkSupportingDocument::insert($data_doc);
                WorkContact::insert($data_contact);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        dd('Done');
        return 'Done';
    }
}

<?php

namespace app\Http\Controllers\Migration;

use App\Models\Project;
use App\Models\ProjectSponsor;
use App\Models\ProjectType;
use App\Models\RRCondition;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;

class MigrationProjectTypeController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_project_type(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM tblProjectsTypes";
        $sql2 = "SELECT * FROM tblProjectSponsor";
        $sql3 = "SELECT * FROM tblprojects;";
        $sql4 = "SELECT * FROM tblrrconditions;";
        $data_pjt = [];
        $data_pjs = [];
        $data_pj = [];
        $data_rr = [];
        $results_pjt = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        $results_pjs = DB::connection($con_gsk_old)
            ->select(DB::raw($sql2));
        $results_pj = DB::connection($con_gsk_old)
            ->select(DB::raw($sql3));
        $results_rr = DB::connection($con_gsk_old)
            ->select(DB::raw($sql4));
//        dd($results);
        if(count($results_pjt) > 0 && count($results_pjs) && count($results_pj) && count($results_rr)){
            foreach ($results_pjt as $pjt){
                $data_pjt[] = [
                    "id" => $pjt->keyID,
                    "description" => $pjt->description,
                    "order" => $pjt->order
                ];
            }

            foreach ($results_pjs as $pjs){
                $data_pjs[] = [
                    "id" => $pjs->keyID,
                    "description" => $pjs->description,
                    "position" => $pjs->position
                ];
            }

            foreach ($results_pj as $pj){
//                    $data = "INSERT INTO tbl_users us WHERE ";
                $data_pj[] = [
                    "id" => $pj->keyID,
                    "client_id" => $pj->clientKey,
                    "property_id" => $pj->siteKey,
                    "survey_id" => $pj->surveyKey,
                    "survey_type" => $pj->surveyType,
                    "title" => $pj->title,
                    "project_type" => $pj->projectType,
                    "reference" => $pj->reference,
                    "date" => $pj->date,
                    "due_date" => $pj->dueDate,
//                    "enquiry_date" => $pj->enquiryDate,
                    "completed_date" => $pj->completedDate,
                    "lead_key" => $pj->leadKey,
                    "second_lead_key" => $pj->secondLeadKey,
                    "sponsor_lead_key" => $pj->sponsorLeadKey,
                    "sponsor_id" => $pj->sponsorID,
                    "job_no" => $pj->jobNo,
                    "locked" => $pj->locked == -1 ? 1 : 0,
                    "additional_info" => $pj->additionalInfo,
                    "status" => $pj->status,
                    "contractors" => $pj->contractors,
                    "checked_contractors" => $pj->checked_contractors,
                    "comments" => $pj->comments,
                    "rr_condition" => $pj->rrCondition,
//                    "created_by" => $pj->createdBy,
                    "created_at" => $pj->date > 0 ? date("Y-m-d H:i:s", $pj->date) : NULL
                ];
            }

            foreach ($results_rr as $rr){
                $data_rr[] = [
                    "id" => $rr->ID,
                    "description" => $rr->description,
                    "order" => $rr->order
                ];
            }
//            dd($data_user);
            DB::beginTransaction();
            try{
                ProjectType::insert($data_pjt);
                ProjectSponsor::insert($data_pjs);
                Project::insert($data_pj);
                RRCondition::insert($data_rr);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        return 'Done';
    }
}

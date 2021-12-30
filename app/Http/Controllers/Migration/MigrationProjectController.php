<?php

namespace app\Http\Controllers\Migration;

use App\Models\Project;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;

class MigrationProjectController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_project(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM tblprojects;";
        $data = [];
        $data_pj = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
//        dd($results);
        if(count($results) > 0){
            foreach ($results as $pj){
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
//            dd($data_user);
            DB::beginTransaction();
            try{
                Project::insert($data_pj);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        return 'Done';
    }
}

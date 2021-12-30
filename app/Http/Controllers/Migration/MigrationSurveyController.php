<?php

namespace app\Http\Controllers\Migration;

use App\Models\PublishedSurvey;
use App\Models\RefurbDocType;
use App\Models\SurveyAnswer;
use App\Models\SurveyType;
use App\Models\Zone;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\Survey;
use App\Models\SurveyInfo;
use App\Models\SurveyDate;
use App\Models\SurveySetting;

class MigrationSurveyController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_survey(){
        $con_gsk_old = "mysql_gsk_old";
        $sql = "SELECT * FROM tblsurveys;";
        $sql2 = "SELECT * FROM tblsurveytype;";
        $sql3 = "SELECT * FROM tblsurveysanswer;";
        $sql4 = "SELECT * FROM refurb_doc_types;";
        $sql5 = "SELECT * FROM published_surveys;";
        $data_survey = $data_survey_info = $data_survey_date = $data_survey_setting = $survey_type = $survey_answer = $refurb_doc_type = $data_publish_survey = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        $results2 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql2));
        $results3 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql3));
        $results4 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql4));
        $results5 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql5));
//        dd($results);
        // data = created_date, addedBy = created_date
//        dd(count($results) , count($results2) , count($results3) , count($results4) , count($results5));
        if(count($results) > 0 && count($results2) && count($results4) && count($results5)){
            foreach ($results as $survey){
                $data_survey[] = [
                    'id' => $survey->keyID,
                    'client_id' => $survey->clientKey,
                    'property_id' => $survey->siteKey,
                    'project_id' => $survey->projectKey,
                    'survey_type' => $survey->surveyType,
                    'reference' => $survey->reference,
                    'decommissioned' => $survey->decommissioned == -1 ? 1 : 0,
                    'lead_by' => $survey->leadBy,
                    'second_lead_by' => $survey->secondLeadBy,
                    'surveyor_id' => $survey->surveyorKey,
//                    'second_surveyor_id' => $survey->secondSurveyor,
                    'quality_id' => $survey->qualityKey,
                    'analyst_id' => $survey->analystKey,
                    'consultant_id' => $survey->consultantKey,
                    'is_locked' => $survey->locked == -1 ? SURVEY_LOCKED : SURVEY_UNLOCKED,
                    'reason' => $survey->reason,
                    'status' => $survey->status,
                    'created_at'=> $survey->date > 0 ? date("Y-m-d H:i:s", $survey->date) : NULL,
                    'created_by' => $survey->addedBy,
                    'work_stream' => $survey->workStream
                ];
                $data_survey_info[] = [
                    'survey_id' => $survey->keyID,
                    'objectives_scope' => $survey->objectives_scope,
                    'comments' => $survey->comments,
                    'executive_summary' => $survey->executivesummary,
                    'property_data' => $survey->siteData,
                    'method' => $survey->method,
                    'limitations' => $survey->limitations,
                    'method_style' => $survey->methodStyle == -1 ? 0 : $survey->methodStyle
                ];
                $data_survey_date[] = [
                    'survey_id' => $survey->keyID,
                    'due_date' => $survey->rpDueDate,
                    'completed_date' => $survey->rpCompletedDate,
                    'started_date' => $survey->rpStartedDate,
                    'sent_out_date' => $survey->rpSentOutDate,
                    'published_date' => $survey->rpPublishedDate,
                    'sent_back_date' => $survey->rpSentBackDate,
                    'surveying_start_date' => $survey->svStartDate,
                    'surveying_finish_date' => $survey->svFinishDate
                ];
                $data_survey_setting[] = [
                    'survey_id' => $survey->keyID,
                    'is_property_plan_photo' => $survey->propertyPlanPhoto == -1 ? REQUIRE_VALUE : NOT_REQUIRE_VALUE,
                    'is_require_priority_assessment' => $survey->priorityAssessmentRequired == -1 ? REQUIRE_VALUE : NOT_REQUIRE_VALUE,
                    'is_require_construction_details' => $survey->constructionDetailsRequired == -1 ? REQUIRE_VALUE : NOT_REQUIRE_VALUE,
                    'is_require_location_void_investigations' => $survey->locationVoidInvestigationsRequired == -1 ? REQUIRE_VALUE : NOT_REQUIRE_VALUE,
                    'is_require_location_construction_details' => $survey->locationConstructionDetailsRequired == -1 ? REQUIRE_VALUE : NOT_REQUIRE_VALUE,
                    'is_require_photos' => $survey->photosRequired == -1 ? REQUIRE_VALUE : NOT_REQUIRE_VALUE,
                    'is_require_license_status' => $survey->licenseStatusRequired == -1 ? REQUIRE_VALUE : NOT_REQUIRE_VALUE,
                    'is_require_size' => $survey->sizeRequired == -1 ? REQUIRE_VALUE : NOT_REQUIRE_VALUE,
                    'is_require_r_and_d_elements' => $survey->RDinManagementAllowed == -1 ? REQUIRE_VALUE : NOT_REQUIRE_VALUE,
                    'is_require_site_objective_scope' => $survey->siteObjectiveScope == -1 ? REQUIRE_VALUE : NOT_REQUIRE_VALUE,
                ];
            }

            foreach ($results2 as $r){
                $survey_type[] = [
                    'id' => $r->ID,
                    'description' => $r->description,
                    'order' => $r->order,
                    'color' => $r->color
                ];
            }

            foreach ($results3 as $r){
                $survey_answer[] = [
                    'id' => $r->keyID,
                    'survey_id' => $r->surveyID,
                    'question_id' => $r->questionID,
                    'answer_id' => $r->answerID,
                    'answerOther' => $r->answerOther,
                    'comment' => $r->comment,
                ];
            }

            foreach ($results4 as $r){
                $refurb_doc_type[] = [
                    'id' => $r->keyID,
                    'doc_type' => $r->docType,
                    'refurb_type' => $r->refurbType,
                    'order' => $r->order,
//                    'is_active' => $r->is_active
                ];
            }

            foreach ($results5 as $ps){
                $data_publish_survey[] = [
                    'id' => $ps->psID,
//                    'client_id' => $ps->clientKey, // remove
//                    'contractor_id' => $ps->psContractor, // remove
                    'survey_id' => $ps->psReportID,
                    'name' => $ps->psName,
                    'revision' => $ps->psRevision,
                    'type' => $ps->psType,
                    'size' => $ps->psSize,
                    'filename' => $ps->psFilename,
                    'mime' => $ps->psMime,
                    'path' => $ps->psPath,
//                    'is_large_file' => $ps->is_large_file,
                    'created_by' => $ps->psAddedBy,
                    'created_at' => $ps->psAdded > 0 ? date("Y-m-d H:i:s", $ps->psAdded) : NULL
                ];
            }
//            dd($data_survey, $data_survey_info, $data_survey_date, $data_survey_setting, $survey_type);
            DB::beginTransaction();
            try{
//                Location::insert($data_location);
//                LocationVoid::insert($data_void);
//                LocationConstruction::insert($data_construction);
                //insert location
                $insert_data_survey = collect($data_survey);
                $chunks = $insert_data_survey->chunk(500);
                foreach ($chunks as $chunk)
                {
                    Survey::insert($chunk->toArray());
                }
                //insert location void
                $insert_data_survey_info = collect($data_survey_info);
                $chunks_info = $insert_data_survey_info->chunk(500);

                foreach ($chunks_info as $chunk_i)
                {
                    SurveyInfo::insert($chunk_i->toArray());
                }
                //insert location construction
                $insert_data_survey_date = collect($data_survey_date);
                $chunks_date = $insert_data_survey_date->chunk(500);

                foreach ($chunks_date as $chunk_d)
                {
                    SurveyDate::insert($chunk_d->toArray());
                }
                //insert location infor
                $insert_data_survey_setting = collect($data_survey_setting);
                $chunks_date = $insert_data_survey_setting->chunk(500);

                foreach ($chunks_date as $chunk_dt)
                {
                    SurveySetting::insert($chunk_dt->toArray());
                }

                $insert_data_st = collect($survey_type);
                $chunks_st = $insert_data_st->chunk(500);
                foreach ($chunks_st as $chunk)
                {
                    SurveyType::insert($chunk->toArray());
                }

                $insert_data_sa = collect($survey_answer);
                $chunks_sa = $insert_data_sa->chunk(500);
                foreach ($chunks_sa as $chunk)
                {
                    SurveyAnswer::insert($chunk->toArray());
                }

                $insert_data_rd = collect($refurb_doc_type);
                $chunks_rd = $insert_data_rd->chunk(500);
                foreach ($chunks_rd as $chunk)
                {
                    RefurbDocType::insert($chunk->toArray());
                }

                $insert_data_ps = collect($data_publish_survey);
                $chunks_ps = $insert_data_ps->chunk(500);
                foreach ($chunks_ps as $chunk)
                {
                    PublishedSurvey::insert($chunk->toArray());
                }
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        DB::disconnect($con_gsk_old);
        dd('Done');
    }
}

<?php

namespace app\Http\Controllers\Migration;

use App\Models\Document;
use App\Models\DocumentBluelightService;
use App\Models\Policy;
use App\Models\SampleCertificate;
use App\Models\SitePlanDocument;
use App\Models\Template;
use App\Models\TemplatesCategory;
use App\Models\TrainingRecord;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class MigrationDocumentBlueLightServiceController extends Controller
{
    /**
     * Migration upload_manifest and download_manifest
     * @return string
     */
    public function migrate_data(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        //no need convert blue light service
        $sql = "SELECT * FROM tbl_document_blue_light_service;";
        $sql2 = "SELECT * FROM documents;";
        $sql3 = "SELECT * FROM siteplan_documents;";
        $sql4 = "SELECT * FROM templates;";
        $sql5 = "SELECT * FROM templates_categories;";
//        $sql6 = "SELECT * FROM training_records;";
        $sql7 = "SELECT * FROM policies;";
        $sql8 = "SELECT * FROM tblsamplecertificates;";
//        $results = DB::connection($con_gsk_old)
//            ->select(DB::raw($sql));
        $results2 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql2));
        $results3 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql3));
        $results4 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql4));
        $results5 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql5));
//        $results6 = DB::connection($con_gsk_old)
//            ->select(DB::raw($sql6));
        $results7 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql7));
        $results8 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql8));
        $data_1 = $data_2 = $data_3 = $data_4 = $data_5 = $data_6 = $data_7 = $data_8 = [];
        if(count($results2) > 0 && count($results3) > 0  && count($results7) > 0 && count($results8) > 0){
//            foreach ($results as $d){
//                $data_1[] = [
//                    'id' => $d->id,
//                    'zone_id' => $d->zoneID,
//                    'property_id' => $d->siteID,
//                    'name' => $d->name,
//                    'type' => $d->type,
//                    'size' => $d->size,
//                    'mime' => $d->mime,
//                    'path' => $d->path,
//                    'number_record' => $d->number_record,
//                    'property_reference' => $d->site_reference,
//                    'last_revision_id' => $d->last_revision_ID,
//                    'created_by' => $d->created_by,
//                    'created_date' => $d->created_date,
//                    'created_at' => $d->id,
//                    'updated_at' => $d->id,
//                    'deleted_at' => $d->id,
//                ];
//            }

            foreach ($results2 as $d){
                $data_2[] = [
                    'id' => $d->dcID,
                    'client_id' => $d->clientKey,
                    'name' => $d->dcName,
                    'reference' => $d->dcReference,
                    'project_id' => $d->dcProjectID,
                    'document_present' => $d->dcDocumentPresent,
                    'status' => $d->status,
                    'type' => $d->dcType,
                    're_type' => $d->dcReType,
                    'contractor' => $d->dcContractor,
                    'size' => $d->dcSize,
                    'file_name' => $d->dcFilename,
                    'mime' => $d->dcMime,
                    'path' => $d->cdPath,
                    'note' => $d->dcNote,
                    'category' => $d->dcCategory,
                    'added' => $d->dcAdded,
                    'rejected' => $d->dcRejected,
                    'deleted' => $d->dcDeleted,
                    'authorised' => $d->dcAuthorised,
                    'approved' => $d->dcApproved,
                    'added_by' => $d->dcAddedBy,
                    'authorised_by' => $d->dcAuthorisedBy,
                    'approved_by' => $d->dcApprovedBy,
                    'rejected_by' => $d->dcRejectedBy,
                    'deadline' => $d->dcDeadline,
                    'contractors' => $d->contractors,
                    'value' => $d->dcValue
                ];
            }

            foreach ($results3 as $d){
                $data_3[] = [
                    'id' => $d->dcID,
                    'reference' => $d->shineReference,
                    'property_id' => $d->siteID,
                    'name' => $d->dcName,
                    'plan_reference' => $d->dcReference,
                    'survey_id' => $d->dcReportID,
                    'document_present' => $d->dcDocumentPresent,
                    'type' => $d->dcType,
                    're_type' => $d->dcReType,
                    'contractor' => $d->dcContractor,
                    'size' => $d->dcSize,
                    'filename' => $d->dcFilename,
                    'mime' => $d->dcMime,
                    'path' => $d->cdPath,
                    'note' => $d->dcNote,
                    'category' => $d->dcCategory,
                    'added' => $d->dcAdded,
                    'rejected' => $d->dcRejected,
                    'deleted' => $d->dcDeleted,
                    'authorised' => $d->dcAuthorised,
                    'added_by' => $d->dcAddedBy,
                    'deleted_by' => $d->dcDeletedBy,
                    'authorised_by' => $d->dcAuthorisedBy,
                    'rejected_by' => $d->dcRejectedBy,
                    'deadline' => $d->dcDeadline,
                    'contractor1' => $d->contractor1,
                    'contractor2' => $d->contractor2,
                    'contractor3' => $d->contractor3,
                    'contractor4' => $d->contractor4,
                    'contractor5' => $d->contractor5,
                    'contractor6' => $d->contractor6,
                    'contractor7' => $d->contractor7,
                    'contractor8' => $d->contractor8,
                    'contractor9' => $d->contractor9,
                    'contractor10' => $d->contractor10

                ];
            }
            //next
            foreach ($results4 as $d){
                $data_4[] = [
                    'id' => $d->dcID,
                    'category_id' => $d->dcCategory,
                    'name' => $d->dcName,
                    'size' => $d->dcSize,
                    'file_name' => $d->dcFilename,
                    'mime' => $d->dcMime,
                    'added' => $d->dcAdded,
                    'created_by' => $d->dcAddedBy
                ];
            }

            foreach ($results5 as $d){
                $data_5[] = [
                    'id' => $d->ID,
                    'category' => $d->category,
                    'order' => $d->order
                ];
            }

//            foreach ($results6 as $d){
//                $data_6[] = [
//                    'id' => $d->dcID,
//                    'name' => $d->dcName,
//                    'size' => $d->dcSize,
//                    'file_name' => $d->dcFilename,
//                    'mime' => $d->dcMime,
//                    'added' => $d->dcAdded,
//                    'added_by' => $d->dcAddedBy,
//                    'client_id' => $d->dcClientID
//
//                ];
//            }

            foreach ($results7 as $d){
                $data_7[] = [
                    'id' => $d->dcID,
                    'name' => $d->dcName,
                    'size' => $d->dcSize,
                    'file_name' => $d->dcFilename,
                    'mime' => $d->dcMime,
                    'added' => $d->dcAdded,
                    'added_by' => $d->dcAddedBy,
                    'client_id' => $d->dcClientID
                ];
            }

            foreach ($results8 as $d){
                $data_8[] = [
                    'id' => $d->ID,
                    'survey_id' => $d->surveyID,
                    'reference' => $d->shineReference,
                    'sample_reference' => $d->sampleRef,
                    'description' => $d->description,
                    'updated_date' => $d->updatedDate,
                    'name' => $d->certificateName,
                    'mime' => $d->certificateMime
                ];
            }
//            dd($data_user);
            DB::beginTransaction();
            try{

//                $insert_1 = collect($data_1);
//                $chunks = $insert_1->chunk(500);
//                foreach ($chunks as $chunk)
//                {
//                    DocumentBluelightService::insert($chunk->toArray());
//                }

                $insert_2 = collect($data_2);
                $chunks = $insert_2->chunk(500);
                foreach ($chunks as $chunk)
                {
                    Document::insert($chunk->toArray());
                }

                $insert_3 = collect($data_3);
                $chunks = $insert_3->chunk(500);
                foreach ($chunks as $chunk)
                {
                    SitePlanDocument::insert($chunk->toArray());
                }

                $insert_4 = collect($data_4);
                $chunks = $insert_4->chunk(500);
                foreach ($chunks as $chunk)
                {
                    Template::insert($chunk->toArray());
                }

                $insert_5 = collect($data_5);
                $chunks = $insert_5->chunk(500);
                foreach ($chunks as $chunk)
                {
                    TemplatesCategory::insert($chunk->toArray());
                }

//                $insert_6 = collect($data_6);
//                $chunks = $insert_6->chunk(500);
//                foreach ($chunks as $chunk)
//                {
//                    TrainingRecord::insert($chunk->toArray());
//                }

                $insert_7 = collect($data_7);
                $chunks = $insert_7->chunk(500);
                foreach ($chunks as $chunk)
                {
                    Policy::insert($chunk->toArray());
                }

                $insert_8 = collect($data_8);
                $chunks = $insert_8->chunk(500);
                foreach ($chunks as $chunk)
                {
                    SampleCertificate::insert($chunk->toArray());
                }

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        dd('Done');
    }
}

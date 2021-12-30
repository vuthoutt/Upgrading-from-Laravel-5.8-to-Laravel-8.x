<?php

namespace app\Http\Controllers\Migration;

use App\Models\DownloadManifest;
use App\Models\UploadManifest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class MigrationManifestController extends Controller
{
    /**
     * Migration upload_manifest and download_manifest
     * @return string
     */
    public function migrate_manifest(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM upload_manifest;";
        $sql2 = "SELECT * FROM download_manifest;";
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        $results2 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql2));

        if(count($results) > 0 && count($results2) > 0){
            foreach ($results as $upload){

                $data_upload[] = [
                    'id' => $upload->ID,
                    'survey_id' => $upload->surveyKey,
                    'status' => $upload->status,
                    'user_id' => $upload->userKey,
                    'total_floor' => $upload->floorCount,// area
                    'total_room' => $upload->roomCount,// location
                    'total_record' => $upload->recordCount,// item
                    'total_image' => $upload->imageCount,
                    'total_plan' => $upload->recordObjectIds,// plan
                    'created_at' => $upload->startTime > 0 ? date("Y-m-d H:i:s", $upload->startTime) : NULL
                ];
            }

            foreach ($results2 as $download){

                $data_download[] = [
                    'id' => $download->ID,
                    'user_id' => $download->userKey,
                    'list_survey_id' => $download->surveyKey,
                    'created_at' => $download->createdDate
                ];
            }

            DB::beginTransaction();
            try{
                $insert_data = collect($data_upload);
                $chunks = $insert_data->chunk(500);
                foreach ($chunks as $chunk1)
                {
                    UploadManifest::insert($chunk1->toArray());
                }

                $insert_data_download = collect($data_download);
                $chunks_download = $insert_data_download->chunk(500);
                foreach ($chunks_download as $chunk2)
                {
                    DownloadManifest::insert($chunk2->toArray());
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

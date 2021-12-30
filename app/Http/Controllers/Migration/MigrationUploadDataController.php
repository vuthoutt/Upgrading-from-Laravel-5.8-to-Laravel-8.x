<?php

namespace app\Http\Controllers\Migration;

use App\Models\UploadDocumentStorage;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class MigrationUploadDataController extends Controller
{
    /**
     * Migration upload_manifest and download_manifest
     * @return string
     */
    public function migrate_upload_data(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM uploadData;";
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        if(count($results) > 0 ){
            foreach ($results as $upload){
                $data_upload[] = [
                    'id' => $upload->ID,
                    'survey_id' => $upload->surveyId,
                    'manifest_id' => $upload->manifestId,
                    'type' => $upload->type,
                    'data' => $upload->data,
                    'correct_id' => $upload->correctId,
                    'unique_id' => $upload->uniqueId
                ];
            }
//            dd($data_user);
            DB::beginTransaction();
            try{
//                UploadDocumentStorage::insert($data_upload);
                $insert_data = collect($data_upload);
                $chunks = $insert_data->chunk(500);
                foreach ($chunks as $chunk)
                {
                    UploadDocumentStorage::insert($chunk->toArray());
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

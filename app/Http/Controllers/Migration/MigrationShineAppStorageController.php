<?php

namespace app\Http\Controllers\Migration;

use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use App\Models\ShineAppDocumentStorage;
use Illuminate\Support\Facades\DB;

class MigrationShineAppStorageController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_shine_storage(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM shineappdocumentstorage;";
        $data_shine_storage = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        if(count($results) > 0){
            foreach ($results as $data){

                $data_shine_storage[] = [
                    'manifest_id' => $data->manifestId,
                    'record_id' => $data->recordID,
                    'type' => $data->type,
                    'survey_id' => $data->surveyID,
                    'path' => $data->path,
                    'file_name' => $data->fileName,
                    'mime' => $data->mime,
                    'size' => $data->size,
                ];
            }

            DB::beginTransaction();
            try{
                $insert_data = collect($data_shine_storage); // Make a collection to use the chunk method
                // it will chunk the dataset in smaller collections containing 500 values each.
                $chunks = $insert_data->chunk(500);

                foreach ($chunks as $chunk)
                {
                    ShineAppDocumentStorage::insert($chunk->toArray());
                }

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());
            }
        }
        return 'Done';
    }
}

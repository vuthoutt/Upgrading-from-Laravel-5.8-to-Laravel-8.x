<?php

namespace app\Http\Controllers\Migration;

use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;
use PHPUnit\Runner\Exception;

class MigrationSitePlanController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_shine_storage(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM shineDocumentStorage;";
        $data_shine_storage = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        if(count($results) > 0){
            foreach ($results as $document){
//                    $data = "INSERT INTO tbl_users us WHERE ";
                // date("Y-m-d H:i:s", $comment->createdDate)
                $data_shine_storage[] = [
                    'object_id' => $document->objectID,
                    'type' => $document->type,
                    'path' => $document->path,
                    'file_name' => $document->fileName,
                    'mime' => $document->mime,
                    'size' => $document->size,
                    'created_by' => $document->addedBy,
                    'created_at' => date("Y-m-d H:i:s", $document->addedDate),
                ];
            }
//            dd($data_shine_storage);
            DB::beginTransaction();
            try{
                $insert_data = collect($data_shine_storage); // Make a collection to use the chunk method
                // it will chunk the dataset in smaller collections containing 500 values each.
                $chunks = $insert_data->chunk(500);

                foreach ($chunks as $chunk)
                {
//                    \DB::table('items_details')->insert($chunk->toArray());
                    ShineDocumentStorage::insert($chunk->toArray());
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

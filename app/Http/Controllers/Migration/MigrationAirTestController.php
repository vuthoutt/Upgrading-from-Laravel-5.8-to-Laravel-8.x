<?php

namespace app\Http\Controllers\Migration;

use App\Models\AirTest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class MigrationAirTestController extends Controller
{
    /**
     * Migration upload_manifest and download_manifest
     * @return string
     */
    public function migrate_airtest_data(){
        $con_gsk_old = "mysql_gsk_old";
        $sql = "SELECT * FROM tblAirTest;";
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        if(count($results) > 0 ){
            foreach ($results as $airtest){
                $data_airtest[] = [
                    'id' => $airtest->ID,
                    'description' => $airtest->description,
                    'comment_id' => $airtest->commentKey,
                    'comment_other' => $airtest->commentOther
                ];
            }
//            dd($data_user);
            DB::beginTransaction();
            try{
                $insert_data = collect($data_airtest);
                $chunks = $insert_data->chunk(500);
                foreach ($chunks as $chunk)
                {
                    AirTest::insert($chunk->toArray());
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

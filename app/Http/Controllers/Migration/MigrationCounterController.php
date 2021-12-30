<?php

namespace app\Http\Controllers\Migration;

use App\Models\Zone;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\Counter;

class MigrationCounterController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_counter(){
        $con_gsk_old = "mysql_gsk_old";
        $sql = "SELECT * FROM tblcounter;";
        $data_counter = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
//        dd($results);
        // data = created_date, addedBy = created_date
        if(count($results) > 0){
            foreach ($results as $c){
                $data_counter[] = [
                    'count_table_name' => $c->cnTable,
                    'total' => $c->cnRecordID,
                ];
            }
            DB::beginTransaction();
            try{
                //insert location
                $insert_data_survey = collect($data_counter);
                $chunks = $insert_data_survey->chunk(500);
                foreach ($chunks as $chunk)
                {
                    Counter::insert($chunk->toArray());
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

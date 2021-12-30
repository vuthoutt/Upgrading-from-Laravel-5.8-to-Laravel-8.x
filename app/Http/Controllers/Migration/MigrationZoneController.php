<?php

namespace app\Http\Controllers\Migration;

use App\Models\Zone;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;

class MigrationZoneController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_zone(){$con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM tblzones;";
        $data = [];
        $data_zone = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
//        dd($results);
        if(count($results) > 0){
            foreach ($results as $zone){
//                    $data = "INSERT INTO tbl_users us WHERE ";
                $data_zone[] = [
                    'id' => $zone->keyID,
                    'client_id' => $zone->clientKey,
                    'reference' => $zone->shineReference,
                    'zone_code' => $zone->zonecode,
                    'zone_name' => $zone->zonename,
                    'parent_id' => $zone->parentID,
//                    'last_revision' => $zone->last_revision,
                ];
            }
//            dd($data_user);
            DB::beginTransaction();
            try{
                Zone::insert($data_zone);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        return 'Done';
    }
}

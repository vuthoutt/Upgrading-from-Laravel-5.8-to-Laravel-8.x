<?php

namespace app\Http\Controllers\Migration;

use App\Models\LogLogin;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;

class MigrationLogLoginController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function migrate_logLogin(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM log_login;";
        $data = [];
        $data_log_login = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));

        if(count($results) > 0){
            foreach ($results as $ll){

                $data_log_login[] = [
                    'id' => $ll->keyID,
                    'user_id'=> $ll->loguser,
                    'logusername'=> $ll->logusername,
                    'logpassword'=> $ll->logpassword,
                    'logip'=> $ll->logip,
                    'logtime'=> $ll->logtime,
                    'success'=> $ll->success == -1 ? 1 : 0,
                    'created_at' => $ll->logtime > 0 ? date("Y-m-d H:i:s", $ll->logtime) : NULL
                ];
            }

            DB::beginTransaction();
            try{
                $insert_loglogin = collect($data_log_login);
                $chunks = $insert_loglogin->chunk(500);
                foreach ($chunks as $chunk)
                {
                    LogLogin::insert($chunk->toArray());
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

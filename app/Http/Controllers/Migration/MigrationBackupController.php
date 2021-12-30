<?php

namespace app\Http\Controllers\Migration;

use App\Models\DownloadBackup;
use App\Models\UploadBackup;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class MigrationBackupController extends Controller
{
    /**
     * Migration upload_manifest and download_manifest
     * @return string
     */
    public function migrate_backup(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";
        $sql = "SELECT * FROM uploadBackup;";
        $sql2 = "SELECT * FROM downloadBackup;";
        $data = [];
        $data_zone = [];
        $results = DB::connection($con_gsk_old)
            ->select(DB::raw($sql));
        $results2 = DB::connection($con_gsk_old)
            ->select(DB::raw($sql2));
        if(count($results) > 0 && count($results2) > 0){
            foreach ($results as $upload){
                $data_upload[] = [
                    'id' => $upload->ID,
                    'user_id' => $upload->userID,
                    'file_path' => $upload->filePath,
                    'file_name' => $upload->fileName,
                    'file_size' => $upload->fileSize,
                    'created_at' => $upload->createdDate > 0 ? date("Y-m-d H:i:s", $upload->createdDate) : NULL
                ];
            }

            foreach ($results2 as $download){
                $data_download[] = [
                    'id' => $download->ID,
                    'user_id' => $download->userID,
                    'upload_backup_id' => $download->uploadBackupID,
                    'created_at' => $download->recoveredDate > 0 ? date("Y-m-d H:i:s", $download->recoveredDate) : NULL
                ];
            }
//            dd($data_user);
            DB::beginTransaction();
            try{
                UploadBackup::insert($data_upload);
                DownloadBackup::insert($data_download);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());

            }
        }
        dd('Done');
    }
}

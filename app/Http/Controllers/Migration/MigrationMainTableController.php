<?php

namespace app\Http\Controllers\Migration;

use App\Models\AuditTrail;
use App\Models\ShineAsbestosLeadAdmin;
use App\Models\LastRevision;
use App\Models\Notification;
use App\Models\UploadBackup;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class MigrationMainTableController extends Controller
{
    /**
     * Migration upload_manifest and download_manifest
     * @return string
     */
    public function index(){
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";

        $sql_audit = "SELECT * FROM audit_trail;";
        $sql_shine_lead_admin = "SELECT * FROM shineasbestosleadadmin;";
//        $sql_last_revision = "SELECT * FROM tbl_last_revision;";
        $sql_notification = "SELECT * FROM tblnotifications;";
        $sql_backup = "SELECT * FROM uploadbackup;";


        $results_audit = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_audit));
        $results_shine_lead_admin = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_shine_lead_admin));
//        $results_last_revision = DB::connection($con_gsk_old)
//            ->select(DB::raw($sql_last_revision));
        $results_notification = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_notification));

        $results_backup = DB::connection($con_gsk_old)
            ->select(DB::raw($sql_backup));
        $data_audit = [];
        $data_shine_lead_admin = [];
        $data_last_revision = [];
        $data_notification = [];
        $data_backup = [];

        if(count($results_audit) > 0 && count($results_audit) > 0){
            foreach ($results_audit as $data){
                $data_audit[] = [
                    "id" => $data->keyID,
                    "property_id" => $data->propertyKey,
                    "type" => $data->auditType,
                    "shine_reference" => 'AR'.$data->keyID,
                    "object_type" => $data->auditObjectType,
                    "object_id" => $data->auditObjectKey,
                    "object_parent_id" => $data->auditObjectParentKey,
                    "object_reference" => $data->auditObjectReference,
                    "archive_id" => $data->auditArchiveKey,
                    "action_type" => $data->auditActionType,
                    "user_id" => $data->auditUserKey,
                    "user_client_id" => $data->auditUserClientKey,
                    "user_name" => $data->auditUserName,
                    "date" => $data->auditDate,
                    "ip" => $data->auditIP,
                    "comments" => $data->auditComments,
                    "backup" => $data->auditBackup,
                    "created_at" => $data->auditDate > 0 ? date("Y-m-d H:i:s", $data->auditDate) : NULL,
                    "updated_at" => null,
                    "deleted_at" => null,
                ];
            }
            if (count($results_shine_lead_admin) > 0) {
                foreach ($results_shine_lead_admin as $data2){
                    $data_shine_lead_admin[] = [
                        'user_id' => $data2->userID,
                    ];
                }
            }
//            if (count($results_last_revision) > 0) {
//                # code...
//                foreach ($results_last_revision as $data3){
//                    $data_last_revision[] = [
//                        "id" => $data3->id,
//                        "last_revision" => $data3->last_revision,
//                        "zone_id" => $data3->zoneID,
//                        "created_at" => null,
//                        "updated_at" => null,
//                        "deleted_at" => null
//                    ];
//                }
//            }

            if(count($results_notification) > 0) {
                foreach ($results_notification as $data4){
                    $data_notification[] = [
                        "id" => $data4->keyID,
                        "user_id" => $data4->userKey,
                        "client_id" => $data4->clientKey,
                        "contractor_id" => $data4->contractorKey,
                        "project_id" => $data4->projectKey,
                        "document_id" => $data4->documentKey,
                        "type" => $data4->type,
                        "status" => $data4->status,
                        "added_date" => $data4->addedDate,
                        "checked_date" => $data4->checkedDate,
                        "checked_user_id" => $data4->checkedUserKey,
                        "created_at" => $data4->addedDate > 0 ? date("Y-m-d H:i:s", $data4->addedDate) : NULL,
                        "updated_at" => null,
                        "deleted_at" => null,
                    ];
                }

            }

            if (count($results_backup) > 0) {
                foreach ($results_backup as $data5){
                    $data_backup[] = [
                        'id' => $data5->ID,
                        'user_id' => $data5->userID,
                        'survey_id' => null,
                        'path' => $data5->filePath,
                        'file_name' => $data5->fileName,
                        'size' => $data5->fileSize,
                        "created_at" => $data5->createdDate > 0 ? date("Y-m-d H:i:s", $data5->createdDate) : NULL,
                        "updated_at" => null,
                        "deleted_at" => null,
                    ];
                }

            }

            DB::beginTransaction();
            try{
                $insert_data_audit = collect($data_audit);
                $chunks1 = $insert_data_audit->chunk(500);
                foreach ($chunks1 as $chunk1)
                {
                    AuditTrail::insert($chunk1->toArray());
                }

                $insert_data_shine_lead = collect($data_shine_lead_admin);
                $chunks2 = $insert_data_shine_lead->chunk(500);
                foreach ($chunks2 as $chunk2)
                {
                    ShineAsbestosLeadAdmin::insert($chunk2->toArray());
                }

//                $insert_last_rivision = collect($data_last_revision);
//                $chunks3 = $insert_last_rivision->chunk(500);
//                foreach ($chunks3 as $chunk3)
//                {
//                    LastRevision::insert($chunk3->toArray());
//                }

                $insert_notification = collect($data_notification);
                $chunks4 = $insert_notification->chunk(500);
                foreach ($chunks4 as $chunk4)
                {
                    Notification::insert($chunk4->toArray());
                }

                $insert_backup = collect($data_backup);
                $chunks5 = $insert_backup->chunk(500);
                foreach ($chunks5 as $chunk5)
                {
                    UploadBackup::insert($chunk5->toArray());
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

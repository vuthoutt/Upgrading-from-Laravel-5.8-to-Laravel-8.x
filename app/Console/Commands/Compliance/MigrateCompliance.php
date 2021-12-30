<?php

namespace App\Console\Commands\Compliance;

use App\Models\Role;
use App\Models\RoleUpdate;
use App\Models\ShineCompliance\Property;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// use RecursiveIteratorIterator;

class MigrateCompliance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fix_data_compliance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        ini_set('memory_limit','5120M');
        //check migrate materials (new on compliance migrated from construction type) to Other + old data
        DB::beginTransaction();
        try{
            $this->info('======> staring migrate materials (new on compliance migrated from construction type) to Other + old data');
            //todo write function migrate historical data
            //todo write function migrate Accessibility: for Area i.e Areas from Asbestos are Accessible state
            //todo Could we please have the Department Oxfordshire Count Council End Users removed?
            //todo check is lead assessment for user
            //todo trigger hazard specific location
            $this->migrateConstructionType();
            $this->info('======> staring migrate historical doc to new table');
            $this->migrateHistoricalDoc();
            $this->info('======> staring update Accessibility for Area i.e Areas from Asbestos are Accessible state');
            $this->correctAccessibilityArea();
            $this->info('======> remove  Department Oxfordshire Count Council department and update to Technical Depart, remove London Borough of Hackney Council removed from the Workstream/Programme dropdown');
//            $this->correctDepartmentUser();
            $this->info("inacc area dropdown");
//            $this->insertInaccessibleReasonForApp();
            $this->info("update assessment lead user");
            $this->updateAssessmnetLead();
            // todo migrate roles + property view/edit
            $this->migrateRoles();
            $this->correctRiskClassificationProject();
            $this->info('======> Done');
            DB::commit();
        }catch (\Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }
    }

    public function migrateConstructionType(){
        DB::insert("INSERT INTO tbl_property_construction_materials (property_id, material_id, other) SELECT property_id, 45, construction_type FROM tbl_property_survey WHERE construction_type IS NOT NULL AND construction_type != ''");
//        $properties = Property::with('propertySurvey')->get();
//        $chunks = $properties->chunk(500);
//        foreach ($chunks as $chunk)
//        {
//            foreach ($chunk as $property){
//                if(isset($property->propertySurvey->construction_type) && !empty($property->propertySurvey->construction_type)){
//                    $data_sync[$property->id] = ['material_id' => MATERIAL_OTHER, 'other' => $property->propertySurvey->construction_type];
//                    $property->constructionMaterials()->sync($data_sync);
//                }
//            }
//        }
    }

    public function migrateHistoricalDoc(){
        DB::insert("INSERT IGNORE INTO compliance_documents (id,reference,property_id,equipment_id,programme_id,parent_type,type,is_external_ms,is_reinspected,category_id,type_other,`status`,system_id,date,`name`,compliance_type,created_by,created_at,updated_at,is_identified_acm,is_inaccess_room)
                    (
                        SELECT id,reference,property_id,0,0,1,doc_type,is_external_ms,0,category,NULL,1,0,added,`name`,1,created_by,created_at,updated_at,is_identified_acm,is_inaccess_room FROM tbl_historicdocs
                    );");
        DB::insert("INSERT IGNORE INTO `compliance_document_category`(`id`, `property_id`, `name`)
                    (
                        SELECT id, property_id, category FROM tbl_historicdocs_categories
                    );");
        DB::insert("
                    INSERT IGNORE INTO `compliance_document_storage`(`id`, `object_id`, `type`, `path`, `file_name`, `mime`, `size`, `addedBy`, `addedDate`, `created_at`, `updated_at`)
                    (
                        SELECT `id`, `object_id`, 'doc_photo', `path`, `file_name`, `mime`, `size`, `addedBy`, `addedDate`, `created_at`, `updated_at` FROM tbl_shine_document_storage WHERE type = 'hd'
                    );");
    }

    public function correctDepartmentUser(){
        DB::update("UPDATE tbl_users SET department_id = 7 WHERE department_id = 8 and client_id > 1;");
        DB::delete("DELETE FROM tbl_departments_contractor WHERE id = 8;");
        DB::delete("DELETE FROM tbl_work_stream WHERE id = 0;");
    }

    public function correctAccessibilityArea(){
        DB::update("UPDATE tbl_area SET state = 1 WHERE state IS NULL;");
    }

    public function insertInaccessibleReasonForApp(){
        // run by manually
        DB::insert("INSERT INTO tbldropdown (ID,description,endpoint,multiTiered) VALUES (906,'Area Inaccessible Reason','areaInaccess',0);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2362,'Confined Space',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2363,'Denied Access',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2364,'Excessive Storage',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2365,'Excessive Waste',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2366,'No Keys',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2367,'Locked',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2368,'Height Restriction',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2369,'Restricted Access',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2370,'Room/location Occupied',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2371,'Structurally Unsafe',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2372,'Unsafe Access',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2373,'Fixed Ceiling Tiles',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2374,'Parquet Flooring',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2375,'Listed Decoration/feature',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2376,'Sealed Floor',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2377,'Damage to Decoration',906);");
        DB::insert("INSERT INTO tbldropdowndata (ID,description,dropdownID) VALUES (2378,'Other ',906);");
    }

    public function updateAssessmnetLead(){
        DB::update("UPDATE tbl_users SET assessor_lead = 1 WHERE client_id = 1;");
    }

    public function migrateRoles(){
        $roles = Role::all();
        $role_updates = RoleUpdate::all();
        $contractor_value_view = $contractor_value_edit = [];
        foreach ($roles as $role){
            foreach ($role->contractor as $contractor_id => $value){
                foreach ($value as $v){
                    if($v == CONTRACTOR_DETAILS){
                        $contractor_value_view[$role->id][$contractor_id][] = JR_CONTRACTOR_DETAILS;
                    } else if($v == CONTRACTOR_POLICY_DOCUMENTS){
                        $contractor_value_view[$role->id][$contractor_id][] = JR_CONTRACTOR_POLICY_DOCUMENTS;
                    } else if($v == CONTRACTOR_DEPARTMENTS){
                        $contractor_value_view[$role->id][$contractor_id][] = JR_CONTRACTOR_DEPARTMENTS;
                    } else if($v == CONTRACTOR_TRAINING_RECORDS){
                        $contractor_value_view[$role->id][$contractor_id][] = JR_CONTRACTOR_TRAINING_RECORDS;
                    }
                }
            }
        }

        foreach ($role_updates as $role){
            foreach ($role->contractor as $contractor_id => $value){
                foreach ($value as $v){
                    if($v == CONTRACTOR_DETAILS){
                        $contractor_value_edit[$role->id][$contractor_id][] = JR_CONTRACTOR_DETAILS;
                    } else if($v == CONTRACTOR_POLICY_DOCUMENTS){
                        $contractor_value_edit[$role->id][$contractor_id][] = JR_CONTRACTOR_POLICY_DOCUMENTS;
                    } else if($v == CONTRACTOR_DEPARTMENTS){
                        $contractor_value_edit[$role->id][$contractor_id][] = JR_CONTRACTOR_DEPARTMENTS;
                    } else if($v == CONTRACTOR_TRAINING_RECORDS){
                        $contractor_value_edit[$role->id][$contractor_id][] = JR_CONTRACTOR_TRAINING_RECORDS;
                    }
                }
            }
        }
        $view1 = isset($contractor_value_view[1]) ? json_encode($contractor_value_view[1]) : NULL;
        $view2 = isset($contractor_value_view[2]) ? json_encode($contractor_value_view[2]) : NULL;
        $view3 = isset($contractor_value_view[3]) ? json_encode($contractor_value_view[3]) : NULL;
        $view4 = isset($contractor_value_view[4]) ? json_encode($contractor_value_view[4]) : NULL;
        $view5 = isset($contractor_value_view[5]) ? json_encode($contractor_value_view[5]) : NULL;

        $edit1 = isset($contractor_value_edit[1]) ? json_encode($contractor_value_edit[1]) : NULL;
        $edit2 = isset($contractor_value_edit[2]) ? json_encode($contractor_value_edit[2]) : NULL;
        $edit3 = isset($contractor_value_edit[3]) ? json_encode($contractor_value_edit[3]) : NULL;
        $edit4 = isset($contractor_value_edit[4]) ? json_encode($contractor_value_edit[4]) : NULL;
        $edit5 = isset($contractor_value_edit[5]) ? json_encode($contractor_value_edit[5]) : NULL;
        //for dev, clone tables before truncate
        DB::table("cp_job_roles")->truncate();
        DB::table("cp_job_roles_view_value")->truncate();
        DB::table("cp_job_roles_edit_value")->truncate();
        DB::table("cp_job_role_edit_property")->truncate();
        DB::table("cp_job_role_view_property")->truncate();
        // job roles
        DB::insert("INSERT INTO `cp_job_roles` VALUES (1, 'Super User', '2021-07-08 12:05:08', '2021-07-08 12:05:08');");
        DB::insert("INSERT INTO `cp_job_roles` VALUES (2, 'Management', '2021-07-08 12:05:08', '2021-07-08 12:05:08');");
        DB::insert("INSERT INTO `cp_job_roles` VALUES (3, 'Basic User', '2021-07-08 12:05:08', '2021-07-08 12:05:08');");
        DB::insert("INSERT INTO `cp_job_roles` VALUES (4, 'Administrator', '2021-07-08 12:05:08', '2021-07-08 12:05:08');");
        DB::insert("INSERT INTO `cp_job_roles` VALUES (5, 'Site Operative', '2021-07-08 12:05:08', '2021-07-08 12:05:08');");
        //job role edit
        //db insert will not string escape  so need to copy manually
        DB::insert("INSERT INTO `cp_job_roles_edit_value` VALUES (1, 1, '{\"asbestos\":\"0\",\"general\":\"0\"}', '2021-05-04 13:32:11', '2021-06-23 05:16:59', '{\"asbestos\":\"[37,38,39,40,41,42,43,45]\",\"general\":\"[3,84,6,7,8,9,14,80,81,82,83,17,20,32,33,35,85,88,89,90]\"}', '{\"asbestos\":\"{\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"group\\\":[],\\\"category\\\":[],\\\"all_client\\\":0}\",\"general\":\"{\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"group\\\":[],\\\"category\\\":[6,7,8,16,9,10,11,12,13,14],\\\"all_client\\\":0,\\\"all_organisation\\\":1}\"}', NULL, '{\"1\":[2,3,4,5,6,7,8,9,10,11,13,19,23,26]}', '$edit1', '1');");
        DB::insert("INSERT INTO `cp_job_roles_edit_value` VALUES (2, 2, '{\"general\":\"0\",\"asbestos\":\"0\"}', '2021-05-04 13:32:11', '2021-06-23 05:16:30', '{\"general\":\"[85,88]\",\"asbestos\":\"[]\"}', '{\"general\":\"{\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"group\\\":[],\\\"category\\\":[6,7,8,16,9,10,11,12,13,14],\\\"all_client\\\":0}\",\"asbestos\":\"{\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"group\\\":[],\\\"category\\\":[],\\\"all_client\\\":0}\"}', NULL, NULL, '$edit2', NULL);");
        DB::insert("INSERT INTO `cp_job_roles_edit_value` VALUES (3, 3, '{\"general\":\"0\",\"asbestos\":\"0\"}', '2021-05-04 13:32:11', '2021-06-23 05:15:39', '{\"general\":\"[85,88]\",\"asbestos\":\"[]\"}', '{\"general\":\"{\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"group\\\":[],\\\"category\\\":[6,7,8,16,9,10,11,12,13,14],\\\"all_client\\\":0}\",\"asbestos\":\"{\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"group\\\":[],\\\"category\\\":[],\\\"all_client\\\":0}\"}', NULL, NULL, '$edit3', NULL);");
        DB::insert("INSERT INTO `cp_job_roles_edit_value` VALUES (4, 4, '{\"general\":\"0\",\"asbestos\":\"0\"}', '2021-05-04 13:32:11', '2021-06-23 05:12:54', '{\"general\":\"[80,81,82,83,85,88]\",\"asbestos\":\"[40,41,42,43]\"}', '{\"general\":\"{\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"group\\\":[],\\\"category\\\":[6,7,8,16,9,10,11,12,13,14],\\\"all_client\\\":0,\\\"all_organisation\\\":1}\",\"asbestos\":\"{\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"group\\\":[],\\\"category\\\":[],\\\"all_client\\\":0}\"}', NULL, '{\"1\":[2,3,4,5,6,7,8,9,10,11,13,19,23,26]}', '$edit4', '1');");
        DB::insert("INSERT INTO `cp_job_roles_edit_value` VALUES (5, 5, '{\"general\":\"0\",\"asbestos\":\"0\"}', '2021-05-04 13:32:11', '2021-06-23 05:19:57', '{\"general\":\"[88]\",\"asbestos\":\"[]\"}', '{\"general\":\"{\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"group\\\":[],\\\"category\\\":[6,7,8,16,9,10,11,12,13,14],\\\"all_client\\\":0}\",\"asbestos\":\"{\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"group\\\":[],\\\"category\\\":[],\\\"all_client\\\":0}\"}', NULL, NULL, '$edit5', NULL);");
//        //job role value
        DB::insert("INSERT INTO `cp_job_roles_view_value` VALUES (1, 1, '{\"asbestos\":\"0\",\"general\":\"0\"}', 0, 0, '2021-05-15 03:26:18', '2021-06-23 04:20:08', '{\"asbestos\":\"[40,41,42,43,44,45,46,47,48,49,51,52,53,54,55,56]\",\"general\":\"[2,6,131,9,10,11,12,15,18,126,127,128,129,21,27,29,123,124,132,30,34,125,133,134,36,119]\"}', '{\"asbestos\":\"{\\\"email_notification\\\":[],\\\"reporting\\\":[3,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,24,25,26,27,30,31,32,33,34,35,39,40],\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"work_flow\\\":[],\\\"work_request\\\":[],\\\"category\\\":[],\\\"group\\\":[],\\\"all_client\\\":0}\",\"general\":\"{\\\"email_notification\\\":[],\\\"reporting\\\":[3],\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"work_flow\\\":[],\\\"work_request\\\":[],\\\"category\\\":[6,7,8,16,9,10,11,12,13,14],\\\"group\\\":[],\\\"all_client\\\":0,\\\"all_organisation\\\":1}\"}', NULL, '{\"1\":[2,3,4,5,6,7,8,9,10,11,13,19,23,26,27]}', NULL, '$view1');");
        DB::insert("INSERT INTO `cp_job_roles_view_value` VALUES (2, 2, '{\"general\":\"0\",\"asbestos\":\"0\"}', 0, 0, '2021-05-15 03:26:18', '2021-06-23 05:01:52', '{\"general\":\"[2,6,131,9,10,11,12,15,18,126,127,128,129,21,27,29,123,124,132,34,125,133,134]\",\"asbestos\":\"[40,41,42,43,44,45,46,47,48,49,51,52,53,54,55,56]\"}', '{\"general\":\"{\\\"email_notification\\\":[],\\\"reporting\\\":[3],\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"work_flow\\\":[],\\\"work_request\\\":[],\\\"category\\\":[8,16,9,10,11,12,13],\\\"group\\\":[],\\\"all_client\\\":0,\\\"all_organisation\\\":1}\",\"asbestos\":\"{\\\"email_notification\\\":[],\\\"reporting\\\":[9,10,17],\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"work_flow\\\":[],\\\"work_request\\\":[],\\\"category\\\":[],\\\"group\\\":[],\\\"all_client\\\":0}\"}', NULL, '{\"1\":[2,3,4,5,6,7,8,9,10,11,13,19,23,26,27]}', NULL, '$view2');");
        DB::insert("INSERT INTO `cp_job_roles_view_value` VALUES (3, 3, '{\"general\":\"0\",\"asbestos\":\"0\"}', 0, 0, '2021-05-15 03:26:18', '2021-06-23 05:15:33', '{\"general\":\"[2,6,131,9,10,11,12,15,18,126,127,128,129,132,125,134]\",\"asbestos\":\"[40,41,42,43,44,45,46,47,48,49,51,52,53,54,55,56]\"}', '{\"general\":\"{\\\"email_notification\\\":[],\\\"reporting\\\":[3],\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"work_flow\\\":[],\\\"work_request\\\":[],\\\"category\\\":[7],\\\"group\\\":[],\\\"all_client\\\":0,\\\"all_organisation\\\":1}\",\"asbestos\":\"{\\\"email_notification\\\":[],\\\"reporting\\\":[8,9,10,17],\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"work_flow\\\":[],\\\"work_request\\\":[],\\\"category\\\":[],\\\"group\\\":[],\\\"all_client\\\":0}\"}', NULL, '{\"1\":[2,3,4,5,6,7,8,9,10,11,13,19,23,26]}', NULL, '$view3');");
        DB::insert("INSERT INTO `cp_job_roles_view_value` VALUES (4, 4, '{\"general\":\"0\",\"asbestos\":\"0\"}', 0, 0, '2021-05-15 03:26:18', '2021-06-23 05:09:52', '{\"general\":\"[2,6,131,9,10,11,12,15,18,126,127,128,129,21,27,29,123,124,132,34,125,133,134]\",\"asbestos\":\"[40,41,42,43,44,45,46,47,48,49]\"}', '{\"general\":\"{\\\"email_notification\\\":[],\\\"reporting\\\":[3],\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"work_flow\\\":[],\\\"work_request\\\":[],\\\"category\\\":[8,16,9],\\\"group\\\":[],\\\"all_client\\\":0,\\\"all_organisation\\\":1}\",\"asbestos\":\"{\\\"email_notification\\\":[],\\\"reporting\\\":[8,9,10],\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"work_flow\\\":[],\\\"work_request\\\":[],\\\"category\\\":[],\\\"group\\\":[],\\\"all_client\\\":0}\"}', NULL, '{\"1\":[2,3,4,5,6,7,8,9,10,11,13,19,23,26]}', NULL, '$view4');");
        DB::insert("INSERT INTO `cp_job_roles_view_value` VALUES (5, 5, '{\"general\":\"0\",\"asbestos\":\"0\"}', 1, 0, '2021-05-15 03:26:18', '2021-06-23 05:26:33', '{\"general\":\"[6,131,9,10,11,15,125]\",\"asbestos\":\"[51,52]\"}', '{\"general\":\"{\\\"email_notification\\\":[],\\\"reporting\\\":[3],\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"work_flow\\\":[],\\\"work_request\\\":[],\\\"category\\\":[],\\\"group\\\":[],\\\"all_client\\\":0}\",\"asbestos\":\"{\\\"email_notification\\\":[],\\\"reporting\\\":[10],\\\"organisation\\\":[],\\\"client\\\":[],\\\"contractor\\\":[],\\\"work_flow\\\":[],\\\"work_request\\\":[],\\\"category\\\":[],\\\"group\\\":[],\\\"all_client\\\":0}\"}', NULL, '{\"1\":[2,3,4,5,6,7,8,9,10,11,13,19,23,26]}', NULL, '$view5');");
        // migrate properties view/edit
        //todo check role_view_property
        $list_job_role_view = DB::select("SELECT property, id FROM tbl_role;");
        $list_job_role_edit = DB::select("SELECT property, id FROM tbl_role_update;");
        $insert_data_view = $insert_data_update = [];
//        dd($list_job_role_view, $list_job_role_edit);
        if(count($list_job_role_view)){
            foreach ($list_job_role_view as $job){
                $list_property = explode(",", $job->property);
                foreach ($list_property as $property_id){
                    if($property_id > 0){
                        $insert_data_view[] = [
                            'role_id' => $job->id,
                            'property_id' => $property_id
                        ];
                    }
                }
            }
        }
        if(count($list_job_role_edit)){
            foreach ($list_job_role_edit as $job){
                $list_property = explode(",", $job->property);
                foreach ($list_property as $property_id){
                    if($property_id > 0){
                        $insert_data_update[] = [
                            'role_id' => $job->id,
                            'property_id' => $property_id
                        ];
                    }
                }
            }
        }
        $insert_data = collect($insert_data_view);
        $chunks = $insert_data->chunk(500);
        foreach ($chunks as $chunk){
            \DB::table('cp_job_role_view_property')->insert($chunk->toArray());
        }

        $insert_data = collect($insert_data_update);
        $chunks = $insert_data->chunk(500);
        foreach ($chunks as $chunk){
            \DB::table('cp_job_role_edit_property')->insert($chunk->toArray());
        }
//        dd($insert_data_view, $insert_data_update);
//        DB::select("INSERT INTO role_view_property (role_view_property.role_id, role_view_property.property_id)
//                            SELECT ID,SUBSTRING_INDEX(SUBSTRING_INDEX(t.property, ',', n.n), ',', -1) value
//                              FROM tbl_role t CROSS JOIN
//                              (
//                               SELECT a.N + b.N * 10 + 1 n
//                                 FROM
//                                (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
//                               ,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
//                                ORDER BY n
//                               ) n
//                             WHERE n.n <= 1 + (LENGTH(t.property) - LENGTH(REPLACE(t.property, ',', '')))
//                             ORDER BY ID,value");
    }

    public function correctRiskClassificationProject(){
        DB::update("UPDATE tbl_project SET risk_classification_id = 1;");
    }
}

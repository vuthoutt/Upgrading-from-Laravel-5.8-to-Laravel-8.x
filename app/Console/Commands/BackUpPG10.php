<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Zone;
use App\Models\Property;
use App\Models\Survey;
use App\Models\Project;
use App\Models\Item;
use App\Models\ShineDocumentStorage;
use App\Models\Document;
use App\Models\SitePlanDocument;
use App\Models\HistoricDoc;
use ZipArchive;
// use RecursiveIteratorIterator;

class BackUpPG10 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:BackUpPG10';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create backup zip file for property group';
    public $array_files = [];
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
    public function handle()
    {
        //missing duplicate data
        $zone_id = [4,10,19,20,23];

        $zones = Zone::whereIn('id',$zone_id)->get();

        try {
             foreach ($zones as $zone) {
                $this->info("========>  Starting backup $zone->zone_name\n ");
                // \DB::beginTransaction();
                // remove exist directory
                if (\Storage::exists('back_up/'.$zone->zone_name)) {
                    $this->info("remove old folder $zone->zone_name\n ");
                    \Storage::deleteDirectory('back_up/'.$zone->zone_name);
                }

                $this->moveProjectDocument($zone->id);
                $this->moveSurveyDocument($zone->id);
                $this->moveHistoricalDocument($zone->id);
                // if (\File::exists('back_up/'.$zone->zone_name)) {
                //     $this->zipFile($zone->zone_name);
                // } else {
                //     $this->info("Not exist folder $zone->zone_name\n ");
                // }
                // \DB::commit();
            }
        } catch (\Exception $e) {
            // \DB::rollback();
            dd($e);
        }
    }

    public function copyAndRename($old_path, $new_path) {
        $file_old = public_path() . '/'. $old_path;
        $file_new = public_path() . '/'. $new_path;

        $this->info("Check path : $file_old\n ");
        if ($old_path) {
            if (file_exists($file_old)) {
                if (\Storage::exists($new_path)) {
                    $this->info("??? New path : $new_path is already exist\n ");
                    //rename new path
                    // \Storage::delete($new_path);
                    // $this->info("??? Delete path : $new_path \n ");
                    $frequent = array_count_values($this->array_files);
                    $number = $frequent[$new_path];

                    $file_name = pathinfo($new_path, PATHINFO_FILENAME);

                    $new_file_name = $file_name . '('.$number .')';

                    $path_replace = str_replace($file_name, $new_file_name, $new_path);

                    \Storage::copy($old_path, $path_replace);
                    $this->info("??? Replace New path : $path_replace \n ");
                } else {
                    \Storage::copy($old_path, $new_path);
                }
                $this->array_files[] = $new_path;
                $this->info("==>> Copy success $old_path <==> $new_path\n ");
            } else {
                $this->info("!! Path does not exist : $old_path \n ");
            }
        }
    }

    public function copyAndRenameSurveyPDF($old_path, $new_path) {
        //remove old path hard folder
        $old_path = str_replace("home/www/lrvl-dev.shinedev.co.uk/storage/app/","", $old_path);
        $file_old = public_path() . '/'. $old_path;
        $this->info("Check path : $old_path\n ");
        if ($old_path) {
            if (file_exists($file_old)) {
                if (\Storage::exists($new_path)) {
                    $this->info("??? New path : $new_path is already exist\n ");
                    \Storage::delete($new_path);
                    $this->info("??? Delete path : $new_path \n ");
                }
                \Storage::copy($old_path, $new_path);
                // $this->array_files[] = storage_path().'/app/'.$new_path;
                $this->info("==>> Copy success $old_path <==> $new_path\n ");
            } else {
                $this->info("!! Path does not exist : $old_path \n ");
            }
        }
    }


    public function getProjectFolder($project) {
        $folder = 'back_up/'.$project->zone_name. '/' . $project->property_name . '/Asbestos_'.$project->zone_name. '_'
                    . $project->property_name . '_' . $project->title . '_' . $project->start_date;
        if(!\File::exists($folder)) {
            // path does not exist
            mkdir($folder, 0755 , true);
        }
        $new_path = $folder.'/' .$project->file_name;
        return $new_path;
    }

    public function getHistoricalDocFolder($historical) {
        $folder = 'back_up/'.$historical->zone_name. '/' . $historical->property_name . '/Asbestos_'.$historical->zone_name. '_' . $historical->property_name . '_Historical Data' ;
        if(!\File::exists($folder)) {
            // path does not exist
            mkdir($folder, 0755 , true);
        }
        $new_path = $folder.'/' .$historical->file_name;
        return $new_path;
    }

    public function moveProjectDocument($groupID) {
        $sql =  "SELECT `z`.zone_name,
                        p.name as property_name,
                        pj.title as title,
                        FROM_UNIXTIME(pj.date, \"%d_%m_%Y\") as start_date,
                        sds.path,
                        sds.file_name
                    FROM tbl_documents d
                    LEFT JOIN tbl_project pj ON `d`.`project_id` = `pj`.`id`
                    LEFT JOIN tbl_property p ON `pj`.`property_id` = `p`.`id`
                    LEFT JOIN tbl_zones z ON `p`.`zone_id` = `z`.`id`
                    LEFT JOIN tbl_shine_document_storage sds on object_id = d.id
                    WHERE `z`.`id` = $groupID and sds.type = 'documents' and pj.created_at < '2020-01-01 00:00:00'";
        $projects = \DB::select($sql);
        $this->info("=============> Starting clone project document \n");
        if (count($projects)) {
            foreach ($projects as $project) {
                $new_path = $this->getProjectFolder($project);
                $old_path = $project->path;
                $this->copyAndRename($old_path, $new_path);
            }
        }
        $this->info("=============> End of project document \n");
    }

    public function moveHistoricalDocument($groupID) {
        $sql =  "SELECT `z`.zone_name,
                        p.name as property_name,
                        sds.path,
                        sds.file_name
                    FROM tbl_historicdocs d
                    LEFT JOIN tbl_property p ON `d`.`property_id` = `p`.`id`
                    LEFT JOIN tbl_zones z ON `p`.`zone_id` = `z`.`id`
                    LEFT JOIN tbl_shine_document_storage sds on sds.object_id = d.id
                    WHERE `z`.`id` = $groupID  and sds.type = 'hd'";
        $HistoricDocs = \DB::select($sql);
        $this->info("=============> Starting clone historical document \n");
        if (count($HistoricDocs)) {
            foreach ($HistoricDocs as $doc) {
                $new_path = $this->getHistoricalDocFolder($doc);
                $old_path = $doc->path;
                $this->copyAndRename($old_path, $new_path);
            }
        }
        $this->info("=============> End of project document \n");
    }

    public function moveSurveyDocument($groupID) {
        // sample certificate
        $sql_sample =  "SELECT `z`.zone_name,
                        p.name as property_name,
                        st.description as title,
                        FROM_UNIXTIME(sd.started_date, \"%d_%m_%Y\") as start_date,
                        sds.path,
                        sds.file_name
                    FROM tbl_sample_certificates sc
                    LEFT JOIN tbl_survey s ON `sc`.`survey_id` = `s`.`id`
                    LEFT JOIN tbl_survey_date sd on s.id = sd.survey_id
                    LEFT JOIN tbl_property p ON `s`.`property_id` = `p`.`id`
                    LEFT JOIN tbl_zones z ON `p`.`zone_id` = `z`.`id`
                    LEFT JOIN tbl_survey_type st ON `st`.`id` = `s`.`survey_type`
                    LEFT JOIN tbl_shine_document_storage sds on sds.object_id = sc.id
                    WHERE `z`.`id` = $groupID and sds.type = 'sct' and s.created_at < '2020-01-01 00:00:00' ";
        $samples = \DB::select($sql_sample);
        $this->info("=============> Starting clone sample Certificate \n");
        if (count($samples)) {
            foreach ($samples as $sample) {
                $new_path = $this->getProjectFolder($sample);
                $old_path = $sample->path;
                $this->copyAndRename($old_path, $new_path);
            }
        }
        // plan document
        $sql_plan =  "SELECT `z`.zone_name,
                            p.name as property_name,
                            st.description as title,
                            FROM_UNIXTIME(sd.started_date, \"%d_%m_%Y\") as start_date,
                            sds.path,
                            sds.file_name
                        FROM tbl_siteplan_documents sc
                        LEFT JOIN tbl_survey s ON `sc`.`survey_id` = `s`.`id`
                        LEFT JOIN tbl_survey_date sd on s.id = sd.survey_id
                        LEFT JOIN tbl_property p ON `s`.`property_id` = `p`.`id`
                        LEFT JOIN tbl_zones z ON `p`.`zone_id` = `z`.`id`
                        LEFT JOIN tbl_survey_type st ON `st`.`id` = `s`.`survey_type`
                        LEFT JOIN tbl_shine_document_storage sds on sds.object_id = sc.id
                        WHERE `z`.`id` = $groupID and sds.type = 'p' and s.created_at < '2020-01-01 00:00:00'";
        $plans = \DB::select($sql_plan);
        $this->info("=============> Starting clone plan document \n");
        if (count($plans)) {
            foreach ($plans as $plan) {
                $new_path = $this->getProjectFolder($plan);
                $old_path = $plan->path;
                $this->copyAndRename($old_path, $new_path);
            }
        }
        //survey pdf
        $sql_survey =  "SELECT `z`.zone_name,
                            p.name as property_name,
                            st.description as title,
                            FROM_UNIXTIME(sd.started_date, \"%d_%m_%Y\") as start_date,
                            ps1.path,
                            ps1.filename as file_name
                        FROM tbl_survey s
                        LEFT JOIN tbl_survey_date sd on s.id = sd.survey_id
                        LEFT JOIN tbl_property p ON `s`.`property_id` = `p`.`id`
                        LEFT JOIN tbl_zones z ON `p`.`zone_id` = `z`.`id`
                        LEFT JOIN tbl_survey_type st ON `st`.`id` = `s`.`survey_type`
                        LEFT JOIN (
                            SELECT MAX(id) as max_id, survey_id from tbl_published_surveys group by survey_id
                        ) as sds on sds.survey_id = s.id
                        LEFT JOIN tbl_published_surveys ps1 ON sds.max_id = ps1.id
                        WHERE `z`.`id` = $groupID and s.created_at < '2020-01-01 00:00:00'";
        $surveys = \DB::select($sql_survey);
        $this->info("=============> Starting clone survey pdf \n");
        if (count($surveys)) {
            foreach ($surveys as $survey) {
                $new_path = $this->getProjectFolder($survey);
                $old_path = $survey->path;
                $this->copyAndRenameSurveyPDF($old_path, $new_path);
            }
        }
    }

    //zip a file
    public function zipFile($name){
        $public_dir = storage_path();
        $path = realpath(__DIR__);
        $directory = storage_path().'/app/back_up/'.$name;
        $this->info("start zip  $name\n ");
        $zip_destination = $public_dir. '/app/zip_backup/' . $name. '.zip';
        if(!\File::exists($public_dir. '/app/zip_backup/')) {
            // path does not exist
            mkdir($public_dir. '/app/zip_backup/', 0755 , true);
        }
        $zip = new ZipArchive();
        $this->info("start zip  $zip_destination \n ");
        $zip->open($zip_destination, ZipArchive::CREATE);

        if (is_dir($directory)) {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));
        $this->info("add file to folder zip \n ");
            foreach ($files as $name => $file)
            {
                if ($file->isDir()) {
                    echo $name . "\n";
                    flush();
                    continue;
                }
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($path));
                $zip->addFile($filePath, $relativePath);
            }
            # code...
        } else {
            $this->info("Not exist folder $name\n ");
        }


        $zip->close();
        return 'zip done';
    }

}

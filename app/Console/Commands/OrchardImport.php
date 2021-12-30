<?php

namespace App\Console\Commands;
use App\Models\LogOrchard;
use App\Models\Orchard;
use App\Models\Property;
use App\Models\Zone;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrchardImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orchard:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $import_file = '';
        $folder_import_file = 'data/documents/orchard/import';
        $time = time();
        // list all filenames in given path
        $allFiles = Storage::files($folder_import_file);
        // filter the ones that match the filename.*
        $matchingFiles = preg_grep('/^.*\.(txt|TXT)/', $allFiles);
        $lasttime = 0;
        foreach ($matchingFiles as $txt_file) {
            if(Storage::exists($txt_file)){
//                var_dump($txt_file, Storage::lastModified($txt_file), $lasttime);
                if(Storage::lastModified($txt_file) > $lasttime){
                    $import_file = $txt_file;
                }
                $lasttime = Storage::lastModified($txt_file);
            }
        }
        if($import_file){
            $file_info = pathinfo($import_file);
            $file_name = $file_info['filename'];
            $file_size = filesize(storage_path()."/app/".$import_file);
            $now = time();
            $date_time = date("Y-m-d H:i:s");
            $message_log = 'Start the progress on '.$date_time.' -- ';
//            $data = Storage::disk('local')->get($import_file);
            $data = file(storage_path()."/app/".$import_file);
            $created_at = Carbon::now();
            if($file_size > 0 && $data){
                try{
                    DB::beginTransaction();
                    $arr_ref_ids = [];
                    $new_data = [];
                    //define order of each data in txt file
                    // 2,3,10,16,18
                    $key_property_ref = 0;
                    $key_property_class_code = 1;
                    $key_property_block = 2;
                    $key_property_name = 3;
                    $key_property_house_number = 4;
                    $key_property_address1 = 5;//suffix
                    $key_property_address2 = 6;//street name
                    $key_property_address3 = 7;//location 1
                    $key_property_address4 = 8;//location 2
                    $key_property_post_code = 9;
                    $key_property_responsibility = 10;
                    $key_property_build_date = 11;
                    $key_property_tenure_type_code = 12;;
                    $key_property_tenure_type_description = 13;// should save tenure description now
                    $key_property_service_area_code = 14;
                    $key_property_service_area_description = 15;
                    $key_property_right_buy_code = 16;
                    $key_property_right_buy_description = 17;
                    $key_property_user_code = 18;//Property User Code (Exclude from Shine at the moment but include in extract)
                    $key_property_void = 19;
                    $key_orchard_display_address = 20;

                    foreach ($data as $val){
                        $row_explode = explode("|", $val);
                        $key = trim($row_explode[$key_property_ref], '"');
                        $arr_ref_ids[] = $key;
                        $new_data[$key] = $row_explode;
                    }

                    //property reference that only is on Orchard save as XXX not 000XXX
                    $arr_ref_ids =  array_unique($arr_ref_ids);
                    $arr_ref_ids = array_filter($arr_ref_ids, function($v){
                        return $v !== false && is_numeric($v);
                    });
                    $arr_orchard_only = $arr_ref_ids;
                    //        $list_zone_ids = '10, 11, 14, 15, 18, 19, 22, 23';
                    $zone_ids = Zone::where('zone_name','like','%Domestic%')->orWhere('zone_name','like','%Communal%')->pluck('id')->toArray();
                    $properties = Property::whereIn('zone_id', $zone_ids)
                        ->where('decommissioned', 0)
                        ->where('reference','!=','')
                        ->whereNotNull('reference')
                        ->select('id', 'property_reference')
                        ->get();
                    $shine_properties = $arr_common = [];
                    $shine_compare = $shine_compare_responsibility = [];
                    if(count($properties)){
                        foreach ($properties as $p){
                            if(isset($p->id) && $p->id > 0 && !empty($p->property_reference)){
                                $str = ltrim($p->property_reference, "0");
                                //for insert later
                                $shine_properties[$p->id] = ['ref_old'=>$p->property_reference, 'ref_new' => $str];
                                //for compare with orchard
                                if(isset($str)){
                                    $shine_compare[] = $str;
                                }
                            }
                        }
                    }
                    $shine_compare = array_unique($shine_compare);
                    $arr_shine_only = $shine_compare;
                    $arr_common = array_intersect($shine_compare, $arr_ref_ids);

                    // Search for the array key and unset
                    $query_common_value = $query_shine = $query_orchard = "";
                    $arr_shine_only = array_diff($arr_shine_only, $arr_common);
                    $arr_orchard_only = array_diff($arr_orchard_only, $arr_common);

                    //1: Shine only, 2: Orchard only, 3: Common
                    //Insert common value, status = 3
                    $data_shine = $data_orchard = $data_common = [];
                    if(count($shine_properties) && (count($arr_shine_only) || count($arr_common))){
                        foreach ($shine_properties as $pro_id => $value){
                            if(in_array($value['ref_new'], $arr_shine_only)){
                                $data_shine[] = [
                                    'property_reference_id' => $value['ref_new'],
                                    'property_id' => $pro_id,
                                    'status' => 1,
                                    'date' => $now,
                                    'created_at' => $created_at

                                ];
                            }

                            if(in_array($value['ref_new'], $arr_common)){
                                $data_common[] = [
                                    'property_reference_id' => $value['ref_new'] ,
                                    'property_class_code' => isset($new_data[$value['ref_new']][$key_property_class_code]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_class_code], '"')) : '' ,
                                    'property_address1' => isset($new_data[$value['ref_new']][$key_property_address1]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_address1], '"')) : '' ,
                                    'property_address2' => isset($new_data[$value['ref_new']][$key_property_address2]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_address2], '"')) : '' ,
                                    'property_address3' => isset($new_data[$value['ref_new']][$key_property_address3]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_address3], '"')) : '' ,
                                    'property_address4' => isset($new_data[$value['ref_new']][$key_property_address4]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_address4], '"')) : '' ,
                                    'property_post_code' => isset($new_data[$value['ref_new']][$key_property_post_code]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_post_code], '"')) : '' ,
                                    'property_responsibility' => isset($new_data[$value['ref_new']][$key_property_responsibility]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_responsibility], '"')) : '' ,
                                    'property_tenure_type_code' => isset($new_data[$value['ref_new']][$key_property_tenure_type_code]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_tenure_type_code], '"')) : '' ,
                                    'property_service_area_code' => isset($new_data[$value['ref_new']][$key_property_service_area_code]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_service_area_code], '"')) : '' ,
                                    'property_service_area_description' => isset($new_data[$value['ref_new']][$key_property_service_area_description]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_service_area_description], '"')) : '' ,
                                    'property_tenure_type_description' => isset($new_data[$value['ref_new']][$key_property_tenure_type_description]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_tenure_type_description], '"')) : '' ,
                                    'property_right_buy_code' => isset($new_data[$value['ref_new']][$key_property_right_buy_code]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_right_buy_code], '"')) : '' ,
                                    'property_user_code' => isset($new_data[$value['ref_new']][$key_property_user_code]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_user_code], '"')) : '' ,
                                    'property_block' => isset($new_data[$value['ref_new']][$key_property_block]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_block], '"')) : '' ,
                                    'property_name' => isset($new_data[$value['ref_new']][$key_property_name]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_name], '"')) : '' ,
                                    'property_house_number' => isset($new_data[$value['ref_new']][$key_property_house_number]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_house_number], '"')) : '' ,
                                    'property_build_date' => isset($new_data[$value['ref_new']][$key_property_build_date]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_build_date], '"')) : '' ,
                                    'property_right_buy_description' => isset($new_data[$value['ref_new']][$key_property_right_buy_description]) ? addslashes(trim($new_data[$value['ref_new']][$key_property_right_buy_description], '"')) : '' ,
                                    'property_void' => isset($new_data[$value['ref_new']][$key_property_void]) ? addslashes(trim(trim(preg_replace('/\s+/', ' ', $new_data[$value['ref_new']][$key_property_void])), '"')) : '',
                                    'orchard_display_address' => isset($new_data[$value['ref_new']][$key_orchard_display_address]) ? addslashes(trim(trim(preg_replace('/\s+/', ' ', $new_data[$value['ref_new']][$key_orchard_display_address])), '"')) : '',
                                    'date' => $now,
                                    'status' => 3,
                                    'property_id' => $pro_id,
                                    'created_at' => $created_at
                                ];
                            }
                        }
                    }
                    if(count($arr_orchard_only)){
                        $data_orchard = [];
                        foreach ($arr_orchard_only as $val){
                            //save orchard info base on refenrence
                            $data_orchard[] = [
                                'property_reference_id' => isset($new_data[$val][$key_property_ref]) ? addslashes(trim($new_data[$val][$key_property_ref], '"')) : '' ,
                                'property_class_code' => isset($new_data[$val][$key_property_class_code]) ? addslashes(trim($new_data[$val][$key_property_class_code], '"')) : '' ,
                                'property_address1' => isset($new_data[$val][$key_property_address1]) ? addslashes(trim($new_data[$val][$key_property_address1], '"')) : '' ,
                                'property_address2' => isset($new_data[$val][$key_property_address2]) ? addslashes(trim($new_data[$val][$key_property_address2], '"')) : '' ,
                                'property_address3' => isset($new_data[$val][$key_property_address3]) ? addslashes(trim($new_data[$val][$key_property_address3], '"')) : '' ,
                                'property_address4' => isset($new_data[$val][$key_property_address4]) ? addslashes(trim($new_data[$val][$key_property_address4], '"')) : '' ,
                                'property_post_code' => isset($new_data[$val][$key_property_post_code]) ? addslashes(trim($new_data[$val][$key_property_post_code], '"')) : '' ,
                                'property_responsibility' => isset($new_data[$val][$key_property_responsibility]) ? addslashes(trim($new_data[$val][$key_property_responsibility], '"')) : '' ,
                                'property_tenure_type_code' => isset($new_data[$val][$key_property_tenure_type_code]) ? addslashes(trim($new_data[$val][$key_property_tenure_type_code], '"')) : '' ,
                                'property_service_area_code' => isset($new_data[$val][$key_property_service_area_code]) ? addslashes(trim($new_data[$val][$key_property_service_area_code], '"')) : '' ,
                                'property_service_area_description' => isset($new_data[$val][$key_property_service_area_description]) ? addslashes(trim($new_data[$val][$key_property_service_area_description], '"')) : '' ,
                                'property_tenure_type_description' => isset($new_data[$val][$key_property_tenure_type_description]) ? addslashes(trim($new_data[$val][$key_property_tenure_type_description], '"')) : '' ,
                                'property_right_buy_code' => isset($new_data[$val][$key_property_right_buy_code]) ? addslashes(trim($new_data[$val][$key_property_right_buy_code], '"')) : '' ,
                                'property_user_code' => isset($new_data[$val][$key_property_user_code]) ? addslashes(trim($new_data[$val][$key_property_user_code], '"')) : '' ,
                                'property_block' => isset($new_data[$val][$key_property_block]) ? addslashes(trim($new_data[$val][$key_property_block], '"')) : '' ,
                                'property_name' => isset($new_data[$val][$key_property_name]) ? addslashes(trim($new_data[$val][$key_property_name], '"')) : '' ,
                                'property_house_number' => isset($new_data[$val][$key_property_house_number]) ? addslashes(trim($new_data[$val][$key_property_house_number], '"')) : '' ,
                                'property_build_date' => isset($new_data[$val][$key_property_build_date]) ? addslashes(trim($new_data[$val][$key_property_build_date], '"')) : '' ,
                                'property_right_buy_description' => isset($new_data[$val][$key_property_right_buy_description]) ? addslashes(trim($new_data[$val][$key_property_right_buy_description], '"')) : '' ,
                                'property_void' => isset($new_data[$val][$key_property_void]) ? addslashes(trim(trim(preg_replace('/\s+/', ' ', $new_data[$val][$key_property_void])), '"')) : '' ,
                                'orchard_display_address' => isset($new_data[$val][$key_orchard_display_address]) ? addslashes(trim(trim(preg_replace('/\s+/', ' ', $new_data[$val][$key_orchard_display_address])), '"')) : '' ,
                                'date' => $now,
                                'status' => 2,
                                'property_id' => NULL,
                                'created_at' => $created_at
                            ];
                        }
                    }
                    Orchard::truncate();
                    if(count($arr_common) && count($data_common) > 0){
                        $insert_data = collect($data_common);
                        $chunks = $insert_data->chunk(500);
                        foreach ($chunks as $chunk)
                        {
                            Orchard::insert($chunk->toArray());
                        }
                    }

                    if(count($arr_shine_only) && count($data_shine) > 0){
                        $insert_data = collect($data_shine);
                        $chunks = $insert_data->chunk(500);
                        foreach ($chunks as $chunk)
                        {
                            Orchard::insert($chunk->toArray());
                        }
                    }

                    if(count($arr_orchard_only) && count($data_orchard) > 0){
                        $insert_data = collect($data_orchard);
                        $chunks = $insert_data->chunk(500);
                        foreach ($chunks as $chunk)
                        {
                            Orchard::insert($chunk->toArray());
                        }
                    }
                    echo "Import Done!";
                    $message_log .= " Completed. ";
                    DB::commit();
                } catch (\Exception $ex){
                    DB::rollBack();
                    $message_log .= $ex->getMessage() . ', ';
                }
            }
            $message_log .= ' End the progress. ';
            $dataLog = [
                'description' => $message_log,
                'file_name' => $file_name,
                'size' => $file_size,
                'action' => 'import',
                'date' => $now,
            ];
            LogOrchard::create($dataLog);
//            $this->call('orchard:export');
        } else {
            $dataLog = [
                'description' => 'Fail to open the import file',
                'file_name' => '',
                'size' => 0,
                'action' => 'import',
                'date' => time(),
            ];
            LogOrchard::create($dataLog);
        }

    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\Location;
use App\Models\Area;
use App\Repositories\SurveyRepository;
use Illuminate\Support\Facades\DB;

class MergeVoidLocationRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:merge_location_void_construction';

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
//        dd(123);
        $all_location = Location::with('locationConstruction', 'locationVoid')
            ->leftJoin('tbl_survey as s1','tbl_location.survey_id','=','s1.id')
            ->leftJoin('tbl_survey_date as sd','sd.survey_id','=','s1.id')
            ->leftJoin('tbl_survey_setting as ss','ss.survey_id','=','s1.id')
            ->whereRaw("(s1.`status` = 5 and s1.decommissioned = 0 and tbl_location.survey_id > 0 and tbl_location.decommissioned = 0
                            AND (ss.is_require_location_void_investigations = 1 OR ss.is_require_location_construction_details = 1)
                        ) OR (tbl_location.survey_id = 0 and tbl_location.decommissioned = 0)")
        ->orderBy('tbl_location.record_id','DESC')
        ->orderBy('sd.completed_date','DESC')
        ->select('tbl_location.*')
        ->get();

        $location_register = $all_location->where('survey_id',0);
        $location_survey = $all_location->where('survey_id', '!=', 0);
//        dd(count($all_location), count($location_register), count($location_survey));
        try {
            DB::beginTransaction();
            $arr_void = $arr_construction = [];
            foreach ($location_survey as $key => $ls) {
                // void
                //other get of last survey complete
                $arr_void[$ls->record_id]['ceiling_other']= isset($arr_void[$ls->record_id]['ceiling_other']) && !empty($arr_void[$ls->record_id]['ceiling_other']) ? $arr_void[$ls->record_id]['ceiling_other'] : ($ls->locationVoid->ceiling != 1807 ? $ls->locationVoid->ceiling_other ?? '' : '');
                $arr_void[$ls->record_id]['ceiling'][]= isset($ls->locationVoid->ceiling) ? explode(",", $ls->locationVoid->ceiling) : [];
                $arr_void[$ls->record_id]['cavities_other']= isset($arr_void[$ls->record_id]['cavities_other']) && !empty($arr_void[$ls->record_id]['cavities_other']) ? $arr_void[$ls->record_id]['cavities_other'] : ($ls->locationVoid->cavities != 1808 ? $ls->locationVoid->cavities_other ?? '' : '');
                $arr_void[$ls->record_id]['cavities'][]= isset($ls->locationVoid->cavities) ? explode(",", $ls->locationVoid->cavities) : [];
                $arr_void[$ls->record_id]['risers_other']= isset($arr_void[$ls->record_id]['risers_other']) && !empty($arr_void[$ls->record_id]['risers_other']) ? $arr_void[$ls->record_id]['risers_other'] : ($ls->locationVoid->risers != 1809 ? $ls->locationVoid->risers_other ?? '' : '');
                $arr_void[$ls->record_id]['risers'][]= isset($ls->locationVoid->risers) ? explode(",", $ls->locationVoid->risers) : [];
                $arr_void[$ls->record_id]['ducting_other']= isset($arr_void[$ls->record_id]['ducting_other']) && !empty($arr_void[$ls->record_id]['ducting_other']) ? $arr_void[$ls->record_id]['ducting_other'] : ($ls->locationVoid->ducting != 1810 ? $ls->locationVoid->ducting_other ?? '' : '');
                $arr_void[$ls->record_id]['ducting'][]= isset($ls->locationVoid->ducting) ? explode(",", $ls->locationVoid->ducting) : [];
                $arr_void[$ls->record_id]['boxing_other']= isset($arr_void[$ls->record_id]['boxing_other']) && !empty($arr_void[$ls->record_id]['boxing_other']) ? $arr_void[$ls->record_id]['boxing_other'] : ($ls->locationVoid->boxing != 1813 ? $ls->locationVoid->boxing_other ?? '' : '');
                $arr_void[$ls->record_id]['boxing'][]= isset($ls->locationVoid->boxing) ? explode(",", $ls->locationVoid->boxing) : [];
                $arr_void[$ls->record_id]['pipework_other']= isset($arr_void[$ls->record_id]['pipework_other']) && !empty($arr_void[$ls->record_id]['pipework_other']) ? $arr_void[$ls->record_id]['pipework_other'] : ($ls->locationVoid->pipework != 1812 ? $ls->locationVoid->pipework_other ?? '' : '');
                $arr_void[$ls->record_id]['pipework'][]= isset($ls->locationVoid->pipework) ? explode(",", $ls->locationVoid->pipework) : [];
                $arr_void[$ls->record_id]['floor_other']= isset($arr_void[$ls->record_id]['floor_other']) && !empty($arr_void[$ls->record_id]['floor_other']) ? $arr_void[$ls->record_id]['floor_other'] : ($ls->locationVoid->floor != 1811 ? $ls->locationVoid->floor_other ?? '' : '');
                $arr_void[$ls->record_id]['floor'][]= isset($ls->locationVoid->floor) ? explode(",", $ls->locationVoid->floor) : [];

                //construction
                $celing  = $this->stringToArray($ls->locationConstruction->ceiling ?? null);
                $walls  = $this->stringToArray($ls->locationConstruction->walls ?? null);
                $floor  = $this->stringToArray($ls->locationConstruction->floor ?? null);
                $doors  = $this->stringToArray($ls->locationConstruction->doors ?? null);
                $windows  = $this->stringToArray($ls->locationConstruction->windows ?? null);

                $arr_construction[$ls->record_id]['ceiling'][] = $celing;
                $arr_construction[$ls->record_id]['ceiling_other'] = isset($arr_construction[$ls->record_id]['ceiling_other']) && !empty($arr_construction[$ls->record_id]['ceiling_other']) ? $arr_construction[$ls->record_id]['ceiling_other'] : (!in_array(1957, $celing) ? $ls->locationConstruction->ceiling_other ?? NULL : '');
                $arr_construction[$ls->record_id]['walls'][] = $walls;
                $arr_construction[$ls->record_id]['walls_other'] = isset($arr_construction[$ls->record_id]['walls_other']) && !empty($arr_construction[$ls->record_id]['walls_other']) ? $arr_construction[$ls->record_id]['walls_other'] : (!in_array(1958, $walls) ? $ls->locationConstruction->walls_other ?? NULL : '');
                $arr_construction[$ls->record_id]['floor'][] = $floor;
                $arr_construction[$ls->record_id]['floor_other'] = isset($arr_construction[$ls->record_id]['floor_other']) && !empty($arr_construction[$ls->record_id]['floor_other']) ? $arr_construction[$ls->record_id]['floor_other'] : (!in_array(1959, $floor) ? $ls->locationConstruction->floor_other ?? NULL : '');
                $arr_construction[$ls->record_id]['doors'][] = $doors;
                $arr_construction[$ls->record_id]['doors_other'] = isset($arr_construction[$ls->record_id]['doors_other']) && !empty($arr_construction[$ls->record_id]['doors_other']) ? $arr_construction[$ls->record_id]['doors_other'] : (!in_array(1960, $doors) ? $ls->locationConstruction->doors_other ?? NULL : '');
                $arr_construction[$ls->record_id]['windows'][] = $windows;
                $arr_construction[$ls->record_id]['windows_other'] = isset($arr_construction[$ls->record_id]['windows_other']) && !empty($arr_construction[$ls->record_id]['windows_other']) ? $arr_construction[$ls->record_id]['windows_other'] : (!in_array(1961, $windows) ? $ls->locationConstruction->windows_other ?? NULL : '');

                // register have other then no update


            }
//            dd($arr_construction, $arr_void);

            foreach ($location_register as $key => $location){
                $lv = $location->locationVoid ?? NULL;
                if($lv){
//                    dd($this->handleVoid($lv->ceiling, $arr_void[$location->record_id]['ceiling'] ?? [], 1807), $location->record_id);
                    $lv->ceiling = $this->handleVoid($lv->ceiling, $arr_void[$location->record_id]['ceiling'] ?? [], 1807);
                    $lv->ceiling_other = isset($lv->ceiling_other) && !empty($lv->ceiling_other) ? $lv->ceiling_other : $arr_void[$location->record_id]['ceiling_other'] ?? NULL ;
                    $lv->cavities = $this->handleVoid($lv->cavities, $arr_void[$location->record_id]['cavities'] ?? [], 1808);
                    $lv->cavities_other = isset($lv->cavities_other) && !empty($lv->cavities_other) ? $lv->cavities_other : $arr_void[$location->record_id]['cavities_other'] ?? NULL ;
                    $lv->risers = $this->handleVoid($lv->risers, $arr_void[$location->record_id]['risers'] ?? [], 1809);
                    $lv->risers_other = isset($lv->risers_other) && !empty($lv->risers_other) ? $lv->risers_other : $arr_void[$location->record_id]['risers_other'] ?? NULL ;
                    $lv->ducting = $this->handleVoid($lv->ducting, $arr_void[$location->record_id]['ducting'] ?? [], 1810);
                    $lv->ducting_other = isset($lv->ducting_other) && !empty($lv->ducting_other) ? $lv->ducting_other : $arr_void[$location->record_id]['ducting_other'] ?? NULL ;
                    $lv->boxing = $this->handleVoid($lv->boxing, $arr_void[$location->record_id]['boxing'] ?? [], 1813);
                    $lv->boxing_other = isset($lv->boxing_other) && !empty($lv->boxing_other) ? $lv->boxing_other : $arr_void[$location->record_id]['boxing_other'] ?? NULL ;
                    $lv->pipework = $this->handleVoid($lv->pipework, $arr_void[$location->record_id]['pipework'] ?? [], 1812);
                    $lv->pipework_other = isset($lv->pipework_other) && !empty($lv->pipework_other) ? $lv->pipework_other : $arr_void[$location->record_id]['pipework_other'] ?? NULL ;
                    $lv->floor = $this->handleVoid($lv->floor, $arr_void[$location->record_id]['floor'] ?? [], 1811);
                    $lv->floor_other = isset($lv->floor_other) && !empty($lv->floor_other) ? $lv->floor_other : $arr_void[$location->record_id]['floor_other'] ?? NULL ;
                    $lv->save(['timestamps' => false]);
                }

                $lc = $location->locationConstruction ?? NULL;
//                dd($lc);
                if($lc){
                    $lc->ceiling = $this->handleConstruction($lc->ceiling, $arr_construction[$location->record_id]['ceiling'] ?? [], CELLING_CONSTRUCTION_OOS);
                    $lc->ceiling_other = isset($lc->ceiling_other) && !empty($lc->ceiling_other) ? $lc->ceiling_other : $arr_construction[$location->record_id]['ceiling_other'] ?? '' ;
                    $lc->walls = $this->handleConstruction($lc->walls, $arr_construction[$location->record_id]['walls'] ?? [], WALL_CONSTRUCTION_OOS);
                    $lc->walls_other = isset($lc->walls_other) && !empty($lc->walls_other) ? $lc->walls_other : $arr_construction[$location->record_id]['ceiling_other'] ?? '' ;
                    $lc->floor = $this->handleConstruction($lc->floor, $arr_construction[$location->record_id]['floor'] ?? [], FLOOR_CONSTRUCTION_OOS);
                    $lc->floor_other = isset($lc->floor_other) && !empty($lc->floor_other) ? $lc->floor_other : $arr_construction[$location->record_id]['floor_other'] ?? '' ;
                    $lc->doors = $this->handleConstruction($lc->doors, $arr_construction[$location->record_id]['doors'] ?? [], DOOR_CONSTRUCTION_OOS);
                    $lc->doors_other = isset($lc->doors_other) && !empty($lc->doors_other) ? $lc->doors_other : $arr_construction[$location->record_id]['doors_other'] ?? '' ;
                    $lc->windows = $this->handleConstruction($lc->windows, $arr_construction[$location->record_id]['windows'] ?? [], WINDOWN_CONSTRUCTION_OOS);
                    $lc->windows_other = isset($lc->windows_other) && !empty($lc->windows_other) ? $lc->windows_other : $arr_construction[$location->record_id]['windows_other'] ?? '' ;
                    $lc->save(['timestamps' => false]);
                }

            }
//            dd($arr_void, $arr_construction);
            DB::commit();
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            dd($e->getMessage());
        }
        // dd($all_noacm_items->count());
    }
    //$location_void of location register
    //$register_location is register location for get record_id
    //$arr_void == $arr_void[$location->record_id]
    public function handleVoid($location_void, $arr_void, $out_of_scope){
        $arr_temp = [];
        if(count($arr_void) > 0){
            // new void sorted by survey complete date desc
            // only one new parent id is dif to old parent id then stop
            $old_data_array = explode(",",$location_void);
            foreach ($arr_void as $k => $new_data_array){
                if (count($new_data_array) > 0 and count($old_data_array) > 0) {
                    if(!in_array($out_of_scope, $new_data_array)){
//                        dd($new_data_array, $old_data_array);
                        if($new_data_array[0] == $old_data_array[0]){
                            if(count($new_data_array) > 0){
                                $arr_temp = array_unique(array_merge($arr_temp,$new_data_array));
                            }
                        } else {
                            break;
                        }
                    }
                }
            }
//            dd($location_void, $arr_void, $out_of_scope, $old_data_array, $arr_temp);
            if(count($arr_temp) > 0){
                if(count($old_data_array)){
                    return implode(",",array_unique(array_merge($old_data_array,$arr_temp)));
                } else {
                    return implode(",",array_unique($arr_temp));
                }
            }
        }
        return $location_void;
    }

    public function handleConstruction($location_construction, $arr_construction, $out_of_scope){
        $arr_temp = [];
        if(count($arr_construction) > 0){
            $old_data_array = explode(",",$location_construction);
            foreach($arr_construction as $new_construction){
                if(count($new_construction) > 0 && !in_array($out_of_scope, $new_construction)){
                    if(count($new_construction) > 0){
                        $arr_temp = array_unique(array_merge($arr_temp,$new_construction));
                    }
                }
            }
            if(count($arr_temp) > 0){
                if(count($old_data_array) > 0){
//                    dd($old_data_array, $arr_temp, array_unique(array_merge($old_data_array,$arr_temp)), implode(",",array_unique(array_merge($old_data_array,$arr_temp))));
                    return implode(",",array_unique(array_merge($old_data_array,$arr_temp)));
                } else {
                    return implode(",",array_unique($arr_temp));
                }
            }
        }
        return $location_construction;
    }

    public function stringToArray($string) {
        if (isset($string)) {
            if (!is_null($string)) {
                return explode(",",$string);
            }
        }
        return [];
    }

    public function locationMergeConstruction($new_data, $old_data){
        $new_data_array = explode(",",$new_data);
        $old_data_array = explode(",",$old_data);
        $data = array_merge($new_data_array,$old_data_array);
        $data = array_unique($data);
        return implode(",",$data);
    }
}

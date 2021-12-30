<?php

namespace app\Http\Controllers\Migration;

use App\Models\Area;
use App\Models\Location;
use App\Models\Item;
use App\Models\Survey;
use App\Models\AuditTrail;
use Illuminate\Routing\Controller;
use App\Http\Controllers\LoginController as BaseController;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MigrationDataStampController extends Controller
{
    /**
     * Migration tblzone
     * @return string
     */
    public function index($type) {
        switch ($type) {
            case 1:
                $this->migrateArea();
                break;

            case 2:
                $this->migrateAreaFromSurvey();
                break;

            case 3:
                $this->migrateLocation();
                break;

            case 4:
                $this->migrateLocationFromSurvey();
                break;

            case 5:
                $this->migrateItem();
                break;

            case 6:
                $this->migrateItemFromSurvey();
                break;

            default:
                # code...
                break;
        }
    }

    public function migrateArea(){
        try {
            \DB::beginTransaction();
            $registerAreas = Area::whereColumn('id', 'record_id')->get();
            foreach ($registerAreas as $area) {
                $created_at = null;
                $created_by = null;
                $updated_at = null;
                $updated_by = null;

                $created = AuditTrail::where('object_id', $area->id)->where('object_type', 'area')->where('action_type', 'add')->first();
                //get from audit add
                if (!is_null($created)) {
                    $created_at = $created->date_raw;
                    $created_by = $created->user_id;
                //get from audit edit
                } else {
                    $last_view =  AuditTrail::where('object_id', $area->id)
                                                ->where('object_type', 'area')
                                                ->whereIn('action_type', ['edit','view'])
                                                ->orderBy('date','desc')->first();
                    if ($last_view) {
                        $created_at =  $last_view->date_raw;
                        $created_by = $last_view->user_id;
                    }
                }

                $updated = AuditTrail::where('object_id', $area->id)->where('object_type', 'area')
                                ->where('action_type', 'edit')->orderBy('date','desc')->first();
                //get from audit update
                if (!is_null($updated)) {
                    $updated_at = $updated->date_raw;
                    $updated_by = $updated->user_id;
                //get from survey
                } else {
                    $record_id = $area->record_id;
                    $survey = \DB::select("SELECT s.surveyor_id, sd.completed_date from tbl_survey s
                                            left join tbl_area a on a.survey_id = s.id
                                            left join tbl_survey_date sd on sd.survey_id = s.id
                                            WHERE a.record_id = $record_id
                                            AND s.status = 5
                                            ORDER BY sd.completed_date DESC
                                            limit 1");
                    if (count($survey) > 0) {
                        $survey = $survey[0];
                        $updated_at = $survey->completed_date;
                        $updated_by = $survey->surveyor_id;
                    }
                }
                if (is_null($created_at) and !is_null($updated_at)) {
                    $created_at = $updated_at;
                    $created_by = $updated_by;
                } elseif(!is_null($created_at) and is_null($updated_at)) {
                    $updated_at = $created_at;
                    $updated_by = $created_by;
                } elseif(is_null($created_at) and is_null($updated_at)) {
                    if(!is_null($area->updated_at)) {
                        $created_at = date_timestamp_get($area->updated_at);
                        $updated_at = date_timestamp_get($area->updated_at);
                    } else {
                        $created_at = 1448953461;
                        $updated_at = 1448953461;
                    }

                }

                if ($created_at > $updated_at) {
                    $updated_at = $created_at;
                }

                $createdDateTime = Carbon::parse((int)$created_at)->format('Y-m-d H:i:s');
                $updatedDateTime = Carbon::parse((int)$updated_at)->format('Y-m-d H:i:s');
                Area::where('id', $area->id)->update([
                    'created_at'=> $createdDateTime,
                    'created_by'=> $created_by,
                    'updated_at'=> $updatedDateTime,
                    'updated_by'=> $updated_by,
                    ]);
            }

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollback();
            dd($e);
        }
        dd('Done');
    }

    public function migrateAreaFromSurvey() {
        try {
            \DB::beginTransaction();
            $registerAreas = Area::whereColumn('id','!=','record_id')->get();
            $created_at = null;
            $created_by = null;
            $updated_at = null;
            $updated_by = null;
            foreach ($registerAreas as $area) {
                $created = AuditTrail::where('object_id', $area->id)->where('object_type', 'area')->where('action_type', 'add')->first();
                //get from audit add
                if (!is_null($created)) {
                    $created_at = $created->date_raw;
                    $created_by = $created->user_id;
                //get from audit edit
                } else {
                    $record_id = $area->record_id;
                    // get last sendback date
                    $last_send_back = \DB::select("SELECT sd.completed_date, s.surveyor_id, sd.sent_back_date from tbl_survey s
                                            left join tbl_area a on a.survey_id = s.id
                                            left join tbl_survey_date sd on sd.survey_id = s.id
                                            WHERE a.record_id = $record_id
                                            AND s.status = 5
                                            ORDER BY sd.sent_back_date ASC
                                            limit 1");
                    if (count($last_send_back) > 0) {
                        $last_send_back = $last_send_back[0];
                        if ($last_send_back->sent_back_date == 0) {
                            $created_at = ($last_send_back->completed_date == 0) ? 1448953461 : $last_send_back->completed_date;
                        } else {
                            $created_at = $last_send_back->sent_back_date;
                        }

                        $created_by = $last_send_back->surveyor_id;
                    } else {
                        $last_view =  AuditTrail::where('object_id', $area->id)
                                                    ->where('object_type', 'area')
                                                    ->whereIn('action_type', ['edit','view'])
                                                    ->orderBy('date','desc')->first();
                        if ($last_view) {
                            $created_at =  $last_view->date_raw;
                            $created_by = $last_view->user_id;
                        }
                    }

                }

                $updated = AuditTrail::where('object_id', $area->id)->where('object_type', 'area')->where('action_type', 'edit')->orderBy('date','desc')->first();
                //get from audit update
                if (!is_null($updated)) {
                    $updated_at = $updated->date_raw;
                    $updated_by = $updated->user_id;
                //get from survey
                } else {
                    $record_id = $area->record_id;
                    $survey = \DB::select("SELECT s.surveyor_id, sd.completed_date from tbl_survey s
                                            left join tbl_area a on a.survey_id = s.id
                                            left join tbl_survey_date sd on sd.survey_id = s.id
                                            WHERE a.record_id = $record_id
                                            AND s.status = 5
                                            ORDER BY sd.completed_date DESC
                                            limit 1");
                    if (count($survey) > 0) {
                        $survey = $survey[0];
                        $updated_at = $survey->completed_date;
                        $updated_by = $survey->surveyor_id;
                    }
                }
                if (is_null($created_at) and !is_null($updated_at)) {
                    $created_at = $updated_at;
                    $created_by = $updated_by;
                } elseif(!is_null($created_at) and is_null($updated_at)) {
                    $updated_at = $created_at;
                    $updated_by = $created_by;
                } elseif(is_null($created_at) and is_null($updated_at)) {
                    if(!is_null($area->updated_at)) {
                        $created_at = date_timestamp_get($area->updated_at);
                        $updated_at = date_timestamp_get($area->updated_at);
                    } else {
                        $created_at = 1448953461;
                        $updated_at = 1448953461;
                    }

                }
                if ($created_at > $updated_at) {
                    $updated_at = $created_at;
                }

                Area::where('id', $area->id)->update([
                    'created_at'=> Carbon::parse((int)$created_at)->format('Y-m-d H:i:s'),
                    'created_by'=> $created_by,
                    'updated_at'=> Carbon::parse((int)$updated_at)->format('Y-m-d H:i:s'),
                    'updated_by'=> $updated_by,
                    ]);
            }

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollback();
            dd($e);
        }
        dd('Done');
    }

    public function migrateLocation(){
        try {
            \DB::beginTransaction();
            $registerLocations = Location::whereColumn('id', 'record_id')->get();
            $created_at = null;
            $created_by = null;
            $updated_at = null;
            $updated_by = null;
            foreach ($registerLocations as $location) {
                $created = AuditTrail::where('object_id', $location->id)->where('object_type', 'location')->where('action_type', 'add')->first();
                //get from audit add
                if (!is_null($created)) {
                    $created_at = $created->date_raw;
                    $created_by = $created->user_id;
                //get from audit edit
                } else {
                    $last_view =  AuditTrail::where('object_id', $location->id)
                                                ->where('object_type', 'location')
                                                ->whereIn('action_type', ['edit','view'])
                                                ->orderBy('date','desc')->first();
                    if ($last_view) {
                        $created_at =  $last_view->date_raw;
                        $created_by = $last_view->user_id;
                    }

                }

                $updated = AuditTrail::where('object_id', $location->id)->where('object_type', 'location')
                                    ->where('action_type', 'edit')->orderBy('date','desc')->first();
                //get from audit update
                if (!is_null($updated)) {
                    $updated_at = $updated->date_raw;
                    $updated_by = $updated->user_id;
                //get from survey
                } else {
                    $record_id = $location->record_id;
                    $survey = \DB::select("SELECT s.surveyor_id, sd.completed_date from tbl_survey s
                                            left join tbl_location l on l.survey_id = s.id
                                            left join tbl_survey_date sd on sd.survey_id = s.id
                                            WHERE l.record_id = $record_id
                                            AND s.status = 5
                                            ORDER BY sd.completed_date DESC
                                            limit 1");
                    if (count($survey) > 0) {
                        $survey = $survey[0];
                        $updated_at = $survey->completed_date;
                        $updated_by = $survey->surveyor_id;
                    }
                }
                if (is_null($created_at) and !is_null($updated_at)) {
                    $created_at = $updated_at;
                    $created_by = $updated_by;
                } elseif(!is_null($created_at) and is_null($updated_at)) {
                    $updated_at = $created_at;
                    $updated_by = $created_by;
                } elseif(is_null($created_at) and is_null($updated_at)) {
                    if(!is_null($location->updated_at)) {
                        $created_at = date_timestamp_get($location->updated_at);
                        $updated_at = date_timestamp_get($location->updated_at);
                    } else {
                        $created_at = 1448953461;
                        $updated_at = 1448953461;
                    }

                }
                if ($created_at > $updated_at) {
                    $updated_at = $created_at;
                }

                Location::where('id', $location->id)->update([
                    'created_at'=> Carbon::parse((int)$created_at)->format('Y-m-d H:i:s'),
                    'created_by'=> $created_by,
                    'updated_at'=> Carbon::parse((int)$updated_at)->format('Y-m-d H:i:s'),
                    'updated_by'=> $updated_by,
                    ]);
            }

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollback();
            dd($e);
        }
        dd('Done');
    }

    public function migrateLocationFromSurvey() {
        try {
            \DB::beginTransaction();
            $registerLocations = Location::whereColumn('id','!=','record_id')->get();
            $created_at = null;
            $created_by = null;
            $updated_at = null;
            $updated_by = null;
            foreach ($registerLocations as $location) {
                $created = AuditTrail::where('object_id', $location->id)->where('object_type', 'location')->where('action_type', 'add')->first();
                //get from audit add
                if (!is_null($created)) {
                    $created_at = $created->date_raw;
                    $created_by = $created->user_id;
                //get from audit edit
                } else {
                    $record_id = $location->record_id;
                    // get last sendback date
                    $last_send_back = \DB::select("SELECT sd.completed_date, s.surveyor_id, sd.sent_back_date from tbl_survey s
                                            left join tbl_location l on l.survey_id = s.id
                                            left join tbl_survey_date sd on sd.survey_id = s.id
                                            WHERE l.record_id = $record_id
                                            AND s.status = 5
                                            ORDER BY sd.sent_back_date ASC
                                            limit 1");
                    if (count($last_send_back) > 0) {
                        $last_send_back = $last_send_back[0];
                        if ($last_send_back->sent_back_date == 0) {
                            $created_at = ($last_send_back->completed_date == 0) ? 1448953461 : $last_send_back->completed_date;
                        } else {
                            $created_at = $last_send_back->sent_back_date;
                        }
                        $created_by = $last_send_back->surveyor_id;
                    } else {
                        $last_view =  AuditTrail::where('object_id', $location->id)
                                                    ->where('object_type', 'location')
                                                    ->whereIn('action_type', ['edit','view'])
                                                    ->orderBy('date','desc')->first();
                        if ($last_view) {
                            $created_at =  $last_view->date_raw;
                            $created_by = $last_view->user_id;
                        }
                    }

                }

                $updated = AuditTrail::where('object_id', $location->id)->where('object_type', 'location')
                                    ->where('action_type', 'edit')->orderBy('date','desc')->first();
                //get from audit update
                if (!is_null($updated)) {
                    $updated_at = $updated->date_raw;
                    $updated_by = $updated->user_id;
                //get from survey
                } else {
                    $record_id = $location->record_id;
                    $survey = \DB::select("SELECT s.surveyor_id, sd.completed_date from tbl_survey s
                                            left join tbl_location l on l.survey_id = s.id
                                            left join tbl_survey_date sd on sd.survey_id = s.id
                                            WHERE l.record_id = $record_id
                                            AND s.status = 5
                                            ORDER BY sd.completed_date DESC
                                            limit 1");
                    if (count($survey) > 0) {
                        $survey = $survey[0];
                        $updated_at = $survey->completed_date;
                        $updated_by = $survey->surveyor_id;
                    }
                }
                if (is_null($created_at) and !is_null($updated_at)) {
                    $created_at = $updated_at;
                    $created_by = $updated_by;
                } elseif(!is_null($created_at) and is_null($updated_at)) {
                    $updated_at = $created_at;
                    $updated_by = $created_by;
                } elseif(is_null($created_at) and is_null($updated_at)) {
                    if(!is_null($location->updated_at)) {
                    $created_at = date_timestamp_get($location->updated_at);
                    $updated_at = date_timestamp_get($location->updated_at);
                    } else {
                        $created_at = 1448953461;
                        $updated_at = 1448953461;
                    }

                }
                if ($created_at > $updated_at) {
                    $updated_at = $created_at;
                }

                Location::where('id', $location->id)->update([
                    'created_at'=> Carbon::parse((int)$created_at)->format('Y-m-d H:i:s'),
                    'created_by'=> $created_by,
                    'updated_at'=> Carbon::parse((int)$updated_at)->format('Y-m-d H:i:s'),
                    'updated_by'=> $updated_by,
                    ]);
            }

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollback();
            dd($e);
        }
        dd('Done');    }
    public function migrateItem(){
        try {
            \DB::beginTransaction();
            $registerItems = Item::whereColumn('id', 'record_id')->get();
            $created_at = null;
            $created_by = null;
            $updated_at = null;
            $updated_by = null;
            foreach ($registerItems as $item) {
                $created = AuditTrail::where('object_id', $item->id)->where('object_type', 'item')->where('action_type', 'add')->first();
                //get from audit add
                if (!is_null($created)) {
                    $created_at = $created->date_raw;
                    $created_by = $created->user_id;
                //get from audit edit
                } else {
                    $last_view =  AuditTrail::where('object_id', $item->id)
                                                ->where('object_type', 'item')
                                                ->whereIn('action_type', ['edit','view'])
                                                ->orderBy('date','desc')->first();
                    if ($last_view) {
                        $created_at =  $last_view->date_raw;
                        $created_by = $last_view->user_id;
                    }

                }

                $updated = AuditTrail::where('object_id', $item->id)->where('object_type', 'item')
                                ->where('action_type', 'edit')->orderBy('date','desc')->first();
                //get from audit update
                if (!is_null($updated)) {
                    $updated_at = $updated->date_raw;
                    $updated_by = $updated->user_id;
                //get from survey
                } else {
                    $record_id = $item->record_id;
                    $survey = \DB::select("SELECT s.surveyor_id, sd.completed_date from tbl_survey s
                                            left join tbl_items i on i.survey_id = s.id
                                            left join tbl_survey_date sd on sd.survey_id = s.id
                                            WHERE i.record_id = $record_id
                                            AND s.status = 5
                                            ORDER BY sd.completed_date DESC
                                            limit 1");
                    if (count($survey) > 0) {
                        $survey = $survey[0];
                        $updated_at = $survey->completed_date;
                        $updated_by = $survey->surveyor_id;
                    }
                }
                if (is_null($created_at) and !is_null($updated_at)) {
                    $created_at = $updated_at;
                    $created_by = $updated_by;
                } elseif(!is_null($created_at) and is_null($updated_at)) {
                    $updated_at = $created_at;
                    $updated_by = $created_by;
                } elseif(is_null($created_at) and is_null($updated_at)) {
                    if(!is_null($item->updated_at)) {
                    $created_at = date_timestamp_get($item->updated_at);
                    $updated_at = date_timestamp_get($item->updated_at);
                    } else {
                        $created_at = 1448953461;
                        $updated_at = 1448953461;
                    }

                }

                if ($created_at > $updated_at) {
                    $updated_at = $created_at;
                }

                Item::where('id', $item->id)->update([
                    'created_at'=> Carbon::parse((int)$created_at)->format('Y-m-d H:i:s'),
                    'created_by'=> $created_by,
                    'updated_at'=> Carbon::parse((int)$updated_at)->format('Y-m-d H:i:s'),
                    'updated_by'=> $updated_by,
                    ]);
            }

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollback();
            dd($e);
        }
        dd('Done');
    }

    public function migrateItemFromSurvey() {
        try {
            \DB::beginTransaction();
            $registerItems = Item::whereColumn('id','!=','record_id')->get();
            $created_at = null;
            $created_by = null;
            $updated_at = null;
            $updated_by = null;
            foreach ($registerItems as $item) {
                $created = AuditTrail::where('object_id', $item->id)->where('object_type', 'item')->where('action_type', 'add')->first();
                //get from audit add
                if (!is_null($created)) {
                    $created_at = $created->date_raw;
                    $created_by = $created->user_id;
                //get from audit edit
                } else {
                    $record_id = $item->record_id;
                    // get last sendback date
                    $last_send_back = \DB::select("SELECT sd.completed_date, s.surveyor_id, sd.sent_back_date from tbl_survey s
                                            left join tbl_items i on i.survey_id = s.id
                                            left join tbl_survey_date sd on sd.survey_id = s.id
                                            WHERE i.record_id = $record_id
                                            AND s.status = 5
                                            ORDER BY sd.sent_back_date ASC
                                            limit 1");
                    if (count($last_send_back) > 0) {
                        $last_send_back = $last_send_back[0];
                        if ($last_send_back->sent_back_date == 0) {
                            $created_at = ($last_send_back->completed_date == 0) ? 1448953461 : $last_send_back->completed_date;
                        } else {
                            $created_at = $last_send_back->sent_back_date;
                        }
                        $created_by = $last_send_back->surveyor_id;
                    } else {
                        $last_view =  AuditTrail::where('object_id', $item->id)
                                                    ->where('object_type', 'item')
                                                    ->whereIn('action_type', ['edit','view'])
                                                    ->orderBy('date','desc')->first();
                        if ($last_view) {
                            $created_at =  $last_view->date_raw;
                            $created_by = $last_view->user_id;
                        }
                    }

                }

                $updated = AuditTrail::where('object_id', $item->id)->where('object_type', 'item')
                                        ->where('action_type', 'edit')->orderBy('date','desc')->first();
                //get from audit update
                if (!is_null($updated)) {
                    $updated_at = $updated->date_raw;
                    $updated_by = $updated->user_id;
                //get from survey
                } else {
                    $record_id = $item->record_id;
                    $survey = \DB::select("SELECT s.surveyor_id, sd.completed_date from tbl_survey s
                                            left join tbl_items i on i.survey_id = s.id
                                            left join tbl_survey_date sd on sd.survey_id = s.id
                                            WHERE i.record_id = $record_id
                                            AND s.status = 5
                                            ORDER BY sd.completed_date DESC
                                            limit 1");
                    if (count($survey) > 0) {
                        $survey = $survey[0];
                        $updated_at = $survey->completed_date;
                        $updated_by = $survey->surveyor_id;
                    }
                }
                if (is_null($created_at) and !is_null($updated_at)) {
                    $created_at = $updated_at;
                    $created_by = $updated_by;
                } elseif(!is_null($created_at) and is_null($updated_at)) {
                    $updated_at = $created_at;
                    $updated_by = $created_by;
                } elseif(is_null($created_at) and is_null($updated_at)) {
                    if(!is_null($item->updated_at)) {
                        $created_at = date_timestamp_get($item->updated_at);
                        $updated_at = date_timestamp_get($item->updated_at);
                    } else {
                        $created_at = 1448953461;
                        $updated_at = 1448953461;
                    }

                }

                if ($created_at > $updated_at) {
                    $updated_at = $created_at;
                }

                Item::where('id', $item->id)->update([
                    'created_at'=> Carbon::parse((int)$created_at)->format('Y-m-d H:i:s'),
                    'created_by'=> $created_by,
                    'updated_at'=> Carbon::parse((int)$updated_at)->format('Y-m-d H:i:s'),
                    'updated_by'=> $updated_by,
                    ]);
            }

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollback();
            dd($e);
        }
        dd('Done');    }
}

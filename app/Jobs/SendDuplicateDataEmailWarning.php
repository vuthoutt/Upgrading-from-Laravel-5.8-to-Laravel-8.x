<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use App\Models\Location;
use App\Models\Area;
use App\Models\Survey;
use App\Mail\DuplicateDataEmailWarning;

class SendDuplicateDataEmailWarning implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $survey_id;
    public $area_id;
    public $location_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($survey_id, $area_id, $location_id)
    {
        $this->survey_id  = $survey_id;
        $this->area_id  = $area_id;
        $this->location_id  = $location_id;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $survey = Survey::find($this->survey_id);
            $areas = Area::whereIn('id', $this->area_id)->get();
            $locations = Location::whereIn('id', $this->location_id)->get();
            // only send for ID7 and ID64
            $users = User::whereIn('id',[7,64])->get();
            if (!is_null($locations) or !is_null($areas)) {
                foreach ($users as $user) {
                    \Mail::to($user->email)->send(new DuplicateDataEmailWarning($survey,
                                                            $areas,
                                                            $locations
                                                        ));
                }
            }
            echo(' ==========>  Send duplicate mail successfully');
        } catch (\Exception $e) {
            echo($e->getMessage());
        }
    }

}

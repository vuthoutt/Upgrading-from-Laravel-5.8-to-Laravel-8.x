<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Survey;
use App\User;
use App\Mail\HighRiskSurveyApprovalEmail;

class SendHighRiskSurveyApproval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $survey_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($survey_id)
    {
        $this->survey_id  = $survey_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $survey =  Survey::find($this->survey_id);
            $survey->link_pdf = route('survey.view.pdf',['type'=> VIEW_SURVEY_PDF,'id'=> \CommonHelpers::getLatestPdfBySurvey($this->survey_id)]);
            if (isset($survey->project) and !is_null($survey->project)) {
                $contacts = [$survey->project->lead_key ?? 0, $survey->project->second_lead_key ?? 0, $survey->project->sponsor_lead_key ?? 0];
                $users = User::whereIn('id', $contacts)->get();
                foreach ($users as $user) {
                    if (!is_null($user)) {
                            \Mail::to($user->email)->send(new HighRiskSurveyApprovalEmail($survey));
                    }
                }
            }

        } catch (\Exception $e) {
            echo($e->getMessage());
        }
    }

}

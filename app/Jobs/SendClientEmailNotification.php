<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use App\Models\Property;
use App\Models\Survey;
use App\Mail\ClientEmail;

class SendClientEmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $type;
    public $user_id;
    public $property_ref;
    public $property_name;
    public $property_block;
    public $survey_ref;
    public $contractor_name;
    public $survey_name;
    public $project_ref;
    public $client_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_name, $type, $user_id, $property_ref, $property_name, $property_block, $survey_ref = '', $contractor_name = '', $survey_name = '' , $project_ref = '')
    {
        $this->client_name  = $client_name;
        $this->type  = $type;
        $this->property_ref  = $property_ref;
        $this->property_name  = $property_name;
        $this->property_block  = $property_block;
        $this->survey_ref  = $survey_ref;
        $this->contractor_name  = $contractor_name;
        $this->survey_name  = $survey_name;
        $this->project_ref  = $project_ref;
        $this->user_id  = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            if (CONTRACTOR_APPROVED_EMAILTYPE == $this->type || CONTRACTOR_REJECT_EMAILTYPE == $this->type) {
                $user =  User::find($this->user_id);
                $client = $user->clients;
                if (!is_null($client)) {
                \Mail::to($client->email_notification)->send(new ClientEmail($this->client_name,
                                                        $this->type,
                                                        $this->property_ref,
                                                        $this->property_name,
                                                        $this->property_block,
                                                        $this->survey_ref,
                                                        $this->contractor_name,
                                                        $this->survey_name,
                                                        $this->project_ref
                                                    ));
//                echo(' ==========>  Send client mail successfully');
            } else {
//                echo(' ==========>  User does not exist');
            }
            }else{
            $user =  User::find($this->user_id);
            if (!is_null($user)) {

                \Mail::to($user->email)->send(new ClientEmail($this->client_name,
                                                        $this->type,
                                                        $this->property_ref,
                                                        $this->property_name,
                                                        $this->property_block,
                                                        $this->survey_ref,
                                                        $this->contractor_name,
                                                        $this->survey_name,
                                                        $this->project_ref
                                                    ));
//                echo(' ==========>  Send client mail successfully');
            } else {
//                echo(' ==========>  User does not exist');
            }
        }
        } catch (\Exception $e) {
            echo($e->getMessage());
        }


    }

    protected function listenForEvents()
    {
        $this->laravel['events']->listen(JobProcessing::class, function ($event) {
            $this->writeOutput($event->job, 'starting');
        });

        $this->laravel['events']->listen(JobProcessed::class, function ($event) {
            $this->writeOutput($event->job, 'success');
        });

        $this->laravel['events']->listen(JobFailed::class, function ($event) {
            $this->writeOutput($event->job, 'failed');

            $this->logFailedJob($event);
        });
    }
}

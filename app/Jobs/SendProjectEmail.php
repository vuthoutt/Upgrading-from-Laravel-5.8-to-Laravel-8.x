<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Project;
use App\Models\Client;
use App\Mail\ProjectEmail;

class SendProjectEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $project_id;
    public $contractor_id;
    public $document_id;
    public $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $project_id, $contractor_id, $document_id = null)
    {
        $this->project_id = $project_id;
        $this->contractor_id = $contractor_id;
        $this->type = $type;
        $this->document_id = $document_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            //    echo(' ==========> Get Contractor ');
            $contractor = Client::find($this->contractor_id);

//            echo(' ==========> Get Project ');
            $project = Project::find($this->project_id);

//            echo(' ==========> Get My Organisation ');
            $myOrganisation = Client::where('client_type', Client::$USER_ADMIN)->first();
            if (isset($contractor->email_notification) and !is_null($contractor->email_notification)) {
                if (strpos($contractor->email_notification, ',') !== FALSE) {
                    foreach (explode(',', $contractor->email_notification) as $emailC) {
//                        echo(' ==========> Send Mail To '. $emailC);
                            \Mail::to(trim($emailC))
                            ->send(new ProjectEmail(
                                                    $this->type,
                                                    $project->reference,
                                                    $project->title,
                                                    $myOrganisation->name,
                                                    $this->project_id,
                                                    $project->property,
                                                    $project->project_type_text,
                                                ));
                    }
                } else {
//                    echo(' ==========> Send Mail To '. $contractor->email_notification);
                            \Mail::to($contractor->email_notification)
                            ->send(new ProjectEmail(
                                                    $this->type,
                                                    $project->reference,
                                                    $project->title,
                                                    $myOrganisation->name,
                                                    $this->project_id,
                                                    $project->property,
                                                    $project->project_type_text,
                                                ));
                }
            }
//            echo(' ==========> Send Mail To Successful');

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

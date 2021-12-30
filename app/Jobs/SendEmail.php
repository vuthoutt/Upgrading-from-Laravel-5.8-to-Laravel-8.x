<?php

namespace App\Jobs;

use App\Mail\SurveyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\ResetPasswordEmail;
use App\Mail\ChangeMailEmail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    public $email;
    public $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $data, $type)
    {
        $this->email = $email;
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            switch ($this->type) {
                case EMAIL_CHANGE_MAIL_QUEUE:
                    echo(' ==========>  Send change mail email ');
                    \Mail::to($this->email)->send(new ChangeMailEmail($this->data));
                    break;

                case EMAIL_REGISTER_QUEUE:
                case EMAIL_RESET_PASSWORD_QUEUE:
                    echo(' ==========>  Send reset password mail email ');
                    \Mail::to($this->email)->send(new ResetPasswordEmail($this->data));
                    break;
                case SURVEY_REJECT_EMAIL_QUEUE:

                    if (isset($this->email) and !is_null($this->email)) {
                        if (strpos($this->email, ',') !== FALSE) {
                            foreach (explode(',', $this->email) as $emailC) {
                                \Mail::to(trim($emailC))
                                    ->send(new SurveyEmail($this->data));
                            }
                        } else {
                            \Mail::to(trim($this->email))
                                ->send(new SurveyEmail($this->data));
                        }
                    }
                    break;
                default:
                    # code...
                    break;
            }
            echo(' ==========>  Send  mail successfully ');

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

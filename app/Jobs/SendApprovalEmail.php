<?php

namespace App\Jobs;

use App\Mail\ApprovalSurveyEmail;
use App\Mail\WorkRequestApprovalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\WorkRequestPublishedEmail;

class SendApprovalEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $type;
    public $additional_email;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$type, $additional_email = null)
    {
        $this->data = $data;
        $this->type = $type;
        $this->additional_email = $additional_email;
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
                case SURVEY_APPROVAL_EMAIL_QUEUE:
                    if(count($this->additional_email) > 0 and $this->additional_email[0] != null){
                        \Mail::to($this->data['email'])->cc($this->additional_email)->send(new ApprovalSurveyEmail($this->data));
                    }else{
                        \Mail::to($this->data['email'])->send(new ApprovalSurveyEmail($this->data));
                    }
                    break;
                case PUBLISHED_WORK_REQUEST_EMAIL_QUEUE:
                    if(count($this->additional_email) > 0 and $this->additional_email[0] != null){
                        \Mail::to($this->data['email'])->cc($this->additional_email)->send(new WorkRequestPublishedEmail($this->data));
                    }else{
                        \Mail::to($this->data['email'])->send(new WorkRequestPublishedEmail($this->data));
                    }
                    break;
                case WORK_REQUEST_EMAIL_QUEUE:
                    if(count($this->additional_email) > 0 and $this->additional_email[0] != null){
                        \Mail::to($this->data['email'])->cc($this->additional_email)->send(new WorkRequestApprovalEmail($this->data));
                    }else{
                        \Mail::to($this->data['email'])->send(new WorkRequestApprovalEmail($this->data));
                    }
                    break;
                default:
                    # code...
                    break;
            }
        } catch (\Exception $e) {
            echo($e->getMessage());
        }
    }
}


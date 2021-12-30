<?php

namespace App\Jobs;

use App\Mail\IncidentReportApprovedNotification;
use App\Mail\IncidentReportReadyApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class IncidentReportNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $data;
    private $type;

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
                case INCIDENT_REPORT_READY_FOR_APPROVAL_EMAIL:
                    \Mail::to($this->email)->send(new IncidentReportReadyApproval($this->data));
                    break;
                case INCIDENT_REPORT_APPROVED_EMAIL:
                    \Mail::to($this->email)->send(new IncidentReportApprovedNotification($this->data));
                    break;
                case INCIDENT_REPORT_APPROVED_PROPERTY_CONTACT_EMAIL:
                    foreach ($this->email as $user){
                        $this->data['username'] = $user->full_name ?? '';
                        \Mail::to($user->email)->send(new IncidentReportApprovedNotification($this->data));
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

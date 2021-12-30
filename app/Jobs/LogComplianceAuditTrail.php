<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\ShineCompliance\ComplianceAuditTrail;

class LogComplianceAuditTrail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $audit = ComplianceAuditTrail::create($this->data);

        if ($audit) {
            ComplianceAuditTrail::where('id', $audit->id)->update(['shine_reference' => 'AR'.$audit->id]);
        }

    }

}

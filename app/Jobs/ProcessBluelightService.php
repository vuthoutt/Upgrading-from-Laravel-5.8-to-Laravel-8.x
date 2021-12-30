<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\ResourceController;

class ProcessBluelightService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $zone_id;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($zone_id, $user)
    {
        $this->zone_id = $zone_id;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ResourceController $rescourceBluelight)
    {
        $rescourceBluelight->generateZipProcess($this->zone_id, $this->user);
    }

}

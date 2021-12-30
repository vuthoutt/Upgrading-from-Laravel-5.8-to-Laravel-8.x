<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\UploadManifestRepository;

class ProcessMobileDataEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $manifest_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($manifest_id)
    {
        $this->manifest_id = $manifest_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UploadManifestRepository $uploadManifestRepository)
    {
        $uploadManifestRepository->insertUploadData($this->manifest_id);
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

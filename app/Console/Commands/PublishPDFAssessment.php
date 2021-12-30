<?php

namespace App\Console\Commands;

use App\Http\Controllers\ShineCompliance\AssessmentController;
use App\Models\ShineCompliance\Assessment;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class PublishPDFAssessment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:publish_pdf_assessment {asse_ids}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish multiple assessment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $asse_ids = $this->argument('asse_ids');
        $this->info("Start publish pdf assessment of ids: $asse_ids");
        $asse_ids = explode(",",$asse_ids);
        $assessments = Assessment::whereIn('id', $asse_ids)->get();
        $request = new Request();
        $request->merge(['assessment_draft' => true]);
        if(count($assessments)){
            $user = User::where('id', 1)->first();
            $user = \Auth::setUser($user);
            $_SERVER['REMOTE_ADDR'] = 'localhost';
            $generatePDFService = app()->make(AssessmentController::class);
            foreach ($asse_ids as $id){
                $this->info("Start publish pdf assessment reference: {$id}");
                $generatePDFService->publishAssessment($id, $request);
            }
        }
        $this->info("Done");
    }
}

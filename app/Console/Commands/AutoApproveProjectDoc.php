<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;
use App\Models\AuditTrail;

class AutoApproveProjectDoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:approve_project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto approve project document after 30 days';

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
        $due_date = time() - 60*60*24*30;
        $documents = Document::where('category',CONTRACTOR_DOC_CATEGORY)->where('status',1)->where('added','<', $due_date)->get();

        if (!is_null($documents)) {
            foreach ($documents as $document) {
                // clone to register
                $this->info("==========> Starting approve document: {$document->name} with ID : {$document->id}! \n");
                Document::where('id',$document->id)->update(['status' => 2,'auto_approve' => 1]);
                $this->info("==========> Approve document: {$document->name} successfully! \n ");
                //log audit
                    $data = [
                        'property_id' => 0,
                        'type' => 'system',
                        'object_type' => 'document',
                        'object_id' => $document->id,
                        'object_parent_id' => $document->project_id,
                        'shine_reference' => $document->reference,
                        'object_reference' => $document->name,
                        'action_type' => 'approved',
                        'user_id' => 0,
                        'user_client_id' => 0,
                        'user_name' => 'System',
                        'date' => time(),
                        'archive_id' => 0,
                        'ip' => 0,
                        'comments' => "Auto approved {$document->name} after 30days"
                    ];
                    AuditTrail::create($data);
            }
        }
    }
}

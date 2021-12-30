<?php

namespace App\Console\Commands;

use App\Helpers\CommonHelpers;
use App\Models\Document;
use App\Models\Project;
use App\Models\PublishedWorkRequest;
use App\Models\ShineDocumentStorage;
use App\Models\WorkRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\LogLogin;
use App\User;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\DB;

class CorrectTenderWorkRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:correctTenderWR';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding Tender to Project and check send emails';

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
        $this->info("==========> Start ");
        $result = DB::table('tbl_work_request as w')
            ->leftJoin('tbl_project as p','p.id','=','w.project_id')
            ->leftJoin('tbl_documents as d',function($join){
                $join->on('d.project_id', '=', 'p.id')
                    ->where(['d.category'=>TENDER_DOC_CATEGORY]);
            })
            ->where(['w.status' => WORK_REQUEST_COMPLETE, 'w.is_major' => 0])
            ->where('w.sor_id', '>', 0)
            ->whereRaw("d.id IS NULL")->selectRaw('w.*')->get();
        if($result){
            // case WR approved in Orchard
            //TODO send mail for valid WRs
            $copy_pdf = [];
            foreach ($result as $data){
                if($data->project_id > 0){
                    $this->info("==========> Project ID: {$data->project_id}");
                    $project = Project::find($data->project_id);
                    if($project){
//                        if($project->contractors){
//                            $ExistPONotifications = \Notifications::getListExistingNotificationsInProject($project->id, 1);
//                            $ExistPOContractors = [];
//                            foreach ($ExistPONotifications as $existNoti) {
//                                $ExistPOContractors[] = $existNoti->contractor_id;
//                            }
//                            foreach (explode(",", $project->contractors) as  $contractor_id) {
//                                if(!in_array($contractor_id, $ExistPOContractors)){
//                                    $this->info("==========> Send first email to ID: {$contractor_id}");
//                                    \Notifications::sendMailNotificationCommand(1, $project->id, $contractor_id);
//                                }
//                            }
//                        }
//                        if($project->checked_contractors) {
//                            $ExistSuccessNotifications = \Notifications::getListExistingNotificationsInProject($project->id, 3);
//                            $ExistSuccessContractors = [];
//                            foreach ($ExistSuccessNotifications as $existNoti) {
//                                $ExistSuccessContractors[] = $existNoti->contractor_id;
//                            }
//                            foreach (explode(",", $project->checked_contractors) as $contractor_id) {
//                                if(!in_array($contractor_id, $ExistSuccessContractors)){
//                                    $this->info("==========> Send first email to ID: {$contractor_id}");
//                                    \Notifications::sendMailNotificationCommand(3, $project->id, $contractor_id);
//                                }
//                            }
//                        }
                        $copy_pdf = $this->copyWorkRequestDocument($data, $project);
                        $this->info("==========> Is copy okay: {$copy_pdf}");
                    }
                }
            }
        }
        dd('Done');
    }

    public function copyWorkRequestDocument($work_request, $project, $contractors = ''){
        $work_request_document = PublishedWorkRequest::where('work_request_id', $work_request->id)->orderBy('id','DESC')->first();
        // only create document for WR when the API is done
        if($work_request_document && $project){
            $dataDocument = [
                "client_id" => $project->client_id,
                "name" => $work_request_document->name ?? '',
                "project_id" => $project->id,
                "type" => 30,
                "re_type" => "project",
                "contractors" => $contractors, // permission
                "rejected" => 0,
                "deleted" => 0,
                "category" => 4,
                "authorised" => Carbon::now()->timestamp,
                "authorised_by" => $work_request->created_by ?? 0,
                "value" => 0,
                "added" => Carbon::now()->timestamp,
                "status" => 1,
                "added_by" => $work_request->created_by ?? 0
            ];
            $document = Document::create($dataDocument);
            if ($document) {
                $docRef = CommonHelpers::getDocumentReference($document->category, $document->id);
                if($docRef){
                    Document::where('id', $document->id)->update(['reference' => $docRef]);
                }
                //copy work request pdf to tender
                $copy_tender = ShineDocumentStorage::updateOrCreate([
                    "object_id" => $document->id,
                    "type" => DOCUMENT_FILE
                ],
                    [
                        "path" => $work_request_document->path,
                        "file_name" => $work_request_document->filename,
                        "mime" => $work_request_document->mime,
                        "size" => $work_request_document->size,
                        "addedDate" => time(),
                        "addedBy" => $work_request->created_by ?? 0,
                    ]);
                if($copy_tender){
                    Document::where('id', $document->id)->update(['document_present' => 1]);
                }
                //send email
                //TODO send mail later, recheck
                if(isset($work_request->contractors) && !empty($work_request->contractors)){
                    foreach (explode(",", $work_request->contractors) as  $contractor_id) {
                        \Notifications::sendMailNotification(1, $project->id, $contractor_id);
                    }
                }
                if(isset($work_request->checked_contractors) && !empty($work_request->checked_contractors)) {
                    foreach (explode(",", $work_request->checked_contractors) as $contractor_id) {
                        \Notifications::sendMailNotification(3, $project->id, $contractor_id);
                    }
                }
            }
            return true;
        }
        return false;
    }
}

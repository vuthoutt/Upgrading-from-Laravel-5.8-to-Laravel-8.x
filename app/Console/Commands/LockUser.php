<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LogLogin;
use App\User;
use App\Models\AuditTrail;

class LockUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:lock_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lock User After 90 days not access to system';

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
        // only CO1 user
        $sql = "SELECT max(logtime) as logtime, user_id from tbl_log_login WHERE success = 1 GROUP BY user_id";
        $log_logins = \DB::select($sql);

        foreach ($log_logins as $log) {
            $log_time = (time() - $log->logtime)/86400;

            if ($log_time > 90) {
                User::where('id', $log->user_id)->where('is_locked',0)->where('client_id', 1)->update(['is_locked' => 1]);
                $this->info("==========> user: {$log->user_id} =>>> last time :{$log_time }");
                $this->info("==========> lock user {$log->user_id}");
                //log audit
                    $data = [
                        'property_id' => 0,
                        'type' => 'system',
                        'object_type' => 'user',
                        'object_id' => $log->user_id,
                        'object_parent_id' => 0,
                        'shine_reference' => 0,
                        'object_reference' => 0,
                        'action_type' => 'lock',
                        'user_id' => 0,
                        'user_client_id' => 1,
                        'user_name' => 'System',
                        'date' => time(),
                        'archive_id' => 0,
                        'ip' => 0,
                        'comments' => "Auto lock user ID{$log->user_id} after 90days"
                    ];
                    AuditTrail::create($data);
            }
        }
    }
}

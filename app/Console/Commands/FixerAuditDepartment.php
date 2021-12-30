<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuditTrail;
use App\Models\Department;
use App\Models\DepartmentContractor;
use App\User;

class FixerAuditDepartment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixer:audit_department';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try {
            \DB::beginTransaction();
            $audits = AuditTrail::with('auditUser','auditUser.department','auditUser.departmentContractor')
                                    ->chunk(1000, function ($audits) {
                                        foreach ($audits as $key => $audit) {
                                            $user = $audit->auditUser;
                                            if(!is_null($user)) {
                                                if($user->client_id == 1) {
                                                    $department = $user->department;
                                                } else {
                                                    $department = $user->departmentContractor;
                                                }
                                                $user_depart = $this->getDepartmentRecursiveNameAudit($department);
                                                $this->info("update department for user {$audit->user_name} : {$user_depart}");
                                                AuditTrail::where('id', $audit->id)->update(['department' => $user_depart]);
                                            }
                                        }

                                    }
                                  );
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            dd($e);
        }
    }

    public function getDepartmentRecursiveNameAudit($department) {

        $name = [];
        $name[] = $department->name;
        if (isset($department->parents) and !is_null($department->parents)) {
            $name = $this->deparmentRecursiveParent($department->parents, $name);

        }
        $name = array_reverse($name);
        return implode(" --- ",$name);
    }

    public function deparmentRecursiveParent($department, $name) {
        $name[] = $department->name;
        if (isset($department->parents) and !is_null($department->parents) ) {
            return $this->deparmentRecursiveParent($department->parents,$name);
        }
        return $name;
    }
}

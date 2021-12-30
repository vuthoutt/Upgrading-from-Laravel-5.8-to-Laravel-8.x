<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\ShineCompliance\JobRole;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateCallCentreUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:create_call_centre_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Call Centre User';

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
            $basic_role = JobRole::where('name', 'Basic User')->first();
            if (isset($basic_role)) {
                $user = User::create([
                    'username' => 'call_centre',
                    'first_name' => 'Call',
                    'last_name' => 'Centre',
                    'email' => 'call_centre@mail.co.uk',
                    'is_site_operative' => 0,
                    'client_id' => 1,
                    'department_id' => 999,
                    'password' => Hash::make('Shine123'),
                    'role' => $basic_role->id,
                    'is_call_centre_staff' => 1
                ]);
                if ($user) {
                    $user_id = $user->id;
                    $reference = 'ID' . $user_id;
                    User::where('id', $user_id)->update(['shine_reference' => $reference]);
                    Contact::create([
                        'user_id' => $user_id,
                        'job_title' => 'Call Centre'
                    ]);
                }
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            dd($e);
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\AsbestosUpdateMail;
use App\User;
use Illuminate\Console\Command;

class SendShineAsbestosUpdateEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_asbestos_update_email';

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
         $users =  User::where('is_locked',0)
                        ->whereRaw(" email is not null and email != '' and email REGEXP '^[A-Z0-9._%-+]+@[A-Z0-9.-]+\.[A-Z]{2,63}$' ")
                        ->groupBy('email')
                        ->pluck('email')
                        ->toArray();

         foreach (array_chunk($users, 10) as $bcc) {
             \Mail::bcc($bcc)
                 ->send(new AsbestosUpdateMail());
             // ...
         }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixerMigrateItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:migrate_item';

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
        $con_gsk_old = "mysql_gsk_old";
        $con_gsk_new = "mysql";


    }
}

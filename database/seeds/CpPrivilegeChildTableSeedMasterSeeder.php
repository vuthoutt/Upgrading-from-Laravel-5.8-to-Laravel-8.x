<?php

use Illuminate\Database\Seeder;

class CpPrivilegeChildTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_privilege_child')->delete();

        \DB::table('cp_privilege_child')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Asbestos Register Update',
                'privilege_id' => 19,
                'is_view' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
            'name' => 'Remedial Action(s) Required',
                'privilege_id' => 19,
                'is_view' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'General Summaries',
                'privilege_id' => 25,
                'is_view' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'Pre-Planned Maintenance Summary',
                'privilege_id' => 77,
                'is_view' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'Pre-Planned Maintenance Summary',
                'privilege_id' => 118,
                'is_view' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'name' => 'Pre-Planned Maintenance Summary',
                'privilege_id' => 73,
                'is_view' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'name' => 'Pre-Planned Maintenance Summary',
                'privilege_id' => 162,
                'is_view' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}

<?php

use Illuminate\Database\Seeder;

class TblAppAuditActionsTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('tbl_app_audit_actions')->delete();

        \DB::table('tbl_app_audit_actions')->insert(array (
            0 =>
            array (
                'id' => 1,
                'action_type' => 'Add',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'action_type' => 'Edit',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'action_type' => 'Decommissioned',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'action_type' => 'Not Assessed',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'action_type' => 'Release From Scope',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'action_type' => 'Recommissioned',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'action_type' => 'Snap',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'action_type' => 'Save',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 =>
            array (
                'id' => 9,
                'action_type' => 'Validated',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 =>
            array (
                'id' => 10,
                'action_type' => 'Backed up',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 =>
            array (
                'id' => 11,
                'action_type' => 'Abort',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 =>
            array (
                'id' => 12,
                'action_type' => 'Sent back',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 =>
            array (
                'id' => 13,
                'action_type' => 'Remove',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 =>
                array (
                    'id' => 14,
                    'action_type' => 'Restore',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            14 =>
                array (
                    'id' => 15,
                    'action_type' => 'Validation Changed',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            15 =>
                array (
                    'id' => 16,
                    'action_type' => 'Downloaded',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
        ));


    }
}

<?php

use Illuminate\Database\Seeder;

class TblProjectTypesTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('tbl_project_types')->delete();

        \DB::table('tbl_project_types')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'description' => 'Asbestos Survey Only',
                    'order' => 3,
                    'compliance_type' => 1,
                ),
            1 =>
                array (
                    'id' => 2,
                    'description' => 'Asbestos Remediation/Removal',
                    'order' => 2,
                    'compliance_type' => 1,
                ),
            2 =>
                array (
                    'id' => 3,
                    'description' => 'Asbestos Demolition',
                    'order' => 1,
                    'compliance_type' => 1,
                ),
            3 =>
                array (
                    'id' => 4,
                    'description' => 'Asbestos Analytical',
                    'order' => 0,
                    'compliance_type' => 1,
                ),
            4 =>
                array (
                    'id' => 5,
                    'description' => 'Fire Risk Assessment',
                    'order' => 8,
                    'compliance_type' => 2,
                ),
            5 =>
                array (
                    'id' => 6,
                    'description' => 'Fire Remedial Project',
                    'order' => 7,
                    'compliance_type' => 2,
                ),
            6 =>
                array (
                    'id' => 7,
                    'description' => 'Fire Independent Survey',
                    'order' => 6,
                    'compliance_type' => 2,
                ),
            11 =>
                array (
                    'id' => 12,
                    'description' => 'Fire Equipment Assessment',
                    'order' => 5,
                    'compliance_type' => 2,
                ),
            7 =>
                array (
                    'id' => 8,
                    'description' => 'Legionella Risk Assessment',
                    'order' => 9,
                    'compliance_type' => 3,
                ),
            8 =>
                array (
                    'id' => 9,
                    'description' => 'Water Testing Assessment',
                    'order' => 12,
                    'compliance_type' => 3,
                ),
            9 =>
                array (
                    'id' => 10,
                    'description' => 'Water Remedial Assessment',
                    'order' => 10,
                    'compliance_type' => 3,
                ),
            10 =>
                array (
                    'id' => 11,
                    'description' => 'Water Temperature Assessment',
                    'order' => 11,
                    'compliance_type' => 3,
                ),
        ));


    }
}

<?php

use Illuminate\Database\Seeder;

class CpAssessmentManagementInfoQuestionsTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_assessment_management_info_questions')->delete();

        \DB::table('cp_assessment_management_info_questions')->insert(array (
            0 =>
            array (
                'id' => 1,
                'description' => 'WCC/TMO',
                'pre_loaded' => NULL,
                'answer_type' => 1,
                'is_other' => 1,
                'order' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'description' => 'Management Extent',
                'pre_loaded' => NULL,
                'answer_type' => 1,
                'is_other' => 1,
                'order' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'description' => 'Details of Site Management',
                'pre_loaded' => NULL,
                'answer_type' => 1,
                'is_other' => 1,
                'order' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'description' => 'Person Managing Fire Safety in the Premises',
                'pre_loaded' => 'WCC are the “Responsible Person” for fire safety matters at this address. The Fire Safety Manager, (Housing), is the “Competent Person”.',
                'answer_type' => 2,
                'is_other' => 0,
                'order' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'description' => 'Main Fire Safety Legislation',
                'pre_loaded' => 'Regulatory Reform, (Fire Safety), Order 2005.',
                'answer_type' => 2,
                'is_other' => 0,
                'order' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'description' => 'Other Applicable Legislation',
                'pre_loaded' => 'Housing Act 2004.',
                'answer_type' => 2,
                'is_other' => 0,
                'order' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'description' => 'Main FS Guidance Used in this Assessment',
                'pre_loaded' => NULL,
                'answer_type' => 3,
                'is_other' => 1,
                'order' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'description' => 'Other FS Guidance Used in this Assessment',
                'pre_loaded' => NULL,
                'answer_type' => 3,
                'is_other' => 1,
                'order' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}

<?php

use Illuminate\Database\Seeder;

class CpAssessmentAnswersTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_assessment_answers')->delete();

        \DB::table('cp_assessment_answers')->insert(array (
            0 =>
            array (
                'id' => 1,
                'type' => '1',
                'description' => 'Yes',
                'score' => 1,
            ),
            1 =>
            array (
                'id' => 2,
                'type' => '1',
                'description' => 'No',
                'score' => 0,
            ),
            2 =>
            array (
                'id' => 3,
                'type' => '2',
                'description' => 'Yes',
                'score' => 1,
            ),
            3 =>
            array (
                'id' => 4,
                'type' => '2',
                'description' => 'No',
                'score' => 0,
            ),
            4 =>
            array (
                'id' => 5,
                'type' => '2',
                'description' => 'N/A',
                'score' => 0,
            ),
            5 =>
            array (
                'id' => 6,
                'type' => '3',
                'description' => 'Yes',
                'score' => 0,
            ),
            6 =>
            array (
                'id' => 7,
                'type' => '3',
                'description' => 'No',
                'score' => 1,
            ),
            7 =>
            array (
                'id' => 8,
                'type' => '3',
                'description' => 'N/A',
                'score' => 0,
            ),
            8 =>
            array (
                'id' => 9,
                'type' => '3',
                'description' => 'Unknown',
                'score' => 0,
            ),
            9 =>
                array (
                    'id' => 10,
                    'type' => '4',
                    'description' => 'Yes',
                    'score' => 1,
                ),
            10 =>
                array (
                    'id' => 11,
                    'type' => '4',
                    'description' => 'No',
                    'score' => 0,
                ),
            11 =>
                array (
                    'id' => 12,
                    'type' => '4',
                    'description' => 'Unknown',
                    'score' => 0,
                ),
        ));


    }
}

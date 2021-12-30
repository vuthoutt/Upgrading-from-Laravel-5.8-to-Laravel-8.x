<?php

use Illuminate\Database\Seeder;

class TblSurveyTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_survey_type')->delete();
        
        \DB::table('tbl_survey_type')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Management Survey',
                'order' => 0,
                'color' => '#FFA700',
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Refurbishment Survey',
                'order' => 1,
                'color' => '#A84400',
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Re-Inspection Survey',
                'order' => 2,
                'color' => '#FFD86C',
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Demolition Survey',
                'order' => 3,
                'color' => '#F96300',
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Management Survey – Partial',
                'order' => 4,
                'color' => '#3368FF',
            ),
            5 => 
            array (
                'id' => 6,
                'description' => 'Sample Survey',
                'order' => 5,
                'color' => '#f9e6c5',
            ),
        ));
        
        
    }
}
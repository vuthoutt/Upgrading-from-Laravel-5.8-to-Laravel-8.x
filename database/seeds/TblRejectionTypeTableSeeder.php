<?php

use Illuminate\Database\Seeder;

class TblRejectionTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_rejection_type')->delete();
        
        \DB::table('tbl_rejection_type')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Missing Scope',
                'type' => 3,
                'order' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'description' => 'Inaccurate Scope',
                'type' => 1,
                'order' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Poor Scope Quality',
                'type' => 4,
                'order' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Poor Recording of Data',
                'type' => 4,
                'order' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Poor Recapture of Data',
                'type' => 4,
                'order' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'description' => 'Missing Re-visit Details',
                'type' => 3,
                'order' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'description' => 'Missing Data',
                'type' => 3,
                'order' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'description' => 'Incorrect Data Capture',
                'type' => 2,
                'order' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'description' => 'Incorrect Photo Capture',
                'type' => 2,
                'order' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'description' => 'Poor Photo Quality',
                'type' => 4,
                'order' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'description' => 'Poor Quality Plans',
                'type' => 4,
                'order' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'description' => 'Incorrect Plans',
                'type' => 2,
                'order' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'description' => 'Incorrect Plan Information Recorded',
                'type' => 2,
                'order' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'description' => 'Inaccurate Extent',
                'type' => 1,
                'order' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'description' => 'Inaccurate Specific Location',
                'type' => 1,
                'order' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'description' => 'Inaccurate Sampling',
                'type' => 1,
                'order' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'description' => 'Inaccurate MAS',
                'type' => 1,
                'order' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'description' => 'Inaccurate PAS',
                'type' => 1,
                'order' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'description' => 'Inaccurate Recommendations',
                'type' => 1,
                'order' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'description' => 'Inaccurate Room/location Construction Details',
                'type' => 1,
                'order' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'description' => 'Inaccurate Room/location Void Investigation Details',
                'type' => 1,
                'order' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'description' => 'Poor Spelling',
                'type' => 4,
                'order' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'description' => 'Poor Grammar',
                'type' => 4,
                'order' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'description' => 'Poor Formatting',
                'type' => 4,
                'order' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'description' => 'Poor Punctuation',
                'type' => 4,
                'order' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'description' => 'Misuse of Comments within Room/location',
                'type' => 3,
                'order' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'description' => 'Misuse of Item Comments',
                'type' => 3,
                'order' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'description' => 'Missing Bulk Sample Certificate',
                'type' => 3,
                'order' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'description' => 'Missing Plans',
                'type' => 3,
                'order' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'description' => 'Missing Survey Data',
                'type' => 3,
                'order' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'description' => 'Client Error',
                'type' => 5,
                'order' => NULL,
            ),
        ));
        
        
    }
}
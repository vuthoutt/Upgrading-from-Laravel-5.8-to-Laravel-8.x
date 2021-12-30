<?php

use Illuminate\Database\Seeder;

class CpAssessmentAbortedReasonTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_assessment_aborted_reason')->delete();
        
        \DB::table('cp_assessment_aborted_reason')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'Access Denied by Tenant',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            1 => 
            array (
                'id' => 2,
            'description' => 'Animal(s) Preventing Safe Access',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'Doorway Boarded',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'Excessive Storage',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'Extensive Waste',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            5 => 
            array (
                'id' => 6,
                'description' => 'Hazardous Area',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            6 => 
            array (
                'id' => 7,
                'description' => 'No Keys',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            7 => 
            array (
                'id' => 8,
                'description' => 'Structurally Unsafe',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            8 => 
            array (
                'id' => 9,
                'description' => 'Unable to Access with Issued Keys',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            9 => 
            array (
                'id' => 10,
                'description' => 'Unsafe Access',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            10 => 
            array (
                'id' => 11,
                'description' => 'Assessment Cancelled',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            11 => 
            array (
                'id' => 12,
                'description' => 'Personnel Change ',
                'is_deleted' => 0,
                'classification' => 2,
            ),
            12 => 
            array (
                'id' => 25,
                'description' => 'Access Denied by Tenant',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            13 => 
            array (
                'id' => 26,
            'description' => 'Animal(s) Preventing Safe Access',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            14 => 
            array (
                'id' => 27,
                'description' => 'Doorway Boarded',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            15 => 
            array (
                'id' => 28,
                'description' => 'Excessive Storage',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            16 => 
            array (
                'id' => 29,
                'description' => 'Extensive Waste',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            17 => 
            array (
                'id' => 30,
                'description' => 'Hazardous Area',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            18 => 
            array (
                'id' => 31,
                'description' => 'No Keys',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            19 => 
            array (
                'id' => 32,
                'description' => 'Structurally Unsafe',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            20 => 
            array (
                'id' => 33,
                'description' => 'Unable to Access with Issued Keys',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            21 => 
            array (
                'id' => 34,
                'description' => 'Unsafe Access',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            22 => 
            array (
                'id' => 35,
                'description' => 'Assessment Cancelled',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            23 => 
            array (
                'id' => 36,
                'description' => 'Personnel Change ',
                'is_deleted' => 0,
                'classification' => 4,
            ),
            24 => 
            array (
                'id' => 37,
                'description' => 'Access Denied by Tenant',
                'is_deleted' => 0,
                'classification' => 5,
            ),
            25 => 
            array (
                'id' => 38,
            'description' => 'Animal(s) Preventing Safe Access',
                'is_deleted' => 0,
                'classification' => 5,
            ),
            26 => 
            array (
                'id' => 39,
                'description' => 'Doorway Boarded',
                'is_deleted' => 0,
                'classification' => 5,
            ),
            27 => 
            array (
                'id' => 40,
                'description' => 'Excessive Storage',
                'is_deleted' => 0,
                'classification' => 5,
            ),
            28 => 
            array (
                'id' => 41,
                'description' => 'Extensive Waste',
                'is_deleted' => 0,
                'classification' => 5,
            ),
            29 => 
            array (
                'id' => 42,
                'description' => 'Hazardous Area',
                'is_deleted' => 0,
                'classification' => 5,
            ),
            30 => 
            array (
                'id' => 43,
                'description' => 'No Keys',
                'is_deleted' => 0,
                'classification' => 5,
            ),
            31 => 
            array (
                'id' => 44,
                'description' => 'Structurally Unsafe',
                'is_deleted' => 0,
                'classification' => 5,
            ),
            32 => 
            array (
                'id' => 45,
                'description' => 'Unable to Access with Issued Keys',
                'is_deleted' => 0,
                'classification' => 5,
            ),
            33 => 
            array (
                'id' => 46,
                'description' => 'Unsafe Access',
                'is_deleted' => 0,
                'classification' => 5,
            ),
            34 => 
            array (
                'id' => 47,
                'description' => 'Assessment Cancelled',
                'is_deleted' => 0,
                'classification' => 5,
            ),
            35 => 
            array (
                'id' => 48,
                'description' => 'Personnel Change ',
                'is_deleted' => 0,
                'classification' => 5,
            ),
        ));
        
        
    }
}
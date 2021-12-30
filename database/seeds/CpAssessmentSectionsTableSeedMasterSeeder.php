<?php

use Illuminate\Database\Seeder;

class CpAssessmentSectionsTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_assessment_sections')->delete();
        
        \DB::table('cp_assessment_sections')->insert(array (
            0 => 
            array (
                'id' => 27,
                'description' => 'Management of Risk',
                'order' => '0',
                'type' => '4',
                'parent_id' => 0,
                'score' => 0,
            ),
            1 => 
            array (
                'id' => 28,
                'description' => 'The Written Scheme',
                'order' => '0',
                'type' => '4',
                'parent_id' => 0,
                'score' => 0,
            ),
            2 => 
            array (
                'id' => 29,
                'description' => 'Design and Construction',
                'order' => '0',
                'type' => '4',
                'parent_id' => 0,
                'score' => 0,
            ),
            3 => 
            array (
                'id' => 30,
                'description' => 'Cold Water Systems',
                'order' => '0',
                'type' => '4',
                'parent_id' => 0,
                'score' => 0,
            ),
            4 => 
            array (
                'id' => 31,
                'description' => 'Hot Water Systems',
                'order' => '0',
                'type' => '4',
                'parent_id' => 0,
                'score' => 0,
            ),
            5 => 
            array (
                'id' => 32,
                'description' => 'Operation and Maintenance',
                'order' => '0',
                'type' => '4',
                'parent_id' => 0,
                'score' => 0,
            ),
            6 => 
            array (
                'id' => 33,
                'description' => 'Water Treatment Programme',
                'order' => '0',
                'type' => '4',
                'parent_id' => 0,
                'score' => 0,
            ),
            7 => 
            array (
                'id' => 34,
                'description' => 'Monitoring',
                'order' => '0',
                'type' => '4',
                'parent_id' => 0,
                'score' => 0,
            ),
            8 => 
            array (
                'id' => 35,
                'description' => 'Management of Risk',
                'order' => '1',
                'type' => '4',
                'parent_id' => 27,
                'score' => 0,
            ),
            9 => 
            array (
                'id' => 36,
                'description' => 'The Written Scheme',
                'order' => '1',
                'type' => '4',
                'parent_id' => 28,
                'score' => 0,
            ),
            10 => 
            array (
                'id' => 37,
                'description' => 'Design and Construction',
                'order' => '1',
                'type' => '4',
                'parent_id' => 29,
                'score' => 0,
            ),
            11 => 
            array (
                'id' => 38,
                'description' => 'Cold Water Systems',
                'order' => '1',
                'type' => '4',
                'parent_id' => 30,
                'score' => 0,
            ),
            12 => 
            array (
                'id' => 39,
                'description' => 'Hot Water Systems',
                'order' => '1',
                'type' => '4',
                'parent_id' => 31,
                'score' => 0,
            ),
            13 => 
            array (
                'id' => 40,
                'description' => 'Operation and Maintenance',
                'order' => '1',
                'type' => '4',
                'parent_id' => 32,
                'score' => 0,
            ),
            14 => 
            array (
                'id' => 41,
                'description' => 'Water Treatment Programme',
                'order' => '1',
                'type' => '4',
                'parent_id' => 33,
                'score' => 0,
            ),
            15 => 
            array (
                'id' => 42,
                'description' => 'Temperature',
                'order' => '1',
                'type' => '4',
                'parent_id' => 34,
                'score' => 0,
            ),
            16 => 
            array (
                'id' => 43,
                'description' => 'Biocides',
                'order' => '2',
                'type' => '4',
                'parent_id' => 34,
                'score' => 0,
            ),
            17 => 
            array (
                'id' => 44,
                'description' => 'General',
                'order' => '3',
                'type' => '4',
                'parent_id' => 34,
                'score' => 0,
            ),
            18 => 
            array (
                'id' => 45,
                'description' => 'Microbiological',
                'order' => '4',
                'type' => '4',
                'parent_id' => 34,
                'score' => 0,
            ),
            19 => 
            array (
                'id' => 46,
                'description' => 'Cleaning and Disinfection',
                'order' => '5',
                'type' => '4',
                'parent_id' => 34,
                'score' => 0,
            ),
            20 => 
            array (
                'id' => 47,
                'description' => 'Elimination or Reduction of Fire Hazards',
                'order' => '1',
                'type' => '2',
                'parent_id' => 0,
                'score' => 0,
            ),
            21 => 
            array (
                'id' => 48,
                'description' => 'General Fire Protection Measures',
                'order' => '2',
                'type' => '2',
                'parent_id' => 0,
                'score' => 0,
            ),
            22 => 
            array (
                'id' => 49,
                'description' => 'Fire Safety Management',
                'order' => '3',
                'type' => '2',
                'parent_id' => 0,
                'score' => 0,
            ),
            23 => 
            array (
                'id' => 50,
                'description' => 'Additional Issues',
                'order' => '4',
                'type' => '2',
                'parent_id' => 0,
                'score' => 0,
            ),
            24 => 
            array (
                'id' => 51,
            'description' => 'Electrical Ignition Sources (A)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 47,
                'score' => 0,
            ),
            25 => 
            array (
                'id' => 52,
            'description' => 'Smoking Policies (B)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 47,
                'score' => 0,
            ),
            26 => 
            array (
                'id' => 53,
            'description' => 'Arson (C)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 47,
                'score' => 0,
            ),
            27 => 
            array (
                'id' => 54,
            'description' => 'Space Heating (D)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 47,
                'score' => 0,
            ),
            28 => 
            array (
                'id' => 55,
            'description' => 'Cooking (E)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 47,
                'score' => 0,
            ),
            29 => 
            array (
                'id' => 56,
            'description' => 'Lightning (F)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 47,
                'score' => 0,
            ),
            30 => 
            array (
                'id' => 57,
            'description' => 'House-Keeping (G)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 47,
                'score' => 0,
            ),
            31 => 
            array (
                'id' => 58,
            'description' => 'Contractors (H)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 47,
                'score' => 0,
            ),
            32 => 
            array (
                'id' => 59,
            'description' => 'Dangerous Substances (I)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 47,
                'score' => 0,
            ),
            33 => 
            array (
                'id' => 60,
            'description' => 'Other Significant Hazards (J)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 47,
                'score' => 0,
            ),
            34 => 
            array (
                'id' => 61,
            'description' => 'Means of Escape (K)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 48,
                'score' => 0,
            ),
            35 => 
            array (
                'id' => 62,
            'description' => 'Flat Entrance Doors (L)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 48,
                'score' => 0,
            ),
            36 => 
            array (
                'id' => 63,
            'description' => 'Common Area Fire Doors (M)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 48,
                'score' => 0,
            ),
            37 => 
            array (
                'id' => 64,
            'description' => 'Emergency + Lighting (N)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 48,
                'score' => 0,
            ),
            38 => 
            array (
                'id' => 65,
            'description' => 'Fire Safety Signs and Notices (O)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 48,
                'score' => 0,
            ),
            39 => 
            array (
                'id' => 66,
            'description' => 'Means of Giving Warning in Case of Fire (P)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 48,
                'score' => 0,
            ),
            40 => 
            array (
                'id' => 67,
            'description' => 'Limiting Fire Spread (Q)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 48,
                'score' => 0,
            ),
            41 => 
            array (
                'id' => 68,
            'description' => 'Fire Extinguishing Appliances (R)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 48,
                'score' => 0,
            ),
            42 => 
            array (
                'id' => 69,
            'description' => 'Other Fire Safety Systems and Equipment (S)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 48,
                'score' => 0,
            ),
            43 => 
            array (
                'id' => 70,
            'description' => 'Procedures and Arrangements (T)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 49,
                'score' => 0,
            ),
            44 => 
            array (
                'id' => 71,
            'description' => 'Training and Drills (U)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 49,
                'score' => 0,
            ),
            45 => 
            array (
                'id' => 72,
            'description' => 'Testing and Maintenance (V)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 49,
                'score' => 0,
            ),
            46 => 
            array (
                'id' => 73,
            'description' => 'Records (W)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 49,
                'score' => 0,
            ),
            47 => 
            array (
                'id' => 74,
            'description' => 'Electrical Services (X)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 50,
                'score' => 0,
            ),
            48 => 
            array (
                'id' => 75,
            'description' => 'Gas Services (Y)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 50,
                'score' => 0,
            ),
            49 => 
            array (
                'id' => 76,
            'description' => 'Other Issues (Z)',
                'order' => '0',
                'type' => '2',
                'parent_id' => 50,
                'score' => 0,
            ),
        ));
        
        
    }
}
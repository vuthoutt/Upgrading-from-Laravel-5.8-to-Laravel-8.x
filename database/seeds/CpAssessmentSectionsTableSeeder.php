<?php

use Illuminate\Database\Seeder;

class CpAssessmentSectionsTableSeeder extends Seeder
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
            50 => 
            array (
                'id' => 77,
                'description' => 'Accidents, Incidents and Near Misses',
                'order' => '1',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            51 => 
            array (
                'id' => 78,
                'description' => 'Asbestos',
                'order' => '2',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            52 => 
            array (
                'id' => 79,
                'description' => 'Communication and Training',
                'order' => '3',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            53 => 
            array (
                'id' => 80,
                'description' => 'Contingency/Emergency Plans',
                'order' => '4',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            54 => 
            array (
                'id' => 81,
                'description' => 'Control of Contractors',
                'order' => '5',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            55 => 
            array (
                'id' => 82,
                'description' => 'Display Screen Equipment',
                'order' => '6',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            56 => 
            array (
                'id' => 83,
                'description' => 'Driving for Work',
                'order' => '7',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            57 => 
            array (
                'id' => 84,
                'description' => 'Electrical Installation',
                'order' => '8',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            58 => 
            array (
                'id' => 85,
                'description' => ' Environment',
                'order' => '9',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            59 => 
            array (
                'id' => 86,
                'description' => 'Falls and Falling Objects',
                'order' => '10',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            60 => 
            array (
                'id' => 87,
                'description' => 'Fire Safety',
                'order' => '11',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            61 => 
            array (
                'id' => 88,
                'description' => 'Gas',
                'order' => '12',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            62 => 
            array (
                'id' => 89,
            'description' => 'Hazardous Substances (COSHH)',
                'order' => '13',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            63 => 
            array (
                'id' => 90,
            'description' => 'Health Surveillance (Noise/Respiratory/HAVS)',
                'order' => '14',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            64 => 
            array (
                'id' => 91,
                'description' => 'Housekeeping',
                'order' => '15',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            65 => 
            array (
                'id' => 92,
                'description' => 'Infection Control',
                'order' => '16',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            66 => 
            array (
                'id' => 93,
                'description' => 'Kitchen',
                'order' => '17',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            67 => 
            array (
                'id' => 94,
                'description' => 'Legionnaire\'s Disease',
                'order' => '18',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            68 => 
            array (
                'id' => 95,
                'description' => 'Lifting Operations & Lifting Equipment',
                'order' => '19',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            69 => 
            array (
                'id' => 96,
                'description' => 'Manual Handling',
                'order' => '20',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            70 => 
            array (
                'id' => 97,
                'description' => 'Personal Protection Equipment',
                'order' => '21',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            71 => 
            array (
                'id' => 98,
                'description' => 'Pest Control',
                'order' => '22',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            72 => 
            array (
                'id' => 99,
                'description' => 'Policies',
                'order' => '23',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            73 => 
            array (
                'id' => 100,
                'description' => 'Risk Assessment',
                'order' => '24',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            74 => 
            array (
                'id' => 101,
                'description' => 'Waste Management',
                'order' => '25',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            75 => 
            array (
                'id' => 102,
                'description' => 'Welfare',
                'order' => '26',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            76 => 
            array (
                'id' => 103,
                'description' => 'Work Equipment',
                'order' => '27',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            77 => 
            array (
                'id' => 104,
                'description' => 'Workplace Inspections and Room Inspections',
                'order' => '28',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            78 => 
            array (
                'id' => 105,
                'description' => 'Other Hazards',
                'order' => '29',
                'type' => '5',
                'parent_id' => 0,
                'score' => 0,
            ),
            79 => 
            array (
                'id' => 106,
            'description' => '(A) Accidents, Incidents and Near Misses',
                'order' => '1',
                'type' => '5',
                'parent_id' => 77,
                'score' => 0,
            ),
            80 => 
            array (
                'id' => 107,
            'description' => '(B) Asbestos',
                'order' => '2',
                'type' => '5',
                'parent_id' => 78,
                'score' => 0,
            ),
            81 => 
            array (
                'id' => 108,
            'description' => '(C) Communication and Training',
                'order' => '3',
                'type' => '5',
                'parent_id' => 79,
                'score' => 0,
            ),
            82 => 
            array (
                'id' => 109,
            'description' => '(D) Contingency/Emergency Plans',
                'order' => '4',
                'type' => '5',
                'parent_id' => 80,
                'score' => 0,
            ),
            83 => 
            array (
                'id' => 110,
            'description' => '(E) Control of Contractors',
                'order' => '5',
                'type' => '5',
                'parent_id' => 81,
                'score' => 0,
            ),
            84 => 
            array (
                'id' => 111,
            'description' => '(F) Display Screen Equipment',
                'order' => '6',
                'type' => '5',
                'parent_id' => 82,
                'score' => 0,
            ),
            85 => 
            array (
                'id' => 112,
            'description' => '(G) Driving for Work',
                'order' => '7',
                'type' => '5',
                'parent_id' => 83,
                'score' => 0,
            ),
            86 => 
            array (
                'id' => 113,
            'description' => '(H) Electrical Installation',
                'order' => '8',
                'type' => '5',
                'parent_id' => 84,
                'score' => 0,
            ),
            87 => 
            array (
                'id' => 114,
            'description' => '(I) Environment',
                'order' => '9',
                'type' => '5',
                'parent_id' => 85,
                'score' => 0,
            ),
            88 => 
            array (
                'id' => 115,
            'description' => '(J) Falls and Falling Objects',
                'order' => '10',
                'type' => '5',
                'parent_id' => 86,
                'score' => 0,
            ),
            89 => 
            array (
                'id' => 116,
            'description' => '(K) Fire Safety',
                'order' => '11',
                'type' => '5',
                'parent_id' => 87,
                'score' => 0,
            ),
            90 => 
            array (
                'id' => 117,
            'description' => '(L) Gas',
                'order' => '12',
                'type' => '5',
                'parent_id' => 88,
                'score' => 0,
            ),
            91 => 
            array (
                'id' => 118,
            'description' => '(M) Hazardous Substances (COSHH)',
                'order' => '13',
                'type' => '5',
                'parent_id' => 89,
                'score' => 0,
            ),
            92 => 
            array (
                'id' => 119,
            'description' => '(N) Health Surveillance (Noise/Respiratory/HAVS)',
                'order' => '14',
                'type' => '5',
                'parent_id' => 90,
                'score' => 0,
            ),
            93 => 
            array (
                'id' => 120,
            'description' => '(O) Housekeeping',
                'order' => '15',
                'type' => '5',
                'parent_id' => 91,
                'score' => 0,
            ),
            94 => 
            array (
                'id' => 121,
            'description' => '(P) Infection Control',
                'order' => '16',
                'type' => '5',
                'parent_id' => 92,
                'score' => 0,
            ),
            95 => 
            array (
                'id' => 122,
            'description' => '(Q) Kitchen',
                'order' => '17',
                'type' => '5',
                'parent_id' => 93,
                'score' => 0,
            ),
            96 => 
            array (
                'id' => 123,
            'description' => '(R) Legionnaire\'s Disease',
                'order' => '18',
                'type' => '5',
                'parent_id' => 94,
                'score' => 0,
            ),
            97 => 
            array (
                'id' => 124,
            'description' => '(S) Lifting Operations & Lifting Equipment',
                'order' => '19',
                'type' => '5',
                'parent_id' => 95,
                'score' => 0,
            ),
            98 => 
            array (
                'id' => 125,
            'description' => '(T) Manual Handling',
                'order' => '20',
                'type' => '5',
                'parent_id' => 96,
                'score' => 0,
            ),
            99 => 
            array (
                'id' => 126,
            'description' => '(U) Personal Protection Equipment',
                'order' => '21',
                'type' => '5',
                'parent_id' => 97,
                'score' => 0,
            ),
            100 => 
            array (
                'id' => 127,
            'description' => '(V) Pest Control',
                'order' => '22',
                'type' => '5',
                'parent_id' => 98,
                'score' => 0,
            ),
            101 => 
            array (
                'id' => 128,
            'description' => '(W) Policies',
                'order' => '23',
                'type' => '5',
                'parent_id' => 99,
                'score' => 0,
            ),
            102 => 
            array (
                'id' => 129,
            'description' => '(X) Risk Assessment',
                'order' => '24',
                'type' => '5',
                'parent_id' => 100,
                'score' => 0,
            ),
            103 => 
            array (
                'id' => 130,
            'description' => '(Y) Waste Management',
                'order' => '25',
                'type' => '5',
                'parent_id' => 101,
                'score' => 0,
            ),
            104 => 
            array (
                'id' => 131,
            'description' => '(Z) Welfare',
                'order' => '26',
                'type' => '5',
                'parent_id' => 102,
                'score' => 0,
            ),
            105 => 
            array (
                'id' => 132,
            'description' => '(AA) Work Equipment',
                'order' => '27',
                'type' => '5',
                'parent_id' => 103,
                'score' => 0,
            ),
            106 => 
            array (
                'id' => 133,
            'description' => '(AB) Workplace Inspections and Room Inspections',
                'order' => '28',
                'type' => '5',
                'parent_id' => 104,
                'score' => 0,
            ),
            107 => 
            array (
                'id' => 134,
            'description' => '(AC) Other Hazards',
                'order' => '29',
                'type' => '5',
                'parent_id' => 105,
                'score' => 0,
            ),
        ));
        
        
    }
}
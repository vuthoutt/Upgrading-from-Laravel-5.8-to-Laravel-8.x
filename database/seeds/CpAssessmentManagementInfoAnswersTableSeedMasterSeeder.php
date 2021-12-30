<?php

use Illuminate\Database\Seeder;

class CpAssessmentManagementInfoAnswersTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_assessment_management_info_answers')->delete();

        \DB::table('cp_assessment_management_info_answers')->insert(array (
            0 =>
            array (
                'id' => 1,
                'question_id' => 1,
                'description' => 'WCC',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'question_id' => 1,
                'description' => 'TMO',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'question_id' => 1,
                'description' => 'Other',
                'order' => 1,
                'other' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'question_id' => 2,
                'description' => 'This address in under the full control of WCC, who are responsible for all fire safety matters',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'question_id' => 2,
                'description' => 'This block is managed by a TMO, who have responsibility for the day to day management. However, WCC are responsible for the provision and maintenance of FS systems and equipment.',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'question_id' => 3,
                'description' => 'This property is a general needs block. There are no employees permanently onsite, although cleaning and maintenance staff visit the block regularly. Monthly H & S inspections are carried out by the Area Compliance Team.',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'question_id' => 3,
                'description' => 'This property is a general needs block. A concierge is on duty in the reception area on a part time basis. There are no employees permanently onsite, although cleaning and maintenance staff visit the block regularly. Monthly H & S inspections are carried out by the Area Compliance Team.',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'question_id' => 3,
                'description' => 'This property is a sheltered scheme. The scheme manager lives on site. However, she/he is not on duty 24/7. The scheme manager is supported by cleaning and maintenance staff.',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 =>
            array (
                'id' => 9,
                'question_id' => 3,
                'description' => 'This property is a sheltered scheme. The manager for this scheme does not live on site but does make regular site visits. The scheme manager is supported by cleaning and maintenance staff.',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 =>
            array (
                'id' => 10,
                'question_id' => 7,
                'description' => 'LACoRS Guide 2008.',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 =>
            array (
                'id' => 11,
                'question_id' => 7,
                'description' => 'Fire Safety in Purpose Built Blocks of Flats 2012.',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 =>
            array (
                'id' => 12,
                'question_id' => 7,
                'description' => 'NFCC Guide to Fire Safety in Specialised Housing.',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 =>
            array (
                'id' => 13,
                'question_id' => 7,
                'description' => 'Other',
                'order' => 1,
                'other' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 =>
            array (
                'id' => 14,
                'question_id' => 8,
                'description' => 'Approved Document B of Building Regulations.',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 =>
            array (
                'id' => 15,
                'question_id' => 8,
                'description' => 'CLG Guide appropriate to this building',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 =>
            array (
                'id' => 16,
                'question_id' => 8,
                'description' => 'BS 5839-1: 2017 - Fire detection and fire alarm system for buildings',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 =>
            array (
                'id' => 17,
                'question_id' => 8,
            'description' => 'BS 5839-6: 2019 - Fire Detection and Fire Alarm Systems for Buildings, (Residential)',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 =>
            array (
                'id' => 18,
                'question_id' => 8,
                'description' => 'BS 5266-1: 2016 - Emergency Lighting - Code of practice for the emergency lighting of premises',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 =>
            array (
                'id' => 19,
                'question_id' => 8,
                'description' => 'BS EN 3: Portable fire extinguishers',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 =>
            array (
                'id' => 20,
                'question_id' => 8,
                'description' => 'BS 5499-10: 2014 - Guidance for the selection and use of safety signs and ﬁre safety notices',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 =>
            array (
                'id' => 21,
                'question_id' => 8,
                'description' => 'BS EN 62305 Lighting Protection Systems',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 =>
            array (
                'id' => 22,
                'question_id' => 8,
                'description' => 'BS 9990 Dry rising mains',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 =>
            array (
                'id' => 23,
                'question_id' => 8,
                'description' => 'BS EN12101 Smoke & Heat Control Systems',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 =>
            array (
                'id' => 24,
                'question_id' => 8,
                'description' => 'BS 8629 Evacuation Alert systems',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 =>
            array (
                'id' => 25,
                'question_id' => 8,
                'description' => 'BS 9251 Sprinklers',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 =>
            array (
                'id' => 26,
                'question_id' => 8,
                'description' => 'BS 7671  Electrical Regulations',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 =>
            array (
                'id' => 27,
                'question_id' => 8,
            'description' => 'Gas Safety (Installation and Use) Regulations 1998 (GSIUR) as amended',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 =>
            array (
                'id' => 28,
                'question_id' => 8,
                'description' => 'PAS79-2:2020 - Housing- Fire Risk Assessment',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 =>
                array (
                    'id' => 29,
                    'question_id' => 2,
                    'description' => 'Other',
                    'order' => 1,
                    'other' => 1,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            29 =>
                array (
                    'id' => 30,
                    'question_id' => 3,
                    'description' => 'Other',
                    'order' => 1,
                    'other' => 1,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            30 =>
                array (
                    'id' => 31,
                    'question_id' => 8,
                    'description' => 'Other',
                    'order' => 1,
                    'other' => 1,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
        ));


    }
}

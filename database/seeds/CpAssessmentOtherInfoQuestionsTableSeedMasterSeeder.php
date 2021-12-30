<?php

use Illuminate\Database\Seeder;

class CpAssessmentOtherInfoQuestionsTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_assessment_other_info_questions')->delete();

        \DB::table('cp_assessment_other_info_questions')->insert(array (
            0 =>
            array (
                'id' => 1,
                'description' => 'Areas of the building where access was not possible',
                'pre_loaded' => null,
                'answer_type' => 2,
                'order' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'description' => 'Block accessibility for LFB',
                'pre_loaded' => '“Fire appliances attending an incident at this address would park in XXXXXXXXXX, immediately outside the main door into the building. This door is secured by an electromagnetic lock, which can be raised using the residents fob, or a standard drop key, (override provided), both of which are carried on all frontline fire appliances based in the borough of Westminster and in surrounding fire stations.”

“Fire appliances attending an incident at this address would park in XXXXXXXXXX, immediately outside the main door into the building. This door is secured by a single latch. No override facility is provided. However, attending fire crews would be easily be able to force entry using standard, “breaking in equipment”, which is carried on all frontline fire appliances.”

“Fire appliances attending an incident at this address would park in XXXXXXXXXX, immediately outside the main door into the building. There are no access controls into the building.”',
                'answer_type' => 2,
                'order' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'description' => 'Fire loss since previous FRA',
                'pre_loaded' => '“The assessor was not aware of any fire losses since the previous FRA was carried out”.',
                'answer_type' => 2,
                'order' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}

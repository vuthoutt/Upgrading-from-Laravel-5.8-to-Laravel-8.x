<?php

use Illuminate\Database\Seeder;

class CpAssessmentQuestionsTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_assessment_questions')->delete();
        
        \DB::table('cp_assessment_questions')->insert(array (
            0 => 
            array (
                'id' => 116,
                'section_id' => 35,
                'description' => 'Has there been any changes to water system?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '3',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            1 => 
            array (
                'id' => 117,
                'section_id' => 35,
                'description' => 'Has there been any changes to the way occupants use the water system?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '3',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            2 => 
            array (
                'id' => 118,
                'section_id' => 35,
                'description' => 'Has there been any changes to the property where the water system is installed?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '3',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            3 => 
            array (
                'id' => 119,
                'section_id' => 35,
                'description' => 'Is there new information available about risks or control measures?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '3',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            4 => 
            array (
                'id' => 120,
                'section_id' => 35,
                'description' => 'Has there been a previous case if Legionnaires Disease associated with the water system?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '3',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            5 => 
            array (
                'id' => 121,
                'section_id' => 35,
                'description' => 'Has advice been given to occupants about the risks of Legionnaires Disease and how the risks can be reduced?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            6 => 
            array (
                'id' => 122,
                'section_id' => 36,
                'description' => 'Is there a written scheme for controlling the risk from exposure to legionella bacteria?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            7 => 
            array (
                'id' => 123,
                'section_id' => 36,
            'description' => 'Does the scheme contain an up-to-date plan of the system (a schematic is okay)?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            8 => 
            array (
                'id' => 124,
                'section_id' => 36,
                'description' => 'Does the plan show and identify all system plant, for example water softeners, filters, strainers, pumps, nonreturn valves and all outlets, for example showers, wash-hand basins etc?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            9 => 
            array (
                'id' => 125,
                'section_id' => 36,
                'description' => 'Does the plan show and identify all standby equipment, for example spare pumps?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            10 => 
            array (
                'id' => 126,
                'section_id' => 36,
                'description' => 'Does the plan show and identify all associated pipework and piping routes?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            11 => 
            array (
                'id' => 127,
                'section_id' => 36,
                'description' => 'Does the plan show and identify all associated storage and header tanks?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            12 => 
            array (
                'id' => 128,
                'section_id' => 36,
                'description' => 'Does the plan show and identify the origin of water supply?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            13 => 
            array (
                'id' => 129,
                'section_id' => 36,
                'description' => 'Does the plan show and identify any parts that may be out of use temporarily?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            14 => 
            array (
                'id' => 130,
                'section_id' => 36,
                'description' => 'Does the scheme contain instructions for the operation of the system?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            15 => 
            array (
                'id' => 131,
                'section_id' => 36,
                'description' => 'Does the scheme contain details of the precautions to be taken to control the risk of exposure to legionella bacteria?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            16 => 
            array (
                'id' => 132,
                'section_id' => 36,
            'description' => 'Does the scheme contain details of the checks that are to be carried out (and their frequency) to ensure that the scheme is effective?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            17 => 
            array (
                'id' => 133,
                'section_id' => 37,
                'description' => 'If you are fitting a new system, do any of the materials or fittings used in the water systems support the growth of micro-organisms?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '3',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            18 => 
            array (
                'id' => 134,
                'section_id' => 37,
                'description' => 'Are low corrosion materials used?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            19 => 
            array (
                'id' => 135,
                'section_id' => 37,
            'description' => 'If fitted, are thermostatic mixing valves (TMVs) sited as close as possible to the point of use?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            20 => 
            array (
                'id' => 136,
                'section_id' => 38,
                'description' => 'Are low use outlets installed upstream of higher use outlets?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            21 => 
            array (
                'id' => 137,
                'section_id' => 38,
                'description' => 'Has cold water storage been assessed and minimised, i.e. holds enough for a day’s use only?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            22 => 
            array (
                'id' => 138,
                'section_id' => 38,
            'description' => 'Is piping insulated and kept away from heat sources (where possible)?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            23 => 
            array (
                'id' => 139,
                'section_id' => 38,
            'description' => 'Is the cold water tank fitted with a cover and insect screen(s) on any pipework open to the atmosphere?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            24 => 
            array (
                'id' => 140,
                'section_id' => 38,
                'description' => 'Is the cold water tank located in a cool place and protected from extremes of temperature?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            25 => 
            array (
                'id' => 141,
                'section_id' => 38,
                'description' => 'Is the cold water tank accessible?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            26 => 
            array (
                'id' => 142,
                'section_id' => 39,
                'description' => 'Does the calorifier storage capacity meet normal daily fluctuations in hot water use while maintaining a supply temperature of at least 50°C?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            27 => 
            array (
                'id' => 143,
                'section_id' => 39,
                'description' => 'Are the hot water distribution pipes insulated?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            28 => 
            array (
                'id' => 144,
                'section_id' => 39,
                'description' => 'If more than one calorifier is used, are they connected in parallel?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            29 => 
            array (
                'id' => 145,
                'section_id' => 39,
            'description' => 'Does the calorifier(s) have a drain valve?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            30 => 
            array (
                'id' => 146,
                'section_id' => 39,
            'description' => 'Does the calorifier(s) have a temperature gauge on the inlet and outlet?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            31 => 
            array (
                'id' => 147,
                'section_id' => 39,
            'description' => 'Does the calorifier(s) have an access panel?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            32 => 
            array (
                'id' => 148,
                'section_id' => 40,
                'description' => 'If the water supplied to your building is not mains supply, has the water been pre-treated to make sure it is of the same quality as the mains?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            33 => 
            array (
                'id' => 149,
                'section_id' => 40,
                'description' => 'Is the entire contents of the calorifier, including the base, heated to 60°C for an hour each day, for example by using a shunt pump?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            34 => 
            array (
                'id' => 150,
                'section_id' => 40,
                'description' => 'Are all outlets that are no longer required cut back as far as the main pipe run?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            35 => 
            array (
                'id' => 151,
                'section_id' => 40,
                'description' => 'Are there arrangements to incorporate standby equipment, for example calorifiers, pumps, into routine use?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            36 => 
            array (
                'id' => 152,
                'section_id' => 40,
                'description' => 'If little used outlets have not been removed, are there arrangements in place to either flush them through on at least a weekly basis or carry out a safe purge of stagnant water before use?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            37 => 
            array (
                'id' => 153,
                'section_id' => 40,
                'description' => 'If thermostatic mixing valves are fitted, are they included in the maintenance schedule?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            38 => 
            array (
                'id' => 154,
                'section_id' => 41,
                'description' => 'Is there a water treatment programme in place?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            39 => 
            array (
                'id' => 155,
                'section_id' => 41,
                'description' => 'Is temperature used as a control method?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            40 => 
            array (
                'id' => 156,
                'section_id' => 41,
                'description' => 'Are biocides used as a control method?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            41 => 
            array (
                'id' => 157,
                'section_id' => 42,
            'description' => 'If there is a risk of scalding (for example where the young, elderly or disabled may use the outlets), are thermostatic mixing valves fitted?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            42 => 
            array (
                'id' => 158,
                'section_id' => 42,
                'description' => 'Is the temperature of sentinel hot and cold water outlets checked on a monthly basis?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            43 => 
            array (
                'id' => 159,
                'section_id' => 42,
                'description' => 'If fitted, is the temperature of the water supply to thermostatic mixing valves checked on a monthly basis?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            44 => 
            array (
                'id' => 160,
                'section_id' => 42,
                'description' => 'Is the temperature of the water in the outlet and return pipes of the calorifier checked on a monthly basis?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            45 => 
            array (
                'id' => 161,
                'section_id' => 42,
                'description' => 'Is the temperature of the incoming cold water supply checked on a six-monthly basis?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            46 => 
            array (
                'id' => 162,
                'section_id' => 42,
                'description' => 'Is the temperature of a representative number of hot and cold water outlets checked on an annual basis?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            47 => 
            array (
                'id' => 163,
                'section_id' => 43,
                'description' => 'Is the control level required known and recorded in the operations manual?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            48 => 
            array (
                'id' => 164,
                'section_id' => 43,
                'description' => 'Is the rate of release/rate of addition of biocide known and recorded?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            49 => 
            array (
                'id' => 165,
                'section_id' => 43,
                'description' => 'Is the concentration of the biocide at sentinel outlets checked on a monthly basis?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            50 => 
            array (
                'id' => 166,
                'section_id' => 43,
                'description' => 'Is the concentration of biocide checked at representative outlets on an annual basis?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            51 => 
            array (
                'id' => 171,
                'section_id' => 44,
                'description' => 'On an annual basis is a check on the existence of all water connections to outside services?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            52 => 
            array (
                'id' => 172,
                'section_id' => 44,
            'description' => 'Are results of all tests and checks recorded, together with details of any remedial action taken (if required)?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            53 => 
            array (
                'id' => 173,
                'section_id' => 45,
                'description' => 'Are there procedures in place to identify circumstances when either general microbiological monitoring or sampling for legionella would be appropriate?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            54 => 
            array (
                'id' => 174,
                'section_id' => 45,
                'description' => 'If there are procedures in place, do these identify where samples should be taken, and the frequency and actions required?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            55 => 
            array (
                'id' => 175,
                'section_id' => 46,
                'description' => 'Have the circumstances when cleaning and disinfection of the hot water system would be appropriate been identified?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            56 => 
            array (
                'id' => 176,
                'section_id' => 46,
                'description' => 'If cleaning and disinfection carried out?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            57 => 
            array (
                'id' => 177,
                'section_id' => 46,
                'description' => 'Are procedures in place for the chosen method of cleaning and disinfection?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            58 => 
            array (
                'id' => 178,
                'section_id' => 44,
                'description' => 'Weekly flushing and recording of the little used outlets identified?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            59 => 
            array (
                'id' => 179,
                'section_id' => 42,
                'description' => 'Monthly inspection and recording of flow and return temperatures of hot water?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            60 => 
            array (
                'id' => 180,
                'section_id' => 42,
                'description' => 'Monthly inspection and recording of combination water heater temperature at an outlet?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            61 => 
            array (
                'id' => 181,
                'section_id' => 42,
                'description' => 'Monthly inspection and recording of hot sentinel tap temperatures?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            62 => 
            array (
                'id' => 182,
                'section_id' => 42,
                'description' => 'Monthly inspection and recording of cold sentinel tap temperatures?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            63 => 
            array (
                'id' => 183,
                'section_id' => 42,
                'description' => 'Monthly temperature check at last cold outlet on each long branch?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            64 => 
            array (
                'id' => 184,
                'section_id' => 46,
                'description' => 'Quarterly dismantling, cleaning and descaling of showers, sluice sprays and spray taps?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            65 => 
            array (
                'id' => 185,
                'section_id' => 42,
                'description' => 'Quarterly inspection and recording of the temperature of the return legs of subordinate loops in the hot  circulating system?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            66 => 
            array (
                'id' => 186,
                'section_id' => 42,
                'description' => 'Six monthly inspection and recording of POU water heater <15L temperature at an outlet?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            67 => 
            array (
                'id' => 187,
                'section_id' => 44,
                'description' => 'Six monthly expansion vessel flushing and purging to drain?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            68 => 
            array (
                'id' => 188,
                'section_id' => 42,
                'description' => 'Annual internal inspection and recording of tank water temperature?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            69 => 
            array (
                'id' => 189,
                'section_id' => 44,
                'description' => 'Annual inspection of header tanks on combination water heaters?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            70 => 
            array (
                'id' => 190,
                'section_id' => 45,
                'description' => 'Annual calorifier internal inspection/inspection of debris in the base/microbiological analysis?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            71 => 
            array (
                'id' => 191,
                'section_id' => 46,
                'description' => 'Annual cleaning, descaling and disinfection of TMVs?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            72 => 
            array (
                'id' => 192,
                'section_id' => 42,
            'description' => 'Annual temperature check of representative number of hot and cold taps(rotational basis) carried out and recorded?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => '4',
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            73 => 
            array (
                'id' => 193,
                'section_id' => 44,
                'description' => 'Have water samples been collected?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => NULL,
                'hazard_answer_value' => NULL,
                'hz_name' => NULL,
                'hz_verb_id' => NULL,
                'hz_noun_id' => NULL,
            ),
            74 => 
            array (
                'id' => 194,
                'section_id' => 51,
            'description' => 'Was access gained to the electrical intake/meter cupboard(s) for the block?',
                'score' => '0',
                'answer_type' => 2,
                'is_key' => 0,
                'good_answer' => '0',
                'preloaded_comment' => 'Access was gained to the one electrical intake cupboard at this address, which is located XXXXXX

It was not possible to access the main electrical intake room which is under the control of UKPN, the door for which is secured locked shut by a non-standard padlock.

It was not possible to locate the electrical intake cupboard. It is recommended that the enclosure is located and surveyed to confirm: a) The good condition of the electrical intake equipment, and b) That the enclosure is a minimum of 30 minutes FR.
',
            'hazard_answer_value' => '4',
            'hz_name' => 'Access to be provided',
            'hz_verb_id' => 45,
            'hz_noun_id' => 98,
        ),
        75 => 
        array (
            'id' => 195,
            'section_id' => 51,
        'description' => 'Is the common area fixed electrical installation inspected and tested within the last five years? (State the date of the last test if available)',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'It is policy for WCC to carry out statuary 5 yearly inspections and testing of the landlord\'s electrical supply system. Records of all testing inspection and maintenance are held centrally

It is policy for WCC to carry out statuary 5 yearly inspections and testing of the landlord\'s electrical supply system. However, the test for this address is overdue
',
            'hazard_answer_value' => NULL,
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        76 => 
        array (
            'id' => 196,
            'section_id' => 51,
        'description' => 'Is the common area fixed electrical installation free from visible defects? (from cursory visual inspection only)',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No defects noted at the time of this assessment.',
            'hazard_answer_value' => '4',
            'hz_name' => 'Repair defect',
            'hz_verb_id' => 27,
            'hz_noun_id' => 99,
        ),
        77 => 
        array (
            'id' => 197,
            'section_id' => 51,
        'description' => 'Is portable appliance testing (PAT) being completed within the common areas?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No portable appliances were observed in communal or landlord only areas which would be subject to PAT testing. Portable electrical appliances are used in the common areas by WCC own staff and approved contractors. WCC has a system in place for testing its own portable appliances. Those appliances used by contractors are subject to the contractor’s own company\'s Health and Safety arrangements which are required by WCC.',
            'hazard_answer_value' => '6,7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        78 => 
        array (
            'id' => 198,
            'section_id' => 51,
            'description' => 'Is there a policy in place regarding use of personal electrical appliances within the common areas which is being adhered to at the time of inspection?',
            'score' => '0',
            'answer_type' => 4,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'It is WCC policy for all portable electrical appliances owned by them, which are located/used in communal and/or landlord only areas to be PAT tested. Residents are not permitted to use their personal portable electrical appliances in the communal areas.',
            'hazard_answer_value' => NULL,
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        79 => 
        array (
            'id' => 199,
            'section_id' => 51,
            'description' => 'If occurring, is the use of multi-way plug adapters and/or extension leads within the common areas considered acceptable?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'None noted as being in use in either the common parts or landlord areas at the time of this assessment.',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        80 => 
        array (
            'id' => 200,
            'section_id' => 52,
        'description' => 'Are there suitable arrangements in place for those who wish to smoke? (State what arrangements are in place)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'In line with current UK legislation, no smoking is permitted in the common or landlord controlled areas. Resident must either smoke within their own flat, or outside of the block.',
            'hazard_answer_value' => NULL,
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        81 => 
        array (
            'id' => 201,
            'section_id' => 52,
            'description' => 'Is there a policy in place to prevent or restrict smoking within the building?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'The policy regarding smoking is in line with legislation. No smoking is permitted in the communal, or landlord controlled areas.',
            'hazard_answer_value' => NULL,
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        82 => 
        array (
            'id' => 202,
            'section_id' => 52,
            'description' => 'Does the policy in relation to smoking appear to be observed?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No breaches noted at the time of this assessment.

Discarded smoking materials were loacted in XXXXX. Additional, "No Smoking", signage should be prominantly displayed in strategic locations throughout the building.
',
            'hazard_answer_value' => '2',
            'hz_name' => 'Evidence of smoking',
            'hz_verb_id' => 19,
            'hz_noun_id' => 100,
        ),
        83 => 
        array (
            'id' => 203,
            'section_id' => 52,
            'description' => 'Is there adequate provision of "No Smoking" signage within the common area?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Adequate signage instructing persons not to smoke in the communal areas is displayed.',
            'hazard_answer_value' => '2',
            'hz_name' => 'No signage provided.',
            'hz_verb_id' => 19,
            'hz_noun_id' => 100,
        ),
        84 => 
        array (
            'id' => 204,
            'section_id' => 52,
        'description' => 'Are there appropriately sited facilities for electrical isolation of any photovoltaic (PV) cells, with appropriate signage, to assist the fire and rescue service?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
        'preloaded_comment' => 'No Photovoltaic, (PV), cells were identified at this address.

It was not possible to locate or identify the isolation facilities for the Photovoltaic, (PV), cells, which are located on the roof. Appropriate signage should be displayed to indicate the location of these facilities, to assist the fire and rescue service in the event of a fire at the premises.?
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        85 => 
        array (
            'id' => 205,
            'section_id' => 53,
        'description' => 'Are the premises secured against arson by outsiders? (Please state how)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'The single entrance into the building is secured locked shut. It can only be opened from outside by the resident’s fobs/keys.

All of the entrances into the building are secured locked shut. They can only be opened from outside by the resident’s fobs/keys, entryphone system or LFB override.

The electromagnetic lock securing the entrance door into the block is defective, leaving the building unsecure.

Access to the block is unrestricted. Although there was no evidence noted of any arson, unrestricted access increases the risk of an arson attack. As such, it is recommended that the provision of a controlled access system is considered at the time of the next major works carried out at the block.
',
            'hazard_answer_value' => '2',
            'hz_name' => 'Repair mag-lock',
            'hz_verb_id' => 46,
            'hz_noun_id' => 101,
        ),
        86 => 
        array (
            'id' => 206,
            'section_id' => 53,
        'description' => 'Are bins stored in a suitable location? (Please state bin type and location)',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'A dedicated bin room is located at G level. It is accessed via externally doors.

Dedicated bin stores are located at G level. They are accessed via external doors.

There are no bins provided at this address. Residents dispose of their refuse in the paladins provided in surrounding streets.

There are no bins provided at this address. Residents deposit their rubbish in the street on designated days. 
',
            'hazard_answer_value' => '4',
            'hz_name' => 'Bins not stored in appropriate location',
            'hz_verb_id' => 47,
            'hz_noun_id' => 102,
        ),
        87 => 
        array (
            'id' => 207,
            'section_id' => 53,
        'description' => 'Are bins adequately secured at or within their storage location? (Please state how bins are secured)',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'The bin room is secured locked shut.

The bin rooms are secured locked shut.

The bin rooms are unlocked. However, the hoppers are located on open balconies and risks are considered low. Arrangements are considered acceptable.

The bin rooms are unlocked. The hoppers are located in FR lobbies.

The bin rooms are unlocked. The hoppers open directly into the escape staircase enclosure.
',
            'hazard_answer_value' => '4',
            'hz_name' => 'Bin room not secure',
            'hz_verb_id' => 48,
            'hz_noun_id' => 102,
        ),
        88 => 
        array (
            'id' => 208,
            'section_id' => 53,
            'description' => 'Is fire load close to the premises minimised?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There was no evidence of any waste build up around the perimeter of the building at the time of this assessment.',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        89 => 
        array (
            'id' => 209,
            'section_id' => 54,
        'description' => 'Are the common areas of the building provided with any form of FIXED space heating system? (State type provided)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There is no fixed heating in the common parts.',
            'hazard_answer_value' => NULL,
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        90 => 
        array (
            'id' => 210,
            'section_id' => 54,
        'description' => 'Are the commonal areas of the building provided with any form of PORTABLE space heating system? (State type provided)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There were no portable heaters in the common parts.',
            'hazard_answer_value' => '1',
            'hz_name' => 'If yes, remove / replace with fixed wall heater',
            'hz_verb_id' => 28,
            'hz_noun_id' => 104,
        ),
        91 => 
        array (
            'id' => 211,
            'section_id' => 55,
            'description' => 'Are commonal cooking facilities provided in the block?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no cooking facilities in the communal areas.',
            'hazard_answer_value' => '1',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        92 => 
        array (
            'id' => 212,
            'section_id' => 55,
            'description' => 'Are canteen facilites provided?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no canteen facilities at this address',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        93 => 
        array (
            'id' => 213,
            'section_id' => 55,
            'description' => 'Where cooking facilities are provided, are filters cleaned or changed and ductwork cleaned regularly?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no canteen facilities at this address',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        94 => 
        array (
            'id' => 214,
            'section_id' => 56,
            'description' => 'Does the building have a lightning protection system installed?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'A lightning protection system has been provided for the block.

No lightning protection system appears to be provided for the premise. It is considered most unlikely that one would be required for building of this height in this location.

No lightning protection system appears to be provided for the premise. At building design there was no requirement to install lightening protection. However, taking into account the height of this building and of surrounding buildings, in the event of any major structural works or major alterations to the roof area, it is recommended that a feasibility assessment should be undertaken on the property to identify if an LPS is required in line with BS EN 62305- 2.
',
            'hazard_answer_value' => NULL,
            'hz_name' => 'Further survey required:',
            'hz_verb_id' => 17,
            'hz_noun_id' => 103,
        ),
        95 => 
        array (
            'id' => 215,
            'section_id' => 56,
            'description' => 'From visual inspection, does the lightning protection system appear to be in good condition?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No defects were noted at the time of this assessment.',
            'hazard_answer_value' => '4',
            'hz_name' => 'Repair defect',
            'hz_verb_id' => 27,
            'hz_noun_id' => 99,
        ),
        96 => 
        array (
            'id' => 216,
            'section_id' => 57,
            'description' => 'Is the property regularly cleaned to prevent the build up of combustibles?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'The block was in a clean condition. Regular visits are made by the caretaker/cleaner.',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        97 => 
        array (
            'id' => 217,
            'section_id' => 57,
            'description' => 'Are combustible items kept clear from sources of ignition such as electrical equipment?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No combustibles were noted as being located adjacent to any ignition sources at the time of this assessment.

Combustibles were noted as being located in the electrical intake cupboard.
',
            'hazard_answer_value' => '4',
            'hz_name' => 'Combustible in electrical intake cupboard',
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        98 => 
        array (
            'id' => 218,
            'section_id' => 57,
            'description' => 'Are escape routes kept clear of combustible items or waste materials?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'At the time of this assessment the escape routes were clear.

Combustible materials were found to be located within the protected escape route in the following locations:
XXXXXX
',
            'hazard_answer_value' => '2',
            'hz_name' => 'Combustible in communal escape enclosures.',
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        99 => 
        array (
            'id' => 219,
            'section_id' => 57,
            'description' => 'Are escape routes kept clear of any trip hazards?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No trip hazards were found to be present in any of the escape routes at the time of this assessment.

The combustible items listed in the question above are also causing a trip hazard.
',
            'hazard_answer_value' => '2',
            'hz_name' => 'Obstructions in communal escape routes',
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        100 => 
        array (
            'id' => 220,
            'section_id' => 57,
            'description' => 'Are any hazardous materials noted being stored correctly?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No hazardous materials were found to be stored on the premises at the time of this assessment.',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        101 => 
        array (
            'id' => 221,
            'section_id' => 57,
            'description' => 'Are all other house-keeping issues satisfactory?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No other housekeeping issues noted at the time of this assessment. 

It was noted that the time of this assessment that unwanted and unnecessary combustible items were located in the following landlord areas: XXXXXXXXX. It is recommended that arrangements are made for all unnecessary items currently located in the basement areas to be removed from site.
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        102 => 
        array (
            'id' => 222,
            'section_id' => 58,
            'description' => 'Are fire safety conditions imposed on outside contractors when working on the premises?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Contractors are provided with a copy of the latest FRA for the property where works are to be carried out. It is the Principal Contractor’s responsibility to implement and manage the necessary fire safety arrangements for the duration of the works.',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        103 => 
        array (
            'id' => 223,
            'section_id' => 58,
        'description' => 'Are there satisfactory controls in place over works carried out on the premises by OUTSIDE contractors? (e.g. Hot Work Permits)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Where hot works are to be carried by contractors, conditions are placed upon the contractors to strictly comply with the hot works permit system.',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        104 => 
        array (
            'id' => 224,
            'section_id' => 58,
        'description' => 'Are there satisfactory controls in place over works carried out in the premises by IN-HOUSE staff? (e.g. Hot Work Permits)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'All hot works are currently carried out by contractors. Should this change in the future, any hot works to be carried out by in-house employees will need to be carried out in compliance with the hot works permit system.',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        105 => 
        array (
            'id' => 225,
            'section_id' => 59,
            'description' => 'Are any "dangerous substances" stored or in use within the property?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No dangerous substances were noted as being stored or in use within the premises at the time of this assessment.',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        106 => 
        array (
            'id' => 226,
            'section_id' => 60,
            'description' => 'Are all other Fire Hazard issues considered satisfactory?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No other fire hazards were noted at the time of this assessment.',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        107 => 
        array (
            'id' => 227,
            'section_id' => 61,
        'description' => 'Is the escape route design deemed satisfactory? (Consider current design codes)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'The means of escpape appears to be adequate from all areas.',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        108 => 
        array (
            'id' => 228,
            'section_id' => 61,
        'description' => 'Are the escape routes adequately protected? (Consider lobby protection to staircase, if needed)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'This property was built to modern building regulations. The walls protecting the escape route appear to provide a minimum of 60 minutes protection from any risk, with doors providing a minimum of 30 minutes FR.

As with many properties of this age, there is no lobby protection to the staircases from the FEDs. Although not ideal, it would meet the recommendations in LACoRS, the relevant guidance document for a property of this nature, if the doors protecting the escape routes were to the recommended standard. At present, this is not the case. See sections L, M, & Q, 

Lobby protection is provided to the staircase(s).

The building was deigned to provide adequate protection to the escape routes. However, see sections L, M & Q
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        109 => 
        array (
            'id' => 229,
            'section_id' => 61,
        'description' => 'Is the fire-resisting construction (including any glazing)protecting escape routes and staircases of a suitable standard and maintained in sound condition?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'This property was built to modern building regulations. The walls protecting the escape route appear to provide a minimum of 60 minutes protection from any risk, with doors providing a minimum of 30 minutes FR.',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        110 => 
        array (
            'id' => 230,
            'section_id' => 61,
            'description' => 'Is there adequate provision of exits for the numbers who may be present?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
        'preloaded_comment' => 'The exit(s) provided are adequate for the maximum number of persons ever likely to need them to escape from a fire, taking into account the evacuation strategy in place for the building',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        111 => 
        array (
            'id' => 231,
            'section_id' => 61,
            'description' => 'Is there adequate exit width for the numbers who may be present?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
        'preloaded_comment' => 'The width(s) of the exit(s) provided are adequate for the maximum number of persons ever likely to need them to escape from a fire, taking into account the evacuation strategy in place for the building',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        112 => 
        array (
            'id' => 232,
            'section_id' => 61,
        'description' => 'Are doors on escape routes easily opened? (and are sliding or revolving doors avoided?)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no doors on the escape routes from this block.

The final exit door is opened by releasing a simple catch/handle. There are no revolving or sliding doors.

The electromagnetic lock(s) securing the final exit gate(s) are released using the push button releases, which are located adjacent to the gates. There are no revolving or sliding doors.
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        113 => 
        array (
            'id' => 233,
            'section_id' => 61,
        'description' => 'Are doors or gates on escape routes provided with electrically operated access control systems? (Describe provision)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no doors on the escape routes from this block.

The final exit door is opened by releasing a simple catch/handle.

The final exit door(s) is secured locked shut by an electromagnetic lock which can be released using the push button release(s) adjacent to the door(s).

The final exit door(s) is secured locked shut by an electromagnetic lock which can be released using the simple latch/handle(s)
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        114 => 
        array (
            'id' => 234,
            'section_id' => 61,
            'description' => 'Are electrically operated access control systems fitted to doors or gates on escape routes provided with override facilities and/or designed to "fail safe" on power failure?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no doors at this address which are secured by electromagnetic locks.

It was confirmed by WCC Homes M & E Team that the electromagnetic lock on the entrance door at this block, “Fails Safe”, to open in the event of a mains power failure.

Although the final exit door(s) is secured by an electromagnetic lock(s), it can be released using the simple mechanical latch/handle. As such, there is no requirement for the electromagnet to fail safe to open.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        115 => 
        array (
            'id' => 235,
            'section_id' => 61,
            'description' => 'Do final exits open in the direction of escape where necessary?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
        'preloaded_comment' => 'The final exit door(s) opens in the direction of travel.

The final exit(s) opens against the direction of travel. However, taking into consideration the maximum number of persons ever likely to need to use this door to escape from a fire, this is considered acceptable.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        116 => 
        array (
            'id' => 236,
            'section_id' => 61,
        'description' => 'Are travel distances satisfactory? (consider single direction and more than one direction)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'The travel distances from all areas are within the maximum distances recommended in national guidance.',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        117 => 
        array (
            'id' => 237,
            'section_id' => 61,
            'description' => 'Are the precautions for all inner rooms suitable?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no inner rooms in the communal or landlord only parts at this address.',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        118 => 
        array (
            'id' => 238,
            'section_id' => 61,
            'description' => 'Are escape routes adequately separated from each other, with fire resisting construction where required?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There is just a single means of escape.

Adequate separation is provided between the escape routes.

There is no physical barrier between the exits at either end of the block. However, they are a considerable distance apart, and any fire affecting one of the exits will not affect the other.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        119 => 
        array (
            'id' => 239,
            'section_id' => 61,
            'description' => 'Are corridors sub-divided with a cross-corridor fire resisting door where required?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There is no requirement for the corridors to be sub-divided.

The corridors are sub-divided by FD30 fire doors.

There is no physical barrier between the exits at either end of the block. However, they are a considerable distance apart, and any fire affecting one of the exits will not affect the other.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        120 => 
        array (
            'id' => 240,
            'section_id' => 61,
            'description' => 'Do escape routes lead to a place of safety?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
        'preloaded_comment' => 'The escape staircase(s) exit at G level to a place(s) of, "Ultimate Safety", in XXXXX

All escape routes lead to places of, "Ultimate Safety".
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        121 => 
        array (
            'id' => 241,
            'section_id' => 61,
        'description' => 'Are the stairs and/or lobbies provided with adequate permanent or manually operated ventilation openings for control of smoke? (State provision)',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are openable windows throughout the escape staircase enclosure.

The escape balconies are open to air. The escape staircases are open at each level.

A POV is provided at the head of the escape staircase enclosure(s).
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        122 => 
        array (
            'id' => 242,
            'section_id' => 61,
        'description' => 'Are the stairs and/or lobbies provided with an adequate automatic or remotely operated smoke ventilation system? (State provision)',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No automatic or remotely operated vent systems required or provided at this premises.',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        123 => 
        array (
            'id' => 243,
            'section_id' => 61,
            'description' => 'Are there suitable arrangements in the building for means of escape for people with disabilities?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
        'preloaded_comment' => 'This is a general needs block. The risk assessor was not made aware of any persons who may be vulnerable and/or may have a mental or physical disability, and none were identified at the time of this assessment. The latest Government guidance states that it proposes to, “Require the responsible person to prepare a personal emergency evacuation plan (PEEP) for every resident in a high-rise residential building who self-identifies to them as being unable to self-evacuate (subject to the resident’s voluntary self-identification) and to do so in consultation with them.” Currently, the responsibility for gathering this information sits with the WCC Housing Team.  The Housing Team should ensure that this information is being gathered and that PEEPs are carried out in each case identified. Any specialist equipment/safety system, as identified as being necessary by any PEEP should be provided. Furthermore, it is recommended that proactive measures are taken to identify such persons. 


The WCC Fire Safety (Housing) Policy is currently being updated. It must be ensured that, as a minimum, the reviewed policy meets, but preferably exceeds, the Government guidance to identify vulnerable residents and provide PEEPs where required, so as to ensure the safety of those residents.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        124 => 
        array (
            'id' => 244,
            'section_id' => 61,
            'description' => 'Are all other means of escape issues satisfactory?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No other issues regarding the means of escape from this premises were identified.

The escape staircase(s) has plain concrete steps. It is recommended that stair nosings, complying with relevant guidance given in; Building Regulations Document K, Building Regulations Document  M, BS 8300, BS 9266 and BRIP-IP15/03, are provided on the steps in both staircases.
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        125 => 
        array (
            'id' => 245,
            'section_id' => 61,
            'description' => 'Is the current evacuation strategy for the property considered appropriate?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'All of the FEDs appear to be a minimum of 30 miuntes FR

Those FEDs, as detailed in the Hazard raised relating to this issue, do not appear to be adequately FR and are in need of upgrading/replacement by doors which are a minimum of 30 minutes FR.
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        126 => 
        array (
            'id' => 246,
            'section_id' => 62,
        'description' => 'Are flat entrance doors or doors / frames appropriately fire rated? (State type and standard of doors)',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'All of the FEDs appear to be a minimum of 30 miuntes FR

Those FEDs, as detailed in the Hazard raised relating to this issue, do not appear to be adequately FR and are in need of upgrading/replacement by doors which are a minimum of 30 minutes FR.
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        127 => 
        array (
            'id' => 247,
            'section_id' => 62,
            'description' => 'Are fire rated flat entrance doors in good condition - not in need of repair?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'All of the FEDs appeared to be in acceptable condition and well fitting. No specific defects were noted at the time of this assessment.

Those FEDS, as detailed in the Hazard raised relating to this issue, are damaged/defective and in need of repair/replacement.
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        128 => 
        array (
            'id' => 248,
            'section_id' => 62,
            'description' => 'Is all glazing to flat entrance doors appropriately fire rated?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'None of the FEDs are glazed.

Where there are glazed units in the FEDs they are either Georgian glass or FR pyroglazing.

Those glazing panels in FEDs as detailed in the Hazard raised relating to this issue, do not appear to be adequately FR and are in need of replacement by panels which are a minimum of 30 minutes FR.
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        129 => 
        array (
            'id' => 249,
            'section_id' => 62,
            'description' => 'Are fanlights above flat entrance doors appropriately fire rated?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no fanlights to the FEDs in this block.

There are Georgian glazed panels above all of the FEDs. All panels were in good order and appeared to be well fitted.

There are glazing panels in the FEDs. However, as they are above 1100mm above balcony level there is no requirement for them to be FR.

Those fanlights as detailed in the Hazard raised relating to this issue, do not appear to be adequately FR and are in need of replacement by panels which are a minimum of 30 minutes FR.
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        130 => 
        array (
            'id' => 250,
            'section_id' => 62,
            'description' => 'Are side panels to flat entrance doors appropriately fire rated?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no side panels to the FEDs in this block.

Where provided, the glazing panels to the sides of the FEDs are Georgian glass.

Those side panels as detailed in the Hazard raised relating to this issue, do not appear to be adequately FR and are in need of replacement by panels which are a minimum of 30 minutes FR.
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        131 => 
        array (
            'id' => 251,
            'section_id' => 62,
            'description' => 'Are all flat entrance doors fitted with adequate self-closing devices?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'National guidance makes no requirement for self closers to be provided on the FEDs at this address.

All of the FEDs inspected were found to be provided with self closers to BS EN 1154 standard.

It was noted that  the self closers on those communal fire doors, as detailed in the Hazard raised relating to this issue, were either missing or deffective.
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        132 => 
        array (
            'id' => 252,
            'section_id' => 62,
            'description' => 'Are all flat entrance doors fitted with intumescent strips and cold smoke seals?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'National guidance makes no requirement for intumescent strips and cold smoke seals to be provided on the FEDs at this address.

National guidance recommends that FEDs in a premises of this height and nature are to FD30S standard. They all appear to be to this standard.

National guidance recommends that FEDs in a premises of this height and nature are to FD30S standard. It could not be confirmed that this is the case and it is recommended that the FEDs in this block are included in the planned survey of FEDs.

National guidance recommends that FEDs in a premises of this height and nature are to FD30S standard. It was noted that the intumescent strips and cold smoke seals on the FEDs to those flats detailed in the Hazard raised relating to this issue, are missing or defective.
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        133 => 
        array (
            'id' => 253,
            'section_id' => 62,
        'description' => 'Are letterboxes to flat entrance doors satisfactory? (State only if missing, damaged or uPVC)',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no letter boxes in the FEDs at this address.

No defects noted at the time of this assessment.

It was noted that the letterboxes in those communal fire doors, as detailed in the Hazard raised relating to this issue, were damaged and in need of repair/replacement.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        134 => 
        array (
            'id' => 254,
            'section_id' => 62,
            'description' => 'Are all other flat entrance door issues satisfactory?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No other issues noted regarding the FEDs at the time of this assessment.',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        135 => 
        array (
            'id' => 255,
            'section_id' => 63,
            'description' => 'Are all common area doors and frames requiring fire resistance appropriately fire rated?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'The doors from landlord areas leading into the escape route appear to be to be to at least notional FD30 standard.

Those communal fire doors detailed in the Hazard raised regading this issue, do not appear to be adequately FR and should be replaced/upgraded. 
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        136 => 
        array (
            'id' => 256,
            'section_id' => 63,
            'description' => 'Are all common area fire rated fire doors in good condition - and not in need of repair?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No defects noted during this assessment.

It was noted that  those communal fire doors, as detailed in the Hazard raised relating to this issue, were damaged and in need of repair/replacement.

It was noted that the gap between the sides of the doors and their frames of those communal fire doors, as detailed in the Hazard raised relating to this issue, are excessively large and the doors are in need of adjustment/repair.
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        137 => 
        array (
            'id' => 257,
            'section_id' => 63,
            'description' => 'Is all glazing to common area fire doors appropriately fire rated?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'None of the communal doors include any glazing elements.

Where there are glazing elements in the communal doors, those elements are either Georgian glass or FR glazing panels, and appear to be in good condition and well fitting and capable of providing the necessary FR.

It was noted that the glazing units in those communal fire doors detailed in the Hazard raised regading this issue, do not appear to be adequately FR and should be replaced.
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        138 => 
        array (
            'id' => 258,
            'section_id' => 63,
            'description' => 'Are fan lights/side panels to common area fire doors appropriately fire rated?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'None of the fire doors from landlord areas into the escape routes have any fan lights/side panels.

Where there are glazed fan lights/side panels in communal doors the panels are Georgian glass and appear to be in acceptable condition and well fitting and capable of providing the necessary FR.

It was noted that the glazing units in those fanlights/side panels detailed in the Hazard raised regading this issue, do not appear to be adequately FR and should be replaced.

Where there are glazing elements in the side panels to the communal doors into the single staircase enclosure, those elements are Georgian glass panels, and appear to be in good condition and well fitting and capable of providing the necessary FR. However, it is unlikely that this glass is heat resisting as well as fire resisting. At the time of the next major refurbishment at the block it is recommended that consideration is given to replacing the glazing panels in the doors between the lobbies and the escape staircase enclosure to panels which heat resisting as well at being FR, in line with current building regulations.
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        139 => 
        array (
            'id' => 259,
            'section_id' => 63,
            'description' => 'Are common area fire doors fitted with adequate self-closing devices where required?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no communal doors which require a self closer to be provided.

Where necessary, all of the communal doors have been furnished with self closing devices complying with BS EN 1154.

It was noted that the self closer(s), provided on communal fire doors detailed in the Hazard raised regarding this issue, are defective and in need of repair/replacement.

It was noted that the self closer(s), provided on communal fire doors detailed in the Hazard raised regarding this issue, do not meet current standards and are in need of repair/replacement.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        140 => 
        array (
            'id' => 260,
            'section_id' => 63,
            'description' => 'Are intumescent strips and smoke seals provided to common area fire doors?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no communal doors which require intumescent strips and cold smoke seals to be provided.

All of the communal doors have been furnished with intumescent strips and cold smoke seals.

It was noted that the intumescent strips and cold smoke seals on those communal fire doors, as detailed in the Hazard raised relating to this issue, are missing and in need of replacement.

National guidance recommends that all communal fire doors in a premises of this nature are to FD30S standard. It was noted that this is not the case. Those communal fire doors detailed in the Hazard raised regading this issue, should be upgrade to this standard or replaced by doors to FD30S standard..
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        141 => 
        array (
            'id' => 261,
            'section_id' => 63,
            'description' => 'Are all doors to landlord areas furnished with appropriate standard locks to allow access to WWC personel and approved contractors?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No defects or deficiencies noted at the time of this assessment.

It was noted that those communal fire doors, detailed in the Hazard raised regading this issue, are provided with non-standard locks, and which were not possible to access during this assessment. 
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        142 => 
        array (
            'id' => 262,
            'section_id' => 63,
            'description' => 'Where required to be so, are all doors to landlord areas secured locked shut?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'All doors to communal areas were found to be secured locked shut.

It was noted that the locks to those communal fire doors, as detailed in the Hazard raised relating to this issue, were missing/damage and in need of replacement.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        143 => 
        array (
            'id' => 263,
            'section_id' => 63,
        'description' => 'Are common area fire doors adequate otherwise? (Ironmongery, hold open hooks etc.)',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No defects were noted at the time of this assessment.',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        144 => 
        array (
            'id' => 264,
            'section_id' => 63,
            'description' => 'Are all other common area fire door issues satisfactory?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No defects were noted at the time of this assessment.
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        145 => 
        array (
            'id' => 265,
            'section_id' => 64,
        'description' => 'Is emergency lighting provided to the common areas of the block? (if "Yes" then describe provision)',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Emergency lighting is provided throughout the escape routes.

There is currently no provision of emergency lighting in the communal escape staircase enclosure.

None of the lighting units in the escape routes could be confirmed as being emergency lighting units. Unless it can be confirmed that emergency lighting is indeed provided throughout the escape routes, it should be provided now, in compliance with the requirements of BS 5266. 

There is no emergency lighting provided along the open balconies. However, it is considered that, in the event of a mains electrical power failure, adequate, "Borrowed Light", would be available from street lighting and surrounding buildings. As such, the provision of emergency lighting in these areas is not considered as being a requirement.
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        146 => 
        array (
            'id' => 266,
            'section_id' => 64,
            'description' => 'From visual inspection, does the emergency lighting system appear to be in good working order?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No defects were noted at the time of this assessment.',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        147 => 
        array (
            'id' => 267,
            'section_id' => 64,
        'description' => 'From visual inspection, does the coverage of the emergency lighting system provided appear to be adequate? (Internal and external)',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Where necessary, the provision of emergency lighting is considered adequate.

The provision of emergency lighting is not considered adequate in the following areas:
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        148 => 
        array (
            'id' => 268,
            'section_id' => 65,
            'description' => 'Is there adequate provision of fire action notices within the common areas?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'An FAN is displayed in the entrance lobby.

FANs  are displayed in each entrance lobby.
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        149 => 
        array (
            'id' => 269,
            'section_id' => 65,
            'description' => 'Do the FANs provide the correct evacuation strategy for the premises?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
        'preloaded_comment' => 'The FAN(s) correctly gives instruction for the, "Simultaneous Evacuation", strategy.

The FAN(s) correctly gives instruction for the, "Defend in Place" evacuation strategy.
',
            'hazard_answer_value' => '1,2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        150 => 
        array (
            'id' => 270,
            'section_id' => 65,
            'description' => 'Is fire door signage adequate?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Door signage is considered adequate.

Signs stating, "Fire Door Keep Shut", should be provided with at eye level on each side of those communal fire doors listed in the Hazard associated with this issue.

Signs stating, "Fire Door Keep Locked Shut", should be provided with at eye level on the outer leaf of those communal fire doors listed in the Hazard associated with this issue.

Signs stating, "Fire Exit Keep Clear", should be provided with at eye level on the outer leaf of those communal fire doors listed in the Hazard raised  associated with this issue.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        151 => 
        array (
            'id' => 271,
            'section_id' => 65,
            'description' => 'Where required, is appropriate signage provided to indicate the means of operation of any door release mechanisms provided on doors on the escape routes?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Where necessary, appropriate pictorial signage has been provided to indicate the means of operating door release mechanisms on the exit doors.

Appropriate pictorial signage should be displayed adjacent to the push bar/pad release mechanisms on of those communal fire doors listed in the Hazard raised associated with this issue.

Appropriate pictorial signage should be displayed adjacent to the thumb turn lock on of those communal fire doors listed in the Hazard raised associated with this issue.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        152 => 
        array (
            'id' => 272,
            'section_id' => 65,
            'description' => 'If required, is directional/exit signage adequate?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Adequate directional signage has been provided where considered necessary.

Being a simple single direction means of escape in a premise occupied by persons who are familiar with the premises, no direction signage is considered necessary.

Directional signage is required in those areas stated in the Hazard raised associated with this issue.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        153 => 
        array (
            'id' => 273,
            'section_id' => 65,
            'description' => 'Is signage provided instructing persons not to use the lift in the event of a fire?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Signs instructing persons not to use the lift in the event of a fire are displayed adjacent to the lift doors on each level.

Signs instructing persons not to use the lift in the event of a fire should be displayed adjacent to the lift doors on each level.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        154 => 
        array (
            'id' => 274,
            'section_id' => 65,
        'description' => 'Where required, is appropriate signage provided to indicate each floor level wiithin the escape staircase(s)?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
        'preloaded_comment' => 'For a building of this height the provision of signage in the staircase(s) to indicate each floor level is not considered to be a requirement.

Appropriate signage is provided in the staircase(s) to indicate each floor level.

It is recommended that signs are prominently displayed at each floor level in both of the staircases to clearly indicate each floor level. Following recommendations made in the report of Phase 1 of the Grenfell Fire enquiry, the signs provided should be made of materials which are visible, “in low light and smoky conditions”.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        155 => 
        array (
            'id' => 275,
            'section_id' => 65,
            'description' => 'Where required, is adequate signage provided to indicate the location of the dry rising main inlets?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There is no DRM provided at this address.

The DRM inlet box located in a position where it is clearly visible to attending fire crews. No additional signage is considered necessary.

The inlet box for the DRM at this address it located in a position which may not be immediately apparent to atending fire crews. Additional signage, to clearly indicate its ocation should be provided, as detailed in the Hazard raised associated with this issue.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        156 => 
        array (
            'id' => 276,
            'section_id' => 65,
            'description' => 'Are all other fire safety signs issues satisfactory?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No other fire safety signage issues noted at the time of this assessment.',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        157 => 
        array (
            'id' => 277,
            'section_id' => 66,
        'description' => 'Has the building got an electrical fire alarm system comprising manual call points and/or automatic detection? (Provide details)',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'In line with recommendations in national guidance for purpose built residential blocks, designed to facilitate a, \'Defend in Place\' evacuation strategy, there is no requirement for automatic detection to be fitted within the common areas.

In line with recommendations in national guidance for buildings which have been converted to residential flats, (LACoRS), where adequate compartmentation between the flats cannot be guaranteed, a Grade A, LD2 fire detection/alarm system is provided, with smoke detection throughout the escape route and HD in each of the hallways of the flats.

Although being purpose built, the block was constructed at the turn of the 20th century and has timber floors. Adequate compartmentation cannot be guaranteed. As such, as a compensatory measure, a Grade A, LD2 fire detection/alarm system is provided, with smoke detection throughout the escape route and HD in each of the hallways of the flats.

The recommendations in the recognised national guidance, (LACoRS), for converted flats of 5/6 storeys, is that a Grade A, LD2 communal fire alarm system should be provided, with SD throughout the escape staircase enclosure and HD in the hallway of each flat, all interconnect to a control panel. At present, no such system is provided. It is recommended a system is provided to this specification. 
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        158 => 
        array (
            'id' => 278,
            'section_id' => 66,
            'description' => 'From visual inspection, does the common area fire detection/alarm system appear to be in good working condition?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No defects identified at the time of this assessment.

It was noted that a fault wasshowing on the fire alarm control panel. The fault should be cleared and the system restored to full working order.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        159 => 
        array (
            'id' => 279,
            'section_id' => 66,
            'description' => 'Is the Grade and Category of the common area fire detection/alarm system appropriate for the building type, occupancy and fire risk?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Being a purpose built residential block, designed to facilitate a, \'Defend in Place\' evacuation strategy, the provision of no communal fire alarm system is appropriate.

The Grade A, LD2 fire detection/alarm system is in line with recommendations in LACoRS.

The grade of alarm provided in an LD2 system to Grade D standard. For converted houses of 3 and more floors the normal recommended standard is Grade A. However, the communal escape route only covers the G & 1st floors. As such the grade D system provided is considered acceptable.

The current arrangements for the communal fire alarm system are not considered adequate, and the necessary provision/upgrading should be provided as detailed in the Hazard raised associated with this issue.
',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        160 => 
        array (
            'id' => 280,
            'section_id' => 66,
            'description' => 'Where a communal fire alarm system is provided, is a zone plan displayed adjacent to the fire alarm control panel?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'An appropriate fire alarm zone plan is displayed adjacent to the fire alarm control panel.

There is currently no zone plan displayed for the fire alarm system. One should be provided and displayed adjacent to the fire alarm control panel.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        161 => 
        array (
            'id' => 281,
            'section_id' => 66,
        'description' => 'If applicable, are independent domestic hard-wired smoke/heat alarm systems within the flats installed to a suitable standard? (Grade D LD3 minimum standard)',
            'score' => '0',
            'answer_type' => 4,
            'is_key' => 0,
            'good_answer' => '0',
        'preloaded_comment' => 'It is WCC policy for all tenanted flats to be provided with in-flat fire alarm systems to at least Grade D, LD3 standard, (to be upgraded to LD2 standard when voids occur). All of those flats inspected are provided with such systems. In the case of leasehold flats, it is the responsibility of the leaseholder to provide in-flat detectors/alarms.

It is WCC policy for all tenanted flats to be provided with in-flat fire alarm systems to at least Grade D, LD3 standard, (to be upgraded to LD2 standard when voids occur). It was noted during the assessmnet that this was not the case in those flats specified in the Hazard raised associated with this issue. Grade D, LD2 systems should be provided in these flats. In the case of leasehold flats, it is the responsibility of the leaseholder to provide in-flat detectors/alarms.

It is WCC policy for all tenanted flats to be provided with in-flat fire alarm systems to at least Grade D, LD3 standard, (to be upgraded to LD2 standard when voids occur). However, during the assessment it was noted that no such systems are provided in those flats detailed in the Hazard raised for this issue. LD2 systems should be installed in these flats now. In the case of leasehold flats, it is the responsibility of the leaseholder to provide in-flat detectors/alarms.
',
            'hazard_answer_value' => '11',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        162 => 
        array (
            'id' => 282,
            'section_id' => 66,
            'description' => 'Where required is a fire alert system provided for the building?',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Such a system is not required for a building of this height.

No such system is currently provided. However, WCC are committed to installing such systems in their buildings over 18m in line with the strong recommendation in national guidance. Such systems, which will be installed in compliance with BS 8629:2019, will be introduced on a risk based approach.
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        163 => 
        array (
            'id' => 283,
            'section_id' => 66,
        'description' => 'Is a social alarm system required to allow remote monitoring of any common fire alarm system and independent domestic hard-wired smoke/heat alarm systems within the flats? (Sheltered accommodation)',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'This is a general needs property. No social alarm system is required.',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        164 => 
        array (
            'id' => 284,
            'section_id' => 66,
            'description' => 'Are all other fire detection and alarm system issues satisfactory?',
            'score' => '0',
            'answer_type' => 1,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No other issues regarding the fire detection/alarm system noted during this assessment.',
            'hazard_answer_value' => '2',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        165 => 
        array (
            'id' => 285,
            'section_id' => 67,
        'description' => 'Is the level of compartmentation adequate? (Special consideration should be given to converted or non "purpose built" premises)',
            'score' => '0',
            'answer_type' => 4,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'This is a purpose built block, built to modern building regulation requirements. No evidence was seen to doubt adequate compartmentation.

Being a converted building of this age, adequate compartmentation between the flats cannot be guaranteed. As a compensatory measure, an LD2 communal fire alarm system has been provided, and a, Simultaneous Evacuation”, strategy implemented.
',
            'hazard_answer_value' => '11',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        166 => 
        array (
            'id' => 286,
            'section_id' => 67,
        'description' => 'Are hidden voids appropriately enclosed and/or fire-stopped? (consider above suspended ceilings and behind casings)',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'None noted during this assessment.

Voids, as detaied the the Hazard raised regarding this issue, were noted during this assessment. It is recommended that these areas are surveyed to determine whether or not the void needs to be FR and whether or not adequate fire stopping has been provided where services pass out of the void into other fire compartment.
',
            'hazard_answer_value' => '7,9',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        167 => 
        array (
            'id' => 287,
            'section_id' => 67,
            'description' => 'Are services risers, shafts, ducts and cupboards in the common area appropriately enclosed with fire resisting construction and adequately fire-stopped?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'Any riser and cupboards in the communal areas appear to have been adequately fire stopped.

There is a service riser as detailed in the Hazard raised regading this issue. It was not possible to access this riser at the time of the assessment. However, it  appears that it may not be adequately FR.  It is recommended that a survey of this void is carried to confirm whether or not the riser needs to be FR and whether or not adequate fire stopping has been carried out where services pass into/out of the void from other fire compartments. Any deficiencies identified by the survey should be addressed.

It was noted that adequate fire stopping has not been carried out in the electrical intake room, as detailed in the Hazard raised regading this issue. This should be carried out by a specialist 3rd party accreditted contractor.
',
            'hazard_answer_value' => '7,9',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        168 => 
        array (
            'id' => 288,
            'section_id' => 67,
        'description' => 'Are pipes and other services provided with adequate fire-stopping measures as required where they pass through fire resisting construction? (Consider fire collars etc)',
            'score' => '0',
            'answer_type' => 2,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'No defects noted at the time of this assessment.

It was noted that fire stopping does not appear to be adequate in those areas detailed in the Hazard raised regading this issue. 
',
            'hazard_answer_value' => '4',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        169 => 
        array (
            'id' => 289,
            'section_id' => 67,
        'description' => 'If a waste chute is provided within the building are adequate measures in place to limit fire spread/growth? (Consider provision of fusible link dampers and sprinklers etc)',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'There are no waste chutes at this block.

It was noted that not all of the hopper seals are in good servicable condition and should be replaced as detailed in the Hazard raised regading this issue.

An automatic shutter is provided at the base of the refuse chute.

The bin room is covered by a sprinkler system.

The hppers open directly into the escape staircase enclosure. It is recommended that an automatic shutter is provided at the base of the refuse chute.
',
            'hazard_answer_value' => '7',
            'hz_name' => NULL,
            'hz_verb_id' => NULL,
            'hz_noun_id' => NULL,
        ),
        170 => 
        array (
            'id' => 290,
            'section_id' => 67,
            'description' => 'Are any roof space voids present above the common areas provided with adequate compartmentation to support the evacuation strategy for the building?',
            'score' => '0',
            'answer_type' => 3,
            'is_key' => 0,
            'good_answer' => '0',
            'preloaded_comment' => 'This block has a flat roof. There are no roof spaces.

There is just one flat on the top floor. The dividing walls between this address and adjacent properties extend beyond the height of the roof.

It was not possible to access the roof spaces. Unless already completed, it is recommended that a survey is carried out to ensure that either, a) the ceilings between the top floor flats and roof spaces are a minimum of 60 minutes FR, or, b) the compartment walls between the top floor flats extend up to roof level, and should this found not to be the case, appropriate compartmentalisation should be provided.
',
        'hazard_answer_value' => '7',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    171 => 
    array (
        'id' => 291,
        'section_id' => 67,
    'description' => 'Are electrical installations/intakes enclosed in fire rated construction? (Where necessary)',
        'score' => '0',
        'answer_type' => 3,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'The electrical intake equipment is enclosed within the electrical intake room which provides a minimum of 30 minutes protection to the escape route.

The electrical intake equipment is located at high level in the entrance lobby. The enclosure in not adequately FR and should be replaced by an enclosure that is FR for a minimum of 30 minutes. The access hatch/door should be to FD30S standard.
',
        'hazard_answer_value' => '7',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    172 => 
    array (
        'id' => 292,
        'section_id' => 67,
        'description' => 'Where electrical meter cupboards in are in flat walls, is adequate compartmentation maintained?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There are no electrical meters in the flat walls at this block.

The electrical meters in the flat walls have a sealed FR glazed facia.

Many of the metal doors to the electric meters in the flat walls are unlocked, with several being damaged. 
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    173 => 
    array (
        'id' => 293,
        'section_id' => 67,
    'description' => 'If present, are common ventilation systems, ventilation ducts and grills adequate to limit fire spread/growth? (Consider dampers etc.)',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There are no ventilation systems in the communal areas at this block. Openable windows are the only form of ventilation to the staircase enclosure.

It was noted that the filter screens across the POVs are dirty. This could affect the air flow through the vent. Arrangements should be made for them to be cleaned.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    174 => 
    array (
        'id' => 294,
        'section_id' => 67,
        'description' => 'Are wall and ceiling linings appropriate to limit fire spread?',
        'score' => '0',
        'answer_type' => 3,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'The wall linings appear to be to Class 0 standard.

It was not possible to confirm the FR of wall and ceiling linings. However, the existing finishes are in reasonable condition and do not appear to present a significant risk to fire spread or safe escape. It is WCC poicy that all materials used for future redecorations will complying to Class 0 of BS476 parts 6&7 or BS EN13501- 1.
',
        'hazard_answer_value' => '7',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    175 => 
    array (
        'id' => 295,
        'section_id' => 67,
        'description' => 'If provided, are soft furnishings in common areas appropriate to limit fire spread/growth?',
        'score' => '0',
        'answer_type' => 3,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'No soft furnishings were noted as being in the communal areas.

The soft furnishings in the communal escape route appears to meet the current regulations.

The soft furnishings as detailed in the Hazard raised regarding this issue do not appear to meet current Furniture & Furnishing Regulations and should be removed from site.

Although appearing to meet the current Furniture & Furnishing Regulations, those soft furnishings detailed in the Hazard raised regarding this issue are torn, reducing their resistance to fire and should be replaced/removed.
',
        'hazard_answer_value' => '7',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    176 => 
    array (
        'id' => 296,
        'section_id' => 67,
        'description' => 'If provided, are curtains or drapes within common areas appropriate to limit fire spread/growth?',
        'score' => '0',
        'answer_type' => 3,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'No drapes or curtains were noted as being in the communal areas.',
        'hazard_answer_value' => '7',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    177 => 
    array (
        'id' => 297,
        'section_id' => 67,
    'description' => 'Are the external walls of the building satisfactory with regard to fire spread? (For buildings over 18m consider flammability of cladding/external insulation system if provided)',
        'score' => '0',
        'answer_type' => 3,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'The external walls are plain brick.

The external walls are plain render.

The external walls are blockwork enclosed in a Rockwall style insulation with a concrete render covering. It is recommended that, unless already carried out, a survey is carried out to ensure that the insulation is non-combustible. It found not to be the case then expert advice should be sought to consider it\'s removal.
',
        'hazard_answer_value' => '7',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    178 => 
    array (
        'id' => 298,
        'section_id' => 67,
        'description' => 'Are all other fire spread/compartmentation issues satisfactory?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'No other issues noted at the time of this assessment.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    179 => 
    array (
        'id' => 299,
        'section_id' => 68,
    'description' => 'Are portable fire extinguishers provided in the common areas? (Give details of any provision)',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There are no fire extinguishers provided in the communal areas.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    180 => 
    array (
        'id' => 300,
        'section_id' => 68,
        'description' => 'Is it considered appropriate to provide portable fire extinguishers given the building occupancy?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There are no employees on site and no training is provided to residents in the use of extinguishers. As such, it is not considered appropriate for PFFE to be provided in the communal parts.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    181 => 
    array (
        'id' => 301,
        'section_id' => 68,
    'description' => 'Is the existing provision of portable fire extinguishers considered adequate for the building (type, number, location etc.)?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'Taking into account the fire risks present on the premises, the provision of PFEE is considered adequate.

Taking into account the fire risks present on the premises and that no training is provided to residents, the provision of PFEE is considered acceptable.

Taking into account the fire risks present on the premisesand the fact that contractors are required to provide their own PFFE, the provision of no PFEE is considered acceptable.
',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    182 => 
    array (
        'id' => 302,
        'section_id' => 68,
        'description' => 'Are all fire extinguishing appliances suitably located and readily accessible?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There is a CO2 extinguisher located in the electrical intake cupboard. It is accessible to any employees and contractors, but not to the residents.

There are CO2 extinguishers located in landlord areas. Taking into account the fire risks present on the premises, the provision of PFEE is considered adequate.

No PFFE has been provided at this address. 
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    183 => 
    array (
        'id' => 303,
        'section_id' => 69,
        'description' => 'Is the building provided with drop key override switch facilities for Fire and Rescue Service access?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'The entrance door into the building is secured by an EVVA lock, which is released either by resident’s keys, or the answerphone system. There is no override for this lock. However, any fire appliances attending an incident at the block would be able to park directly outside the entrance, and using equipment immediately available from the fire appliances, it would be easy for fire crews to force access into the block if necessary.

There is a standard drop key override provided at the main entrance into the building. All frontline appliances based in Westminster and surrounding stations have also now been provided with entry fobs.

There is a standard drop key override provided at the main entrance into the building. However, it was noted that it is defective. It is recommended that it is repaired to full working order.

The standard drop key override for the main entrance door has been decommissioned. However, recent conversations between WCC and LFB senior managers indicated that LFB are not happy with this arrangement. As such it is recommended that the override is reinstated by which ever system has been decided going forward, (standard drop key or new ASSA key system).

Standard drop key overrides are provided at the main entrances into the building and for the security doors into the corridors. All frontline appliances based in Westminster and surrounding stations have also now been provided with entry fobs. 
',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    184 => 
    array (
        'id' => 304,
        'section_id' => 69,
    'description' => 'Is the building provided with a fire mains system? (Dry or wet riser etc.)',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'As would be expected for building of this height, a DRM is provided.

No DRM is provided or required at this address.

Where any storey has a floor height in excess of 18 metres above ground level current building regulations recommend that a dry rising main for fire fighting operations is provided. It is noted that no DRM has been provided for this block. Although the provision of a DRM was not a requirement at the time at which the block was built, unless one has already been carried out, it is recommended that a feasibility study is carried out to consider the possibility of retrofitting a DRM for the block.
',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    185 => 
    array (
        'id' => 305,
        'section_id' => 69,
    'description' => 'Is the building provided with a lift (or lifts) used for fire safety purposes? (Firefighting, fireman’s or evacuation lift)',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There are no lifts provided at this address.

A lift is provided, but it is neither a firefighting nor fireman\'s lift.

The lift(s) provided at this block is not a firefighting lift. However, override facilities are provided, enabling firefighters attending any incident at the block to take control of the lift car.
',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    186 => 
    array (
        'id' => 306,
        'section_id' => 69,
    'description' => 'Is the building provided with disabled evacuation aids? (Evacuation chairs, sheets, mats or sledges etc.)',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'This is a general needs block. The risk assessor was not made aware of any persons who may be vulnerable and/or may have a mental or physical disability, and none were identified at the time of this assessment. No aids were noted as having been provided to assist persons with mobility issues to evacuate the building. 
However, see Section K.16.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    187 => 
    array (
        'id' => 307,
        'section_id' => 69,
    'description' => 'Is a sprinkler system provided within the building? (provide details of type and extent)',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'No sprinkler system is provided or required at this address.

No sprinkler system is provided. As the block is over 60m high consideration has been given to retrofitting sprinklers in compliance with national recommendations. However, due to the considerable number of leaseholders in the block this would be difficult to effectively implement under current legislation.

No sprinkler system is provided or required for the residential areas at this address. There is however sprinkler cover in landlord areas.
',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    188 => 
    array (
        'id' => 308,
        'section_id' => 69,
        'description' => 'Are hose reels provided within the building?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'No hosereels are provided or required at this address.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    189 => 
    array (
        'id' => 309,
        'section_id' => 69,
        'description' => 'Is any other relevant fire safety system or equipment installed?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There are no other relevant fire safety systems or equipment installed.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    190 => 
    array (
        'id' => 310,
        'section_id' => 69,
        'description' => 'Is a premises information box provided for the premises?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'No premises information box is provided or required for this address.

No premises information box is currently provided atthis address. However, it is WCC policy that they will be installed in blocks over 18m high when and where fire alert evacuation systems are installed. 
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    191 => 
    array (
        'id' => 311,
        'section_id' => 69,
        'description' => 'Where a premises information box is provided is all information within the box complete and up to date?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'No premises information box is provided or required for this address.

Details of the physical ability of each resident are provided in the premises information box. 
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    192 => 
    array (
        'id' => 312,
        'section_id' => 70,
    'description' => 'Has a competent person(s) been appointed within the organisation to assist in undertaking preventative and protective fire safety measures?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'The Housing Fire Manager is the appointed, “Competent Person”.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    193 => 
    array (
        'id' => 313,
        'section_id' => 70,
        'description' => 'Is there a suitable record of the fire safety arrangements?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There is a Fire Safety Policy in place. Records of all testing and maintenance of the fire safety equipment are held centrally. They were not seen at the time of this assessment.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    194 => 
    array (
        'id' => 314,
        'section_id' => 70,
        'description' => 'Are there appropriate procedures in place in the event of fire and are these documented?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'Any reported fire where residential areas are affected are investigated, the record of which is maintained centrally.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    195 => 
    array (
        'id' => 315,
        'section_id' => 70,
        'description' => 'Has information on fire procedures been disseminated to residents?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
    'preloaded_comment' => 'An FAN(s), giving details of the evacuation strategy for the building, are displayed in the communal areas. New occupiers are also provided with a tenants pack, which includes fire safety procedures.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    196 => 
    array (
        'id' => 316,
        'section_id' => 70,
        'description' => 'Is fire safety information disseminated to residents?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'In addition to the FANs and tenants pack, fire safety advice is inlude in the bi weeky Residents News Letter.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    197 => 
    array (
        'id' => 317,
        'section_id' => 70,
        'description' => 'Are there suitable arrangements for calling the Fire Service?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
    'preloaded_comment' => 'The FAN(s) includes instruction for the person discovering the fire to call the fire brigade.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    198 => 
    array (
        'id' => 318,
        'section_id' => 70,
        'description' => 'Are there suitable arrangements for meeting them on arrival and providing relevant information?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'As with other general needs blocks, there are no arrangements for meeting the fire service on their arrival.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    199 => 
    array (
        'id' => 319,
        'section_id' => 70,
        'description' => 'Are there suitable fire assembly points away from any risk?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'As with other general needs blocks, it is not practical to expect residents to report to a nominated assembly point and as such, none is designated.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    200 => 
    array (
        'id' => 320,
        'section_id' => 70,
        'description' => 'Are there adequate procedures in place for the evacuation of people with a disability who are likely to be present?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'This is a general needs block. The risk assessor was not made aware of any persons who may be vulnerable and/or may have a mental or physical disability, and none were identified at the time of this assessment. However, see Sections K.17 & S.4.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    201 => 
    array (
        'id' => 321,
        'section_id' => 70,
        'description' => 'Are staff nominated to use fire extinguishing appliances in the event of fire?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There are no permanent members of staff at this general needs block.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    202 => 
    array (
        'id' => 322,
        'section_id' => 70,
        'description' => 'Are staff nominated to assist with evacuations in the event of fire?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There are no permanent members of staff at this general needs block. As with all general needs blocks, residents would be expected to evacuate the premises without assistance.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    203 => 
    array (
        'id' => 323,
        'section_id' => 70,
        'description' => 'Are the provisions and controls in place to ensure a safe evacuation from the premises of all occupants adequate?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'This is a general needs block. The necessary provisions and controls have been put in place to ensure a safe evacuation of all occupants who able able to evacuate without assistance. However, see Section K.17.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    204 => 
    array (
        'id' => 324,
        'section_id' => 70,
        'description' => 'Is there appropriate liaison with the local Fire and Rescue Service?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'Being a general needs block no liaison is considered necessary with the LFB specific to this block. However, the block is subject to periodic inspection by LFB fire safety officers in line with their risk based programme of inspections, and liaison between LFB and WCC is maintained at strategic level.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    205 => 
    array (
        'id' => 325,
        'section_id' => 70,
        'description' => 'Are routine in-house fire safety checks carried out?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'A monthly HSS inspection is undertaken by the area compliance team. ',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    206 => 
    array (
        'id' => 326,
        'section_id' => 70,
        'description' => 'Are all other fire safety management issues satisfactory?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'No other fire safety management issues were noted at the time of this assessment.

Unless already obtained, It is recommended that requests are made to the, "Rosponsible Persons", of the commercial premises at G/F level for a list of the significant finding identified and listed in the reports for their latest FRAs. Particular attention should be given to comments regarding any issues identified which could affect the residents in the flats.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    207 => 
    array (
        'id' => 327,
        'section_id' => 71,
        'description' => 'Do staff receive adequate induction and annual refresher fire safety training? To include fire risks in the premises, fire safety measures in the building, action in the event of fire and on hearing alarm, location and use of fire extinguishers?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'WCC personnel are provided with mandatory fire awareness training on joining the organisation. This training clearly informs them what to do in the event of fire.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    208 => 
    array (
        'id' => 328,
        'section_id' => 71,
        'description' => 'Are the staff nominated to use fire extinguishing appliances, in the event of fire, given appropriate additional training?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'This is a general needs block. There are no employees on site on a day to day basis.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    209 => 
    array (
        'id' => 329,
        'section_id' => 71,
        'description' => 'Are staff given additional training to cover any specific roles and responsibilities?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'None considered necessary at this general needs address.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    210 => 
    array (
        'id' => 330,
        'section_id' => 71,
        'description' => 'Are fire drills carried out at appropriate intervals?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'As with other general needs blocks, no fire drills are carried out.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    211 => 
    array (
        'id' => 331,
        'section_id' => 72,
        'description' => 'Is the common area fire detection/alarm system tested and serviced in accordance with relevant guidance?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There is a strict system of maintenance and testing of all facilities in accordance to current statutory regulations to ensure that the WCC fulfils its legal obligations. A term contract is in place for the monthly, 6 monthly and 12 monthly testing of the communal fire alarm system. Records are kept and can be made available to view at the WCC central offices.

There is no fire alarm system covering the communal parts at this address.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    212 => 
    array (
        'id' => 332,
        'section_id' => 72,
        'description' => 'Is the common area emergency lighting system tested and serviced in accordance with relevant guidance?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There is a strict system of maintenance and testing of all facilities in accordance to current statutory regulations to ensure that the WCC fulfils its legal obligations. A term contract is in place for the monthly and 12 monthly testing of the emergency lighting. Records are kept and can be made available to view at the WCC central offices.

There is no emergency lighting system covering the communal parts at this address.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    213 => 
    array (
        'id' => 333,
        'section_id' => 72,
        'description' => 'Are fire extinguishers tested and serviced in accordance with relevant guidance?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There is a strict system of maintenance and testing of all facilities in accordance to current statutory regulations to ensure that the WCC fulfils its legal obligations. A term contract is in place for the 12 monthly testing of the PFFE. The date of the testing is provided on the individual extinguisher. Records are also kept and can be made available to view at the WCC central offices.

There are no portable fire extinguishers provided at this address.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    214 => 
    array (
        'id' => 334,
        'section_id' => 72,
        'description' => 'Are fire mains inspected, tested and serviced in accordance with relevant guidance?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There is a strict system of maintenance and testing of all facilities in accordance to current statutory regulations to ensure that the WCC fulfils its legal obligations. A term contract is in place for the 6 monthly testing of the DRM. Records are kept and can be made available to view at the WCC central offices.

There are no fire mains at this address.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    215 => 
    array (
        'id' => 335,
        'section_id' => 72,
        'description' => 'Is the lightning protection system inspected and tested in accordance with relevant guidance?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There is a strict system of maintenance and testing of all facilities in accordance to current statutory regulations to ensure that the WCC fulfils its legal obligations. A term contract is in place for the 12 monthly testing of the lightning protection system. Records are kept and can be made available to view at the WCC central offices.

There is no lightning protection system at this address.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    216 => 
    array (
        'id' => 336,
        'section_id' => 72,
        'description' => 'Are access control systems inspected, tested and serviced in accordance with relevant guidance?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'A term contract is in place for the 6 monthly testing of the access control system. Records are kept and can be made available to view at the WCC central offices.

There are no access controls at this address.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    217 => 
    array (
        'id' => 337,
        'section_id' => 72,
    'description' => 'Are lifts in the building used for fire safety purposes inspected, tested and serviced in accordance with relevant guidance? (Firefighting, fireman’s or evacuation lift)',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'A term contract is in place for the periodic testing of the firefighter lifts. They are tested monthly by a specialist contractor. Records are kept and can be made available to view at the WCC central offices.

There are no lifts at this address which have facilities to assist firefighting.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    218 => 
    array (
        'id' => 338,
        'section_id' => 73,
        'description' => 'Is there a log book on the premises?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'As with other general needs blocks, no fire log book is kept on the premises.',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    219 => 
    array (
        'id' => 339,
        'section_id' => 73,
        'description' => 'Are details of fire drills recorded?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'As with other general needs blocks, no fire drills are carried out. 
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    220 => 
    array (
        'id' => 340,
        'section_id' => 73,
        'description' => 'Are details of fire safety training recorded?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'Records of staff FS training are maintained centrally.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    221 => 
    array (
        'id' => 341,
        'section_id' => 73,
        'description' => 'Are routine in-house fire safety checks recorded?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'Records of the monthly H & S inspections are maintained at the area office.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    222 => 
    array (
        'id' => 342,
        'section_id' => 73,
        'description' => 'Are fire alarm system inspections, tests and servicing recorded?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'Records are maintained centrally.

There is no communal fire alarm system at this address.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    223 => 
    array (
        'id' => 343,
        'section_id' => 73,
        'description' => 'Are emergency lighting system inspections, tests and servicing recorded?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'Records are maintained centrally

There is no emergency lighting in the communal areas at this address.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    224 => 
    array (
        'id' => 344,
        'section_id' => 73,
        'description' => 'Are records kept of the fire extinguisher inspections, tests and servicing?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'The annual test dates are recorded on the individual extinguishers as well records which are maintained centrally.

There are no fire extinguishers provided at this address.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    225 => 
    array (
        'id' => 345,
        'section_id' => 73,
        'description' => 'Are fire mains inspections, tests and services recorded?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'Records are maintained centrally.

There are no fire mains at this address.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    226 => 
    array (
        'id' => 346,
        'section_id' => 73,
        'description' => 'Are inspections, tests and servicing of lifts used for fire safety purposes recorded?',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'Records are maintained centrally.

There are no lifts at this address equipped with facilities to assist firefighting.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    227 => 
    array (
        'id' => 347,
        'section_id' => 74,
    'description' => 'Are any wiring systems visible within the common escape route(s) supported in accordance with BS 7671:2011 (as amended) such that they will not be liable to premature collapse in the event of fire? (Consider the use of non-metallic cable clips, ties etc.)',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There were no issues noted during this assessment regarding supports to electrical cables.

It was noted that electrical cables within the communal escape routes are supported in plastic supports/trunking. BS7671 is non-retrospective, but going forward, all works carried out will comply with BS7671 2018.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    228 => 
    array (
        'id' => 348,
        'section_id' => 75,
    'description' => 'Where there are fixed gas installations present within the common parts of the building are Gas Regs. Being met? (Consider gas pipework and meters)',
        'score' => '0',
        'answer_type' => 2,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'No fixed gas installations were noted as being present in the common parts of the building.

Where there are gas pipes in the escape routes, the pipes are screwed steel pipes, which appear to be in compliance with Gas Safety (Installation and Use) Regulations 1998 (GSIUR) as amended, and as such considered acceptable.
',
        'hazard_answer_value' => '4',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
    229 => 
    array (
        'id' => 349,
        'section_id' => 76,
        'description' => 'Are all other issues deemed satisfactory?',
        'score' => '0',
        'answer_type' => 1,
        'is_key' => 0,
        'good_answer' => '0',
        'preloaded_comment' => 'There were no issues noted during this assessment that are not already mentioned in other areas of this report.',
        'hazard_answer_value' => '2',
        'hz_name' => NULL,
        'hz_verb_id' => NULL,
        'hz_noun_id' => NULL,
    ),
));
        
        
    }
}
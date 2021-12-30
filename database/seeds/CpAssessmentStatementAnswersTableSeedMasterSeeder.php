<?php

use Illuminate\Database\Seeder;

class CpAssessmentStatementAnswersTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cp_assessment_statement_answers')->delete();
        
        \DB::table('cp_assessment_statement_answers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'question_id' => 194,
                'description' => 'Access was gained to the one electrical intake cupboard at this address, which is located XXXXXX',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'question_id' => 194,
                'description' => 'It was not possible to access the main electrical intake room which is under the control of UKPN, the door for which is secured locked shut by a non-standard padlock.',
                'order' => 1,
                'other' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'question_id' => 194,
            'description' => 'It was not possible to locate the electrical intake cupboard. It is recommended that the enclosure is located and surveyed to confirm: a) The good condition of the electrical intake equipment, and b) That the enclosure is a minimum of 30 minutes FR.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        3 => 
        array (
            'id' => 4,
            'question_id' => 194,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        4 => 
        array (
            'id' => 5,
            'question_id' => 195,
            'description' => 'It is policy for WCC to carry out statuary 5 yearly inspections and testing of the landlord\'s electrical supply system. Records of all testing inspection and maintenance are held centrally',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        5 => 
        array (
            'id' => 6,
            'question_id' => 195,
            'description' => 'It is policy for WCC to carry out statuary 5 yearly inspections and testing of the landlord\'s electrical supply system. However, the test for this address is overdue',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        6 => 
        array (
            'id' => 7,
            'question_id' => 195,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        7 => 
        array (
            'id' => 8,
            'question_id' => 202,
            'description' => 'No breaches noted at the time of this assessment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        8 => 
        array (
            'id' => 9,
            'question_id' => 202,
            'description' => 'Discarded smoking materials were loacted in XXXXX. Additional, "No Smoking", signage should be prominantly displayed in strategic locations throughout the building.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        9 => 
        array (
            'id' => 10,
            'question_id' => 202,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        10 => 
        array (
            'id' => 11,
            'question_id' => 204,
        'description' => 'No Photovoltaic, (PV), cells were identified at this address.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        11 => 
        array (
            'id' => 12,
            'question_id' => 204,
        'description' => 'It was not possible to locate or identify the isolation facilities for the Photovoltaic, (PV), cells, which are located on the roof. Appropriate signage should be displayed to indicate the location of these facilities, to assist the fire and rescue service in the event of a fire at the premises.?',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        12 => 
        array (
            'id' => 13,
            'question_id' => 204,
        'description' => 'Adequate signage is provided to clearly indicate the location of the isolation facilities for the Photovoltaic, (PV), cells, which are located on the roof.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        13 => 
        array (
            'id' => 14,
            'question_id' => 204,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        14 => 
        array (
            'id' => 15,
            'question_id' => 205,
            'description' => 'The single entrance into the building is secured locked shut. It can only be opened from outside by the resident’s fobs/keys.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        15 => 
        array (
            'id' => 16,
            'question_id' => 205,
            'description' => 'All of the entrances into the building are secured locked shut. They can only be opened from outside by the resident’s fobs/keys, entryphone system or LFB override.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        16 => 
        array (
            'id' => 17,
            'question_id' => 205,
            'description' => 'The electromagnetic lock securing the entrance door into the block is defective, leaving the building unsecure.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        17 => 
        array (
            'id' => 18,
            'question_id' => 205,
            'description' => 'Access to the block is unrestricted. Although there was no evidence noted of any arson, unrestricted access increases the risk of an arson attack. As such, it is recommended that the provision of a controlled access system is considered at the time of the next major works carried out at the block.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        18 => 
        array (
            'id' => 19,
            'question_id' => 205,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        19 => 
        array (
            'id' => 20,
            'question_id' => 206,
            'description' => 'A dedicated bin room is located at G level. It is accessed via externally doors.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        20 => 
        array (
            'id' => 21,
            'question_id' => 206,
            'description' => 'Dedicated bin stores are located at G level. They are accessed via external doors.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        21 => 
        array (
            'id' => 22,
            'question_id' => 206,
            'description' => 'There are no bins provided at this address. Residents dispose of their refuse in the paladins provided in surrounding streets.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        22 => 
        array (
            'id' => 23,
            'question_id' => 206,
            'description' => 'There are no bins provided at this address. Residents deposit their rubbish in the street on designated days. ',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        23 => 
        array (
            'id' => 24,
            'question_id' => 206,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        24 => 
        array (
            'id' => 25,
            'question_id' => 207,
            'description' => 'The bin room is secured locked shut.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        25 => 
        array (
            'id' => 26,
            'question_id' => 207,
            'description' => 'The bin rooms are secured locked shut.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        26 => 
        array (
            'id' => 27,
            'question_id' => 207,
            'description' => 'The bin rooms are unlocked. However, the hoppers are located on open balconies and risks are considered low. Arrangements are considered acceptable.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        27 => 
        array (
            'id' => 28,
            'question_id' => 207,
            'description' => 'The bin rooms are unlocked. The hoppers are located in FR lobbies.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        28 => 
        array (
            'id' => 29,
            'question_id' => 207,
            'description' => 'The bin rooms are unlocked. The hoppers open directly into the escape staircase enclosure.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        29 => 
        array (
            'id' => 30,
            'question_id' => 207,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        30 => 
        array (
            'id' => 31,
            'question_id' => 214,
            'description' => 'A lightning protection system has been provided for the block. ',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        31 => 
        array (
            'id' => 32,
            'question_id' => 214,
            'description' => 'No lightning protection system appears to be provided for the premise. It is considered most unlikely that one would be required for building of this height in this location.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        32 => 
        array (
            'id' => 33,
            'question_id' => 214,
            'description' => 'No lightning protection system appears to be provided for the premise. At building design there was no requirement to install lightening protection. However, taking into account the height of this building and of surrounding buildings, in the event of any major structural works or major alterations to the roof area, it is recommended that a feasibility assessment should be undertaken on the property to identify if an LPS is required in line with BS EN 62305- 2.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        33 => 
        array (
            'id' => 34,
            'question_id' => 214,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        34 => 
        array (
            'id' => 35,
            'question_id' => 217,
            'description' => 'No combustibles were noted as being located adjacent to any ignition sources at the time of this assessment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        35 => 
        array (
            'id' => 36,
            'question_id' => 217,
            'description' => 'Combustibles were noted as being located in the electrical intake cupboard.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        36 => 
        array (
            'id' => 37,
            'question_id' => 217,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        37 => 
        array (
            'id' => 38,
            'question_id' => 218,
            'description' => 'At the time of this assessment the escape routes were clear.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        38 => 
        array (
            'id' => 39,
            'question_id' => 218,
            'description' => 'Combustible materials were found to be located within the protected escape route in the following locations:
XXXXXX',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        39 => 
        array (
            'id' => 40,
            'question_id' => 218,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        40 => 
        array (
            'id' => 41,
            'question_id' => 219,
            'description' => 'No trip hazards were found to be present in any of the escape routes at the time of this assessment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        41 => 
        array (
            'id' => 42,
            'question_id' => 219,
            'description' => 'The combustible items listed in the question above are also causing a trip hazard.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        42 => 
        array (
            'id' => 43,
            'question_id' => 219,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        43 => 
        array (
            'id' => 44,
            'question_id' => 221,
            'description' => 'No other housekeeping issues noted at the time of this assessment. ',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        44 => 
        array (
            'id' => 45,
            'question_id' => 221,
            'description' => 'It was noted that the time of this assessment that unwanted and unnecessary combustible items were located in the following landlord areas: XXXXXXXXX. It is recommended that arrangements are made for all unnecessary items currently located in the basement areas to be removed from site.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        45 => 
        array (
            'id' => 46,
            'question_id' => 221,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        46 => 
        array (
            'id' => 47,
            'question_id' => 228,
            'description' => 'This property was built to modern building regulations. The walls protecting the escape route appear to provide a minimum of 60 minutes protection from any risk, with doors providing a minimum of 30 minutes FR.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        47 => 
        array (
            'id' => 48,
            'question_id' => 228,
            'description' => 'As with many properties of this age, there is no lobby protection to the staircases from the FEDs. Although not ideal, it would meet the recommendations in LACoRS, the relevant guidance document for a property of this nature, if the doors protecting the escape routes were to the recommended standard. At present, this is not the case. See sections L, M, & Q, ',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        48 => 
        array (
            'id' => 49,
            'question_id' => 228,
        'description' => 'Lobby protection is provided to the staircase(s).',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        49 => 
        array (
            'id' => 50,
            'question_id' => 228,
            'description' => 'The building was deigned to provide adequate protection to the escape routes. However, see sections L, M & Q',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        50 => 
        array (
            'id' => 51,
            'question_id' => 228,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        51 => 
        array (
            'id' => 52,
            'question_id' => 232,
            'description' => 'There are no doors on the escape routes from this block.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        52 => 
        array (
            'id' => 53,
            'question_id' => 232,
            'description' => 'The final exit door is opened by releasing a simple catch/handle. There are no revolving or sliding doors.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        53 => 
        array (
            'id' => 54,
            'question_id' => 232,
        'description' => 'The electromagnetic lock(s) securing the final exit gate(s) are released using the push button releases, which are located adjacent to the gates. There are no revolving or sliding doors.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        54 => 
        array (
            'id' => 55,
            'question_id' => 232,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        55 => 
        array (
            'id' => 56,
            'question_id' => 233,
            'description' => 'There are no doors on the escape routes from this block.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        56 => 
        array (
            'id' => 57,
            'question_id' => 233,
            'description' => 'The final exit door is opened by releasing a simple catch/handle.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        57 => 
        array (
            'id' => 58,
            'question_id' => 233,
        'description' => 'The final exit door(s) is secured locked shut by an electromagnetic lock which can be released using the push button release(s) adjacent to the door(s).',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        58 => 
        array (
            'id' => 59,
            'question_id' => 233,
        'description' => 'The final exit door(s) is secured locked shut by an electromagnetic lock which can be released using the simple latch/handle(s)',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        59 => 
        array (
            'id' => 60,
            'question_id' => 233,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        60 => 
        array (
            'id' => 61,
            'question_id' => 234,
            'description' => 'There are no doors at this address which are secured by electromagnetic locks.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        61 => 
        array (
            'id' => 62,
            'question_id' => 234,
            'description' => 'It was confirmed by WCC Homes M & E Team that the electromagnetic lock on the entrance door at this block, “Fails Safe”, to open in the event of a mains power failure.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        62 => 
        array (
            'id' => 63,
            'question_id' => 234,
        'description' => 'Although the final exit door(s) is secured by an electromagnetic lock(s), it can be released using the simple mechanical latch/handle. As such, there is no requirement for the electromagnet to fail safe to open.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        63 => 
        array (
            'id' => 64,
            'question_id' => 234,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        64 => 
        array (
            'id' => 65,
            'question_id' => 235,
        'description' => 'The final exit door(s) opens in the direction of travel.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        65 => 
        array (
            'id' => 66,
            'question_id' => 235,
        'description' => 'The final exit(s) opens against the direction of travel. However, taking into consideration the maximum number of persons ever likely to need to use this door to escape from a fire, this is considered acceptable.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        66 => 
        array (
            'id' => 67,
            'question_id' => 235,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        67 => 
        array (
            'id' => 68,
            'question_id' => 238,
            'description' => 'There is just a single means of escape.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        68 => 
        array (
            'id' => 69,
            'question_id' => 238,
            'description' => 'Adequate separation is provided between the escape routes.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        69 => 
        array (
            'id' => 70,
            'question_id' => 238,
            'description' => 'There is no physical barrier between the exits at either end of the block. However, they are a considerable distance apart, and any fire affecting one of the exits will not affect the other.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        70 => 
        array (
            'id' => 71,
            'question_id' => 238,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        71 => 
        array (
            'id' => 72,
            'question_id' => 239,
            'description' => 'There is no requirement for the corridors to be sub-divided.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        72 => 
        array (
            'id' => 73,
            'question_id' => 239,
            'description' => 'The corridors are sub-divided by FD30 fire doors.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        73 => 
        array (
            'id' => 74,
            'question_id' => 239,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        74 => 
        array (
            'id' => 75,
            'question_id' => 240,
        'description' => 'The escape staircase(s) exit at G level to a place(s) of, "Ultimate Safety", in XXXXX',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        75 => 
        array (
            'id' => 76,
            'question_id' => 240,
            'description' => 'All escape routes lead to places of, "Ultimate Safety".',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        76 => 
        array (
            'id' => 77,
            'question_id' => 240,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        77 => 
        array (
            'id' => 78,
            'question_id' => 241,
            'description' => 'There are openable windows throughout the escape staircase enclosure.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        78 => 
        array (
            'id' => 79,
            'question_id' => 241,
            'description' => 'The escape balconies are open to air. The escape staircases are open at each level.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        79 => 
        array (
            'id' => 80,
            'question_id' => 241,
        'description' => 'A POV is provided at the head of the escape staircase enclosure(s).',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        80 => 
        array (
            'id' => 81,
            'question_id' => 241,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        81 => 
        array (
            'id' => 82,
            'question_id' => 244,
            'description' => 'No other issues regarding the means of escape from this premises were identified.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        82 => 
        array (
            'id' => 83,
            'question_id' => 244,
        'description' => 'The escape staircase(s) has plain concrete steps. It is recommended that stair nosings, complying with relevant guidance given in; Building Regulations Document K, Building Regulations Document  M, BS 8300, BS 9266 and BRIP-IP15/03, are provided on the steps in both staircases.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        83 => 
        array (
            'id' => 84,
            'question_id' => 244,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        84 => 
        array (
            'id' => 85,
            'question_id' => 246,
            'description' => 'All of the FEDs appear to be a minimum of 30 miuntes FR',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        85 => 
        array (
            'id' => 86,
            'question_id' => 246,
            'description' => 'Those FEDs, as detailed in the Hazard raised relating to this issue, do not appear to be adequately FR and are in need of upgrading/replacement by doors which are a minimum of 30 minutes FR.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        86 => 
        array (
            'id' => 87,
            'question_id' => 246,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        87 => 
        array (
            'id' => 88,
            'question_id' => 247,
            'description' => 'All of the FEDs appeared to be in acceptable condition and well fitting. No specific defects were noted at the time of this assessment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        88 => 
        array (
            'id' => 89,
            'question_id' => 247,
            'description' => 'Those FEDS, as detailed in the Hazard raised relating to this issue, are damaged/defective and in need of repair/replacement.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        89 => 
        array (
            'id' => 90,
            'question_id' => 247,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        90 => 
        array (
            'id' => 91,
            'question_id' => 248,
            'description' => 'None of the FEDs are glazed.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        91 => 
        array (
            'id' => 92,
            'question_id' => 248,
            'description' => 'Where there are glazed units in the FEDs they are either Georgian glass or FR pyroglazing.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        92 => 
        array (
            'id' => 93,
            'question_id' => 248,
            'description' => 'Those glazing panels in FEDs as detailed in the Hazard raised relating to this issue, do not appear to be adequately FR and are in need of replacement by panels which are a minimum of 30 minutes FR.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        93 => 
        array (
            'id' => 94,
            'question_id' => 248,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        94 => 
        array (
            'id' => 95,
            'question_id' => 249,
            'description' => 'There are no fanlights to the FEDs in this block.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        95 => 
        array (
            'id' => 96,
            'question_id' => 249,
            'description' => 'There are Georgian glazed panels above all of the FEDs. All panels were in good order and appeared to be well fitted.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        96 => 
        array (
            'id' => 97,
            'question_id' => 249,
            'description' => 'There are glazing panels in the FEDs. However, as they are above 1100mm above balcony level there is no requirement for them to be FR.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        97 => 
        array (
            'id' => 98,
            'question_id' => 249,
            'description' => 'Those fanlights as detailed in the Hazard raised relating to this issue, do not appear to be adequately FR and are in need of replacement by panels which are a minimum of 30 minutes FR.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        98 => 
        array (
            'id' => 99,
            'question_id' => 249,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        99 => 
        array (
            'id' => 100,
            'question_id' => 250,
            'description' => 'There are no side panels to the FEDs in this block.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        100 => 
        array (
            'id' => 101,
            'question_id' => 250,
            'description' => 'Where provided, the glazing panels to the sides of the FEDs are Georgian glass.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        101 => 
        array (
            'id' => 102,
            'question_id' => 250,
            'description' => 'Those side panels as detailed in the Hazard raised relating to this issue, do not appear to be adequately FR and are in need of replacement by panels which are a minimum of 30 minutes FR.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        102 => 
        array (
            'id' => 103,
            'question_id' => 250,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        103 => 
        array (
            'id' => 104,
            'question_id' => 251,
            'description' => 'National guidance makes no requirement for self closers to be provided on the FEDs at this address.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        104 => 
        array (
            'id' => 105,
            'question_id' => 251,
            'description' => 'All of the FEDs inspected were found to be provided with self closers to BS EN 1154 standard.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        105 => 
        array (
            'id' => 106,
            'question_id' => 251,
            'description' => 'It was noted that  the self closers on those communal fire doors, as detailed in the Hazard raised relating to this issue, were either missing or deffective.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        106 => 
        array (
            'id' => 107,
            'question_id' => 251,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        107 => 
        array (
            'id' => 108,
            'question_id' => 252,
            'description' => 'National guidance makes no requirement for intumescent strips and cold smoke seals to be provided on the FEDs at this address.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        108 => 
        array (
            'id' => 109,
            'question_id' => 252,
            'description' => 'National guidance recommends that FEDs in a premises of this height and nature are to FD30S standard. They all appear to be to this standard.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        109 => 
        array (
            'id' => 110,
            'question_id' => 252,
            'description' => 'National guidance recommends that FEDs in a premises of this height and nature are to FD30S standard. It could not be confirmed that this is the case and it is recommended that the FEDs in this block are included in the planned survey of FEDs.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        110 => 
        array (
            'id' => 111,
            'question_id' => 252,
            'description' => 'National guidance recommends that FEDs in a premises of this height and nature are to FD30S standard. It was noted that the intumescent strips and cold smoke seals on the FEDs to those flats detailed in the Hazard raised relating to this issue, are missing or defective.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        111 => 
        array (
            'id' => 112,
            'question_id' => 252,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        112 => 
        array (
            'id' => 113,
            'question_id' => 253,
            'description' => 'There are no letter boxes in the FEDs at this address.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        113 => 
        array (
            'id' => 114,
            'question_id' => 253,
            'description' => 'No defects noted at the time of this assessment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        114 => 
        array (
            'id' => 115,
            'question_id' => 253,
            'description' => 'It was noted that the letterboxes in those communal fire doors, as detailed in the Hazard raised relating to this issue, were damaged and in need of repair/replacement.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        115 => 
        array (
            'id' => 116,
            'question_id' => 253,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        116 => 
        array (
            'id' => 117,
            'question_id' => 255,
            'description' => 'The doors from landlord areas leading into the escape route appear to be to be to at least notional FD30 standard.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        117 => 
        array (
            'id' => 118,
            'question_id' => 255,
            'description' => 'Those communal fire doors detailed in the Hazard raised regading this issue, do not appear to be adequately FR and should be replaced/upgraded.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        118 => 
        array (
            'id' => 119,
            'question_id' => 255,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        119 => 
        array (
            'id' => 120,
            'question_id' => 256,
            'description' => 'No defects noted during this assessment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        120 => 
        array (
            'id' => 121,
            'question_id' => 256,
            'description' => 'It was noted that  those communal fire doors, as detailed in the Hazard raised relating to this issue, were damaged and in need of repair/replacement.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        121 => 
        array (
            'id' => 122,
            'question_id' => 256,
            'description' => 'It was noted that the gap between the sides of the doors and their frames of those communal fire doors, as detailed in the Hazard raised relating to this issue, are excessively large and the doors are in need of adjustment/repair.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        122 => 
        array (
            'id' => 123,
            'question_id' => 256,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        123 => 
        array (
            'id' => 124,
            'question_id' => 257,
            'description' => 'None of the communal doors include any glazing elements.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        124 => 
        array (
            'id' => 125,
            'question_id' => 257,
            'description' => 'Where there are glazing elements in the communal doors, those elements are either Georgian glass or FR glazing panels, and appear to be in good condition and well fitting and capable of providing the necessary FR.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        125 => 
        array (
            'id' => 126,
            'question_id' => 257,
            'description' => 'It was noted that the glazing units in those communal fire doors detailed in the Hazard raised regading this issue, do not appear to be adequately FR and should be replaced.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        126 => 
        array (
            'id' => 127,
            'question_id' => 257,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        127 => 
        array (
            'id' => 128,
            'question_id' => 258,
            'description' => 'None of the fire doors from landlord areas into the escape routes have any fan lights/side panels.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        128 => 
        array (
            'id' => 129,
            'question_id' => 258,
            'description' => 'Where there are glazed fan lights/side panels in communal doors the panels are Georgian glass and appear to be in acceptable condition and well fitting and capable of providing the necessary FR.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        129 => 
        array (
            'id' => 130,
            'question_id' => 258,
            'description' => 'It was noted that the glazing units in those fanlights/side panels detailed in the Hazard raised regading this issue, do not appear to be adequately FR and should be replaced.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        130 => 
        array (
            'id' => 131,
            'question_id' => 258,
            'description' => 'Where there are glazing elements in the side panels to the communal doors into the single staircase enclosure, those elements are Georgian glass panels, and appear to be in good condition and well fitting and capable of providing the necessary FR. However, it is unlikely that this glass is heat resisting as well as fire resisting. At the time of the next major refurbishment at the block it is recommended that consideration is given to replacing the glazing panels in the doors between the lobbies and the escape staircase enclosure to panels which heat resisting as well at being FR, in line with current building regulations.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        131 => 
        array (
            'id' => 132,
            'question_id' => 258,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        132 => 
        array (
            'id' => 133,
            'question_id' => 259,
            'description' => 'There are no communal doors which require a self closer to be provided.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        133 => 
        array (
            'id' => 134,
            'question_id' => 259,
            'description' => 'Where necessary, all of the communal doors have been furnished with self closing devices complying with BS EN 1154.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        134 => 
        array (
            'id' => 135,
            'question_id' => 259,
        'description' => 'It was noted that the self closer(s), provided on communal fire doors detailed in the Hazard raised regarding this issue, are defective and in need of repair/replacement.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        135 => 
        array (
            'id' => 136,
            'question_id' => 259,
        'description' => 'It was noted that the self closer(s), provided on communal fire doors detailed in the Hazard raised regarding this issue, do not meet current standards and are in need of repair/replacement.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        136 => 
        array (
            'id' => 137,
            'question_id' => 259,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        137 => 
        array (
            'id' => 138,
            'question_id' => 260,
            'description' => 'There are no communal doors which require intumescent strips and cold smoke seals to be provided.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        138 => 
        array (
            'id' => 139,
            'question_id' => 260,
            'description' => 'All of the communal doors have been furnished with intumescent strips and cold smoke seals.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        139 => 
        array (
            'id' => 140,
            'question_id' => 260,
            'description' => 'It was noted that the intumescent strips and cold smoke seals on those communal fire doors, as detailed in the Hazard raised relating to this issue, are missing and in need of replacement.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        140 => 
        array (
            'id' => 141,
            'question_id' => 260,
            'description' => 'National guidance recommends that all communal fire doors in a premises of this nature are to FD30S standard. It was noted that this is not the case. Those communal fire doors detailed in the Hazard raised regading this issue, should be upgrade to this standard or replaced by doors to FD30S standard.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        141 => 
        array (
            'id' => 142,
            'question_id' => 260,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        142 => 
        array (
            'id' => 143,
            'question_id' => 261,
            'description' => 'No defects or deficiencies noted at the time of this assessment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        143 => 
        array (
            'id' => 144,
            'question_id' => 261,
            'description' => 'It was noted that those communal fire doors, detailed in the Hazard raised regading this issue, are provided with non-standard locks, and which were not possible to access during this assessment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        144 => 
        array (
            'id' => 145,
            'question_id' => 261,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        145 => 
        array (
            'id' => 146,
            'question_id' => 262,
            'description' => 'All doors to communal areas were found to be secured locked shut.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        146 => 
        array (
            'id' => 147,
            'question_id' => 262,
            'description' => 'It was noted that the locks to those communal fire doors, as detailed in the Hazard raised relating to this issue, were missing/damage and in need of replacement.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        147 => 
        array (
            'id' => 148,
            'question_id' => 262,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        148 => 
        array (
            'id' => 149,
            'question_id' => 265,
            'description' => 'Emergency lighting is provided throughout the escape routes.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        149 => 
        array (
            'id' => 150,
            'question_id' => 265,
            'description' => 'There is currently no provision of emergency lighting in the communal escape staircase enclosure.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        150 => 
        array (
            'id' => 151,
            'question_id' => 265,
            'description' => 'None of the lighting units in the escape routes could be confirmed as being emergency lighting units. Unless it can be confirmed that emergency lighting is indeed provided throughout the escape routes, it should be provided now, in compliance with the requirements of BS 5266.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        151 => 
        array (
            'id' => 152,
            'question_id' => 265,
            'description' => 'There is no emergency lighting provided along the open balconies. However, it is considered that, in the event of a mains electrical power failure, adequate, "Borrowed Light", would be available from street lighting and surrounding buildings. As such, the provision of emergency lighting in these areas is not considered as being a requirement.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        152 => 
        array (
            'id' => 153,
            'question_id' => 265,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        153 => 
        array (
            'id' => 154,
            'question_id' => 267,
            'description' => 'Where necessary, the provision of emergency lighting is considered adequate.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        154 => 
        array (
            'id' => 155,
            'question_id' => 267,
            'description' => 'The provision of emergency lighting is not considered adequate in the following areas:',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        155 => 
        array (
            'id' => 156,
            'question_id' => 267,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        156 => 
        array (
            'id' => 157,
            'question_id' => 268,
            'description' => 'An FAN is displayed in the entrance lobby.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        157 => 
        array (
            'id' => 158,
            'question_id' => 268,
            'description' => 'FANs are displayed in each entrance lobby.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        158 => 
        array (
            'id' => 159,
            'question_id' => 268,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        159 => 
        array (
            'id' => 160,
            'question_id' => 269,
        'description' => 'The FAN(s) correctly gives instruction for the, "Simultaneous Evacuation", strategy.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        160 => 
        array (
            'id' => 161,
            'question_id' => 269,
        'description' => 'The FAN(s) correctly gives instruction for the, "Defend in Place" evacuation strategy.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        161 => 
        array (
            'id' => 162,
            'question_id' => 269,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        162 => 
        array (
            'id' => 163,
            'question_id' => 270,
            'description' => 'Door signage is considered adequate.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        163 => 
        array (
            'id' => 164,
            'question_id' => 270,
            'description' => 'Signs stating, "Fire Door Keep Shut", should be provided with at eye level on each side of those communal fire doors listed in the Hazard associated with this issue.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        164 => 
        array (
            'id' => 165,
            'question_id' => 270,
            'description' => 'Signs stating, "Fire Door Keep Locked Shut", should be provided with at eye level on the outer leaf of those communal fire doors listed in the Hazard associated with this issue.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        165 => 
        array (
            'id' => 166,
            'question_id' => 270,
            'description' => 'Signs stating, "Fire Exit Keep Clear", should be provided with at eye level on the outer leaf of those communal fire doors listed in the Hazard raised  associated with this issue.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        166 => 
        array (
            'id' => 167,
            'question_id' => 270,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        167 => 
        array (
            'id' => 168,
            'question_id' => 271,
            'description' => 'Where necessary, appropriate pictorial signage has been provided to indicate the means of operating door release mechanisms on the exit doors.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        168 => 
        array (
            'id' => 169,
            'question_id' => 271,
            'description' => 'Appropriate pictorial signage should be displayed adjacent to the push bar/pad release mechanisms on of those communal fire doors listed in the Hazard raised associated with this issue.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        169 => 
        array (
            'id' => 170,
            'question_id' => 271,
            'description' => 'Appropriate pictorial signage should be displayed adjacent to the thumb turn lock on of those communal fire doors listed in the Hazard raised associated with this issue.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        170 => 
        array (
            'id' => 171,
            'question_id' => 271,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        171 => 
        array (
            'id' => 172,
            'question_id' => 272,
            'description' => 'Adequate directional signage has been provided where considered necessary.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        172 => 
        array (
            'id' => 173,
            'question_id' => 272,
            'description' => 'Being a simple single direction means of escape in a premise occupied by persons who are familiar with the premises, no direction signage is considered necessary.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        173 => 
        array (
            'id' => 174,
            'question_id' => 272,
            'description' => 'Directional signage is required in those areas stated in the Hazard raised associated with this issue.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        174 => 
        array (
            'id' => 175,
            'question_id' => 272,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        175 => 
        array (
            'id' => 176,
            'question_id' => 273,
            'description' => 'Signs instructing persons not to use the lift in the event of a fire are displayed adjacent to the lift doors on each level.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        176 => 
        array (
            'id' => 177,
            'question_id' => 273,
            'description' => 'Signs instructing persons not to use the lift in the event of a fire should be displayed adjacent to the lift doors on each level.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        177 => 
        array (
            'id' => 178,
            'question_id' => 273,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        178 => 
        array (
            'id' => 179,
            'question_id' => 274,
        'description' => 'For a building of this height the provision of signage in the staircase(s) to indicate each floor level is not considered to be a requirement.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        179 => 
        array (
            'id' => 180,
            'question_id' => 274,
            'description' => 'It is recommended that signs are prominently displayed at each floor level in both of the staircases to clearly indicate each floor level. Following recommendations made in the report of Phase 1 of the Grenfell Fire enquiry, the signs provided should be made of materials which are visible, “in low light and smoky conditions”.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        180 => 
        array (
            'id' => 181,
            'question_id' => 274,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        181 => 
        array (
            'id' => 182,
            'question_id' => 275,
            'description' => 'There is no DRM provided at this address.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        182 => 
        array (
            'id' => 183,
            'question_id' => 275,
            'description' => 'The DRM inlet box located in a position where it is clearly visible to attending fire crews. No additional signage is considered necessary.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        183 => 
        array (
            'id' => 184,
            'question_id' => 275,
            'description' => 'The inlet box for the DRM at this address it located in a position which may not be immediately apparent to atending fire crews. Additional signage, to clearly indicate its ocation should be provided, as detailed in the Hazard raised associated with this issue.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        184 => 
        array (
            'id' => 185,
            'question_id' => 275,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        185 => 
        array (
            'id' => 186,
            'question_id' => 277,
            'description' => 'In line with recommendations in national guidance for purpose built residential blocks, designed to facilitate a, ’Defend in Place’
evacuation strategy, there is no requirement for automatic detection to be fitted within the common areas.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        186 => 
        array (
            'id' => 187,
            'question_id' => 277,
        'description' => 'In line with recommendations in national guidance for buildings which have been converted to residential flats, (LACoRS), where adequate compartmentation between the flats cannot be guaranteed, a Grade A, LD2 fire detection/alarm system is provided, with smoke detection throughout the escape route and HD in each of the hallways of the flats.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        187 => 
        array (
            'id' => 188,
            'question_id' => 277,
            'description' => 'Although being purpose built, the block was constructed at the turn of the 20th century and has timber floors. Adequate compartmentation cannot be guaranteed. As such, as a compensatory measure, a Grade A, LD2 fire detection/alarm system is provided, with smoke detection throughout the escape route and HD in each of the hallways of the flats.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        188 => 
        array (
            'id' => 189,
            'question_id' => 277,
        'description' => 'The recommendations in the recognised national guidance, (LACoRS), for converted flats of 5/6 storeys, is that a Grade A, LD2 communal fire alarm system should be provided, with SD throughout the escape staircase enclosure and HD in the hallway of each flat, all interconnect to a control panel. At present, no such system is provided. It is recommended a system is provided to this specification.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        189 => 
        array (
            'id' => 190,
            'question_id' => 277,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        190 => 
        array (
            'id' => 191,
            'question_id' => 278,
            'description' => 'No defects identified at the time of this assessment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        191 => 
        array (
            'id' => 192,
            'question_id' => 278,
            'description' => 'It was noted that a fault wasshowing on the fire alarm control panel. The fault should be cleared and the system restored to full working order.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        192 => 
        array (
            'id' => 193,
            'question_id' => 278,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        193 => 
        array (
            'id' => 194,
            'question_id' => 279,
            'description' => 'Being a purpose built residential block, designed to facilitate a, ’Defend in Place’ evacuation strategy, the provision of no communal fire alarm system is appropriate.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        194 => 
        array (
            'id' => 195,
            'question_id' => 279,
            'description' => 'The Grade A, LD2 fire detection/alarm system is in line with recommendations in LACoRS.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        195 => 
        array (
            'id' => 196,
            'question_id' => 279,
            'description' => 'The grade of alarm provided in an LD2 system to Grade D standard. For converted houses of 3 and more floors the normal recommended standard is Grade A. However, the communal escape route only covers the G & 1st floors. As such the grade D system provided is considered acceptable.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        196 => 
        array (
            'id' => 197,
            'question_id' => 279,
            'description' => 'The current arrangements for the communal fire alarm system are not considered adequate, and the necessary provision/upgrading should be provided as detailed in the Hazard raised associated with this issue.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        197 => 
        array (
            'id' => 198,
            'question_id' => 279,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        198 => 
        array (
            'id' => 199,
            'question_id' => 280,
            'description' => 'An appropriate fire alarm zone plan is displayed adjacent to the fire alarm control panel.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        199 => 
        array (
            'id' => 200,
            'question_id' => 280,
            'description' => 'There is currently no zone plan displayed for the fire alarm system. One should be provided and displayed adjacent to the fire alarm control panel.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        200 => 
        array (
            'id' => 201,
            'question_id' => 280,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        201 => 
        array (
            'id' => 202,
            'question_id' => 281,
        'description' => 'It is WCC policy for all tenanted flats to be provided with in-flat fire alarm systems to at least Grade D, LD3 standard, (to be upgraded to LD2 standard when voids occur). All of those flats inspected are provided with such systems. In the case of leasehold flats, it is the responsibility of the leaseholder to provide in-flat detectors/alarms.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        202 => 
        array (
            'id' => 203,
            'question_id' => 281,
        'description' => 'It is WCC policy for all tenanted flats to be provided with in-flat fire alarm systems to at least Grade D, LD3 standard, (to be upgraded to LD2 standard when voids occur). It was noted during the assessmnet that this was not the case in those flats specified in the Hazard raised associated with this issue. Grade D, LD2 systems should be provided in these flats. In the case of leasehold flats, it is the responsibility of the leaseholder to provide in-flat detectors/alarms.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        203 => 
        array (
            'id' => 204,
            'question_id' => 281,
        'description' => 'It is WCC policy for all tenanted flats to be provided with in-flat fire alarm systems to at least Grade D, LD3 standard, (to be upgraded to LD2 standard when voids occur). However, during the assessment it was noted that no such systems are provided in those flats detailed in the Hazard raised for this issue. LD2 systems should be installed in these flats now. In the case of leasehold flats, it is the responsibility of the leaseholder to provide in-flat detectors/alarms.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        204 => 
        array (
            'id' => 205,
            'question_id' => 281,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        205 => 
        array (
            'id' => 206,
            'question_id' => 282,
            'description' => 'Such a system is not required for a building of this height.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        206 => 
        array (
            'id' => 207,
            'question_id' => 282,
            'description' => 'No such system is currently provided. However, WCC are committed to installing such systems in their buildings over 18m in line with the strong recommendation in national guidance. Such systems, which will be installed in compliance with BS 8629:2019, will be introduced on a risk based approach.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        207 => 
        array (
            'id' => 208,
            'question_id' => 282,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        208 => 
        array (
            'id' => 209,
            'question_id' => 285,
            'description' => 'This is a purpose built block, built to modern building regulation requirements. No evidence was seen to doubt adequate compartmentation.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        209 => 
        array (
            'id' => 210,
            'question_id' => 285,
            'description' => 'Being a converted building of this age, adequate compartmentation between the flats cannot be guaranteed. As a compensatory measure, an LD2 communal fire alarm system has been provided, and a, Simultaneous Evacuation”, strategy implemented.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        210 => 
        array (
            'id' => 211,
            'question_id' => 285,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        211 => 
        array (
            'id' => 212,
            'question_id' => 286,
            'description' => 'None noted during this assessment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        212 => 
        array (
            'id' => 213,
            'question_id' => 286,
            'description' => 'Voids, as detaied the the Hazard raised regarding this issue, were noted during this assessment. It is recommended that these areas are surveyed to determine whether or not the void needs to be FR and whether or not adequate fire stopping has been provided where services pass out of the void into other fire compartment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        213 => 
        array (
            'id' => 214,
            'question_id' => 286,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        214 => 
        array (
            'id' => 215,
            'question_id' => 287,
            'description' => 'Any riser and cupboards in the communal areas appear to have been adequately fire stopped.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        215 => 
        array (
            'id' => 216,
            'question_id' => 287,
            'description' => 'There is a service riser as detailed in the Hazard raised regading this issue. It was not possible to access this riser at the time of the assessment. However, it  appears that it may not be adequately FR.  It is recommended that a survey of this void is carried to confirm whether or not the riser needs to be FR and whether or not adequate fire stopping has been carried out where services pass into/out of the void from other fire compartments. Any deficiencies identified by the survey should be addressed.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        216 => 
        array (
            'id' => 217,
            'question_id' => 287,
            'description' => 'It was noted that adequate fire stopping has not been carried out in the electrical intake room, as detailed in the Hazard raised regading this issue. This should be carried out by a specialist 3rd party accreditted contractor.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        217 => 
        array (
            'id' => 218,
            'question_id' => 287,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        218 => 
        array (
            'id' => 219,
            'question_id' => 288,
            'description' => 'No defects noted at the time of this assessment.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        219 => 
        array (
            'id' => 220,
            'question_id' => 288,
            'description' => 'It was noted that fire stopping does not appear to be adequate in those areas detailed in the Hazard raised regading this issue.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        220 => 
        array (
            'id' => 221,
            'question_id' => 288,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        221 => 
        array (
            'id' => 222,
            'question_id' => 289,
            'description' => 'There are no waste chutes at this block.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        222 => 
        array (
            'id' => 223,
            'question_id' => 289,
            'description' => 'It was noted that not all of the hopper seals are in good servicable condition and should be replaced as detailed in the Hazard raised regading this issue.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        223 => 
        array (
            'id' => 224,
            'question_id' => 289,
            'description' => 'An automatic shutter is provided at the base of the refuse chute.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        224 => 
        array (
            'id' => 225,
            'question_id' => 289,
            'description' => 'The bin room is covered by a sprinkler system.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        225 => 
        array (
            'id' => 226,
            'question_id' => 289,
            'description' => 'The hppers open directly into the escape staircase enclosure. It is recommended that an automatic shutter is provided at the base of the refuse chute.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        226 => 
        array (
            'id' => 227,
            'question_id' => 289,
            'description' => 'Other',
            'order' => 1,
            'other' => 1,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        227 => 
        array (
            'id' => 228,
            'question_id' => 290,
            'description' => 'This block has a flat roof. There are no roof spaces.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        228 => 
        array (
            'id' => 229,
            'question_id' => 290,
            'description' => 'There is just one flat on the top floor. The dividing walls between this address and adjacent properties extend beyond the height of the roof.',
            'order' => 1,
            'other' => 0,
            'created_at' => NULL,
            'updated_at' => NULL,
        ),
        229 => 
        array (
            'id' => 230,
            'question_id' => 290,
        'description' => 'It was not possible to access the roof spaces. Unless already completed, it is recommended that a survey is carried out to ensure that either, a) the ceilings between the top floor flats and roof spaces are a minimum of 60 minutes FR, or, b) the compartment walls between the top floor flats extend up to roof level, and should this found not to be the case, appropriate compartmentalisation should be provided.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    230 => 
    array (
        'id' => 231,
        'question_id' => 290,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    231 => 
    array (
        'id' => 232,
        'question_id' => 291,
        'description' => 'The electrical intake equipment is enclosed within the electrical intake room which provides a minimum of 30 minutes protection to the escape route.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    232 => 
    array (
        'id' => 233,
        'question_id' => 291,
        'description' => 'The electrical intake equipment is located at high level in the entrance lobby. The enclosure in not adequately FR and should be replaced by an enclosure that is FR for a minimum of 30 minutes. The access hatch/door should be to FD30S standard.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    233 => 
    array (
        'id' => 234,
        'question_id' => 291,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    234 => 
    array (
        'id' => 235,
        'question_id' => 292,
        'description' => 'There are no electrical meters in the flat walls at this block.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    235 => 
    array (
        'id' => 236,
        'question_id' => 292,
        'description' => 'The electrical meters in the flat walls have a sealed FR glazed facia.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    236 => 
    array (
        'id' => 237,
        'question_id' => 292,
        'description' => 'Many of the metal doors to the electric meters in the flat walls are unlocked, with several being damaged.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    237 => 
    array (
        'id' => 238,
        'question_id' => 292,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    238 => 
    array (
        'id' => 239,
        'question_id' => 293,
        'description' => 'There are no ventilation systems in the communal areas at this block. Openable windows are the only form of ventilation to the staircase enclosure.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    239 => 
    array (
        'id' => 240,
        'question_id' => 293,
        'description' => 'It was noted that the filter screens across the POVs are dirty. This could affect the air flow through the vent. Arrangements should be made for them to be cleaned.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    240 => 
    array (
        'id' => 241,
        'question_id' => 293,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    241 => 
    array (
        'id' => 242,
        'question_id' => 294,
        'description' => 'The wall linings appear to be to Class 0 standard.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    242 => 
    array (
        'id' => 243,
        'question_id' => 294,
        'description' => 'It was not possible to confirm the FR of wall and ceiling linings. However, the existing finishes are in reasonable condition and do not appear to present a significant risk to fire spread or safe escape. It is WCC poicy that all materials used for future redecorations will complying to Class 0 of BS476 parts 6&7 or BS EN13501- 1.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    243 => 
    array (
        'id' => 244,
        'question_id' => 294,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    244 => 
    array (
        'id' => 245,
        'question_id' => 295,
        'description' => 'No soft furnishings were noted as being in the communal areas.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    245 => 
    array (
        'id' => 246,
        'question_id' => 295,
        'description' => 'The soft furnishings in the communal escape route appears to meet the current regulations.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    246 => 
    array (
        'id' => 247,
        'question_id' => 295,
        'description' => 'The soft furnishings as detailed in the Hazard raised regarding this issue do not appear to meet current Furniture & Furnishing Regulations and should be removed from site.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    247 => 
    array (
        'id' => 248,
        'question_id' => 295,
        'description' => 'Although appearing to meet the current Furniture & Furnishing Regulations, those soft furnishings detailed in the Hazard raised regarding this issue are torn, reducing their resistance to fire and should be replaced/removed.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    248 => 
    array (
        'id' => 249,
        'question_id' => 295,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    249 => 
    array (
        'id' => 250,
        'question_id' => 297,
        'description' => 'The external walls are plain brick.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    250 => 
    array (
        'id' => 251,
        'question_id' => 297,
        'description' => 'The external walls are plain render.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    251 => 
    array (
        'id' => 252,
        'question_id' => 297,
        'description' => 'The external walls are blockwork enclosed in a Rockwall style insulation with a concrete render covering. It is recommended that, unless already carried out, a survey is carried out to ensure that the insulation is non-combustible. It found not to be the case then expert advice should be sought to consider it’s removal.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    252 => 
    array (
        'id' => 253,
        'question_id' => 297,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    253 => 
    array (
        'id' => 254,
        'question_id' => 301,
        'description' => 'Taking into account the fire risks present on the premises, the provision of PFEE is considered adequate.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    254 => 
    array (
        'id' => 255,
        'question_id' => 301,
        'description' => 'Taking into account the fire risks present on the premises and that no training is provided to residents, the provision of PFEE is considered acceptable.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    255 => 
    array (
        'id' => 256,
        'question_id' => 301,
        'description' => 'Taking into account the fire risks present on the premisesand the fact that contractors are required to provide their own PFFE, the provision of no PFEE is considered acceptable.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    256 => 
    array (
        'id' => 257,
        'question_id' => 301,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    257 => 
    array (
        'id' => 258,
        'question_id' => 302,
        'description' => 'There is a CO2 extinguisher located in the electrical intake cupboard. It is accessible to any employees and contractors, but not to the residents.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    258 => 
    array (
        'id' => 259,
        'question_id' => 302,
        'description' => 'There are CO2 extinguishers located in landlord areas. Taking into account the fire risks present on the premises, the provision of PFEE is considered adequate.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    259 => 
    array (
        'id' => 260,
        'question_id' => 302,
        'description' => 'No PFFE has been provided at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    260 => 
    array (
        'id' => 261,
        'question_id' => 302,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    261 => 
    array (
        'id' => 262,
        'question_id' => 303,
        'description' => 'The entrance door into the building is secured by an EVVA lock, which is released either by resident’s keys, or the answerphone system. There is no override for this lock. However, any fire appliances attending an incident at the block would be able to park directly outside the entrance, and using equipment immediately available from the fire appliances, it would be easy for fire crews to force access into the block if necessary.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    262 => 
    array (
        'id' => 263,
        'question_id' => 303,
        'description' => 'There is a standard drop key override provided at the main entrance into the building. All frontline appliances based in Westminster and surrounding stations have also now been provided with entry fobs.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    263 => 
    array (
        'id' => 264,
        'question_id' => 303,
        'description' => 'There is a standard drop key override provided at the main entrance into the building. However, it was noted that it is defective. It is recommended that it is repaired to full working order.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    264 => 
    array (
        'id' => 265,
        'question_id' => 303,
    'description' => 'The standard drop key override for the main entrance door has been decommissioned. However, recent conversations between WCC and LFB senior managers indicated that LFB are not happy with this arrangement. As such it is recommended that the override is reinstated by which ever system has been decided going forward, (standard drop key or new ASSA key system).',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    265 => 
    array (
        'id' => 266,
        'question_id' => 303,
        'description' => 'Standard drop key overrides are provided at the main entrances into the building and for the security doors into the corridors. All frontline appliances based in Westminster and surrounding stations have also now been provided with entry fobs.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    266 => 
    array (
        'id' => 267,
        'question_id' => 303,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    267 => 
    array (
        'id' => 268,
        'question_id' => 304,
        'description' => 'As would be expected for building of this height, a DRM is provided.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    268 => 
    array (
        'id' => 269,
        'question_id' => 304,
        'description' => 'No DRM is provided or required at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    269 => 
    array (
        'id' => 270,
        'question_id' => 304,
        'description' => 'Where any storey has a floor height in excess of 18 metres above ground level current building regulations recommend that a dry rising main for fire fighting operations is provided. It is noted that no DRM has been provided for this block. Although the provision of a DRM was not a requirement at the time at which the block was built, unless one has already been carried out, it is recommended that a feasibility study is carried out to consider the possibility of retrofitting a DRM for the block.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    270 => 
    array (
        'id' => 271,
        'question_id' => 304,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    271 => 
    array (
        'id' => 272,
        'question_id' => 305,
        'description' => 'There are no lifts provided at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    272 => 
    array (
        'id' => 273,
        'question_id' => 305,
        'description' => 'A lift is provided, but it is neither a firefighting nor fireman’s lift.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    273 => 
    array (
        'id' => 274,
        'question_id' => 305,
    'description' => 'The lift(s) provided at this block is not a firefighting lift. However, override facilities are provided, enabling firefighters attending any incident at the block to take control of the lift car.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    274 => 
    array (
        'id' => 275,
        'question_id' => 305,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    275 => 
    array (
        'id' => 276,
        'question_id' => 307,
        'description' => 'No sprinkler system is provided or required at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    276 => 
    array (
        'id' => 277,
        'question_id' => 307,
        'description' => 'No sprinkler system is provided. As the block is over 60m high consideration has been given to retrofitting sprinklers in compliance with national recommendations. However, due to the considerable number of leaseholders in the block this would be difficult to effectively implement under current legislation.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    277 => 
    array (
        'id' => 278,
        'question_id' => 307,
        'description' => 'No sprinkler system is provided or required for the residential areas at this address. There is however sprinkler cover in landlord areas.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    278 => 
    array (
        'id' => 279,
        'question_id' => 307,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    279 => 
    array (
        'id' => 280,
        'question_id' => 310,
        'description' => 'No premises information box is provided or required for this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    280 => 
    array (
        'id' => 281,
        'question_id' => 310,
        'description' => 'No premises information box is currently provided atthis address. However, it is WCC policy that they will be installed in blocks over 18m high when and where fire alert evacuation systems are installed.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    281 => 
    array (
        'id' => 282,
        'question_id' => 310,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    282 => 
    array (
        'id' => 283,
        'question_id' => 311,
        'description' => 'No premises information box is provided or required for this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    283 => 
    array (
        'id' => 284,
        'question_id' => 311,
        'description' => 'Details of the physical ability of each resident are provided in the premises information box.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    284 => 
    array (
        'id' => 285,
        'question_id' => 311,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    285 => 
    array (
        'id' => 286,
        'question_id' => 326,
        'description' => 'No other fire safety management issues were noted at the time of this assessment.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    286 => 
    array (
        'id' => 287,
        'question_id' => 326,
        'description' => 'Unless already obtained, It is recommended that requests are made to the, "Rosponsible Persons", of the commercial premises at G/F level for a list of the significant finding identified and listed in the reports for their latest FRAs. Particular attention should be given to comments regarding any issues identified which could affect the residents in the flats.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    287 => 
    array (
        'id' => 288,
        'question_id' => 326,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    288 => 
    array (
        'id' => 289,
        'question_id' => 331,
        'description' => 'There is a strict system of maintenance and testing of all facilities in accordance to current statutory regulations to ensure that the WCC fulfils its legal obligations. A term contract is in place for the monthly, 6 monthly and 12 monthly testing of the communal fire alarm system. Records are kept and can be made available to view at the WCC central offices.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    289 => 
    array (
        'id' => 290,
        'question_id' => 331,
        'description' => 'There is no fire alarm system covering the communal parts at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    290 => 
    array (
        'id' => 291,
        'question_id' => 331,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    291 => 
    array (
        'id' => 292,
        'question_id' => 332,
        'description' => 'There is a strict system of maintenance and testing of all facilities in accordance to current statutory regulations to ensure that the WCC fulfils its legal obligations. A term contract is in place for the monthly and 12 monthly testing of the emergency lighting. Records are kept and can be made available to view at the WCC central offices.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    292 => 
    array (
        'id' => 293,
        'question_id' => 332,
        'description' => 'There is no emergency lighting system covering the communal parts at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    293 => 
    array (
        'id' => 294,
        'question_id' => 332,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    294 => 
    array (
        'id' => 295,
        'question_id' => 333,
        'description' => 'There is a strict system of maintenance and testing of all facilities in accordance to current statutory regulations to ensure that the WCC fulfils its legal obligations. A term contract is in place for the 12 monthly testing of the PFFE. The date of the testing is provided on the individual extinguisher. Records are also kept and can be made available to view at the WCC central offices.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    295 => 
    array (
        'id' => 296,
        'question_id' => 333,
        'description' => 'There are no portable fire extinguishers provided at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    296 => 
    array (
        'id' => 297,
        'question_id' => 333,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    297 => 
    array (
        'id' => 298,
        'question_id' => 334,
        'description' => 'There is a strict system of maintenance and testing of all facilities in accordance to current statutory regulations to ensure that the WCC fulfils its legal obligations. A term contract is in place for the 6 monthly testing of the DRM. Records are kept and can be made available to view at the WCC central offices.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    298 => 
    array (
        'id' => 299,
        'question_id' => 334,
        'description' => 'There are no fire mains at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    299 => 
    array (
        'id' => 300,
        'question_id' => 334,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    300 => 
    array (
        'id' => 301,
        'question_id' => 335,
        'description' => 'There is a strict system of maintenance and testing of all facilities in accordance to current statutory regulations to ensure that the WCC fulfils its legal obligations. A term contract is in place for the 12 monthly testing of the lightning protection system. Records are kept and can be made available to view at the WCC central offices.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    301 => 
    array (
        'id' => 302,
        'question_id' => 335,
        'description' => 'There is no lightning protection system at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    302 => 
    array (
        'id' => 303,
        'question_id' => 335,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    303 => 
    array (
        'id' => 304,
        'question_id' => 336,
        'description' => 'A term contract is in place for the 6 monthly testing of the access control system. Records are kept and can be made available to view at the WCC central offices.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    304 => 
    array (
        'id' => 305,
        'question_id' => 336,
        'description' => 'There are no access controls at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    305 => 
    array (
        'id' => 306,
        'question_id' => 336,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    306 => 
    array (
        'id' => 307,
        'question_id' => 337,
        'description' => 'A term contract is in place for the periodic testing of the firefighter lifts. They are tested monthly by a specialist contractor. Records are kept and can be made available to view at the WCC central offices.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    307 => 
    array (
        'id' => 308,
        'question_id' => 337,
        'description' => 'There are no lifts at this address which have facilities to assist firefighting.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    308 => 
    array (
        'id' => 309,
        'question_id' => 337,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    309 => 
    array (
        'id' => 310,
        'question_id' => 342,
        'description' => 'Records are maintained centrally.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    310 => 
    array (
        'id' => 311,
        'question_id' => 342,
        'description' => 'There is no communal fire alarm system at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    311 => 
    array (
        'id' => 312,
        'question_id' => 342,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    312 => 
    array (
        'id' => 313,
        'question_id' => 343,
        'description' => 'Records are maintained centrally',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    313 => 
    array (
        'id' => 314,
        'question_id' => 343,
        'description' => 'There is no emergency lighting in the communal areas at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    314 => 
    array (
        'id' => 315,
        'question_id' => 343,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    315 => 
    array (
        'id' => 316,
        'question_id' => 344,
        'description' => 'The annual test dates are recorded on the individual extinguishers as well records which are maintained centrally.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    316 => 
    array (
        'id' => 317,
        'question_id' => 344,
        'description' => 'There are no fire extinguishers provided at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    317 => 
    array (
        'id' => 318,
        'question_id' => 344,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    318 => 
    array (
        'id' => 319,
        'question_id' => 345,
        'description' => 'Records are maintained centrally.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    319 => 
    array (
        'id' => 320,
        'question_id' => 345,
        'description' => 'There are no fire mains at this address.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    320 => 
    array (
        'id' => 321,
        'question_id' => 345,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    321 => 
    array (
        'id' => 322,
        'question_id' => 346,
        'description' => 'Records are maintained centrally.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    322 => 
    array (
        'id' => 323,
        'question_id' => 346,
        'description' => 'There are no lifts at this address equipped with facilities to assist firefighting.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    323 => 
    array (
        'id' => 324,
        'question_id' => 346,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    324 => 
    array (
        'id' => 325,
        'question_id' => 347,
        'description' => 'There were no issues noted during this assessment regarding supports to electrical cables.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    325 => 
    array (
        'id' => 326,
        'question_id' => 347,
        'description' => 'It was noted that electrical cables within the communal escape routes are supported in plastic supports/trunking. BS7671 is non-retrospective, but going forward, all works carried out will comply with BS7671 2018.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    326 => 
    array (
        'id' => 327,
        'question_id' => 347,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    327 => 
    array (
        'id' => 328,
        'question_id' => 348,
        'description' => 'No fixed gas installations were noted as being present in the common parts of the building.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    328 => 
    array (
        'id' => 329,
        'question_id' => 348,
    'description' => 'Where there are gas pipes in the escape routes, the pipes are screwed steel pipes, which appear to be in compliance with Gas Safety (Installation and Use) Regulations 1998 (GSIUR) as amended, and as such considered acceptable.',
        'order' => 1,
        'other' => 0,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
    329 => 
    array (
        'id' => 330,
        'question_id' => 348,
        'description' => 'Other',
        'order' => 1,
        'other' => 1,
        'created_at' => NULL,
        'updated_at' => NULL,
    ),
));
        
        
    }
}
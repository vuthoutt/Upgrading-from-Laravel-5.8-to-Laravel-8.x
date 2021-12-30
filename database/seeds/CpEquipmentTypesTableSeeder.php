<?php

use Illuminate\Database\Seeder;

class CpEquipmentTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_equipment_types')->delete();

        \DB::table('cp_equipment_types')->insert(array (
            0 =>
            array (
                'id' => 1,
                'description' => 'Air Conditioning Unit',
                'template_id' => 1,
            ),
            1 =>
            array (
                'id' => 2,
                'description' => 'Air Handling Unit',
                'template_id' => 1,
            ),
            2 =>
            array (
                'id' => 3,
                'description' => 'AOV Actuator',
                'template_id' => 1,
            ),
            3 =>
            array (
                'id' => 4,
                'description' => 'AOV Call Point',
                'template_id' => 1,
            ),
            4 =>
            array (
                'id' => 5,
                'description' => 'AOV Detector',
                'template_id' => 1,
            ),
            5 =>
            array (
                'id' => 6,
                'description' => 'Auto Closing Doors',
                'template_id' => 1,
            ),
            6 =>
            array (
                'id' => 7,
                'description' => 'Barrier',
                'template_id' => 1,
            ),
            7 =>
            array (
                'id' => 10,
                'description' => 'Booster Pump',
                'template_id' => 1,
            ),
            8 =>
            array (
                'id' => 11,
                'description' => 'Buffer Vessel',
                'template_id' => 1,
            ),
            9 =>
            array (
                'id' => 13,
                'description' => 'Car Park Gates',
                'template_id' => 1,
            ),
            10 =>
            array (
                'id' => 14,
                'description' => 'Carbon Monoxide Detector',
                'template_id' => 1,
            ),
            11 =>
            array (
                'id' => 15,
                'description' => 'Chilled Water Dispenser',
                'template_id' => 1,
            ),
            12 =>
            array (
                'id' => 16,
                'description' => 'Circulating Pump',
                'template_id' => 1,
            ),
            13 =>
            array (
                'id' => 17,
                'description' => 'Cold Water Feed & Expansion Tank',
                'template_id' => 3,
            ),
            14 =>
            array (
                'id' => 18,
            'description' => 'Cold Water Spray Tap (Bath)',
                'template_id' => 4,
            ),
            15 =>
            array (
                'id' => 19,
            'description' => 'Cold Water Spray Tap (Bidet)',
                'template_id' => 4,
            ),
            16 =>
            array (
                'id' => 20,
            'description' => 'Cold Water Spray Tap (Butler Sink)',
                'template_id' => 4,
            ),
            17 =>
            array (
                'id' => 21,
            'description' => 'Cold Water Spray Tap (Sink)',
                'template_id' => 4,
            ),
            18 =>
            array (
                'id' => 22,
            'description' => 'Cold Water Spray Tap (Walk-in Bath)',
                'template_id' => 4,
            ),
            19 =>
            array (
                'id' => 23,
            'description' => 'Cold Water Spray Tap (Wash Hand Basin)',
                'template_id' => 4,
            ),
            20 =>
            array (
                'id' => 24,
                'description' => 'Cold Water Storage Tank',
                'template_id' => 3,
            ),
            21 =>
            array (
                'id' => 25,
            'description' => 'Cold Water Tap (Bath)',
                'template_id' => 4,
            ),
            22 =>
            array (
                'id' => 26,
            'description' => 'Cold Water Tap (Bidet)',
                'template_id' => 4,
            ),
            23 =>
            array (
                'id' => 27,
            'description' => 'Cold Water Tap (Butler Sink)',
                'template_id' => 4,
            ),
            24 =>
            array (
                'id' => 28,
            'description' => 'Cold Water Tap (Sink)',
                'template_id' => 4,
            ),
            25 =>
            array (
                'id' => 29,
            'description' => 'Cold Water Tap (Walk-in Bath)',
                'template_id' => 4,
            ),
            26 =>
            array (
                'id' => 30,
            'description' => 'Cold Water Tap (Wash Hand Basin)',
                'template_id' => 4,
            ),
            27 =>
            array (
                'id' => 31,
            'description' => 'Cooker (Electric)',
                'template_id' => 1,
            ),
            28 =>
            array (
                'id' => 32,
            'description' => 'Cooker (Gas)',
                'template_id' => 1,
            ),
            29 =>
            array (
                'id' => 33,
                'description' => 'Cooling Tower',
                'template_id' => 1,
            ),
            30 =>
            array (
                'id' => 34,
            'description' => 'Dead End (<6 Pipe Diameter)',
                'template_id' => 1,
            ),
            31 =>
            array (
                'id' => 35,
            'description' => 'Dead End (6+ Pipe Diameter)',
                'template_id' => 1,
            ),
            32 =>
            array (
                'id' => 36,
                'description' => 'Dead Leg',
                'template_id' => 1,
            ),
            33 =>
            array (
                'id' => 37,
                'description' => 'Dehumidifier',
                'template_id' => 1,
            ),
            34 =>
            array (
                'id' => 38,
                'description' => 'Disability Lifts',
                'template_id' => 1,
            ),
            35 =>
            array (
                'id' => 39,
                'description' => 'Dishwasher',
                'template_id' => 1,
            ),
            36 =>
            array (
                'id' => 40,
                'description' => 'Door Entry',
                'template_id' => 1,
            ),
            37 =>
            array (
                'id' => 41,
                'description' => 'Drinking Water Fountain',
                'template_id' => 4,
            ),
            38 =>
            array (
                'id' => 42,
                'description' => 'Drinks Vending Machine',
                'template_id' => 1,
            ),
            39 =>
            array (
                'id' => 43,
                'description' => 'Dry Riser',
                'template_id' => 1,
            ),
            40 =>
            array (
                'id' => 44,
                'description' => 'Electric Shower',
                'template_id' => 5,
            ),
            41 =>
            array (
                'id' => 45,
                'description' => 'Evaporative Cooling System',
                'template_id' => 1,
            ),
            42 =>
            array (
                'id' => 46,
                'description' => 'Expansion Unit',
                'template_id' => 1,
            ),
            43 =>
            array (
                'id' => 47,
                'description' => 'Expansion Vessel',
                'template_id' => 1,
            ),
            44 =>
            array (
                'id' => 48,
            'description' => 'Extinguisher (ABC Powder)',
                'template_id' => 1,
            ),
            45 =>
            array (
                'id' => 49,
            'description' => 'Extinguisher (Carbon Dioxide)',
                'template_id' => 1,
            ),
            46 =>
            array (
                'id' => 50,
            'description' => 'Extinguisher (Foam Spray)',
                'template_id' => 1,
            ),
            47 =>
            array (
                'id' => 51,
            'description' => 'Extinguisher (Water)',
                'template_id' => 1,
            ),
            48 =>
            array (
                'id' => 52,
            'description' => 'Extinguisher (Wet Chemical)',
                'template_id' => 1,
            ),
            49 =>
            array (
                'id' => 53,
                'description' => 'Extractor Fan ',
                'template_id' => 1,
            ),
            50 =>
            array (
                'id' => 54,
                'description' => 'Fall Arrest System',
                'template_id' => 1,
            ),
            51 =>
            array (
                'id' => 55,
                'description' => 'Filling Loop',
                'template_id' => 1,
            ),
            52 =>
            array (
                'id' => 56,
                'description' => 'Fire Alarm Control Panel',
                'template_id' => 1,
            ),
            53 =>
            array (
                'id' => 57,
                'description' => 'Fire Blanket',
                'template_id' => 1,
            ),
            54 =>
            array (
                'id' => 58,
                'description' => 'Fire Detection & Alarm System',
                'template_id' => 1,
            ),
            55 =>
            array (
                'id' => 59,
                'description' => 'Fire Suppression System',
                'template_id' => 1,
            ),
            56 =>
            array (
                'id' => 60,
                'description' => 'Flex-filler Digital Pressurisation Equipment',
                'template_id' => 1,
            ),
            57 =>
            array (
                'id' => 61,
                'description' => 'Freezer',
                'template_id' => 1,
            ),
            58 =>
            array (
                'id' => 62,
                'description' => 'Fridge',
                'template_id' => 1,
            ),
            59 =>
            array (
                'id' => 63,
                'description' => 'Fridge Freezer',
                'template_id' => 1,
            ),
            60 =>
            array (
                'id' => 64,
                'description' => 'Grey Water Recycling System',
                'template_id' => 1,
            ),
            61 =>
            array (
                'id' => 65,
                'description' => 'Heat Detector',
                'template_id' => 1,
            ),
            62 =>
            array (
                'id' => 66,
                'description' => 'Hose-reel',
                'template_id' => 1,
            ),
            63 =>
            array (
                'id' => 67,
            'description' => 'Hot Water Spray Tap (Bath)',
                'template_id' => 6,
            ),
            64 =>
            array (
                'id' => 68,
            'description' => 'Hot Water Spray Tap (Butler Sink)',
                'template_id' => 6,
            ),
            65 =>
            array (
                'id' => 69,
            'description' => 'Hot Water Spray Tap (Sink)',
                'template_id' => 6,
            ),
            66 =>
            array (
                'id' => 70,
            'description' => 'Hot Water Spray Tap (Walk-in Bath)',
                'template_id' => 6,
            ),
            67 =>
            array (
                'id' => 71,
            'description' => 'Hot Water Spray Tap (Wash Hand Basin)',
                'template_id' => 6,
            ),
            68 =>
            array (
                'id' => 73,
            'description' => 'Hot Water Tap (Bath)',
                'template_id' => 6,
            ),
            69 =>
            array (
                'id' => 74,
            'description' => 'Hot Water Tap (Butler Sink)',
                'template_id' => 6,
            ),
            70 =>
            array (
                'id' => 75,
            'description' => 'Hot Water Tap (Sink)',
                'template_id' => 6,
            ),
            71 =>
            array (
                'id' => 76,
            'description' => 'Hot Water Tap (Walk-in Bath)',
                'template_id' => 6,
            ),
            72 =>
            array (
                'id' => 77,
            'description' => 'Hot Water Tap (Wash Hand Basin)',
                'template_id' => 6,
            ),
            73 =>
            array (
                'id' => 78,
                'description' => 'Humidifier',
                'template_id' => 1,
            ),
            74 =>
            array (
                'id' => 79,
                'description' => 'I/O Unit',
                'template_id' => 1,
            ),
            75 =>
            array (
                'id' => 80,
                'description' => 'Instant Water Heater',
                'template_id' => 13,
            ),
            76 =>
            array (
                'id' => 81,
                'description' => 'Intruder Alarm System',
                'template_id' => 1,
            ),
            77 =>
            array (
                'id' => 82,
                'description' => 'Jacuzzi Hot Tub',
                'template_id' => 1,
            ),
            78 =>
            array (
                'id' => 83,
                'description' => 'Macerator',
                'template_id' => 1,
            ),
            79 =>
            array (
                'id' => 84,
                'description' => 'Mechanical Garage Door Openers',
                'template_id' => 1,
            ),
            80 =>
            array (
                'id' => 85,
                'description' => 'Mechanical Ventilation Heat Recovery System',
                'template_id' => 1,
            ),
            81 =>
            array (
                'id' => 86,
            'description' => 'Mixer Shower (Bath)',
                'template_id' => 5,
            ),
            82 =>
            array (
                'id' => 87,
            'description' => 'Mixer Spray Tap (Bath)',
                'template_id' => 5,
            ),
            83 =>
            array (
                'id' => 88,
            'description' => 'Mixer Spray Tap (Bedit)',
                'template_id' => 5,
            ),
            84 =>
            array (
                'id' => 89,
            'description' => 'Mixer Spray Tap (Butler Sink)',
                'template_id' => 5,
            ),
            85 =>
            array (
                'id' => 90,
            'description' => 'Mixer Spray Tap (Sink)',
                'template_id' => 5,
            ),
            86 =>
            array (
                'id' => 91,
            'description' => 'Mixer Spray Tap (Walk-in Bath)',
                'template_id' => 5,
            ),
            87 =>
            array (
                'id' => 92,
            'description' => 'Mixer Spray Tap (Wash Hand Basin)',
                'template_id' => 5,
            ),
            88 =>
            array (
                'id' => 93,
            'description' => 'Mixer Tap (Bath)',
                'template_id' => 5,
            ),
            89 =>
            array (
                'id' => 94,
            'description' => 'Mixer Tap (Bidet)',
                'template_id' => 5,
            ),
            90 =>
            array (
                'id' => 95,
            'description' => 'Mixer Tap (Butler Sink)',
                'template_id' => 5,
            ),
            91 =>
            array (
                'id' => 96,
            'description' => 'Mixer Tap (Sink)',
                'template_id' => 5,
            ),
            92 =>
            array (
                'id' => 97,
            'description' => 'Mixer Tap (Walk-in Bath)',
                'template_id' => 5,
            ),
            93 =>
            array (
                'id' => 98,
            'description' => 'Mixer Tap (Wash Hand Basin)',
                'template_id' => 5,
            ),
            94 =>
            array (
                'id' => 99,
                'description' => 'Multi-Sensor',
                'template_id' => 1,
            ),
            95 =>
            array (
                'id' => 100,
                'description' => 'Non-Drinking Fountain',
                'template_id' => 1,
            ),
            96 =>
            array (
                'id' => 101,
                'description' => 'Passenger & Goods Lifts',
                'template_id' => 1,
            ),
            97 =>
            array (
                'id' => 102,
                'description' => 'Pedestrian Gates',
                'template_id' => 1,
            ),
            98 =>
            array (
                'id' => 103,
                'description' => 'Pipework',
                'template_id' => 1,
            ),
            99 =>
            array (
                'id' => 104,
                'description' => 'Point of Use Heater',
                'template_id' => 7,
            ),
            100 =>
            array (
                'id' => 105,
                'description' => 'Quickfill',
                'template_id' => 1,
            ),
            101 =>
            array (
                'id' => 106,
                'description' => 'Sewage Pumps',
                'template_id' => 1,
            ),
            102 =>
            array (
                'id' => 107,
                'description' => 'Smoke Alarm',
                'template_id' => 1,
            ),
            103 =>
            array (
                'id' => 108,
                'description' => 'Smoke Detector',
                'template_id' => 1,
            ),
            104 =>
            array (
                'id' => 109,
                'description' => 'Solar Panels',
                'template_id' => 1,
            ),
            105 =>
            array (
                'id' => 110,
                'description' => 'Spa Bath',
                'template_id' => 1,
            ),
            106 =>
            array (
                'id' => 111,
                'description' => 'Sprinklers',
                'template_id' => 1,
            ),
            107 =>
            array (
                'id' => 112,
                'description' => 'Sump Pumps',
                'template_id' => 1,
            ),
            108 =>
            array (
                'id' => 113,
                'description' => 'Tea Point',
                'template_id' => 6,
            ),
            109 =>
            array (
                'id' => 114,
            'description' => 'Thermostatic Mixing Valve (TMV)',
                'template_id' => 17,
            ),
            110 =>
            array (
                'id' => 115,
                'description' => 'Toilet',
                'template_id' => 1,
            ),
            111 =>
            array (
                'id' => 116,
                'description' => 'Urinal',
                'template_id' => 1,
            ),
            112 =>
            array (
                'id' => 117,
                'description' => 'Ventilation System',
                'template_id' => 1,
            ),
            113 =>
            array (
                'id' => 118,
                'description' => 'Walk-in Bath',
                'template_id' => 1,
            ),
            114 =>
            array (
                'id' => 119,
                'description' => 'Washing Machine',
                'template_id' => 1,
            ),
            115 =>
            array (
                'id' => 120,
                'description' => 'Water Leak Detection System',
                'template_id' => 1,
            ),
            116 =>
            array (
                'id' => 121,
                'description' => 'Water Softner System',
                'template_id' => 1,
            ),
            117 =>
            array (
                'id' => 122,
                'description' => 'Water/Booster Pumps ',
                'template_id' => 1,
            ),
            118 =>
            array (
                'id' => 123,
                'description' => 'Wet Riser',
                'template_id' => 1,
            ),
            119 =>
            array (
                'id' => 125,
                'description' => 'Incoming Mains',
                'template_id' => 8,
            ),
            120 =>
            array (
                'id' => 126,
            'description' => 'Cold Water Tap (Bib)',
                'template_id' => 4,
            ),
            121 =>
            array (
                'id' => 127,
            'description' => 'Bath (Hot and Cold Water Tap)',
                'template_id' => 16,
            ),
            122 =>
            array (
                'id' => 128,
            'description' => 'Bath (Mixer Tap)',
                'template_id' => 15,
            ),
            123 =>
            array (
                'id' => 129,
            'description' => 'Bulter Sink (Cold Water Tap)',
                'template_id' => 9,
            ),
            124 =>
            array (
                'id' => 130,
            'description' => 'Bulter Sink (Hot and Cold Water Tap)',
                'template_id' => 16,
            ),
            125 =>
            array (
                'id' => 131,
            'description' => 'Bulter Sink (Hot Water Tap)',
                'template_id' => 14,
            ),
            126 =>
            array (
                'id' => 132,
            'description' => 'Bulter Sink (Mixer Spray Tap)',
                'template_id' => 15,
            ),
            127 =>
            array (
                'id' => 133,
            'description' => 'Bulter Sink (Mixer Tap)',
                'template_id' => 15,
            ),
            128 =>
            array (
                'id' => 134,
            'description' => 'Sink (Cold Water Tap)',
                'template_id' => 9,
            ),
            129 =>
            array (
                'id' => 135,
            'description' => 'Sink (Hot and Cold Water Tap)',
                'template_id' => 16,
            ),
            130 =>
            array (
                'id' => 136,
            'description' => 'Sink (Hot Water Tap)',
                'template_id' => 14,
            ),
            131 =>
            array (
                'id' => 137,
            'description' => 'Sink (Mixer Spray Tap)',
                'template_id' => 15,
            ),
            132 =>
            array (
                'id' => 138,
            'description' => 'Sink (Mixer Tap)',
                'template_id' => 15,
            ),
            133 =>
            array (
                'id' => 139,
            'description' => 'Walk-in Bath (Hot and Cold Water Tap)',
                'template_id' => 16,
            ),
            134 =>
            array (
                'id' => 140,
            'description' => 'Walk-in Bath (Mixer Tap)',
                'template_id' => 15,
            ),
            135 =>
            array (
                'id' => 141,
            'description' => 'Wash Hand Basin (Cold Water Tap)',
                'template_id' => 9,
            ),
            136 =>
            array (
                'id' => 142,
            'description' => 'Wash Hand Basin (Hot and Cold Water Tap)',
                'template_id' => 16,
            ),
            137 =>
            array (
                'id' => 143,
            'description' => 'Wash Hand Basin (Hot Water Tap)',
                'template_id' => 14,
            ),
            138 =>
            array (
                'id' => 144,
            'description' => 'Wash Hand Basin (Mixer Spray Tap)',
                'template_id' => 15,
            ),
            139 =>
            array (
                'id' => 145,
            'description' => 'Wash Hand Basin (Mixer Tap)',
                'template_id' => 15,
            ),
            140 =>
            array (
                'id' => 146,
            'description' => 'Boiler (Combi)',
                'template_id' => 10,
            ),
            141 =>
            array (
                'id' => 147,
                'description' => 'Calorifier',
                'template_id' => 11,
            ),
            142 =>
            array (
                'id' => 148,
                'description' => 'Cylinder',
                'template_id' => 11,
            ),
            143 =>
            array (
                'id' => 149,
                'description' => 'Hot Water Storage Heater',
                'template_id' => 12,
            ),
            144 =>
            array (
                'id' => 151,
                'description' => 'Sluice Spray',
                'template_id' => 5,
            ),
            145 =>
            array (
                'id' => 152,
                'description' => 'UV Filter',
                'template_id' => 1,
            ),
        ));


    }
}
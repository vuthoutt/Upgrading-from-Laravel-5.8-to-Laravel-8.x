<?php

use Illuminate\Database\Seeder;

class CpEquipmentTemplatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cp_equipment_templates')->delete();

        \DB::table('cp_equipment_templates')->insert(array (
            0 =>
            array (
                'id' => 1,
                'decription' => 'Misc',
            ),
            1 =>
            array (
                'id' => 2,
                'decription' => 'Hot Services',
            ),
            2 =>
            array (
                'id' => 3,
                'decription' => 'Cold Storage',
            ),
            3 =>
            array (
                'id' => 4,
                'decription' => 'Cold Outlets',
            ),
            4 =>
            array (
                'id' => 5,
                'decription' => 'Mixer Outlets',
            ),
            5 =>
            array (
                'id' => 6,
                'decription' => 'Hot Outlets',
            ),
            6 =>
            array (
                'id' => 7,
                'decription' => 'Point of Use Heaters',
            ),
            7 =>
            array (
                'id' => 8,
                'decription' => 'Incoming Mains',
            ),
            8 =>
            array (
                'id' => 9,
                'decription' => 'Combined Outlets Cold',
            ),
            9 =>
            array (
                'id' => 10,
            'decription' => 'Boiler (Combi)',
            ),
            10 =>
            array (
                'id' => 11,
                'decription' => 'Calorifier/Cylinder',
            ),
            11 =>
            array (
                'id' => 12,
                'decription' => 'Hot Water Storage Heater',
            ),
            12 =>
            array (
                'id' => 13,
                'decription' => 'Instant Water Heater',
            ),
            13 =>
            array (
                'id' => 14,
                'decription' => 'Combined Outlets Hot',
            ),
            14 =>
            array (
                'id' => 15,
                'decription' => 'Combined Outlets Mixer',
            ),
            15 =>
            array (
                'id' => 16,
                'decription' => 'Combined Outlets Hot and Cold',
            ),
            16 =>
            array (
                'id' => 17,
            'decription' => 'Thermostatic Mixing Valve (TMV)',
            ),
        ));


    }
}